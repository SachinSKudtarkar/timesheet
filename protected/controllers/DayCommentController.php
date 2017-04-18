<?php

class DayCommentController extends Controller {

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
                    Yii::app()->user->setFlash('error', $errorMessage);
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
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'addcomment', 'GetProjName', 'getIrregularEmp', 'sendReminder', 'AdminAll', 'NotFilledStatus', 'fetchSubProject','fetchSubTask', 'StatusReport', 'getSubPStatus'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'testMail', 'home','test'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('NotFilledStatus', 'StatusReport', 'AdminAll', 'timeSheetNotFilled'),
                'expression' => 'CHelper::isAccess("STATUS", "full_access")',
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'application.components.ExportableGridBehavior',
                'filename' => 'dailyStatus.csv',
                'csvDelimiter' => ',', //i.e. Excel friendly csv delimiter
        ));
    }
    public function actionTest(){
      $pid = 9;
        echo "<pre>";
        $userId = 46;
        $query ="select st.sub_project_id,sp.sub_project_name,st.stask_id  from tbl_sub_task as st inner join tbl_pid_approval as pa on (st.pid_approval_id = pa.pid_id) inner join tbl_sub_project as sp on (sp.spid = st.sub_project_id)
where st.project_id = {$pid} and st.emp_id = {$userId} group by st.stask_id";

        $res = Yii::app()->db->createCommand($query)->queryAll();

        $hours = array();
    if (isset($res)) {
        foreach ($res as $ke => $val) {
             $query1 = "select sec_to_time(sum(time_to_sec(hours))) as hours,est_hrs 
                        from tbl_sub_task as st 
                        inner join tbl_day_comment as dc on (st.stask_id =dc.stask_id) 
                        where st.emp_id ={$userId} and dc.stask_id ={$val['stask_id']}
                        group by st.stask_id";
             $res1 = Yii::app()->db->createCommand($query1)->queryRow();
            echo $hr = explode(":",$res1['hours'])[0];
             if($hr < $res1['est_hrs']){
                unset($val);
             }

             $newData[] = $val;

         }
     }
     print_r($newData);
        
    }

    public function actiontestMail() {
        echo $from = $to = "aashay.t@infinitylabs.in";
        $to_name = $from_name = "aashay";
        $subject = "test mail";
        $message = "testing mail";
        $replyto = "aashay.thakur@ril.com";
        echo CommonUtility::sendmail($to, $to_name, $from, $from_name, $subject, $message, $cc = '', $cc_name = '', $replyto = '');
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

    public function actionhome() {
        //$this->redirect("http://portal.infinitylabs.in/app/index.php");
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new DayComment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DayComment'])) {
            $model->attributes = $_POST['DayComment'];
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

        if (isset($_POST['DayComment'])) {
            $model->attributes = $_POST['DayComment'];
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
        if (isset(Yii::app()->session['login']['user_id'])) {
            $current_user_id = Yii::app()->session['login']['user_id'];
            $projectData = $combinearray = array();

            $dataProvider = new CActiveDataProvider('DayComment');
            $model = new DayComment();
            if(isset($_REQUEST['selecting_date'])){
            $model->from = $_REQUEST['selecting_date'];
            $model->to = date('Y-m-d', strtotime("+6 day", strtotime($_REQUEST['selecting_date'])));
            }else{
                
            $model->from = '';
            $model->to = '';
            }
            $dataProvider1 = $model->search(false);
           //  if(Yii::app()->session['login']['user_id'] == 46) {
           // echo "<pre>";
          //  print_r($dataProvider1);exit;
          //  }
            $arrData = $arrSubmitted = array();
            $is_submitted = 0;
            if (count($dataProvider1->getData())) {
                $dayNo = 0;

                foreach ($dataProvider1->getData() as $k => $v) {

                    $day = date('Y_m_d', strtotime($v->day));
                    $arrData[$day][$dayNo]['id'] = $v->id;
                    $arrData[$day][$dayNo]['pid'] = $v->pid;
                    $arrData[$day][$dayNo]['spid']['result'] = $this->getSubProjectByProjectId2($v->pid);
                    $arrData[$day][$dayNo]['spid']['selected'] = $v->spid;
                    $arrData[$day][$dayNo]['stask_id']['selected'] =$v->stask_id;
                    $arrData[$day][$dayNo]['emp_id'] = $v->emp_id;
                    $arrData[$day][$dayNo]['day'] = $v->day;
                    $arrData[$day][$dayNo]['comment'] = $v->comment;
                    list($hrs, $mnts) = explode(':', $v->hours);
                    $arrData[$day][$dayNo]['hrs'] = $hrs;
                    $arrData[$day][$dayNo]['mnts'] = $mnts;
                    $arrData[$day][$dayNo]['is_submitted'] = $v->is_submitted;

                    

                   /* if ($arrSubmitted[date('Y-m-d', strtotime("monday this week", strtotime($v->day)))] == 0)
                    */    $arrSubmitted[date('Y-m-d', strtotime("monday this week", strtotime($v->day)))] = $v->is_submitted;

                    $dayNo++;
                }
                  
            }

              if(Yii::app()->session['login']['user_id'] == 2065) {
           //echo "<pre>";
          // print_r($arrData);exit;
           }

            $query = "SELECT pid FROM tbl_resource_allocation_project_work WHERE allocated_resource like '%," . $current_user_id . "'
                        OR allocated_resource like '%," . $current_user_id . ",%' OR allocated_resource like '" . $current_user_id . ",%' OR allocated_resource = " . $current_user_id;
            $result = Yii::app()->db->createCommand($query)->queryAll();
            $combinearray = array_column($result, 'pid');
            $combinearray[] = "109";  //109 for common task
            $pidString = implode("','", $combinearray);
            $projectData = Yii::app()->db->createCommand("SELECT pid,project_name,project_description FROM tbl_project_management WHERE pid IN('" . $pidString . "')")->queryAll();
            $this->render('index', array('dataProvider' => $dataProvider, 'allProjects' => $projectData, 'arrData' => $arrData, 'arrSubmitted' => $arrSubmitted));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new DayComment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DayComment'])) {
            $_GET['DayComment'] = array_map('trim', $_GET['DayComment']);
            $model->attributes = $_GET['DayComment'];
        }
        if ($this->isExportRequest()) {
            $inpCount = 0;
            $dataProvider1 = $model->search(false);
            $result = $dataProvider1->getData();
            foreach ($result as $key => $value) {
                $inpCount++;
                $finalArr['rows'][] = array(
                    $inpCount,
                    $value['project_name'],
                    $value['sub_project_name'],
                    $this->getEname($value->emp_id),
                    $value['day'],
                    $value['comment'],
                    $value['hours'],
                );
            }
            $export_column_name = array('Sr. No.', 'Project name', 'Task', 'Employee name', 'date', 'comment', 'hours');
            $filename = "daily_comment " . date('d_m_Y') . "_" . date('H') . "_hr";
            CommonUtility::downloadDataInCSV($export_column_name, $finalArr['rows'], $filename);
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminAll() {
        $model = new DayComment('searchAll');
        $model->unsetAttributes();  // clear any default values
        $_GET['DayComment'] = array_map('trim', $_GET['DayComment']);
        $model->attributes = $_GET['DayComment'];
        if (isset($_GET)) {
            $model->from = $_GET['from'];
            $model->to = $_GET['to'];
            $model->emp_id = $_GET['employee'];
            $model->pid = $_GET['ProjectName'];
            $model->sub_project_name = $_GET['Task_Name'];
        }
        if ($this->isExportRequest()) {
            $inpCount = 0;
            $dataProvider1 = $model->searchAll(false);
            $result = $dataProvider1->getData();
            foreach ($result as $key => $value) {
                $inpCount++;
                $finalArr['rows'][] = array(
                    $inpCount,
                    $value['project_name'],
                    $value['sub_project_name'],
                    $value['name'],
                    $value['day'],
                    $value['comment'],
                    $value['hours'],
                );
            }

            $export_column_name = array('Sr. No.', 'Project name', 'Task', 'Employee name', 'date', 'comment', 'hours');
            $filename = "daily_comment " . date('d_m_Y') . "_" . date('H') . "_hr";
            CommonUtility::downloadDataInCSV($export_column_name, $finalArr['rows'], $filename);
        }
        $this->render('allcomment', array(
            'model' => $model,
        ));
    }

    public function actionTimeSheetNotFilled() {
        $model = new DayComment('searchStatusIncomplete');
        $model->unsetAttributes();  // clear any default values
        $_GET['DayComment'] = array_map('trim', $_GET['DayComment']);
        $model->attributes = $_GET['DayComment'];
        if (isset($_GET)) {
            $model->from = $_GET['from'];
            $model->to = $_GET['to'];
            $model->pid = $_GET['ProjectName'];
            $date1 = date_create($_GET['from']);
            $date2 = date_create($_GET['to']);
            $diff = date_diff($date1, $date2);
            $model->range_days = ((int) $diff->format("%a")) + 1;
        }
        if ($this->isExportRequest()) {
            $inpCount = 0;
            $dataProvider1 = $model->searchAll(false);
            $result = $dataProvider1->getData();
            foreach ($result as $key => $value) {
                $inpCount++;
                $finalArr['rows'][] = array(
                    $inpCount,
                    $value['project_name'],
                    $value['sub_project_name'],
                    $value['name'],
                    $value['day'],
                    $value['comment'],
                    $value['hours'],
                );
            }

            $export_column_name = array('Sr. No.', 'Project name', 'Task', 'Employee name', 'date', 'comment', 'hours');
            $filename = "daily_comment " . date('d_m_Y') . "_" . date('H') . "_hr";
            CommonUtility::downloadDataInCSV($export_column_name, $finalArr['rows'], $filename);
        }
        $this->render('incompletecomment', array(
            'model' => $model,
        ));
    }

    public function actionStatusReport() {
        $from = $to = $employee = "";
        $day_dif = 30;
        if (!empty($_REQUEST['from'])) {
            $from = $_REQUEST['from'];
        }
        if (!empty($_REQUEST['to'])) {
            $to = $_REQUEST['to'];
            $date1 = date_create($from);
            $date2 = date_create($to);
            $diff = date_diff($date1, $date2);
            $day_dif = $diff->d;
        }
        if (!empty($_REQUEST['employee'])) {
            $emp_id = $_REQUEST['employee'];
        }
        Yii::import('application.extensions.arrayDataProvider.*');
        $new_date = "";
        $date_raw = date('Y-m-d');
        $final_Arr = $final_ArrS = array();
        if (empty($emp_id)) {
            $query = "Select emp_id,first_name,last_name from tbl_employee where is_active ='1'";
        } else {
            $query = "Select emp_id,first_name,last_name from tbl_employee where is_active ='1' AND emp_id='{$emp_id}'";
        }
        $employee = Yii::app()->db->createCommand($query)->queryAll();

        for ($i = 1; $i < $day_dif; $i++) {
            $new_date = date('Y-m-d', strtotime('-' . $i . 'day', strtotime($date_raw)));
            $day = date('D', strtotime($new_date));
            if ($day == "Sun")
                continue;

            foreach ($employee as $key => $emp_value) {
                $query = "Select emp_id,pid,comment,day,hours,created_by,created_at from tbl_day_comment where day like '{$new_date}%' AND emp_id='{$emp_value['emp_id']}'";
                $day_comment = Yii::app()->db->createCommand($query)->queryRow();
                if (empty($day_comment)) {
                    $final_Arr[$emp_value['first_name'] . "_" . $emp_value['last_name']]['rows'][] = array(
                        $new_date
                    );
                    $arrayData[] = array('name' => $emp_value['first_name'] . "_" . $emp_value['last_name'], 'date' => $new_date);
                }
            }
        }
        $dataProvider = new CArrayDataProvider($arrayData, array(
            'pagination' => array(
                'pageSize' => 15,
                'pageVar' => 'page'
            ),
        ));
		
		 if($this->isExportRequest())
                        {
                       
            $export_column_name = array( 'name',  
            'date');
            
            $this->exportCSV($arrayData, $export_column_name);
            }
        $this->render('status', array('dataProvider' => $dataProvider, 'data' => $_GET));
    }

    public function actionNotFilledStatus() {
        $new_date = "";
        $date_raw = date('Y-m-d');
        $final_Arr = $final_ArrS = array();
        $query = "Select emp_id,first_name,last_name from tbl_employee where is_active ='1'";
        $employee = Yii::app()->db->createCommand($query)->queryAll();

        $projectList = Yii::app()->db->createCommand("Select pid,project_name from tbl_project_management")->queryAll();
        $new_projectList = array();
        foreach ($projectList as $key => $value) {
            $new_projectList[$value['pid']] = $value;
        }
        for ($i = 30; $i > 0; $i--) {
            $new_date = date('Y-m-d', strtotime('-' . $i . 'day', strtotime($date_raw)));

            $day = date('D', strtotime($new_date));
            if ($day == "Sun")
                continue;

            foreach ($employee as $key => $emp_value) {
                $query = "Select emp_id,pid,comment,day,hours,created_by,created_at from tbl_day_comment where day like '{$new_date}%' AND emp_id='{$emp_value['emp_id']}'";
                $day_comment = Yii::app()->db->createCommand($query)->queryRow();
                if (empty($day_comment)) {
//                    $final_Arr[$emp_value['first_name'] . "_" . $emp_value['last_name']]['header'] = array('date');
                    $final_Arr[$emp_value['first_name'] . "_" . $emp_value['last_name']]['rows'][] = array(
                        $new_date
                    );
                } else {
//                    $final_ArrS[$emp_value['first_name'] . "_" . $emp_value['last_name']]['header'] = array('date', 'project_name', 'hours', 'comment');
                    $final_ArrS[$emp_value['first_name'] . "_" . $emp_value['last_name']]['rows'][] = array(
                        $new_date,
                        $new_projectList[$day_comment['pid']]['project_name'],
                        $day_comment['hours'],
                        trim($day_comment['comment'], $day_comment['pid'] . "==")
                    );
                }
            }
        }

        if (isset($_REQUEST['comment'])) {
            $filenameS = "status_submitted_" . $date_raw;
            CommonUtility::generateExcelMultipleTab($final_ArrS, $filenameS);
        } else {
            $filename = "status_Not_submitted_" . $date_raw;
            CommonUtility::generateExcelMultipleTab($final_Arr, $filename);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DayComment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = DayComment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DayComment $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'day-comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAddcomment() {
//        $rd_day = isset($_POST['Date']) ? $_POST['Date'] : '';
        $status_date = isset($_POST['Date']) ? $_POST['Date'] : '';
        $dayComment = isset($_POST['dayComment']) ? $_POST['dayComment'] : 0;
        $totalPrjcts = isset($_POST['totalPrjcts']) ? $_POST['totalPrjcts'] : 0;
        $projectsName = isset($_POST['ProjectName']) ? $_POST['ProjectName'] : '';
        $SubProjectName = isset($_POST['SubProjectName']) ? $_POST['SubProjectName'] : '';
        $comments = isset($_POST['procomment']) ? $_POST['procomment'] : '';
        $pidsid = isset($_POST['pidsid']) ? $_POST['pidsid'] : 0;
        $arrWrkhrs = isset($_POST['hrs']) ? $_POST['hrs'] : '';
        $arrWrkmnts = isset($_POST['mnts']) ? $_POST['mnts'] : '';
        $is_submitted = isset($_POST['yt1']) ? 1 : 0;
        $selected_date = isset($_REQUEST['selected_date']) ? $_REQUEST['selected_date'] : '';
        $importData = array();
        $pidsarray = explode(',', $pidsid);
        $pidsarray = array_unique($pidsarray);
        if ($rd_day == '') {
            $rd_day = date('d/m/Y');
        }

        $projectsName = array_filter($projectsName);
        $all_data = array();

        $totalworkedHrs = 0;
        $tblString = "<table width='100%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  ><thead  bgcolor='#CD853F' align='center' style='color:white; '>";
        $tblString .= "<th bgcolor='#CD853F' align='center' style='color:white; '>Date </th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Project ID</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Project Name</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Sub Project Name</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Requestor</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Project Manager</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Total Efforts(Hrs)</th>"
                . "<th bgcolor='#CD853F' align='center' style='color:white; '>Comments</th>";
        $tblString .= "</thead><tbody style='font-size:14px;'>";
        if (!empty($projectsName)) {
            foreach ($projectsName as $key => $value) {
                $all_data[] = array(
                    'day' => date('Y-m-d H:i:s', strtotime($status_date[$key])),
                    'emp_id' => Yii::app()->session['login']['user_id'],
                    'comment' => $comments[$key],
                    'pid' => $value,
                    'spid' => $SubProjectName[$key],
                    'hours' => $arrWrkhrs[$key] . ':' . $arrWrkmnts[$key],
                    'is_submitted' => $is_submitted,
                    'created_by' => Yii::app()->session['login']['user_id'],
                    'created_at' => date('Y-m-d H:i:s')
                );
                $sub_pro_name = SubProject::model()->find(array('select' => "spid,sub_project_name", "condition" => "spid= '" . $SubProjectName[$key] . "'"));
                $projName = $this->GetProjName($value);
                $projRequestor = $this->GetRequestorName($value);
                $projManager = $this->GetProjManager($value);
                $projManagerEmailId = $this->GetProjManagerEmail($value);

                $stringprojecthoursarray[] = "Project ID = " . $value . "(" . $projName . ") Worked Hours " . $arrWrkhrs[$key];
                $stringComments[] = "<br>Comments For <b>" . $projName . "</b> is " . $comments[$key];
                $tblString .= "<tr bgcolor='#FFDAB9' style='border-bottom: 1px solid #ccc;'><td>" . $status_date[$key] . "</td><td>" . $value . "</td><td>" . $projName . "</td><td>" . $sub_pro_name->sub_project_name . "</td><td>" . $projRequestor . "</td><td>" . $projManager . "</td><td>" . $arrWrkhrs[$key] . "</td><td style='width:50%'>" . $comments[$key] . "</td>";
                $tblString .= "</tr>";
            }
            $totalworkedHrs = array_sum($arrWrkhrs);
            $tblString .= "<tr bgcolor='#F4A460'><td></td><td><b>TOTAL</b></td><td></td><td></td><td></td><td><b>" . $totalworkedHrs . "</b></td><td></td></tr>";
            $tblString .= "</tbody></table>";

            $model = new DayComment;
            $model->emp_id = Yii::app()->session['login']['user_id'];

            $this->deleteMultipleRecords($all_data);
            $this->multipleInsert($model, $all_data);

            $commentor_name = $model->getUserName($model);
            $commentor_email = $model->getCommentorEmail($model);

            $message = "<b>Dear Manager,</b> <br><br>";
            $message .= "Work Status Between <b> " . $rd_day . "</b> Updated by <b>" . $commentor_name . "</b> is as below: <br><br>";
            $message .= $tblString;
            $message .= "<br><br>";
            $message .= "<br><br>";

            $message .= "Regards,";
            $message .= "<br>Infinity Team";

            if ($is_submitted == 1) {
                $mail_sent = $this->sendEmail($message, $commentor_name, $commentor_email, $rd_day, $projManagerEmailId, $projManager);
            }
            Yii::app()->user->setFlash('success', "Comment added successfully.");
            $this->redirect(array('daycomment/index/selecting_date/' . $selected_date));
        }
        Yii::app()->user->setFlash('error', "Please select project.");
        $this->redirect(array('daycomment/index/selecting_date/' . $selected_date));
    }

    public function sendEmail($message, $commentor_name, $commentor_email, $rd_day, $projManagerEmailId, $projManager) {

        $from = "support@infinitylabs.in";
        $from_name = "Infinitylabs Team";
        $to = array();
        $cc = array();
        $bcc = array();
        //$to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
        //    $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => $projManagerEmailId, "name" => $projManager);
        $cc[] = array("email" => "jinal@infinitylabs.in", "name" => "Jinal Thakkar");
        $cc[] = array("email" => "abhijit@infinitylabs.in", "name" => "Abhijit Aphale");
        $cc[] = array("email" => $commentor_email, "name" => $commentor_name);

        if (($commentor_name != 'Lalitha Shivkumar') && ($commentor_name != 'Vidhyadhar Kulkarni')) {
            $cc[] = array("email" => "vidyadhar@infinitylabs.in", "name" => "Vidhyadhar Kulkarni");
            $cc[] = array("email" => "lalitha.shivkumar@infinitylabs.in", "name" => "Lalitha Shivkumar");
        }
        $subject = "Daily Status By " . $commentor_name . " (" . $rd_day . ")";
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }

    public function GetProjName($pid = null) {
        $query = "SELECT project_name FROM tbl_project_management WHERE pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['project_name'];
    }

    public function GetRequestorName($pid = null) {
        $query = "SELECT requester FROM tbl_project_management WHERE pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['requester'];
    }

    public function GetProjManager($pid = null) {
        $query = "SELECT CONCAT(e.first_name,' ',e.last_name) as managerName FROM tbl_employee e INNER JOIN tbl_project_management pm ON (e.emp_id = pm.created_by) WHERE pm.pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['managerName'];
    }

    public function GetProjManagerEmail($pid = null) {
        $query = "SELECT e.email as ProjectManagerEmail FROM tbl_employee e INNER JOIN tbl_project_management pm ON (e.emp_id = pm.created_by) WHERE pm.pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['ProjectManagerEmail'];
    }

    public function actiongetIrregularEmp() {
        $HOWMANYDAYS = 3;
        $collectionarray = array();
        $today = date('Y-m-d');
        $days_ago = date('Y-m-d', strtotime('-' . $HOWMANYDAYS . ' days', strtotime($today)));
        $daysarray = array();
        for ($i = $HOWMANYDAYS; $i > 0; $i--) {

            $ddate = date('Y-m-d', strtotime('-' . $i . ' days', strtotime($today)));

            $daysarray[] = $ddate;
            $collectionarray[$ddate][] = "";
        }


        $CONDITION = "day BETWEEN '" . $days_ago . "' AND '" . $today . "'";
        $all_reggular_emps = Yii::app()->db->createCommand()
                ->select("emp_id,DATE(day) as date")
                ->from('tbl_day_comment')
                ->where($CONDITION)
                ->group('emp_id,day')
                ->queryAll();

        $data_array = array();

        foreach ($daysarray as $day) {
            foreach ($all_reggular_emps as $eacharray) {
                if ($day == $eacharray['date']) {
                    $collectionarray[$day][] = $eacharray['emp_id'];
                }
            }
        }
        $CONDITION = "user_type = 2 AND is_active = 1";
        $all_emps = Yii::app()->db->createCommand()
                ->select("tbl_emp_id")
                ->from('tbl_infi_employee')
                ->where($CONDITION)
                ->order('first_name')
                ->queryAll();


        $tblString = "<table width='70%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  ><thead  bgcolor='#CD853F' align='center' style='color:white; '>";
        $tblString .= "<th bgcolor='#CD853F' align='center' style='color:white; ' width='20%'>Employee</th>";
        foreach ($daysarray as $day) {
            $theday = date('l', strtotime($day));
            $theday = "<br>(" . $theday . ")";
            $day .= $theday;
            $tblString .= "<th bgcolor='#CD853F' align='center' style='color:white; '>$day</th>";
        }
        $tblString .= "</thead><tbody style='font-size:14px;'>";



        foreach ($all_emps as $eachemployee) {
            $tblempid = $eachemployee['tbl_emp_id'];
            $EmployeeName = $this->getEname($tblempid);
            $tblString .= "<tr bgcolor='#FFDAB9' style='border-bottom: 1px solid #ccc;'>";
            $tblString .= "<td><b style='color:#580000 ;'>" . $EmployeeName . "</b></td>";
            foreach ($collectionarray as $key => $val) {
                if (count($collectionarray[$key]) > 1 && in_array($tblempid, $val)) {
                    $tblString .= "<td><b style='color:green;'>Submitted</b></td>";
                } else {
                    $tblString .= "<td>-</td>";
                }
            }

            $tblString .= "</tr>";
        }

        $tblString .= "<tr bgcolor='#F4A460'><td></td><td colspan = '$HOWMANYDAYS'></td></tr>";
        $tblString .= "</tbody></table>";

        $message = "<b>Dear Manager,</b> <br><br>";
        $message .= "Below is the record of employees about daily status sumission in last $HOWMANYDAYS days. <br><br>";

        $message .= $tblString;
        $message .= "<br><br>";
        $message .= "<br><br>";

        $message .= "Regards,";
        $message .= "<br>Infinity Team";
        $mail_sent = $this->sendStatusEmail($message, $HOWMANYDAYS);

        echo "Done";
    }

    public function getEname($empid) {
        $emp_model = Employee::model()->findByPk($empid);
        return $empName = $emp_model->first_name . " " . $emp_model->last_name;
    }

    public function sendStatusEmail($message, $HOWMANYDAYS) {
        $from = "support@infinitylabs.in";
        $from_name = "Infinitylabs Team";
        $to = array();
        $cc = array();
        $bcc = array();
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
        //  $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => "rjilautoteam@external.cisco.com", "name" => "Automation Team");


        $subject = "Last $HOWMANYDAYS Days Status ";
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }

    public function actionsendReminder() {
        $today = date('Y-m-d');
        $theday = "2016-01-18";
        $today = $theday;
        $CONDITION = "day = '" . $today . "'";
        $todaysStatus = Yii::app()->db->createCommand()
                ->select("emp_id")
                ->from('tbl_day_comment')
                ->where($CONDITION)
                ->group('emp_id')
                ->queryAll();
        $todaysStatus = array_column($todaysStatus, 'emp_id');

        $stringemps = implode("','", $todaysStatus);

        $CONDITIONFORMISSEDEMPLOYEES = "tbl_emp_id NOT IN('" . $stringemps . "') AND user_type = 2 AND is_active = 1";
        $missedemployees = Yii::app()->db->createCommand()
                ->select("tbl_emp_id,email")
                ->from('tbl_infi_employee')
                ->where($CONDITIONFORMISSEDEMPLOYEES)
                ->queryAll();

        $tosendmail = array();
        foreach ($missedemployees as $indimiss) {
            $tosendmail[$indimiss['tbl_emp_id']] = $indimiss['email'];
        }

//        echo "<pre>";
//        print_r($tosendmail);
//        die("herere");

        foreach ($tosendmail as $key => $val) {
            $message = "";
            $missername = $this->getEname($key);
            $message .= "<br>";
            $message .= "<b>Dear $missername,</b> <br><br>";
            $message .= "You missed to fill daily status for $today. <br><br>";
            $message .= "<br><br>";
            $message .= "Regards,";
            $message .= "<br>RJIL Auto Team";
            $mail_sent = $this->sendReminderEmail($message, $val, $missername);
        }

        echo "Done";
    }

    public function sendReminderEmail($message, $tomisser, $missername) {

        $from = "support@infinitylabs.in";
        $from_name = "Infinitylabs Team";
        $to = array();
        $cc = array();
        $bcc = array();
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
        // $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => $tomisser, "name" => $missername);
        //  $cc[] = array("email" => "Aashay.t@infinitylabs.in", "name" => "Aashay");


        $subject = "Daily Status Reminder - $missername";
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }

    public function actionfetchSubProject() {
        $newData = $da = $nn = $result = array();
        $pid = $_POST['pid'];
        //$pid = 9;
        //echo "<pre>";
        $userId = Yii::app()->session['login']['user_id'];
        
        $query ="select st.sub_project_id,sp.sub_project_name,st.stask_id  from tbl_sub_task as st inner join tbl_pid_approval as pa on (st.pid_approval_id = pa.pid_id) inner join tbl_sub_project as sp on (sp.spid = st.sub_project_id)
where st.project_id = {$pid} and st.emp_id = {$userId} group by st.stask_id";

        $res = Yii::app()->db->createCommand($query)->queryAll();

		$hours = array();
    if (isset($res)) {
		foreach ($res as $ke => $val) {
             $query1 = "select sec_to_time(sum(time_to_sec(hours))) as hours,est_hrs 
                        from tbl_sub_task as st 
                        inner join tbl_day_comment as dc on (st.stask_id =dc.stask_id) 
                        where st.emp_id ={$userId} and dc.stask_id ={$val['stask_id']}
                        group by st.stask_id";
             $res1 = Yii::app()->db->createCommand($query1)->queryRow();
             $hr = explode(":",$res1['hours'])[0];
             if(!empty($res1)){
                if($hr > $res1['est_hrs']){
                unset($val);
             } 
             }
            
             $newData[] = $val;

         }
     }



		foreach (array_filter($newData) as $key => $val) {
            
			  $nn[] = $val;
            
			$hours[$val['sub_project_id']] = $val['hours'];
        }
        $list = CHtml::listData($nn, 'sub_project_id', 'sub_project_name');
        $data['result'] = $list;
		//$data['workhours'] = $hours;
        $data['status'] = 'SUCCESS';
        echo json_encode($data);
        die();
    }

	 public function actionfetchSubTask() {
        $newData = $da = $nn = $result = $data = array();
        $spid = $_POST['pid'];
          
        $userId = Yii::app()->session['login']['user_id'];
        //tbl_resource_allocation_project_work ,tbl_task_allocation stask_id, sub_project_id, est_hrs, approved=1 
        //$query = "select st.stask_id,st.sub_project_id, st.est_hrs from tbl_sub_task as st inner join tbl_pid_approval  as pa on(st.pid_approval_id=pa.pid_id) where pa.approved=1 AND pa.approved =2 AND st.sub_project_id={$spid}";// AND st.emp_id = {$userId}";
		$query ="select * from tbl_sub_task where sub_project_id={$spid} and emp_id ={$userId};";
		$res = Yii::app()->db->createCommand($query)->queryAll();

		$hours = array();
      
        if(!empty($res)){
          foreach ($res as $ke => $val) {
           $res2 = Yii::app()->db->createCommand("select hours from tbl_day_comment where stask_id ={$val['stask_id']} and emp_id = {$userId}")->queryRow();
            if (!empty($res2['stask_id'])) {
                        $query = "SELECT sum(sb.est_hrs) as hours ,sb.est_hrs,sb.stask_id,sb.sub_task_name
                                    from tbl_day_comment as da right join tbl_sub_task as sb on (da.s_task_id=sb.stask_id ) right join
                                    tbl_project_management as pm on (da.pid=pm.pid) right join tbl_employee em on (da.emp_id=em.emp_id)
                            WHERE  da.emp_id ={$userId} and da.s_task_id = {$val['stask_id']} 
                            group by da.s_task_id order by em.emp_id"; // and sb.spid = ra.spid
                $res1 = Yii::app()->db->createCommand($query)->queryRow();
                $newData[] = $res1;
                
            } elseif (empty($res2['spid'])) {
                $nn[] = $val;
                $hours[$val['spid']] = 0;
                
            }
        }

        foreach ($newData as $key => $val) {
            
            if (empty($val)) {
                continue;
            }
            if (empty($val['hours'])) {
                continue;
            }
             if($val['hours'] <= $val['est_hrs']){

            $nn[] = $val;
            }
           // $nn[] = $val;
            $hours[$val['spid']] = $val['hours'];
        }
        $list = CHtml::listData($nn, 's_task_id', 'sub_task_name');
        }else{
            $list = CHtml::listData($res, 's_task_id', 'sub_task_name');
        }
		
        
        $data['result'] = $list;
		//$data['workhours'] = $hours;
        $data['status'] = 'SUCCESS';
        echo json_encode($data);
        die();
    }
	
    public function actionGetSubPStatus() {
        $spid = $_POST['spid'];
        $userId = Yii::app()->session['login']['user_id'];

        //tbl_resource_allocation_project_work ,tbl_task_allocation  if(sb.total_hr_estimation_hour <= sum(hours), sum(hours) ,'') as hours,
        $query = "SELECT if(sb.total_hr_estimation_hour >= sum(hours), sum(hours) ,'') as hours,sum(hours),sb.total_hr_estimation_hour
from tbl_employee em,tbl_day_comment as da,tbl_sub_project as sb,tbl_project_management as pm 
WHERE em.emp_id=da.emp_id AND da.spid=sb.spid AND da.pid=pm.pid and da.emp_id ={$userId} and da.spid = {$spid}
group by da.spid order by em.emp_id"; // and sb.spid = ra.spid
        $res = Yii::app()->db->createCommand($query)->queryRow();

        //$list = CHtml::listData($res, 'spid', 'sub_project_name');
        //echo $res[0]['hours'];
        

        //$data['result'] = $list;
        if (empty($res['hours']))
            $data['status'] = 'UNSUCCESS';
        echo json_encode($data);
        die();
    }

    private function getSubProjectByProjectId($pid) {
        $result = array();
        if ($pid) {
            $objSP = SubProject::model()->findAll(array('select' => "spid,sub_project_name", 'order' => 'sub_project_name', 'condition' => 'pid=' . $pid . ' AND is_deleted =0 '));
            foreach ($objSP as $k => $v) {
                $result[$v->spid] = $v->sub_project_name;
            }
        }
        return $result;
    }

    private function getSubProjectByProjectId2($pid) {
        $newData = $da = $nn = $result = array();
        if ($pid) {

            //echo "<pre>";
            $userId = Yii::app()->session['login']['user_id'];
            //tbl_resource_allocation_project_work ,tbl_task_allocation
            $query = "select sb.spid,sb.sub_project_name from tbl_sub_project as sb inner join tbl_task_allocation  as ra on(sb.pid=ra.pid) where ra.pid={$pid} AND sb.is_deleted =0
		and FIND_IN_SET($userId,ra.allocated_resource) and sb.spid = ra.spid "; // and sb.spid = ra.spid


            $res = Yii::app()->db->createCommand($query)->queryAll();


//             foreach ($res as $ke => $val) {
//                 $res2 = Yii::app()->db->createCommand("select spid from tbl_day_comment where spid ={$val['spid']} and emp_id = {$userId}")->queryRow();
//                 if (!empty($res2['spid'])) {
//                     $query = "SELECT sum(hours) as hours ,sb.total_hr_estimation_hour,sb.spid,sb.sub_project_name
// from tbl_day_comment as da right join tbl_sub_project as sb on (da.spid=sb.spid ) right join
// tbl_project_management as pm on (da.pid=pm.pid) right join tbl_employee em on (da.emp_id=em.emp_id)
// WHERE  da.emp_id ={$userId} and da.spid = {$val['spid']} 
//                group by da.spid order by em.emp_id"; // and sb.spid = ra.spid
//                     $res1 = Yii::app()->db->createCommand($query)->queryRow();
//                     $newData[] = $res1;
//                 } elseif (empty($res2['spid'])) {
//                     $nn[] = $val;
//                 }
//             }
//             foreach ($newData as $key => $val) {
//                 if (empty($val)) {
//                     continue;
//                 }
//                 if (empty($val['hours'])) {
//                     continue;
//                 }
//                 $nn[] = $val;
//             }
            foreach ($res as $ke => $val) {
            $res2 = Yii::app()->db->createCommand("select spid from tbl_day_comment where spid ={$val['spid']} and emp_id = {$userId}")->queryRow();

        
            if (!empty($res2['spid'])) {

         

                $query = "SELECT sum(hours) as hours ,sb.total_hr_estimation_hour,sb.spid,sb.sub_project_name
from tbl_day_comment as da right join tbl_sub_project as sb on (da.spid=sb.spid ) right join
tbl_project_management as pm on (da.pid=pm.pid) right join tbl_employee em on (da.emp_id=em.emp_id)
WHERE  da.emp_id ={$userId} and da.spid = {$val['spid']} 
               group by da.spid order by em.emp_id"; // and sb.spid = ra.spid


//                $query ="SELECT sum(hours) as hours ,ta.est_hrs,sb.spid,sb.sub_project_name
// from tbl_day_comment as da right join tbl_sub_project as sb on (da.spid=sb.spid ) right join
// tbl_project_management as pm on (da.pid=pm.pid) right join tbl_employee em on (da.emp_id=em.emp_id) left join tbl_sub_task as ta on(da.pid =ta.project_id)
// WHERE  da.emp_id ={$userId} and da.spid = {$val['spid']} 
//                group by da.spid order by em.emp_id";
                $res1 = Yii::app()->db->createCommand($query)->queryRow();
                $newData[] = $res1;
                
            } elseif (empty($res2['spid'])) {
                $nn[] = $val;
                $hours[$val['spid']] = 0;
                
            }
        }
        foreach ($newData as $key => $val) {
            
            if (empty($val)) {
                continue;
            }
            if (empty($val['hours'])) {
                continue;
            }
            if($val['hours'] <= $val['total_hr_estimation_hour']){

            $nn[] = $val;
            }
            $hours[$val['spid']] = $val['hours'];
        }

            foreach ($nn as $k => $v) {
                $result[$v['spid']] = $v['sub_project_name'];
            }
        }
        return $result;
    }

    public function deleteMultipleRecords($all_data) {

        if (count($all_data)) {

            foreach ($all_data as $k => $v) {
                $command = Yii::app()->db->createCommand();
                $condition = 'day = :day AND emp_id=:emp_id';
                $params = array(':day' => $v['day'], ':emp_id' => $v['emp_id']);
                $command->delete('tbl_day_comment', $condition, $params);
            }
        }
    }

}
