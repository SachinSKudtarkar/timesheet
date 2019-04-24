<?php

/**
 * This is the model class for table "tbl_day_comment".
 *
 * The followings are the available columns in table 'tbl_day_comment':
 * @property integer $id
 * @property integer $pid
 * @property string $day
 * @property string $comment
 * @property integer $created_by
 */
class DayComment extends CActiveRecord {

    public $projectName;
    public $project_name;
    public $program_name;
    public $sub_project_name;
    public $sub_task_name;
    public $task_name;
    //public $hours;
    public $name;
    public $user_name;
    public $from;
    public $total_hours;
    public $range_days;
    public $days_filled;
    public $dates_filled;
    public $to;
    public $shift;
    public $usedHrs;
    public $approved_hrs;
    public $remarks;
    public $logged_hrs;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_day_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pid, day, comment, created_by,comment, hours, projectName, project_name ,sub_project_name', 'required'),
            array('pid, created_by, is_submitted', 'numerical', 'integerOnly' => true),
            array('day', 'length', 'max' => 15),
            array('comment', 'length', 'max' => 1000),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, pid, day, comment, created_by,hours, projectName, project_name ,sub_project_name, emp_id,name, is_submitted,approved_hrs,logged_hrs,remarks', 'safe', 'on' => 'search,searchAll'),
            array('pid, day, comment,hours, projectName, project_name ,sub_project_name,shift,approved_hrs,remarks,  ', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            // 'Creater' => array(self::BELONGS_TO, 'Employee', 'emp_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'pid' => 'Pid',
            'emp_id' => 'Employee',
            'day' => 'Date',
            'comment' => 'Comment',
            'created_by' => 'created_by',
            'project_name' => 'Project Name',
            'hours' => 'Hours',
            'sub_project_name' => 'Project',
            'sub_task_name' => 'Task',
            'shift' => 'Shift',
            'approved_hrs' => 'Approved Hours',
            'logged_hrs' => 'Logged Hours',
            'remarks' => 'Approval Remarks',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($pagination = true) {
        $criteria = new CDbCriteria;
        // @todo Please modify the following code to remove attributes that should not be searched.
        // echo $this->from;die;
        if (!empty($this->from) && !empty($this->to)) {
            $criteria->condition = ' (t.day between "' . date('Y-m-d', strtotime($this->from)) . '" AND "' . date('Y-m-d', strtotime($this->to)) . ' 23:59:59")';

        }
        $criteria->select = "t.*, pm.project_name,t.comment,t.day,t.comment,t.hours,t.emp_id,sb.sub_project_name,st.sub_task_name,t.remarks";
        $criteria->compare('id', $this->id);
        $criteria->compare('t.pid', $this->pid);
        $criteria->compare('t.emp_id', Yii::app()->session['login']['user_id']);
        $criteria->compare('t.day', $this->day, true);
        $criteria->compare('t.comment', $this->comment, true);
        $criteria->compare('t.hours', $this->hours, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('t.remarks', $this->remarks);
        $criteria->compare('sb.sub_project_name', $this->sub_project_name);
        $criteria->compare('pm.project_name', $this->project_name, true);
        $criteria->join = "INNER JOIN tbl_project_management pm ON t.pid = pm.pid LEFT join tbl_sub_project sb ON sb.spid=t.spid LEFT join tbl_sub_task as st ON st.stask_id=t.stask_id";

        $criteria->order = "id,day DESC";

        if ($pagination) {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => array('pageSize' => 20,),
            ));
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => false
            ));
        }
    }

    public function searchAll($pagination = true) {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        if (!empty($this->from) && !empty($this->to)) {
            $criteria->condition = ' (t.day between "' . date('Y-m-d', strtotime($this->from)) . '" AND "' . date('Y-m-d', strtotime($this->to)) . ' 23:59:59")';
        }
//		if($criteria->condition != '') $criteria->condition .= ' AND ';
//		$criteria->condition .= ' (t.emp_id = ' . Yii::app()->session['login']['user_id'] . ' )';

        $criteria->select = "t.*,CONCAT(first_name,' ',last_name) as name,sb.sub_project_name,pm.project_name, t.is_submitted,st.task_name ";
        $criteria->compare('id', $this->id);
        $criteria->compare('t.pid', $this->pid);
        $criteria->compare('t.emp_id', $this->emp_id);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('t.hours', $this->hours, true);
        $criteria->compare('t.is_submitted', $this->is_submitted, true);
        $criteria->compare('pm.project_name', $this->project_name, true);
        $criteria->compare('sb.sub_project_name', $this->sub_project_name);
        $criteria->compare('st.task_name', $this->task_name);
        $criteria->compare('t.shift', $this->shift,true);
        $criteria->compare('CONCAT(first_name," ",last_name)', $this->name, true);
        // $criteria->with = array("Creater" => array("alias" => 'r', "select" => 'first_name, last_name'));
        
        $criteria->join = "INNER JOIN tbl_project_management pm ON t.pid = pm.pid INNER JOIN tbl_employee emp ON emp.emp_id = t.emp_id LEFT join tbl_sub_project sb ON sb.spid=t.spid left Join tbl_task as st on st.task_id = t.stask_id";
        // $criteria->order = "id, day DESC";

        if ($pagination) {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => array('pageSize' => 30,),
            ));
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => false
            ));
        }
    }

    public function searchStatusIncomplete($pagination = true) {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        if (!empty($this->from) && !empty($this->to)) {
            $criteria->condition = ' (t.day between "' . date('Y-m-d', strtotime($this->from)) . '" AND "' . date('Y-m-d', strtotime($this->to)) . ' 23:59:59")';
        }
//		if($criteria->condition != '') $criteria->condition .= ' AND ';
//		$criteria->condition .= ' (t.emp_id = ' . Yii::app()->session['login']['user_id'] . ' )';
        $criteria->select = "t.*,CONCAT(first_name,' ',last_name) as name, sum(t.hours) as total_hours, count(DISTINCT(t.day)) as days_filled, GROUP_CONCAT(DISTINCT(DATE_FORMAT(t.day,'%d-%m-%Y'))) as dates_filled ";
        $criteria->compare('id', $this->id);
        $criteria->compare('t.pid', $this->pid);
        $criteria->compare('t.emp_id', $this->emp_id);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('t.hours', $this->hours, true);
        $criteria->compare('t.dates_filled', $this->dates_filled, true);
        $criteria->compare('t.total_hours', $this->total_hours, true);
        $criteria->compare('t.days_filled', $this->days_filled, true);
        $criteria->compare('CONCAT(first_name," ",last_name)', $this->name, true);
        $criteria->join = "INNER JOIN tbl_employee emp ON emp.emp_id = t.emp_id";
        $criteria->group = "t.emp_id";
        $criteria->order = "id, day DESC";
        if ($pagination) {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => array('pageSize' => 20,),
            ));
        } else {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => false
            ));
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DayComment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getProject($model) {
        $query = "Select project_name from tbl_project_management Where pid =" . $model->pid;
        $projectName = Yii::app()->db->createCommand($query)->queryRow();
        return $projectName['project_name'];
    }

    public function getUserName($model) {
        $name = Yii::app()->db->createCommand()
                ->select('CONCAT(first_name," ",last_name) as full_name')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $model->emp_id))
                ->queryRow();
        return isset($name['full_name']) ? $name['full_name'] : $model->emp_id;
    }

    public function getCommentorEmail($model) {

        $name = Yii::app()->db->createCommand()
                ->select('email')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $model->emp_id))
                ->queryRow();

        return $name['email'];
    }

    public function getCleanComment($model) {

        $comment = $model->comment;
        $splited = explode("==", $comment);
        return $splited[1];
    }

    public function getFormatedDate($model) {
        $date = date("Y-m-d", strtotime($model['day']));
        return  $date;
    }

    public function sendMailStatus() {
        echo "\n\r run cron \n\r";
       // $selecting_date = date('Y-m-d',strtotime('last monday -7 days'));
    $selecting_date = date('Y-m-d', strtotime("monday this week"));
        $query_str = "";
        for ($k = 0; $k < 6; $k++) {
            $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
            $date = date("Y-m-d", $generte_date);
            $query_str_array[] = "DATE(dc.day) = '" . $date . "'";
        }
        $query_str = implode(" AND ", $query_str_array);
        $query_str = "(" . $query_str . ")";
        $query = "SELECT DATE(dc.day),CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp left join tbl_day_comment dc on emp.emp_id=dc.emp_id WHERE is_active = 1 AND " . $query_str." group by emp_name";//

        $emp_list = Yii::app()->db->createCommand($query)->queryAll();
        $emp_ids = "'" .implode(" ','", array_column($emp_list, 'emp_id')) ."'";

        $query2 = "SELECT CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp WHERE is_active = 1 AND emp.emp_id not in ({$emp_ids})";//
        $emp_list2 = Yii::app()->db->createCommand($query2)->queryAll();
        foreach ($emp_list2 as $key => $value) {

            $message = "";
            $message .= "<br>";
            $message .= "<b>Dear " . $value['emp_name'] . ",</b> <br><br>";
            $message .= "You missed to fill daily status of the week. <br>";
            $message .= "Please fill your daily status. <br><br>";
            $message .= "<br><br>";
            $message .= "Regards,";
            $message .= "<br>Infinity Team";
            $message ."<BR /><br />";
            $from = "support@infinitylabs.in";
            $from_name = "Infinity Support";
            $to = array();
            $cc = array();
            $bcc = array();
//            $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
//            $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
//            $value['email']="aashay.t@infintylabs.in";
            $to[] = array("email" => $value['email'], "name" => $value['emp_name']);
            $cc[] = array("email" => "sachin.k@infinitylabs.in", "name" => "sachin Kudtarkar");
            $subject = "Weekly Status Reminder - " . $value['emp_name'];
//            echo $message;
           echo CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
//           break;
        }
    }

    public function sendStatusReport() {
       echo "\n\r run cron \n\r";
       $selecting_date = date('Y-m-d', strtotime("monday this week"));
        $query_str = "";
        for ($k = 0; $k < 6; $k++) {
            $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
            $date = date("Y-m-d", $generte_date);
            $query_str_array[] = "DATE(dc.day) = '" . $date . "'";
        }
        //echo '<pre>';
        foreach ($query_str_array as $key => $value) {
            $wk[$key] = explode(" ", $value);
            $week[] = $wk[$key][2];
        }
        $query_str = implode(" AND ", $query_str_array);

        $query_str = "(" . $query_str . ")";

        $query = "SELECT DATE(dc.day),CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp left join tbl_day_comment dc on emp.emp_id=dc.emp_id WHERE is_active = 1 AND " . $query_str . " group by emp_name"; //


        $emp_list = Yii::app()->db->createCommand($query)->queryAll();


        $emp_ids = "'" . implode(" ','", array_column($emp_list, 'emp_id')) . "'";
        $query2 = "SELECT CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp WHERE is_active = 1 AND emp.emp_id not in ({$emp_ids})"; //
        $emp_list2 = Yii::app()->db->createCommand($query2)->queryAll();

        $message = "";
        $message = "<table width='100%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  ><thead  bgcolor='#CD853F' align='center' style='color:white; '>";
        $message .="<tr><th bgcolor='#CD853F' align='center' style='color:white; '>Date </th>";
        $message .= "<th bgcolor='#CD853F' align='center' style='color:white; '>Resource Name</th></tr>";
        $message .= "</thead><tbody style='font-size:14px;'>";

        // $message .= "</tr>";
        foreach ($emp_list2 as $key => $value) {
            $message .= "<tr bgcolor='#FFDAB9' style='border-bottom: 1px solid #ccc;'><td>Not submitted weekly working status<br /></td>";
            $message .= "<td>" . $value['emp_name'] . "<br /></td></tr>";
        }
        // $message .= "</tr>";
        $message .= "</tbody></table>";

        $message .= "Regards,";
        $message .= "<br>RJIL Auto Team";
        $message . "<BR /><br />";

        $from = "support@infinitylabs.in";
        $from_name = "Infinity Support";
        $to = array();
        $cc = array("email" => "sachin.k@infinitylabs.in", "name" => "Sachin Kudtarkar");
        $bcc = array();
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
//            $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        //$to[] = array("email" => $value['email'], "name" => $value['emp_name']);
        $subject = "Not Submitted Weekly Status ";
        echo $message;
        //return $message;
         echo  CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);

    }

    public function getDifference($sub_project_id,$sub_task_id)
    {
        $time_diff = "SELECT BIG_SEC_TO_TIME((est_hrs*60)*60) as est_hrs, BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` ) ) ) AS utilized_hrs  from tbl_sub_task as st inner join tbl_day_comment dc on dc.stask_id = st.stask_id where sub_project_id = {$sub_project_id} and st.stask_id = {$sub_task_id}";

        $time_diff_hrs = Yii::app()->db->createCommand($time_diff)->queryRow();

        $difference = $this->calculateTimeDiff($time_diff_hrs['est_hrs'],$time_diff_hrs['utilized_hrs']);
        // $difference['difference'] = $time_diff_hrs['difference'];
        // $dif_exp = explode(":",$time_diff_hrs['difference']);
        // $difference['hours'] = $dif_exp[0];
        // $difference['mins'] = $dif_exp[1];
        // $difference['secs'] = $dif_exp[2];

        $difference['estimated'] = $time_diff_hrs['est_hrs'];
        // print_r(explode(":",$time_diff_hrs['difference'])[0]);die;

        return $difference;
    }

    public function calculateTimeDiff($time1,$time2)
    {
        // 10:30:00 - 1:20:00
        // $difference = 0;
        $hours = 0;
        $mins = 0;

        $time1_arr = explode(':',$time1);
        $time2_arr = explode(':',$time2);
        // echo '<pre>';
        // print_r($time1_arr);
        // print_r($time2_arr);
        $hours = $time1_arr[0] - $time2_arr[0];

        if($time2_arr[1] > 0)
        {
            $mins = 60 - $time2_arr[1];
            $hours--;
        }
        
        $difference['hours'] = ($hours < 0) ? 00 : $hours;
        $difference['mins'] = $mins;
        // $difference['secs'] = $secs;
        $difference['difference'] = sprintf("%02d",$hours).":".sprintf("%02d",$mins);
        
        return $difference;

    }

    public function getUserTimesheetDetails()
    {
        $emp_id = Yii::app()->session['login']['user_id'];
       
        $tasks = Yii::app()->db->createCommand("
            select project_name,sub_project_name,sub_task_name,st.stask_id,dc.day,dc.updated_at,
            est_hrs,
            BIG_SEC_TO_TIME(est_hrs*60*60) as est_time,
            TIME_FORMAT(IF (SUM( BIG_TIME_TO_SEC( `hours` )) > 0, BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ), '00:00' ), '%H:%i')as utilized_hrs,
            TIME_FORMAT(TIMEDIFF(BIG_SEC_TO_TIME(est_hrs*60*60), BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) )), '%H:%i') as remaining_hours
            from tbl_sub_task st
            left join tbl_sub_project sp on sp.spid = st.sub_project_id
            left join tbl_project_management pm on pm.pid = st.project_id
            left join tbl_day_comment dc on dc.stask_id = st.stask_id 
            where st.emp_id = {$emp_id} 
            group by dc.stask_id
            #having remaining_hours > '00:00' OR utilized_hrs = '00:00:00'
            #order by stask_id,day desc
            ")->queryAll();
            // print_r($tasks);die;
            return $tasks;
    }

    public function checkTodayUserComment($comment, $emp_id)
    {
        return Yii::app()->db->createCommand("select count(*) as count from tbl_day_comment where emp_id = {$emp_id} and stask_id = {$comment[0]} and id = {$comment[1]} and day = CURDATE();")->queryRow();
    }

    public function checkUserHasTask($comment, $emp_id)
    {
        return Yii::app()->db->createCommand("select count(*) as count from tbl_sub_task where emp_id = {$emp_id} and stask_id = {$comment[0]};")->queryRow();
    }

    public function updateUserTimesheet($comment, $emp_id, $data)
    {
        $updated_by = date('Y-m-d H:i:s');
        $hours = sprintf('%02d',$data['hours']).':'.sprintf('%02d',$data['mins']).':00';
        $update = Yii::app()->db->createCommand("update tbl_day_comment set hours = '{$hours}', comment = '{$data["comment"]} ', updated_by = {$emp_id}, updated_at = '{$updated_by}' where emp_id = {$emp_id} and stask_id = {$comment[0]} and id = {$comment[1]} and day = CURDATE();")->execute();

        if($update)
        {
            $taskStatus['message'] = $data['task_name'].' was updated successfully';
            $taskStatus['status'] = 'Success!';
            return $taskStatus;
        }else{
            $taskStatus['message'] = $data['task_name'].' could not be updated successfully';
            $taskStatus['status'] = 'Error!';
            return $taskStatus;
        }
    }

     public function insertUserTimesheet($comment, $emp_id, $data)
    {
        $hours = sprintf('%02d',$data['hours']).':'.sprintf('%02d',$data['mins']).':00';
        $sub_task_details = Yii::app()->db->createCommand("select project_id,sub_project_id,stask_id from tbl_sub_task where stask_id = {$comment[0]}")->queryRow();
        $model = new DayComment;
        $model->day = date('Y-m-d');
        $model->hours = $hours;
        $model->comment = $data["comment"];
        $model->emp_id = $emp_id;
        $model->created_by = $emp_id;
        $model->created_by = $emp_id;
        $model->created_at = date('Y-m-d H:i:s');
        $model->pid = $sub_task_details['project_id'];
        $model->spid = $sub_task_details['sub_project_id'];
        $model->stask_id = $comment[0];
        $model->logged_hrs = $hours;
        $model->is_submitted = 0;
        $model->shift = 1;
        $model->save();
        if($model->save(false))
        {
            $taskStatus['message'] = $data['task_name'].' was updated successfully';
            $taskStatus['status'] = 'Success!';
            return $taskStatus;
        }else{
            $taskStatus['message'] = $data['task_name'].' was not be updated successfully';
            $taskStatus['status'] = 'Error!';
            return $taskStatus;
        }
    }

    public function checkHoursLessThanRemain($stask_id,$today_hours)
    {

        $emp_id = Yii::app()->session['login']['user_id'];
        
        return Yii::app()->db->createCommand("select st.stask_id,
           #TIME_FORMAT(TIMEDIFF(TIMEDIFF(BIG_SEC_TO_TIME(est_hrs*60*60), IF(BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) <> '',BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ),'00:00:00')), '{$today_hours}'), '%H:%i:%s') as ssdas,
           #IF(TIME_FORMAT(TIMEDIFF(TIMEDIFF(BIG_SEC_TO_TIME(est_hrs*60*60), IF(BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) <> '',BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ),'00:00:00')), '{$today_hours}'), '%H:%i:%s') >= '00:00:00', true, false) as result
            IF(TIME_FORMAT(TIMEDIFF(BIG_SEC_TO_TIME(est_hrs*60*60),ADDTIME(IF(BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ) <> '',BIG_SEC_TO_TIME( SUM( BIG_TIME_TO_SEC( `hours` )) ),'00:00:00'), '{$today_hours}')), '%H:%i:%s') >= '00:00:00', true, false) as result
            from tbl_sub_task st
            left join tbl_day_comment dc on dc.stask_id = st.stask_id 
            where st.emp_id = {$emp_id} and st.stask_id={$stask_id} and dc.day <> CURDATE()
            group by dc.stask_id;")->queryRow();

    }
}
