<?php

/**
 * This is the model class for table "tbl_state_master".
 *
 * The followings are the available columns in table 'tbl_state_master':
 * @property string $state_id
 * @property string $state_name_short
 * @property string $state_name
 * @property integer $is_disabled
 * @property integer $has_form
 */
class StateMaster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_state_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state_name_short', 'required'),
			array('is_disabled, has_form', 'numerical', 'integerOnly'=>true),
			array('state_name_short', 'length', 'max'=>2),
			array('state_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('state_id, state_name_short, state_name, is_disabled, has_form', 'safe', 'on'=>'search'),
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
			'employeeDetails' => array(self::HAS_MANY, 'EmployeeDetail', 'state_id'),
			                
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'state_id' => 'State',
			'state_name_short' => 'State Name Short',
			'state_name' => 'State Name',
			'is_disabled' => 'Is Disabled',
			'has_form' => 'Has Form',
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

		$criteria->compare('state_id',$this->state_id,true);
		$criteria->compare('state_name_short',$this->state_name_short,true);
		$criteria->compare('state_name',$this->state_name,true);
		$criteria->compare('is_disabled',$this->is_disabled);
		$criteria->compare('has_form',$this->has_form);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StateMaster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
