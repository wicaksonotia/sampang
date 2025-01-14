<?php

/**
 * This is the model class for table "tbl_ttd".
 *
 * The followings are the available columns in table 'tbl_ttd':
 * @property string $nama
 * @property string $nip
 * @property string $jabatan
 * @property string $title_bag
 * @property integer $id_ttd
 * @property boolean $enable
 */
class TblTtd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_ttd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_ttd', 'required'),
			array('id_ttd', 'numerical', 'integerOnly'=>true),
			array('nama, jabatan', 'length', 'max'=>50),
			array('nip', 'length', 'max'=>40),
			array('title_bag, enable', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('nama, nip, jabatan, title_bag, id_ttd, enable', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nama' => 'Nama',
			'nip' => 'Nip',
			'jabatan' => 'Jabatan',
			'title_bag' => 'Title Bag',
			'id_ttd' => 'Id Ttd',
			'enable' => 'Enable',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('nip',$this->nip,true);
		$criteria->compare('jabatan',$this->jabatan,true);
		$criteria->compare('title_bag',$this->title_bag,true);
		$criteria->compare('id_ttd',$this->id_ttd);
		$criteria->compare('enable',$this->enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblTtd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
