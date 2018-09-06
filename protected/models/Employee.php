<?php

/**
 * This is the model class for table "tbl_employee".
 *
 * The followings are the available columns in table 'tbl_employee':
 * @property string $emp_id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $access_type
 * @property string $access_rights
 * @property string $created_date
 * @property string $modified_date
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $modified_by
 */
class Employee extends CActiveRecord {

    public $repeat_password;
    public $emp_name;
    public $new_password;
    public $confirm_new_password;
    public $verifyCode;
    public $activity_time;
    public $count_all;
    public $completed_all;
    public $count_today;
    public $completed_today;
    public $count_today_pending;
    public $count_today_rejected;
    public $count_today_progress;
    public $count_today_partially_completed;
    public $count_today_scheduled_an_date;
    public $count_today_no_change;
    public $file;
    public $mobile;
    public $count_till_today;
    public $completed_count_till_today;
    public $completed_count_today;

    const WEAK = 0;
    const STRONG = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_employee';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, first_name, last_name', 'required'),
            array('is_active, is_deleted, created_by, modified_by, access_type', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 150),
            array('password, repeat_password, access_type', 'required', 'on' => 'create'),
            array('access_type', 'required', 'on' => 'update'),
            array('password, repeat_password', 'required', 'on' => array('register')),
            array('password, confirm_new_password, new_password', 'required', 'on' => array('changePwd')),
            array('confirm_new_password, new_password', 'length', 'max' => 50, 'min' => 8, 'on' => array('changePwd')),
            //array('password, new_password', 'length', 'max' => 50, 'min' => 6),
            array('password, new_password', 'length', 'max' => 50),
            array('password', 'length', 'max' => 50, 'min' => 8, 'on' => array('update', 'create')),
            array('first_name, middle_name, last_name', 'length', 'max' => 30),
            array('password', 'passwordStrength', 'strength' => self::STRONG, 'on' => array('register', 'create')),
            array('password', 'passwordStrengthupdate', 'strength' => self::STRONG, 'on' => array('update')),
            array('new_password', 'passwordStrength', 'strength' => self::STRONG, 'on' => array('changePwd')),
            //array('access_type', 'length', 'max'=>1),
            array('modified_date, mobile, field_engg_added_by', 'safe'),
            array('email', 'unique', 'message' => 'Email already exists!'),
            array('password, repeat_password', 'length', 'max' => 50, 'min' => 8, 'on' => array('register')),
            array('email', 'email'),
            array('password', 'compare', 'compareAttribute' => 'repeat_password', 'on' => array('register', 'create')),
            array('confirm_new_password', 'compare', 'compareAttribute' => 'new_password', 'on' => array('changePwd'), 'message' => 'New Password and Confirm New Password fileds should match'),
            // The fo 18:03 llowing rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('confirm_new_password, new_password, password', 'safe', 'on' => 'changePwd'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => array('register')),
            //array('verifyCode', 'validateCaptcha','on'=>array('register')),
            array('emp_id, email, password, first_name, middle_name, last_name, access_type, access_rights, created_date, modified_date, is_active, is_deleted, created_by, modified_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * check if the user password is strong enough
     * check the password against the pattern requested
     * by the strength parameter
     * This is the 'passwordStrength' validator as declared in rules().
     */
    public function passwordStrength($attribute, $params) {
        if ($params['strength'] === self::WEAK) {
            $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';
        } elseif ($params['strength'] === self::STRONG) {
            //  $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
            $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,}$/';
        }

        if (!preg_match($pattern, $this->$attribute))
            $this->addError($attribute, 'your password is not strong enough!');
    }

    public function passwordStrengthupdate($attribute, $params) {
        if ($params['strength'] === self::WEAK) {
            $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';
        } elseif ($params['strength'] === self::STRONG) {
            //  $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
            $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,}$/';
        }

        if ($this->$attribute != '') {
            if (!preg_match($pattern, $this->$attribute))
                $this->addError($attribute, 'your password is not strong enough!');
        }
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

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
        $criteria = new CDbCriteria;
//        $criteria->join = 'LEFT JOIN tbl_roles_manager rm ON rm.id=t.access_type';
        $criteria->select = "emp_id,email,first_name,middle_name,last_name";
        $criteria->compare('emp_id', $this->emp_id, true);
        $criteria->compare('email', $this->email, true);
        //$criteria->compare('password',$this->password,true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('middle_name', $this->middle_name, true);
        $criteria->compare('last_name', $this->last_name, true);
//        $criteria->compare('access_type', $this->access_type, true);
        //$criteria->compare('access_rights',$this->access_rights,true);
        //$criteria->compare('created_date',$this->created_date,true);
        //$criteria->compare('modified_date',$this->modified_date,true);
//        $criteria->compare('is_active', 1);
//        $criteria->compare('t.is_deleted', 0);
        //$criteria->compare('created_by',$this->created_by);
        //$criteria->compare('modified_by',$this->modified_by);

        /* return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
          )); */

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize'])
            ),
        ));
    }

    public function viewUploadedFieldEngineers($rowsUploaded) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->join = 'INNER JOIN tbl_employee_detail ON (tbl_employee_detail.emp_id = t.emp_id)';
        $criteria->select = "t.first_name, t.last_name, tbl_employee_detail.mobile";
        $criteria->compare('t.first_name', $this->first_name, true);
        $criteria->compare('t.last_name', $this->last_name, true);
        $criteria->compare('tbl_employee_detail.mobile', $this->mobile, true);
        $criteria->addInCondition('t.emp_id', $rowsUploaded);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100000,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Employee the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Save before data
     * @return type saver data
     */
//    public function beforeSave() {
//        $this->password = md5($this->password);
//        return parent::beforeSave();
//    }

    public static function getUsersListByRole($role_name) {
        $criteria = new CDbCriteria;
        $getIntegratorList = array();
        $roleLikeArr = array();
        $roleLike = " t.is_deleted = 0 AND rm.name = '" . trim($role_name) . "'";
        $criteria->join = 'INNER JOIN tbl_roles_manager rm ON rm.id=t.access_type';
        $criteria->select = "t.emp_id , CONCAT(t.first_name,' ',t.last_name,' (',t.email,')') as emp_name";
        $criteria->order = " emp_name ASC";

        if (!empty($roleLike)) {
            $criteria->addCondition($roleLike);
        }

        $getData = self::model()->findAll($criteria);



        if (isset($getData)) {
            foreach ($getData as $user) {
                $getIntegratorList[$user->emp_id] = $user->emp_name;
            }
        }
        return $getIntegratorList;
    }

    public static function getUsersListByRoleWithActivity($role_name, $ai_status) {


        if (isset($ai_status) && ( $ai_status == 2 || $ai_status == 3)) {
            $addcon = "is_auto_int = '1' ";
        } else {
            $addcon = "user_type = '1' ";
        }

        $result = Yii::app()->db->createCommand()
                ->select('*')
                ->from('allocate_request_user')
                ->where($addcon)
                ->order('emp_name ASC')
                ->queryAll();


        return $result;

//
//        $criteria = new CDbCriteria;
//        $getIntegratorList = array();
//        $roleLikeArr = array();
//      //  $roleLike = " t.is_deleted = 0 AND rm.name = '" . trim($role_name) . "'";
//        //$criteria->join = 'INNER JOIN tbl_roles_manager rm ON rm.id=t.access_type LEFT JOIN `tbl_assigned_sites` `as` on t.emp_id = as.allocated_to ';
//        $criteria->join = ' LEFT JOIN `tbl_assigned_sites` `as` on t.emp_id = as.allocated_to ';
//
//        $criteria->select = "t.emp_id,
//                                CONCAT(t.first_name,' ',t.last_name) as emp_name,
//                                t.email as email,
//                                t.login_status,
//                                SEC_TO_TIME(TIMESTAMPDIFF(SECOND,t.activity_time,CURRENT_TIMESTAMP())) as activity_time,
//                                count(as.allocated_to) count_all,
//                                sum(case when as.status = '1' then 1 else 0 end) as completed_all,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() then 1 else 0 end) as count_today,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status != '2' AND as.status != '1' then 1 else 0 end) as count_today_pending,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status = '2' then 1 else 0 end) as count_today_rejected,
//                                sum(case when as.status = '1' and DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() then 1 else 0 end) as completed_today ,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status = '6' then 1 else 0 end) as count_today_progress,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status = '3' then 1 else 0 end) as count_today_partially_completed,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status = '4' then 1 else 0 end) as count_today_scheduled_an_date,
//                                sum(case when DATE_FORMAT(as.allocated_date, '%Y-%m-%d') = CURDATE() AND as.status = '0' then 1 else 0 end) as count_today_no_change";
//        $criteria->group = " t.emp_id ";
//        $criteria->order = " count_today DESC, count_today_no_change ASC , emp_name ASC";
//        $criteria->addCondition(" t.is_deleted = 0 ");
//        if (isset($ai_status) && ( $ai_status == 2 || $ai_status == 3))
//            $criteria->addCondition(" t.is_auto_int = '1' ");
//        else
//            $criteria->addCondition(" user_type = '1' ");
//
//
//
//
//        $getData = self::model()->findAll($criteria);
//
//        return $getData;
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function validateCaptcha() {
        $captcha = Yii::app()->getController()->createAction("captcha");
        $code = $captcha->verifyCode;
        if ($code !== $this->verifyCode) {
            CHelper::setFlashError("Verify code you have entered is incorrect.");
        }
    }

    public static function getUserFullNameById($id) {
        $model = Employee::model()->findByPk($id);
        return $model->first_name . ' ' . $model->last_name;
    }

    public function setLogingStatus($id, $status = 0) {
        $model = Employee::model()->findByPk($id);
        $status = !empty($status) ? 1 : 0;
        $model->login_status = $status;
        $model->save(true, array('login_status'));
    }

    public function setActivityStamp($id) {
//        $user = Employee::model()->findByPk($id);
//        $user->activity_time = Date("Y-m-d H:i:s");
//        $user->save(true, array('activity_time'));
    }

    public function getadmin() {
        $user = Yii::app()->session['login']['user_id'];
        $result = Employee::model()->findByAttributes(array('emp_id' => $user, 'access_type' => '1'));
        return $result;
    }

    public function getUserName($id) {
        $name = Yii::app()->db->createCommand()
                ->select('CONCAT(first_name,last_name) as full_name')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $id))
                ->queryRow();

        return $name;
    }

// this list only for allocated task employee
    public static function getEmloyeeList($project_id = '') {
//        $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1'));
//        $emp_list = array();
//        foreach ($employeeData as $key => $value) {
//            $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
//        }

        $query = "select allocated_resource from tbl_resource_allocation_project_work  where pid ='{$project_id}' ";
        $list = Yii::app()->db->createCommand($query)->queryRow();
        $newList = explode(",", $list['allocated_resource']);
        if (!$project_id) {
            $query = "select allocated_resource from tbl_resource_allocation_project_work";
            $list = Yii::app()->db->createCommand($query)->queryAll();
            foreach ($list as $key => $list_empid) {
                $emp_id = explode(",", $list_empid['allocated_resource']);
                foreach ($emp_id as $k => $id) {
                    $newList[$id] = $id;
                }
            }
        }


        $emp_list = array();
        foreach ($newList as $key => $value) {
            if ($value) {
                $e_list = Yii::app()->db->createCommand("select * from tbl_employee where emp_id ={$value}")->queryRow();
                $emp_list[$value] = $e_list['first_name'] . " " . $e_list['last_name'];
            }
        }
        return $emp_list;
    }

// name in array with containg firstname and last name
    public function returnEmp_id($name) {
        if (isset($name)) {
            $first_name = $name['first_name'];
            $last_name = $name['last_name'];
            $e_list = Yii::app()->db->createCommand("select emp_id from tbl_employee where first_name like '{$first_name}' and last_name like '{$last_name}'")->queryRow();

            return $e_list['emp_id'];
        }
    }

    public function getConcatened() {
        //return $this->name;
        return ucwords($this->name . ' (' . $this->level_name . ')');
    }

    public function fetchEmployee($project_id,$emp_id) {

        $query = "select allocated_resource from tbl_resource_allocation_project_work  where pid =$project_id";
        $allocated_resource = Yii::app()->db->createCommand($query)->queryRow();
        $resource['options_data'] = array();
        if ($allocated_resource && trim($allocated_resource['allocated_resource']) != '') {

            $query1 = "select emp.emp_id,concat(concat(first_name,' ',last_name),' ',CONCAT('(', lm.level_name ,')') ) as name,lm.level_name, lm.budget_per_hour
                                                from tbl_employee emp
                                                left join tbl_assign_resource_level	rl on rl.emp_id = emp.emp_id
                                                left join tbl_level_master lm on lm.level_id = rl.level_id
                                                where emp.emp_id in ({$allocated_resource['allocated_resource']}) order by first_name";
            $resource['emp_list'] = Yii::app()->db->createCommand($query1)->queryAll();

            foreach ($resource['emp_list'] as $value => $name) {
                if($name['emp_id'] == $emp_id)
                {
                    $options_data[$name['emp_id']] = array('id' => $name['budget_per_hour'],'selected'=>true);
                }else{
                    $options_data[$name['emp_id']] = array('id' => $name['budget_per_hour']);
                }
            }

            $resource['options_data'] = $options_data;
        }
        return $resource;
    }
}
