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
 * @property integer $approval_status
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
    public $program;
    public $Project;
    public $Aproved_hour;
    public $Consumed_hours;
    public $Task;
    public $Estimated_hours;
    public $consumed_hours;
    public $approval_status;
    public $chkHrs;
    public $estHrsradio;
    public $hoursArray;
    public $unqid;
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
                    array('pid, sub_project_name, sub_project_description,status,priority,requester', 'required'),
                    array('pid, created_by, updated_by, is_deleted,approval_status', 'numerical', 'integerOnly'=>true),
                    array('sub_project_name, sub_project_description, requester', 'length', 'max'=>250),
                    array('total_hr_estimation_hour', 'numerical' ),
                    // The following rule is used by search().
                    // @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
                    array('spid, pid, project_id, sub_project_name, sub_project_description, requester, estimated_end_date, total_hr_estimation_hour, ,estimated_start_date , created_by, created_date, updated_by, updated_date,project_name,approval_status,status', 'safe', 'on'=>'search'),
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
                    'project_id' => 'Project Id',
                    'sub_project_name' => 'Project name',
                    'sub_project_description' => 'Project Description',
                    'requester' => 'Requester',
                    'estimated_end_date' => 'Estimated End Date',
                    'total_hr_estimation_hour' => 'Total Hr Estimation Hour',
                    'estimated_start_date' =>'Estimated Start Date',
                    'status' =>'Status',
                    'priority' =>'Priority',
                    'created_by' => 'Created By',
                    'created_date' => 'Created Date',
                    'updated_by' => 'Updated By',
                    'updated_date' => 'Updated Date',
                    'is_deleted' => 'Is Deleted',
                    'approval_status' => 'Approval Status',
                    'estHrsradio' => 'How would you like to add Estimated Hours?'
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
//                $criteria->select = "t.*,pr.project_name,t.spid as taskId ";//t.spid as taskId
            $criteria->compare('spid',$this->spid);
            $criteria->compare('t.project_id',$this->project_id);
            $criteria->compare('pr.project_name',$this->pid, true);
            $criteria->compare('sub_project_name',$this->sub_project_name,true);
            $criteria->compare('sub_project_description',$this->sub_project_description,true);
            $criteria->compare('t.requester',$this->requester,true);
            $criteria->compare('estimated_end_date',$this->estimated_end_date,true);
            $criteria->compare('total_hr_estimation_hour',$this->total_hr_estimation_hour,true);
            //$criteria->compare('created_by',$this->created_by);
            $criteria->compare('concat(cEmp.first_name, " ", cEmp.last_name)',$this->created_by, true);
            $criteria->compare('created_date',$this->created_date,true);
            $criteria->compare('concat(mEmp.first_name, " ", mEmp.last_name)',$this->updated_by, true);
            $criteria->compare('t.approval_status',$this->approval_status,true);
            $criteria->compare('t.status',$this->status, true);
            $criteria->compare('updated_date',$this->updated_date,true);
            $criteria->compare('is_deleted',$this->is_deleted);
            $criteria->compare('project_name',$this->project_name);
            $criteria->order = 'approval_status desc';
            $criteria->join = "INNER join tbl_project_management pr ON (pr.pid=t.pid) "
                    . " LEFT JOIN tbl_employee as cEmp on t.created_by = cEmp.emp_id "
                    . " LEFT JOIN tbl_employee as mEmp on t.updated_by = mEmp.emp_id ";
//                $criteria->join = "INNER join tbl_project_management pr ON (pr.pid=t.pid)"
//                        . "INNER join tbl_pid_approval as pa ON (t.spid = pa.sub_project_id) ";
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

    public function getCreatedBy($model) {
        $emp = Employee::model()->findByPk($model['created_by']);
        if (!empty($emp))
            return $emp['first_name'] . " " . $emp['last_name'];
    }

    public function getUpdatedBy($model) {
        $emp = Employee::model()->findByPk($model['updated_by']);
        if (!empty($emp))
            return $emp['first_name'] . " " . $emp['last_name'];
    }
	
    public function getCreatedDate($model) {
        return (new Datetime($model['created_date']))->format('d-m-Y');
    }
	
    public function getApprovalStatus($model) {

        $status = '<span style="color:blue"><strong>Pending</strong></span>';
        
        if($model->approval_status == 0)
        {
            $status = '<span style="color:#f00"><strong>Rejected</strong></span>';
        }else if($model->approval_status == 1)
        {
            $status = '<span style="color:#00CC00"><strong>Approved</strong></span>';
        }
        return $status;
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

		public  function getProgram($model){
			$name = ProjectManagement::model()->findByPk($model->pid);

			return $name['project_name'];
		}

    public function getSubProject($project_id)
    {

        $query = "select spid, sub_project_name from tbl_sub_project where is_deleted = 0 and pid = $project_id";

        $project = Yii::app()->db->createCommand($query)->queryRow();
        // print_r($project);die;
        return $project;
    }
}
