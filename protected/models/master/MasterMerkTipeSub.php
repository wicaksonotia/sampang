<?php

class MasterMerkTipeSub extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master_variandata';
	}

	public function primaryKey()
	{
		return array('vehicle_varian_id');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vehicle_varian_id, vehicle_varian_type_id, vehicle_varian_code, vehicle_varian_name, vehicle_varian_desc', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MKelurahan the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
