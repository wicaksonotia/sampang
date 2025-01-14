<?php

class DefaultController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    /* =====================================================================
     * RETRIBUSI
      ===================================================================== */

    public function actionIndex()
    {
        $this->pageTitle = 'RETRIBUSI';
        $tbl_uji = TblUji::model()->getUjiReribusi();
        //==============================================
        $tbl_jns_kend = TblJnsKend::model()->findAll();
        $this->render('form_retribusi', array(
            'tbl_uji' => $tbl_uji,
            'tbl_jns_kend' => $tbl_jns_kend
        ));
    }

    public function actionIndexCoba()
    {
        $this->pageTitle = 'RETRIBUSI';
        $tbl_uji = TblUji::model()->getUjiReribusi();
        //==============================================
        $tbl_jns_kend = TblJnsKend::model()->findAll();
        $this->render('form_retribusi_coba', array(
            'tbl_uji' => $tbl_uji,
            'tbl_jns_kend' => $tbl_jns_kend
        ));
    }

    public function actionDetailNoSb()
    {
        $var_search = strtoupper($_POST['no_sb']);
        $pilihan = $_POST['pilihan'];
        $criteria = new CDbCriteria();
        if ($pilihan == 'no_chasis') {
            $criteria->addCondition("replace(LOWER(no_chasis),' ','') like replace(LOWER('" . $var_search . "'),' ','')");
        } else if ($pilihan == 'no_mesin') {
            $criteria->addCondition("replace(LOWER(no_mesin),' ','') like replace(LOWER('" . $var_search . "'),' ','')");
        } else {
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $var_search . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $var_search . "'),' ',''))");
        }
        $result = VKendaraan::model()->find($criteria);
        if (!empty($result)) {
            $jnsKend = TblJnsKend::model()->findByAttributes(array('id_jns_kend' => $result->id_jns_kend));
        }
        if (!empty($result) && !empty($jnsKend)) {
            $tgl_mati_uji = date('d/m/Y', strtotime($result->tgl_mati_uji));
            $criteria = new CDbCriteria();
            $criteria->addCondition("id_kendaraan = $result->id_kendaraan");
            $criteria->order = 'tgl_uji DESC';
            $dtRiwayat = VRiwayat::model()->find($criteria);
            if (!empty($dtRiwayat)) {
                $tgl_mati_uji = date('d/m/Y', strtotime($dtRiwayat->tglmati));
            } else {
                $tgl_mati_uji = date('d/m/Y');
            }
            $data = array(
                "no_uji" => $result->no_uji,
                "id_kendaraan" => $result->id_kendaraan,
                "no_kendaraan" => $result->no_kendaraan,
                "id_jns_kend" => $result->id_jns_kend,
                "jns_kend" => $result->jns_kend,
                "nama_pemilik" => $result->nama_pemilik,
                "alamat" => $result->alamat,
                "no_identitas" => $result->no_identitas,
                "no_telp" => $result->no_telp,
                "no_mesin" => $result->no_mesin,
                "no_chasis" => $result->no_chasis,
                "jbb" => $result->kemjbb,
                "identitas" => $result->identitas,
                "tgl_mati" => $tgl_mati_uji,
                "img_scan" => $result->img_scan
            );
        } else {
            $data = 0;
        }
        echo json_encode($data);
    }

    public function actionPenjumlahanTanggal()
    {
        $tanggal_pengujian = $_POST['tanggal_pengujian'];
        $data['tgl_mati_uji'] = date('d-M-y', strtotime('+6 month', strtotime($tanggal_pengujian)));
        echo json_encode($data);
    }

    public function actionGetListSelect()
    {
        $pilih = $_POST['pilih'];
        $option = '';
        switch ($pilih) {
            case 'jenis_kendaraan':
                $tbl_jns_kend = TblJnsKend::model()->findAll();
                foreach ($tbl_jns_kend as $jns_kend) :
                    $option .= "<option value='$jns_kend->id_jns_kend'>$jns_kend->jns_kend</pilih>";
                endforeach;
                break;
            case 'jenis_uji':
                $tbl_uji = TblUji::model()->getEditRetribusi();
                foreach ($tbl_uji as $uji) :
                    $option .= "<option value='$uji->id_uji'>$uji->nm_uji</pilih>";
                endforeach;
                break;
            case 'buku':
                $tbl_bk_uji = TblBkMasuk::model()->findAll();
                foreach ($tbl_bk_uji as $bk_uji) :
                    $option .= "<option value='$bk_uji->id_bk_masuk'>$bk_uji->bk_masuk</pilih>";
                endforeach;
                break;
        }
        echo $option;
    }

    public function actionUpdateRetribusi()
    {
        $ex_idret_tglmati = explode('_', $_POST['dlg_idret_tglmati']);
        $id_retribusi = $ex_idret_tglmati[0];
        $tgl_mati = $ex_idret_tglmati[1];
        $jns_kend = $_POST['dlg_jns_kend'];
        $pilih_kategori = $_POST['pilih_kategori'];
        $kategori = 0;
        $textJbb = 0;
        $id_kendaraan = 0;
        $wilayah_asal = 'SMPNG';
        if ($pilih_kategori == 'tgluji') {
            $tgl_mati = date("m/d/Y", strtotime($_POST['ganti_tgl_uji']));
        } elseif ($pilih_kategori == 'denda') {
            $tgl_mati = $_POST['ganti_tgl_mati'];
        } elseif ($pilih_kategori == 'replace') {
            $textCategory = $_POST['ganti_replace'];
            $criteria = new CDbCriteria();
            $criteria->addCondition("( (replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) OR (replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) )");
            $dtKend = TblKendaraan::model()->find($criteria);
            $id_kendaraan = $dtKend->id_kendaraan;
        } elseif ($pilih_kategori == 'jbb' and ($jns_kend != 4 or $jns_kend != 5)) {
            $textJbb = $_POST['ganti_jbb'];
        } elseif ($pilih_kategori == 'wilayah_asal') {
            $wilayah_asal = $_POST['wilayah_asal'];
        } else {
            $kategori = $_POST['kategori'];
        }

        // elseif ($pilih_kategori == 'jenis_uji' || $pilih_kategori == 'buku') {
        //     $kategori = $_POST['kategori'];
        // }
        $updateRetribusi = "Select edit_retribusi(" . $id_retribusi . ",'" . $tgl_mati . "','" . $pilih_kategori . "',$kategori, $textJbb, $id_kendaraan, '" . $wilayah_asal . "')";
        Yii::app()->db->createCommand($updateRetribusi)->query();
    }

    public function actionSaveform()
    {
        $form = $_POST['FORM'];
        $tanda_pengenal = $form['JENIS_PENGENAL'];
        $userlogin = $form['USER_LOGIN'];
        $tgl_retribusi = date("m/d/Y");

        $JENIS_PENGUJIAN = $form['JENIS_PENGUJIAN'];
        $WILAYAH_ASAL = 'SMPNG';
        if ($JENIS_PENGUJIAN == 2 || $JENIS_PENGUJIAN == 4) {
            $WILAYAH_ASAL = empty($form['WILAYAH_ASAL']) ? 'SMPNG' : $form['WILAYAH_ASAL'];
        }
        $BUKU_UJI = $form['BUKU_UJI'];
        // $str = date('H:i:s Y/m/d');
        // $str = 'SPG ' . date('jnGis');
        // $nomer_uji = ($JENIS_PENGUJIAN == 8) ? strtoupper($str) : strtoupper($form['NO_STUK']);
        // $nomer_uji = strtoupper($form['NO_STUK']);
        // $var_no_uji = str_replace("'", " ", $nomer_uji);
        // $var_nomor_uji = preg_replace("/([[:alpha:]])([[:digit:]])/", "\\1 \\2", $var_no_uji);
        // $NO_STUK = preg_replace("/([[:digit:]])([[:alpha:]])/", "\\1 \\2", $var_nomor_uji);
        $NO_STUK = strtoupper(rtrim($form['NO_STUK']));

        $ID_KEND = empty($form['ID_KEND']) ? 0 : $form['ID_KEND'];
        $ID_KENDARAAN = $form['ID_KENDARAAN'];
        // $JBB = empty($form['JBB']) ? 0 : $form['JBB'];
        $TGLPENGUJIAN = DateTime::createFromFormat('d/m/Y', $form['TGL_PENGUJIAN']);
        $TGL_PENGUJIAN = $TGLPENGUJIAN->format('m/d/Y');
        $TGLMATIUJI = DateTime::createFromFormat('d/m/Y', $form['TGL_MATI_UJI']);
        $TGL_MATI_UJI = $TGLMATIUJI->format('m/d/Y');
        $nomer_kend = strtoupper($form['NO_KENDARAAN']);
        $var_no_kend = str_replace("'", " ", $nomer_kend);
        $var_nomor_kend = preg_replace("/([[:alpha:]])([[:digit:]])/", "\\1 \\2", $var_no_kend);
        $NO_KENDARAAN = preg_replace("/([[:digit:]])([[:alpha:]])/", "\\1 \\2", $var_nomor_kend);
        // $JENIS_KENDARAAN = $form['JENIS_KENDARAAN'];
        $NAMA_PEMILIK = str_replace("'", "`", strtoupper($form['NAMA_PEMILIK']));
        $ALAMAT = str_replace("'", "`", strtoupper($form['ALAMAT']));
        $NO_KTP = strtoupper($form['NO_KTP']);
        $NO_TELP = strtoupper($form['NO_TELP']);
        $NO_MESIN = strtoupper($form['NO_MESIN']);
        $NO_CHASIS = strtoupper($form['NO_CHASIS']);
        $KARTU_HILANG = strtoupper($form['KARTU_HILANG']) ?? '-';

        if ($JENIS_PENGUJIAN == 8 || $JENIS_PENGUJIAN == 4 || $JENIS_PENGUJIAN == 2) {
            $cekDataKendaraan = TblKendaraan::model()->findByAttributes(array('no_kendaraan' => $NO_KENDARAAN));
            if (empty($cekDataKendaraan)) {
                $ID_KENDARAAN = 0;
            }
        }
        //JIKA (ADA ID KENDARAAN DAN UJI PERTAMA) || (TIDAK ADA KENDARAAN DAN BERKALA)
        if (($ID_KENDARAAN != 0 && $JENIS_PENGUJIAN == 8) or ($ID_KENDARAAN == 0 && $JENIS_PENGUJIAN == 1)) {
            $result['ada'] = 'false';
            $result['message'] = "\"" . $NO_KENDARAAN . '" daftar <b>Jenis Pengujian</b> salah';
        } else {
            $data_kendaraan = $NO_STUK . '~' . $NO_KENDARAAN . '~' . $NO_CHASIS . '~' . $NO_MESIN . '~' . $ID_KENDARAAN;

            $data_uji = $BUKU_UJI . '~' . $JENIS_PENGUJIAN . '~' . $tgl_retribusi . '~' . $TGL_PENGUJIAN . '~' . $TGL_MATI_UJI . '~' . $WILAYAH_ASAL;

            $data_pemilik = $NAMA_PEMILIK . '~' . $ALAMAT . '~' . $NO_KTP . '~' . $NO_TELP . '~' . $tanda_pengenal . '~' . $KARTU_HILANG;
            $data_kuasa = '' . '~' . '' . '~' . '' . '~' . 'Orang/Pribadi' . '~' . 'false';

            //CEK DAFTAR HARI INI
            $criteria = new CDbCriteria();
            $criteria->addCondition('id_kendaraan = ' . $ID_KENDARAAN);
            $criteria->addCondition("tgl_uji =  '$TGL_PENGUJIAN'");
            $criteria->addCondition("id_uji =  1");
            $data = TblRetribusi::model()->find($criteria);
            //JIKA BELUM TERDAFTAR ATAU LAIN-LAIN ATAU MUTASI KELUAR ATAU NUMPANG KELUAR
            // if (empty($data) || $JENIS_PENGUJIAN == 5 || $JENIS_PENGUJIAN == 3 || $JENIS_PENGUJIAN == 10 || $JENIS_PENGUJIAN == 11) {
            if (empty($data)) {
                //LAIN-LAIN BEDA PENDAFTAR
                $td = 'false';
                $result['ada'] = 'true';
                $result['message'] = "\"" . $NO_STUK . '" berhasil didaftarkan';
                $inputRetribusi = "Select insert_retribusi( '" . $userlogin . "', '" . $data_kendaraan . "','" . $data_uji . "','" . $data_pemilik . "','" . $td . "','" . $data_kuasa . "')";
                Yii::app()->db->createCommand($inputRetribusi)->query();

                /**
                 * UNTUK UPLOAD SCAN
                 */
                require_once Yii::app()->basePath . '/extensions/jquery.fileuploader.php';
                // initialize FileUploader
                $dir = Yii::getPathOfAlias('webroot') . '/downloadsfile/';
                $FileUploader = new FileUploader('files', array(
                    'uploadDir' => $dir,
                    'title' => 'auto'
                ));

                // call to upload the files
                $dataUpload = $FileUploader->upload();

                // if uploaded and success
                if ($dataUpload['isSuccess'] && !empty($dataUpload['files'])) {
                    $uploadedFiles = $dataUpload['files'];

                    //GAMBAR KE-1
                    if (empty($uploadedFiles[0]['name'])) {
                        $base64_img_1 = '';
                    } else {
                        $img = $uploadedFiles[0]['name'];
                        $ext = $uploadedFiles[0]['extension'];
                        $image = $this->resize($dir . $img, 1280, 1024, false, $ext);
                        ob_start();
                        if ($ext == "jpg" || $ext == "jpeg") {
                            imagejpeg($image);
                        } else {
                            imagepng($image);
                        }
                        $contents = ob_get_contents();
                        ob_end_clean();

                        $base64_img_1 = base64_encode($contents);
                        unlink($dir . $img);
                    }

                    $query = "update tbl_kendaraan set img_scan = '$base64_img_1' where id_kendaraan = $ID_KENDARAAN";
                    Yii::app()->db->createCommand($query)->execute();
                }
            } else {
                $result['ada'] = 'false';
                $result['message'] = "\"" . $NO_STUK . '" sudah terdaftar hari ini';
            }
        }

        $result['buku_uji'] = $BUKU_UJI;
        $result['tgluji'] = date('d/m/Y');
        $result['tglmati'] = date('d/m/Y');
        echo json_encode($result);
    }

    private function resize($file, $w, $h, $crop = FALSE, $ext)
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        if ($ext == "jpg" || $ext == "jpeg") {
            $src = imagecreatefromjpeg($file);
        } else {
            $src = imagecreatefrompng($file);
        }
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public function actionValidasilistgridPetugas()
    {
        $ok = Yii::app()->baseUrl . "/images/icon_approve.png";
        $reject = Yii::app()->baseUrl . "/images/icon_reject.png";
        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $selectDate = strtoupper($_POST['selectDate']);
        $petugas = Yii::app()->session['username'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_retribusi';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;

        if (!empty($textCategory)) {
            if ($selectCategory == 'numerator') {
                $criteria->addCondition("$selectCategory = $textCategory");
            } else {
                $criteria->addCondition("( (replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) OR (replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) )");
            }
        }
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        if (!Yii::app()->user->isRole('Admin')) {
            $criteria->addCondition("penerima like '$petugas'");
        }
        $result = VValidasi::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $tgl_mati = TblRetribusi::model()->findByPk($p->id_retribusi)->tglmati;
            $bk_masuk = '';
            if ($p->id_bk_masuk != 1) {
                $bk_masuk = "(" . $p->bk_masuk . ")";
            }
            $dataJson[] = array(
                "id" => $p->id_retribusi,
                "bap" => $p->id_retribusi . "_" . $p->id_kendaraan . "_" . $p->id_uji,
                "ACTIONS" => $p->id_retribusi,
                "delete" => $p->id_retribusi,
                "idret_tglmati" => $p->id_retribusi . "_" . $tgl_mati . "_" . $p->id_jns_kend,
                "id_retribusi" => $p->id_retribusi,
                "gesekan" => $p->id_kendaraan . "_" . $p->id_uji . '_' . $p->id_retribusi,
                "numerator" => $p->numerator,
                "numerator_hari" => $p->numerator_hari,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "tgl_uji" => date('d/m/Y', strtotime($p->tgl_uji)),
                "uji" => $p->nm_uji,
                "nama_pemilik" => $p->nama_pemilik,
                "jns" => $p->jenis,
                "b_retribusi_lebih" => number_format($p->b_retribusi_lebih, 0, ',', '.') . " <br/><b>(" . $p->tlt_retribusi . ")</b>",
                "b_berkala" => number_format($p->b_berkala, 0, ',', '.'),
                "b_rekom" => number_format($p->b_rekom, 0, ',', '.'),
                "b_buku" => number_format($p->b_buku, 0, ',', '.') . " <br/><b>" . $bk_masuk . "</b>",
                "b_tlt_uji" => number_format($p->b_tlt_uji, 0, ',', '.') . " <br/><b>(" . $p->lm_tlt . " bln)</b>",
                "b_plat_uji" => number_format($p->b_plat_uji, 0, ',', '.'),
                "total" => number_format($p->total, 0, ',', '.'),
                "buku" => ($p->id_bk_masuk != 1) ? "<img src='$ok'>" : "<img src='$reject'>"
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VValidasi::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    public function actionDetailRetribusi()
    {
        $id_retribusi = $_POST['id_retribusi'];
        $data_retribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $id_retribusi));
        $this->renderPartial('detail_retribusi', array('data' => $data_retribusi));
    }

    public function actionCetakRetribusi($id)
    {
        $this->layout = '//';
        $data_retribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $id));
        $this->render('cetak_retribusi', array('id' => $id, 'data_retribusi' => $data_retribusi));
    }

    public function actionCetakCheckedRetribusi()
    {
        $this->layout = '//';
        $arrayId = $_REQUEST['idArray'];
        $idArray = explode(',', $arrayId);
        $this->render('cetak_checked_retribusi', array(
            'idArray' => $idArray
        ));
    }

    public function actionDetailPengurus()
    {
        // $id_pengurus = $_POST['id_pengurus'];
        // if (!empty($id_pengurus)) {
        //     $data = VPengurus1::model()->findByAttributes(array('id_pengurus' => $id_pengurus));
        //     $result['no_ktp'] = $data->no_ktp;
        //     $result['alamat'] = $data->alamat;
        // } else {
        //     $result['no_ktp'] = '';
        //     $result['alamat'] = '';
        // }
        // echo json_encode($result);
    }

    public function actionCetakStiker($id, $penguji)
    {
        $this->layout = '//';
        $this->render('cetak_stiker', array(
            'id_kendaraan' => $id,
            'penguji' => $penguji
        ));
    }

    public function actionDeleteRetribusi()
    {
        $id = $_POST['id'];
        $sql = "DELETE FROM tbl_retribusi WHERE id_retribusi = $id";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function actionGetListCalculator()
    {
        $idArray = $_POST['idArray'];

        $jmlTotal = 0;
        foreach ($idArray as $key => $arrayId) :
            $dtRetribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $arrayId));
            $no_uji = $dtRetribusi->no_uji;
            $numerator = $dtRetribusi->numerator;
            $total = number_format($dtRetribusi->total, 0, ',', '.');
            $jmlTotal += $dtRetribusi->total;
            $dataJson[] = array(
                "no_uji" => $no_uji,
                "numerator" => $numerator,
                "total" => $total,
            );
        endforeach;
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => count($idArray),
                'rows' => $dataJson,
                'totalcalculator' => number_format($jmlTotal, 0, ',', '.'),
            )
        );
    }

    public function actionCetakBap($id_retribusi, $id_kendaraan, $id_uji)
    {
        $this->layout = '//';
        $this->render('cetak_bap', array('id_kendaraan' => $id_kendaraan, 'id_retribusi' => $id_retribusi, 'id_uji' => $id_uji));
    }

    public function actionCetakGesekan($id_kendaraan, $id_retribusi)
    {
        $this->layout = '//';
        $this->render('cetak_gesekan', array('id_kendaraan' => $id_kendaraan, 'id_retribusi' => $id_retribusi));
    }

    public function actionReplaceFile()
    {
        $this->renderPartial('replace_file');
    }
}
