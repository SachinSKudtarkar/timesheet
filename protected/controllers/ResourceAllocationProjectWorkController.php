<?php

class ResourceAllocationProjectWorkController extends Controller {

    public function multipleInsert($model, $importData, $transactionSupport = true) {
        if (!empty($importData)) {
            $batches = array();
            $batches = array_chunk($importData, 200);
            $error = false;
            $success = false;
            $errorMessage = null;
            foreach ($batches as $batch) {
                if ($transactionSupport)
                    $transaction = $model->getDbConnection()->beginTransaction();
                try {
                    $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $batch);
                    $query->execute();
                    if ($transactionSupport)
                        $transaction->commit();
                    $success = true;
                } catch (Exception $ex) {
                    if ($transactionSupport)
                        $transaction->rollback();
                    $error = true;
                    $errorMessage = $ex->getMessage();
                }
                usleep(1000);
            }
        } else {
            Yii::app()->user->setFlash('error', "No records found.");
        }
    }

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
                'actions' => array('create', 'create_task', 'update', 'allocate', 'getPname', 'fetchAllocatedResource', 'resourceManagement', 'resourcearrangement', 'EmployeesProjects', 'deletMiss', 'getPId', 'getallocatedResource', 'allocateTask', 'manageTask', 'fetchAllocatedResourceForTask', 'resourceAllocatedtask', 'getProjectStatistics', 'test'),
//                'expression' => 'CHelper::isAccess("RESOURCEALLOCATION", "full_access")',
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
//                'expression' => 'CHelper::isAccess("RESOURCEALLOCATION", "full_access")',
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

    public function actiondeletMiss() {
//         $query = "SELECT * FROM tbl_infi_employee";
//            $result = Yii::app()->db->createCommand($query)->queryAll();
//            $x1=  array_column($result,'tbl_emp_id');
//            $x1=  array_unique($x1);
//
//             $y1 = implode("','", $x1);
//             echo $query = "delete FROM tbl_employee where emp_id not in('$y1') ";
//            $result = Yii::app()->db->createCommand($query)->execute();
//            Chelper::dump($result);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ResourceAllocationProjectWork;
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['ResourceAllocationProjectWork'])) {
            $model->attributes = $_POST['ResourceAllocationProjectWork'];
            $model->modified_by = Yii::app()->session['login']['user_id'];
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $getData = $model->projectStatus();  // Project Name and daily Status

        $this->render('create', array('model' => $model, 'data' => $getData, 'dataProvider' => $dataProvider));
    }

    public function actionCreate_task() {
        $model = new ResourceAllocationProjectWork;
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ResourceAllocationProjectWork'])) {
            /* 		print_r($_POST['ResourceAllocationProjectWork']);
              die(); */
            $model->attributes = $_POST['ResourceAllocationProjectWork'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $getData = $model->projectStatus();  // Project Name and daily Status

        $this->render('create_task', array('model' => $model, 'data' => $getData, 'dataProvider' => $dataProvider));
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

        if (isset($_POST['ResourceAllocationProjectWork'])) {

            $model->attributes = $_POST['ResourceAllocationProjectWork'];
            $model->modified_by = Yii::app()->session['login']['user_id'];
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionresourcearrangement() {
        $model = new ResourceAllocationProjectWork;
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');
        $displaydata = array();

        $pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
        if ($pid == 0) {
            $pid = isset($_REQUEST['projectid']) ? $_REQUEST['projectid'] : 0;
        }

        if (!empty($pid)) {

            $date_array = isset($_REQUEST['Date']) ? $_REQUEST['Date'] : array();
            if (!empty($date_array)) {
                Yii::app()->db->createCommand("UPDATE tbl_resource_arrangement SET is_deleted= 1 WHERE pid = {$pid}")->execute();

                $importData = array();
                foreach ($date_array as $key => $val) {

                    $todtbase = explode("-", $val);
                    $datetodb = $todtbase[0] . "-" . $todtbase[1] . "-" . $todtbase[2];
                    $modelResourceArrangement = new ResourceArrangement();
                    $modelResourceArrangement->pid = $pid;
                    $modelResourceArrangement->rid = $key;
                    $modelResourceArrangement->deadline_date = $datetodb;
                    $modelResourceArrangement->created_by = Yii::app()->session['login']['user_id'];
                    $modelResourceArrangement->updated_by = Yii::app()->session['login']['user_id'];
                    $modelResourceArrangement->created_date = date('Y-m-d H:i:s');
                    $modelResourceArrangement->updated_date = date('Y-m-d H:i:s');
                    $importData[] = $modelResourceArrangement->getAttributes();
                }


                $this->multipleInsert($modelResourceArrangement, $importData);
                Yii::app()->user->setFlash('success', "Resources are arranged successfully.");
                $this->redirect(array('resourcearrangement'));
            }

            $data = $this->getAllocatedResource($pid);
            $displaydata = array();
            if (!empty($data)) {
                $datarray = explode(',', $data);
                foreach ($datarray as $indarray) {
                    $eachdata = explode("==", $indarray);
                    $displaydata[$eachdata[0]] = $eachdata[1];
                }
            }

            $datedata = $this->getDatedataFromArrangedResource($pid);
        }


        $getData = $model->projectStatus();  // Project Name and daily Status
        $modelResourceArrangementDatedata = new ResourceArrangement();



        $this->render('resourcearrangement', array('model' => $model, 'data' => $getData, 'displaydata' => $displaydata, 'datedata' => $datedata, 'dataProvider' => $dataProvider));
    }

    public function actionResourceAllocatedtask() {


        $model = new SubProject;

        if (isset($_REQUEST['SubProject'])) {
            $condition = $_REQUEST['SubProject'];
            $whrcondition = '';
            if ($condition['name'] != '')
                $whrcondition .= " where em.first_name like '" . $condition['name'] . "%' or em.last_name like '" . $condition['name'] . "%'";
            if ($condition['sub_project_name'] != '')
                $whrcondition .= " where sp.sub_project_name like'" . $condition['sub_project_name'] . "%'";
            if ($condition['project_name'] != '')
                $whrcondition .= " where pp.project_name = '" . $condition['project_name'] . "%'";
			if(	$condition['Priority'] != '')
				$whrcondition .= " where sp.Priority = '" . $condition['Priority'] . "'";
        } else
            $whrcondition = '';
        //echo 'test'.$whrcondition;

        /* 	$query = "SELECT concat(em.first_name,' ',em.last_name) as name,pm.project_name,sb.sub_project_name,sb.estimated_end_date,sb.estimated_start_date,sb.total_hr_estimation_hour,sb.Priority,
          tmp.hours
          FROM tbl_task_allocation as ta inner join tbl_sub_project as sb on(ta.spid = sb.spid), tbl_employee em , tbl_project_management as pm,
          (select sum(hours) as hours from tbl_employee em ,tbl_day_comment as da , tbl_sub_project as sb  where da.spid = sb.spid and da.emp_id = em.emp_id group by da.spid ) as tmp
          WHERE  em.emp_id in (ta.allocated_resource) and sb.pid = pm.pid  order by em.first_name"; */
$query ="select concat(first_name,' ',last_name) as name,project_name as Program ,sub_project_name as Project ,sub_task_name as Task,est_hrs as Estimated_hours,time(sum(hours)) as consumed_hours from tbl_day_comment as dc inner join tbl_sub_task as st on (dc.stask_id = st.stask_id)
inner join tbl_sub_project as sp on (dc.spid= sp.spid)
inner join tbl_project_management as pp on (pp.pid = dc.pid)
inner join tbl_employee as em on (dc.emp_id = em.emp_id) $whrcondition
group by dc.stask_id order by em.emp_id;";

//         $query = "SELECT concat(em.first_name,' ',em.last_name) as name,sum(hours) as Consumed_hours,pm.project_name as Program ,sb.sub_project_name as Project ,sb.estimated_end_date,sb.estimated_start_date,sb.total_hr_estimation_hour as Aproved_hour,
// 		case when (sb.Priority = 1) then 'Heigh' when (sb.Priority = 2) then 'Medium' when (sb.Priority = 3) then 'Low' else null end as  Priority
// from tbl_employee em,tbl_day_comment as da,tbl_sub_project as sb,tbl_project_management as pm
// WHERE em.emp_id=da.emp_id AND da.spid=sb.spid AND da.pid=pm.pid $whrcondition
// group by da.spid order by em.emp_id";
        $rawData = Yii::app()->db->createCommand($query)->queryAll();


        $this->render('resourceallocatedtask', array(
            'rawData' => $rawData,
            'model' => $model
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
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ResourceAllocationProjectWork('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ResourceAllocationProjectWork']))
            $model->attributes = $_GET['ResourceAllocationProjectWork'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionManageTask() {
        $model = new TaskAllocation('search');


        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ResourceAllocationProjectWork']))
            $model->attributes = $_GET['ResourceAllocationProjectWork'];

        $this->render('manageTask', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ResourceAllocationProjectWork the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ResourceAllocationProjectWork::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ResourceAllocationProjectWork $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'resource-allocation-project-work-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAllocate() {

        $projectId = isset($_POST['ProjectName']) ? $_POST['ProjectName'] : 0;

        $_POST['txtarea2'] = array_filter($_POST['txtarea2']);
        $all_resources = isset($_POST['txtarea2']) ? implode(',', $_POST['txtarea2']) : '';
        $resourceIds = isset($_POST['txtarea2']) ? implode("','", $_POST['txtarea2']) : '';

        $query = "SELECT id FROM tbl_resource_allocation_project_work WHERE pid = '{$projectId}'";
        $result = YII::app()->db->createCommand($query)->queryRow();

        $done = 0;
        $modified_by = Yii::app()->session['login']['user_id'];
        $modified_at = date('Y-m-d H:i:s');
        if (!empty($result)) {
            $done = 1;
            $updateQuery = "UPDATE tbl_resource_allocation_project_work SET allocated_resource = '{$all_resources}', modified_by ='{$modified_by}' , modified_at = '{$modified_at}',day = '{$modified_at}'   WHERE id = '{$result['id']}'  ";
            Yii::app()->db->createCommand($updateQuery)->execute();


        } else {
            $model = new ResourceAllocationProjectWork;
            $model->pid = $projectId;
            $model->allocated_resource = $all_resources;
            $model->date = date('Y-m-d h:i:s');
            $model->created_by = Yii::app()->session['login']['user_id'];
            $model->modified_by = Yii::app()->session['login']['user_id'];
            $model->modified_at = date('Y-m-d H:i:s');
            $model->save(false);
            $done = 1;
        }

        if ($done) {

            $emails = array();
            $queryGetemails = "SELECT email FROM  tbl_employee WHERE emp_id IN('" . $resourceIds . "')";
            $emails = Yii::app()->db->createCommand($queryGetemails)->queryAll();

            $project_name = $this->getPname($model);
            $allocated_resources = $this->getReource($model);
            $allocator_name = $this->getUserName($model->created_by);
            $message = "<b>Dear Manager,</b> <br><br>";
            $message .= "<b>Resource(s) are allocated</b> <br><br>";
            $message .= "<b>Project ID</b>: " . $model->pid;
            $message .= "<br><br>";
            $message .= "<b>Project Name</b>: " . $project_name;
            $message .= "<br><br>";
            $message .= "<b>Allocated Resources</b>: " . $allocated_resources;
            $message .= "<br><br>";
            $message .= "<b>Allocated By</b>: " . $allocator_name;
            $message .= "<br><br>";
            $message .= "<b>Allocated Date</b>: " . $model->date;
            $message .= "<br><br>";
            $message .= "<br><br>";
            $message .= "Regards,";
            $message .= "<br>Infinity Team";
            $mail_sent = $this->sendEmail($message, $emails, $project_name);
            if (empty($resourceIds)) {
                Yii::app()->user->setFlash('success', "Resources are deallocated Successfully.");
            } else {
                Yii::app()->user->setFlash('success', "Resources allocated Successfully.");
            }

            $this->redirect(array('resourceallocationprojectwork/admin'));
        } else {
            Yii::app()->user->setFlash('error', "Resources allocation failed. Please select project and allocate resource properly.");
            $this->redirect(array('resourceallocationprojectwork/create'));
        }
    }

    public function actionAllocateTask() {

        $projectId = isset($_POST['ProjectName']) ? $_POST['ProjectName'] : 0;
        $subprojectId = isset($_POST['TaskName']) ? $_POST['TaskName'] : 0;

        $_POST['txtarea2'] = array_filter($_POST['txtarea2']);
        $all_resources = isset($_POST['txtarea2']) ? implode(',', $_POST['txtarea2']) : '';
        $resourceIds = isset($_POST['txtarea2']) ? implode("','", $_POST['txtarea2']) : '';

        $query = "SELECT id FROM tbl_task_allocation WHERE pid = '{$projectId}' and spid ='{$subprojectId}'";
        $result = YII::app()->db->createCommand($query)->queryRow();
        /* print_r($result);
          echo 'hi';
          exit; */
        $done = 0;
        if (empty($result)) {
            $sql = "insert into tbl_task_allocation (pid,spid,date,allocated_resource,created_by)
		values (:pid,:spid, :date,:allocated_resource,:created_by)";
            $parameters = array(":pid" => $projectId, ":spid" => $subprojectId, ":date" => date('Y-m-d H:i:s'), ":allocated_resource" => $all_resources, ":created_by" => Yii::app()->session['login']['user_id']);
            Yii::app()->db->createCommand($sql)->execute($parameters);
            $done = 1;
        } else {
            $updateQuery = "UPDATE tbl_task_allocation SET allocated_resource ='{$all_resources}' WHERE id ='{$result['id']}'";
            Yii::app()->db->createCommand($updateQuery)->execute();
            $done = 1;
        }
        if ($done) {

            $emails = array();
            $queryGetemails = "SELECT email FROM  tbl_employee WHERE emp_id IN('" . $resourceIds . "')";
            $emails = Yii::app()->db->createCommand($queryGetemails)->queryAll();
            $model = new ResourceAllocationProjectWork;

            $Taskname = $this->getTaskname($projectId);
            $allocated_resources = $this->getReource($model);
            $allocator_name = $this->getUserName($model->created_by);
            $message = "<b>Dear Manager,</b> <br><br>";
            $message .= "<b>Resource(s) are allocated</b> <br><br>";
            $message .= "<b>Project ID</b>: " . $model->pid;
            $message .= "<br><br>";
            $message .= "<b>Task Name</b>: " . $Taskname;
            $message .= "<br><br>";
            $message .= "<b>Allocated Resources</b>: " . $allocated_resources;
            $message .= "<br><br>";
            $message .= "<b>Allocated By</b>: " . $allocator_name;
            $message .= "<br><br>";
            $message .= "<b>Allocated Date</b>: " . $model->date;
            $message .= "<br><br>";
            $message .= "<br><br>";
            $message .= "Regards,";
            $message .= "<br>Infinity Team";
            $mail_sent = $this->sendEmail($message, $emails, $Taskname);
            if (empty($resourceIds)) {
                Yii::app()->user->setFlash('success', "Resources are deallocated Successfully.");
            } else {
                Yii::app()->user->setFlash('success', "Resources allocated Successfully.");
            }

            $this->redirect(array('resourceallocationprojectwork/create_task'));
        } else {
            Yii::app()->user->setFlash('error', "Resources allocation failed. Please select project and allocate resource properly.");
            $this->redirect(array('resourceallocationprojectwork/create_task'));
        }
    }

    public function getPname($model) {
        $query = "SELECT project_name from tbl_project_management WHERE pid = '{$model->pid}'";
        $pname = Yii::app()->db->createCommand($query)->queryRow();
        return $pname['project_name'];
    }

    public function getTaskname($projectId) {
        $query = "SELECT sub_project_name from tbl_sub_project WHERE pid ='{$projectId}'";
        $pname = Yii::app()->db->createCommand($query)->queryRow();
        return $pname['sub_project_name'];
    }

    public function getReource($model) {
        if (trim($model->allocated_resource) != '') {
            $query = "SELECT CONCAT(first_name,' ',last_name) as rname FROM tbl_employee WHERE emp_id IN(" . $model->allocated_resource . ")";
            $rsrce = Yii::app()->db->createCommand($query)->queryAll();
            $rname = array();
            foreach ($rsrce as $rc) {
                $rname[] = $rc['rname'];
            }

            return implode(', ', $rname);
        }
    }

    public function sendEmail($message, $emails, $project_name) {
        $from = "support@infinitylabs.in";
        $from_name = "Infinity Team";

        $to = array();
        $cc = array();
        //$to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");


        foreach ($emails as $indiemails) {
            $cc[] = array("email" => $indiemails['email'], "name" => "");
        }

        $subject = "Resource(s) Allocated For the Project " . $project_name;
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null);
    }

    public function getUserName($id) {
        $name = Yii::app()->db->createCommand()
                ->select('CONCAT(first_name," ",last_name) as full_name')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $id))
                ->queryRow();

        return $name['full_name'];
    }

    public function actionfetchAllocatedResource() {
        $pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
        echo $data = $this->getAllocatedResource($pid);
    }

    public function actionfetchAllocatedResourceForTask() {
        $spid = isset($_REQUEST['taskid']) ? $_REQUEST['taskid'] : 0;
        echo $data = $this->getAllocatedTaskResource($spid);
    }

    public function actionresourceManagement() {
        $project = isset($_POST['ProjectName']) ? $_POST['ProjectName'] : 0;
        $employee = isset($_POST['employee']) ? $_POST['employee'] : 0;
        $arraydata = array();

        if (!empty($project)) {
            $query = "SELECT emp_id FROM tbl_day_comment WHERE pid =$project";
            $result = Yii::app()->db->createCommand($query)->queryAll();
            //CHelper::debug($result);
            if (!empty($result)) {
                $result = array_column($result, 'emp_id');
                $result = array_count_values($result);
                foreach ($result as $key => $value) {
                    $intvalue = intval($value);
                    $username = $this->getUserName($key);
                    $arraydata[] = array($username, $intvalue);
                }
            }
        }

        if (!empty($employee)) {
            $query = "SELECT tdc.pid FROM tbl_day_comment tdc INNER JOIN tbl_employee tie ON (tdc.emp_id = tie.emp_id) WHERE tie.emp_id =$employee";
            $result = Yii::app()->db->createCommand($query)->queryAll();
            if (!empty($result)) {
                $result = array_column($result, 'pid');
                $result = array_count_values($result);
                foreach ($result as $key => $value) {
                    $intvalue = intval($value);
                    $ProjName = $this->GetProjName($key);
                    $arraydata[] = array($ProjName, $intvalue);
                }
            }
        }



        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');

        $this->render('resourcemanage', array('dataProvider' => $dataProvider, 'arraydata' => $arraydata));
    }

    public function getAllocatedResource($pid) {
        $query = "SELECT allocated_resource FROM tbl_resource_allocation_project_work WHERE pid ={$pid}";
        $resources = Yii::app()->db->createCommand($query)->queryRow();

        if (!empty($resources['allocated_resource'])) {
            $query = 'SELECT emp_id,CONCAT(first_name," ",last_name) as full_name FROM tbl_employee WHERE emp_id IN(' . $resources['allocated_resource'] . ') and is_active = 1 and is_password_changed="yes"';
            $empDetails = Yii::app()->db->createCommand($query)->queryAll();
            $rtrnstringarray = array();
            foreach ($empDetails as $indidetails) {
                $rtrnstringarray[] = $indidetails['emp_id'] . "==" . $indidetails['full_name'];
            }
            return $rtrnstring = implode(',', $rtrnstringarray);
        } else
            return 0;
    }

    public function getAllocatedTaskResource($spid) {
        $query = "SELECT allocated_resource FROM tbl_task_allocation  WHERE spid ={$spid}";
        $resources = Yii::app()->db->createCommand($query)->queryRow();

        if (!empty($resources['allocated_resource'])) {
            $query = 'SELECT emp_id,CONCAT(first_name," ",last_name) as full_name FROM tbl_employee WHERE emp_id IN(' . $resources['allocated_resource'] . ')';
            $empDetails = Yii::app()->db->createCommand($query)->queryAll();
            $rtrnstringarray = array();
            foreach ($empDetails as $indidetails) {
                $rtrnstringarray[] = $indidetails['emp_id'] . "==" . $indidetails['full_name'];
            }
            return $rtrnstring = implode(',', $rtrnstringarray);
        } else
            return 0;
    }

    public function GetProjName($pid = null) {
        $query = "SELECT project_name FROM tbl_project_management WHERE pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['project_name'];
    }

    public function getDatedataFromArrangedResource($pid) {
        $query = "SELECT rid,deadline_date FROM tbl_resource_arrangement WHERE is_deleted = 0 AND pid = $pid";
        $result = Yii::app()->db->createCommand($query)->queryAll();
        $returndata = array();

        foreach ($result as $indiresult) {
            $returndata[$indiresult['rid']] = date('Y-m-d', strtotime($indiresult['deadline_date']));
        }

        return $returndata;
    }

    public function actionEmployeesProjects() {
        $empdata = InfiEmployee::model()->getEmployees();
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');
        $multdata = array();
        $truncquery = "Truncate tbl_resource_availability";
        $exc = Yii::app()->db->createCommand($truncquery)->execute();

        foreach ($empdata as $indidata) {

            $modelr = new ResourceAvailability();

            $infi_id = $indidata['id'];
            $query = "SELECT pid FROM tbl_resource_allocation_project_work WHERE allocated_resource like '%," . $infi_id . "'
                        OR allocated_resource like '%," . $infi_id . ",%' OR allocated_resource like '" . $infi_id . ",%' OR allocated_resource = " . $infi_id;
            $result = Yii::app()->db->createCommand($query)->queryAll();
            $result = array_column($result, 'pid');
            $pidString = implode("','", $result);

            $query_getProject = "SELECT project_name FROM tbl_project_management WHERE pid IN('" . $pidString . "')";
            $projectData = Yii::app()->db->createCommand($query_getProject)->queryAll();
            $projectData = array_column($projectData, 'project_name');
            $projString = implode(", ", $projectData);

            $query_estimate = "SELECT DATE(estimated_end_date) as estimated_end_date FROM tbl_project_management WHERE pid IN('" . $pidString . "')";
            $projectEstimate = Yii::app()->db->createCommand($query_estimate)->queryAll();

            $tot = count($projectEstimate);
            $tmpdate = date_create("'" . $projectEstimate[0]['estimated_end_date'] . "'");

            for ($i = 1; $i < $tot; $i++) {
                if (strtotime($tmpdate) > strtotime($projectEstimate[$i]['estimated_end_date'])) {
                    $tmpdate = $tmpdate;
                } else {
                    $tmpdate = $projectEstimate[$i]['estimated_end_date'];
                }
            }

            $diff = "Not specified";
            if ($tot == 1) {
                $tmpdate = $projectEstimate[0]['estimated_end_date'];
            }
            if (!empty($tmpdate)) {
                $now = time(); // or your date as well
                $your_date = strtotime($tmpdate);
                $datediff = $your_date - $now;
                $diff = floor($datediff / (60 * 60 * 24));
            }

            $modelr->emp_name = $indidata['first_name'];
            $modelr->projects = $projString;
            $modelr->estimated_date = $tmpdate;
            $modelr->days_remained = $diff;
            $multdata[] = $modelr->getAttributes();
        }

        $this->multipleInsert($modelr, $multdata);

        $showdata = ResourceAvailability::getData();


        $this->render('empvspoject', array('dataProvider' => $dataProvider, 'showdata' => $showdata));
    }

    public function actionGetPId() {
        $query = "select spid,sub_project_name from tbl_sub_project where pid ={$_POST['pid']}";
        $data = Yii::app()->db->createCommand($query)->queryAll();
        echo CHtml::tag('option', array('value' => ''), 'Please select Project', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $name['spid']), CHtml::encode($name['sub_project_name']), true);
        }
    }

    public function actionGetallocatedResource() {

        if(!empty($_REQUEST['pid']))
        {
            $query = "select allocated_resource from tbl_resource_allocation_project_work  where pid ={$_REQUEST['pid']}";
            $allocated_resource = Yii::app()->db->createCommand($query)->queryRow();

            if(!empty($allocated_resource)) {

                $query1 = "select emp.emp_id,concat(first_name,' ',last_name) as name,lm.level_name, lm.budget_per_hour
                            from tbl_employee emp
                            left join tbl_assign_resource_level rl on rl.emp_id = emp.emp_id
                            left join tbl_level_master lm on lm.level_id = rl.level_id
                            where emp.emp_id in ({$allocated_resource['allocated_resource']}) order by first_name";
                $resource = Yii::app()->db->createCommand($query1)->queryAll();

                echo CHtml::tag('option', array('value' => ''), 'Please select resource', true);

                foreach ($resource as $value => $name) {

                    $name_with_level = $name['name'];
                    if(!empty($name['level_name'])){
                        $name_with_level = $name['name'].'('.$name['level_name'].')';
                    }

                    echo CHtml::tag('option', array('value' => $name['emp_id'],'id'=>$name['budget_per_hour']), CHtml::encode($name_with_level), true);
                }


            }

        } else {
            echo 0;            
        }





        
    }

    public function actionGetProjectStatistics() {

        $model = new TaskAllocation;


        $model = new SubProject;

        if (isset($_REQUEST['SubProject'])) {
            $condition = $_REQUEST['SubProject'];
            $whrcondition = '';
            if ($condition['name'] != '')
                $whrcondition .= " AND em.first_name like '" . $condition['name'] . "%' or em.last_name like '" . $condition['name'] . "%'";
            if ($condition['sub_project_name'] != '')
                $whrcondition .= " AND sb.sub_project_name = '" . $condition['sub_project_name'] . "'";
            if ($condition['project_name'] != '')
                $whrcondition .= " AND pm.project_name = '" . $condition['project_name'] . "'";
        }


        $query = "select sb.spid,sb.sub_project_name,total_hr_estimation_hour as total_asign_hours,sum(da.hours) as consumed_hours from tbl_task_allocation as ta inner join tbl_sub_project as sb on (ta.spid = sb.spid) ,tbl_day_comment as da  where da.emp_id in (ta.allocated_resource) and sb.spid = da.spid group by sb.spid";
        $rawData = Yii::app()->db->createCommand($query)->queryAll();

        $filteredData = $filtersForm->filter($rawData);
        $dataProvider = new CArrayDataProvider($rawData);

// Render
        $this->render('projectStatistics', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionTest() {
        $newData = $da = $nn = $result = array();
        $pid = 9;
        $userId = 2072;

        $model = new SubProject;

        if (isset($_REQUEST['SubProject'])) {
            $condition = $_REQUEST['SubProject'];
            $whrcondition = '';
            if ($condition['name'] != '')
                $whrcondition .= " AND em.first_name like '" . $condition['name'] . "%' or em.last_name like '" . $condition['name'] . "%'";
            if ($condition['sub_project_name'] != '')
                $whrcondition .= " AND sb.sub_project_name = '" . $condition['sub_project_name'] . "'";
            if ($condition['project_name'] != '')
                $whrcondition .= " AND pm.project_name = '" . $condition['project_name'] . "'";
        } else
            $whrcondition = '';

  $query = "select sb.spid,sb.sub_project_name,sb.total_hr_estimation_hour,group_concat(concat(first_name,' ',last_name)) as allocated_resource from tbl_sub_project as sb inner join tbl_task_allocation  as ra on(sb.pid=ra.pid) inner join tbl_employee as emp on FIND_IN_SET(emp.emp_id,ra.allocated_resource) where sb.is_deleted = 0 and sb.spid = ra.spid group by sb.spid";

            $rawData = Yii::app()->db->createCommand($query)->queryAll();


        $this->render('hoursallocation', array(
            'rawData' => $rawData,
            'model' => $model
        ));
    }




}
