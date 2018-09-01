<?php
error_reporting(E_ALL);
ini_set('display_errors',0);
class SubProjectController extends Controller {

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
                'actions' => array('create', 'update', 'admin','fetchProjectId'),
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
        $model = new SubProject;
        //print_r($model);exit;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {

            $model->attributes = $_POST['SubProject'];
            $model->created_date = date('Y-m-d h:i:s');
			$model->project_id = $_POST['sub_project_id'];
            $model->created_by = Yii::app()->session['login']['user_id'];
            $valid =$_POST['SubProject'];

            if(empty($valid['pid']) || empty($valid['sub_project_name'])|| empty($valid['sub_project_description'])|| empty($valid['requester'])|| empty($valid['status']) ||empty($valid['priority']))
            {
                Yii::app()->user->setFlash('error','Please fill all required filleds');
            }else{

               if($model->save())
				$insert_id = Yii::app()->db->getLastInsertID();
				Yii::app()->user->setFlash('success', "ProjectId is {$insert_id}");

				foreach ($_POST['group-a'] as $key => $val) {
					if(!empty($val['level_hours'])) {
						$modelPLA = new ProjectLevelAllocation;
						$modelPLA->project_id = $insert_id;
						$modelPLA->level_id = $val['level_id'];
						$modelPLA->level_hours = $val['level_hours'];
						$modelPLA->created_by = Yii::app()->session["login"]["user_id"];
						$modelPLA->created_at = date("Y-m-d h:i:s");
						$modelPLA->save(false);
					}
					//$importData[] = $modelST->getAttributes();
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
		$levels = ProjectLevelAllocation::model()->findAll("project_id=$id");
		$hours_label['allocated'] = Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs from tbl_sub_project sp left join tbl_sub_task st on st.sub_project_id  = sp.spid where spid = {$id}")->queryRow();
		$hours_label['estimated'] = Yii::app()->db->createCommand("select sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid where spid = {$id}")->queryRow();
		$hours_label['utilized'] = Yii::app()->db->createCommand("SELECT  SEC_TO_TIME( SUM( TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  FROM tbl_day_comment where spid={$id}")->queryRow();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {

            $model->attributes = $_POST['SubProject'];
			$model->project_id = $_POST['sub_project_id'];
            $model->updated_date = date('Y-m-d h:i:s');
            $model->updated_by = Yii::app()->session['login']['user_id'];



            if ($model->save())
				Yii::app()->db->createCommand("DELETE FROM tbl_project_level_allocation where project_id={$id}")->execute();
				foreach ($_POST['group-a'] as $key => $val) {

					if(!empty($val['level_hours'])) {

						$modelPLA = new ProjectLevelAllocation;


						/* $checkCount = Yii::app()->db->createCommand('Select count(*) as count from tbl_project_level_allocation where project_id='.$id.' and level_id='.$val['level_id'])->queryRow();  */

						$modelPLA->project_id = $id;
						$modelPLA->level_id = $val['level_id'];
						$modelPLA->level_hours = $val['level_hours'];
						$modelPLA->created_by = Yii::app()->session["login"]["user_id"];
						$modelPLA->updated_at = date("Y-m-d h:i:s");
						$modelPLA->save(false);
/* 						if($checkCount['count'] > 0){
							$modelPLA->modified_by = Yii::app()->session["login"]["user_id"];
							$modelPLA->updated_at = date("Y-m-d h:i:s");
							Yii::app()->db->createCommand("UPDATE tbl_project_level_allocation SET level_hours={$val['level_hours']}, modified_by={$modelPLA->modified_by} WHERE project_id={$id} and level_id={$val['level_id']}")->execute();

						}else{
							$modelPLA->created_by = Yii::app()->session["login"]["user_id"];
							$modelPLA->updated_at = date("Y-m-d h:i:s");
							$modelPLA->save(false);
						}
 */

					}
					//$importData[] = $modelST->getAttributes();
				}
                $this->redirect(array('admin'));

        }

        $this->render('update', array(
            'model' => $model,
            'levels' => $levels,
			'hours_label' => $hours_label
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
        $model = new SubProject('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SubProject']))
            $model->attributes = $_GET['SubProject'];

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
        $model = SubProject::model()->findByPk($id);
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

    /**
     * Fetches the program id to generate the project id.
     * @param SubProject $projectid
     */
    public function actionfetchProjectId(){

        $name = ProjectManagement::model()->findByPk($_POST['project_id']);

        if(!empty($_POST['update_id']))
        {
                $TaskId = $_POST['update_id'];
        }else{
                $TaskId = Yii::app()->db->createCommand('Select max(spid) as maxId from tbl_sub_project ')->queryRow();
                $TaskId = $TaskId['maxId'] + 1;
        }

        $projectformat = $name['project_id'].sprintf("%03d", $TaskId);
        echo $projectformat;
    }
}
