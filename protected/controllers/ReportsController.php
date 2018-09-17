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
                'actions' => array('create', 'update','getReports','getTimesheet'),
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

    public function actiongetReports(){
        $this->layout = 'column1';
        $condition = '';
        if(isset($_POST['from_date']) && isset($_POST['to_date']))
        {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $condition = "where st.created_at BETWEEN '{$from_date}' and '{$to_date}'";
        }

        $getRecords = "select pm.project_id as program_id,
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
                        inner join tbl_pid_approval pa on pa.pid_id = st.pid_approval_id ".$condition." order by created_at desc";
        

        $records = Yii::app()->db->createCommand($getRecords)->queryAll();

        $this->render('projectreports', array(
            'records' => $records,
        ));
    }

    public function actiongetTimesheet(){
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

}
