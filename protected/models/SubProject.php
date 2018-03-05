<?php

/**
 * This is the model class for table "tbl_sub_project".
 *
 * The followings are the available columns in table 'tbl_sub_project':
 * @property integer $spid
 * @property integer $pid
 * @property string $sub_project_name
 * @property string $sub_project_description
 * @property string $requester
 * @property string $estimated_end_date
 * @property string $total_hr_estimation_hour
 * @property integer $created_by
 * @property string $created_date
 * @property integer $updated_by
 * @property string $updated_date
 * @property integer $is_deleted
 */
class SubProject extends CActiveRecord
{   
                public $project_name;
		public $taskId;//for database
		public $Task_ID;// for display purpose
		public $name;
		public $hours;
                public $allocated_resource;
                public $Allocate_Hours;
		public $Program;
		public $Project;
		public $Aproved_hour;
		public $Consumed_hours;
		public $Task;
		public $Estimated_hours;
		public $consumed_hours;
                
		
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_sub_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, sub_project_name, sub_project_description,status,Priority,requester', 'required'),
			array('pid, created_by, updated_by, is_deleted', 'numerical', 'integerOnly'=>true),
			array('sub_project_name, sub_project_description, requester', 'length', 'max'=>250), 
                        array('total_hr_estimation_hour', 'numerical' ),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
			array('spid, pid, sub_project_name, sub_project_description, requester, estimated_end_date, total_hr_estimation_hour, ,estimated_start_date , created_by, created_date, updated_by, updated_date,project_name', 'safe', 'on'=>'search'),
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
			'spid' => 'Spid',
			'pid' => 'Pid',
			'sub_project_name' => 'Project name',
			'sub_project_description' => 'Project Description',
			'requester' => 'Requester',
			'estimated_end_date' => 'Estimated End Date',
			'total_hr_estimation_hour' => 'Total Hr Estimation Hour',
			'estimated_start_date' =>'Estimated Start Date',
			'status' =>'Status',
			'Priority' =>'Priority',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'updated_by' => 'Updated By',
			'updated_date' => 'Updated Date',
			'is_deleted' => 'Is Deleted',
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
                $criteria->select = "t.*,pr.project_name,t.spid as taskId ";//t.spid as taskId 
		$criteria->compare('spid',$this->spid);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('sub_project_name',$this->sub_project_name,true);
		$criteria->compare('sub_project_description',$this->sub_project_description,true);
		$criteria->compare('requester',$this->requester,true);
		$criteria->compare('estimated_end_date',$this->estimated_end_date,true);
		$criteria->compare('total_hr_estimation_hour',$this->total_hr_estimation_hour,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('is_deleted',$this->is_deleted);
                $criteria->compare('project_name',$this->project_name);
                $criteria->join = "INNER join tbl_project_management pr ON (pr.pid=t.pid)"
                        . "INNER join tbl_pid_approval as pa ON (t.spid = pa.sub_project_id) ";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubProject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
          public function getAllocateHrLink($model, $row) {
        if ($model->status == 'new')
            return 'N/A';
        return CHtml::link("Allocate hours", array('#'), array('data-toggle' => "modal", 'data-target' => "#", 'class' => 'AllocateHrLink', 'data-key' => $model->spid));
    }
    
    public function getAllProject() {
            $pro = array();
            $r1 = Yii::app()->db->createCommand('select distinct t.spid, t.sub_project_description, t.sub_project_name from tbl_sub_project as t where t.sub_project_description!=""')->queryAll();            
            $pro[] = 'Select Sub Project';
            foreach($r1 as $v1){
                $pro[$v1['spid']] = $v1['sub_project_description'];
            }
            return $pro;
        }
}
