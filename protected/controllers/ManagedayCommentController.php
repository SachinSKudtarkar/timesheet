<?php

class ManagedayCommentController extends Controller
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
				'actions'=>array('index','view','admin','update','FetchDaycomments','updateStatus','UpdateTick'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
		$model=new DayComment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DayComment']))
		{
			$model->attributes=$_POST['DayComment'];
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

		if(isset($_POST['DayComment']))
		{
			$model->attributes=$_POST['DayComment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

        
        
        public function actionUpdateStatus()
	{
           
           $day= explode(" ", $_POST['day']);
           $empid = $_POST['empid'];
           $from = date('Y-m-d', strtotime($day[0]));
           $to = date('Y-m-d', strtotime($day[2]));

		 if(!empty($day))
            {
                $command= Yii::app()->db->createCommand("UPDATE tbl_day_comment SET is_submitted = '0' WHERE emp_id= '{$empid}' and day between '{$from}'and '{$to}'");
		$sql_result = $command->execute();  
            }
  
            
            }
	
         public function actionUpdateTick()
	{
           //update status backup
           $day= explode(" ", $_POST['day']);
           $empid = $_POST['empid'];
           $from = date('Y-m-d', strtotime($day[0]));
           $to = date('Y-m-d', strtotime($day[2]));

		 if(!empty($day))
            {
                 $command=Yii::app()->db->createCommand("UPDATE tbl_day_comment SET is_submitted = '1' WHERE id=:id");
                $command->bindValue(':id', $_POST['id']);
		$sql_result = $command->execute();
            }
            else
            {
                $command=Yii::app()->db->createCommand("UPDATE tbl_day_comment SET is_submitted = '0' WHERE id=:id");
                $command->bindValue(':id', $_POST['id']);
		$sql_result = $command->execute();
  
            
            }
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
		$dataProvider=new CActiveDataProvider('DayComment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DayComment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DayComment']))
			$model->attributes=$_GET['DayComment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DayComment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DayComment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DayComment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='day-comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
          public function actionFetchDaycomments() {
          $empid = $_POST['empid'];
          $day= explode(" ", $_POST['day']);
          //print_r($day)
          $from = $day[0];
          $to = $day[2];
          
          $model = new DayComment();
          $model->emp_id = $empid;
          $model->from = $from;
          $model->to = $to;
          $model->is_submitted = 1;
       
         echo $this->renderPartial('fetchcomments',
                 array('model'=>$model,) 
                 );
         exit;
       
    }
}
