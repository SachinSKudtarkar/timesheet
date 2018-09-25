<?php
error_reporting(E_ALL);
ini_set('display_errors',0);

class ReportsController extends Controller {
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
                'actions' => array('create', 'update','getReports','getTimesheet','isExportRequest'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'testMail', 'home', 'test','dailytask'),
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


    public function actiongetTimesheet1(){
        $this->layout = 'column1';
        $condition = '';
        
        if(isset($_POST['from_date']) && isset($_POST['to_date']))
        {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $condition = "where dc.day BETWEEN '{$from_date}' and '{$to_date}'";
        }

        $getRecords = "select date(dc.day) as day, 
                            pm.project_name as program_name,
                            sp.sub_project_name as project_name,
                            pa.project_task_id,
                            pa.task_title,
                            pa.task_description,
                            st.sub_task_id, 
                            st.sub_task_name,
                            st.est_hrs,
                            concat(em.first_name,' ',em.last_name) as username,
                            dc.comment, 
                            dc.hours
                        from tbl_day_comment dc
                        inner join tbl_project_management pm on pm.pid = dc.pid
                        inner join tbl_sub_project sp on sp.spid = dc.spid
                        inner join tbl_sub_task st on st.stask_id = dc.stask_id
                        inner join tbl_pid_approval pa on pa.pid_id = st.pid_approval_id
                        inner join tbl_employee em on em.emp_id = dc.emp_id ".$condition."
                        order by dc.created_at desc";
        

        $records = Yii::app()->db->createCommand($getRecords)->queryAll();

    
        $this->render('timesheetreports', array(
            'records' => $records,
        ));
    }


    /**
     * Function to generate records based on all program, project, task and sub task
     */
    public function actiongetReports() {
    
        $model = new Reports('search');
        $model->unsetAttributes();  // clear any default values
        $this->layout = 'column1';

        $_GET['DayComment'] = array_map('trim', $_GET['DayComment']);
        // $model->attributes = $_GET['DayComment'];
        $condition = $_GET['Reports'];
        $allcondition = '';
        $datecondition = '';

        /* Search filters for all the fields */
        if (isset($_REQUEST['Reports'])) {

            $whrcondition = '';
            $program_id = trim($condition['program_id']);
            if($program_id){
                $whrcondition .= "pm.project_id LIKE '%{$program_id}%'";
            }
            $program_name = trim($condition['program_name']);
            if($program_name){
                $whrcondition .= "pm.project_name LIKE '%{$program_name}%'";
            }
            $project_id = trim($condition['project_id']);
            if($project_id){
                
                $whrcondition .= "sp.project_id LIKE '%{$project_id}%'";
            }
            $task_name = trim($condition['task_name']);
            if($task_name){
                $whrcondition .= "tk.task_name LIKE '%{$task_name}%' ";
            }
            $project_task_id = trim($condition['project_task_id']);
            if($project_task_id){
                $whrcondition .= "pa.project_task_id LIKE '%{$project_task_id}%'";
            }
            $task_title = trim($condition['task_title']);
            if($task_title){
                $whrcondition .= "pa.task_title LIKE '%{$task_title}%'";
            }
            $task_description = trim($condition['task_description']);
            if($task_description){
                $whrcondition .= "pa.task_description LIKE '%{$task_description}%'";
            }
            $sub_task_id = trim($condition['sub_task_id']);
            if($sub_task_id){
                $whrcondition .= "st.sub_task_id LIKE '%{$sub_task_id}%'";
            }
            $sub_task_name = trim($condition['sub_task_name']);
            if($sub_task_name){
                $whrcondition .= "st.sub_task_name LIKE '%{$sub_task_name}%'";
            }
            $est_hrs = trim($condition['est_hrs']);
            if($est_hrs){
                $whrcondition .= "st.est_hrs LIKE '%{$est_hrs}%'";
            }
            $created_at = trim($condition['created_at']);
            if($created_at){
                $whrcondition .= "st.created_at LIKE '%{$created_at}%'";
            }

            
        }

        /* Date filters for the records to search from_date to to_date */
        if(isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))
        {
            $from_date = $_REQUEST['from_date'];
            $to_date = $_REQUEST['to_date'];
            $datecondition = " st.created_at BETWEEN '{$from_date}' and '{$to_date}'";
        }

        if(!empty($whrcondition))
        {
            
            $allcondition = ' where '.$whrcondition;
            
            if(!empty($datecondition))
                $allcondition .= ' and '.$datecondition;
        }else{

            if(!empty($datecondition))
                $allcondition = ' where '.$datecondition;
        }
            

        $sql1 = "select pm.project_id as program_id,
                pm.project_name as program_name,
                sp.project_id as project_id,
                sp.sub_project_name as project_name,
                (select sum(level_hours) from tbl_project_level_allocation where project_id = sp.spid) as allocated,
                tk.task_name,
                pa.project_task_id,
                pa.task_title,
                pa.task_description,
                st.sub_task_id,
                st.sub_task_name,
                st.est_hrs,
                (SELECT  SEC_TO_TIME( SUM( TIME_TO_SEC( `hours` ) ) )  FROM tbl_day_comment where stask_id=st.stask_id) as utilized_hours,
                (Select concat(first_name,' ',last_name) from tbl_employee where emp_id = st.created_by) as created_by,
                st.created_at
            from tbl_sub_task st
            inner join tbl_project_management pm on pm.pid = st.project_id
            inner join tbl_task tk on tk.task_id = st.task_id
            inner join tbl_sub_project sp on sp.spid = st.sub_project_id
            inner join tbl_pid_approval pa on pa.pid_id = st.pid_approval_id ".$allcondition." order by created_at desc";



            $search_data = Yii::app()->db->createCommand($sql1)->queryAll();
            
        /* Export filter to export the data to excelsheet */
        if ($this->isExportRequest()) {
            

            $inpCount = 0;
            foreach ($search_data as $key => $value) {
                $inpCount++;
                $finalArr[$key] = array(
                    $inpCount,
                    $value['program_id'],
                    $value['program_name'],
                    $value['project_id'],
                    $value['project_name'],
                    $value['allocated'],
                    $value['task_name'],
                    $value['project_task_id'],
                    $value['task_title'],
                    $value['task_description'],
                    $value['sub_task_id'],
                    $value['sub_task_name'],
                    $value['est_hrs'],
                    $value['utilized_hours'],
                    $value['created_by'],
                    $value['created_at'],
                );
            }

            $export_column_name = array('Sr No.','Program ID', 'Program Name','Project ID', 'Project Name', 'Allocated', 'Task Name', 'Project Task Id','Task Title','Task Description','Sub Task ID','Sub Task Name','Estimated Hours','Utilized Hours','Created by','Created at');
            $filename = "Timesheet All Reports " . date('d_m_Y') . "_" . date('H') . "_hr.csv";
            CommonUtility::generateExcel($export_column_name, $finalArr, $filename);
        }
        $this->render('allreports', array(
            'model' => $model,
            'data' => $search_data,
        ));

    }

    /**
     * Function to generate report based on all the timesheet entries entered by users.
     */
    public function actiongetTimesheet() {
    
        $model = new Reports('search');
        $model->unsetAttributes();  // clear any default values
    
        $this->layout = 'column1';

        $_GET['DayComment'] = array_map('trim', $_GET['DayComment']);
    
        $condition = $_GET['Reports'];
        $allcondition = '';
        $datecondition = '';
        /* Search filters to search respective fields in the database */
        if (isset($_REQUEST['Reports'])) {

            $whrcondition = '';
            $day = trim($condition['day']);
            if($day){
                $whrcondition .= "dc.day LIKE '%{$day}%'";
            }
            $program_name = trim($condition['program_name']);
            if($program_name){
                $whrcondition .= "pm.project_name LIKE '%{$program_name}%'";
            }
            $project_task_id = trim($condition['project_task_id']);
            if($project_task_id){
                $whrcondition .= "pa.project_task_id LIKE '%{$project_task_id}%'";
            }
            $task_title = trim($condition['task_title']);
            if($task_title){
                $whrcondition .= "pa.task_title LIKE '%{$task_title}%'";
            }
            $task_description = trim($condition['task_description']);
            if($task_description){
                $whrcondition .= "pa.task_description LIKE '%{$task_description}%'";
            }
            $sub_task_id = trim($condition['sub_task_id']);
            if($sub_task_id){
                $whrcondition .= "st.sub_task_id LIKE '%{$sub_task_id}%'";
            }
            $sub_task_name = trim($condition['sub_task_name']);
            if($sub_task_name){
                $whrcondition .= "st.sub_task_name LIKE '%{$sub_task_name}%'";
            }
            $est_hrs = trim($condition['est_hrs']);
            if($est_hrs){
                $whrcondition .= "st.est_hrs LIKE '%{$est_hrs}%'";
            }
            $username = trim($condition['username']);
            if($username){
                $whrcondition .= "CONCAT(first_name,' ',last_name) LIKE '%{$username}%'";
            }
            $comment = trim($condition['comment']);
            if($comment){
                $whrcondition .= "dc.comment LIKE '%{$comment}%'";
            }
            $hours = trim($condition['hours']);
            if($hours){
                $whrcondition .= "dc.hours LIKE '%{$hours}%'";
            }

            
        }

        /* Date filters to get records from the specified dates*/
        if(isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))
        {
        
            $from_date = $_REQUEST['from_date'];
            $to_date = $_REQUEST['to_date'];
            $datecondition = " st.created_at BETWEEN '{$from_date}' and '{$to_date}'";    
        
            
        }

        if(!empty($whrcondition))
        {
            
            $allcondition = ' where '.$whrcondition;
            
            if(!empty($datecondition))
                $allcondition .= ' and '.$datecondition;
        }else{

            if(!empty($datecondition))
                $allcondition = ' where '.$datecondition;
        }
            

        $sql1 = "select date(dc.day) as day, 
                        pm.project_name as program_name,
                        sp.sub_project_name as project_name,
                        pa.project_task_id,
                        pa.task_title,
                        pa.task_description,
                        st.sub_task_id, 
                        st.sub_task_name,
                        st.est_hrs,
                        concat(em.first_name,' ',em.last_name) as username,
                        dc.comment, 
                        dc.hours
                    from tbl_day_comment dc
                    inner join tbl_project_management pm on pm.pid = dc.pid
                    inner join tbl_sub_project sp on sp.spid = dc.spid
                    inner join tbl_sub_task st on st.stask_id = dc.stask_id
                    inner join tbl_pid_approval pa on pa.pid_id = st.pid_approval_id
                    inner join tbl_employee em on em.emp_id = dc.emp_id ".$allcondition."
                    order by dc.created_at desc";

        
        $search_data = Yii::app()->db->createCommand($sql1)->queryAll();
            
        /* Export filter to export the records to excelsheet. */
        if ($this->isExportRequest()) {

            $inpCount = 0;
            foreach ($search_data as $key => $value) {
                $inpCount++;
                $finalArr[$key] = array(
                    $inpCount,
                    $value['day'],
                    $value['program_name'],
                    $value['project_name'],
                    $value['project_task_id'],
                    $value['task_title'],
                    $value['task_description'],
                    $value['sub_task_id'],
                    $value['sub_task_name'],
                    $value['est_hrs'],
                    $value['username'],
                    $value['comment'],
                    $value['hours'],
                );
            }

            $export_column_name = array('Sr No.','Day', 'Program Name','Project Name', 'Project Task Id','Task Title','Task Description','Sub Task ID','Sub Task Name','Estimated Hours','Username','Comment','Utilized Hours');
            $filename = "Timesheet All Comments " . date('d_m_Y') . "_" . date('H') . "_hr.csv";
            CommonUtility::generateExcel($export_column_name, $finalArr, $filename);
        }
        $this->render('alltimesheet', array(
            'model' => $model,
            'data' => $search_data,
        ));

    }
}