<?php

class VRekom extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'v_rekom';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id_rekom', $this->id_rekom, true);
		$criteria->compare('tgl_rekom', $this->tgl_rekom, true);
		$criteria->compare('no_uji', $this->no_uji, true);
		$criteria->compare('no_kendaraan', $this->no_kendaraan, true);
		$criteria->compare('mutke', $this->mutke);
		$criteria->compare('nik_baru', $this->nik_baru);
		$criteria->compare('pemilik_baru', $this->pemilik_baru);
		$criteria->compare('alamat_baru', $this->alamat_baru);
		$criteria->compare('id_provinsi_mutke', $this->id_provinsi_mutke);
		$criteria->compare('id_kota_mutke', $this->id_kota_mutke);
		$criteria->compare('numke', $this->numke);
		$criteria->compare('id_provinsi_numke', $this->id_provinsi_numke);
		$criteria->compare('id_kota_numke', $this->id_kota_numke);
		$criteria->compare('ubhbentuk', $this->ubhbentuk);
		$criteria->compare('ket_ubah_bentuk', $this->ket_ubah_bentuk);
		$criteria->compare('tahun', $this->tahun, true);
		$criteria->compare('bulan', $this->bulan, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VValidasi the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
