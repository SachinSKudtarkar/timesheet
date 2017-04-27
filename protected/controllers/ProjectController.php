<?php

class ProjectController extends Controller {

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
                'actions' => array('index', 'view', 'admin', 'AllProject'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
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
        $model = new Project;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Project'])) {
            $model->attributes = $_POST['Project'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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

        if (isset($_POST['Project'])) {
            $model->attributes = $_POST['Project'];
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
        $dataProvider = new CActiveDataProvider('Project');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Project('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Project']))
            $model->attributes = $_GET['Project'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Project the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Project::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Project $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'project-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
     public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'application.components.ExportableGridBehavior',
                'filename' => 'dailyStatus.csv',
                'csvDelimiter' => ',', //i.e. Excel friendly csv delimiter
        ));
    }

    public function actionAllProject() {
        $model = new SubTask();

        if (isset($_REQUEST['SubTask'])) {
            $condition = $_REQUEST['SubTask'];

            $whrcondition = '';
            if ($condition['Name'] != '')
                $whrcondition .= " where em.first_name like '" . $condition['Name'] . "%' or em.last_name like '" . $condition['Name'] . "%'";
            if ($condition['Project'] != '')
                $whrcondition .= " where sp.sub_project_name like '" . $condition['Project'] . "%'";
            if ($condition['Program'] != '')
                $whrcondition .= " where pm.project_name like '" . $condition['Program'] . "'";
            if ($condition['Task'] != '')
                $whrcondition .= " where st.sub_task_name like  '" . $condition['Task'] . "%'";
            if ($condition['Type'] != '')
                $whrcondition .= " where tt.task_name like '" . $condition['Type'] . "%'";
            } else 
                $whrcondition = '';
           


        $query = "select concat(em.first_name,' ',em.last_name) as Name,pm.project_name as Program ,sp.sub_project_name as Project ,st.sub_task_name as Task ,tt.task_name as Type ,
                    st.stask_id,st.est_hrs as Estimated_Hours from tbl_sub_task as st 
                    inner join tbl_project_management as pm on(st.project_id = pm.pid)
                    inner join tbl_sub_project as sp on (st.sub_project_id = sp.spid)
                    inner join tbl_task as tt on(st.task_id = tt.task_id)
                    inner join tbl_employee as em on(st.emp_id = em.emp_id) {$whrcondition} order by em.first_name;";
        $rawData = Yii::app()->db->createCommand($query)->queryAll();

        // echo "<pre>";
        //print_r($rawData);
        foreach ($rawData as $key => $value) {

            $query1 = "select concat(em.first_name,' ',em.last_name) as name,
                    sum(dc.hours) as Consumed_Hours
                    from  tbl_day_comment as dc
                    inner join tbl_employee as em on(dc.emp_id = em.emp_id)
                    where dc.stask_id = {$value['stask_id']} group by dc.emp_id ";
            $rawData_daycomment = Yii::app()->db->createCommand($query1)->queryRow();

            if (!empty($rawData_daycomment)) {
                $newarray[$key]['Consumed_Hours'] = $rawData_daycomment['Consumed_Hours'];
            }


            $newarray[] = $value;
        }
        
        
        
        if ($this->isExportRequest()) {
           
          $export_column_name = array('Name',
            'Program',
            'Project',
            'Task',
            'Type',
             'Estimated_Hours',
              'Consumed_Hours');
            $filename = "All_Project_Details " . date('d_m_Y') . "_" . date('H') . "_hr";
            CommonUtility::downloadDataInCSV($export_column_name, $newarray, $filename);
        }
        
        
        
        //   CHelper::debug($newarray);
        $this->render('allProject', array(
            'data' => $newarray, 'model' => $model,
        ));
    }

}
