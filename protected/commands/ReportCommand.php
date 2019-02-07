<?php
/**
 * Description of LoginStatusCommand
 *
 * @author Sachin Kudtarkar <sachin.k@infinitylabs.in>
 */
class ReportCommand extends CConsoleCommand
{
    public function actionLoginReport()
    {
        $today = date('Y-m-d');
//      echo 'asdasdas';die;
        $start_date = $end_dat = '';
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] && isset($_REQUEST['end_date']) && $_REQUEST['end_date']) {
            $start_date = $_REQUEST['start_date'];
            $end_date = $_REQUEST['end_date'];
            $condition = ' where todaydate between "' . $start_date . '" and "' . $end_date . '" ';
            $subject = "Employee Login Time Report From $start_date to $end_date";
            $file_name = "Login-Time-Report-From-" . $start_date . "-To-" . $end_date . ".xls";
            $text = "from $start_date to $end_date";
        } else {
            $condition = " where todaydate = '$today'";
            $subject = "Employee Login Time Report for $today";
            $file_name = "Login-Time-Report-" . $today . ".xls";
            $text = "for $today";
        }
        $sql = "select concat(t1.first_name,' ',t1.last_name) as username ,t.todaydate,t.in_time,t.out_time
                from tbl_capture_img t
                inner join tbl_employee t1 on t1.emp_id = t.user_id
                {$condition} order by t.user_id";
//        $sql = " select CONCAT(first_name,' ',last_name) as name ,in_time,out_time,todaydate from tbl_capture_img as t inner join tbl_employee as em on (emp_id = user_id) {$condition} order by in_time asc";
        $row = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($row)) {
            $data = array();
            foreach ($row as $key => $val) {
                $data[$key]['username'] = $val['username'];
                $data[$key]['date'] = $val['todaydate'];
                $data[$key]['in'] = $val['in_time'];
                $data[$key]['out'] = $val['out_time'];
                if (!empty($val['in_time']) && !empty($val['out_time']) && $val['out_time'] != '00:00:00' && $val['in_time'] != '00:00:00') {
                    $data[$key]['total'] = $this->getTimeDiff($val['in_time'], $val['out_time']);
                } else {
                    $data[$key]['total'] = "00:00:00";
                }
            }
            $header = array('Employee Name', 'Date', 'In Time', 'Out Time', 'Total Hours');
            //$file_name = "Login-Time-Report-". $today . ".xls";
            $attachment_path = "/var/www/html/timesheet/tm_reports/";
            $file_name1 = CommonUtility::generateExcelSaveFileOnServer($header, $data, $file_name, $attachment_path);
            $destination_path = $attachment_path . $file_name;
            $to_name = "Reema Dhanwani";
//            $to = "reema.dhanwani@infinitylabs.in";
           //  $to[] = "mudliyarp@hcl.com";
  //         $cc[] = "pm-cnaap@infinitylabs.in";
//            $bcc[] = "tirthesh.trivedi@infinitylabs.in";
  //          $bcc[] = "mudliyarp@hcl.com";
            //$cc = [];
                $to[] = array("email" => "reema.dhanwani@infinitylabs.in", "name" => "Reema Dhanwani");
                $cc[] = array("email" => "tirthesh.trivedi@infinitylabs.in", "name" => "Tirthesh");
                $cc[] = array("email" => "pm-cnaap@infinitylabs.in", "name" => "PM");
        //      $bcc[] = array("email" => "tirthesh.trivedi@infinitylabs.in", "name" => "Tirthesh Trivedi");
                $cc[] = array("email" => "mudliyarp@hcl.com", "name" => "Prabhakar Mudliyar");
            $from = "support@cnaap.net";
            $from_name = "CNAAP TEAM";
            $message = 'Team,'
                . ''
                . '<p>The attached document contains the list of all employee\'s login time & logout time ' . $text
                . '<p><p><br/><br/>'
                . '<p>Thanks & Regards,</p>'
                . '<p>CNAAP Team</p>';
            $result = CommonUtility::sendmailWithAttachment($to, $to_name, $from, $from_name, $subject, $message, $destination_path, $file_name, $cc);
            if ($result) {
                $link = 'http://$_SERVER["HTTP_HOST"]/timesheet/tm_reports/' . $file_name1;
                echo "<h3>Email has been sent successfully with the report as attachment, to download the report click on the link:. <a href='{$link}' download>Download</a></h3>";
                //@unlink("/tmp/" .$file_name);
            } else {
                echo "Failed\n";
            }
        }
    }
    function getTimeDiff($dtime,$atime)
    {
        $nextDay = $dtime>$atime?1:0;
        $dep = explode(':',$dtime);
        $arr = explode(':',$atime);
        $diff = abs(mktime($dep[0],$dep[1],0,date('n'),date('j'),date('y'))-mktime($arr[0],$arr[1],0,date('n'),date('j')+$nextDay,date('y')));
        $hours = floor($diff/(60*60));
        $mins = floor(($diff-($hours*60*60))/(60));
        $secs = floor(($diff-(($hours*60*60)+($mins*60))));
        if(strlen($hours)<2){$hours="0".$hours;}
        if(strlen($mins)<2){$mins="0".$mins;}
        if(strlen($secs)<2){$secs="0".$secs;}
        return $hours.':'.$mins.':'.$secs;
    }
}
