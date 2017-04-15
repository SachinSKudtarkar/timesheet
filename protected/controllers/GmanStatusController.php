<?php

class GmanStatusController extends Controller {

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
                'actions' => array('create', 'update','dropFunction','killWorkers'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
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
        $model = new GmanStatus;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GmanStatus'])) {
            $tmp = new GmanStatus;
            $model->attributes = $_POST['GmanStatus'];

            if ($_POST['GmanStatus']['gman_jobs_name'] == 'addWorker') {
                $functionName = trim($_POST['GmanStatus']['gman_workers_description']);
                $specificName = explode("::",$functionName);
                if(count($specificName)> 1)
                {
                    $functionName = $specificName[2];
                }           
                $functionName = strtolower($functionName);
                $result = $tmp->getWorkerQueueStatus(" php -f /var/www/html/protected/yiic.php '".$functionName."' > /dev/null &");
                $_POST['GmanStatus']['gman_jobs_name'] = 'status';
                $result = $tmp->getWorkerQueueStatus("gearadmin --status");
            }
            else if ($_POST['GmanStatus']['gman_jobs_name'] == 'functionname') {
                $functionName = trim($_POST['GmanStatus']['gman_workers_description']);
                $result = $tmp->getWorkerQueueStatus("gearadmin --status | grep '" . $functionName . "'");
            } else if ($_POST['GmanStatus']['gman_jobs_name'] == 'status') {
                $result = $tmp->getWorkerQueueStatus("gearadmin --status");
            } else if ($_POST['GmanStatus']['gman_jobs_name'] == 'workers') {
                $result = $tmp->getWorkerQueueStatus("gearadmin --workers");
                //$result = $tmp->getWorkerQueueStatus("gearadmin --workers | grep 'RjilAuto::Gearman::AutomationIntegration::run'"); 
            }
            $this->render('create', array('model' => $model, 'data' => $result['gmanstatusstring'], 'postval' => $_POST['GmanStatus']['gman_jobs_name']));
        } else
            $this->render('create', array(
                'model' => $model, 'data' => array()
            ));
    }
    
    public function actionDropFunction()
    {
            $tmp = new GmanStatus;
        $functionName = isset($_REQUEST['functionName'])?$_REQUEST['functionName']:'';     
        $result = $tmp->getWorkerQueueStatus("gearadmin --drop-function ".$functionName);
    }
    
    public function actionKillWorkers()
    {
            $tmp = new GmanStatus;
        $functionName = isset($_REQUEST['functionName'])?$_REQUEST['functionName']:'';  
        $specificName = explode("::",$functionName);
                if(count($specificName)> 1)
                {
                    $functionName = $specificName[2];
                }
                
        $functionName = strtolower($functionName);           
        $result = $tmp->getWorkerQueueStatus("ps -aux | grep ".$functionName);
        $output = $result['gmanstatusstring'];
        $processids = array();
        foreach($output as $indString)
        {             
            $tmparray = mb_split("\s",$indString); 
            $tmparray = array_filter($tmparray);
            $tmparray = array_slice($tmparray,0);  
            $processids[] = $tmparray[1];
        }
        $processidstring = implode(" ",$processids); 
        
         $result = $tmp->getWorkerQueueStatus("kill -9  ".$processidstring);
       /* foreach($processids as $indid)
        {
            $result = $tmp->getWorkerQueueStatus("kill -9  ".$indid);
        }*/
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

        if (isset($_POST['GmanStatus'])) {
            $model->attributes = $_POST['GmanStatus'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $dataProvider = new CActiveDataProvider('GmanStatus');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GmanStatus('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GmanStatus']))
            $model->attributes = $_GET['GmanStatus'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GmanStatus the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GmanStatus::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GmanStatus $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gman-status-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
