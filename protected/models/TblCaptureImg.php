<?php

/**
 * This is the model class for table "tbl_capture_img".
 *
 * The followings are the available columns in table 'tbl_capture_img':
 * @property integer $sr_no
 * @property string $user_id
 * @property string $user_name
 * @property string $in_time
 * @property string $out_time
 * @property string $image_url
 */
class TblCaptureImg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_capture_img';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_name', 'required'),
			array('user_id, user_name', 'length', 'max'=>50),
			array('in_image_url, out_image_url', 'length', 'max'=>255),
                       
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sr_no, user_id, user_name, in_time, out_time, in_image_url, out_image_url, todaydate', 'safe', 'on'=>'search'),
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
			'sr_no' => 'Sr No',
			'user_id' => 'User',
			'user_name' => 'User Name',
			'in_time' => 'In Time',
			'out_time' => 'Out Time',
			'in_image_url' => 'In Image Url',
                        'out_image_url' => 'Out Image Url',
                        'todaydate' => 'Today Date',
		);
	}

        public function getGenderOptions(){
            return array('M' => 'Male', 'F' => 'Female');
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

		$criteria->compare('sr_no',$this->sr_no);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('in_time',$this->in_time,true);
		$criteria->compare('out_time',$this->out_time,true);
		$criteria->compare('in_image_url',$this->in_image_url,true);
                $criteria->compare('out_image_url',$this->out_image_url,true);
                $criteria->compare('todaydate',$this->todaydate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TblCaptureImg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public function GetTodayUSerrecord()
        {
              $id = Yii::app()->session['login']['user_id'];  
             $tdate = date('Y-m-d');
             $sql = "SELECT * FROM tbl_capture_img WHERE user_id =$id AND todaydate = '$tdate'";           
             return Yii::app()->db->createCommand($sql)->queryRow(); 
        }
        
         public function Getloginrecord()
        {
              $id = Yii::app()->session['login']['user_id'];  
             $tdate = date('Y-m-d');
             $sql = "SELECT is_timesheet FROM tbl_employee WHERE emp_id =$id";           
             return Yii::app()->db->createCommand($sql)->queryRow(); 
}


        
}
