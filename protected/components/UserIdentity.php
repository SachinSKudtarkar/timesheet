<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the email and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public $authtype;
    public $email;

    public function __construct($email, $password, $authtype) {
        $this->email = $email;
        $this->password = $password;
        $this->authtype = $authtype;
        $this->username = $email;
    }

    public function authenticate() {
        // set criteria to get blacklist count
        //join the tbl_user and tbl_user_access to get details from bothe table

        $user = Yii::app()->db->createCommand()
                ->select('te.emp_id, te.first_name, te.last_name,te.email, te.password,te.access_type,te.is_duo_added ,If(te.access_type=0,te.access_rights,rm.access_rights) as access_rights, te.portal_survey')
                ->from('tbl_employee te')
                ->leftjoin('tbl_roles_manager rm', 'rm.id=te.access_type')
                ->where('te.email=:email AND te.is_deleted=:is_deleted AND te.is_active=:is_active', array(':email' => $this->email, ':is_deleted' => 0, ':is_active' => 1))
                ->order('te.access_type ASC')
                ->queryAll();

        #----------------------
        # Default Time log Disabled for All Users
        #----------------------

        if (count($user) == 0) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (((md5($this->password)) != $user[0]['password']) && ($this->password != $user[0]['password'])) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID; 
        } else { 
            // User/pass match
            $this->errorCode = self::ERROR_NONE;
            #----------------------------
            # Get All Acess Rights For Supper Admin
            #----------------------------
            $user[0]['access_rights'] = unserialize($user[0]['access_rights']);

            //add the value to session 			
            $data = array(
                'user_id' => $user[0]['emp_id'],
                'first_name' => $user[0]['first_name'],
                'last_name' => $user[0]['last_name'],
                'email' => $user[0]['email'],
                'is_duo_added' => $user[0]['is_duo_added'],
                'access_type' => $user[0]['access_type'],
                'access_rights' => $user[0]['access_rights'],
                'survey_taken_count' => $user[0]['portal_survey'],
            );

            Yii::app()->user->id = $user[0]['emp_id'];

            // set session for user
            Yii::app()->session['login'] = $data;
          
            //Survey show only for 10 users
//            $portalcnt = PortalSurvey::model()->count();
//            if($portalcnt >= 11){
//                $_SESSION['login']['survey_taken_count'] = 5;
//            }
            //unset login cookie
            //unset($_COOKIE['login']);
            CHelper::removeCookie("login");
            // Reagain set login cookies
            ///////setcookie("login", "", time() - 3600);
            CHelper::setCookie("login", "", (time() - 3600));
            $user_access_id = $user[0]['emp_id'];

            #---------------------------------
            # Accitvity Log Disable now, No Need for Loggin
            #-------------------------------
            //insert login action to activity log
            //CLog::setActivityLog($user['user_id'], $user_access_id, 0, 0, "Login as super admin ( UserId:".$user['user_id'].")");
        }
        if ($this->errorCode >= 0) {
            if (isset($_COOKIE['login'])) {
                if ($_COOKIE['login'] < 10) { //put attempt =2 while make live
                    #---------------------------------
                    # Set Cookies for Attemp Count by User
                    #-------------------------------
                    $attempts = $_COOKIE['login'] + 1;
                    ///setcookie('login', $attempts, ( time() + 60 * 30)); //set the cookie for 30 minutes with the number of attempts stored
                    CHelper::setCookie("login", $attempts, (time() + 60 * 30));
                } else {

                    Yii::app()->controller->redirect(array('//login'));
                    //End for Send mail
                }
            } else {
                ////setcookie('login', 1, time() + 60 * 30); //set the cookie for 30 minutes with the initial value of 1
                CHelper::setCookie("login", 1, (time() + 60 * 30));
            }
        }
        return !$this->errorCode;
    }

}
