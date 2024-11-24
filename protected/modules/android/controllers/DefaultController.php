<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of terminal
 *
 * @author TIA.WICAKSONO
 */
class DefaultController extends Controller
{
    public $layout = '//';
    /* =====================================================================
     * BARCODE DALOPS
      ===================================================================== */

    public function actionRiwayatKendaraan()
    {
        $this->layout = '//';
        $json = file_get_contents('php://input');
        $get_json = json_decode($json, true);
        $no_uji = strtoupper($get_json['noUji']);
        $data['tempat_uji'] = '-';
        $data['no_uji'] = '-';
        $data['no_kendaraan'] = '-';
        $data['mati_uji'] = '-';
        $data['merk'] = '-';
        $data['tipe'] = '-';
        $data['no_chasis'] = '-';
        $data['no_mesin'] = '-';
        $data['pemilik'] = '-';
        $data['kondisi'] = '-';
        $data['success'] = false;

        if (!empty($get_json['noUji'])) {
            $criteria = new CDbCriteria();
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','')) or (replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $no_uji . "%'),' ',''))");
            $criteria->order = 'id_riwayat DESC';
            $result = VRiwayat::model()->find($criteria);
            $tempat_uji = 'Pengujian Kendaraan Bermotor Sampang, Jawa Timur';

            if (!empty($result)) {
                $data['tempat_uji'] = $tempat_uji;
                $data['no_uji'] = $result->no_uji;
                $data['no_kendaraan'] = $result->no_kendaraan;
                $data['mati_uji'] = date("d F Y", strtotime($result->tglmati));
                $data['merk'] = $result->merk;
                $data['tipe'] = $result->tipe;
                $data['no_chasis'] = $result->no_chasis;
                $data['no_mesin'] = $result->no_mesin;
                $data['pemilik'] = $result->nama_pemilik;
                if (strtotime($result->tglmati) < strtotime(date('m/d/Y'))) {
                    $data['kondisi'] = 'mati';
                } else {
                    $data['kondisi'] = 'hidup';
                }
                $data['success'] = true;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function actionDetailPersyaratan()
    {
        $this->layout = '//';
        $json = file_get_contents('php://input');
        $get_json = json_decode($json, true);
        $kategori = strtoupper($get_json['kategori']);
        $criteria = new CDbCriteria();
        $criteria->addCondition("category_code = '$kategori'");
        $result = VPersyaratan::model()->findAll($criteria);
        $data = array();
        foreach ($result as $p) {
            $data[] = array(
                "keterangan" => $p->persyaratan,
            );
        }
        echo json_encode($data);
    }

    public function actionDetailKendaraan()
    {
        $no_uji = $_POST['noUji'];

        $data['id_kendaraan'] = 0;
        $data['no_uji'] = "-";
        $data['no_kendaraan'] = "-";
        $data['merk'] = '-';
        $data['tipe'] = '-';
        $data['no_chasis'] = '-';
        $data['no_mesin'] = '-';
        $data['pemilik'] = '-';
        $data['jns_kend'] = '-';
        $data['mati_uji'] = '-';
        $data['nama_komersil'] = '-';
        $data['jenis_karoseri'] = '-';
        $data['bahan_utama'] = '-';
        $data['panjang'] = '-';
        $data['lebar'] = '-';
        $data['tinggi'] = '-';
        $data['dimpanjang'] = '-';
        $data['dimlebar'] = '-';
        $data['dimtinggi'] = '-';
        $data['jbb'] = '-';
        $data['orang'] = '-';
        $data['barang'] = '-';
        $data['sb1'] = '-';
        $data['sb2'] = '-';
        $data['sb3'] = '-';
        $data['sb4'] = '-';
        $data['sb5'] = '-';
        $data['total_sb'] = '-';
        $data['jbi'] = '-';
        $data['mst'] = '-';
        $data['kondisi'] = 'mati';
        $data['success'] = false;
        $data['tujuan'] = '-';
        $data['img_depan'] = '-';
        $data['img_belakang'] = '-';
        $data['img_kanan'] = '-';
        $data['img_kiri'] = '-';

        if (!empty($no_uji)) {
            $criteria = new CDbCriteria();
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $no_uji . "'),' ',''))");
            $result = VKendaraan::model()->find($criteria);

            if (!empty($result)) {
                // $dtRiwayat = VRiwayat::model()->find($criteria);
                // $tgl_mati_uji = $dtRiwayat->tglmati;

                $criteriaHasilUji = new CDbCriteria();
                $criteriaHasilUji->addCondition("id_kendaraan = $result->id_kendaraan");
                $criteriaHasilUji->order = 'jdatang desc';
                $criteriaHasilUji->limit = 1;
                $hasilUji = TblHasilUji::model()->find($criteriaHasilUji);
                if (empty($hasilUji->img_depan)) {
                    $img_depan = '-';
                } else {
                    $img_depan = $hasilUji->img_depan;
                }

                if (empty($hasilUji->img_belakang)) {
                    $img_belakang = '-';
                } else {
                    $img_belakang = $hasilUji->img_belakang;
                }

                if (empty($hasilUji->img_kanan)) {
                    $img_kanan = '-';
                } else {
                    $img_kanan = $hasilUji->img_kanan;
                }

                if (empty($hasilUji->img_kiri)) {
                    $img_kiri = '-';
                } else {
                    $img_kiri = $hasilUji->img_kiri;
                }

                $data['id_kendaraan'] = $result->id_kendaraan;
                $data['no_uji'] = $result->no_uji;
                $data['no_kendaraan'] = $result->no_kendaraan;
                $data['merk'] = $result->merk;
                $data['tipe'] = $result->tipe;
                $data['no_chasis'] = $result->no_chasis;
                $data['no_mesin'] = $result->no_mesin;
                $data['pemilik'] = $result->nama_pemilik;
                $data['jns_kend'] = $result->karoseri_jenis;
                $tgl_mati_uji = $result->tgl_mati_uji;
                $data['mati_uji'] = date("d F Y", strtotime($tgl_mati_uji));
                $data['nama_komersil'] = $result->nm_komersil;
                $data['jenis_karoseri'] = $result->karoseri_jenis;
                $data['bahan_utama'] = $result->karoseri_bahan;
                $data['panjang'] = $result->ukuran_panjang . " mm";
                $data['lebar'] = $result->ukuran_lebar . " mm";
                $data['tinggi'] = $result->ukuran_tinggi . " mm";
                $data['dimpanjang'] = $result->dimpanjang . " mm";
                $data['dimlebar'] = $result->dimlebar . " mm";
                $data['dimtinggi'] = $result->dimtinggi . " mm";
                $data['jbb'] = $result->kemjbb . " Kg";
                $data['orang'] = $result->karoseri_duduk . " Orang, " . $result->kemorang . " Kg";
                $data['barang'] = $result->kembarang . " Kg";
                $bsumbu1 = $result->bsumbu1;
                $bsumbu2 = $result->bsumbu2;
                $bsumbu3 = $result->bsumbu3;
                $bsumbu4 = $result->bsumbu4;
                $bsumbu5 = $result->bsumbu5;
                $total_berat_sumbu = $bsumbu1 + $bsumbu2 + $bsumbu3 + $bsumbu4 + $bsumbu5;
                $data['sb1'] = $bsumbu1 . " Kg";
                $data['sb2'] = $bsumbu2 . " Kg";
                $data['sb3'] = $bsumbu3 . " Kg";
                $data['sb4'] = $bsumbu4 . " Kg";
                $data['sb5'] = $bsumbu5 . " Kg";
                $data['total_sb'] = $total_berat_sumbu . " Kg";
                $data['jbi'] = $result->jbi . " Kg";
                $data['mst'] = $result->mst . " Kg";
                if (strtotime($tgl_mati_uji) < strtotime(date('m/d/Y'))) {
                    $data['kondisi'] = 'mati';
                } else {
                    $data['kondisi'] = 'hidup';
                }
                $data['success'] = true;
                $data['tujuan'] = '-';
                $data['img_depan'] = $img_depan;
                $data['img_belakang'] = $img_belakang;
                $data['img_kanan'] = $img_kanan;
                $data['img_kiri'] = $img_kiri;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function actionHasilUji()
    {
        $no_uji = strtoupper($_POST['noUji']);

        $data['no_uji'] = '-';
        $data['no_kendaraan'] = "-";
        $data['pemilik'] = "-";
        $data['hasil_tgl_terakhir_uji'] = "-";
        $data['hasil_tgl_mati_uji'] = "-";
        $data['hasil_prauji'] = "-";
        $data['hasil_emisi'] = "-";
        $data['hasil_pitlift'] = "-";
        $data['hasil_lampu'] = "-";
        $data['hasil_break'] = "-";
        $data['ltl'] = "-";
        $data['keterangan'] = "-";
        $data['kondisi'] = 'mati';
        $data['img_depan'] = 'A';
        $data['img_belakang'] = '-';
        $data['img_kanan'] = '-';
        $data['img_kiri'] = '-';

        if (!empty($no_uji)) {
            $criteria = new CDbCriteria();
            $criteria->order = 'id_hasil_uji DESC';
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $no_uji . "'),' ',''))");
            $result = VStatusProses::model()->find($criteria);
            if (!empty($result)) {
                // IMAGE
                if (empty($result->img_depan)) {
                    $img_depan = '-';
                } else {
                    $img_depan = $result->img_depan;
                }

                if (empty($result->img_belakang)) {
                    $img_belakang = '-';
                } else {
                    $img_belakang = $result->img_belakang;
                }

                if (empty($result->img_kanan)) {
                    $img_kanan = '-';
                } else {
                    $img_kanan = $result->img_kanan;
                }

                if (empty($result->img_kiri)) {
                    $img_kiri = '-';
                } else {
                    $img_kiri = $result->img_kiri;
                }
                //prauji
                if ($result->prauji == "true") {
                    $prauji = 1;
                    if ($result->lulus_prauji == "true")
                        $hasil_prauji = "LULUS";
                    else
                        $hasil_prauji = "TIDAK LULUS";
                } else {
                    $prauji = 0;
                    $hasil_prauji = "PROSES";
                }
                //smoke
                if ($result->smoke == "true") {
                    $smoke = 1;
                    if ($result->lulus_smoke == "true")
                        $hasil_emisi = "LULUS";
                    else
                        $hasil_emisi = "TIDAK LULUS";
                } else {
                    $smoke = 0;
                    $hasil_emisi = "PROSES";
                }
                //pitlift
                if ($result->pitlift == "true") {
                    $pitlift = 1;
                    if ($result->lulus_pitlift == "true")
                        $hasil_pitlift = "LULUS";
                    else
                        $hasil_pitlift = "TIDAK LULUS";
                } else {
                    $pitlift = 0;
                    $hasil_pitlift = "PROSES";
                }
                //lampu
                if ($result->lampu == "true") {
                    $lampu = 1;
                    if ($result->lulus_lampu == "true")
                        $hasil_lampu = "LULUS";
                    else
                        $hasil_lampu = "TIDAK LULUS";
                } else {
                    $lampu = 0;
                    $hasil_lampu = "PROSES";
                }
                //rem
                if ($result->break == "true") {
                    $break = 1;
                    if ($result->lulus_break == "true")
                        $hasil_break = "LULUS";
                    else
                        $hasil_break = "TIDAK LULUS";
                } else {
                    $break = 0;
                    $hasil_break = "PROSES";
                }

                if ($prauji == 1 && $smoke == 1 && $pitlift == 1 && $lampu == 1 && $break == 1) {
                    if ($result->hasil == "true")
                        $ltl = 'LULUS';
                    else
                        $ltl = 'TIDAK LULUS';
                } else {
                    $ltl = 'PROSES';
                }

                $dataTl = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $result->id_hasil_uji));
                $keterangan = '';
                $no = 1;
                foreach ($dataTl as $p) {
                    $keterangan .= $no . ". " . $p->kelulusan . "\n";
                    $no++;
                }
                $data['no_uji'] = $result->no_uji;
                $data['no_kendaraan'] = $result->no_kendaraan;
                $data['pemilik'] = $result->nama_pemilik;
                $data['hasil_tgl_terakhir_uji'] = date("d F Y", strtotime($result->jdatang));
                $data['hasil_tgl_mati_uji'] = date("d F Y", strtotime($result->tgl_mati_uji));
                $data['hasil_prauji'] = $hasil_prauji;
                $data['hasil_emisi'] = $hasil_emisi;
                $data['hasil_pitlift'] = $hasil_pitlift;
                $data['hasil_lampu'] = $hasil_lampu;
                $data['hasil_break'] = $hasil_break;
                $data['ltl'] = $ltl;
                $data['keterangan'] = $keterangan;
                if (strtotime($result->tgl_mati_uji) < strtotime(date('m/d/Y'))) {
                    $data['kondisi'] = 'mati';
                } else {
                    $data['kondisi'] = 'hidup';
                }
                $data['img_depan'] = $img_depan;
                $data['img_belakang'] = $img_belakang;
                $data['img_kanan'] = $img_kanan;
                $data['img_kiri'] = $img_kiri;
            }
        }
        echo json_encode($data);
    }

    public function actionStatusRekom()
    {
        $json = file_get_contents('php://input');
        $get_json = json_decode($json, true);
        $no_uji = strtoupper($get_json['noUji']);
        $criteria = new CDbCriteria();
        $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $no_uji . "'),' ',''))");
        $result = VRekom::model()->find($criteria);
        if (!empty($result)) {
            $data['no_uji'] = $result->no_uji;
            $data['no_kendaraan'] = $result->no_kendaraan;
            $data['tgl_rekom'] = date("d F Y", strtotime($result->tgl_retribusi));
            if ($result->mutke == true) {
                $rekom = "Mutasi Keluar";
            } elseif ($result->numke == true) {
                $rekom = "Numpang Keluar";
            } elseif ($result->ubhsifat == true) {
                $rekom = "Ubah Sifat";
            } else {
                $rekom = "-";
            }
            $data['rekom'] = $rekom;

            $criteriaRekomStatus = new CDbCriteria();
            $criteriaRekomStatus->addInCondition('id_rekom', array($result->id_rekom));
            $dataCriteriaRekomStatus = TblRekomStatus::model()->find($criteriaRekomStatus);
            if (empty($dataCriteriaRekomStatus)) {
                $kasubag = 0;
                $kaupt = 0;
                $kadis = 0;
                $tglKasubag = '-';
                $tglKaupt = '-';
                $tglKadis = '-';
            } else {
                $kasubag = $dataCriteriaRekomStatus->approve1;
                $kaupt = $dataCriteriaRekomStatus->approve2;
                $kadis = $dataCriteriaRekomStatus->approve3;
                $tglKasubag = date("d F Y", strtotime($dataCriteriaRekomStatus->tgl_approve1));
                $tglKaupt = date("d F Y", strtotime($dataCriteriaRekomStatus->tgl_approve2));
                $tglKadis = date("d F Y", strtotime($dataCriteriaRekomStatus->tgl_approve3));
            }
            $data['kasubag'] = $kasubag;
            $data['kaupt'] = $kaupt;
            $data['kadis'] = $kadis;
            $data['tglKasubag'] = $tglKasubag;
            $data['tglKaupt'] = $tglKaupt;
            $data['tglKadis'] = $tglKadis;
            $data['lokasiRekom'] = "UPTD PKB Wiyung Surabaya";
        } else {
            $data['no_uji'] = '-';
            $data['no_kendaraan'] = '-';
            $data['tgl_rekom'] = '-';
            $data['rekom'] = '-';
            $data['kasubag'] = 0;
            $data['kaupt'] = 0;
            $data['kadis'] = 0;
            $data['tglKasubag'] = '-';
            $data['tglKaupt'] = '-';
            $data['tglKadis'] = '-';
            $data['lokasiRekom'] = "UPTD PKB Wiyung Tandes";
        }

        echo json_encode($data);
    }

    public function actionListKendaraan()
    {
        $json = file_get_contents('php://input');
        $get_json = json_decode($json, true);
        $no_uji = strtoupper($get_json['noUji']);
        $per_page = 7;
        $page = (isset($_POST['index'])) ? (int) $_POST['index'] : 0;
        $start = ($page) * $per_page;
        $criteriaWiyung = new CDbCriteria();
        $criteriaWiyung->order = "id_hasil_uji DESC";
        $criteriaWiyung->limit = $per_page;
        $criteriaWiyung->offset = $start;
        if (!empty($no_uji)) {
            $criteriaWiyung->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $no_uji . "'),' ',''))");
        }
        $result = VVerifikasi::model()->findAll($criteriaWiyung);
        $response = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $response[] = array(
                    'no_kendaraan' => $row->no_kendaraan,
                    'no_uji' => $row->no_uji,
                    'nama_pemilik' => $row->nama_pemilik,
                    'status_kelulusan' => $row->hasil == 'true' ? 'LULUS' : 'TIDAK LULUS'
                );
            }
        }
        echo json_encode($response);
    }

    public function actionTotalListKendaraan()
    {
        $sql_tandes = "select 
        (select jml_kuota from tbl_kuota) as total,
        (select count(*) from tbl_daftar where tgl_uji=current_date) as jml,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=0) as brg,
        (select count(*) from tbl_daftar where tgl_uji=current_date and datang='true') as dtg,
        (select count(*) from tbl_daftar where tgl_uji=current_date and datang='false') as td,
        (select count(*) from tbl_daftar where tgl_uji=current_date and datang='true' and lulus='true') as lls,
        (select count(*) from tbl_daftar a left join tbl_hasil_uji b ON a.id_daftar=b.id_daftar where a.tgl_uji=current_date and a.datang='true' and a.lulus='false' and b.cetak='true') as tdklls,
        (select count(*) from tbl_daftar where tgl_uji=current_date and datang='true')-((select count(*) from tbl_daftar where tgl_uji=current_date and datang='true' and lulus='true') + (select count(*) from tbl_daftar a left join tbl_hasil_uji b ON a.id_daftar=b.id_daftar where a.tgl_uji=current_date and a.datang='true' and a.lulus='false' and b.cetak='true')) as blmprs,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=1) as pnp,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=2) as bis,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=3) as khs,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=4) as gdn,
        (select count(*) from tbl_daftar where tgl_uji=current_date and id_jns=5) as tmp,
        (select jml_kuota from tbl_kuota)-(select count(*) from tbl_daftar where tgl_uji=current_date) as sisa";
        $row_tandes = Yii::app()->db->createCommand($sql_tandes)->queryRow();

        $data['mobil_barang_tandes'] = $row_tandes['brg'];
        $data['mobil_bis_tandes'] = $row_tandes['bis'];
        $data['mobil_penumpang_tandes'] = $row_tandes['pnp'];
        $data['mobil_khusus_tandes'] = $row_tandes['khs'];
        $data['mobil_gandengan_tandes'] = $row_tandes['gdn'];
        $data['mobil_tempelan_tandes'] = $row_tandes['tmp'];
        $data['jumlah_tandes'] = $row_tandes['jml'];
        $data['mobil_datang_tandes'] = $row_tandes['dtg'];
        $data['mobil_tidak_datang_tandes'] = $row_tandes['td'];
        $data['lulus_uji_tandes'] = $row_tandes['lls'];
        $data['tidak_lulus_uji_tandes'] = $row_tandes['tdklls'];
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function actionListRiwayatKendaraan()
    {
        $no_uji = strtoupper($_POST['noUji']);
        $data[] = array(
            "no_uji" => "-",
            "no_kendaraan" => "-",
            "tgl_uji" => "-",
            "tglmati" => "-",
            "merk" => "-",
            "tipe" => "-",
            "no_chasis" => "-",
            "no_mesin" => "-",
            "nm_penguji" => "-",
            "nrp" => "-",
            "no_seri" => "-",
            "nm_uji" => "-",
            "catatan" => "-",
        );
        if ($no_uji != '-') {
            $criteria = new CDbCriteria();
            $criteria->order = 'id_riwayat DESC';
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $no_uji . "%'),' ','')) or (replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $no_uji . "%'),' ',''))");
            $result = VRiwayat::model()->findAll($criteria);
            $data = array();
            if (!empty($result)) {
                foreach ($result as $p) {
                    $nm_uji = 'BERKALA';
                    $catatan = '-';
                    if ($p->jenis_uji == 'MK') {
                        $nm_uji = 'MUTASI KELUAR';
                        $catatan = strtoupper($p->catatan);
                    } else if ($p->jenis_uji == 'NK') {
                        $nm_uji = 'NUMPANG UJI KELUAR';
                        $catatan = strtoupper($p->catatan);
                    }
                    $data[] = array(
                        "no_uji" => $p->no_uji,
                        "no_kendaraan" => $p->no_kendaraan,
                        "tgl_uji" => date("d F Y", strtotime($p->tgl_uji)),
                        "tglmati" => date("d F Y", strtotime($p->tglmati)),
                        "merk" => $p->merk,
                        "tipe" => $p->tipe,
                        "no_chasis" => $p->no_chasis,
                        "no_mesin" => $p->no_mesin,
                        "nm_penguji" => empty($p->nama_penguji) ? ' -' : $p->nama_penguji,
                        "nrp" => empty($p->nrp) ? ' -' : $p->nrp,
                        "no_seri" => $p->no_seri,
                        "nm_uji" => $nm_uji,
                        "catatan" => $catatan
                    );
                }
            }
        }
        echo json_encode($data);
    }

    public function actionSaveRetribusi()
    {
        $idKendaraan = $_POST['idKendaraan'];
        $tgl_uji = DateTime::createFromFormat('d/m/Y', $_POST['tglUji']);
        $tglUji = $tgl_uji->format('m/d/Y');
        $tglRetribusi = date('m/d/Y');

        $criteria = new CDbCriteria();
        $criteria->addCondition("id_kendaraan = '$idKendaraan'");
        $criteria->addCondition("tgl_uji = '$tglUji'");
        $dtValidasi = VValidasi::model()->find($criteria);
        $result['cekTerdaftar'] = '1';
        $result['statusResponse'] = '1';
        $result['va'] = '0';
        $result['b_retribusi'] = 0;
        $result['b_rekom'] = 0;
        $result['b_denda'] = 0;
        $result['b_kartu_rusak'] = 0;
        $result['total_bayar'] = 0;

        if (!empty($dtValidasi)) {
            $b_retribusi = $dtValidasi->b_retribusi_lebih + $dtValidasi->b_berkala + $dtValidasi->b_plat_uji;
            $b_rekom = $dtValidasi->b_rekom;
            $b_denda = $dtValidasi->b_tlt_uji;
            $b_kartu_rusak = $dtValidasi->b_buku;
            $total = $dtValidasi->total;
            $result['b_retribusi'] = "Rp. " . number_format($b_retribusi, 0, ',', '.') . ",-";
            $result['b_rekom'] = "Rp. " . number_format($b_rekom, 0, ',', '.') . ",-";
            $result['b_denda'] = "Rp. " . number_format($b_denda, 0, ',', '.') . ",-";
            $result['b_kartu_rusak'] = "Rp. " . number_format($b_kartu_rusak, 0, ',', '.') . ",-";
            $result['total_bayar'] = "Rp. " . number_format($total, 0, ',', '.') . ",-";
        } else {
            $result['cekTerdaftar'] = '0';
            /**
             * INSERT
             */
            $result_data_kendaraan = VKendaraan::model()->findByAttributes(array('id_kendaraan' => $idKendaraan));
            $NO_STUK = $result_data_kendaraan->no_uji;
            $NO_KENDARAAN = $result_data_kendaraan->no_kendaraan;
            $NO_CHASIS = $result_data_kendaraan->no_chasis;
            $NO_MESIN = $result_data_kendaraan->no_mesin;
            $JENIS_KENDARAAN = $result_data_kendaraan->id_jns_kend;
            $JBB = $result_data_kendaraan->kemjbb;
            $TGL_MATI_UJI = $result_data_kendaraan->tgl_mati_uji;
            $NAMA_PEMILIK = $result_data_kendaraan->nama_pemilik;
            $ALAMAT = $result_data_kendaraan->alamat;
            $NO_KTP = $result_data_kendaraan->no_identitas;
            $NO_TELP = $result_data_kendaraan->no_telp;
            $tanda_pengenal = $result_data_kendaraan->identitas;

            $data_kendaraan = $NO_STUK . '~' . $NO_KENDARAAN . '~' . $NO_CHASIS . '~' . $NO_MESIN . '~' . $idKendaraan . '~' . $JENIS_KENDARAAN . '~' . $JBB;
            $data_uji = 1 . '~' . 1 . '~' . $tglRetribusi . '~' . $tglUji . '~' . $TGL_MATI_UJI . '~' . 'SMPNG';
            $data_pemilik = $NAMA_PEMILIK . '~' . $ALAMAT . '~' . $NO_KTP . '~' . $NO_TELP . '~' . $tanda_pengenal;
            $data_kuasa = '' . '~' . '' . '~' . '' . '~' . 'Orang/Pribadi' . '~' . 'false';
            $inputRetribusi = "Select insert_retribusi( 'online', '" . $data_kendaraan . "','" . $data_uji . "','" . $data_pemilik . "','false','" . $data_kuasa . "')";
            Yii::app()->db->createCommand($inputRetribusi)->query();
            /**
             * SHOW
             */
            $dtValidasi = VValidasi::model()->findByAttributes(array('id_kendaraan' => $idKendaraan, 'tgl_retribusi' => $tglRetribusi));
            if (!empty($dtValidasi)) {
                $b_retribusi = $dtValidasi->b_retribusi_lebih + $dtValidasi->b_berkala + $dtValidasi->b_plat_uji;
                $b_rekom = $dtValidasi->b_rekom;
                $b_denda = $dtValidasi->b_tlt_uji;
                $b_kartu_rusak = $dtValidasi->b_buku;
                $total = $dtValidasi->total;
                $result['b_retribusi'] = "Rp. " . number_format($b_retribusi, 0, ',', '.') . ",-";
                $result['b_rekom'] = "Rp. " . number_format($b_rekom, 0, ',', '.') . ",-";
                $result['b_denda'] = "Rp. " . number_format($b_denda, 0, ',', '.') . ",-";
                $result['b_kartu_rusak'] = "Rp. " . number_format($b_kartu_rusak, 0, ',', '.') . ",-";
                $result['total_bayar'] = "Rp. " . number_format($total, 0, ',', '.') . ",-";
            } else {
                $result['statusResponse'] = '0';
            }

            /**
             * =======================================
             * KIRIM QRIS KE BANK JATIM
             * =======================================
             */
            // $urlBankJatim = "https://jatimva.bankjatim.co.id/MC/Qris/Dynamic";
            // $data = array(
            //     'merchantPan' => '9360011400000268092',
            //     'hashcodeKey' => hash('sha256', '9360011400000268092' . $dtValidasi->virtual_account . 'U0110023COIRMUA3B'),
            //     'billNumber' => $dtValidasi->virtual_account,
            //     'purposetrx' => 'DISHUB',
            //     'storelabel' => 'UJI KIR KAB JOMBANG',
            //     'customerlabel' => 'PUBLIC',
            //     'terminalUser' => 'U011002',
            //     'expiredDate' => date('Y-m-d 14:00:00', strtotime($dtValidasi->tgl_retribusi)),
            //     'amount' => intval($dtValidasi->rettotal),
            // );
            // $kirim = json_encode($data);
            // $ch = curl_init($urlBankJatim);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $resultQris = curl_exec($ch);

            // $arrayQris = json_decode(json_encode($resultQris), true);
            // $arrayQrisJson = json_decode($arrayQris, true);
            // $status_response_qris = $arrayQrisJson['responsCode'];
            // if ($status_response_qris == '00') {
            //     //SUKSES
            //     $qrValue = $arrayQrisJson['qrValue'];
            //     $queryUpdateRetribusi = "UPDATE tbl_retribusi set qr_value = '$qrValue' WHERE id_retribusi = $dtValidasi->id_retribusi";
            //     Yii::app()->db->createCommand($queryUpdateRetribusi)->execute();
            // } else {
            //     $result['qris'] = 'error';
            // }
            // curl_close($ch);
        }
        echo json_encode($result);
    }
}
