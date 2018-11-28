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
                'actions' => array('create', 'update', 'admin','fetchProjectId','updateLog','updateTask','uploadExcel','replaceId'),
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
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {

            $model->attributes = $_POST['SubProject'];
            $model->created_date = date('Y-m-d h:i:s');
            $model->project_id = $_POST['sub_project_id'];
            $model->created_by = Yii::app()->session['login']['user_id'];
            $model->approval_status = 2;
            $valid = $_POST['SubProject'];
            // print_r($model);die;
            if (empty($valid['pid']) || empty($valid['sub_project_name']) || empty($valid['sub_project_description']) || empty($valid['requester']) || empty($valid['status']) || empty($valid['priority'])) {
                Yii::app()->user->setFlash('error', 'Please fill all required fields.');
            } else {
                
                if ($model->save())
                {    
                    $insert_id = Yii::app()->db->getLastInsertID();
                    
                    
                    foreach ($_POST['group-a'] as $key => $val) {
                        if (!empty($val['level_hours'])) {
                            $modelPLA = new ProjectLevelAllocation;
                            $modelPLA->project_id = $insert_id;
                            $modelPLA->level_id = $val['level_id'];
                            $modelPLA->level_hours = $val['level_hours'];
                            $modelPLA->created_by = Yii::app()->session["login"]["user_id"];
                            $modelPLA->created_at = date("Y-m-d h:i:s");
                            $modelPLA->save(false);

                            //Update recorded as log in tbl_project_level_allocation_log for every update / insert
                            $modelPLALog = new ProjectLevelAllocationLog;
                            $modelPLALog->project_id = $insert_id;
                            $modelPLALog->level_id = $val['level_id'];
                            $modelPLALog->old_level_hours = 0;
                            $modelPLALog->new_level_hours = $val['level_hours'];
                            $modelPLALog->comments = 'Initial estimation';
                            $modelPLALog->rl_log_id = 0;
                            $modelPLALog->created_by = Yii::app()->session["login"]["user_id"];
                            $modelPLALog->created_at = date("Y-m-d h:i:s");
                            $modelPLALog->save(false);
                        }
                    }


                    $this->sendApprovalMail($insert_id);   
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
        $levels = ProjectLevelAllocation::model()->findAll("project_id=$id");
        $levels_log = Yii::app()->db->createCommand("SELECT CONCAT('CR',rl_log_id) as cr, project_id,GROUP_CONCAT(CONCAT(level_name, '(', new_level_hours, ')'), ' ') as log, comments FROM tbl_project_level_allocation_log pla join tbl_level_master lm on lm.level_id = pla.level_id where project_id = {$id} GROUP BY rl_log_id;")->queryAll();
        //$hours_label['estimated'] = Yii::app()->db->createCommand("select sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid where spid = {$id}")->queryRow();
        $estimatedArr = Yii::app()->db->createCommand("select level_name, level_hours from tbl_project_level_allocation pla inner join tbl_level_master lm on lm.level_id = pla.level_id  where pla.project_id = {$id}")->queryAll();

        $hours_label['estimated']['estimated_hrs'] = 0;
        foreach($estimatedArr as $e){
            $hours_label['estimated']['estimated_hrs'] += $e['level_hours'];
        }

        $hours_label['allocated'] = Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs from tbl_sub_project sp left join tbl_sub_task st on st.sub_project_id  = sp.spid where spid = {$id}")->queryRow();
        $hours_label['utilized'] = Yii::app()->db->createCommand("SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  FROM tbl_day_comment where spid={$id}")->queryRow();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SubProject'])) {
            
            $model->attributes = $_POST['SubProject'];
            $model->project_id = $_POST['sub_project_id'];
            $model->updated_date = date('Y-m-d h:i:s');
            $model->updated_by = Yii::app()->session['login']['user_id'];
            $model->approval_status = 2;

            if ($model->save())
                $checkLogCount = Yii::app()->db->createCommand('Select rl_log_id from tbl_project_level_allocation_log where project_id=' . $id . ' order by created_at desc')->queryRow();
            $rl_log_id = $checkLogCount['rl_log_id'] + 1;
            foreach ($_POST['group-a'] as $key => $val) {

                if (!empty($val['level_hours'])) {
                    $checkCount = Yii::app()->db->createCommand('Select * from tbl_project_level_allocation where project_id=' . $id . ' and level_id=' . $val['level_id'])->queryRow();
                    if (isset($checkCount['id'])) {
                        $modelPLA = ProjectLevelAllocation::model()->findByAttributes(array('id' => $checkCount['id']));
                        $modelPLA->modified_by = Yii::app()->session["login"]["user_id"];
                        $modelPLA->updated_at = date("Y-m-d h:i:s");
                    } else {
                        $modelPLA = new ProjectLevelAllocation;
                        $modelPLA->created_by = Yii::app()->session["login"]["user_id"];
                        $modelPLA->created_at = date("Y-m-d h:i:s");
                    }

                    $modelPLA->project_id = $id;
                    $modelPLA->level_id = $val['level_id'];
                    $modelPLA->level_hours = $val['level_hours'];
                    $modelPLA->save(false);

                    //Update recorded as log in tbl_project_level_allocation_log for every update / insert
                    $modelPLALog = new ProjectLevelAllocationLog;
                    $modelPLALog->project_id = $id;
                    $modelPLALog->level_id = $val['level_id'];
                    $modelPLALog->old_level_hours = $checkCount['level_hours'];
                    $modelPLALog->new_level_hours = $val['level_hours'];
                    $modelPLALog->comments = $_POST['level_comments'];
                    $modelPLALog->rl_log_id = empty($checkLogCount) ? 0 : $rl_log_id;
                    $modelPLALog->created_by = Yii::app()->session["login"]["user_id"];
                    $modelPLALog->created_at = date("Y-m-d h:i:s");
                    $modelPLALog->save(false);
                }
                //$importData[] = $modelST->getAttributes();
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
            'levels_log' => $levels_log
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
        $projectDetails = Yii::app()->db->createCommand("select sub_project_name,sub_project_description,pa.level_id ,lm.level_name,level_hours,concat(em.first_name,' ',em.last_name) as username from  tbl_project_level_allocation pa left join tbl_sub_project sp  on sp.spid = pa.project_id left join tbl_level_master lm on lm.level_id = pa.level_id left join tbl_employee em on em.emp_id = sp.created_by where spid = {$project_id}")->queryAll();
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
            if($baseurl == "http://localhost:8012/timesheet" || $baseurl == "https://staging.cnaap.net/timesheet")
            {

                $to[] = array("email" => "ridhisha.joshi@infinitylabs.in", "name" => "Ridhisha Joshi");
                $to[] = array("email" => "mudliyarp@hcl.com", "name" => "Prabhakar");                
                $to[] = array("email" => "Vinay.Nataraj@infinitylabs.in", "name" => "Vinay Nataraj");
                $to[] = array("email" => "sachin.potdar@infinitylabs.in", "name" => "Sachin Potdar");   
            }else{

                $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
                $bcc[] = array("email" => "Vinay.Nataraj@infinitylabs.in", "name" => "Vinay Nataraj");
                $bcc[] = array("email" => "sachin.potdar@infinitylabs.in", "name" => "Sachin Potdar");
                
            }

            $subject = "CNAAP New Project Approval";
            // echo $message;die;
            echo CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }

    public function actionupdateStatus($id)
    {
        if(Yii::app()->session['login']['user_id'] == '3616' || Yii::app()->session['login']['user_id'] == '6'){ 
            $status = $id[0];
            $project_id = substr($id, 1);
            $projectDetails = Yii::app()->db->createCommand("update tbl_sub_project set approval_status = {$status} where spid={$project_id} and approval_status = 2")->execute();        

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
        $find_id_1 = 6;
        $find_id_2 = 6999;
        $replace_id = 3616;
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
        // update tbl_sub_task set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2};
        Yii::app()->db->createCommand("update tbl_sub_task set updated_by = {$replace_id} where updated_by = {$find_id_1} or updated_by = {$find_id_2}")->execute();
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
}
