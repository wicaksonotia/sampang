<?php

class RemController extends Controller
{

    public $layout = '//layouts/main_top';

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    //    public function actionIndex() {
    //        $this->pageTitle = 'Rem';
    //        $this->render('index');
    //    }

    public function actionReloadData()
    {
        $tahun_kendaraan = intval($_POST['tahun_kendaraan']);
        $jbb = intval($_POST['jbb']);
        $tahun_sekarang = intval(date('Y'));

        if (($tahun_sekarang - $tahun_kendaraan) <= 5) {
            $sumbu1 = rand(75, 80);
            $kiri1 = 47 / 100 * $sumbu1;
            $kanan1 = 53 / 100 * $sumbu1;
            $sumbu2 = rand(75, 80);
            $kiri2 = 47 / 100 * $sumbu2;
            $kanan2 = 53 / 100 * $sumbu2;
            $sumbu3 = rand(75, 80);
            $kiri3 = 47 / 100 * $sumbu3;
            $kanan3 = 53 / 100 * $sumbu3;
            $sumbu4 = rand(75, 80);
            $kiri4 = 47 / 100 * $sumbu4;
            $kanan4 = 53 / 100 * $sumbu4;
            $selsumbu1 = rand(2, 3);
            $selsumbu2 = rand(2, 3);
            $selsumbu3 = rand(2, 3);
            $selsumbu4 = rand(2, 3);
        } else {
            $sumbu1 = rand(70, 75);
            $kiri1 = 47 / 100 * $sumbu1;
            $kanan1 = 53 / 100 * $sumbu1;
            $sumbu2 = rand(70, 75);
            $kiri2 = 47 / 100 * $sumbu2;
            $kanan2 = 53 / 100 * $sumbu2;
            $sumbu3 = rand(70, 75);
            $kiri3 = 47 / 100 * $sumbu3;
            $kanan3 = 53 / 100 * $sumbu3;
            $sumbu4 = rand(70, 75);
            $kiri4 = 47 / 100 * $sumbu4;
            $kanan4 = 53 / 100 * $sumbu4;
            $selsumbu1 = rand(3, 4);
            $selsumbu2 = rand(3, 4);
            $selsumbu3 = rand(3, 4);
            $selsumbu4 = rand(3, 4);
        }
        $range_parkir_tangan = rand(20, 40);
        $range_parkir_kaki = rand(20, 40);
        $nilai_rem_parkir_tangan = (($range_parkir_tangan / 100) * $jbb) / 2;
        $nilai_rem_parkir_kaki = (($range_parkir_kaki / 100) * $jbb) / 2;
        $parkir_kiri_tangan = round($nilai_rem_parkir_tangan);
        $parkir_kanan_tangan = round($nilai_rem_parkir_tangan);
        $parkir_kiri_kaki = round($nilai_rem_parkir_kaki);
        $parkir_kanan_kaki = round($nilai_rem_parkir_kaki);
        $data['bsb1'] = $sumbu1;
        $data['bsb2'] = $sumbu2;
        $data['bsb3'] = $sumbu3;
        $data['bsb4'] = $sumbu4;
        $data['bsel1'] = $selsumbu1;
        $data['bsel2'] = $selsumbu2;
        $data['bsel3'] = $selsumbu3;
        $data['bsel4'] = $selsumbu4;
        $data['kiri1'] = $kiri1;
        $data['kiri2'] = $kiri2;
        $data['kiri3'] = $kiri3;
        $data['kiri4'] = $kiri4;
        $data['kanan1'] = $kanan1;
        $data['kanan2'] = $kanan2;
        $data['kanan3'] = $kanan3;
        $data['kanan4'] = $kanan4;
        $data['parkir_kiri_tangan'] = $parkir_kiri_tangan;
        $data['parkir_kanan_tangan'] = $parkir_kanan_tangan;
        $data['parkir_kiri_kaki'] = $parkir_kiri_kaki;
        $data['parkir_kanan_kaki'] = $parkir_kanan_kaki;

        echo json_encode($data);
    }

    public function countDecimals($x)
    {
        return strlen(substr(strrchr($x + "", "."), 1));
    }

    public function random($min, $max)
    {
        $decimals = max($this->countDecimals($min), $this->countDecimals($max));
        $factor = pow(10, $decimals);
        return rand($min * $factor, $max * $factor) / $factor;
        //        return mt_rand($min * $mul, $max * $mul) / $mul;
    }

    public function actionListGrid()
    {
        $kategori = $_POST['textCategory'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'numerator';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        $result = VCisBrake::model()->findAll($criteria);

        $dataJson = array();
        foreach ($result as $p) {
            $dataJson[] = array(
                "id_hasil_uji" => $p->id_hasil_uji,
                "no_antrian" => $p->no_antrian,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "merk" => $p->merk,
                "tipe" => $p->tipe,
                "tahun" => $p->tahun,
                "nm_komersil" => $p->nm_komersil,
                "konsumbu" => $p->konsumbu,
                "bsumbu1" => $p->bsumbu1,
                "bsumbu2" => $p->bsumbu2,
                "bsumbu3" => $p->bsumbu3,
                "bsumbu4" => $p->bsumbu4,
                "jbb" => $p->kemjbb,
                "id_jns_kend" => $p->id_jns_kend,
                "nm_uji" => $p->nm_uji,
                "numerator" => $p->numerator,
                "numerator_hari" => $p->numerator_hari
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VCisBrake::model()->count($criteria),
                'rows' => $dataJson,
            )
        );

        Yii::app()->end();
    }

    public function actionProses()
    {
        $idhasil = $_POST['id_hasil_uji'];
        $variabel = $_POST['variabel'];
        $username = $_POST['username'];
        $kategori_rem = $_POST['kategori_rem'];
        $query = "select update_cis_brake('$variabel',$idhasil,'$username');";
        Yii::app()->db->createCommand($query)->execute();
    }
}
