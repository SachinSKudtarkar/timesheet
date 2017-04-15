<?php

/**
 * This is the model class for table "_user".
 *
 * The followings are the available columns in table '_user':
 * @property string $id
 * @property string $hash
 * @property string $language
 * @property string $locale
 * @property string $timezone
 * @property string $username
 * @property string $serializedavatardata
 * @property integer $isactive
 * @property integer $isrootuser
 * @property integer $hidefromselecting
 * @property integer $issystemuser
 * @property integer $hidefromleaderboard
 * @property string $lastlogindatetime
 * @property string $permitable_id
 * @property string $person_id
 * @property string $currency_id
 * @property string $manager__user_id
 * @property string $role_id
 * @property integer $passwordflag
 */
class User extends CActiveRecord {

    public $password;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('isactive, isrootuser, hidefromselecting, issystemuser, hidefromleaderboard, passwordflag', 'numerical', 'integerOnly' => true),
            array('hash', 'length', 'max' => 60),
            array('language, locale', 'length', 'max' => 10),
            array('timezone, username', 'length', 'max' => 64),
            array('permitable_id, person_id, currency_id, manager__user_id, role_id', 'length', 'max' => 11),
            array('serializedavatardata, lastlogindatetime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, hash, language, locale, timezone, username, serializedavatardata, isactive, isrootuser, hidefromselecting, issystemuser, hidefromleaderboard, lastlogindatetime, permitable_id, person_id, currency_id, manager__user_id, role_id, passwordflag', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'hash' => 'Hash',
            'language' => 'Language',
            'locale' => 'Locale',
            'timezone' => 'Timezone',
            'username' => 'Username',
            'serializedavatardata' => 'Serializedavatardata',
            'isactive' => 'Isactive',
            'isrootuser' => 'Isrootuser',
            'hidefromselecting' => 'Hidefromselecting',
            'issystemuser' => 'Issystemuser',
            'hidefromleaderboard' => 'Hidefromleaderboard',
            'lastlogindatetime' => 'Lastlogindatetime',
            'permitable_id' => 'Permitable',
            'person_id' => 'Person',
            'currency_id' => 'Currency',
            'manager__user_id' => 'Manager User',
            'role_id' => 'Role',
            'passwordflag' => 'Passwordflag',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('hash', $this->hash, true);
        $criteria->compare('language', $this->language, true);
        $criteria->compare('locale', $this->locale, true);
        $criteria->compare('timezone', $this->timezone, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('serializedavatardata', $this->serializedavatardata, true);
        $criteria->compare('isactive', $this->isactive);
        $criteria->compare('isrootuser', $this->isrootuser);
        $criteria->compare('hidefromselecting', $this->hidefromselecting);
        $criteria->compare('issystemuser', $this->issystemuser);
        $criteria->compare('hidefromleaderboard', $this->hidefromleaderboard);
        $criteria->compare('lastlogindatetime', $this->lastlogindatetime, true);
        $criteria->compare('permitable_id', $this->permitable_id, true);
        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('currency_id', $this->currency_id, true);
        $criteria->compare('manager__user_id', $this->manager__user_id, true);
        $criteria->compare('role_id', $this->role_id, true);
        $criteria->compare('passwordflag', $this->passwordflag);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $query = "SELECT * FROM _user where username = '{$username}'";
            $result = Yii::app()->dbinfi->createCommand($query)->queryAll();
          CHelper::dump($result); 
//        $x = User::model()->findByAttributes(array('username' => $username));
        $x = User::model()->findByAttributes(array('username' => $username));
        CHelper::dump($x);
//          return self::findByAttributes (array ('username' => $username, 'isactive' => 1));
//        return self::findOne(['username' => $username, 'isactive' => 1]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return null;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $password;
    }

    /**
     * Get all active users
     * 
     * @return type
     */
    public static function getActiveUsersList() {
        $sql = "SELECT u.id, u.username, concat(p.firstname,' ',p.middlename,' ',p.lastname) "
                . "as emp_name FROM infinity._user u INNER JOIN infinity.person p "
                . "ON u.person_id=p.id where u.isactive=1;";
        $result = self::findBySql($sql)->asArray()->all();
        return $result;
    }

    public static function getEmployeeName($id) {
        $sql = "SELECT concat(p.firstname,' ',p.middlename,' ',p.lastname) "
                . "as emp_name "
                . " FROM _user u INNER JOIN person p "
                . " ON u.person_id=p.id "
                . " where u.id=:user_id;";
        $result = self::findBySql($sql)->addParams([':user_id' => $id])->asArray()->one();

        if (!empty($result['emp_name'])) {
            return $result['emp_name'];
        }
        return null;
    }

}
