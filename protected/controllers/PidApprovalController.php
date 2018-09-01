<?php
error_reporting(E_ALL);
ini_set('display_errors',0);
class PidApprovalController extends Controller {

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
                'actions' => array('index', 'view', 'Approvalstatus', 'Allprojects','fetchSubProjectIdAndHours','checkHoursAndBudget'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update','fetchSubProjectIdAndHours','checkHoursAndBudget'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
//				'users'=>array('admin'),
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


        $model = new PidApproval('search');

//		$model=new PidApproval;
        //$subtask=new SubTask;
        $FINAL_ARRAY = array();
        // Uncomment the following line if AJAX validation is needed

        //print_R($_POST);
        //print_R($model);exit;

        $this->performAjaxValidation($model);

        if (isset($_POST['PidApproval'])) {
//                CHelper::prd($_POST);
            $valid = $_POST;

            if (empty($valid['PidApproval']['project_id']) || empty($valid['PidApproval']['sub_project_id']) || empty($valid['PidApproval']['inception_date']) || empty($valid['PidApproval']['jira_id']) ||
                    empty($valid['PidApproval']['total_est_hrs']) || ($valid['PidApproval']['total_est_hrs'] == 0) ) { //|| isset($valid['task_id']) || isset($valid['sub_task_name']) || isset($valid['est_hrs'])

                Yii::app()->user->setFlash('error', 'Please fill all filleds, All Filleds are Required');
                $this->render('create', array(
                    'model' => $model
                ));
            }

                $model->project_id = $_POST['project_id'];
                $model->sub_project_id = $_POST['sub_project_id'];
                $model->approved = 2;
                $model->created_by = Yii::app()->session["login"]["user_id"];
                $model->created_at = date("Y-m-d h:i:s");
                $model->project_task_id = $_POST['project_task_id'];
                $model->attributes = $_POST['PidApproval'];
//                $this->performAjaxValidation($model);
                foreach ($_POST['sub_task_name'] as $key => $val) {
                    $FINAL_ARRAY[$key]['task_id'] = $_POST['task_id'][$key];
					/* $FINAL_ARRAY[$key]['task_id'] = 1; */
                    $FINAL_ARRAY[$key]['emp_id'] = $_POST['emp_id'][$key];
                    $FINAL_ARRAY[$key]['sub_task_name'] = $_POST['sub_task_name'][$key];
                    $FINAL_ARRAY[$key]['est_hrs'] = $_POST['est_hrs'][$key];
					$FINAL_ARRAY[$key]['st_jira_id'] = $_POST['st_jira_id'][$key];
					$FINAL_ARRAY[$key]['st_inception_date'] = $_POST['st_inception_date'][$key];
                }
                if (isset($_POST['l2_ring'])) {
                    foreach ($_POST['l2_ring'] as $key => $val) {
                        $FINAL_ARRAY[] = $_POST['l2_ring'][$key];
                    }
                }

            if($model->validate()){
                //rint_r($FINAL_ARRAY);

                if ($model->save(false)){
                    foreach ($FINAL_ARRAY as $key => $val) {
                        $modelST = new SubTask;
                        $modelST->s_task_id = $key + 1;
                        $modelST->pid_approval_id = $model->pid_id;
                        $modelST->project_id = $model->project_id;
                        $modelST->sub_project_id = $model->sub_project_id;
                        $modelST->task_id = $val['task_id'];
                        $modelST->emp_id = $val['emp_id'];
                        $modelST->sub_task_name = $val['sub_task_name'];
                        $modelST->est_hrs = $val['est_hrs'];
                        $modelST->created_by = Yii::app()->session["login"]["user_id"];
                        $modelST->created_at = date("Y-m-d h:i:s");
						$subTaskId = Yii::app()->db->createCommand('Select max(stask_id) as maxId from tbl_sub_task ')->queryRow();
						$modelST->sub_task_id = $_POST['project_task_id'].sprintf("%02d", $val['task_id']).sprintf("%03d", $subTaskId['maxId'] + 1);
						$modelST->st_jira_id = $val['st_jira_id'];
						$modelST->st_inception_date = $val['st_inception_date'];
                        $modelST->save(false);
                        //$importData[] = $modelST->getAttributes();
                    }
                    //$this->redirect(Yii::app()->urlManager->createUrl("Project/allProject"));
                    $this->redirect('admin', array('model' => $model));
                }else{
                }
            }else{
                print_R($model->getErrors());
                exit;
            }
        }

        $this->render('create', array(
            'model' => $model
            // 'subtask'=>$subtask,
        ));
    }

    public function validated($post){

        Yii::app()->user->setFlash('error', 'Please fill All Filleds are Required');
                $this->render('create', array(
                    'model' => $model
                ));

    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
		$query1 = "select st.*,lm.budget_per_hour from tbl_sub_task st inner join tbl_assign_resource_level lr on lr.emp_id = st.emp_id inner join tbl_level_master lm on lm.level_id = lr.level_id where pid_approval_id=$id";
        $subtask = Yii::app()->db->createCommand($query1)->queryAll();


        if (isset($_POST['PidApproval'])) {

            $model->project_id = $_POST['project_id'];
                $model->sub_project_id = $_POST['sub_project_id'];
                $model->approved = 2;
				$model->project_task_id = $_POST['project_task_id'];

			$model->attributes = $_POST['PidApproval'];
            //SubTask::model()->deleteAll("pid_approval_id=$id");

			//delete existing tasks if there is no comment added by any user in the day comment;
			/* if(!empty($_POST['deleted_stask_id']))
			{
				$this->deleteTask($_POST['deleted_stask_id'],$id);
			} */
            foreach ($_POST['task_id'] as $key => $val) {
				$FINAL_ARRAY[$key]['stask_id'] = $_POST['stask_id'][$key];
                $FINAL_ARRAY[$key]['task_id'] = $_POST['task_id'][$key];
				/* $FINAL_ARRAY[$key]['task_id'] = 1; */
				$FINAL_ARRAY[$key]['emp_id'] = $_POST['emp_id'][$key];
				$FINAL_ARRAY[$key]['sub_task_name'] = $_POST['sub_task_name'][$key];
				$FINAL_ARRAY[$key]['est_hrs'] = $_POST['est_hrs'][$key];
				$FINAL_ARRAY[$key]['st_jira_id'] = $_POST['st_jira_id'][$key];
				$FINAL_ARRAY[$key]['st_inception_date'] = $_POST['st_inception_date'][$key];
            }
            if (isset($_POST['l2_ring'])) {
                foreach ($_POST['l2_ring'] as $key => $val) {
                    $FINAL_ARRAY[] = $_POST['l2_ring'][$key];
                }
            }


            if ($model->save())
                foreach ($FINAL_ARRAY as $val) {
					if(!empty($val['stask_id']) && $val['stask_id'] > 0)
					{
						$modelST = SubTask::model()->findByAttributes(array('stask_id' => $val['stask_id']));
						$modelST->updated_by = Yii::app()->session["login"]["user_id"];
					}else{
						$modelST = new SubTask;
						$modelST->s_task_id = $key + 1;
						$modelST->created_by = Yii::app()->session["login"]["user_id"];
						$modelST->created_at = date("Y-m-d h:i:s");
						$subTaskId = Yii::app()->db->createCommand('Select max(stask_id) as maxId from tbl_sub_task ')->queryRow();
						$modelST->sub_task_id = $_POST['project_task_id'].sprintf("%02d", $val['task_id']).sprintf("%03d", $subTaskId['maxId'] + 1);
					}

						$modelST->pid_approval_id = $model->pid_id;
						$modelST->project_id = $model->project_id;
						$modelST->sub_project_id = $model->sub_project_id;
						$modelST->task_id = $val['task_id'];
						$modelST->emp_id = $val['emp_id'];
						$modelST->sub_task_name = $val['sub_task_name'];
						$modelST->est_hrs = $val['est_hrs'];
						$modelST->st_jira_id = $val['st_jira_id'];
						$modelST->st_inception_date = $val['st_inception_date'];
						$modelST->save(false);


                }
            $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
            'subtask' => $subtask,
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
        $dataProvider = new CActiveDataProvider('PidApproval');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->layout = 'column1';

        $model = new PidApproval('search');
        $model->unsetAttributes();  // clear any default values
        // if(isset($_REQUEST['PidApproval'])) {
        // $model->attributes=$_REQUEST['PidApproval'];
        // 	echo "seraching";
        //  CHelper::debug($_REQUEST);
        // }
        if (isset($_REQUEST['PidApproval'])) {
            $condition = $_REQUEST['PidApproval'];

            $whrcondition = '';
            if ($condition['emp_id'] != '')
                $whrcondition .= " AND em.first_name like '" . $condition['emp_id'] . "%' or em.last_name like '" . $condition['emp_id'] . "%'";
            if ($condition['sub_project_id'] != '')
                $whrcondition .= " AND sp.sub_project_name like '" . $condition['sub_project_id'] . "%'";
            if ($condition['project_name'] != '')
                $whrcondition .= " AND pm.project_name like '" . $condition['project_id'] . "'";
            if ($condition['sub_task_name'] != '')
                $whrcondition .= " AND st.sub_task_name like  '" . $condition['sub_task_name'] . "%'";
            if ($condition['sr'] != '')
                $whrcondition .= " AND st.pid_approval_id like '" . $condition['sr'] . "%'";
            if ($condition['task_id'] != '')
                $whrcondition .= " AND tt.task_name like '" . $condition['task_id'] . "%'";
            if ($condition['inception_date'] != '')
                $whrcondition .= " AND pa.inception_date like '" . $condition['inception_date'] . "%'";
        } else
            $whrcondition = '';


        if (isset($_REQUEST['PidApproval'])) {

            $pid_approval_id = '';
            $sql1 = "select st.pid_approval_id,st.jira_id,st.project_id,st.sub_project_id,st.task_id,st.stask_id,pa.inception_date,st.emp_id from tbl_project_management as pm inner join tbl_sub_project as sp on (pm.pid = sp.pid ) inner join tbl_sub_task as st on(st.project_id = pm.pid)
			 inner join tbl_task as tt on (st.task_id = tt.task_id) inner join tbl_pid_approval as pa on(st.pid_approval_id = pa.pid_id ) inner join tbl_employee as em on (st.emp_id = em.emp_id) where  st.sub_project_id = sp.spid $whrcondition ";
            $search_id = Yii::app()->db->createCommand($sql1)->queryRow();
            if ($condition['emp_id'] != '')
                $pid_approval_id .= "AND st.emp_id = " . $search_id['emp_id'];
            if ($condition['sub_task_name'] != '')
                $pid_approval_id .= "AND st.stask_id = " . $search_id['stask_id'];
            if ($condition['task_id'] != '')
                $pid_approval_id .= "AND st.task_id = " . $search_id['task_id'];

            if ($condition['sub_project_id'] != '')
                $pid_approval_id .= "AND t.sub_project_id =" . $search_id['sub_project_id'];
            if ($condition['project_name'] != '')
                $pid_approval_id .= "AND t.project_id = " . $search_id['project_id'];
            if ($condition['sr'] != '')
                $pid_approval_id .= "AND t.pid_id = " . $search_id['pid_approval_id'];

            if ($condition['inception_date'] != '')
                $pid_approval_id .= "AND t.inception_date =" . $search_id['inception_date'];
            //$pid_approval_id = "AND pid_id = ".$search_id['pid_approval_id'];
        }else {
            $pid_approval_id = '';
        }
        $data = array();

        $sql = "select t.*,st.task_id,st.stask_id,st.sub_task_name,st.sub_task_id,st.emp_id,st.est_hrs from tbl_pid_approval as t inner join tbl_sub_task as st on(st.pid_approval_id = t.pid_id )
  		order by t.pid_id desc	 "; //where approved!=2 and approved!=0 {$pid_approval_id}
        $results = Yii::app()->db->createCommand($sql)->queryAll();


        foreach ($results as $key => $stask) {
            $data[] = array(
                'sr' => $stask['pid_id'],
                'project_id' => $stask['project_id'],
                'jira_id' => $stask['jira_id'],
                'sub_project_id' => $stask['sub_project_id'],
                'inception_date' => $stask['inception_date'],
                'total_est_hrs' => $stask['total_est_hrs'],
                'comments' => $stask['comments'],
                'task_id' => $stask['task_id'],
                'sub_task_id' => $stask['sub_task_id'],
                'sub_task_name' => $stask['sub_task_name'],
                'emp_id' => $stask['emp_id'],
                'est_hrs' => $stask['est_hrs'],
                'approved' => $stask['approved'],
				'project_task_id' => $stask['project_task_id'],
				'task_title' => $stask['task_title'],
				'task_description' => $stask['task_description'],
            );
        }
        // $sql = "select t.* from tbl_pid_approval t where approved!=2 and approved!=0 {$pid_approval_id}";
        //  $results = Yii::app()->db->createCommand($sql)->queryAll();
//        CHelper::debug($results);
//         $data = array();
//         if (!empty($results)) {
//             foreach ($results as $result) {
//                 $sql1 = "SELECT * FROM tbl_sub_task  WHERE pid_approval_id={$result['pid_id']} ";
//                 $stasks = Yii::app()->db->createCommand($sql1)->queryAll();
// //                CHelper::debug($stasks);
//                 if (!empty($stasks)) {
//                     foreach ($stasks as $key => $stask) {
//                         $data[] = array(
//                             'sr' => $result['pid_id'],
//                             'project_id' => $result['project_id'],
//                             'sub_project_id' => $result['sub_project_id'],
//                             'inception_date' => $result['inception_date'],
//                             'total_est_hrs' => $result['total_est_hrs'],
//                             'comments' => $result['comments'],
//                             'task_id' => $stask['task_id'],
//                             'sub_task_name' => $stask['sub_task_name'],
//                             'emp_id' => $stask['emp_id'],
//                             'est_hrs' => $stask['est_hrs'],
//                             'approved' => $result['approved'],
//                         );
//                     }
//                 }
//             }
//         }
        // CHelper::debug($data);
        $this->render('admin', array(
            'data' => $data,
            'model' => $model,
        ));
    }

    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'application.components.ExportableGridBehavior',
                'filename' => 'dailyStatus.csv',
                'csvDelimiter' => ',', //i.e. Excel friendly csv delimiter
        ));
    }

    public function actionAllprojects() {
        $model = new PidApproval('search');
        $model->unsetAttributes();  // clear any default values
        // if(isset($_GET['PidApproval']))
        // 	$model->attributes=$_GET['PidApproval'];

        if (isset($_REQUEST['PidApproval'])) {
            // CHelper::debug($_REQUEST['PidApproval']);
            $condition = $_REQUEST['PidApproval'];

            $whrcondition = '';
            if ($condition['emp_id'] != '')
                $whrcondition .= " AND em.first_name like '" . trim($condition['emp_id']) . "' or em.last_name like '" . trim($condition['emp_id']) . "'";
            if ($condition['sub_project_id'] != '')
                $whrcondition .= " AND sp.sub_project_name like '" . trim($condition['sub_project_id']) . "%'";
            if ($condition['project_name'] != '')
                $whrcondition .= " AND pm.project_name like '" . trim($condition['project_id']) . "'";
            if ($condition['sub_task_name'] != '')
                $whrcondition .= " AND st.sub_task_name like  '" . trim($condition['sub_task_name']) . "%'";
            if ($condition['sr'] != '')
                $whrcondition .= " AND st.pid_approval_id like '" . trim($condition['sr']) . "%'";
            if ($condition['task_id'] != '')
                $whrcondition .= " AND tt.task_name like '" . trim($condition['task_id']) . "%'";
            if ($condition['inception_date'] != '')
                $whrcondition .= " AND pa.inception_date like '" . trim($condition['inception_date']) . "%'";
            if ($condition['approved'] != '')
                $whrcondition .= " AND pa.approved like '" . trim($condition['approved']) . "%'";
        } else
            $whrcondition = '';

        if (isset($_REQUEST['PidApproval'])) {

            $pid_approval_id = '';
            $sql1 = "select st.pid_approval_id,st.project_id,st.sub_project_id,st.task_id,st.stask_id,pa.inception_date,st.emp_id,pa.approved from tbl_project_management as pm inner join tbl_sub_project as sp on (pm.pid = sp.pid ) inner join tbl_sub_task as st on(st.project_id = pm.pid)
			 inner join tbl_task as tt on (st.task_id = tt.task_id) inner join tbl_pid_approval as pa on(st.pid_approval_id = pa.pid_id ) inner join tbl_employee as em on (st.emp_id = em.emp_id) where  st.sub_project_id = sp.spid $whrcondition ";
            $search_id = Yii::app()->db->createCommand($sql1)->queryRow();
            if ($condition['emp_id'] != '')
                $pid_approval_id .= "where st.emp_id = " . $search_id['emp_id'];
            if ($condition['sub_task_name'] != '')
                $pid_approval_id .= "where st.stask_id = " . $search_id['stask_id'];
            if ($condition['task_id'] != '')
                $pid_approval_id .= "where st.task_id = " . $search_id['task_id'];

            if ($condition['sub_project_id'] != '')
                $pid_approval_id .= "where st.sub_project_id =" . $search_id['sub_project_id'];
            if ($condition['project_name'] != '')
                $pid_approval_id .= "where st.project_id = " . $search_id['project_id'];
            if ($condition['sr'] != '')
                $pid_approval_id .= "where pa.pid_id = " . $search_id['pid_approval_id'];

            if ($condition['inception_date'] != '')
                $pid_approval_id .= "where st.inception_date =" . $search_id['inception_date'];
            if ($condition['approved'] != '')
                $pid_approval_id .= "where pa.approved =" . $search_id['approved'];
            //$pid_approval_id = "AND pid_id = ".$search_id['pid_approval_id'];
        }else {
            $pid_approval_id = '';
        }
        $data = array();

        $sql = "select pid_id as sr, st.project_id,st.sub_project_id,st.task_id,sub_task_name,inception_date,total_est_hrs,est_hrs,emp_id,
		   case when(approved = 2) then 'Approved' when (approved = 1) then 'In Queue' when (approved = 3) then 'Rejected' else null end as approved
		   ,comments,pa.created_by from tbl_sub_task as st
		   			inner join tbl_pid_approval as pa on (st.pid_approval_id = pa.pid_id) {$pid_approval_id};";
        $results = Yii::app()->db->createCommand($sql)->queryAll();


        $newResult = array();
        foreach ($results as $key => $value) {
            $newResult[$key]['sr'] = $value['sr'];
            $newResult[$key]['project_id'] = $this->getProjectname($value['project_id']);
            $newResult[$key]['sub_project_id'] = $this->getSUbProjectname($value['sub_project_id']);
            $newResult[$key]['task_id'] = $this->gettaskname($value['task_id']);
            $newResult[$key]['sub_task_name'] = $value['sub_task_name'];
            $newResult[$key]['inception_date'] = $value['inception_date'];
            $newResult[$key]['total_est_hrs'] = $value['total_est_hrs'];
            $newResult[$key]['est_hrs'] = $value['est_hrs'];
            $newResult[$key]['name'] = $this->getName($value['emp_id']);
            $newResult[$key]['PM'] = $this->getName($value['created_by']);
            $newResult[$key]['approved'] = $value['approved'];
            $newResult[$key]['comments'] = $value['comments'];
        }


        if ($this->isExportRequest()) {

            $export_column_name = array('Sr.no',
                'Program',
                'Project',
                'Type',
                'Task',
                'Start Date',
                'Assign Hours Project',
                'Assign Hours Task',
                'Employee',
                'PM',
                'Approval Status',
                'comments',);
            $filename = "All_Project_Details_Status" . date('d_m_Y') . "_" . date('H') . "_hr";
            CommonUtility::downloadDataInCSV($export_column_name, $newResult, $filename);
        }


        $this->render('AllProjects', array(
            'data' => $results, 'model' => $model,
        ));
    }

    public function getName($empid) {

        $results = Yii::app()->db->createCommand("select concat(first_name,' ',last_name) as name from tbl_employee where emp_id ={$empid};")->queryRow();

        return $results['name'];
    }

    public function getProjectname($pid) {

        $results = Yii::app()->db->createCommand("select project_name from tbl_project_management where pid ={$pid};")->queryRow();

        return $results['project_name'];
    }

    public function getSUbProjectname($spid) {

        $results = Yii::app()->db->createCommand("select sub_project_name from tbl_sub_project where spid ={$spid};")->queryRow();

        return $results['sub_project_name'];
    }

    public function gettaskname($task_id) {

        $results = Yii::app()->db->createCommand("select task_name from tbl_task where task_id ={$task_id};")->queryRow();

        return $results['task_name'];
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PidApproval the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PidApproval::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PidApproval $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pid-approval-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionApprovalstatus() {
        // CHelper::debug($_REQUEST);


        if ($_REQUEST['approval_id'] != '') {
            $approval_id = $_REQUEST['approval_id'];
            $str = explode('-', $approval_id);
            $id = $str[0];
            $approved = $str[1];
            //$model = new PidApproval;
            $model = $this->loadModel($id);
            $model->approved = $approved;
            $model->comments = $_POST['comments'];
            if ($model->save())
                if ($approved == 2) {
                    $pidGenration = PidMapping::model()->pidgenration($id);

                    Yii::app()->user->setFlash('success', 'PID Genrated successfully.');
                    //$pidGenration->pidgenration($id);
                }
            //$this->redirect(array('admin'));
            //$data = new PidMapping;
            if ($approved == 3) {
                Yii::app()->user->setFlash('error', 'Due to invalid data PID Rejected.');
            }
            $this->redirect(array('admin'));
            //return $this->actionAdmin;
        }
        $this->redirect(array('admin'));
    }

	/**
     * Fetches the project id to generate the task id.
     * @param Project Id $projectid
     */
	public function actionfetchSubProjectIdAndHours()
	{

		$name = SubProject::model()->findByPk($_POST['project_id']);
		//$name_pid = PidApproval::model()->findByPk($_POST['update_id']);
		if(!isset($name['project_id']) && empty($name['project_id']))
		{
			echo 0;die;
		}

		if(!empty($_POST['update_id']))
		{
			$TaskId = $_POST['update_id'];
		}else{
			$TaskId = Yii::app()->db->createCommand('Select max(pid_id) as maxId from tbl_pid_approval ')->queryRow();
			$TaskId = $TaskId['maxId'] + 1;
		}
		$projectformat['project_id'] = $name['project_id'].sprintf("%03d", $TaskId);

		$projectformat['allocated'] = Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs from tbl_sub_project sp left join tbl_sub_task st on st.sub_project_id  = sp.spid where spid = {$_POST['project_id']}")->queryRow();
		$projectformat['estimated'] = Yii::app()->db->createCommand("select sum(pl.level_hours * lm.budget_per_hour) as total_budget,sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid left join tbl_level_master lm on lm.level_id = pl.level_id where spid = {$_POST['project_id']}")->queryRow();
		$projectformat['utilized'] = Yii::app()->db->createCommand("SELECT  SEC_TO_TIME( SUM( TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  FROM tbl_day_comment where spid={$_POST['project_id']}")->queryRow();
		echo json_encode($projectformat);
	}

	public function actioncheckHoursAndBudget()
	{

		$totalBudget = Yii::app()->db->createCommand("select sum(pl.level_hours * lm.budget_per_hour) as total_budget,sum(level_hours) as allocated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid left join tbl_level_master lm on lm.level_id = pl.level_id where spid = {$_POST['project_id']}")->queryRow();

		if($_POST['update_pid'] == 0) {
			$total_allocated_budget = Yii::app()->db->createCommand("select SUM(st.est_hrs * lm.budget_per_hour) AS budget from tbl_sub_task st left join tbl_assign_resource_level rl on rl.emp_id = st.emp_id left join tbl_level_master lm on lm.level_id = rl.level_id where sub_project_id = {$_POST['project_id']}")->queryRow();
			$est_hrs = $_POST['allhrs'];
		}else{
			$total_allocated_budget = Yii::app()->db->createCommand("select SUM(st.est_hrs * lm.budget_per_hour) AS budget from tbl_sub_task st left join tbl_assign_resource_level rl on rl.emp_id = st.emp_id left join tbl_level_master lm on lm.level_id = rl.level_id where sub_project_id = {$_POST['project_id']} and pid_approval_id != {$_POST['update_pid']}")->queryRow();
			$remainhrs = Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs from tbl_sub_project sp left join tbl_sub_task st on st.sub_project_id  = sp.spid left join tbl_pid_approval pa on pa.pid_id = st.pid_approval_id where spid = {$_POST['project_id']} and pid_approval_id != {$_POST['update_pid']}")->queryRow();
			/* $total_hrs = $remainhrs['allocated_hrs'] + $_POST['totalhrs']; */
			$est_hrs = $remainhrs['allocated_hrs'] +  $_POST['allhrs'];


		}
		$total_hrs = $_POST['totalhrs'];


		if($est_hrs > $total_hrs){
			echo "Exceeding Allocated Hours({$total_hrs}). Please check the estimated hours.";die;
		}
		$final_allocated_budget = $total_allocated_budget['budget'] + $_POST['remaining_budget'];

		if($totalBudget['total_budget'] < $final_allocated_budget && $totalBudget['total_budget'] > 0)
		{
			echo "Exceeding Allocated Budget. Please check the estimated hours and resource.";die;
		}

		echo 0;

	}

}
