<?php

/**
 * This is the model class for table "tbl_resource_allocation_project_work".
 *
 * The followings are the available columns in table 'tbl_level_master':
 * @property integer $level_id
 * @property string $level_name
 * @property string $budget_per_hour
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $updated_at
 * @property integer $is_deleted
 */
class LevelResourceAllocation extends CActiveRecord {

    public $level_id;
    public $emp_id;
    public $created_at;
    public $created_by;
    public $modified_by;
    public $modified_at;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_assign_resource_level';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('emp_id, level_id', 'required'),
            array('level_id, emp_id, level_id, created_by, modified_by, created_at, updated_at', 'numerical', 'integerOnly' => true),
            //array('l', 'length', 'max'=>250),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
            array('id,emp_id, level_id,created_at,updated_at,created_by,modified_by', 'safe', 'on' => 'search'),
        );
    }

    /**
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
            'id' => 'Resource Level Id',
            'emp_id' => 'Employee Id',
            'level_id' => 'Level Id',
            'created_by' => 'Created By',
            'created_at' => 'Created Date',
            'modified_by' => 'Modified By',
            'updated_at' => 'Updated Date'
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
//                $criteria->select = "t.*,pr.project_name,t.spid as taskId ";//t.spid as taskId
        $criteria->compare('id', $this->id);
        //$criteria->compare('pid',$this->pid);
        $criteria->compare('emp_id', $this->emp_id, true);
        $criteria->compare('level_id', $this->level_id, true);
        $criteria->compare('concat(cEmp.first_name, " ", cEmp.last_name)', $this->created_by, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('concat(mEmp.first_name, " ", mEmp.last_name)', $this->modified_by, true);
        $criteria->compare('modified_by', $this->modified_by, true);
        $criteria->order = 'created_at desc';
        $criteria->join = " LEFT JOIN tbl_employee as cEmp on t.created_by = cEmp.emp_id "
                . " LEFT JOIN tbl_employee as mEmp on t.modified_by = mEmp.emp_id ";
        /*                 $criteria->join = "INNER join tbl_level_master lv ON (lv.level_id=t.level_id) ";
          $criteria->join = "INNER join tbl_employee em ON (em.emp_id=t.emp_id)"; */
//                        . "INNER join tbl_pid_approval as pa ON (t.spid = pa.sub_project_id) ";
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResourceAllocationProjectWork the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function projectStatus() {
        $query = " SELECT pm.pid,pm.project_name,rap.allocated_resource,day.comment,day.day FROM tbl_project_management pm  "
                . " INNER JOIN tbl_resource_allocation_project_work rap "
                . " ON (pm.pid = rap.pid) "
                . " INNER JOIN tbl_day_comment day"
                . " ON (pm.pid = day.pid) ";
        $result = Yii::app()->db->createCommand($query)->queryAll();
        return $result;
    }

    public function renderButtonsSelectProject() {

        echo CHtml::link("Select Another Project", Yii::app()->createUrl("resourceallocationprojectwork/resourcearrangement"));
    }

    public function getCreatedBy($model) {
        $emp = Employee::model()->findByPk($model['created_by']);
        if (!empty($emp))
            return $emp['first_name'] . " " . $emp['last_name'];
    }

    public function getModifiedBy($model) {
        $emp = Employee::model()->findByPk($model['modified_by']);
        if (!empty($emp))
            return $emp['first_name'] . " " . $emp['last_name'];
    }

    public function getAllocateHrLink($model, $row) {
        if ($model->status == 'new')
            return 'N/A';

        return CHtml::link("View Feedback", array('#'), array('data-toggle' => "modal", 'data-target' => "#", 'class' => 'AllocateHrLink', 'data-key' => $model->spid));
    }

    public function search_forResourceTask() {

        $command = $this->connection_invetory->createCommand("
            SELECT concat(em.first_name,' ',em.last_name) as name,pm.project_name,sb.sub_project_name,sb.estimated_end_date,sb.estimated_start_date,sb.total_hr_estimation_hour,sb.Priority
			FROM tbl_task_allocation as ta inner join tbl_sub_project as sb on(ta.spid = sb.spid), tbl_employee em , tbl_project_management as pm
			WHERE  em.emp_id in (ta.allocated_resource) and sb.pid = pm.pid order by em.first_name;
        ")->queryAll();
        $dataProvider = new CArrayDataProvider($command, array(
            'id' => 'name',
            'sort' => array(
                'defaultOrder' => 'name DESC',
            ),
            'pagination' => array(
                'pageSize' => 15,
            ),
        ));
        return $dataProvider;
    }

}
