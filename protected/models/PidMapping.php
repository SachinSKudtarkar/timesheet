<?php

/**
 * This is the model class for table "tbl_pid_mapping".
 *
 * The followings are the available columns in table 'tbl_pid_mapping':
 * @property integer $id
 * @property string $year_month
 * @property integer $project_id
 * @property integer $sub_project_id
 * @property integer $task_id
 * @property integer $sub_task_id
 * @property integer $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property string $pid
 */
class PidMapping extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */

	public $pid_id;
	public $inception_date;
	public $total_est_hrs;
	public $comments;
	public $approved;
	public $is_deleted;
	public $sr;
	public $emp_id;
	public $sub_task_name;
	public $est_hrs;
	public function tableName()
	{
		return 'tbl_pid_mapping';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, modified_at', 'required'),
			array('project_id, sub_project_id, task_id, sub_task_id, status, created_by, modified_by', 'numerical', 'integerOnly'=>true),
			array('year_month', 'length', 'max'=>6),
			array('pid', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, year_month, project_id, sub_project_id, task_id, sub_task_id, status, created_at, created_by, modified_at, modified_by, pid', 'safe', 'on'=>'search'),
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
			'year_month' => 'Year Month',
			'project_id' => 'Project',
			'sub_project_id' => 'Sub Project',
			'task_id' => 'Task',
			'sub_task_id' => 'Sub Task',
			'status' => 'Status',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'modified_at' => 'Modified At',
			'modified_by' => 'Modified By',
			'pid' => 'Pid',
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
                $criteria->select = "t.*,sbpr.emp_id ";
		$criteria->compare('id',$this->id);
		$criteria->compare('year_month',$this->year_month,true);
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('sub_project_id',$this->sub_project_id,true);
		$criteria->compare('task_id',$this->task_id,true);
		$criteria->compare('sub_task_id',$this->sub_task_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('pid',$this->pid,true);
		$criteria->order = "id DESC";
                $criteria->join = "INNER JOIN tbl_sub_task as sbpr ON sbpr.stask_id=t.sub_task_id";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      
			
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PidMapping the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function pidgenration($id){
	$PidApproval=PidApproval::model()->findByPk($id);
	  if($PidApproval->approved == 2){
	 
			  $subtask = $results = Yii::app()->db->createCommand("select * from tbl_sub_task where pid_approval_id = {$PidApproval->pid_id} ")->queryAll();
			
	$importData = array();
		foreach($subtask as $key => $val){
				  $this->year_month = substr($PidApproval->created_at,0,4).substr($PidApproval->created_at,5,2);
				  $this->project_id =  sprintf("%03d",$PidApproval->project_id);
				  $this->sub_project_id = sprintf("%04d",$PidApproval->sub_project_id);
				  $this->task_id  = sprintf("%03d",$val['task_id']);
				  $this->sub_task_id = sprintf("%03d",$val['stask_id']);
				  $this->pid = $this->year_month.$this->project_id.$this->sub_project_id.$this->task_id.$this->sub_task_id;
				  $this->status = $PidApproval->status;
				  $this->created_at = date('Y-m-d h:i:s');
				  $this->created_by = Yii::app()->session['login']['user_id'];
				  $this->modified_at = date('Y-m-d h:i:s');
				  $this->modified_by = Yii::app()->session['login']['user_id']; 
			
				  $importData[] = $this->getAttributes();
				  
				  
				  
				  
		}
		$this->SetTaskAllocation($subtask);
		
	   } else {
            $this->addError('input_file', "No records found.");
            throw new Exception("No records found.");
        }

        if (!empty($importData)) {
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $query = $this->commandBuilder->createMultipleInsertCommand($this->tableName(), $importData);
                $query->execute();
                $transaction->commit();
                return TRUE;
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $ex;
            }
        } else {
            $this->addError('input_file', "No records found.");
            throw new Exception("No records found.");
        }
	 
	return true;
	
	
	}
	
	public function SetTaskAllocation($importData){
	if(!empty($importData)){
		foreach($importData as $key=>$val)
		{
			$pid = $val['project_id'];
			$spid = $val['sub_project_id'];
			$emp[$key] = $val['emp_id'];
			$pid_approval_id = $val['pid_approval_id'];
		}
		$emp = implode(',',$emp);
		
		 $model = new TaskAllocation;
		 $model->pid = $pid;
		 $model->spid = $spid;
		 $model->pid_approval_id = $pid_approval_id;
		 $model->date = date('Y-m-d h:i:s');
		 $model->allocated_resource = $emp;
		 $model->created_by = Yii::app()->session['login']['user_id']; 
		 if($model->save()){
		}
		else{print_r($model->getErrors());
		exit;
		}
	 
		}
	}
	
	public function getProjectDescription($pmodel){
		if($pmodel->project_id){
			$project = ProjectManagement::model()->findByPk($pmodel->project_id);
			return $project['project_name'];
		}
		return '';
		
		}
		
		public function getSubProjectDescription($pmodel){
		if($pmodel->sub_project_id){
			$project = SubProject::model()->findByPk($pmodel->sub_project_id);
			return $project['sub_project_name'];
		}
		return '';
		
		}
		
		public function getTaskDescription($pmodel){
		if($pmodel->task_id){
			$project = Task::model()->findByPk($pmodel->task_id);
			return $project['task_name'];
		}
		return '';
		
		}
		
		public function getSubTaskDescription($pmodel){
			if($pmodel->sub_task_id){
				$project = SubTask::model()->findByPk($pmodel->sub_task_id);
				return $project['sub_task_name'];
			}
			return '';
		
		}
                 public function getemp_name($pmodel){
                    if($pmodel->emp_id){
                         $model = Employee::model()->findByPk($pmodel->emp_id);
                        return $model->first_name . ' ' . $model->last_name;
			}
			return '';
                }
}
