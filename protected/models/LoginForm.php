<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    //public $email;
    public $password;
    public $rememberMe;
    public $email;
    public $verifyCode;
    private $_identity;
    public $authtype;
    public $username;
    public $lastLoginAt = null;
    private $_user = false;
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('email, password', 'required'),
            // array('password', 'length', 'max' => 12, 'min' => 6),
            array('email', 'email', 'message' => 'Email Address is incorrect .'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
            //For forgot username/password
            array('email', 'required', 'on' => 'forgotpassword'),
            array('email', 'isEmailExists', 'on' => 'forgotpassword', 'message' => 'This email is not matching with any email ID'),
            array('email', 'email', 'message' => 'Email you have entered is incorrect', 'on' => 'forgotpassword'),
            //array('verifyCode', 'captcha', 'on' => 'captchaRequired'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'forgotpassword'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'login_with_captcha'),
                // array('verifyCode', 'validateCaptcha'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => 'Remember me next time',
            'verifyCode' => 'Verification Code',
            'email' => 'Email Address',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password, $this->authtype);
            // authenticate user
            if (!$this->_identity->authenticate())
                CHelper::setFlashError("Username/password you have entered is incorrect.");
            //$this->addError('password','Incorrect username or password.');
        }
    }

    public function validateCaptcha() {
        $captcha = Yii::app()->getController()->createAction("captcha");
        $code = $captcha->verifyCode;
        if ($code !== $this->verifyCode) {
            CHelper::setFlashError("Verify code you have entered is incorrect.");
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password, $this->authtype);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? ( 3600 * 24 * 30 ) : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else {
            return false;
        }
    }

    public function isEmailExists($attribute, $params) {
        $count = Employee::model()->count('email=:email', array(':email' => $this->$attribute));
        if ($count != 1)
            $this->addError($attribute, 'This email is not matching with any email ID from system!');
    }

    public function getUser() {
        if ($this->_user === false) { 
            $this->_user = $this->findByUsername($this->username, $this->lastLoginAt);
			return $this->_user;
        }
    }

    public function findByUsername($username ,$lastLoginAt) {
	//AND lastlogindatetime ='{$lastLoginAt}'
        $query = "SELECT * FROM tbl_employee where email = '{$username}' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        return $result;
    }

    public function autoLogin() {

        $this->_user = $this->getUser(); // && $this->_user['lastlogindatetime'] == $this->lastLoginAt
        if ($this->_user) { 
//            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0); 
            return 1;
        }
        return FALSE;
    }

}

?>
