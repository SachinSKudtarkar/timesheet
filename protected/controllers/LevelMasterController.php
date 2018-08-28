<?php

/*  error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","1");  */
class LevelMasterController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),

                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin'),
                'expression' => 'CHelper::isAccess("PROJECTS", "full_access")',
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'expression' => 'CHelper::isAccess("PROJECTS", "full_access")',
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
		$model = new LevelMaster;

        //print_r($modal);exit;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['LevelMaster'])) {
		
            $model->attributes = $_POST['LevelMaster'];
            $model->created_at = date('Y-m-d h:i:s');
            $model->created_by = Yii::app()->session['login']['user_id'];
            $valid =$_POST['LevelMaster'];
            if(empty($valid['level_name'])|| empty($valid['budget_per_hour']))
            {
                Yii::app()->user->setFlash('error','Please fill all required filleds');
            }else{
			
				if($model->save())
				{
					$insert_id = Yii::app()->db->getLastInsertID();
					Yii::app()->user->setFlash('success', "Level {$valid['level_name']} has been created successfully.");
					
				}else{
					Yii::app()->user->setFlash('error', "Sorry! Insert was not succesfull.");
				}
				$this->redirect(array('admin'));
		   }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['LevelMaster'])) {
            $model->attributes = $_POST['LevelMaster'];
            $model->updated_at = date('Y-m-d h:i:s');
            $model->modified_by = Yii::app()->session['login']['user_id'];
            if ($model->save())
                $this->redirect(array('admin'));

        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('SubProject', array(
        'criteria'=>array(
            'order'=>'created_date DESC',
        )));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new LevelMaster('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['LevelMaster']))
            $model->attributes = $_GET['LevelMaster'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SubProject the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = LevelMaster::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SubProject $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sub-project-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
