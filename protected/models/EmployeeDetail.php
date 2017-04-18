<?php

/**
 * This is the model class for table "tbl_employee_detail".
 *
 * The followings are the available columns in table 'tbl_employee_detail':
 * @property string $id
 * @property string $emp_id
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $state_id
 * @property string $zip
 * @property string $phone
 * @property string $mobile
 * @property string $modified_date
 * @property string $profile_image
 */
class EmployeeDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_employee_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('work_location, phone, mobile', 'required'),
			array('emp_id', 'length', 'max'=>11),
			array('address_1', 'length', 'max'=>150),
			array('city', 'length', 'max'=>50),
			array('state_id', 'length', 'max'=>2),
			array('zip, mobile', 'length', 'max'=>12),
			array('phone', 'length', 'max'=>20),
			array('profile_image', 'length', 'max'=>100),
//			array('company_name, rjil_ext, building_name, floor, work_location, facebook_link, linkedin_link, twitter_link', 'length', 'max'=>255),
//			array('facebook_link, linkedin_link, twitter_link','url', 'defaultScheme' => 'http://'),
                        array('modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, emp_id, address_1, address_2, city, state_id, zip, phone, mobile, modified_date, profile_image, company_name, rjil_ext, building_name, floor, work_location, facebook_link, linkedin_link, twitter_link', 'safe', 'on'=>'search'),
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
			'Employee' => array(self::BELONGS_TO, 'Employee', 'emp_id'),
			'state' => array(self::BELONGS_TO, 'StateMaster', 'state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'emp_id' => 'Emp',
			'address_1' => 'Address 1',
			'address_2' => 'Address 2',
			'city' => 'City',
			'state_id' => 'State',
			'zip' => 'Zip',
			'phone' => 'Phone',
			'mobile' => 'Mobile',
			'modified_date' => 'Modified Date',
			'profile_image' => 'Profile Image',
			'company_name' => 'Company Name',
			'rjil_ext' => 'Rjil Ext',
			'building_name' => 'Building Name',
			'floor' => 'Floor',
			'work_location' => 'Work Location',
			'facebook_link' => 'Facebook Url',
			'linkedin_link' => 'Linkedin Url',
			'twitter_link' => 'Twitter Url',
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

//		$criteria->compare('id',$this->id,true);
//		$criteria->compare('emp_id',$this->emp_id,true);
//		$criteria->compare('address_1',$this->address_1,true);
////		$criteria->compare('address_2',$this->address_2,true);
//		$criteria->compare('city',$this->city,true);
//		$criteria->compare('state_id',$this->state_id,true);
//		$criteria->compare('zip',$this->zip,true);
//		$criteria->compare('phone',$this->phone,true);
//		$criteria->compare('mobile',$this->mobile,true);
//		$criteria->compare('modified_date',$this->modified_date,true);
//		$criteria->compare('profile_image',$this->profile_image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
