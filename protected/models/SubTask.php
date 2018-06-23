<?php

/**
 * This is the model class for table "db_rjilautots_dev.tbl_sub_task".
 *
 * The followings are the available columns in table 'db_rjilautots_dev.tbl_sub_task':
 * @property integer $stask_id
 * @property integer $task_id
 * @property integer $project_id
 * @property integer $sub_project_id
 * @property string $emp_id
 * @property string $sub_task_name
 * @property string $description
 * @property integer $status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $is_approved
 * @property integer $is_delete
 *
 * The followings are the available model relations:
 * @property Task $task
 * @property Employee $emp
 */
class SubTask extends CActiveRecord
{
    
       // public $pid_approval_id;
	/**
	 * @return string the associated database table name
	 */
	public $project_name;
	public $sub_project_name;
	public $task_name;
	public $employee;
        public $id;
        public $project_description;
        public $start_date;
        public $end_date;
        public $hr_clocked;
        public $category;
        public $created_date;
        public $updated_by;
        public $updated_date;
        public $is_deleted;
        public $Name;
        public $Program;
        public $Project;
        public $Task;
        public $Type;
        public $Estimated_Hours;
        public $Consumed_Hours;
	public function tableName()
	{
		return 'tbl_sub_task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, project_id, sub_project_id, emp_id, sub_task_name, description,pid_approval_id, created_by, created_at, is_approved, is_delete,est_hrs', 'required'),
			array('task_id, project_id, sub_project_id,pid_approval_id, status, created_by, is_approved, is_delete,est_hrs', 'numerical', 'integerOnly'=>true),
			array('emp_id', 'length', 'max'=>10),
			array('sub_task_name, description', 'length', 'max'=>255),
			array('sub_task_name', 'unique', 'message' => 'Sub Project already exists!'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stask_id, pid_approval_id ,task_id, project_id, sub_project_id, emp_id, sub_task_name, description, status, created_by, created_at, is_approved, is_delete,est_hrs', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
//		return array(
//			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
//			'emp' => array(self::BELONGS_TO, 'Employee', 'emp_id'),
//		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stask_id' => 'Stask',
			'task_id' => 'Type',
			'project_id' => 'Program',
			'sub_project_id' => 'Project',
			'emp_id' => 'Employee',
			'sub_task_name' => 'Sub Task Name',
			'est_hrs' => 'Estimated Hours',
			'description' => 'Description',
			'pid_approval_id' => 'pid Approval',
			'status' => 'Status',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'is_approved' => 'Is Approved',
			'is_delete' => 'Is Delete',
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

		$criteria->compare('stask_id',$this->stask_id);
		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('sub_project_id',$this->sub_project_id);
		$criteria->compare('pid_approval_id',$this->pid_approval_id);
		$criteria->compare('emp_id',$this->emp_id,true);
		$criteria->compare('sub_task_name',$this->sub_task_name,true);
		$criteria->compare('est_hrs',$this->est_hrs,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('is_approved',$this->is_approved);
		$criteria->compare('is_delete',$this->is_delete);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubTask the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function GetResourceName($model){
	$model = Employee::model()->findByPk($model->emp_id);
	return $model->first_name." ".$model->last_name;
	}
	
	public function getNameById($model){
	$name = ProjectManagement::model()->findByPk($model->project_id);
	return $name['project_name'];	
	}
	public function getSubProject($model){
	$name = SubProject::model()->findByPk($model->sub_project_id);
	return $name['sub_project_name'];	
	}
	public function getType($model){
	$name = Task::model()->findByPk($model->task_id);
	return $name['task_name'];	
	}
}
