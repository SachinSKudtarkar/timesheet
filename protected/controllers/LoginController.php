<?php

class LoginController extends BaseLoginController {

    // $this->theme = "login";
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {

        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        //$this->redirect("http://portal.infinitylabs.in/app/index.php/zurmo/default/login");
       // http://portal.infinitylabs.in/app/index.php/zurmo/default/login

 //print_r('login'); exit
        //to set theme to login page
        
        
        Yii::app()->theme = 'login';
//        $macaddress = CHelper :: getmacAddress();  //Get current mac address
//        $ipaddress = CHelper :: getipAddress();
//        $blockaddress = ($macaddress) ? $macaddress : $ipaddress;


        $model = new LoginForm;
        #---------------------------------------
        # make the captcha required if the unsuccessful 
        # attemps are more of thee.
        #---------------------------------------
        if (Yii::app()->user->getState('attempts-login') > 3) {
            $model->scenario = 'login_with_captcha';
        }


        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            // validate user input and redirect to the previous page if valid 
            //$model->scenario = 'captchaRequired';  
               // CHelper::debug($_POST['LoginForm']);
            if ($model->validate()) {
                // If return Url set will redirct on same
                if ($model->login()) { 
                    Yii::app()->user->setState('attempts-login', 0);
                    if (!empty(Yii::app()->user->returnUrl))
                        $this->redirect(Yii::app()->user->returnUrl);
                    else
                        $this->redirect(array('//employee/admin'));
                }
                else {
                    #---------------------------------------
                    #    Mark attempt login increment if unsuccess attempt
                    #----------------------------------------
                    Yii::app()->user->setState('attempts-login', Yii::app()->user->getState('attempts-login', 0) + 1);
                    if (Yii::app()->user->getState('attempts-login') > 3) {
                        // Only for Enable capcha on unsussess login after validation
                        $model->scenario = 'login_with_captcha';
                    }
                }
            }
        }
        // display the login form

        $session_user_id = Yii::app()->session['login']['user_id'];
    
        if ($session_user_id != '') {
            if (!empty(Yii::app()->user->returnUrl))
                $this->redirect(Yii::app()->user->returnUrl);
            else
                $this->redirect(array('//employee/admin'));
        } else {
            $this->render('//login/login', array('model' => $model));
        }
    }

    /**
     * This is the action to handle forgot password .
     */
    public function actionForgotPassword() {

        $model = new LoginForm('forgotpassword');
        $this->performAjaxValidation($model);
        $model->scenario = 'forgotpassword';
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'forgot-password-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $this->redirect(array('//admin/login'));
            } else {
                // set criteria to get forgot user details with match email in tbl_user
                $email = $_POST['LoginForm']['email'];


                //modify on 7 apr 2014 (add condition for access type and law firm id)
                $forgot_user_details = Yii::app()->db->createCommand()
                        ->select('tu.first_name, tu.email, tu.emp_id')
                        ->from('tbl_employee tu')
                        //->join('tbl_user_access tua', 'tu.user_id=tua.user_id AND tua.is_deleted=0')
                        //->where('tu.email=:email AND tu.is_deleted=:is_deleted AND tu.is_active=:is_active AND tua.lawfirm_id=:lawfirm_id AND tua.access_type in ("A","SA","TR")', array(':email' => $email, ':is_deleted' => 0, ':is_active' => 1, ':lawfirm_id' => 0))
                        ->where('tu.email=:email and is_deleted=:is_deleted and is_active=:is_active', array(':email' => $email, ':is_deleted' => 0, ':is_active' => 1))
                        //->order('tua.access_type ASC')
                        ->queryRow();

                /* End for set criteria to get  forgot user details with match email in tbl_user */
                if ($forgot_user_details == null) {
                    //CSystemGenerated::setMessage('FORGOT_PASSWORD_ERROR', 'error', null);
                    Yii::app()->user->setFlash('error', "User with this email not found");
                    $this->redirect(array('//login'));
                } else {
                    $first_name = $forgot_user_details['first_name'];
                    $user_email = $forgot_user_details['email'];
//                    echo '<pre>';
//                    print_r($first_name);
//                    die;
                    $emp_id = $forgot_user_details['emp_id'];
                    $new_password = CSystemGenerated :: password(8);  //Generate new password  
                    $md5_new_password = md5($new_password);

                    $update = Yii::app()->db->createCommand()->update('tbl_employee'
                            , array('password' => $md5_new_password), 'emp_id=:id', array(':id' => $emp_id)
                    );

                    $loginUrl = CHelper :: projectUrl() . "/login/";
                    $loginUrl = "<a href='$loginUrl'>$loginUrl</a>";
                    $message = "Dear $first_name" . ",<br />";
                    $message.="<p>Please login with your new password : " . $new_password . "</p>";
                    $message.="<p>Regards<br/>RJILAuto Team</p>";
                    $message .= "<p>***This is an auto generated email. PLEASE DO NOT REPLY TO THIS EMAIL.***</p>";
                    /*
                      $send_mail = new CSendMail();
                      $send_mail->addPlacehoder('USER_NAME', $first_name);
                      $send_mail->addPlacehoder('EMAIL_ADDRESS', $user_email);
                      $send_mail->addPlacehoder('NEW_PASSWORD', $new_password);
                      $send_mail->addPlacehoder('LOGIN_URL', $loginUrl);
                      $send_mail->addTo($user_email, $first_name);
                      $send_mail->addCC('ojaspashankar@benchmarkitsolutions.com', 'ojas');
                      // Send mail on forgot password
                      $send_mail->mailTo('FORGOT_PASSWORD_REQUEST');
                      $activity_message = "Password reset - Forgot password";
                      $user_access_id = $forgot_user_details['id']; // SESSION VALUE
                      CLog::setActivityLog($user_id, $user_access_id, 0, 0, $activity_message);

                      CSystemGenerated::setMessage('FORGOT_PASSWORD_SUCCESS', 'success', null); */
                    //sendmailWithOutAttachment($user_email, $first_name, "support@rjilauto.com", "Cisco Team", "New Password", $message)
                    Yii::app()->commUtility->sendmailWithOutAttachment($user_email, $first_name, "support@rjilauto.com", "RJILAuto Team", "New Password", $message);
                    Yii::app()->user->setFlash('success', "Password sent successfully");
                    $this->redirect(array('//login'));
                }
            }
        }
    }

    /**
     * This is the action to handle forgot Username .
     */
    public function actionForgotUsername() {
        $model = new LoginForm();
        $this->performAjaxValidation($model);
        $model->scenario = 'forgotusername';
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'forgot-username-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $this->redirect(array('//admin/login'));
            } else {
                // set criteria to get forgot user details with match email with tbl_user
                $email = $_POST['LoginForm']['email'];

                /* $criteria_forgot_password 	 	= new CDbCriteria();
                  $criteria_forgot_password->select 	= 'first_name,email,username,user_id';
                  $criteria_forgot_password->join         = 'INNER JOIN tbl_user_access ON t.user_id = tbl_user_access.user_id  ';
                  $criteria_forgot_password->condition 	= 'email="'.$email.'" and is_active=1 and is_deleted=0 AND tbl_user_access.access_type in ("A","SA","TR") ';
                  $forgot_user_details 			= Admin::model()->find($criteria_forgot_password); */

                //modify on 7 apr 2014 (add condition for access type and law firm id)
                $forgot_user_details = Yii::app()->db->createCommand()
                        ->select('tu.first_name, tu.email, tu.username,tu.user_id')
                        ->from('tbl_user tu')
                        ->join('tbl_user_access tua', 'tu.user_id=tua.user_id AND tua.is_deleted=0')
                        ->where('tu.email=:email AND tu.is_deleted=:is_deleted AND tu.is_active=:is_active AND tua.lawfirm_id=:lawfirm_id AND tua.access_type in ("A","SA","TR")', array(':email' => $email, ':is_deleted' => 0, ':is_active' => 1, ':lawfirm_id' => 0))
                        ->order('tua.access_type ASC')
                        ->queryRow();


                /* End for set criteria to get  forgot user details with match email with tbl_user */
                if ($forgot_user_details == null) {
                    $this->redirect(array('//admin/login'));
                } else {
                    $first_name = $forgot_user_details['first_name'];
                    $user_email = $forgot_user_details['email'];
                    $username = $forgot_user_details['username'];
                    $user_id = $forgot_user_details['user_id'];
                    $new_password = CSystemGenerated :: password();  //Generate new password  
                    $md5_new_password = md5($new_password);
                    //update password in database
                    $criteria = new CDbCriteria();
                    $criteria->condition = 'user_id = ' . $user_id . '';
                    $attributes = array('password' => '' . $md5_new_password . '');
                    Admin::model()->updateAll($attributes, $criteria);
                    $activity_message = "log out super admin ( UserId:" . $user_id . ")";
                    CLog::setActivityLog($user_id, $user_id, 0, 0, $activity_message);

                    //Send mail to user with user name and password details
                    $loginUrl = CHelper :: projectUrl() . "/index.php/login";
                    $send_mail = new CSendMail();
                    $send_mail->addPlacehoder('USER_NAME', $first_name);
                    $send_mail->addPlacehoder('USERNAME', $username);
                    $send_mail->addPlacehoder('NEW_PASSWORD', $new_password);
                    $send_mail->addPlacehoder('LOGIN_URL', $loginUrl);
                    $send_mail->addTo($user_email, $first_name);
                    Yii::app()->session['suc'] = 1;
                    $send_mail->mailTo('FORGOT_PASSWORD_REQUEST');

                    $this->redirect(array('//admin/login'));
                }
            }
        }
    }

    public function actionUrlInactive() {
        $model = new LoginForm;
        $this->render('//admin/url_inactive', array('model' => $model));
    }

    public function actionDashboard() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        //$this->render('lawfirm_dummy');
        $this->render('index');
    }

    /**
     * Auto login
     * 
     * @param type $token
     * @throws UnauthorizedHttpException
     */
    public function actionAuthenticate($token) {

        $decodedStr = base64_decode($token);
        $tokenData = unserialize($decodedStr);
		
        if (!empty($tokenData['username']) and isset($tokenData['lastlogindatetime'])) {
            $username = $tokenData['username'];
            $lastloginat = $tokenData['lastlogindatetime'];
            $model = new LoginForm();
            $model->username = $username;
            $model->lastLoginAt = $lastloginat;
            $model->rememberMe = false;
            $model->email = $username;
            if ($model->autoLogin() ) {   
                $user = Yii::app()->db->createCommand()
                ->select('te.password')
                ->from('tbl_employee te') 
                ->where('te.email=:email AND te.is_deleted=:is_deleted AND te.is_active=:is_active', array(':email' => $model->email, ':is_deleted' => 0, ':is_active' => 1))           
                ->queryRow(); 
                $model->password = $user['password']; 
                $model->login();  
//                $this->render('//admin/url_inactive', array('model' => $model));
                $this->redirect(array('//daycomment/index'));
            } else {
                throw new UnauthorizedHttpException;
}
        } else {
            throw new UnauthorizedHttpException;
        }
    }
	
	public function actionTokenLogin(){
		if(isset($_GET['token'])){
		
		$this->actionAuthenticate($_GET['token']);
		//echo $email = base64_decode($_GET['token']);
			//exit;
		}
	}

}

#-----------------------------------
# Admin Controller End
#-----------------------------------
