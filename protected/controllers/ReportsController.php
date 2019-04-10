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
                'actions' => array('create', 'update','getReports','getTimesheet','isExportRequest','allReports','fetchGraphData','graphReports','fetchBarData','fetchprojectData','fetchProjectReport','fetchTimesheetReport','timesheetReports','fetchResourcesData','fetchRTimesheetReport','fetchProjectTimeData', 'budgetreport'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'testMail', 'home', 'test','dailytask'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('NotFilledStatus', 'StatusReport', 'AdminAll', 'timeSheetNotFilled','WeeklyReport'),
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

    /**
     * Function to generate records based on all program, project, task and sub task
     */
    public function actiongetReports($project_id) {

        $model = new Reports('search');
        $model->unsetAttributes();  // clear any default values
        $this->layout = 'report';

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

        if(!empty($project_id))
        {
            if(empty($allcondition)){
                $allcondition .= ' where st.sub_project_id='.$project_id;
            } else {
                $allcondition .= ' and st.sub_project_id='.$project_id;
            }
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

        $allcount = $this->getAllCount();

        $this->render('allreports', array(
            'model' => $model,
            'data' => $search_data,
            'allcount' => $allcount
        ));

    }

    /**
     * Function to generate report based on all the timesheet entries entered by users.
     */
    public function actiongetTimesheet($emp_id='',$project_id='') {

        $model = new Reports('search');
        $model->unsetAttributes();  // clear any default values

        $this->layout = 'report';

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
            $datecondition = " dc.day BETWEEN '{$from_date}' and '{$to_date}'";

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


        if(!empty($emp_id))
        {
            if(empty($allcondition)){
                $allcondition .= ' where dc.emp_id='.$emp_id;
            } else {
                $allcondition .= ' and dc.emp_id='.$emp_id;
            }
        }


        if(!empty($project_id))
        {
            if(empty($allcondition)){
                $allcondition .= ' where dc.spid='.$project_id;
            } else {
                $allcondition .= ' and dc.spid='.$project_id;
            }
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

        $allcount = $this->getAllCount();

        $this->render('alltimesheet', array(
            'model' => $model,
            'data' => $search_data,
            'allcount' => $allcount
        ));

    }

    /**
     * Function to fetch count of programs,projects, tasks and subtasks
     */
    public function getAllCount()
    {
        $count_qry = "select
                        (select count(*) from tbl_project_management) as Programs,
                        (select count(*) from tbl_sub_project) as Projects,
                        (select count(*) from tbl_pid_approval) as Tasks,
                        (select count(*) from tbl_sub_task) as SubTasks";

        $counts = Yii::app()->db->createCommand($count_qry)->queryAll();

        return $counts;
    }

    /**
     * Get all reports
     */
    public function actionallReports()
    {
        $this->layout = 'column1';
        $allcount = $this->getAllCount();

        $this->render('reports', array(
            'allcount' => $allcount,
        ));
    }

    public function actionfetchGraphData()
    {

        $graphArr = [];$programArr = [];$nodeArr = [];
        $query = "select sp.spid,pm.pid,sp.sub_project_name as project_name,pm.project_name as program_name,(select sum(pl.level_hours * lm.budget_per_hour) from tbl_project_level_allocation pl inner join tbl_level_master lm on lm.level_id = pl.level_id where pl.project_id = sp.spid) as total_budget  from tbl_sub_project as sp left join tbl_project_management as pm on pm.pid = sp.pid where pm.project_name != '' and sp.approval_status != 0 order by program_name";
        $graphArr = Yii::app()->db->createCommand($query)->queryAll();
        $graphArr[] = array('project_name' => '%Exit%', 'program_name'=>'','total_budget'=>999);

        if(!empty($graphArr))
        {
            $i = 1;
            foreach ($graphArr as $key => $node) {
                if(!empty($node['total_budget'])){

                    if($program_name != $node['program_name'] && $i > 1)
                    {

                        $programArr[] = array('name' => $program_name,'children' => $nodeArr,'id'=>'program_'.$program_id);

                        $nodeArr = [];
                        if($node['project_name'] === '%Exit%'){
                            break;
                        }
                    }
                    if($program_name == $node['program_name'] || $i > 1)
                    {

                        $nodeArr[] = array('name' => $node['project_name'],'size'=>1,'id'=>'project_'.$node['spid']);
                    }

                    $program_name = $node['program_name'];
                    $program_id = $node['pid'];
                    $i++;
                }


            }

            $graphArr = array('name'=>'ProjectReports', 'children'=>$programArr);
        }

        echo json_encode($graphArr);

        die;


    }

    public function actiongraphReports()
    {
        $this->layout = 'column1';
        $this->render('graphreports');
    }

    public function actiontimesheetReports()
    {
        $this->layout = 'column1';



        $this->render('timesheetgraphreports');
    }


    public function actionfetchBarData()
    {
        $graphArr = [];$barArr = [];$nodeArr = [];
        $query = "";
        $barArr = Yii::app()->db->createCommand("select pid_id,task_title,
                    (select sum(est_hrs) from tbl_sub_task where pid_approval_id = pa.pid_id ) as allocated_hrs,
                    (SELECT  TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC( `hours` ) ) ), '%h:%i') from tbl_day_comment dc inner join tbl_sub_task st on st.stask_id = dc.stask_id where st.pid_approval_id = pa.pid_id) as utilized_hrs
                from tbl_pid_approval pa
                where sub_project_id = 341;
                ")->queryAll();

        if(!empty($barArr)) {
            foreach ($barArr as $key => $value) {
                $allocated_hrs = !empty($value['allocated_hrs']) ? $value['allocated_hrs'] : 0;
                $utilized_hrs = !empty($value['utilized_hrs']) ? $value['utilized_hrs'] : 0;
                $values[] = array('value'=>$allocated_hrs,'rate'=>'Allocated');
                $values[] = array('value'=>$utilized_hrs,'rate'=>'Utilized');
                $barData[] = array('categorie' => $value['task_title'],'values'=>$values);
                $values = [];
            }
        }
        // print_r($barData);
        echo json_encode($barData);die;

    }

    public function actionfetchProjectData()
    {

        $projectData['allocated'] =Yii::app()->db->createCommand("select sum(st.est_hrs) as allocated_hrs,SUM(st.est_hrs * lm.budget_per_hour) AS allocated_budget from tbl_sub_task st left join tbl_assign_resource_level rl on rl.emp_id = st.emp_id left join tbl_level_master lm on lm.level_id = rl.level_id where sub_project_id = {$_POST['project_id']}")->queryRow();
        $projectData['estimated'] = Yii::app()->db->createCommand("select sum(pl.level_hours * lm.budget_per_hour) as estimated_budget,sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid left join tbl_level_master lm on lm.level_id = pl.level_id where spid = {$_POST['project_id']}")->queryRow();
        // $projectData['utilized'] = Yii::app()->db->createCommand("SELECT  SEC_TO_TIME( SUM( TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  FROM tbl_day_comment where spid={$_POST['project_id']}")->queryRow();
        $projectData['utilized'] = Yii::app()->db->createCommand("SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) AS utilized_hrs, SUM((TIME_FORMAT(`hours`,'%H.%i') * budget_per_hour)) as utilized_budget FROM tbl_day_comment dc left join tbl_assign_resource_level rl on rl.emp_id = dc.emp_id left join tbl_level_master lm on lm.level_id= rl.level_id where dc.spid={$_POST['project_id']}")->queryRow();
        $projectData['tasks'] = Yii::app()->db->createCommand("select count(*) as tasks from tbl_pid_approval where sub_project_id={$_POST['project_id']}")->queryRow();
        $projectData['sub_tasks'] = Yii::app()->db->createCommand("select count(*) as sub_tasks from tbl_sub_task where sub_project_id={$_POST['project_id']}")->queryRow();
        $resources = Yii::app()->db->createCommand("select emp_id from tbl_sub_task where sub_project_id = {$_POST['project_id']} group by emp_id")->queryAll();
        $projectData['resources'] = count($resources);
        $projectData['project_id'] = $_POST['project_id'];
        echo json_encode($projectData);die;
    }


    public function actionfetchProjectReport()
    {
        $data = [];
        $taskDetails = Yii::app()->db->createCommand("select project_task_id,task_title,task_name,sub_task_id,sub_task_name,concat(first_name,' ',last_name),est_hrs,
            (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` ) ) )  FROM tbl_day_comment where stask_id=st.stask_id) as utilized_hours
            from tbl_pid_approval pa
            left join tbl_sub_task st on st.pid_approval_id = pa.pid_id
            left join tbl_task tk on tk.task_id = st.task_id
            left join tbl_employee em on em.emp_id = st.emp_id
            where pa.sub_project_id = {$_POST['project_id']}")->queryAll();

        if(!empty($taskDetails))
        {
            foreach($taskDetails as $task) {
                $taskArr = [];
                foreach ($task as $key => $value) {
                    $taskArr[] = $value;
                }
                $data[] = $taskArr;
            }
        }

        $count = count($data);
        $jsonArray['recordsTotal'] = $count;
        $jsonArray['recordsFiltered'] = $count;
        $jsonArray['data'] = $data;
        echo json_encode($jsonArray);die;

    }

    public function actionfetchTimesheetReport()
    {
        $data = [];
        $taskDetails = Yii::app()->db->createCommand("select sp.project_id, sp.sub_project_name, pa.project_task_id, pa.task_title,st.sub_task_id,st.sub_task_name ,dc.day, concat(first_name,' ',last_name) as username,dc.hours, dc.comment
        from tbl_day_comment dc
            left join tbl_sub_task st on st.stask_id = dc.stask_id
            left join tbl_sub_project sp on sp.spid = dc.spid
            left join tbl_pid_approval pa on pa.sub_project_id = sp.spid
            left join tbl_employee em on em.emp_id = dc.emp_id
            where dc.spid = {$_POST['project_id']}")->queryAll();

        if(!empty($taskDetails))
        {
            foreach($taskDetails as $task) {
                $taskArr = [];
                foreach ($task as $key => $value) {
                    $taskArr[] = $value;
                }
                $data[] = $taskArr;
            }
        }

        $count = count($data);
        $jsonArray['recordsTotal'] = $count;
        $jsonArray['recordsFiltered'] = $count;
        $jsonArray['data'] = $data;
        echo json_encode($jsonArray);die;

    }

    public function actionfetchResourcesData()
    {
        $emp_id  = $_POST['emp_id'];
        // $emp_name = ucwords(Yii::app()->session['login']['first_name'].' '.Yii::app()->session['login']['last_name']);
        $emp_name = Yii::app()->db->createCommand("Select concat(first_name,' ',last_name) as emp_name from tbl_employee where emp_id = {$emp_id}")->queryRow();

        $graphArr = [];$programArr = [];$nodeArr = [];
        $query = "select sub_project_id as project_id,sub_project_name as project_name ,pm.pid as program_id,project_name as program_name from tbl_sub_task st left join tbl_sub_project sp on sp.spid = sub_project_id left join tbl_project_management pm on pm.pid = sp.pid where emp_id = {$emp_id} group by sub_project_id order by project_name";

        $emp_project_data = Yii::app()->db->createCommand("SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) AS utilized_hrs, SUM((TIME_FORMAT(`hours`,'%H.%i') * budget_per_hour)) as utilized_budget FROM tbl_day_comment dc left join tbl_assign_resource_level rl on rl.emp_id = dc.emp_id left join tbl_level_master lm on lm.level_id= rl.level_id where dc.emp_id={$emp_id}")->queryRow();

        $graphArr = Yii::app()->db->createCommand($query)->queryAll();
        $graphArr[] = array('project_id'=>'#99','project_name' => '%Exit%', 'program_id'=>'','program_name'=>'test');


        if(!empty($graphArr))
        {
            $i = 1;
            foreach ($graphArr as $key => $node) {
                if(!empty($node['project_id'])){

                    if($program_name != $node['program_name'] && $flag == true)
                    {

                        $programArr[] = array('name' => $program_name,'children' => $nodeArr,'id'=>'program_'.$program_id);
                        // print_r($programArr);
                        $nodeArr = [];
                        $i = 1;
                        if($node['project_name'] === '%Exit%'){
                            break;
                        }
                    }
                    if($program_name == $node['program_name'] || $i == 1)
                    {

                        $nodeArr[] = array('name' => $node['project_name'],'id'=>'project_'.$node['project_id']);

                        $flag = true;
                    }

                    $program_name = $node['program_name'];
                    $program_id = $node['program_id'];
                    $i++;
                }


            }

            $graphDataArr['graphdata'] = array('name'=>ucwords($emp_name['emp_name']), 'children'=>$programArr);
        }


        $graphDataArr['empdata'] = $emp_project_data;
        // print_r($graphArr);die;
        echo json_encode($graphDataArr);

        die;
    }

    public function actionfetchRTimesheetReport()
    {
        $data = [];
        $where ='';
        if($_POST['loaddata'] == "1"){
            $where = 'where dc.emp_id='.$_POST['emp_id'].' and dc.spid='.$_POST['project_id'];
        }else{
            $where = 'where dc.emp_id='.$_POST['emp_id'];
        }
        // echo $where;die;
        $taskDetails = Yii::app()->db->createCommand("select sp.project_id, sp.sub_project_name, pa.project_task_id, pa.task_title,st.sub_task_id,st.sub_task_name ,dc.day, concat(first_name,' ',last_name) as username,dc.hours, dc.comment
        from tbl_day_comment dc
            left join tbl_sub_task st on st.stask_id = dc.stask_id
            left join tbl_sub_project sp on sp.spid = dc.spid
            left join tbl_pid_approval pa on pa.sub_project_id = sp.spid
            left join tbl_employee em on em.emp_id = dc.emp_id
            {$where} order by day desc")->queryAll();

        if(!empty($taskDetails))
        {
            foreach($taskDetails as $task) {
                $taskArr = [];
                foreach ($task as $key => $value) {
                    $taskArr[] = $value;
                }
                $data[] = $taskArr;
            }
        }

        $count = count($data);
        $jsonArray['draw'] = $_REQUEST['draw'];
        $jsonArray['recordsTotal'] = $count;
        $jsonArray['recordsFiltered'] = 10;
        $jsonArray['data'] = $data;

        echo json_encode($jsonArray);die;
    }

    public function actionfetchProjectTimeData()
    {
        // print_r($_POST);die;
        $project_total =Yii::app()->db->createCommand("SELECT SUM((TIME_FORMAT(`hours`,'%H.%i') * budget_per_hour)) as total_budget FROM tbl_day_comment dc left join tbl_assign_resource_level rl on rl.emp_id = dc.emp_id left join tbl_level_master lm on lm.level_id= rl.level_id where dc.emp_id={$_POST['emp_id']}")->queryRow();
        $projectData['utilized'] = Yii::app()->db->createCommand("SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) AS utilized_hrs, SUM((TIME_FORMAT(`hours`,'%H.%i') * budget_per_hour)) as utilized_budget FROM tbl_day_comment dc left join tbl_assign_resource_level rl on rl.emp_id = dc.emp_id left join tbl_level_master lm on lm.level_id= rl.level_id where dc.spid={$_POST['project_id']} and dc.emp_id={$_POST['emp_id']}")->queryRow();
        $projectData['project_per'] = round(($projectData['utilized']['utilized_budget'] / $project_total['total_budget']) * 100);
        $projectData['project_id'] = $_POST['project_id'];
        echo json_encode($projectData);die;
    }

    public function actionBudgetreport(){
        $query = 'select
                pla.project_id, sub_project_name, lm.level_id as levelId, level_name, level_hours
                    from tbl_project_level_allocation pla
                inner join tbl_level_master lm on pla.level_id = lm.level_id
                inner join tbl_sub_project sp on pla.project_id = sp.spid
                ';

        $projectHoursArr =Yii::app()->db->createCommand($query)->queryAll();

        $dataArr = [];
        foreach($projectHoursArr as $projectData){

            $dataArr[$projectData['project_id']]['project_name'] = $projectData['sub_project_name'];
            $dataArr[$projectData['project_id']]['hours'][$projectData['levelId']]['est'] = $projectData['level_hours'];
            $dataArr[$projectData['project_id']]['total'] += $projectData['level_hours'];
            $pQuery = "SELECT  "
                    . " level_id, BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs, sum(cast(time_to_sec(hours) / (60 * 60) as decimal(10, 1))) as usedHours "
                    . " FROM tbl_day_comment tdc"
                    . " LEFT JOIN tbl_assign_resource_level tarl on tdc.emp_id = tarl.emp_id"
                    . " where spid={$projectData['project_id']} and day > '2019-01-01 00:00:00'"
                    . " GROUP BY tarl.level_id";

            $usedHrs = Yii::app()->db->createCommand($pQuery)->queryAll();
            foreach($usedHrs as $hours){
                $levelId = ($hours['level_id'] > 0) ? $hours['level_id'] : 0;
                $dataArr[$projectData['project_id']]['hours'][$levelId]['used'] = $hours['usedHours'];
                $dataArr[$projectData['project_id']]['used_hours'] += $hours['usedHours'];
            }
        }

        $lQuery = 'select * from tbl_level_master';
        $levelMaster = Yii::app()->db->createCommand($lQuery)->queryAll();

        $levelArray = [];
        foreach($levelMaster as $lRow){
            $levelArray[$lRow['level_id']]['budget'] = $lRow['budget_per_hour'];
            $levelArray[$lRow['level_id']]['name'] = $lRow['level_name'];
        }

    }

    public function actionWeeklyReport(){

        $this->layout = 'column1';
        $result  = Yii::app()->db->createCommand('select
                    (select project_name from tbl_project_management where pid = sp.pid) as ProgramName,
                    sub_project_name as ProjectName,
                    (select sum(level_hours) from tbl_project_level_allocation where project_id = sp.spid) as estimated_hrs,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid) as utilized_hrs,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid and day = CURDATE()) as today,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid and day = CURDATE() - 1) as today_1,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid and day = CURDATE() - 2) as today_2,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid and day = CURDATE() - 3) as today_3,
                    (SELECT  BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) from tbl_day_comment where spid = sp.spid and day = CURDATE() - 4) as today_4
                from tbl_sub_project sp')->queryAll();


        // if ($this->isExportRequest()){
        //     $date = date('Y-m-d');
        //     $exportResult[] =array('Program Name', 'Project Name', 'Estimated Hours', 'Utilized Hours', date('Y-m-d', strtotime('-1 day', strtotime($date))),date('Y-m-d', strtotime('-2 day', strtotime($date))),date('Y-m-d', strtotime('-3 day', strtotime($date))),date('Y-m-d', strtotime('-4 day', strtotime($date))));
        //     $exportResult = array_merge($exportResult,$result);
        //     // print_r($exportResult);die;
        //     $table_column = ("");
        //         $this->exportCSV($exportResult, $table_column);
        // }

        // $model = new CArrayDataProvider($result, array(
        //     'totalItemCount' => count($result),
        //     'pagination' => array(
        //     'pageSize' => 10,
        //     ),
        // ));

        $this->render('weeklyreport', array(
            // 'model' => $model,
            'result' => $result
        ));
    }
}