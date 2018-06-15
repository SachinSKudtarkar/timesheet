<?php

/**
 * Description of SubmitStatusCommand
 *
 * @author Sachin Kudtarkar <sachin.k@infinitylabs.in>
 */
class SubmitStatusCommand extends CConsoleCommand {
    /* start worker for snmp commands */

    public function actionSendMail() {
        echo "run";
        ini_set('display_errors', 0);
        error_reporting(0);
        DayComment::model()->sendMailStatus();
    }

    public function actionSend() {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        echo "\n\r run cron \n\r";
        // $selecting_date = date('Y-m-d',strtotime('last monday -7 days'));
        $selecting_date = date('Y-m-d', strtotime("monday this week"));
        $query_str = "";
        for ($k = 0; $k < 6; $k++) {
            $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
            $date[] = date("Y-m-d", $generte_date);
          //  $query_str_array[] = "DATE(dc.day) = '" . $date . "'";
        }
     
//        $query_str = implode(" AND ", $query_str_array);
//        $query_str = "(" . $query_str . ")";
//       $query = "SELECT DATE(dc.day),CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp left join tbl_day_comment dc on emp.emp_id=dc.emp_id WHERE is_active = 1 AND " . $query_str." group by emp_name";//
     $query = "SELECT dc.id , DATE(dc.day),CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp 
                left join tbl_day_comment dc on emp.emp_id=dc.emp_id WHERE is_active = 1  AND  dc.day between '{$date[0]}' and  '{$date[5]}'
                group by emp_name;";

//AND is_submitted = 0
        $emp_list = Yii::app()->db->createCommand($query)->queryAll();
        $emp_ids = "'" .implode(" ','", array_column($emp_list, 'emp_id')) ."'";

        $query2 = "SELECT CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp WHERE is_active = 1 AND emp.emp_id  not in ({$emp_ids})";//
        
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
            $message . "<BR /><br />";
            $from = "support@infinitylabs.in";
            $from_name = "Infinity Support";
            $to = array();
            $cc = array();
            $bcc = array();
                //$to[] =  array("email" => "sachin.k@infinitylabs.in", "name" => "Sachin Kudtarkar");
//            $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
//            $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
            $to[] = array("email" => $value['email'], "name" => $value['emp_name']);
           // $cc[] = array("email" => "sachin.k@infinitylabs.in", "name" => "sachin Kudtarkar");
            $subject = "Weekly Status Reminder - " . $value['emp_name'];
//            echo $message;
            echo CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
//           break;
        }
    }

    public function actionsendStatusReport() {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        $selecting_date = date('Y-m-d', strtotime("monday this week"));
        $query_str = "";
        for ($k = 0; $k < 6; $k++) {
            $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
            $date[] = date("Y-m-d", $generte_date);
        }


        $query = "SELECT dc.id , DATE(dc.day),CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp 
                  left join tbl_day_comment dc on emp.emp_id=dc.emp_id WHERE is_active = 1  AND  dc.day between '{$date[0]}' and  '{$date[5]}'
                  group by emp_name;"; //

        $emp_list = Yii::app()->db->createCommand($query)->queryAll();
        $emp_ids = "'" . implode(" ','", array_column($emp_list, 'emp_id')) . "'";
        $query2 = "SELECT CONCAT(emp.first_name,' ',emp.last_name) as emp_name,emp.emp_id ,emp.email FROM tbl_employee emp WHERE is_active = 1  and emp.emp_id not in ({$emp_ids})"; //
        $emp_list2 = Yii::app()->db->createCommand($query2)->queryAll();

        $message = "";
        $message = "<table width='100%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  ><thead  bgcolor='#CD853F' align='center' style='color:white; '>";
        $message .="<tr><th bgcolor='#CD853F' align='center' style='color:white; '>Status </th>";
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
        $cc = array();
        $bcc = array();
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");
       // $to[] = array("email" => "umang.s@infinitylabs.in", "name" => "Umang T Shukla");
        $cc[] = array("email" => "sachin.k@infinitylabs.in", "name" => "Sachin Kudtarkar");
//            $to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        //$to[] = array("email" => $value['email'], "name" => $value['emp_name']);
        $subject = "Not Submitted Weekly Status ";
        echo $message;
        //return $message;
        echo CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null, $bcc);
    }
    
    public function actionDailyLoginStatus(){
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        $query ="select user_name,in_time,out_time,todaydate from tbl_capture_img where todaydate >= CURDATE()";
        $emp_list = Yii::app()->db->createCommand($query)->queryAll();
        
       

        $fp = fopen('file.csv', 'w');

        foreach ($emp_list as $fields) {
            fputcsv($fp, $fields);
        }

       
        fclose($fp);
    }

}
