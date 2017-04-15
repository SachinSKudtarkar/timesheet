<?php
class DashboardController extends BaseController
{
	/**
	 * Declares class-based actions.
	 */
        public $layout	=   '//layouts/column1';
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
       
        /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
        /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index'),
                'expression' => 'CHelper::isAccess("DASHBOARD")',
                'users' => array('@'),
            ),
             array('allow',
                'actions' => array('welcome'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            //echo '<pre>';print_r(Yii::app()->session['login']);exit;
            // renders the view file 'protected/views/site/index.php'
            // using the default layout 'protected/views/layouts/main.php'
            $this->render('index');
	}
        
        /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionWelcome()
	{
            $this->redirect(array('/issuesList/admin'));
            //$this->render('welcome');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{            
		if($error=Yii::app()->errorHandler->error)
		{ 
                    //echo "<pre>";
                    //print_r($error); die;
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
                           $this->render('error', $error);
		}
	}

	
	public function actionRemoveImage()
	{
		if( isset($_POST['ajax']) && $_POST['ajax'] )
                {
                        $data_image = explode(':', $_POST['data_image']);
                        $model  = $data_image[0];
                        $field  = $data_image[1]; 
                        $id     = $data_image[2];
                        $image_path =   CHelper::basePath().'/../'.$model::model()->findByPk( $id )->$field;
                        if( $model::model()->updateByPk( $id, array( $field  => '' ) ) ){
                            unlink( $image_path );
                            echo true;
                        }else{
                            echo false;
                        }
                }
	}   
       
       ### Function Name    :   actionTimeLog()//
       //  Description      :   This function used to make entry in timelog table after law firm switching law frims from law firm drop down
     
        public function actionTimeLog()
        {
            $law_firm_id    = $_GET['law_firm_id'];
            $login_user_id  = Yii::app()->session['login']['user_id'];
            $login_user_access_type = Yii::app()->session['login']['access_type'];
            
            
//            if(isset(Yii::app()->session['login']['timelogid']))
//            { 
//                //
//                $timelogid = Yii::app()->session['login']['timelogid'];
//                $criteria   = new CDbCriteria(); 
//                $criteria->condition = 'law_firm_id="'.$law_firm_id.'" and log_id='.$timelogid ;						
//                $count = TimeLog::model()->count($criteria);
//                if( $count > 0 )
//                {
//                    $model = TimeLog::model()->findByPk(array('log_id' => $timelogid));
//                    $model->end_date_time   = new CDbExpression('NOW()');
//                    $model->save();
//                }
//                else
//                {
////                    $start_time = new CDbExpression('NOW()');
////                    //Create the entry in the time log table
////                    CLog::setTimeLog($login_user_id, $login_user_access_id, $law_firm_id, $start_time, $start_time);
////                    $time_log_id    =   Yii::app()->db->getLastInsertID();
////                    $session_array  = Yii::app()->session['login'];
////                    $session_array['timelogid']    = $time_log_id;
////                    Yii::app()->session['login'] = $session_array;
//                }
//                
//            }
//            //For the first time: user not selected any law firm
//            //Then make a new entry in time log table and also set the time log id and lawfirm id in the session
//            else
//            {
//                $start_time = new CDbExpression('NOW()');
//            //Create the entry in the time log table
//            CLog::setTimeLog($login_user_id, $login_user_access_id, $law_firm_id, $start_time, $start_time);
//            $time_log_id    =   Yii::app()->db->getLastInsertID();
//            $session_array  = Yii::app()->session['login'];
//            $session_array['timelogid']    = $time_log_id;
//            //Change the law firm id in the session
//            $session_array['law_firm_id']    = $law_firm_id;
//            Yii::app()->session['login'] = $session_array;
//            }
             //$this->redirect(array('/admin')); 
             //CHelper::debug($login_user_access_type);
             
             // Check current access type of the user and case will execute accordingly.
            switch ($login_user_access_type)
            {
                case    'SA':
                            // Check that is the selected law firm is active and not deleted
                            $count = CSystemGenerated::isLawfirmActive( $law_firm_id );
                            //if active then it goes in if condition
                            if( $count )
                            {
                                // Check that is the SA/TR having the access of the selected law firm
                                $response   = CSystemGenerated::setAccessRightsOfActiveUser( $login_user_id, $law_firm_id, 'TR');
                                // Redirect to the selected law firm page
                                if($response)
                                {
                                    CSystemGenerated::setMessage("SWITCH_LAWFIRM_SUCCESS");
                                    // Make Entry in The timr log table
                                    $login_user_access_id   = Yii::app()->session['login']['access_id'];
                                     $start_time = new CDbExpression('NOW()');
                                    //Create the entry in the time log table
                                    CLog::setTimeLog($login_user_id, $login_user_access_id, $law_firm_id, $start_time, $start_time);
                                    $time_log_id    =   Yii::app()->db->getLastInsertID();
                                    $session_array  = Yii::app()->session['login'];
                                    $session_array['timelogid']    = $time_log_id;
                                    Yii::app()->session['login'] = $session_array;
                                    $this->redirect(array('/estate')); 
                                }
                                // IF user is deleted or inactivated meanwhile then set the notice flash message to the current page
                                else
                                {
                                    CSystemGenerated::setMessage("USER_INACTIVE_OR_DELETED_FOR_LAWFIRM");
                                    $this->redirect(array('/admin'));
                                }
                                
                            }
                            //
                            else
                            {
                                // Set the error flash message that this law firm has been deleted or innactivated by admin and redirect to the current view
                                CSystemGenerated::setMessage("LAWFIRM_NOT_AVAILABLE");
                                $this->redirect(array('/admin'));
                            }

                    break;
                case    'TR':
                            # If the trainer switch the role to sub-admin means its law-firm id is zero.
                            //So we have to change its access rights and access type by using function written in csystemgenerated class  
                            //i.e CSystemGenerated::setAccessRightsOfActiveUser( $login_user_id, $law_firm_id, 'SA');
                            #
                            if ($law_firm_id == 0 )
                            {//CHelper::dump(Yii::app()->session['login']);
                                $response   = CSystemGenerated::setAccessRightsOfActiveUser( $login_user_id, $law_firm_id, 'SA');
                                if( $response )
                                {
                                    //CHelper::debug(Yii::app()->session['login']);
                                    //unset( Yii::app()->session['login']['timelogid'] );
                                    $session_array  = Yii::app()->session['login'];
                                    $session_array['timelogid']    = '';
                                    Yii::app()->session['login'] = $session_array;
                                    CSystemGenerated::setMessage("TR_TO_SA_ROLE_CHANGED");
                                    $this->redirect(array('/admin'));
                                }
                                else
                                {
                                    //CHelper::debug(Yii::app()->session['login']);
                                    CSystemGenerated::setMessage("SA_ACCESS_REMOVED");
                                    $this->redirect(array('site/index'));
                                }
                               
                            }
                            # If the trainer selects any other law firm from dropdown list of law-firms then 
                            // first check the selected law-firm is active/deleted or not then
                            // we have to change its access right by using function written in csystemgenerated class                          
                            #
                            else
                            {
                                // Check that is the selected law firm is active and not deleted
                                $count = CSystemGenerated::isLawfirmActive( $law_firm_id );
                                // Check that is the TR having the access of the selected law firm
                                if( $count )
                                {
                                    $response   = CSystemGenerated::setAccessRightsOfActiveUser( $login_user_id, $law_firm_id, 'TR');
                                    if( $response )
                                    {
                                        CSystemGenerated::setMessage("SWITCH_LAWFIRM_SUCCESS");
                                            // Make Entry in The timr log table
                                         $start_time = new CDbExpression('NOW()');
                                         $login_user_access_id   = Yii::app()->session['login']['access_id'];
                                        //Create the entry in the time log table
                                        CLog::setTimeLog($login_user_id, $login_user_access_id, $law_firm_id, $start_time, $start_time);
                                        $time_log_id    =   Yii::app()->db->getLastInsertID();
                                        $session_array  = Yii::app()->session['login'];
                                        $session_array['timelogid']    = $time_log_id;
                                        Yii::app()->session['login'] = $session_array;
                                        // Redirect to the selected law firm page
                                        //  $this->redirect(array('/admin')); 
                                        $this->redirect(array('/estate'));
                                    }
                                    else
                                    {
                                         CSystemGenerated::setMessage("USER_INACTIVE_OR_DELETED_FOR_LAWFIRM");
                                        // Redirect to the selected law firm page
                                        //  $this->redirect(array('/admin')); 
                                        $this->redirect(array('site/index'));
                                    }
                                    
                                }
                                else
                                {
                                    // Set the error flash message that this law firm has been deleted or innactivated by admin and redirect to the current view
                                    CSystemGenerated::setMessage("LAWFIRM_NOT_AVAILABLE");
                                    $this->redirect(array('site/index')); 
                                }
                            }
                    
                    break;
            }
        }
        
        public function actionKeepAlive()
        {
            echo 'OK';
            Yii::app()->end();
           
        }
}