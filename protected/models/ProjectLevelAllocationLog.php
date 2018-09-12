<?php

/**
 * This is the model class for table "tbl_project_level_allocation_log".
 *
 * The followings are the available columns in table 'tbl_level_master':
 * @property integer $rl_id
 * @property integer $rl_log_id
 * @property integer $project_id
 * @property integer $level_id
 * @property integer $old_level_hours
 * @property integer $new_level_hours
 * @property text $comments
 * @property string $created_at
 * @property integer $modified_by
 * @property string $updated_at
 * @property integer $is_deleted
 */
class ProjectLevelAllocationLog extends CActiveRecord{

    public $rl_id;
    public $rl_log_id;
    public $project_id;
    public $level_id;
    public $old_level_hours;
    public $new_level_hours;
    public $comments;
    public $created_at;
    public $modified_by;
    public $updated_at;
    public $is_deleted;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_project_level_allocation_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rl_id, rl_log_id,project_id,level_id,old_level_hours,new_level_hours,comments', 'required'),
            array('rl_id, rl_log_id,project_id,level_id,old_level_hours,new_level_hours, created_by, modified_by, created_at, updated_at', 'numerical', 'integerOnly' => true),
            //array('l', 'length', 'max'=>250),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
            array('rl_id, rl_log_id,project_id,level_id,old_level_hours,new_level_hours,created_at,updated_at,created_by,modified_by', 'safe', 'on' => 'search'),
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
            'rl_id' => 'Resource Level Id',
            'rl_log_id' => 'RL Log Id',
            'project_id' => 'Project Id',
            'level_id' => 'Level Id',
            'old_level_hours' => 'Old Level Hours',
            'new_level_hours' => 'New Level Hours',
            'comments' => 'Comments',
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


}
