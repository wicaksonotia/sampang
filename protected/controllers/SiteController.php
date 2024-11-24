<?php

class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    //    public function init() {
    //        $this->pageTitle = 'SICTI - Inventory Swap';
    //        $this->defaultAction = 'swapList';
    //    }
    public function actionPkb()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->pageTitle = 'WELCOME';
        if (Yii::app()->user->isGuest) {
            $this->actionLogin();
        } else {
            //            $this->render('welcome');
            if (Yii::app()->user->getId() == 5 || Yii::app()->user->getId() == 11) {
                $this->redirect(array('/retribusi/Validasi'));
            } else {
                $this->redirect(array('/retribusi'));
            }
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $this->layout = '/';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                // $this->redirect(Yii::app()->user->returnUrl);
                $this->redirect(Yii::app()->homeUrl . 'pkb');
        }
        // display the login form
        $this->render('main_login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl . 'pkb');
    }

    /**
     * HOME
     * CHANGE PASSWORD
     * Report
     */
    public function actionHome()
    {
        $this->layout = '//layouts/main_android';
        $this->pageTitle = 'DASHBOARD';
        $year = Yii::app()->params['tahunGrafik'];
        //TOTAL HEADER
        $tgl = date('d-M-y');
        $totalRetribusi = 0;
        $totalKendaraan = 0;
        $totalRetribusiBulan = 0;
        $totalRetribusiTahun = 0;
        $criteriatotalRetribusi = new CDbCriteria();
        $criteriatotalRetribusi->addCondition("tgl_pad = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        $dataPad = VLapPad::model()->find($criteriatotalRetribusi);
        if (!empty($dataPad)) {
            $totalRetribusi = $dataPad->total;
        }
        if (!empty($dataPad)) {
            $totalKendaraan = $dataPad->jum_kend;
        }

        $criteriaRetribusiBulan = new CDbCriteria();
        $criteriaRetribusiBulan->select = "SUM(total) AS total";
        $criteriaRetribusiBulan->addCondition("bulan = " . date('n'));
        $criteriaRetribusiBulan->addCondition("tahun = " . date('Y'));
        $dataRetribusiBulan = VLapPad::model()->find($criteriaRetribusiBulan);
        if (!empty($dataRetribusiBulan)) {
            $totalRetribusiBulan = $dataRetribusiBulan->total;
        }

        $criteriaRetribusiTahun = new CDbCriteria();
        $criteriaRetribusiTahun->select = "SUM(total) AS total";
        $criteriaRetribusiTahun->addCondition("tahun = " . date('Y'));
        $dataRetribusiTahun = VLapPad::model()->find($criteriaRetribusiTahun);
        if (!empty($dataRetribusiTahun)) {
            $totalRetribusiTahun = $dataRetribusiTahun->total;
        }
        //        $totalKendaraanU = TblDaftar::model()->totalKedatanganKendaraan($tgl, 'true');
        //        $totalKendaraanBu = TblDaftar::model()->totalKedatanganKendaraan($tgl, 'false');
        ////        $totalKendaraan = $totalKendaraanU + $totalKendaraanBu;

        $mobilBarangU = TblDaftar::model()->totalKendaraan(0, $tgl, 'true');
        $mobilBarangBu = TblDaftar::model()->totalKendaraan(0, $tgl, 'false');
        $mobilPenumpangU = TblDaftar::model()->totalKendaraan(1, $tgl, 'true');
        $mobilPenumpangBu = TblDaftar::model()->totalKendaraan(1, $tgl, 'false');
        $mobilBisU = TblDaftar::model()->totalKendaraan(2, $tgl, 'true');
        $mobilBisBu = TblDaftar::model()->totalKendaraan(2, $tgl, 'false');
        $mobilGandenganU = TblDaftar::model()->totalKendaraan(4, $tgl, 'true') + TblDaftar::model()->totalKendaraan(5, $tgl, 'true');
        $mobilGandenganBu = TblDaftar::model()->totalKendaraan(4, $tgl, 'false') + TblDaftar::model()->totalKendaraan(5, $tgl, 'true');
        //        $totalKendaraan = $mobilBarangU + $mobilBarangBu + $mobilPenumpangU + $mobilPenumpangBu + $mobilBisU + $mobilBisBu;
        //
        //TOTAL KENDARAAN LULUS
        $totalLulusU = TblDaftar::model()->totalKelulusanKendaraan('true', $tgl, 'true');
        $totalLulusBu = TblDaftar::model()->totalKelulusanKendaraan('true', $tgl, 'false');
        //TOTAL KENDARAAN TIDAK LULUS
        $totalTidakLulusU = TblDaftar::model()->totalKelulusanKendaraan('false', $tgl, 'true');
        $totalTidakLulusBu = TblDaftar::model()->totalKelulusanKendaraan('false', $tgl, 'false');

        $this->render('index', array(
            //            'dataEmployee' => $employee,
            'year' => $year,
            'totalRetribusi' => number_format($totalRetribusi),
            'totalRetribusiBulan' => number_format($totalRetribusiBulan),
            'totalRetribusiTahun' => number_format($totalRetribusiTahun),
            'totalKendaraan' => $totalKendaraan,

            'totalLulusU' => $totalLulusU,
            'totalTidakLulusU' => $totalTidakLulusU,
            'totalLulusBu' => $totalLulusBu,
            'totalTidakLulusBu' => $totalTidakLulusBu,

            'mobilBarangU' => $mobilBarangU,
            'mobilBarangBu' => $mobilBarangBu,
            'mobilPenumpangU' => $mobilPenumpangU,
            'mobilPenumpangBu' => $mobilPenumpangBu,
            'mobilBisU' => $mobilBisU,
            'mobilBisBu' => $mobilBisBu,
            'mobilGandenganU' => $mobilGandenganU,
            'mobilGandenganBu' => $mobilGandenganBu,
        ));
    }

    public function actionFormChangePassword()
    {
        $this->pageTitle = 'UBAH PASSWORD';
        $this->render('form_change_password');
    }

    public function actionChangePassword()
    {
        $id = $_POST['employee_id'];
        $new_password = md5(strtolower($_POST['new_password']));
        $sql = "UPDATE tbl_user SET password = '$new_password' WHERE id_user = $id ";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function actionRuncron()
    {
        $sql = "SELECT id_kendaraan, no_uji, tgl_mati_uji, no_telp, tgl_mati_uji::date - now()::date AS selisih FROM v_kendaraan where stts_kirim_wa = false and no_telp <> ''";
        $data_kendaraan = Yii::app()->db->createCommand($sql)->queryAll();
        $no_hp = "";
        $id_kendaraan = "";
        foreach ($data_kendaraan as $data) :
            if ($data['selisih'] <= 7) {
                $no_hp = $data['no_telp'];
                $id_kendaraan = $data['id_kendaraan'];
            }
            if (!empty($no_hp)) {
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                        'to_name' => 'UjiKIR',
                        'to_number' => $no_hp,
                        'message_template_id' => '0ffb59f8-16c0-4241-8ae9-ac128330a0ff',
                        'channel_integration_id' => '509f8fcb-b29e-47dc-be5b-875e7b74ad6d',
                        'language' => [
                            'code' => 'en'
                        ],
                        'parameters' => [
                            '' => ''
                        ]
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer on2HUkR9P6rJkaVDy0iSYuvjW6IMLucWO0B5vP3GLaU",
                        "Content-Type: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    $respon = "cURL Error #:" . $err;
                } else {
                    $respon =  $response;
                }
                // echo $respon;
                $file = Yii::getPathOfAlias('webroot') . '/log_wa.txt';
                $text = file_get_contents($file);
                $data = date('d/m/Y H:i:s') . " = " . $respon . "\n";
                $text .= $data;
                file_put_contents($file, $text);

                $sql = "update tbl_kendaraan set stts_kirim_wa = true where id_kendaraan in ($id_kendaraan)";
                Yii::app()->db->createCommand($sql)->execute();
            }
        endforeach;
    }

    public function actionRuncronOri()
    {
        // $sql = "SELECT id_kendaraan, no_uji, tgl_mati_uji, no_telp, tgl_mati_uji::date - now()::date AS selisih FROM v_kendaraan where stts_kirim_wa = false and no_telp <> ''";
        // $data_kendaraan = Yii::app()->db->createCommand($sql)->queryAll();
        // $result_nohp = array();
        // foreach ($data_kendaraan as $data) :
        //     if ($data['selisih'] <= 7) {
        //         $result_nohp[] = $data['no_telp'];
        //         $result_idkendaraan[] = $data['id_kendaraan'];
        //     }
        // endforeach;
        // $no_hp = implode(",", $result_nohp);
        // $id_kendaraan = implode(",", $result_idkendaraan);
        // if (!empty($no_hp)) {

        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'https://api.fonnte.com/send',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => array(
        //             'target' => $no_hp,
        //             'message' => 'Pelanggan Yth, Demi kenyamanan anda segera uji kendaraan bermotor anda di UPTD Pengujian Kendaraan Bermotor Dinas Perhubungan Kabupaten Sampang.',
        //             'countryCode' => '62', //optional
        //         ),
        //         CURLOPT_HTTPHEADER => array(
        //             'Authorization: q#jK6VTGKEQEvK9yGuce' //change TOKEN to your actual token
        //         ),
        //     ));
        //     $response = curl_exec($curl);
        //     $err = curl_error($curl);
        //     curl_close($curl);
        //     if ($err) {
        //         $respon = "cURL Error #:" . $err;
        //     } else {
        //         $respon =  $response;
        //     }

        //     $sql = "update tbl_kendaraan set stts_kirim_wa = true where id_kendaraan in ($id_kendaraan)";
        //     Yii::app()->db->createCommand($sql)->execute();
        //     $file = Yii::getPathOfAlias('webroot') . '/log_wa.txt';
        //     $text = file_get_contents($file);
        //     $data = date('d/m/Y H:i:s') . " = " . $respon . "\n";
        //     $text .= $data;
        //     file_put_contents($file, $text);
        // }

        // $no_hp = "081333399373";
        // $no_hp = "081241773548";
        $no_hp = "085646774333";
        $message = "*_This is an auto generated message. Please don't reply._*\r\n\r\n";
        $message .= "Pelanggan Yth, Demi kenyamanan anda segera uji kendaraan bermotor anda:\r\n\r\n";
        $message .= "NO UJI : *SB 123456 K*\r\n";
        $message .= "NO KENDARAAN : *L 1234 MN*\r\n";
        $message .= "TGL MATI UJI : *05 Jun 2024*\r\n\r\n";
        $message .= "UPTD Pengujian Kendaraan Bermotor Dinas Perhubungan Surabaya.";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $message,
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: q#jK6VTGKEQEvK9yGuce' //change TOKEN to your actual token
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;
    }
}
