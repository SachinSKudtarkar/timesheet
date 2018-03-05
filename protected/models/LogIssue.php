<?php

/**
 * This is the model class for table "tbl_log_issue".
 *
 * The followings are the available columns in table 'tbl_log_issue':
 * @property integer $id
 * @property integer $rca_id
 * @property string $issue_name
 * @property integer $device_id
 * @property integer $state_id
 * @property integer $city_id
 * @property string $issue_occured_date
 * @property integer $pid
 * @property string $issue_description
 * @property integer $created_by
 * @property string $created_date
 * @property integer $issue_status
 * @property integer $is_deleted
 * @property string $updated_date
 * @property integer $updated_by
 */
class LogIssue extends CActiveRecord
{
    public $issuestatus;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_log_issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_name, device_id,  city_id, issue_occured_date, pid, issue_description', 'required'),
			array('id, rca_id, device_id, state_id, city_id, pid, created_by, issue_status, is_deleted, updated_by', 'numerical', 'integerOnly'=>true),
			array('issue_name', 'unique', 'message' => 'Issue Name already exists!'),
                        array('issue_name', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rca_id, issue_name, device_id, state_id, city_id, issue_occured_date, pid, issue_description, created_by, created_date, issue_status, is_deleted, updated_date, updated_by', 'safe', 'on'=>'search'),
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
			'id' => 'Issue Number',
			'rca_id' => 'Rca',
			'issue_name' => 'Issue Name',
			'device_id' => 'Device',
			'state_id' => 'State',
			'city_id' => 'City',
			'issue_occured_date' => 'Issue Occured Date',
			'pid' => 'Pid',
			'issue_description' => 'Issue Description',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'issue_status' => 'Issue Status',
			'is_deleted' => 'Is Deleted',
			'updated_date' => 'Updated Date',
			'updated_by' => 'Updated By',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('rca.rca_name',$this->rca_id);
		$criteria->compare('issue_name',$this->issue_name,true);
		$criteria->compare('dm.type',$this->device_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('ct.city_name',$this->city_id);
		$criteria->compare('issue_occured_date',$this->issue_occured_date,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('issue_description',$this->issue_description,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('issue_status',$this->issue_status);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('updated_by',$this->updated_by);
                $criteria->select = "t.id,t.issue_name,t.issue_description,t.issue_occured_date,t.issue_status,ct.city_name as city_id,dm.type as device_id,rca.rca_name as rca_id";                     
                $criteria->join = ' INNER JOIN tbl_city_master ct ON ct.id = t.city_id'
                                . ' INNER JOIN tbl_device_master dm ON dm.id = t.device_id  '
                                 . ' INNER JOIN tbl_rca rca ON rca.id = t.rca_id  ';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        
        public function searchissues()
	{
		// @todo Please modify the following code to remove attributes that should not be searched. 
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('rca_id',$this->rca_id);
		$criteria->compare('issue_name',$this->issue_name,true);
		$criteria->compare('dm.type',$this->device_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('ct.city_name',$this->city_id);
		$criteria->compare('issue_occured_date',$this->issue_occured_date,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('issue_description',$this->issue_description,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('issue_status',$this->issue_status);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('updated_by',$this->updated_by);
                $criteria->select = "t.id,t.issue_name,t.issue_description,t.issue_occured_date,t.issue_status,ct.city_name as city_id,dm.type as device_id";                     
                $criteria->join = ' INNER JOIN tbl_city_master ct ON ct.id = t.city_id'
                                . ' INNER JOIN tbl_device_master dm ON dm.id = t.device_id  ';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogIssue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function renderButtonChangeIssueStatus($model)
        {
            
            
             $status = $model->issue_status;  
            if($status == 1)
            {
                 echo CHtml::button('Issue Closed',array('class'=>'','id'=>$model->id,'style'=>'margin-left:0px;')); 
               
            }
            else if($status == 0)
            { 
                echo CHtml::button('Close Issue',array('class'=>'cls_close','id'=>$model->id,'style'=>'margin-left:0px;')); 
            }
            
        }
}
