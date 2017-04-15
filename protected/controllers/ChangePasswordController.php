<?php
class ChangePasswordController extends BaseController
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
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	        
        public function actionIndex()
	{
                 
                //Fetch the session user_id
                $id = Yii::app()->session['login']['user_id'];
                
                $model = Employee::model()->findByAttributes(array('emp_id'=>$id));
                $model->setScenario('changePwd');
                $this->performAjaxValidation($model);
                //die('wadw');
                if(Yii::app()->session['login']['user_id'] == 2065){
                   // echo $_POST['Employee']['password'] ;
                    //exit;
                }
                if(isset($_POST['Employee'])){
                   $old_password = $model->password; 
                   $model->attributes = $_POST['Employee'];
                   $valid = $model->validate();
                   if($valid){
                       if( $old_password ==  md5($model->password))
                       {
                           $model->password = md5($_POST['Employee']['new_password']);
                           $model->change_password = 1;
                           $model->save();
                           Yii::app()->user->setFlash('success', "Your password has been updated successfully.");
                                                      
                       }else
                       {
                           Yii::app()->user->setFlash('error', "The password you entered is incorrect, please retype your current password");
                       }
                       
                       
                       }
                       else
                       {   
                           Yii::app()->user->setFlash('notice', "Please fill data correctly.");
                       }
                       //$this->redirect(array('index','model'=>$model));
                   }
 
                     $this->render('index',array('model'=>$model)); 
              
            
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

       
       
        public function actionKeepAlive()
        {
            echo 'OK';
            Yii::app()->end();
           
        }
        
      /*
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Employee the loaded model
	 * @throws CHttpException
	 */
        public function loadModel($id)
	{
		$model=Employee::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='chnage-password-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}