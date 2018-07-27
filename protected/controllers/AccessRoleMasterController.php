<?php

class AccessRoleMasterController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','setroles','SetManager','Employee_management','admin','delete','create','update'),
				'users'=>array('*'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array('create','update'),
			// 	'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new AccessRoleMaster;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccessRoleMaster']))
		{
			$model->attributes=$_POST['AccessRoleMaster'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccessRoleMaster']))
		{
			$model->attributes=$_POST['AccessRoleMaster'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('AccessRoleMaster');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AccessRoleMaster('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AccessRoleMaster'])){

			// CHelper::debug($_GET['AccessRoleMaster']);

			if($_GET['AccessRoleMaster']['parent_id']){
			 $fullname['first_name'] = explode(" ", trim($_GET['AccessRoleMaster']['parent_id']))[0];
			 $fullname['last_name'] = explode(" ", trim($_GET['AccessRoleMaster']['parent_id']))[1];

			 // CHelper::debug($_GET['AccessRoleMaster']['parent_id']);
			$emp_id = Employee::model()->returnEmp_id($fullname);
			$_GET['AccessRoleMaster']['parent_id'] = $emp_id;
		}

		if($_GET['AccessRoleMaster']['emp_id']){
			 $fullname['first_name'] = explode(" ", trim($_GET['AccessRoleMaster']['emp_id']))[0];
			 $fullname['last_name'] = explode(" ", trim($_GET['AccessRoleMaster']['emp_id']))[1];

			 // CHelper::debug($_GET['AccessRoleMaster']['parent_id']);
			$emp_id = Employee::model()->returnEmp_id($fullname);
			$_GET['AccessRoleMaster']['emp_id'] = $emp_id;
		}


		$model->attributes=$_GET['AccessRoleMaster'];
		}

		


			

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AccessRoleMaster the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AccessRoleMaster::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AccessRoleMaster $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='access-role-master-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSetRoles(){
        $params = [];
        $model = AccessRoleMaster::model()->get_roles();
        if(isset($_POST['txtarea1'])){
            $params['parent_id'] = $_POST['txtarea1'];
            $params['emp_id'] = $_POST['txtarea3'];

            AccessRoleMaster::model()->set_roles($params);
             Yii::app()->user->setFlash('success', 'Yoou successfully allocate is_resource under mention manager.');
                $this->redirect(array('AccessRoleMaster/admin'));
        }
        
        $this->render('mngr_emp_management', array(
            'model' => $model
        ));
    }

     public function actionSetManager(){
   
        $model = AccessRoleMaster::model()->get_roles();
        // for inser data
        if(isset($_POST['manager'])){
         AccessRoleMaster::model()->set_manager($_POST['manager']);
          Yii::app()->user->setFlash('success', 'You  successfully Set manager in our list.');
                $this->redirect(array('AccessRoleMaster/upload'));
        }
        
        $this->render('save_manager', array(
            'model' => $model
        ));
    }


    public function actionEmployee_management(){
        $model = AccessRoleMaster::model()->get_roles();

        $this->render('employee_management', array(
            'model' => $model,
            'dataProvider' => $model,
        ));
    }
}
