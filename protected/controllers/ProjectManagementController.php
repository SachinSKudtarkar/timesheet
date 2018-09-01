<?php

class ProjectManagementController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $sub_project_name;

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
                'actions' => array('index', 'view','getPID','genrate'),
             //   'expression' => 'CHelper::isAccess("PROJECTS", "full_access")',
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
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
        $model = new ProjectManagement('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProjectManagement']))
            $model->attributes = $_GET['ProjectManagement'];

        $model->pid = $id;
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ProjectManagement;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ProjectManagement'])) {
            // CHelper::debug($_POST['ProjectManagement']);
            $model->attributes = $_POST['ProjectManagement'];
			$model->project_id = $_POST['project_id'];
            $model->created_date = date('Y-m-d h:i:s');
            $model->updated_date = date('Y-m-d h:i:s');
            $model->updated_by = Yii::app()->session['login']['user_id'];
            $model->created_by = Yii::app()->session['login']['user_id'];
            if ($model->save()) {
                $creatorName = $this->getUserName(Yii::app()->session['login']['user_id']);
                $message = "<b>Dear Manager,</b> <br><br>";
                $message .= "<b>New Project Created</b> <br><br>";
                $message .= "<b>Project Name</b>: " . $model->project_name;
                $message .= "<br><br>";
                $message .= "<b>Project Description</b>: " . $model->project_description;
                $message .= "<br><br>";
                $message .= "<b>Requester</b>: " . $model->requester;
                $message .= "<br><br>";
               // $message .= "<b>Estimated End Date</b>: " . $model->estimated_end_date;
               // $message .= "<br><br>";
               // $message .= "<b>Total HR Estimation Hours</b>: " . $model->total_hr_estimation_hour;
                $message .= "<br><br>";
                $message .= "<b>Project Created By</b>: " . $creatorName;
                $message .= "<br><br>";
                $message .= "<br><br>";
                $message .= "Regards,";
                $message .= "<br>RJIL Auto Team";
                $mail_sent = $this->sendEmail($message, $model->project_name);
                Yii::app()->user->setFlash('success', "Project " . $model->project_name . " Created Successfully. Please allocate resource.");
                $this->redirect(array('//resourceallocationprojectwork/create'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

     public function actionGenrate() {
        $model = new Project;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

       //
        if (isset($_POST['Project'])) {
            $model->attributes = $_POST['Project'];
            $model->created_date = date('Y-m-d h:i:s');
            $model->updated_date = date('Y-m-d h:i:s');
            $model->updated_by = Yii::app()->session['login']['user_id'];
            $model->created_by = Yii::app()->session['login']['user_id'];

            if(!empty($model)){
                    $sql = "insert into tbl_project (project_name, created_date, updated_date, updated_by, created_by) values (:project_name,:created_date,:updated_date, :updated_by,:created_by)";
                    $parameters = array(":project_name" =>$model->project_name, ":created_date" => $model->created_date, ":updated_date" => $model->updated_date , ":updated_by" =>$model->updated_by, ':created_by' =>$model->created_by);
                    Yii::app()->db->createCommand($sql)->execute($parameters);

            }
            if (!empty($parameters)) {

                $creatorName = $this->getUserName(Yii::app()->session['login']['user_id']);
                $message = "<b>Dear Manager,</b> <br><br>";
                $message .= "<b>New Project Created</b> <br><br>";
                $message .= "<b>Project Name</b>: " . $model->project_name;
                $message .= "<br><br>";
                $message .= "<b>Project Created By</b>: " . $creatorName;
                $message .= "<br><br>";
                $message .= "<br><br>";
                $message .= "Regards,";
                $message .= "<br>RJIL Auto Team";
                $mail_sent = $this->sendEmail($message, $model->project_name);
                Yii::app()->user->setFlash('success', "Project " . $model->project_name . " Created Successfully.");
                $this->redirect(array('//ProjectManagement/genrate'));
            }
        }

        $this->render('genrate', array(
            'model' => $model,
        ));
    }
    public function sendEmail($message, $projectname) {
        $from = "support@infinitylabs.in";
        $from_name = "Infinitylabs Team";
        $to = array();
        $cc = array();
      //$to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");

        $subject = "Project Created: " . $projectname;
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null);
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

        if (isset($_POST['ProjectManagement'])) {
            $model->attributes = $_POST['ProjectManagement'];
            if ($model->save())
                //$this->redirect(array('view', 'id' => $model->pid));

                /**
                 * redirecting to the Program management page instead of program view(project management) page
                 * Tirthesh::08042018
                 */
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
        $model = $this->loadModel($id);

        $subProject = SubProject::model()->findByAttributes(array('pid'=>$id));

        if(!$subProject){
            $model->delete();
            //$model->is_deleted  = 1;
            //$model->save();
        }else{
            //Yii::app()->user->setFlash('failed', "Program can not be deleted.");
            echo 'could not be deleted';
            // could not be deleted;
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
//		$dataProvider=new CActiveDataProvider('ProjectManagement');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
        $model = new ProjectManagement('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProjectManagement']))
            $model->attributes = $_GET['ProjectManagement'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ProjectManagement('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProjectManagement']))
            $model->attributes = $_GET['ProjectManagement'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ProjectManagement the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ProjectManagement::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ProjectManagement $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'project-management-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function getUserName($id) {
        $name = Yii::app()->db->createCommand()
                ->select('CONCAT(first_name," ",last_name) as full_name')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $id))
                ->queryRow();

        return $name['full_name'];
    }
}
