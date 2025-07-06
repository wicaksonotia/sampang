<?php

class ApiController extends Controller
{
    public function actionRetribusiPerBulan()
    {
        $options = array(
            "http" => array(
                "method" => "GET",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $tahun = $data['tahun'];
        // $tahun = 2022;
        $total = 0;
        for ($i = 1; $i <= 12; $i++) {
            $criteriaRetribusiBulan = new CDbCriteria();
            $criteriaRetribusiBulan->select = "SUM(total) AS total";
            $criteriaRetribusiBulan->addCondition("bulan = " . $i);
            $criteriaRetribusiBulan->addCondition("tahun = " . $tahun);
            $dataRetribusiBulan = VLapPad::model()->find($criteriaRetribusiBulan);
            if (!empty($dataRetribusiBulan)) {
                $total += $dataRetribusiBulan->total;
                $totalRetribusi[] = array(
                    "bulan" => Yii::app()->params['bulanArrayInd'][$i - 1],
                    "total_retribusi" => number_format($dataRetribusiBulan->total, 0, ",", ".")
                );
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(
            array(
                'status' => "sukses",
                'tahun' => $tahun,
                'total' => number_format($total, 0, ",", "."),
                'data' => $totalRetribusi,
            )
        );
    }

    public function actionRetribusiPerHari()
    {
        $options = array(
            "http" => array(
                "method" => "GET",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];
        $kalender = CAL_GREGORIAN;
        $tanggal = (int)cal_days_in_month($kalender, $bulan, $tahun);
        $total = 0;
        for ($i = 1; $i < $tanggal; $i++) {
            $criteriaRetribusiBulan = new CDbCriteria();
            $criteriaRetribusiBulan->select = "SUM(total) AS total";
            $criteriaRetribusiBulan->addCondition("tanggal = " . $i);
            $criteriaRetribusiBulan->addCondition("bulan = " . $bulan);
            $criteriaRetribusiBulan->addCondition("tahun = " . $tahun);
            $dataRetribusiBulan = VLapPad::model()->find($criteriaRetribusiBulan);
            if (!empty($dataRetribusiBulan)) {
                $total += $dataRetribusiBulan->total;
                $format_date = $i . "-" . $bulan . "-" . $tahun;
                $format_date = date_create_from_format('d-m-Y', $format_date);
                $format_date = date_format($format_date, 'Y-m-d');
                $format_date = strtotime($format_date);
                if (date('w', $format_date) !== '6' && date('w', $format_date) !== '0') {
                    $totalRetribusi[] = array(
                        "tanggal" => $i,
                        "total_retribusi" => number_format($dataRetribusiBulan->total, 0, ",", ".")
                    );
                }
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(
            array(
                'status' => "sukses",
                'bulan' => Yii::app()->params['bulanArrayInd'][$bulan - 1],
                'tahun' => $tahun,
                'total' => number_format($total, 0, ",", "."),
                'data' => $totalRetribusi,
            )
        );
    }

    /**
     * API KEMENTRIAN
     */
    public function actionApiStatusPenerbitan()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'statuspenerbitan';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_dataissuance";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $issuance_id = $value['issuance_id'];
                $issuance_code = $value['issuance_code'];
                $issuance_name = $value['issuance_name'];
                $issuance_desc = $value['issuance_desc'];
                $sql = "INSERT INTO master_dataissuance VALUES('$issuance_id','$issuance_code','$issuance_name','$issuance_desc')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiKelasJalan()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'kelasjalan';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_dataklsjln";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $kelasjalan_id = $value['kelasjalan_id'];
                $kelasjalan_code = $value['kelasjalan_code'];
                $kelasjalan_name = $value['kelasjalan_name'];
                $kelasjalan_desc = $value['kelasjalan_desc'];
                $muatan_sumbu_terberat = $value['muatan_sumbu_terberat'];
                $vehicle_length = $value['vehicle_length'];
                $vehicle_height = $value['vehicle_height'];
                $vehicle_width = $value['vehicle_width'];
                $sql = "INSERT INTO master_dataklsjln VALUES('$kelasjalan_id','$kelasjalan_code','$kelasjalan_name','$kelasjalan_desc','$muatan_sumbu_terberat','$vehicle_length','$vehicle_height','$vehicle_width')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiBahanBakar()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'fuel';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_datafuel";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $fuel_id = $value['fuel_id'];
                $fuel_name = $value['fuel_name'];
                $fuel_desc = $value['fuel_desc'];
                $sql = "INSERT INTO master_datafuel VALUES('$fuel_id','$fuel_name','$fuel_desc')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiMerk()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'merk';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_databrand";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $vehicle_brand_id = $value['vehicle_brand_id'];
                $vehicle_brand_code = $value['vehicle_brand_code'];
                $vehicle_brand_name = str_replace("'", "`", $value['vehicle_brand_name']);
                $vehicle_brand_desc = str_replace("'", "`", $value['vehicle_brand_desc']);
                $sql = "INSERT INTO master_databrand VALUES('$vehicle_brand_id','$vehicle_brand_code','$vehicle_brand_name','$vehicle_brand_desc')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiVarianType()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'variantype';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            // $sql = "TRUNCATE TABLE master_datavariantype";
            // Yii::app()->db->createCommand($sql)->execute();
            // foreach ($array['data'] as $key => $value) {
            //     $vehicle_varian_type_id = $value['vehicle_varian_type_id'];
            //     $vehicle_brand_id = $value['vehicle_brand_id'];
            //     $vehicle_varian_type_code = $value['vehicle_varian_type_code'];
            //     $vehicle_varian_type_name = str_replace("'", "`", $value['vehicle_varian_type_name']);
            //     $vehicle_varian_type_desc = str_replace("'", "`", $value['vehicle_varian_type_desc']);
            //     $sql = "INSERT INTO master_datavariantype VALUES('$vehicle_varian_type_id','$vehicle_brand_id','$vehicle_varian_type_code','$vehicle_varian_type_name','$vehicle_varian_type_desc')";
            //     Yii::app()->db->createCommand($sql)->execute();
            // }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiVarian()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'varian';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            // $sql = "TRUNCATE TABLE master_variandata";
            // Yii::app()->db->createCommand($sql)->execute();
            // foreach ($array['data'] as $key => $value) {
            //     $vehicle_varian_id = $value['vehicle_varian_id'];
            //     $vehicle_varian_type_id = $value['vehicle_varian_type_id'];
            //     $vehicle_varian_code = $value['vehicle_varian_code'];
            //     $vehicle_varian_name = str_replace("'", "`", $value['vehicle_varian_name']);
            //     $vehicle_varian_desc = str_replace("'", "`", $value['vehicle_varian_desc']);
            //     $sql = "INSERT INTO master_variandata VALUES('$vehicle_varian_id','$vehicle_varian_type_id','$vehicle_varian_code','$vehicle_varian_name','$vehicle_varian_desc')";
            //     Yii::app()->db->createCommand($sql)->execute();
            // }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiVehicleType()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'vehicletype';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_datavehicletype";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $vehicle_type_id = $value['vehicle_type_id'];
                $vehicle_type_code = $value['vehicle_type_code'];
                $vehicle_type_name = str_replace("'", "`", $value['vehicle_type_name']);
                $vehicle_type_desc = str_replace("'", "`", $value['vehicle_type_desc']);
                $sql = "INSERT INTO master_datavehicletype VALUES('$vehicle_type_id','$vehicle_type_code','$vehicle_type_name','$vehicle_type_desc')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiSubVehicleType()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'subvehicletype';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_datasubvehicle";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $vehicle_sub_id = $value['vehicle_sub_id'];
                $vehicle_type_id = $value['vehicle_type_id'];
                $vehicle_sub_code = $value['vehicle_sub_code'];
                $vehicle_sub_name = str_replace("'", "`", $value['vehicle_sub_name']);
                $vehicle_sub_desc = str_replace("'", "`", $value['vehicle_sub_desc']);
                $sql = "INSERT INTO master_datasubvehicle VALUES('$vehicle_sub_id','$vehicle_type_id','$vehicle_sub_code','$vehicle_sub_name','$vehicle_sub_desc')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiPegawai()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'pegawai';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_dataemployee";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $job_type_id = $value['job_type_id'];
                $job_type_code = $value['job_type_code'];
                $job_type_name = $value['job_type_name'];
                $job_id = $value['job_id'];
                $job_code = $value['job_code'];
                $job_name = $value['job_name'];
                $user_id = $value['user_id'];
                $identity_number = $value['identity_number'];
                $full_name = str_replace("'", "`", $value['full_name']);
                $pangkat = $value['pangkat'];
                $email = $value['email'];
                $phone_number = $value['phone_number'];
                $address = str_replace("'", "`", $value['address']);
                $sign_active = $value['sign_active'];
                $sign1 = $value['sign1'];
                $sign2 = $value['sign2'];
                $sign3 = $value['sign3'];
                $job_active = $value['job_active'] == 1 ? 'true' : 'false';
                $sql = "INSERT INTO master_dataemployee VALUES($job_type_id,'$job_type_code','$job_type_name',$job_id,'$job_code','$job_name',$user_id,'$identity_number','$full_name','$pangkat','$email','$phone_number','$address',$sign_active,'$sign1','$sign2','$sign3',$job_active)";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiArea()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'area';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            $sql = "TRUNCATE TABLE master_dataarea";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($array['data'] as $key => $value) {
                $area_id = $value['area_id'];
                $area_code = $value['area_code'];
                $area_name = $value['area_name'];
                $area_address = $value['area_address'];
                $area_email = $value['area_email'];
                $area_pic = $value['area_pic'];
                $area_telp = $value['area_telp'];
                $area_active = $value['area_active'] == 1 ? 'true' : 'false';
                $area_logo_active = $value['area_logo_active'];
                $logo = $value['logo'];
                $logo_gray = $value['logo_gray'];
                $sql = "INSERT INTO master_dataarea VALUES($area_id,'$area_code','$area_name','$area_address','$area_email','$area_pic','$area_telp','$area_active','$area_logo_active','$logo','$logo_gray')";
                Yii::app()->db->createCommand($sql)->execute();
            }
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionApiVta()
    {
        $options = array(
            "http" => array(
                "method" => "POST",
                "header" => "Content-Type: application/json",
            )
        );
        $response = file_get_contents('php://input', false, stream_context_create($options));
        $data = json_decode($response, true);
        $url = Yii::app()->params['urlApi'];
        $token = Yii::app()->params['tokenApi'];
        $prefix = 'vta';
        $data = array(
            'token' => $token,
            'prefix' => $prefix,

        );
        $kirim = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultQris = curl_exec($ch);

        $array = json_decode(json_decode(json_encode($resultQris), true), true);
        $status = $array['status'];
        if ($status) {
            echo "<pre>";
            var_export($array['data']);
            echo "</pre>";
        } else {
            echo $array['message'];
        }
        curl_close($ch);
    }

    public function actionUpdateData()
    {
        // $a = VKendaraan::model()->findAll();
        // foreach ($a as $key => $value) {
        //     BAHAN BAKAR
        //     if ($value->bahan_bakar == "SOLAR") {
        //         $sql = "UPDATE tbl_kendaraan SET fuel_id=2 where id_kendaraan = $value->id_kendaraan";
        //         Yii::app()->db->createCommand($sql)->execute();
        //     } else if ($value->bahan_bakar == "BENSIN") {
        //         $sql = "UPDATE tbl_kendaraan SET fuel_id=1 where id_kendaraan = $value->id_kendaraan";
        //         Yii::app()->db->createCommand($sql)->execute();
        //     } else {
        //         $sql = "UPDATE tbl_kendaraan SET fuel_id=0 where id_kendaraan = $value->id_kendaraan";
        //         Yii::app()->db->createCommand($sql)->execute();
        //         $sql = "UPDATE tbl_type SET bahan_bakar='-' where id_kendaraan = $value->id_kendaraan";
        //         Yii::app()->db->createCommand($sql)->execute();
        //     }


        //     KELAS JALAN
        // if ($value->kls_jln == "I") {
        //     $sql = "UPDATE tbl_kendaraan SET kelasjalan_id=1 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        //     $sql = "UPDATE tbl_type SET kls_jln='KELAS I' where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // }
        // if ($value->kls_jln == "II") {
        //     $sql = "UPDATE tbl_kendaraan SET kelasjalan_id=2 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        //     $sql = "UPDATE tbl_type SET kls_jln='KELAS II' where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // }
        // if ($value->kls_jln == "III") {
        //     $sql = "UPDATE tbl_kendaraan SET kelasjalan_id=54 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        //     $sql = "UPDATE tbl_type SET kls_jln='KELAS III' where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // }

        // MERK
        // if ($value->merk == "UD. TRUCKS") {
        // if ($value->bahan_bakar == "SOLAR") {
        //     $sql = "UPDATE tbl_kendaraan SET merk='NISSAN DIESEL', vehicle_brand_id=1448 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // } else {
        //     $sql = "UPDATE tbl_kendaraan SET merk='NISSAN', vehicle_brand_id=32 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // }
        //     $sql = "UPDATE tbl_kendaraan SET merk='UD TRUCKS', vehicle_brand_id=1346 where id_kendaraan = $value->id_kendaraan";
        //     Yii::app()->db->createCommand($sql)->execute();
        // }
        // }
    }
}
