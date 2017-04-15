<?php

/**
 * This is the model class for table "tbl_infi_employee".
 *
 * The followings are the available columns in table 'tbl_infi_employee':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $shift
 * @property integer $SrNo
 * @property string $created_date
 * @property integer $is_active
 */
class InfiEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_infi_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name, SrNo, created_date', 'required'),
			array('SrNo, is_active', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>50),
			array('shift', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, first_name, last_name, shift, SrNo, created_date, is_active', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'shift' => 'Shift',
			'SrNo' => 'Sr No',
			'created_date' => 'Created Date',
			'is_active' => 'Is Active',
		);
	}

        public function getEmployees()
        {
             $criteria = new CDbCriteria;
             $criteria->select = "id,CONCAT(first_name,' ',last_name) as first_name";
             $criteria->order = "first_name";
             $criteria->condition = "is_active = 1 AND user_type = 2";
             $data = InfiEmployee::model()->findAll($criteria);             
             return $data;
             
        }
        
        public function getManagers()
        {
             $criteria = new CDbCriteria;
             $criteria->select = "id,CONCAT(first_name,' ',last_name) as first_name";
             $criteria->order = "first_name";
             $criteria->condition = "is_active = 1 AND user_type = 1";
             $data = InfiEmployee::model()->findAll($criteria);             
             return $data;
             
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

		$criteria->compare('id',$this->id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('shift',$this->shift,true);
		$criteria->compare('SrNo',$this->SrNo);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InfiEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
