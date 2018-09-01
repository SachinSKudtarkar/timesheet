<?php

/**
 * This is the model class for table "db_rjilautots_dev.tbl_pid_approval".
 *
 * The followings are the available columns in table 'db_rjilautots_dev.tbl_pid_approval':
 * @property integer $pid_id
 * @property integer $project_id
 * @property integer $sub_project_id
 * @property string $inception_date
 * @property integer $total_est_hrs
 * @property string $comments
 * @property integer $status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $approved
 * @property integer $is_deleted
 */
class PidApproval extends CActiveRecord {

    public $project_description;
    public $sub_project_name;
    public $emp_id;
    public $task_id;
    public $sub_task_id;
    public $est_hrs;
    public $sub_task_name;
    public $sr;

    //public $
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_pid_approval';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('project_id, sub_project_id, inception_date, total_est_hrs, jira_id, project_task_id, task_title, task_description', 'required'),
            array('project_id, sub_project_id, total_est_hrs, status, created_by, approved, is_deleted', 'numerical', 'integerOnly' => true),
            array('comments, task_description', 'length', 'max' => 255),
            array('jira_id', 'unique', 'message' => 'jira id already exists!'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('pid_id, project_id, sub_project_id, project_description, sub_project_name, inception_date, total_est_hrs, comments, status, created_by, created_at, approved, is_deleted, comments, created_by, created_at, approved, is_deleted,jira_id,project_task_id, task_title, task_description', 'safe', 'on' => 'search'),
        );
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'pid_id' => 'Sr.No',
            'project_task_id' => 'Project Task Id',
            'project_id' => 'Program',
            'sub_project_id' => 'Project',
            'task_title' => 'Task Title',
            'task_description' => 'Task Description',
            'inception_date' => 'Inception Date',
            'jira_id' => 'Jira Id',
            'total_est_hrs' => 'Total Estimated Hours',
            'comments' => 'Comments',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'approved' => 'Approved',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('pid_id', $this->pid_id);
        $criteria->compare('project_id', $this->project_id);
        $criteria->compare('sub_project_id', $this->sub_project_id);
        $criteria->compare('inception_date', $this->inception_date, true);
        $criteria->compare('jira_id', $this->jira_id, true);
        $criteria->compare('total_est_hrs', $this->total_est_hrs);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('approved', $this->approved);
        $criteria->compare('is_deleted', $this->is_deleted);
        $criteria->join = " LEFT JOIN tbl_project prj ON (t.project_id = prj.id) "
                . " LEFT JOIN tbl_sub_project sprj ON (t.sub_project_id = sprj.spid)";
//                        . " LEFT JOIN tbl_sub_task st ON (t.pid_id = st.pid_approval_id)";
//                $criteria->join = " LEFT JOIN tbl_sub_project sprj ON (t.sub_project_id = sprj.spid)";
        $criteria->select = "t.*, prj.project_description, sprj.sub_project_name";
//                        . ", st.task_id, st.sub_task_name, st.emp_id ";

        $criteria->order = "t.pid_id desc";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PidApproval the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getTaskAssignedEmployee($model) {
        if (!empty($model->pid_id)) {
            $modelSTask = SubTask::model()->findAllByAttributes(array('pid_approval_id' => $model->pid_id), array('select' => 'emp_id'));
            if (!empty($modelSTask)) {
                foreach ($modelSTask as $value) {
                    $emps[] = $this->getEmpName($value->emp_id);
                }
            } else {
                return "-";
            }
            return implode(" \n\r<br/> ", $emps);
        }
    }

    public function getEmpName($pmodel) {
        $emp = Employee::model()->findByPk($pmodel['emp_id']);
        if (!empty($emp))
            return $emp['first_name'] . " " . $emp['last_name'];
    }

    public function getProjectDescription($pmodel) {
        if ($pmodel['project_id']) {
            $project = ProjectManagement::model()->findByPk($pmodel['project_id']);
            return $project['project_name'];
        }
        return '';
    }

    public function getSubProjectDescription($pmodel) {
        if ($pmodel['sub_project_id']) {
            $project = SubProject::model()->findByPk($pmodel['sub_project_id']);
            return $project['sub_project_name'];
        }
        return '';
    }

    public function getTaskDescription($pmodel) {
        if ($pmodel['task_id']) {
            $project = Task::model()->findByPk($pmodel['task_id']);
            return $project['task_name'];
        }
        return '';
    }

    public function getSubTaskDescription($pmodel) {
        if ($pmodel->sub_task_id) {
            $project = SubTask::model()->findByPk($pmodel->sub_task_id);
            return $project['sub_task_name'];
        }
        return '';
    }

    public function pidApprovalbtn($model) {
        $baseUrl = Yii::app()->getBaseUrl(true);
        if ($model['sr']) {
            $project = PidApproval::model()->findByPk($model['sr']);
            $approvalStatus = $project['approved'];
            $accessType = Yii::app()->session['login']['access_type'];
            // CHelper::debug($accessType);
            /* if($accessType==1){
              if($approvalStatus==0){
              return CHtml::link('Send for Approval', array('#'), array('class' => 'approval btn btn-primary mr2', 'data-value' => $model['sr'].'-'.'1', 'style' => 'width:150px;', 'data-toggle' => 'modal', 'data-target' => '#approval',));
              }elseif($approvalStatus==1){
              return "Approval Pending";
              }elseif($approvalStatus==2){
              return "Approved";
              }elseif($approvalStatus==3){
              $reapproval =  CHtml::link('Send for ReApproval', array('javascript:void(0);'), array('class' => 'approval btn btn-primary mr2', 'data-value' => $model['sr'].'-'.'1', 'style' => 'width:150px;', 'data-toggle' => 'modal', 'data-target' => '#approval',));
              $editlink =  CHtml::link('Edit', array("/pidapproval/update/".$model['sr']) );
              return "Rejected"."  ".$reapproval."<br/>  ".$editlink;
              }
              } */
            if ($accessType == 1) {
                if ($approvalStatus == 1) {
                    $approved = CHtml::link('Approve', array('javascript:void(0);'), array('class' => 'approval btn btn-primary mr2', 'data-value' => $model['sr'] . '-' . '2', 'style' => 'width:70px;', 'data-toggle' => 'modal', 'data-target' => '#approval',));
                    $reject = CHtml::link('Reject', array('javascript:void(0);'), array('class' => 'approval btn btn-primary mr2', 'data-value' => $model['sr'] . '-' . '3', 'style' => 'width:70px;', 'data-toggle' => 'modal', 'data-target' => '#rejected',));
                    return $approved . "&nbsp; <br/> " . $reject;
                } elseif ($approvalStatus == 2) {
                    return "Approved";
                } elseif ($approvalStatus == 3) {
                    return "Rejected";
                }
            }
        }
    }
}
