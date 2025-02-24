<?php

/**
 * This is the model class for table "v_print_hasil_now".
 *
 * The followings are the available columns in table 'v_print_hasil_now':
 * @property string $id_hasil_uji
 * @property double $ktlamp_kanan
 * @property double $ktlamp_kiri
 * @property double $dev_kanan
 * @property double $dev_kiri
 * @property double $selrem_sb1
 * @property double $selrem_sb2
 * @property double $selrem_sb3
 * @property double $selrem_sb4
 * @property double $beratgaya
 * @property double $selgaya
 * @property double $selkirikanan
 * @property double $selgaya3
 * @property double $selgaya4
 * @property double $ems_diesel
 * @property double $ems_mesin_co
 * @property double $ems_mesin_hc
 * @property string $nm_penguji
 * @property boolean $ujimekanis
 * @property boolean $prauji
 * @property boolean $cetak
 * @property string $no_surat
 * @property string $no_uji
 * @property string $no_kendaraan
 * @property string $nama_pemilik
 * @property string $tahun
 * @property string $no_mesin
 * @property string $no_chasis
 * @property string $jenis
 * @property string $merk
 * @property string $alamat
 * @property string $bahan_bakar
 * @property string $tipe
 * @property string $id_kendaraan
 * @property string $tgl_uji
 * @property string $nrp
 * @property string $jabatan
 * @property string $id_daftar
 * @property string $jdatang
 * @property string $jselesai
 * @property string $no_antrian
 * @property string $stts_no
 * @property boolean $genap
 * @property boolean $lulus
 * @property string $buku
 * @property integer $id_uji
 * @property integer $antri
 */
class VPrintHasilNow extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'v_print_hasil_now';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_uji, antri', 'numerical', 'integerOnly' => true),
            array('ktlamp_kanan, ktlamp_kiri, dev_kanan, dev_kiri, selrem_sb1, selrem_sb2, selrem_sb3, selrem_sb4, beratgaya, selgaya1, selgaya2, selgaya3, selgaya4, ems_diesel, ems_mesin_co, ems_mesin_hc', 'numerical'),
            array('nm_penguji', 'length', 'max' => 50),
            array('no_surat, no_uji, no_kendaraan, tahun, no_mesin, no_chasis, jenis, merk, bahan_bakar, tipe, nrp, jabatan', 'length', 'max' => 30),
            array('nama_pemilik', 'length', 'max' => 100),
            array('alamat', 'length', 'max' => 200),
            array('id_hasil_uji, ujimekanis, prauji, cetak, id_kendaraan, tgl_uji, id_daftar, jdatang, jselesai, no_antrian, stts_no, genap, lulus, buku', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id_hasil_uji, ktlamp_kanan, ktlamp_kiri, dev_kanan, dev_kiri, selrem_sb1, selrem_sb2, selrem_sb3, selrem_sb4, beratgaya, selgaya1, selgaya2, selgaya3, selgaya4, ems_diesel, ems_mesin_co, ems_mesin_hc, nm_penguji, ujimekanis, prauji, cetak, no_surat, no_uji, no_kendaraan, nama_pemilik, tahun, no_mesin, no_chasis, jenis, merk, alamat, bahan_bakar, tipe, id_kendaraan, tgl_uji, nrp, jabatan, id_daftar, jdatang, jselesai, no_antrian, stts_no, genap, lulus, buku, id_uji, antri', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_hasil_uji' => 'Id Hasil Uji',
            'ktlamp_kanan' => 'Ktlamp Kanan',
            'ktlamp_kiri' => 'Ktlamp Kiri',
            'dev_kanan' => 'Dev Kanan',
            'dev_kiri' => 'Dev Kiri',
            'selrem_sb1' => 'Selrem Sb1',
            'selrem_sb2' => 'Selrem Sb2',
            'selrem_sb3' => 'Selrem Sb3',
            'selrem_sb4' => 'Selrem Sb4',
            'beratgaya' => 'Beratgaya',
            'selgaya1' => 'Selgaya1',
            'selgaya2' => 'Selgaya 2',
            'selgaya3' => 'Selgaya3',
            'selgaya4' => 'Selgaya4',
            'ems_diesel' => 'Ems Diesel',
            'ems_mesin_co' => 'Ems Mesin Co',
            'ems_mesin_hc' => 'Ems Mesin Hc',
            'nm_penguji' => 'Nm Penguji',
            'ujimekanis' => 'Ujimekanis',
            'prauji' => 'Prauji',
            'cetak' => 'Cetak',
            'no_surat' => 'No Surat',
            'no_uji' => 'No Uji',
            'no_kendaraan' => 'No Kendaraan',
            'nama_pemilik' => 'Nama Pemilik',
            'tahun' => 'Tahun',
            'no_mesin' => 'No Mesin',
            'no_chasis' => 'No Chasis',
            'jenis' => 'Jenis',
            'merk' => 'Merk',
            'alamat' => 'Alamat',
            'bahan_bakar' => 'Bahan Bakar',
            'tipe' => 'Tipe',
            'id_kendaraan' => 'Id Kendaraan',
            'tgl_uji' => 'Tgl Uji',
            'nrp' => 'Nrp',
            'jabatan' => 'Jabatan',
            'id_daftar' => 'Id Daftar',
            'jdatang' => 'Jdatang',
            'jselesai' => 'Jselesai',
            'no_antrian' => 'No Antrian',
            'stts_no' => 'Stts No',
            'genap' => 'Genap',
            'lulus' => 'Lulus',
            'buku' => 'Buku',
            'id_uji' => 'Id Uji',
            'antri' => 'Antri',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id_hasil_uji', $this->id_hasil_uji, true);
        $criteria->compare('ktlamp_kanan', $this->ktlamp_kanan);
        $criteria->compare('ktlamp_kiri', $this->ktlamp_kiri);
        $criteria->compare('dev_kanan', $this->dev_kanan);
        $criteria->compare('dev_kiri', $this->dev_kiri);
        $criteria->compare('selrem_sb1', $this->selrem_sb1);
        $criteria->compare('selrem_sb2', $this->selrem_sb2);
        $criteria->compare('selrem_sb3', $this->selrem_sb3);
        $criteria->compare('selrem_sb4', $this->selrem_sb4);
        $criteria->compare('beratgaya', $this->beratgaya);
        $criteria->compare('selgaya1', $this->selgaya1);
        $criteria->compare('selgaya2', $this->selgaya2);
        $criteria->compare('selgaya3', $this->selgaya3);
        $criteria->compare('selgaya4', $this->selgaya4);
        $criteria->compare('ems_diesel', $this->ems_diesel);
        $criteria->compare('ems_mesin_co', $this->ems_mesin_co);
        $criteria->compare('ems_mesin_hc', $this->ems_mesin_hc);
        $criteria->compare('nm_penguji', $this->nm_penguji, true);
        $criteria->compare('ujimekanis', $this->ujimekanis);
        $criteria->compare('prauji', $this->prauji);
        $criteria->compare('cetak', $this->cetak);
        $criteria->compare('no_surat', $this->no_surat, true);
        $criteria->compare('no_uji', $this->no_uji, true);
        $criteria->compare('no_kendaraan', $this->no_kendaraan, true);
        $criteria->compare('nama_pemilik', $this->nama_pemilik, true);
        $criteria->compare('tahun', $this->tahun, true);
        $criteria->compare('no_mesin', $this->no_mesin, true);
        $criteria->compare('no_chasis', $this->no_chasis, true);
        $criteria->compare('jenis', $this->jenis, true);
        $criteria->compare('merk', $this->merk, true);
        $criteria->compare('alamat', $this->alamat, true);
        $criteria->compare('bahan_bakar', $this->bahan_bakar, true);
        $criteria->compare('tipe', $this->tipe, true);
        $criteria->compare('id_kendaraan', $this->id_kendaraan, true);
        $criteria->compare('tgl_uji', $this->tgl_uji, true);
        $criteria->compare('nrp', $this->nrp, true);
        $criteria->compare('jabatan', $this->jabatan, true);
        $criteria->compare('id_daftar', $this->id_daftar, true);
        $criteria->compare('jdatang', $this->jdatang, true);
        $criteria->compare('jselesai', $this->jselesai, true);
        $criteria->compare('no_antrian', $this->no_antrian, true);
        $criteria->compare('stts_no', $this->stts_no, true);
        $criteria->compare('genap', $this->genap);
        $criteria->compare('lulus', $this->lulus);
        $criteria->compare('buku', $this->buku, true);
        $criteria->compare('id_uji', $this->id_uji);
        $criteria->compare('antri', $this->antri);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VPrintHasilNow the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
