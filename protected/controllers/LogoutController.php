<?php

class LogoutController extends BaseController
{
	public $layout  =   '//layouts/column1';
        
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		/*return array(
			'accessControl', // perform access control for CRUD operations
		);*/
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		/*return array(
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);*/
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'	=>$this->loadModel($id),
		));
	}
		
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model	=   User::model()->findByPk($id);
		if( $model === null )
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'   =>  array(
                                            'class'	=> 'CCaptchaAction',
                                            'backColor' => 0xFFFFFF,
                                        ),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'  =>  array(
					'class' =>  'CViewAction',
                                    ),
		);
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if( $error=Yii::app()->errorHandler->error )
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionindex()
	{   
                session_start();
                session_destroy();  
		$homeurl=CHelper :: homeUrl();
              //  $this->redirect("http://portal.infinitylabs.in/app/index.php/zurmo/default/login");
		$this->redirect('login');
	}
	
}
#-----------------------------------
# Admin Controller End
#-----------------------------------
