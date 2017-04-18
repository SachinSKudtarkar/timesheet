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
class AllocateUser extends CActiveRecord {

    public $repeat_password;
    public $emp_name;
    public $new_password;
    public $confirm_new_password;
    public $user_type;
 
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
            array('password, new_password', 'length', 'max' => 50, 'min' => 6),
            array('first_name, middle_name, last_name', 'length', 'max' => 30),
            //array('access_type', 'length', 'max'=>1),
            array('modified_date', 'safe'),
          
            array('email', 'unique','message'=>'Email already exists!'),
            array('email', 'email'),
        /*    array('email', 'match' ,
	    'pattern'=> '(\W|^)[\w.+\-]{0,25}@(yahoo|hotmail|gmail)\.com(\W|$)',
	    'message'=> 'Email address should only in the format @cisco.com OR @rjil.com.'
	    ), */
            array('password', 'compare', 'compareAttribute' => 'repeat_password', 'on' => array('register', 'create')),
            array('confirm_new_password', 'compare', 'compareAttribute' => 'new_password', 'on' => array('changePwd'),'message'=>'New Password and Confirm New Password fileds should match'),
            // The fo 18:03 llowing rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('confirm_new_password, new_password, password', 'safe', 'on' => 'changePwd'),
            array('emp_id, email, password, first_name, middle_name, last_name, access_type, access_rights, created_date, modified_date, is_active, is_deleted, created_by, modified_by, user_type', 'safe', 'on' => 'search'),
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
            'user_type' => 'Select User',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'repeat_password' => 'Confirm Password',
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
    
     /*
     * Edited By Vishal G on 29/12/2014 5:51PM
     * ADD is_auto_int status flag
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        if (isset($_GET['pageSize'])) {             
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
        $criteria = new CDbCriteria;
        $criteria->join = 'LEFT JOIN tbl_roles_manager rm ON rm.id=t.access_type';
        $criteria->select = "is_auto_int,user_type,emp_id,email,first_name,middle_name,last_name,access_type,if(t.access_type='1','Admin',if(t.access_type='0','Individual',rm.name)) as access_type";
        $criteria->compare('emp_id', $this->emp_id, true);
        $criteria->compare('email', $this->email, true);
        //$criteria->compare('password',$this->password,true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('middle_name', $this->middle_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('access_type', $this->access_type, true);
        //$criteria->compare('access_rights',$this->access_rights,true);
        //$criteria->compare('created_date',$this->created_date,true);
        //$criteria->compare('modified_date',$this->modified_date,true);
        $criteria->compare('is_active', 1);
        $criteria->compare('t.is_deleted', 0);
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
    public function beforeSave() {
        $this->password = md5($this->password);
        return parent::beforeSave();
    }

    public static function getUsersListByRole($role_name) {
        $criteria = new CDbCriteria;
        $getIntegratorList = array();
        $roleLikeArr = array();
        $roleLike = " t.is_deleted = 0 AND rm.name = '".trim($role_name)."'";
        $criteria->join = 'INNER JOIN tbl_roles_manager rm ON rm.id=t.access_type';
        $criteria->select = "t.emp_id , CONCAT(t.first_name,' ',t.last_name,' (',t.email,')') as emp_name";
        $criteria->order = " emp_name ASC";
        
//        if (isset($roleType)) {
//            foreach ($roleType as $role) {
//                $roleLikeArr[] = "rm.access_rights LIKE '%{$role}%'";
//            }
//            $roleLike .= implode(' AND ', $roleLikeArr);
//        }
       
        if (!empty($roleLike)) {
            $criteria->addCondition($roleLike);
        }
        
        $getData = self::model()->findAll($criteria);
        
       /* $getData = new CActiveDataProvider(self::model(), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10000, // or another reasonable high value...
            ),
        ));*/

        //$getData = $models->getData();

        if (isset($getData)) {
            foreach ($getData as $user) {
                $getIntegratorList[$user->emp_id] = $user->emp_name;
            }
        }
        return $getIntegratorList;
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public static function getUserFullNameById($id) {
        $model = Employee::model()->findByPk($id);
        return $model->first_name . ' ' . $model->last_name;
    }

}
