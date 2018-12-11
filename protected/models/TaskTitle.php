<?php

/**
 * This is the model class for table "tbl_task_temp".
 *
 * The followings are the available columns in table 'tbl_task_temp':
 * @property string $id
 * @property string $project_id
 * @property string $task_title
 * @property string $task_descriptiom
 * @property string $task_level
 * @property string $task_est_hrs
 * @property integer $created_by
 * @property integer $created_at
 */
class TaskTitle extends CActiveRecord {

    public $id;
    public $project_id;
    public $task_title;
    public $task_descriptiom;
    public $task_level;
    public $task_est_hrs;
    public $created_by;
    public $created_at;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_task_temp';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'EmployeeDetail' => array(self::HAS_MANY, 'EmployeeDetail', 'emp_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'emp_id' => 'Emp',
            'email' => 'Email',
            'password' => 'Password',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'access_type' => 'User Role',
            'access_rights' => 'Access Rights',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'repeat_password' => 'Confirm Password',
            'verifyCode' => 'Verification Code',
        );
    }


}
