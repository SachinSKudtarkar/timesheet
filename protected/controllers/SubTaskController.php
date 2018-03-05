<?php

class SubTaskController extends Controller
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
				'actions'=>array('index','view',),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
				'users'=>array('@'),
			),
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
		$model=new SubTask;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SubTask']))
		{
			$model->attributes=$_POST['SubTask'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->stask_id));
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

		if(isset($_POST['SubTask']))
		{
			$model->attributes=$_POST['SubTask'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->stask_id));
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
		$dataProvider=new CActiveDataProvider('SubTask');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SubTask('search');
		//$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SubTask'])){
		$condition = $_REQUEST['SubTask'];
            $whrcondition = '';
            if ($condition['project_id'] != '')
                $whrcondition .= " AND em.first_name like '" . $condition['employee'] . "%' or em.last_name like '" . $condition['employee'] . "%'";
            if ($condition['sub_project_name'] != '')
                $whrcondition .= " AND pm.project_name like '" . $condition['project_name'] . "%'";
            if ($condition['project_name'] != '')
                $whrcondition .= " AND sp.sub_project_name like '" . $condition['sub_project_name'] . "%'";
			if(	$condition['Priority'] != '')
				$whrcondition .= " AND t.task_name like '" . $condition['task_name'] . "%'";
        } else{
            $whrcondition = '';
			}
			$sql ="select concat(em.first_name,' ',em.last_name) as employee,pm.project_name,sp.sub_project_name,t.task_name  from tbl_sub_task as st , tbl_employee as em ,tbl_project_management as pm , tbl_sub_project as sp , tbl_task as t
where st.emp_id = em.emp_id and st.project_id = pm.pid and st.sub_project_id = sp.spid and st.task_id = t.task_id $whrcondition ";

$data = YII::app()->db->createCommand($sql)->queryAll();
		//	$model->attributes=$_GET['SubTask'];

		$this->render('admin',array(
			'model'=>$model,
			'data' =>$data,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SubTask the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SubTask::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SubTask $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sub-task-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
