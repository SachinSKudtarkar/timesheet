<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

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
                'actions' => array('index', 'view','updateStatus','updateApproval'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin','fetchProjectId','updateLog','updateTask','uploadExcel','replaceId','ajaxUpload','checkTasklist','updateHrs','updateCap','UpdatePlog','CheckEsec','Dbquery'),
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
        $hostName = $_SERVER['SERVER_NAME'];
        $model->estHrsradio = 'M';
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {
            // print_r($_POST);die;
            $model->attributes = $_POST['SubProject'];
            $model->created_date = date('Y-m-d h:i:s');
            $model->created_by = Yii::app()->session['login']['user_id'];
            $model->approval_status = 2;
            $model->unqid = $_POST['unqid'];
            $valid = $_POST['SubProject'];
            $model->project_id = $this->getProjectId($valid['pid']);
            if (empty($valid['pid']) || empty($valid['sub_project_name']) || empty($valid['sub_project_description']) || empty($valid['requester']) || empty($valid['status']) || empty($valid['priority'])) {
                Yii::app()->user->setFlash('error', 'Please fill all required fields.');
            } else {
                
                // if($model->save())
                if($model->save())
                {    
                    //$this->insertUpdateEmployee(1, $model);
                    $insert_id = Yii::app()->db->getLastInsertID();
                    $postHrsArr = [];
                    if($valid['estHrsradio'] == 'E') {
                        $postHrsArr = json_decode($valid['hoursArray'], true);
                    }elseif($valid['estHrsradio'] == 'M') {
                        $postHrsArr = $_POST['group-a'];
                        foreach ($postHrsArr as $key => $value) {
                            $tasktemp = new TaskTitle();
                            $tasktemp->unqid = $model->unqid;
                            $tasktemp->task_title = "This is manual upload";
                            $tasktemp->task_description = "This is manual upload";
                            $tasklevelarr = Yii::app()->db->createCommand("select level_name from tbl_level_master where level_id = {$value['level_id']}")->queryRow();
                            $tasktemp->task_level = $tasklevelarr['level_name']; 
                            $tasktemp->task_est_hrs = $value['level_hours'];
                            $tasktemp->approval_status = '2';
                            $tasktemp->task_status = 'NEW';
                            $tasktemp->created_at = Yii::app()->session["login"]["user_id"];
                            $tasktemp->created_at = date("Y-m-d h:i:s");
                            $tasktemp->save(false);
                        }
                    }
                    
                    
                    // $this->sendApprovalMail($insert_id);   
                    Yii::app()->user->setFlash('success', "{$valid['sub_project_name']} has been created and a mail for project approval has been sent successfully.");
                    $this->redirect(array('admin'));
                }
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
        $model->estHrsradio = 'M';
        $levels = ProjectLevelAllocation::model()->findAll("project_id=$id");
        $levels_log = Yii::app()->db->createCommand("SELECT CONCAT('CR',rl_log_id) as cr, project_id,GROUP_CONCAT(CONCAT(level_name, '(', new_level_hours, ')'), ' ') as log, comments FROM tbl_project_level_allocation_log pla join tbl_level_master lm on lm.level_id = pla.level_id where project_id = {$id} GROUP BY rl_log_id;")->queryAll();
        //$hours_label['estimated'] = Yii::app()->db->createCommand("select sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid where spid = {$id}")->queryRow();
        $estimatedArr = Yii::app()->db->createCommand("select level_name, level_hours, budget_per_hour from tbl_project_level_allocation pla inner join tbl_level_master lm on lm.level_id = pla.level_id  where pla.project_id = {$id}")->queryAll();

        $hours_label['estimated']['estimated_hrs'] = 0;
        foreach($estimatedArr as $e){
            $hours_label['estimated']['estimated_hrs'] += $e['level_hours'];
        }

        $hours_label['allocated'] = Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs from tbl_sub_project sp left join tbl_sub_task st on st.sub_project_id  = sp.spid where spid = {$id}")->queryRow();
        $hours_label['utilized'] = Yii::app()->db->createCommand("SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  FROM tbl_day_comment where spid={$id}")->queryRow();

        $excelHours = $this->getExcelHours($model);

        $newHours = Yii::app()->db->createCommand("select unqid,task_level,SUM(task_est_hrs) as level_hours, (select level_id from tbl_level_master where level_name LIKE task_level) as level_id from tbl_task_temp where unqid LIKE '%{$model->unqid}%' and task_status='NEW' and approval_status = '2' group by task_level")->queryAll();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {
                        
            $model->attributes = $_POST['SubProject'];
            $model->project_id = $_POST['sub_project_id'];
            $model->updated_date = date('Y-m-d h:i:s');
            $model->updated_by = Yii::app()->session['login']['user_id'];
            $model->approval_status = 2;

            if ($model->save())
                //$this->insertUpdateEmployee(2,$model);
                // echo 'asdasd';die;
                $postHrsArr = [];
            // print_r($_POST);
                $radioArray = $_POST['SubProject']['estHrsradio'];
                if($radioArray == 'E') {
                    $postHrsArr = json_decode($_POST['SubProject']['hoursArray'], true);
                    
                }elseif($radioArray == 'M') {
                    $postHrsArr = $_POST['group-a'];
                    // print_r($postHrsArr);die;
                    foreach ($postHrsArr as $key => $value) {
                        $tasktemp = new TaskTitle();
                        $tasktemp->unqid = $model->unqid;
                        $tasktemp->task_title = "This is manual upload";
                        $tasktemp->task_description = "This is manual upload";
                        $tasklevelarr = Yii::app()->db->createCommand("select level_name from tbl_level_master where level_id = {$value['level_id']}")->queryRow();
                        $tasktemp->task_level = $tasklevelarr['level_name']; 
                        $tasktemp->task_est_hrs = $value['level_hours'];
                        $tasktemp->approval_status = '2';
                        $tasktemp->task_status = 'NEW';
                        $tasktemp->created_at = Yii::app()->session["login"]["user_id"];
                        $tasktemp->created_at = date("Y-m-d h:i:s");
                        $tasktemp->save(false);
                    }
                }
                
                
            $this->sendApprovalMail($id);
            if($model->approval_status != 1)
            {
                Yii::app()->user->setFlash('success', "{$valid['sub_project_name']} has been updated and a mail for project approval has been sent successfully.");
            }   
            
            $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
            'levels' => $levels,
            'hours_label' => $hours_label,
            'estimatedArr' => $estimatedArr,
            'levels_log' => $levels_log,
            'excelHours' => $excelHours,
            'newHours' => $newHours,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);

        $tasks = PidApproval::model()->findByAttributes(array('sub_project_id'=>$id));
        if(count($tasks) <= 0){
            $model->delete();
            //$model->is_deleted  = 1;
            //$model->save();
        }else{
            //Yii::app( )->user->setFlash('failed', "Program can not be deleted.");
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
        $dataProvider = new CActiveDataProvider('SubProject', array(
            'criteria' => array(
                'order' => 'created_date DESC',
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
    public function actionfetchProjectId() {

        $name = ProjectManagement::model()->findByPk($_POST['project_id']);

        if (!empty($_POST['update_id'])) {
            $TaskId = $_POST['update_id'];
        } else {
            $TaskId = Yii::app()->db->createCommand('Select max(spid) as maxId from tbl_sub_project ')->queryRow();
            $TaskId = $TaskId['maxId'] + 1;
        }

        $projectformat = $name['project_id'] . sprintf("%03d", $TaskId);
        echo $projectformat;
    }

    /**
     * Updates the project level allocation log with the existing valies
     */
    public function actionupdateLog() {

        $levels = ProjectLevelAllocation::model()->findAll();

        foreach ($levels as $key => $value) {
            // echo $value->project_id.'<br>';
            $modelPLALog = new ProjectLevelAllocationLog;
            $modelPLALog->rl_log_id = 0;
            $modelPLALog->project_id = $value->project_id;
            $modelPLALog->level_id = $value->level_id;
            $modelPLALog->old_level_hours = 0;
            $modelPLALog->new_level_hours = $value->level_hours;
            $modelPLALog->comments = 'initial comment';
            $modelPLALog->created_at = $value->created_at;
            $modelPLALog->created_by = $value->created_by;
            // echo '<pre>';print_r($value);print_r($modelPLALog);die;
            $modelPLALog->save(false);
        }

        echo 'Log has been updated successfully';
        die;
    }

    /**
     * Updates the pid approval with the correct task ids and sub task with updated sub task ids
     */
    public function actionupdateTask($id) {

        if($id == 1) {

            $pids = PidApproval::model()->findAll();

            foreach ($pids as $key => $value) {

                $modelSP = SubProject::model()->findByPk($value->sub_project_id);

                if(!empty($modelSP->project_id)){

                    $modelPid = PidApproval::model()->findByPk($value->pid_id);
                    echo $modelPid->project_task_id.'-->';
                    $modelPid->project_task_id = $modelSP->project_id.sprintf('%03d',$value->pid_id);
                    echo $modelPid->project_task_id.'<br>';
                    $modelPid->save(false);

                }
            }
            echo 'Task has been updated successfully';
        } else if($id == 2) {

            $subtasks = SubTask::model()->findAll();

            foreach ($subtasks as $key => $value) {

                $modelPA = PidApproval::model()->findByPk($value->pid_approval_id);

                if(!empty($modelPA->project_task_id)){

                    $modelST = SubTask::model()->findByPk($value->stask_id);
                    echo $modelST->sub_task_id.'-->';
                    $modelST->sub_task_id = $modelPA->project_task_id.sprintf('%02d',$value->task_id).sprintf('%03d',$value->stask_id);
                    echo $modelST->sub_task_id.'<br>';
                    $modelST->save(false);
                }
            }
            echo 'Sub Task has been updated successfully';
        }
        die;
    }

    public function sendApprovalMail($project_id)
    {
        $total_hours = 0;
        $hostName = $_SERVER['SERVER_NAME'];
        $projectDetails = Yii::app()->db->createCommand("select sub_project_name,sub_project_description,pa.level_id ,lm.level_name,level_hours,concat(em.first_name,' ',em.last_name) as username,sp.created_by as emp_id from  tbl_project_level_allocation pa left join tbl_sub_project sp  on sp.spid = pa.project_id left join tbl_level_master lm on lm.level_id = pa.level_id left join tbl_employee em on em.emp_id = sp.created_by where spid = {$project_id}")->queryAll();
        // print_r($projectDetails);die;
        $baseurl =  Yii::app()->getBaseUrl(true);
        
        $message = "";
            $message .= "<br>";
            $message .= "<b>Team,</b> <br><br>";
            $message .= "Please find the details of the project created by ".ucwords($projectDetails[0]['username'])."<br><br>";
            // $message .= "Project Name: " . $value['emp_name'] . "<br>";
            $message .= "<table border = 1>";
            $message .= "<tr><td>Project Name: </td><td>".$projectDetails[0]['sub_project_name']."</td></tr>";
            $message .= "<tr><td>Project Description: </td><td>".$projectDetails[0]['sub_project_description']."</td></tr>";
            foreach ($projectDetails as $key => $value) {
                $total_hours += $value['level_hours'];
                $message .= "<tr><td>".$value['level_name']." </td><td>".$value['level_hours']."</td></tr>";
            }
            $message .= "<tr><td>Total Estimated Hours: </td><td>".$total_hours."</td></tr>";
            $message .= "</table></br>";
            $message .= "<p>Please click on one of the below links to approve or reject the project estimation.</p>";
            $message .= "<p><a href='{$baseurl}/subproject/updateStatus/1{$project_id}'>Approve </a><strong> OR </strong><a href='{$baseurl}/subproject/updateStatus/0{$project_id}'>Reject </a>";
            // $message .= "Note: This is still under testing.";
            $message .= "<br><br>";
            $message .= "Regards,";
            $message .= "<br>CNAAP";
            $message . "<BR /><br />";
            $from = "support@cnaap.net";
            $from_name = "CNAAP Timesheet";
            $to = array();
            $cc = array();
            $bcc = array();

            // $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");  
            $manager_details = Yii::app()->db->createCommand("select parent_id,rm.emp_id,concat(first_name,' ',last_name) as username, email from tbl_access_role_master rm inner join tbl_employee em on em.emp_id = rm.parent_id where rm.emp_id = {$projectDetails[0]['emp_id']} and rm.parent_id != {$projectDetails[0]['emp_id']}")->queryRow();
            
            if($baseurl == "http://localhost:8081/timesheet" || $baseurl == "https://staging.cnaap.net/timesheet")
            {

                $to[] = array("email" => $manager_details['email'], "name" => $manager_details['username']);  
                // $to[] = array("email" => 'ridhisha.joshi@infinitylabs.in', "name" => "ridhisha joshi");  
                // $to[] = array("email" => 'mudliyarp@hcl.com', "name" => "Prabhakar Mudliyar");  
            }else{

                $to[] = array("email" => $manager_details['email'], "name" => $manager_details['username']);  
                // $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
                //$bcc[] = array("email" => "Vinay.Nataraj@infinitylabs.in", "name" => "Vinay Nataraj");
                //$bcc[] = array("email" => "sachin.potdar@infinitylabs.in", "name" => "Sachin Potdar");
                
            }
            
            $subject = "CNAAP New Project Approval";
            // echo $message;die;
            echo CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }

    public function actionupdateStatus($id)
    {
        $status = $id[0];
       // echo $status;die;
        $project_id = substr($id, 1);
        $projectArr = Yii::app()->db->createCommand("select created_by from tbl_sub_project where spid= {$project_id}")->queryRow();
        $parent_id = Yii::app()->session['login']['user_id'];
        $empp_id = $projectArr['created_by'];
        $approvalDetails = Yii::app()->db->createCommand("select count(*) as count from tbl_access_role_master where parent_id = {$parent_id} and emp_id = {$empp_id}")->queryRow();
        
        if(Yii::app()->session['login']['user_id'] == '3616' || Yii::app()->session['login']['user_id'] == '6' || $approvalDetails['count'] > 0){ 
            

            if($status == 1)
            {
                $unqidarr = Yii::app()->db->createCommand("select unqid from tbl_sub_project where spid = {$project_id}")->queryRow();
                
                $newHours = Yii::app()->db->createCommand("select unqid,task_level,SUM(task_est_hrs) as level_hours, (select level_id from tbl_level_master where level_name LIKE task_level) as level_id from tbl_task_temp where unqid LIKE '%{$unqidarr['unqid']}%' and task_status='NEW' and approval_status = '2' group by task_level")->queryAll();
                
                $checkLogCount = Yii::app()->db->createCommand('Select rl_log_id from tbl_project_level_allocation_log where project_id=' . $id . ' order by rl_id desc')->queryRow();
                $rl_log_id = $checkLogCount['rl_log_id'] + 1;
                foreach ($newHours as $key => $val) {

                    if (!empty($val['level_hours'])) {
                        $checkCount = Yii::app()->db->createCommand('Select * from tbl_project_level_allocation where project_id=' . $project_id . ' and level_id=' . $val['level_id'])->queryRow();
                        if (isset($checkCount['id'])) {
                            $modelPLA = ProjectLevelAllocation::model()->findByAttributes(array('id' => $checkCount['id']));
                            $modelPLA->modified_by = Yii::app()->session["login"]["user_id"];
                            $modelPLA->updated_at = date("Y-m-d h:i:s");
                            $updHours = $val['level_hours'] + $checkCount['level_hours'];
                        } else {
                            $modelPLA = new ProjectLevelAllocation;
                            $modelPLA->created_by = Yii::app()->session["login"]["user_id"];
                            $modelPLA->created_at = date("Y-m-d h:i:s");
                            $updHours = $val['level_hours'];
                        }

                        $modelPLA->project_id = $project_id;
                        $modelPLA->level_id = $val['level_id'];
                        $modelPLA->level_hours = $updHours;
                        $modelPLA->save(false);

                        //Update recorded as log in tbl_project_level_allocation_log for every update / insert
                        $modelPLALog = new ProjectLevelAllocationLog;
                        $modelPLALog->project_id = $id;
                        $modelPLALog->level_id = $val['level_id'];
                        $modelPLALog->old_level_hours = $checkCount['level_hours'];
                        $modelPLALog->new_level_hours = $updHours;
                        $modelPLALog->comments = $_POST['level_comments'];
                        $modelPLALog->rl_log_id = empty($checkLogCount) ? 0 : $rl_log_id;
                        $modelPLALog->created_by = Yii::app()->session["login"]["user_id"];
                        $modelPLALog->created_at = date("Y-m-d h:i:s");
                        $modelPLALog->save(false);
                    }
                    //$importData[] = $modelST->getAttributes();
                }
            }

            $projectDetails = Yii::app()->db->createCommand("update tbl_sub_project set approval_status = {$status} where spid={$project_id} and approval_status = 2")->execute();        
            
            if($status == 1 || $status == 0){
                Yii::app()->db->createCommand("UPDATE tbl_task_temp T1 LEFT JOIN tbl_sub_project T2 ON T2.unqid = T1.unqid SET T1.approval_status = '{$status}', T1.task_status = 'OLD' WHERE T2.spid = {$project_id} and T1.task_status = 'NEW'")->execute();        
            }
            $appstatus = $status == 1 ? 'Approved.' : 'Rejected.'; 
            
            // echo "<h1>Project Estimation has been {$appstatus}</h1>";
            $message = "The hours estimation for the project has been {$appstatus}.";
            // $this->render('statusview', array(
            //     'model' => $this->loadModel($id),
            //     'data' => "The hours estimation for the project has been {$appstatus}."
            // ));
            Yii::app()->user->setFlash('success', $message);
        }
        $this->redirect(array('admin'));

        // echo base64_decode($project_id);
    }

    public function actionuploadExcel()
    {   
        echo 'asdasd';
        // print_r($_REQUEST);
        // var_dump($_FILES);
        die;
    }

    public function actionupdateApproval()
    {
        $projectDetails = Yii::app()->db->createCommand("update tbl_sub_project set approval_status = 1 where approval_status = 2")->execute();

        echo "All project's approval status has been updated as approved";
    }

    public function actionreplaceId()
    {
        echo 'Started<br>';
        $find_id_1 = 4552;
        $find_id_2 = 2448;
        $replace_id = 14594;
        //{$replace_id}_{$find_id_1}_{$find_id_2}
        // select * from tbl_project_management where created_by = 3616 or updated_by = 3616;
        // update tbl_project_management set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_project_management set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        // update tbl_project_management set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_project_management set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2}")->execute();
        echo 'Projec Management Updated<br>';

        // select * from tbl_sub_project where created_by = 3616 or updated_by = 3616;
        // update tbl_sub_project set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_sub_project set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        // update tbl_sub_project set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_sub_project set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2};")->execute();
        echo 'Sub Project Updated<br>';

        // select * from tbl_pid_approval where created_by = 3616;
        // update tbl_pid_approval set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_pid_approval set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        echo 'Pid approval updated<br>';

        // select * from tbl_sub_task where emp_id = 3616 or updated_by = 3616;
        // update tbl_sub_task set emp_id = {$replace_id} where emp_id = {$find_id_1} or emp_id = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_sub_task set emp_id = {$replace_id} where emp_id = {$find_id_1} or emp_id = {$find_id_2}")->execute();
        // update tbl_sub_task set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_sub_task set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        echo 'Sub Task Updated<br>';

        // select * from tbl_day_comment where emp_id = 3616 or created_by = 3616;
        // update tbl_day_comment set emp_id = {$replace_id} where emp_id = {$find_id_1} or emp_id = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_day_comment set emp_id = {$replace_id} where emp_id = {$find_id_1} or emp_id = {$find_id_2};")->execute();
        // update tbl_day_comment set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_day_comment set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        echo 'Day Comment Updated<br>';

        // select * from tbl_resource_allocation_project_work where FIND_IN_SET(6,allocated_resource);
        // update tbl_resource_allocation_project_work set allocated_resource = replace(allocated_resource , '{$find_id_1}', '{$replace_id}') where FIND_IN_SET({$find_id_1},allocated_resource);
        Yii::app()->db->createCommand("update tbl_resource_allocation_project_work set allocated_resource = replace(allocated_resource , '{$find_id_1}', '{$replace_id}') where FIND_IN_SET({$find_id_1},allocated_resource)")->execute();
        // update tbl_resource_allocation_project_work set allocated_resource = replace(allocated_resource , '{$find_id_2}', '{$replace_id}') where FIND_IN_SET({$find_id_2},allocated_resource);
        Yii::app()->db->createCommand("update tbl_resource_allocation_project_work set allocated_resource = replace(allocated_resource , '{$find_id_2}', '{$replace_id}') where FIND_IN_SET({$find_id_2},allocated_resource)")->execute();
        // update tbl_resource_allocation_project_work set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_resource_allocation_project_work set created_by = {$replace_id} where created_by = {$find_id_1} or created_by = {$find_id_2}")->execute();
        // update tbl_resource_allocation_project_work set modified_by = {$replace_id} where modified_by = {$find_id_1} or modified_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_resource_allocation_project_work set modified_by = {$replace_id} where modified_by = {$find_id_1} or modified_by = {$find_id_2}")->execute();
        echo 'Resource allocated updated<br>';

        //update tbl_employee set last_name = concat(last_name,'*') where emp_id = 3616 or emp_id = 3617;
        Yii::app()->db->createCommand("update tbl_employee set last_name = concat(last_name,'*') where emp_id = {$find_id_1} or emp_id = {$find_id_2}")->execute();
        echo "Employee table updated<br>";
        
        // update tbl_access_role_master set parent_id = 6 where parent_id = 3616;
        Yii::app()->db->createCommand("update tbl_access_role_master set parent_id = {$replace_id} where parent_id = {$find_id_1}")->execute();
        Yii::app()->db->createCommand("update tbl_access_role_master set parent_id = {$replace_id} where parent_id = {$find_id_2}")->execute();

        // update tbl_access_role_master set emp_id = 6 where emp_id = 3616;
        Yii::app()->db->createCommand("update tbl_access_role_master set emp_id = {$replace_id} where emp_id = {$find_id_1}")->execute();
        Yii::app()->db->createCommand("update tbl_access_role_master set emp_id = {$replace_id} where emp_id = {$find_id_2}")->execute();
        echo "Employee table updated...done";
    }

    public function actionajaxUpload()
    {
        // print_r($_POST);die;
        $filePath = $_FILES['file']['tmp_name'];
        $date = new DateTime();
        $unqid = $_POST['unqid'];
        $checkTaskData = $_POST['checkTaskData'];
        if(!empty($checkTaskData)){
            $unqid = $checkTaskData;
        }
        // echo json_encode(array($unqid,$checkTaskData));die;
        $row = 0;
        $est_hrs = 0;
        $arraydata = array();

        $arraydata = $this->getExcelData($filePath);
        if (!empty($arraydata)) {
            
            $taskArray = [];
            // $taskserial = 1;
            if ($checkTaskData == '') {
                Yii::app()->db->createCommand("delete from tbl_task_temp where unqid = '{$unqid}'")->execute();
            }
            
            foreach ($arraydata as $key => $value) {
                if($key == 0) continue;
                if($value[4] != '')
                {
                    $tasktemp = new TaskTitle();
                    $tasktemp->unqid = $unqid;
                    $tasktemp->task_title = $value[1];
                    $tasktemp->task_description = $value[2];
                    $tasktemp->task_level = $value[3]; 
                    $tasktemp->task_est_hrs = $value[4];
                    $tasktemp->approval_status = '2';
                    $tasktemp->task_status = 'NEW';
                    $taskArray[$tasktemp->task_level][] = $value[4];
                    $tasktemp->created_at = Yii::app()->session["login"]["user_id"];
                    $tasktemp->created_at = date("Y-m-d h:i:s");
                    $tasktemp->save(false);    
                }
                
            }
            $fhoursArr =[];
            foreach ($taskArray as $key => $value) {
                //$taskArray[$key] = array_sum($value);
                $hArr['unqid'] = $unqid;
                $levelar = Yii::app()->db->createCommand("select level_id from tbl_level_master where level_name LIKE '%{$key}%'")->queryRow();
                $hArr['level_id'] = $levelar['level_id'];
                $hArr['task_level'] = $key;
                $hArr['level_hours'] = array_sum($value);
                $fhoursArr[] = $hArr;
            
            }
            // echo "<pre>",print_r($fhoursArr),"</pre>"; die();
            $getHours = Yii::app()->db->createCommand("select unqid,task_level,SUM(task_est_hrs) as level_hours, (select level_id from tbl_level_master where level_name LIKE task_level) as level_id from tbl_task_temp where unqid LIKE '%{$unqid}%' group by task_level")->queryAll();
            


            $returnTable .= '<table class="table table-bordered" id="hoursTableU" style="width:80%;margin-left:10%">';
            $returnTable .= '<tr>';
            $returnTable .= '<td>Total Estimated Hours</td>';
            foreach ($fhoursArr as $row) {
                $est_hrs = $est_hrs + $row['level_hours'];          

                
                $returnTable .= '<td>'.$row['task_level'].'('.$row['level_hours'].')</td>';
                
            }
            $returnTable .= '<td><strong>Total '.$est_hrs.' Hours</strong></td>';
            $returnTable .= '</tr>';    
            $returnTable .= '</table>';
            $returnTable .= '<table class="table table-bordered" id="hoursTableU" style="width:99%"><tr><th colspan="5" style="text-align:center"><strong>Please check the tasks list, estimated hours for each task and total estimated hours calculated and then create the project.</strong></th></tr>';
            foreach ($arraydata as $key => $row) {
                # code...
                $returnTable .= '<tr>';
                foreach ($row as $value) {
                    
                    if($key == 0)
                    {
                        $returnTable .= '<th>'.$value.'</th>';

                    }else{
                        $returnTable .= '<td>'.$value.'</td>';
                    }
                }
                
                $returnTable .= '</tr>';

            }
            $returnData['table'] = $returnTable;
            $returnData['hrsArr'] = json_encode($getHours);
            $returnData['finalTHours'] = json_encode($fhoursArr);
            echo  json_encode($returnData);die;
            // echo "<pre>",print_r($arraydata),"</pre>"; die();
        } else {
            echo 0;die;
        }
        
    }


    public function getProjectId($pid) {

        $name = ProjectManagement::model()->findByPk($pid);

        
        $TaskId = Yii::app()->db->createCommand('Select max(spid) as maxId from tbl_sub_project ')->queryRow();
        $TaskId = $TaskId['maxId'] + 1;


        $projectformat = $name['project_id'] . sprintf("%03d", $TaskId);
        return $projectformat;
    }

    public function getExcelHours($model)
    {

        $returnTable = "<h4>Since for this project, the estimation of hours were added manually and not using the excel import method, there are no reports associated with this project.</h4>";
        
        $getHours = Yii::app()->db->createCommand("select unqid,task_level,SUM(task_est_hrs) as level_hours, (select level_id from tbl_level_master where level_name LIKE task_level) as level_id from tbl_task_temp where unqid LIKE '%{$model->unqid}%' and task_status= 'NEW' and approval_status != '0' group by task_level")->queryAll();
        $returnTable='';
        if(!empty($model->unqid))
        {
            if(!empty($getHours)){
                $returnTable = '<table class="table table-bordered" id="hoursTableU" style="width:80%;margin-left:10%">';
                $returnTable .= '<tr>';
                $returnTable .= '<td>Hours To Be Added(After Approval)</td>';
                foreach ($getHours as $row) {
                    $est_hrs = $est_hrs + $row['level_hours'];          

                    
                    $returnTable .= '<td>'.$row['task_level'].'('.$row['level_hours'].')</td>';
                    
                }
                $returnTable .= '<td><strong>Total '.$est_hrs.' Hours</strong></td>';
                $returnTable .= '</tr>';    
                $returnTable .= '</table>';
            }
            $returnTable .= '<table class="table table-bordered" id="hoursTableU" style="width:99%"><tr><th colspan="5" style="text-align:center"><strong>Please check the tasks list, estimated hours for each task and total estimated hours calculated and then create the project.</strong></th></tr>';

            $arraydata = Yii::app()->db->createCommand("select task_title,task_description,task_level,task_est_hrs,case when approval_status = '2' then 'Not Approved' when approval_status = '1' then 'Approved' when approval_status = '0' then 'Rejected' end as approval_status from tbl_task_temp where unqid LIKE '%{$model->unqid}%' order by id desc")->queryAll();
            
            // print_r($arraydata);die;
            $returnTable .= '<tr><th>Task Title</th><th>Task Description</th><th>Task Level</th><th>Task Hours</th><th>Task Status</th></tr>';
            foreach ($arraydata as $key => $row) {
                # code...
                $returnTable .= '<tr>';
                foreach ($row as $value) {
                    
                    $returnTable .= '<td>'.$value.'</td>';
                    
                }
                
                $returnTable .= '</tr>';

            }    
        }
        

        return $returnTable;
    }

    public function getExcelData($filePath)
    {
        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        /* Call the excel file and read it */
        $inputFileType = PHPExcel_IOFactory::identify($filePath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filePath);
        //$total_sheets = $objPHPExcel->getSheetCount();
        //$allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < 5; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
                $arraydata[$row - 1][$col] = $value;
            }
        }
        //echo "<pre>",print_r($arraydata),"</pre>"; die();
        spl_autoload_register(array('YiiBase', 'autoload'));

        return $arraydata;
    }

    // public function insertUpdateEmployee($type, $model)
    // {
    //     if($type == 1)
    //     {
    //         $model->created_date = date('Y-m-d h:i:s');
    //         $model->created_by = Yii::app()->session['login']['user_id'];
    //         Yii::app()->db1->createCommand()->insert('tbl_employee',$model);    
    //     }else if($type == 2) {
    //         $model->modified_date = date('Y-m-d h:i:s');
    //         $model->modified_by = Yii::app()->session['login']['user_id'];
    //         Yii::app()->db1->createCommand()->update('tbl_employee',$model,'spid ='.$model->spid);    
    //     }
    // }
    public function actionupdateHrs($id)
    {
            $tempFinalArr = [];
            //get unqid
            $project = Yii::app()->db->createCommand("select unqid,sub_project_name from tbl_sub_project where spid = {$id}")->queryRow();
            
            //get hrs from task temp
            $tasktemp = Yii::app()->db->createCommand("select SUM(task_est_hrs) as level_hours, (select level_id from tbl_level_master where level_name LIKE task_level) as level_id from tbl_task_temp where unqid LIKE '%{$project['unqid']}%' group by task_level")->queryAll();
            foreach ($tasktemp as $value) {
            
                // $tempFinalArr[$value['level_id']] = $value['level_hours'];
                $tempLevelId = $value['level_id'];
                $projectLA = Yii::app()->db->createCommand("select level_hours, level_id from tbl_project_level_allocation where project_id = {$id} and level_id = {$tempLevelId}")->queryAll();
                                echo '<pre>';
                
                if(!empty($projectLA)) {
                    if($value['level_hours'] != $projectLA[0]['level_hours']) {
                        
                        $modelPLA = ProjectLevelAllocation::model()->findByAttributes(array('project_id' => $id, 'level_id' => $value['level_id']));
                        $modelPLA->level_hours = $value['level_hours'];
                        $modelPLA->modified_by = Yii::app()->session["login"]["user_id"];
                        $modelPLA->updated_at = date("Y-m-d h:i:s");         
                        $modelPLA->save(false);
                        print_r($modelPLA);
                    }
                }else{
                    $modelPLA = new ProjectLevelAllocation;
                    $modelPLA->project_id = $id;
                    $modelPLA->level_id = $value['level_id'];
                    $modelPLA->level_hours = $value['level_hours'];
                    $modelPLA->created_by = Yii::app()->session["login"]["user_id"];
                    $modelPLA->created_at = date("Y-m-d h:i:s");
                    $modelPLA->save(false);
                    print_r($modelPLA);
                }

            }
            echo 'Nothing to updated    ';die;
    }

    public function actionupdateCap($id)
    {
        Yii::app()->db->createCommand("update tbl_employee set is_timesheet = '1' where emp_id = {$id}")->execute();
    }

    public function actionUpdatePlog($id,$hours)
    {
            #11111,11021,10909,9636
        Yii::app()->db->createCommand("update tbl_project_level_allocation set level_hours = {$hours} where id={$id}")->execute();
    }

    public function actionCheckEsec($id,$hours)
    {
        Yii::app()->db->createCommand("update tbl_day_comment set hours = {$hours} where id={$id}")->execute();   
    }

    public function actionDbquery()
    {
        Yii::app()->db->createCommand("alter table tbl_task_temp modify column approval_status enum('1','0','2') default 2")->execute();   
        Yii::app()->db->createCommand("update tbl_task_temp set approval_status = '2' where approval_status = '0'")->execute();   
        Yii::app()->db->createCommand("alter table tbl_task_temp add column task_status enum('NEW','OLD') default 'NEW'")->execute();
        Yii::app()->db->createCommand("update tbl_task_temp set task_status = 'NEW' where approval_status = '2'")->execute();
        Yii::app()->db->createCommand("update tbl_task_temp set task_status = 'OLD' where approval_status = '1'")->execute();
    }
}

