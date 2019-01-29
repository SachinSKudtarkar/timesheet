<?php
class CaptureImageController extends Controller {
    public $layout = '//layouts/column1';
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'create','addremoveuser','SendMajorActivityEmail','DailyLoginStatus','DailyLoginTimeReport','LoginTimeReport'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $today_date = date('Y-m-d');
        $model = new TblCaptureImg();
        $id = Yii::app()->session['login']['user_id'];
        $succ_msg = '';
        $this->render('index', array('model' => $model, 'message' => $succ_msg));
    }
    public function actionCreate() {
        $model = new TblCaptureImg();
        $fname = '';
        $intime = '';
        $outtime = '';
        $in_img = '';
        $out_img = '';
        $sr_no = '';
        $succ_msg = '';
        $TotalHours = '';
        $id = Yii::app()->session['login']['user_id'];
        $emp = Employee::model()->findByPk($id);
        $u_email = $emp->email;
        $last_name = $emp->last_name;
        // catch and convert the canvas into .png image
        //$bpath = Yii::app()->getBasePath();
        $bpath = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR;
        //$bpath = str_replace('protected', '', $bpath);
        $img_name = $id . '_' . date('dmH_i_s') . '.png';
        $db_img_path = 'in_out_img/' . $id . '_' . date('dmH_i_s') . '.png';
        $path = $bpath . $db_img_path;
        $img = $_POST['capture_img'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        if (isset($_POST['listname']))
        {
            // check message if IN option is selected          
            if ($_POST['listname'] == '0') {
                $intime = date('H:i:s');
                $in_img = $db_img_path;
            }
            // chech if OUT option is selected 
            if ($_POST['listname'] == '1') {
                $outtime = date('H:i:s');
                $out_img = $db_img_path;
            }
            
            $user_name = Yii::app()->session['login']['first_name'];
            $model->user_id = $id;
            $model->user_name = $user_name;
            $tdate = date('Y-m-d');
            $model->todaydate = $tdate;
            $message = '';
            $cc = array(
                array('email' => "pm-cnaap@infinitylabs.in", 'name' => "PM"),
                array('email' => "reema.dhanwani@infinitylabs.in", 'name' => "Reema Dhanwani"),
                array('email' => "sameer.joshi@infinitylabs.in", 'name' => "Sameer Joshi"),
            );
            $subject = 'CNAAP Team attendance - Employee - ' . $user_name;
            $sql = "SELECT * FROM tbl_capture_img WHERE user_id =$id AND todaydate = '$tdate'";
            //echo  $sql;exit;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            //print_r($row);exit;
            $sr_no = $row['sr_no'];
            //Check If the user record is present for the same date
            if (empty($row))
            {

                $model->in_time = $intime;
                $model->out_time = $outtime;
                $model->in_image_url = $in_img;
                $model->out_image_url = $out_img;
              // echo "<pre>"; print_r($model);exit;
                if ($model->save())
                {
                    $succ_msg.='Save Successfully.';
                    $file = $path;
                    $success = file_put_contents($file, $data);
                    if ($success) 
                    {
                        $message = "<table style='border-collapse:collapse;border-spacing:0;'>
                                               <tr><th style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;' colspan='3'>$user_name $last_name</th></tr>
                                               <tr><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Date</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>In time</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Out Time</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Total Hours</td></tr>
                                               <tr><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$tdate</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$intime</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$outtime</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$TotalHours</td></tr>
                                           </table>";
                        //$result=CommonUtility::sendmail('smita.dhore@infinitylabs.in', 'Smita', $u_email, $user_name, 'Attendance In Out', $message, null, null, null);                                   
                        $result = CommonUtility::sendmailWithAttachment($u_email, $user_name, 'support@cnaap.net', 'CNAAP Team', $subject, $message, $file, $img_name, $cc);
                       
                       $result = 1;
                        //
                            if ($result)
                            {
                                 $query = "UPDATE tbl_employee SET is_timesheet= '0' WHERE emp_id= '{$id}'";
                                $update_query = Yii::app()->db->createCommand($query)->execute();
                                
                                Yii::app()->user->setFlash('success', "Email Sent.");
                                $this->redirect(array('captureimage/index'));
                            } else {
                                Yii::app()->user->setFlash('error', "Unable to send email");
                                $this->redirect(array('captureimage/index'));
                            }
                        
                    }
                    else
                    {
                        Yii::app()->user->setFlash('error', "File Not Saved.");
                        $this->redirect(array('captureimage/index'));
                    }
                } 
                else
                {
                        Yii::app()->user->setFlash('error', "Data Not Saved.");
                        $this->redirect(array('captureimage/index'));
                }

              //  exit;
            } 
            else
            {

              // echo $emp['is_timesheet']."-".$row['in_time'];exit;
               // if( $emp['is_timesheet'] == 0 && $row['in_time'] == '00:00:00') --To remove strict timesheet fill
               if( $emp['is_timesheet'] == 0 && $row['in_time'] != '00:00:00')
               {
                if (Yii::app()->request->isAjaxRequest) {
                    echo CJSON::encode(array(
                        'status' => 'failure',
                        'html' => $this->renderPartial('_form_dialog', array(), true)));
                    exit;
                }
               }else{
                $user = $model->findByPk($sr_no);
                // check if in time is filled up
                if ($row['in_time'] == '00:00:00') {
                    $user->in_time = $intime;
                    $user->in_image_url = $in_img;
                }
                // check if out time is filled up
                if ($row['out_time'] == '00:00:00') {
                    $user->out_time = $outtime;
                    $user->out_image_url = $out_img;
                }
                if($user->out_time != "" && $user->out_time != "00:00:00"){   
                    $TotalHours = $this->getTimeDiff($user->in_time,$user->out_time);
                }
                if ($user->update())
                {
                    $succ_msg.='Save Successfully.';
                    $file = $path;
                    $rin = $row['in_time'];
                    $success = file_put_contents($file, $data);
                    if ($success)
                    {
                        $message = "<table style='border-collapse:collapse;border-spacing:0;'>
                                               <tr><th style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;' colspan='4'>$user_name $last_name</th></tr>
                                               <tr><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Date</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>In time</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Out Time</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>Total Hours</td></tr>
                                               <tr><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$tdate</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$rin</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$outtime</td><td style='vertical-align:top;font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;'>$TotalHours</td></tr>
                                           </table>";
                        $result = CommonUtility::sendmailWithAttachment($u_email, $user_name, 'support@cnaap.net', 'CNAAP Team', $subject, $message, $file, $img_name, $cc);
                       
                       $result = 1;
                        if ($result)
                        {
                          Yii::app()->user->setFlash('success', "Email Sent.");
                          $this->redirect(array('captureimage/index'));
                          
                        } 
                        else
                        {
                            Yii::app()->user->setFlash('error', "Unable to send email.");
                            $this->redirect(array('captureimage/index'));
                        }
                     }
                     else
                     {
                         Yii::app()->user->setFlash('error', "File Not Saved.");
                         $this->redirect(array('captureimage/index'));
                     }
                     
                }
                else
                {
                    Yii::app()->user->setFlash('error', "Data Not Saved.");
                    $this->redirect(array('captureimage/index'));
                }
                   $this->redirect(array('captureimage/index'));
            }
        }
        $this->redirect(array('captureimage/index'));
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
    function actionaddremoveuser()
    {
        if(isset($_GET['add']) && !empty($_GET['add'])){
            $sql  = "INSERT INTO tbl_employee_facelogin(email) values ('{$_GET['add']}')";
            Yii::app()->db->createCommand($sql)->execute();
            Yii::app()->user->setFlash('success', "User Added.");
        }else{
            if(isset($_GET['remove']) && !empty($_GET['remove'])){
                $user=Yii::app()->db->createCommand()->delete('tbl_employee_facelogin', "email='{$_GET['remove']}'");
                Yii::app()->user->setFlash('success', "User Removed.");
            }
            else{
                Yii::app()->user->setFlash('error', "User not updated for facelogin.");
            }            
        }
        $this->redirect(array('captureimage/index'));
    }
    public function actionSendMajorActivityEmail() {
        $master_data = array();
        $today_date = date('Y-m-d');        
        /******************* device management Logic Starts here  *******************/
        $device_mgmt_sql_ondate = "select type as device_type,count(id) as total_count from tbl_all_devices where created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) group by type";
        $device_mgmt_data_ondate = Yii::app()->db->createCommand($device_mgmt_sql_ondate)->queryAll();
        $master_data['devicemgmt_ONDATE'] = $master_data['devicemgmt_TILLDATE'] = array();
        foreach($device_mgmt_data_ondate as $key => $val){
            $master_data['devicemgmt_ONDATE'][$val['device_type']] = $val['total_count'];
        }
            (isset($master_data['devicemgmt_ONDATE']['AG1']))? ($master_data['devicemgmt_ONDATE']['AG1'] = $master_data['devicemgmt_ONDATE']['AG1']): ($master_data['devicemgmt_ONDATE']['AG1'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['AG2']))? ($master_data['devicemgmt_ONDATE']['AG2'] = $master_data['devicemgmt_ONDATE']['AG2']): ($master_data['devicemgmt_ONDATE']['AG2'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['AG3']))? ($master_data['devicemgmt_ONDATE']['AG3'] = $master_data['devicemgmt_ONDATE']['AG3'] ): ($master_data['devicemgmt_ONDATE']['AG3'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['AMR']))? ($master_data['devicemgmt_ONDATE']['AMR'] = $master_data['devicemgmt_ONDATE']['AMR']): ($master_data['devicemgmt_ONDATE']['AMR'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['CRR']))? ($master_data['devicemgmt_ONDATE']['CRR'] = $master_data['devicemgmt_ONDATE']['CRR']): ($master_data['devicemgmt_ONDATE']['CRR'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['CSS']))? ($master_data['devicemgmt_ONDATE']['CSS'] = $master_data['devicemgmt_ONDATE']['CSS']): ($master_data['devicemgmt_ONDATE']['CSS'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['Dcncore']))? ($master_data['devicemgmt_ONDATE']['Dcncore'] = $master_data['devicemgmt_ONDATE']['Dcncore']): ($master_data['devicemgmt_ONDATE']['Dcncore'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['DCNWAN']))? ($master_data['devicemgmt_ONDATE']['DCNWAN'] = $master_data['devicemgmt_ONDATE']['DCNWAN']): ($master_data['devicemgmt_ONDATE']['DCNWAN'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['IBR']))? ($master_data['devicemgmt_ONDATE']['IBR'] = $master_data['devicemgmt_ONDATE']['IBR']): ($master_data['devicemgmt_ONDATE']['IBR'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['IGW']))? ($master_data['devicemgmt_ONDATE']['IGW'] = $master_data['devicemgmt_ONDATE']['IGW']): ($master_data['devicemgmt_ONDATE']['IGW'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['IPSLA']))? ($master_data['devicemgmt_ONDATE']['IPSLA'] = $master_data['devicemgmt_ONDATE']['IPSLA']): ($master_data['devicemgmt_ONDATE']['IPSLA'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['IRR']))? ($master_data['devicemgmt_ONDATE']['IRR'] = $master_data['devicemgmt_ONDATE']['IRR']): ($master_data['devicemgmt_ONDATE']['IRR'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['Nexus']))? ($master_data['devicemgmt_ONDATE']['Nexus'] = $master_data['devicemgmt_ONDATE']['Nexus']): ($master_data['devicemgmt_ONDATE']['Nexus'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['RTBH']))? ($master_data['devicemgmt_ONDATE']['RTBH'] = $master_data['devicemgmt_ONDATE']['RTBH']): ($master_data['devicemgmt_ONDATE']['RTBH'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['SAR']))? ($master_data['devicemgmt_ONDATE']['SAR'] = $master_data['devicemgmt_ONDATE']['SAR']): ($master_data['devicemgmt_ONDATE']['SAR'] = '0'); 
            (isset($master_data['devicemgmt_ONDATE']['URR']))? ($master_data['devicemgmt_ONDATE']['URR'] = $master_data['devicemgmt_ONDATE']['URR']): ($master_data['devicemgmt_ONDATE']['URR'] = '0'); 
            
        $device_mgmt_sql_tilldate = "select type as device_type,count(id) as total_count from tbl_all_devices group by type";
        $device_mgmt_data_tilldate = Yii::app()->db->createCommand($device_mgmt_sql_tilldate)->queryAll();
        foreach($device_mgmt_data_tilldate as $key => $val){
            $master_data['devicemgmt_TILLDATE'][$val['device_type']] = $val['total_count'];
        }
            (isset($master_data['devicemgmt_TILLDATE']['AG1']))? ($master_data['devicemgmt_TILLDATE']['AG1'] = $master_data['devicemgmt_TILLDATE']['AG1']): ($master_data['devicemgmt_TILLDATE']['AG1'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['AG2']))? ($master_data['devicemgmt_TILLDATE']['AG2'] = $master_data['devicemgmt_TILLDATE']['AG2']): ($master_data['devicemgmt_TILLDATE']['AG2'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['AG3']))? ($master_data['devicemgmt_TILLDATE']['AG3'] = $master_data['devicemgmt_TILLDATE']['AG3']): ($master_data['devicemgmt_TILLDATE']['AG3'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['AMR']))? ($master_data['devicemgmt_TILLDATE']['AMR'] = $master_data['devicemgmt_TILLDATE']['AMR']): ($master_data['devicemgmt_TILLDATE']['AMR'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['CRR']))? ($master_data['devicemgmt_TILLDATE']['CRR'] = $master_data['devicemgmt_TILLDATE']['CRR']): ($master_data['devicemgmt_TILLDATE']['CRR'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['CSS']))? ($master_data['devicemgmt_TILLDATE']['CSS'] = $master_data['devicemgmt_TILLDATE']['CSS']): ($master_data['devicemgmt_TILLDATE']['CSS'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['Dcncore']))? ($master_data['devicemgmt_TILLDATE']['Dcncore'] = $master_data['devicemgmt_TILLDATE']['Dcncore']): ($master_data['devicemgmt_TILLDATE']['Dcncore'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['DCNWAN']))? ($master_data['devicemgmt_TILLDATE']['DCNWAN'] = $master_data['devicemgmt_TILLDATE']['DCNWAN']): ($master_data['devicemgmt_TILLDATE']['DCNWAN'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['IBR']))? ($master_data['devicemgmt_TILLDATE']['IBR'] = $master_data['devicemgmt_TILLDATE']['IBR']): ($master_data['devicemgmt_TILLDATE']['IBR'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['IGW']))? ($master_data['devicemgmt_TILLDATE']['IGW'] = $master_data['devicemgmt_TILLDATE']['IGW']): ($master_data['devicemgmt_TILLDATE']['IGW'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['IPSLA']))? ($master_data['devicemgmt_TILLDATE']['IPSLA'] = $master_data['devicemgmt_TILLDATE']['IPSLA']): ($master_data['devicemgmt_TILLDATE']['IPSLA'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['IRR']))? ($master_data['devicemgmt_TILLDATE']['IRR'] = $master_data['devicemgmt_TILLDATE']['IRR']): ($master_data['devicemgmt_TILLDATE']['IRR'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['Nexus']))? ($master_data['devicemgmt_TILLDATE']['Nexus'] = $master_data['devicemgmt_TILLDATE']['Nexus']): ($master_data['devicemgmt_TILLDATE']['Nexus'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['RTBH']))? ($master_data['devicemgmt_TILLDATE']['RTBH'] = $master_data['devicemgmt_TILLDATE']['RTBH']): ($master_data['devicemgmt_TILLDATE']['RTBH'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['SAR']))? ($master_data['devicemgmt_TILLDATE']['SAR'] = $master_data['devicemgmt_TILLDATE']['SAR']): ($master_data['devicemgmt_TILLDATE']['SAR'] = '0'); 
            (isset($master_data['devicemgmt_TILLDATE']['URR']))? ($master_data['devicemgmt_TILLDATE']['URR'] = $master_data['devicemgmt_TILLDATE']['URR']): ($master_data['devicemgmt_TILLDATE']['URR'] = '0'); 
        /******************* device management Logic Ends here  *******************/
        /******************* CSS Nip Logic Starts here  *******************/
        $CSS_NDD_sql = "SELECT  count(DISTINCT host_name) as cssnip_date
                FROM ndd_output_master t
                INNER JOIN ndd_request_master AS nrm 
                ON (nrm.sapid = t.enode_b_sapid AND t.request_id = nrm.request_id AND nrm.is_disabled = 0  )  
                where t.pdf_done =  1 AND delivered_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR)";
                //UNION ALL
        $CSS_NDD_sql1 = "SELECT  count(DISTINCT host_name) as cssnip_date
                FROM ndd_output_master t
                INNER JOIN ndd_request_master AS nrm 
                ON (nrm.sapid = t.enode_b_sapid AND t.request_id = nrm.request_id AND nrm.is_disabled = 0  )  
                where t.pdf_done =  1 AND DATE(delivered_at) <= '".$today_date."'";
        
        $CSS_NDD_data = Yii::app()->db->createCommand($CSS_NDD_sql)->queryAll();
        $master_data['CSS_NIP_ONDATE'] = $CSS_NDD_data[0]['cssnip_date'];
        $CSS_NDD_data1 = Yii::app()->db->createCommand($CSS_NDD_sql1)->queryAll();
        $master_data['CSS_NIP_TILLDATE'] = $CSS_NDD_data1[0]['cssnip_date'];
        /******************* CSS Nip Logic Ends here  *******************/
        /******************* Metro Ag1 Nip Logic Starts here  *******************/
        $Metro_Ag1_nip_sql = "SELECT COUNT(DISTINCT(host_name)) as metroag1nip
                            FROM ndd_mag1_outputmaster AS mag1 
                            WHERE mag1.pdf_done = 1 AND mag1.is_active = 1 AND mag1.modified_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR)
                            union all
                            SELECT COUNT(DISTINCT(host_name)) as metroag1nip
                            FROM ndd_mag1_outputmaster AS mag1 
                            WHERE mag1.pdf_done = 1 AND mag1.is_active = 1 AND DATE(mag1.modified_at) <= DATE(now()) ";
        $Metro_Ag1_nip_data = Yii::app()->db->createCommand($Metro_Ag1_nip_sql)->queryAll();
        $master_data['Metro_Ag1_nip_ONDATE'] = $Metro_Ag1_nip_data[0]['metroag1nip'];
        $master_data['Metro_Ag1_nip_TILLDATE'] = $Metro_Ag1_nip_data[1]['metroag1nip'];
        
        /******************* Metro Ag1 Nip Logic Ends here  *******************/
        /******************* NLD Ag1 Nip Logic Starts here  *******************/
        $NLD_Ag1_nip_sql = "SELECT Count(DISTINCT(ag1om.hostname)) as nldag1nip FROM ndd_ag1_outputmaster AS ag1om
                            INNER JOIN ndd_ag1_input AS ag1i
                            ON ag1om.input_id = ag1i.id
                            WHERE ag1om.pdf_done = 1 AND ag1i.is_active = 1 AND ag1om.modified_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR)
                            union all
                            SELECT Count(DISTINCT(ag1om.hostname)) as nldag1nip FROM ndd_ag1_outputmaster AS ag1om
                            INNER JOIN ndd_ag1_input AS ag1i
                            ON ag1om.input_id = ag1i.id
                            WHERE ag1om.pdf_done = 1 AND ag1i.is_active = 1 AND DATE(ag1om.modified_at)<=DATE(now())";
        $NLD_Ag1_nip_data = Yii::app()->db->createCommand($NLD_Ag1_nip_sql)->queryAll();
        $master_data['NLD_Ag1_nip_ONDATE'] = $NLD_Ag1_nip_data[0]['nldag1nip'];
        $master_data['NLD_Ag1_nip_TILLDATE'] = $NLD_Ag1_nip_data[1]['nldag1nip'];
        
        /******************* NLD Ag1 Nip Logic Ends here  *******************/
        /******************* Cisco L2 Switch Nip Logic Starts here  *******************/
        $cisco_l2switch_nip_sql = "select count(id) as ciscol2switchnip from ndd_l2switch_outputmaster where is_active = 1 and created_date >= DATE_SUB(NOW(),INTERVAL 24 HOUR)
                            union all
                            select count(id) as ciscol2switchnip from ndd_l2switch_outputmaster where is_active = 1 and created_date <= DATE(now())";
        $cisco_l2switch_nip_data = Yii::app()->db->createCommand($cisco_l2switch_nip_sql)->queryAll();
        $master_data['cisco_l2switch_nip_ONDATE'] = $cisco_l2switch_nip_data[0]['ciscol2switchnip'];
        $master_data['cisco_l2switch_nip_TILLDATE'] = $cisco_l2switch_nip_data[1]['ciscol2switchnip'];
        
        /******************* Cisco L2 Switch Nip Logic Ends here  *******************/
        /******************* L3 Switch NIP Logic Starts here  *******************/
        $l3switch_nip_sql = "select count(id) as count from ndd_l3router_outputmaster where is_active = 1
                           union all
                           select count(id) as count from ndd_l3router_outputmaster where is_active = 1 and created_date >= DATE_SUB(NOW(),INTERVAL 24 HOUR)";
        $l3switch_nip_data = Yii::app()->db->createCommand($l3switch_nip_sql)->queryAll();
        $master_data['l3switch_nip_TILLDATE'] = $l3switch_nip_data[0]['count'];
        $master_data['l3switch_nip_ONDATE'] = $l3switch_nip_data[1]['count'];
        
        /******************* L3 Switch NIP Logic Ends here  *******************/
        /******************* Atp1b Approved Logic Starts here  *******************/
        $tilldate_approvedsites_sql1 = "select count(distinct host_name) as total_cnt, device_type from tbl_1b_batch_status where final_status=2 group by device_type
                                        union all
                                        select count(distinct host_name) as total_cnt, device_type from tbl_l2_1b_batch_status where final_status=2 group by device_type";
        $tilldate_approvedsites_data1 = Yii::app()->db->createCommand($tilldate_approvedsites_sql1)->queryAll();
        foreach($tilldate_approvedsites_data1 as $key => $approved_sites1){
            $master_data['atp1b_approvedsites_TILLDATE'][$approved_sites1['device_type']] = $approved_sites1['total_cnt'];        
}
        $ondate_approvedsites_sql1 = "select count(distinct t.host_name) as total_cnt, t.device_type  from tbl_1b_batch_status as t Inner join tbl_1b_qa_manager as qa_mag ON (t.area_id = qa_mag.area_id AND t.batch_no = qa_mag.batch_no) where qa_mag.emp_response_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND t.final_status=2 group by t.device_type
                                        union all
                                        select count(distinct t.host_name) as total_cnt, t.device_type from tbl_l2_1b_batch_status as t Inner join tbl_l2_1b_qa_manager as qa_mag ON (t.area_id = qa_mag.area_id AND t.batch_no = qa_mag.batch_no) where qa_mag.emp_response_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND t.final_status=2 group by t.device_type";
        $ondate_approvedsites_data2 = Yii::app()->db->createCommand($ondate_approvedsites_sql1)->queryAll();
        foreach($ondate_approvedsites_data2 as $key => $approved_sites2){
            $master_data['atp1b_approvedsites_ONDATE'][$approved_sites2['device_type']] = $approved_sites2['total_cnt'];        
        }
        /******************* Atp1b Approved Logic Ends here  *******************/
        /******************* Atp1b submitted Sites Logic Starts here  *******************/
        $tilldate_submittedsites_sql = "select sum(t.total_cnt) as total_cnt1 ,t.device_type as device_type1 from (
                                            select count(distinct host_name) as total_cnt, device_type from tbl_1b_rejected_batch group by device_type
                                            union all
                                            select count(distinct host_name) as total_cnt, device_type from tbl_1b_batch_status where batch_no !=0 group by device_type
                                            union all
                                            select count(distinct host_name) as total_cnt, device_type from tbl_l2_1b_rejected_batch group by device_type
                                            union all
                                            select count(distinct host_name) as total_cnt, device_type from tbl_l2_1b_batch_status where batch_no !=0 group by device_type
                                        ) as t group by t.device_type";
        $tilldate_submittedsites_data = Yii::app()->db->createCommand($tilldate_submittedsites_sql)->queryAll();
        foreach($tilldate_submittedsites_data as $key1 => $submitted_sites1){
            $master_data['atp1b_submittedsites_TILLDATE'][$submitted_sites1['device_type1']] = $submitted_sites1['total_cnt1'];        
        } 
        $ondate_submittedsites_sql = "select sum(t.total_cnt),t.device_type from (
                                        select count(distinct t.host_name) as total_cnt, t.device_type from tbl_1b_batch_status as t where t.batch_ready_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND t.batch_no !=0 group by t.device_type
                                        union all
                                        select count(distinct t.host_name) as total_cnt, t.device_type from tbl_1b_rejected_batch as t where t.batch_ready_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) group by t.device_type
                                        union all
                                        select count(distinct host_name) as total_cnt, t.device_type from tbl_l2_1b_batch_status as t where t.batch_ready_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND t.batch_no !=0 group by t.device_type
                                        union all
                                        select count(distinct host_name) as total_cnt, t.device_type from tbl_l2_1b_rejected_batch as t where t.batch_ready_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR) group by t.device_type
                                    ) as t group by t.device_type";
        $ondate_submittedsites_data = Yii::app()->db->createCommand($ondate_submittedsites_sql)->queryAll();
        foreach($ondate_submittedsites_data as $key2 => $submitted_sites2){
            $master_data['atp1b_submittedsites_ONDATE'][$submitted_sites2['device_type']] = $submitted_sites2['total_cnt'];        
        }
        /******************* Atp1b submitted Sites Logic Ends here  *******************/
        /******************* Service NIP Logic Starts here  *******************/
        $services_nip_ondate_total = $services_nip_tilldate_total = 0;
        $wifi_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_wifi_automation WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['wifi_service_ONDATE'] = $wifi_service_ondate[0]['count']; $services_nip_ondate_total += $wifi_service_ondate[0]['count'];                        
        $wifi_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_wifi_automation WHERE is_active=1")->queryAll();
        $master_data['wifi_service_TILLDATE'] = $wifi_service_tilldate[0]['count']; $services_nip_tilldate_total+= $wifi_service_tilldate[0]['count'];                        
        
        $ndd4k_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_4kwifi_automation WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['ndd4k_service_ONDATE'] = $ndd4k_service_ondate[0]['count']; $services_nip_ondate_total+= $ndd4k_service_ondate[0]['count'];                        
        $ndd4k_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_4kwifi_automation WHERE is_active=1")->queryAll();
        $master_data['ndd4k_service_TILLDATE'] = $ndd4k_service_tilldate[0]['count'];   $services_nip_tilldate_total+= $ndd4k_service_tilldate[0]['count'];                        
        
        $nddill_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_ill_automation WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['nddill_service_ONDATE'] = $nddill_service_ondate[0]['count'];  $services_nip_ondate_total+= $nddill_service_ondate[0]['count'];                        
        $nddill_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_ill_automation WHERE is_active=1")->queryAll();
        $master_data['nddill_service_TILLDATE'] = $nddill_service_tilldate[0]['count']; $services_nip_tilldate_total+= $nddill_service_tilldate[0]['count'];                        
        
        $nddmp_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_mp_police WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['nddmp_service_ONDATE'] = $nddmp_service_ondate[0]['count'];  $services_nip_ondate_total+= $nddmp_service_ondate[0]['count'];                        
        $nddmp_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_jiocentre_input WHERE status=1")->queryAll();
        $master_data['nddmp_service_TILLDATE'] = $nddmp_service_tilldate[0]['count'];  $services_nip_tilldate_total+= $nddmp_service_tilldate[0]['count'];                        
        
        $nddjio_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_jiocentre_input WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND status=1")->queryAll();
        $master_data['nddjio_service_ONDATE'] = $nddjio_service_ondate[0]['count']; $services_nip_ondate_total+= $nddjio_service_ondate[0]['count'];                        
        $nddjio_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_jiocentre_input WHERE status=1")->queryAll();
        $master_data['nddjio_service_TILLDATE'] = $nddjio_service_tilldate[0]['count'];  $services_nip_tilldate_total+= $nddjio_service_tilldate[0]['count'];                        

        $nddcell_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_smallcell_l2switch WHERE created_date >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND status=1")->queryAll();
        $master_data['nddcell_service_ONDATE'] = $nddcell_service_ondate[0]['count']; $services_nip_ondate_total+= $nddcell_service_ondate[0]['count'];                        
        $nddcell_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_smallcell_l2switch WHERE status=1")->queryAll();
        $master_data['nddcell_service_TILLDATE'] = $nddcell_service_tilldate[0]['count'];  $services_nip_tilldate_total+= $nddcell_service_tilldate[0]['count'];                        
        
        $nddmpls_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_mpls_tp_requestmaster WHERE created_date >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['nddmpls_service_ONDATE'] = $nddmpls_service_ondate[0]['count']; $services_nip_ondate_total+= $nddmpls_service_ondate[0]['count'];                        
        $nddmpls_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_mpls_tp_requestmaster WHERE is_active=1")->queryAll();
        $master_data['nddmpls_service_TILLDATE'] = $nddmpls_service_tilldate[0]['count'];  $services_nip_tilldate_total+= $nddmpls_service_tilldate[0]['count'];                        

        $nddcall_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_jio_call_centre_input WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND status=1")->queryAll();
        $master_data['nddcall_service_ONDATE'] = $nddcall_service_ondate[0]['count'];  $services_nip_ondate_total+= $nddcall_service_ondate[0]['count'];                        
        $nddcall_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_jio_call_centre_input WHERE status=1")->queryAll();
        $master_data['nddcall_service_TILLDATE'] = $nddcall_service_tilldate[0]['count']; $services_nip_tilldate_total+= $nddcall_service_tilldate[0]['count'];                        

        $nddl2vpn_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_l2vpn_jharkhand WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['nddl2vpn_service_ONDATE'] = $nddl2vpn_service_ondate[0]['count']; $services_nip_ondate_total+= $nddl2vpn_service_ondate[0]['count'];                        
        $nddl2vpn_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') FROM ndd_l2vpn_jharkhand WHERE is_active=1")->queryAll();
        $master_data['nddl2vpn_service_TILLDATE'] = $nddl2vpn_service_tilldate[0]['count']; $services_nip_tilldate_total+= $nddl2vpn_service_tilldate[0]['count'];                        

        $nddap_service_ondate = Yii::app()->db->createCommand("SELECT COUNT('id') as count FROM ndd_ap_police WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND is_active=1")->queryAll();
        $master_data['nddap_service_ONDATE'] = $nddap_service_ondate[0]['count'];  $services_nip_ondate_total+= $nddap_service_ondate[0]['count'];                        
        $nddap_service_tilldate = Yii::app()->db->createCommand("SELECT COUNT('id') FROM ndd_ap_police WHERE is_active=1")->queryAll();
        $master_data['nddap_service_TILLDATE'] = $nddap_service_tilldate[0]['count'];  $services_nip_tilldate_total+= $nddap_service_tilldate[0]['count'];                        
        /******************* Service NIP Logic Ends here  *******************/
        /**************ZTP Status Logic Starts HEre **************/
        $ztp_status_sql = "SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `as`.`ztp_flag` = 1
                           union all
                           SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `as`.`status`= '1' AND `zd`.`post_log` = 1 AND `zd`.`is_active` = 1 
                           union all 
                           SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `as`.`status` != '1'"; 
        $ztp_status_tilldate = Yii::app()->db->createCommand($ztp_status_sql)->queryAll();
        $master_data['ztp_requested_TILLDATE'] = $ztp_status_tilldate[0]['successcount'];
        $master_data['ztp_completed_TILLDATE'] = $ztp_status_tilldate[1]['successcount'];
        $master_data['ztp_rejected_TILLDATE'] = $ztp_status_tilldate[2]['successcount'];
        
        $ztp_status_sql2 = "SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `as`.`ztp_flag` = 1
                           union all
                           SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`date_completed` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `as`.`status`= '1' AND `zd`.`post_log` = 1 AND `zd`.`is_active` = 1 
                           union all 
                           SELECT count(DISTINCT(`zd`.`sap_id`)) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`date_completed` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `as`.`status` != '1'"; 
        $ztp_status_ondate = Yii::app()->db->createCommand($ztp_status_sql2)->queryAll();
        $master_data['ztp_requested_ONDATE'] = $ztp_status_ondate[0]['successcount'];
        $master_data['ztp_completed_ONDATE'] = $ztp_status_ondate[1]['successcount'];
        $master_data['ztp_rejected_ONDATE'] = $ztp_status_ondate[2]['successcount'];
        
        /**************ZTP Status Logic Ends HEre **************/
        /********************Device Integration logic starts Here *********************/
        /*$normal_int_status_sql = "SELECT COUNT(DISTINCT(`t`.`ip_address`)) AS `successcount` FROM `tbl_ip_master` AS `t` "
                . "INNER JOIN `tbl_assigned_sites` AS `tu` ON (`t`.`id` = `tu`.`site_id`) WHERE `tu`.`ztp_flag` != 1 AND `tu`.`status` = '1'"; 
        $normal_int_status_tilldate = Yii::app()->db->createCommand($normal_int_status_sql)->queryAll();
        $master_data['normal_int_completed_TILLDATE'] = $normal_int_status_tilldate[1]['successcount'];
        
        $normal_int_status_sql2 = "SELECT COUNT(DISTINCT(`t`.`ip_address`)) AS `successcount` FROM `tbl_ip_master` AS `t` INNER JOIN `tbl_assigned_sites` AS `tu` ON (`t`.`id` = `tu`.`site_id`) WHERE date(`date_completed`) = CURDATE() AND `tu`.`ztp_flag` != 1 AND `tu`.`status` = '1'"; 
        $normal_int_status_ondate = Yii::app()->db->createCommand($normal_int_status_sql2)->queryAll();
        $master_data['normal_int_completed_ONDATE'] = $normal_int_status_ondate[1]['successcount'];
        */
        $deviceint_sql = "select count(tipm.site_sap_name) as count , substring(hostname,9,3) as device_type
                        from tbl_assigned_sites t
                        INNER JOIN tbl_ip_master tipm ON (t.site_id = tipm.id and tipm.status != 3)
                        where t.status = '1' and substring(hostname,9,3) in ('ESR','ESS','PAR') group by substring(hostname,9,3)";
        $master_data['device_inte_total_ondate'] = $master_data['device_inte_total_tilldate'] = 0;
        
        $deviceint_tilldate = Yii::app()->db->createCommand($deviceint_sql)->queryAll();
        if(!empty($deviceint_tilldate)){
            foreach($deviceint_tilldate as $key => $val){
                $master_data['deviceint_TILLDATE'][$val['device_type']] = $val['count'];
                $master_data['device_inte_total_tilldate'] += $val['count'];
            }
        }
        
        $deviceintONDATE_sql = "select count(tipm.site_sap_name) as count , substring(hostname,9,3) as device_type
                        from tbl_assigned_sites t
                        INNER JOIN tbl_ip_master tipm ON (t.site_id = tipm.id and tipm.status != 3)
                        where t.status = '1' and substring(hostname,9,3) in ('ESR','ESS','PAR') AND t.date_completed >= DATE_SUB(NOW(),INTERVAL 24 HOUR)  group by substring(hostname,9,3)";
        $deviceint_ondate = Yii::app()->db->createCommand($deviceintONDATE_sql)->queryAll();
        if(!empty($deviceint_ondate)){
            foreach($deviceint_ondate as $key => $val){
                $master_data['deviceint_ONDATE'][$val['device_type']] = $val['count'];
                $master_data['device_inte_total_ondate'] += $val['count'];
            }
        }
        /********************Device Integration logic ends Here *********************/
        /********************ZTP Integration logic starts Here *********************/
        //1) Upconfig Till Date
        $sql1 = "SELECT count(`zd`.`up_config`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`up_config` = 1 AND `zd`.`is_active` = 1";
        //2) File Config Till Date
        $sql2 = "SELECT count(`zd`.`file_copy`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`file_copy` = 1 AND `zd`.`is_active` = 1";
        //3) DHCP Binding Till Date
        $sql3 = "SELECT count(`zd`.`dhcp_binding`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`dhcp_binding` = 1 AND `zd`.`is_active` = 1";
        //4) NIP Replace Till Date
        $sql4 = "SELECT count(`zd`.`nip_replace`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`is_active` = 1";
        //5) NOC Replace Till Date
        $sql5 = "SELECT count(`zd`.`noc`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`is_active` = 1";
        //6) Image Upgrade Till Date
        $sql6 = "SELECT count(`zd`.`image_upgrade`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`image_upgrade` = 1 AND `zd`.`is_active` = 1";
        //7) Post Log Till Date
        $sql7 = "SELECT count(`zd`.`post_log`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`image_upgrade` = 1 AND `zd`.`post_log` = 1 AND `zd`.`is_active` = 1";
        //8) ATP1B Till Date
        $sql8 = "SELECT count(distinct(`hostname`)) as `successcount` from `tbl_atp1b_automation` where `collected_from` = 'LOG' AND `parsing_status` = 3";
        //9) Total Sites Requested Till Date
        $sql9  = "SELECT CAST(SUM(IF((is_qued = 0 AND ztp_flag=1),1,0))AS CHAR) as successcount FROM `tbl_assigned_sites`";
        //10) Total ZTP Ready Site Till Date
        $sql10 = "SELECT count(`zd`.`id`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id`) WHERE `zd`.`sap_id` != '' AND `zd`.`up_config` = 1 AND `zd`.`file_copy` = 1 AND `zd`.`is_active` = 1";
        //11) Total ZTP Rejected Sites Till Date
        $sql11 = "SELECT CAST(SUM(IF((status = '2' AND ztp_flag=1),1,0))AS CHAR) as rejection FROM `tbl_assigned_sites`";
        $ztpint_rejection_tilldate = Yii::app()->db->createCommand($sql11)->queryAll();
        $master_data['ztpint_rejection_TILLDATE'] = $ztpint_rejection_tilldate[0]['rejection'];
        
        $ztpint_tilldate_sql = $sql1." union all ".$sql2." union all ".$sql3." union all ".$sql4." union all ".$sql5." union all ".$sql6." union all ".$sql7." union all ".$sql8." union all ".$sql9." union all ".$sql10;
        $ztpint_tilldate = Yii::app()->db->createCommand($ztpint_tilldate_sql)->queryAll();
        $master_data['ztpint_uconfig_TILLDATE'] = $ztpint_tilldate[0]['successcount'];
        $master_data['ztpint_file_Config_TILLDATE'] = $ztpint_tilldate[1]['successcount'];
        $master_data['ztpint_DHCP_Binding_TILLDATE'] = $ztpint_tilldate[2]['successcount'];
        $master_data['ztpint_NIP_Replace_TILLDATE'] = $ztpint_tilldate[3]['successcount'];
        $master_data['ztpint_NOC_Replace_TILLDATE'] = $ztpint_tilldate[4]['successcount'];
        $master_data['ztpint_Image_Upgrade_TILLDATE'] = $ztpint_tilldate[5]['successcount'];
        $master_data['ztpint_Post_Log_TILLDATE'] = $ztpint_tilldate[6]['successcount'];
        $master_data['ztpint_ATP1B_TILLDATE'] = $ztpint_tilldate[7]['successcount'];
        $master_data['ztpint_Total_Sites_Requested_TILLDATE'] = $ztpint_tilldate[8]['successcount'];
        $master_data['ztpint_Total_ZTP_Ready_Site_TILLDATE'] = $ztpint_tilldate[9]['successcount'];
        //Till Date Logic Ends here  
        
        //1) Upconfig Last 24 Hours
        $sql11 = "SELECT count(`zd`.`up_config`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`up_config_datetime`) = CURDATE() AND `zd`.`up_config` = 1 AND `zd`.`is_active` = 1";
        //2) File Config Last 24 Hours
        $sql12 = "SELECT count(`zd`.`file_copy`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`file_copy_datetime`) = CURDATE() AND `zd`.`file_copy` = 1 AND `zd`.`is_active` = 1";
        //3) DHCP Binding Last 24 Hours
        $sql13 = "SELECT count(`zd`.`dhcp_binding`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`dhcp_binding_datetime`) = CURDATE() AND `zd`.`dhcp_binding` = 1 AND `zd`.`is_active` = 1";
        //4) NIP Replace Last 24 Hours
        $sql14 = "SELECT count(`zd`.`nip_replace`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`dhcp_binding_datetime`) = CURDATE() AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`is_active` = 1";
        //5) NOC Replace Last 24 Hours
        $sql15 = "SELECT count(`zd`.`noc`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`dhcp_binding_datetime`) = CURDATE() AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`is_active` = 1";
        //6) Image Upgrade Last 24 Hours
        $sql16 = "SELECT count(`zd`.`image_upgrade`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`dhcp_binding_datetime`) = CURDATE() AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`image_upgrade` = 1 AND `zd`.`is_active` = 1";
        //7) Post Log Last 24 Hours
        $sql17 = "SELECT count(`zd`.`post_log`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND DATE(`zd`.`dhcp_binding_datetime`) = CURDATE() AND `zd`.`dhcp_binding` = 1 AND `zd`.`nip_replace` = 1 AND `zd`.`noc` = 1 AND `zd`.`image_upgrade` = 1 AND `zd`.`post_log` = 1 AND `zd`.`is_active` = 1";
        //8) ATP1B Last 24 Hours
        $sql18 = "SELECT count(distinct(`hostname`)) as `successcount` from `tbl_atp1b_automation` where `request_at` >= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND `collected_from` = 'LOG' AND `parsing_status` = 3";
        //9) Total Sites Requested Last 24 Hours
        $sql19  = "SELECT CAST(SUM(IF((DATE(qued_datetime) = CURDATE() AND is_qued = 0 AND ztp_flag=1),1,0))AS CHAR) as successcount FROM `tbl_assigned_sites`";
        //10) Total ZTP Ready Site Last 24 Hours
        $sql110 = "SELECT count(`zd`.`id`) as `successcount` FROM `tbl_ztp_details` as `zd` INNER JOIN `tbl_assigned_sites` as `as` ON(`zd`.`assigned_site_id` = `as`.`id` AND `as`.`scheduled_date` >= DATE_SUB(NOW(),INTERVAL 24 HOUR)) WHERE `zd`.`sap_id` != '' AND `zd`.`up_config` = 1 AND `zd`.`file_copy` = 1 AND `zd`.`is_active` = 1";
        //11) Total ZTP Rejected Sites Last 24 Hours
        
        $sql111 = "SELECT CAST(SUM(IF((status = '2' AND DATE(date_modified) = CURDATE() AND ztp_flag=1),1,0))AS CHAR) as rejection FROM `tbl_assigned_sites`";
        $ztpint_rejection_ondate = Yii::app()->db->createCommand($sql111)->queryAll();
        $master_data['ztpint_rejection_ONDATE'] = $ztpint_rejection_ondate[0]['rejection'];
        
        $ztpint_ondate_sql = $sql11." union all ".$sql12." union all ".$sql13." union all ".$sql14." union all ".$sql15." union all ".$sql16." union all ".$sql17." union all ".$sql18." union all ".$sql19." union all ".$sql110;
        $ztpint_ondate = Yii::app()->db->createCommand($ztpint_ondate_sql)->queryAll();
        $master_data['ztpint_uconfig_ONDATE'] = $ztpint_ondate[0]['successcount'];
        $master_data['ztpint_file_Config_ONDATE'] = $ztpint_ondate[1]['successcount'];
        $master_data['ztpint_DHCP_Binding_ONDATE'] = $ztpint_ondate[2]['successcount'];
        $master_data['ztpint_NIP_Replace_ONDATE'] = $ztpint_ondate[3]['successcount'];
        $master_data['ztpint_NOC_Replace_ONDATE'] = $ztpint_ondate[4]['successcount'];
        $master_data['ztpint_Image_Upgrade_ONDATE'] = $ztpint_ondate[5]['successcount'];
        $master_data['ztpint_Post_Log_ONDATE'] = $ztpint_ondate[6]['successcount'];
        $master_data['ztpint_ATP1B_ONDATE'] = $ztpint_ondate[7]['successcount'];
        $master_data['ztpint_Total_Sites_Requested_ONDATE'] = $ztpint_ondate[8]['successcount'];
        $master_data['ztpint_Total_ZTP_Ready_Site_ONDATE'] = $ztpint_ondate[9]['successcount'];
        //On Date Logic Ends here  
        /********************Device Integration logic ends Here *********************/
        /********************Hotoed Device Logic Starts Here ***********************/
        $ag1_hoto_sql = "SELECT count(distinct(t.id)) as count FROM hoto_rediness_final_hottoed as t , hoto_rediness_final_batches as b WHERE t.batch_name = b.batch_name AND b.batch_status = 'Approved' AND substr(b.batch_name,1,3) = 'AG1'
                         union all SELECT count(distinct(t.id)) as count FROM hoto_rediness_final_hottoed as t , hoto_rediness_final_batches as b WHERE t.batch_name = b.batch_name AND b.batch_status = 'Approved' AND substr(b.batch_name,1,3) = 'AG1' AND b.batch_created_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR)";
        $ag1_hoto_data = Yii::app()->db->createCommand($ag1_hoto_sql)->queryAll();
        $master_data['ag1_hoto_TILLDATE'] = $ag1_hoto_data[0]['count'];
        $master_data['ag1_hoto_ONDATE'] = $ag1_hoto_data[1]['count'];
        
        $css_hoto_sql = "SELECT count(distinct(t.id)) as count FROM hoto_rediness_final_hottoed as t , hoto_rediness_final_batches as b WHERE t.batch_name = b.batch_name AND b.batch_status = 'Approved' AND substr(b.batch_name,1,3) = 'CSS'
                         union all SELECT count(distinct(t.id)) as count FROM hoto_rediness_final_hottoed as t , hoto_rediness_final_batches as b WHERE t.batch_name = b.batch_name AND b.batch_status = 'Approved' AND substr(b.batch_name,1,3) = 'CSS' AND b.batch_created_dt >= DATE_SUB(NOW(),INTERVAL 24 HOUR)";
        $css_hoto_data = Yii::app()->db->createCommand($css_hoto_sql)->queryAll();
        $master_data['css_hoto_TILLDATE'] = $css_hoto_data[0]['count'];
        $master_data['css_hoto_ONDATE'] = $css_hoto_data[1]['count'];
        
        /********************Hotoed Device Logic Ends Here ***********************/
        $to = $cc = array();
        $message_HTML = $this->EmailMessageBody($master_data);
        CHelper::debug($message_HTML);
        $cc[] = array("email" => 'dhara.patel@infinitylabs.in', "name" => 'Dhara Patel');
        //$cc[] = array("email" => 'pm@infinitylabs.in');
        //$to[] = array("email" => 'kpanse@cisco.com', "name" => 'Krishnaji Panse');
        $subject = 'Major Activities In CNAAP';
        $from_name = 'CNAAP Team';
        $from = 'support@cnaap.net';
        //$result = CommonUtility::sendmail($to,null, $from, $from_name, $subject,$message_HTML,$cc);            
        if($result)
        {
            echo 'Email Sent';
        }
        else
        {
            echo 'Unable to send email';
        }
    }
    public function EmailMessageBody($master_data){
        //CHelper::debug($master_data);
        $message_HTML = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Email Templates</title>
</head>

<body style="background:#efefef; margin:0px; font-family:Verdana;">
    <div style="width:600px; margin:0 auto; font-size:14px;">
        <p style="margin:10px 0 0 0; padding:0px;">Hello All,</p>
        <p style="margin:8px 0 10px 0; padding:0px;">Please find major activities below : </p>
    </div>
    <div style="width:600px; margin:0 auto;">
        <div style="background:#3c6aa1; padding:5px;">
            <img src="https://www.cnaap.net/images/new-logo.png" width="30%" />
        </div>
        <table style="width:100%; border-spacing:0px; background: #fff; margin-bottom: 10px;">
            <tr>
                <td style="width:50%; vertical-align:text-top;">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Device Management</h5>
                                                <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                                                    <tr>
                                                        <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                                                        <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                                                        <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">AG1</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['AG1'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['AG1'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">AG2</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['AG2'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['AG2'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">AG3</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['AG3'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['AG3'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">AMR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['AMR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['AMR'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">CRR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['CRR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['CRR'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">CSS</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['CSS'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['CSS'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">Dcncore</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['Dcncore'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['Dcncore'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">DCNWAN</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['DCNWAN'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['DCNWAN'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">IBR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['IBR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['IBR'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">IGW</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['IGW'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['IGW'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">IPSLA</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['IPSLA'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['IPSLA'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">IRR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['IRR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['IRR'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">Nexus</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['Nexus'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['Nexus'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">RTBH</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['RTBH'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['RTBH'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">SAR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['SAR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['SAR'].'</td>
                                                    </tr>    
                                                    <tr>
                                                        <td style="border:1px solid #ddd; padding:2px;">URR</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_TILLDATE']['URR'].'</td>
                                                        <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['devicemgmt_ONDATE']['URR'].'</td>
                                                    </tr>    
                                                </table>                                               
                    </div>
                </td>
                <td style="width:50%; vertical-align:text-top;">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Service NIP</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Wifi</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['wifi_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['wifi_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Wifi 4k</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ndd4k_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ndd4k_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">ILL</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddill_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddill_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CCTV</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddmp_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddmp_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Jio Centre</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddjio_service_TILLNDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddjio_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Small Cell</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddcell_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddcell_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">MPLS-TP</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddmpls_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddmpls_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">JIO CALL & Verification Centre</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddcall_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddcall_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;"> L2 VPN</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddl2vpn_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddl2vpn_service_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;"> L3 VPN</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddap_service_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['nddap_service_ONDATE'].'</td>
                          </tr>                       
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        
        
        <table style="width:100%; border-spacing:0px; background: #fff; margin-bottom: 10px;">
            <tr>
                <td style="width:50%; vertical-align:text-top;">
                    <div style="padding:5px; clear:both;">
                                                <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Config Creation</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs</th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CSS NIP created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['CSS_NIP_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['CSS_NIP_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Metro ag1 NIP created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['Metro_Ag1_nip_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['Metro_Ag1_nip_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">NLD ag1 NIP created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['NLD_Ag1_nip_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['NLD_Ag1_nip_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">L3 switch NIP create3d</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['l3switch_nip_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['l3switch_nip_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Cisco L2 switch NIPs created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['cisco_l2switch_nip_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['cisco_l2switch_nip_ONDATE'].'</td>
                          </tr>
                          <!--tr>
                            <td style="border:1px solid #ddd; padding:2px;">Other L2 switch NIPs created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;"></td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;"></td>
                          </tr-->
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Services NIP created</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$services_nip_tilldate_total.'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$services_nip_ondate_total.'</td>
                          </tr>
                        </table>
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Device Integration </h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs</th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Device</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['device_inte_total_tilldate'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['device_inte_total_ondate'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CSS</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_TILLDATE']['ESR'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_ONDATE']['ESR'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">AG1</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_TILLDATE']['PAR'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_ONDATE']['PAR'].'</td>
                          </tr>
                          <!--tr>
                            <td style="border:1px solid #ddd; padding:2px;">Cisco L3 Switch </td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;"></td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;"></td>
                          </tr-->
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Cisco L2 Switch</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_TILLDATE']['ESS'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['deviceint_ONDATE']['ESS'].'</td>
                          </tr>
                        </table>
                    </div>
                </td>
                <td style="width:50%; vertical-align:text-top;">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">CSS Integration - 100% Automated</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Upcoming Requests</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_uconfig_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_uconfig_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">File Config</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_file_Config_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_file_Config_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Dhcp Binding Happened</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_DHCP_Binding_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_DHCP_Binding_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">NIP Replaced</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_NIP_Replace_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_NIP_Replace_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Sites Become Noc Reachable</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_NOC_Replace_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_NOC_Replace_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">IOS Upgraded</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Image_Upgrade_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Image_Upgrade_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Post Logs</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Post_Log_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Post_Log_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">ATP1B</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_ATP1B_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_ATP1B_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Total Sites Requested</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Total_Sites_Requested_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Total_Sites_Requested_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Total ZTP Ready Site</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Total_ZTP_Ready_Site_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_Total_ZTP_Ready_Site_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Total ZTP Rejected Sites</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_rejection_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztpint_rejection_ONDATE'].'</td>
                          </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">CSS Integration using automatic integration flow - non ztp </h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Requested</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_requested_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_requested_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Completed</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_completed_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_completed_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">Rejected</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_rejected_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ztp_rejected_ONDATE'].'</td>
                          </tr>                       
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        
        <table style="width:100%; border-spacing:0px; background: #fff; margin-bottom: 10px;">
            <tbody>
            <tr>
                <td style="width:50%;">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Atp1b Approved</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tbody><tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs</th>
                          </tr>
                                                  <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CSS</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['CSS'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['CSS'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">AG1</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['AG1'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['AG1'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">L3</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['L3_Device'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['L3_Device'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">901</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['901'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['901'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">RAD</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['RAD'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['RAD'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">VOLTEK</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['VOLTEK'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['VOLTEK'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">TECHROUTE</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_TILLDATE']['TECHROUTE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_approvedsites_ONDATE']['TECHROUTE'].'</td>
                          </tr>
                        </tbody></table>
                    </div>
                </td>
                <td style="width:50%; vertical-align:text-top;">
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Atp1b Submitted Sites</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tbody><tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CSS</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['CSS'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['CSS'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">AG1</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['AG1'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['AG1'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">L3</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['L3_Device'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['L3_Device'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">901</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['901'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['901'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">RAD</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['RAD'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['RAD'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">VOLTEK</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['VOLTEK'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['VOLTEK'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">TECHROUTE   </td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_TILLDATE']['TECHROUTE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['atp1b_submittedsites_ONDATE']['TECHROUTE'].'</td>
                          </tr>
                        </tbody></table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        
        
        
        <table style="width:100%; border-spacing:0px; background: #fff; margin-bottom: 10px;">
            <tbody>
            <tr>
                <td>
                    <div style="padding:5px; clear:both;">
                        <h5 style="padding:0px; margin:0px; background:#c4dffe; padding:5px;">Hotoed</h5>
                        <table style="border:1px solid #ddd; cellpadding:0; cellspacing:0; border-collapse: collapse; font-size:11px; width:100%;">
                          <tbody><tr>
                            <th style="border:1px solid #ddd; width:60%; padding:2px;"></th>
                            <th style="border:1px solid #ddd; width:20%; padding:4px;">Till Date</th>
                            <th style="border:1px solid #ddd; width:20%; padding:2px;">In Last 24hrs </th>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">CSS</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['css_hoto_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['css_hoto_ONDATE'].'</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">AG1</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ag1_hoto_TILLDATE'].'</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">'.$master_data['ag1_hoto_ONDATE'].'</td>
                          </tr>
                          <!--tr>
                            <td style="border:1px solid #ddd; padding:2px;">L3</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">7</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">8</td>
                          </tr>
                          <tr>
                            <td style="border:1px solid #ddd; padding:2px;">L2</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">7</td>
                            <td style="border:1px solid #ddd; padding:2px; text-align:center;">8</td>
                          </tr-->
                        </tbody></table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="width:600px; margin:0 auto; font-size:14px;">
        <p style="margin:10px 0 0 0; padding:0px;font-weight:bold;">Regards,</p>
        <p style="margin:2px 0 10px 0; padding:0px;font-weight:bold;">CINAAP Team</p>
        <p style="margin:2px 0 10px 0; padding:0px;font-weight:bold;">(Cisco Intelligent Network Automation and Analytics Platform)</p>
        <p style="text-align:center; font-size:12px; font-weight:bold; margin:10px 0; color: #a94442; background-color: #f2dede; padding:8px; border:1px solid #a94442;">*This is an auto generated email. PLEASE DO NOT REPLY TO THIS EMAIL.*</p>
    </div>

</body>
</html>
';
        return $message_HTML;
    } 
    public function actionDailyLoginTimeReport(){
        $hour = date('H');        
        $format = date('A');   
        if(isset($_REQUEST['date']) && $_REQUEST['date'] != ""){
            $date = trim($_REQUEST['date']);
        }
        if($format == "AM" && $hour < 2){
            $sql = "select concat(t1.first_name,' ',t1.last_name) as username ,t.todaydate,t.in_time,t.out_time 
                from tbl_capture_img t
                inner join tbl_employee t1 on t1.emp_id = t.user_id
                where t.todaydate = DATE_FORMAT( CURRENT_DATE - INTERVAL 1 DAY, '%Y-%m-%d' )
                order by t.user_id";                
        }else if($date != NULL){
            $sql = "select concat(t1.first_name,' ',t1.last_name) as username ,t.todaydate,t.in_time,t.out_time 
                from tbl_capture_img t
                inner join tbl_employee t1 on t1.emp_id = t.user_id
                where t.todaydate = '{$date}'
                order by t.user_id";
        }else{
            $sql = "select concat(t1.first_name,' ',t1.last_name) as username ,t.todaydate,t.in_time,t.out_time 
                from tbl_capture_img t
                inner join tbl_employee t1 on t1.emp_id = t.user_id
                where t.todaydate = DATE_FORMAT( CURRENT_DATE, '%Y-%m-%d' )
                order by t.user_id";                
        }             
        $row = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($row)){
            $data = array();
            foreach($row as $key => $val){
                $data[$key]['username'] = $val['username'];
                $data[$key]['date'] = $val['todaydate'];
                $data[$key]['in'] = $val['in_time'];
                $data[$key]['out'] = $val['out_time'];
                if(!empty($val['in_time']) && !empty($val['out_time']) && ($val['out_time'] != "00:00:00")){
                    $data[$key]['total'] = $this->getTimeDiff($val['in_time'],$val['out_time']);
                }else{
                    $data[$key]['total'] = "";
                }                
            }
            $header = array('Employee Name','Date','In Time','Out Time','Total Hours');
            $file_name = "Daily-Login-Time-Report-". $today . ".xls";
            $attachment_path = "/tmp/";            
            $file_name1 = CommonUtility::generateExcelSaveFileOnServer($header, $data, $file_name, $attachment_path);
            
            /************Download Excel Manually Start Here**********/
            if($date != NULL){
                ob_get_clean();
                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename={$file_name}.csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                $file = fopen('php://output', 'w');
                fputcsv($file, $header);
                foreach($data as $key => $val){
                        fputcsv($file, $val);
                    
                }                
                $file = fopen('php://output', 'w');
                @unlink("/tmp/" .$file_name);
                exit();
            }
            /************Download Excel Manually Ends Here**********/
            
            $destination_path = $attachment_path.$file_name;            
            $to_name = "Krishnaji Panse";
            $to = "kpanse@cisco.com";
            $cc = array(
                array('email' => "reema.dhanwani@infinitylabs.in", 'name' => "Reema Dhanwani"),
                array('email' => "vbollapr@cisco.com", 'name' => "Vijay Bollapragada"),
                array('email' => "ajbapna@cisco.com", 'name' => "Ajay Bapna"),
                array('email' => "sathisba@cisco.com", 'name' => "Sathish Balasubramanian"),
                array('email' => "pchitkar@cisco.com", 'name' => "Piyush Chitkara"),
                array('email' => "rramnath@cisco.com", 'name' => "Ramesh Ramnath"),
                array('email' => "sshelke@cisco.com", 'name' => "Sandeep Shelke"),
                array('email' => "sunilrpa@cisco.com", 'name' => "Sunil Patil"),
            );
            $from = "support@cnaap.net";
            $from_name = "CNAAP TEAM";
            $subject = "Employee Login Time Daily Report";
            $message = 'Hello ,'
                        . ''
                        . '<p>The attached document is the list of all employee\'s login time & logout time '
                        . '<p><p><br/><br/>'
                        . '<p>Thanks & Regards,</p>'
                        ;
            $result = CommonUtility::sendmailWithAttachment($to, $to_name, $from, $from_name, $subject, $message, $destination_path, $file_name, $cc);
            if ($result) {
                echo "Email Sent\n";
                @unlink("/tmp/" .$file_name);
            } else {
                echo "Failed\n";
            }
        }
    }
      
      public function  actionDailyLoginStatus(){
       ini_set('display_errors',1);
        error_reporting(E_ALL);
        $start_date = $end_dat ='';
        if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] && isset($_REQUEST['end_date']) && $_REQUEST['end_date']){
            $start_date = $_REQUEST['start_date'];
            $end_date = $_REQUEST['end_date'];
            $condition = ' where todaydate between "'.$start_date.'" and "'.$end_date.'" ';
        }else{
            $condition = " where todaydate >= CURDATE()";
        }

        $query = " select CONCAT(first_name,' ',last_name) as name ,in_time,out_time,todaydate from tbl_capture_img as t inner join tbl_employee as em on (emp_id = user_id) {$condition} order by in_time asc";
        
        $row = Yii::app()->db->createCommand($query)->queryAll();
        
        if(!empty($row)){
            
            $data = array();
            foreach($row as $key => $val){
                $data[$key]['username'] = $val['name'];
                $data[$key]['date'] = $val['todaydate'];
                $data[$key]['in'] = $val['in_time'];
                $data[$key]['out'] = $val['out_time'];
                // if(!empty($val['in_time']) && !empty($val['out_time'])){
                //     $data[$key]['total'] = $this->getTimeDiff($val['in_time'],$val['out_time']);
                // }else{
                //     $data[$key]['total'] = "";
                // }                
                
            }
            $header = array('Employee Name','Date','In Time','Out Time');
            $file_name = "Daily-Login-Time-Report-". $today . ".xls";
            $attachment_path = "/tmp/";            
            $file_name1 = CommonUtility::generateExcelSaveFileOnServer($header, $data, $file_name, $attachment_path);
            $destination_path = $attachment_path.$file_name;
            $to_name = "Reema Dhanwani";
            $to = "reema.dhanwani@infinitylabs.in";
            $bcc[] = array("email" => "tirthesh.trivedi@infinitylabs.in");
            $cc[] = array("email" => "pm-cnaap@infinitylabs.in");
            // $cc[] = array("email" => "reema.dhanwani@infinitylabs.in");
            $from = "support@cnaap.net";
            $from_name = "CNAAP TEAM";
            $subject = "Employee Login Time Daily Report";
            $message = 'Team,'
                        . ''
                        . '<p>Please find the attached document which includes the employees log in and log out status for the day '.date("d/m/Y")
                        . '<p><p><br/><br/>'
                        . '<p>Thanks & Regards,</p><p>CNAAP</'
                        ;
            $result = CommonUtility::sendmailWithAttachment($to, $to_name, $from, $from_name, $subject, $message, $destination_path, $file_name, $cc);
            if ($result) {
                echo "Email Sent\n";
                @unlink("/tmp/" .$file_name);
            } else {
                echo "Failed\n";
            }
        }
    }
    public function actionLoginTimeReport(){
        $today = date('Y-m-d');
                          
        $start_date = $end_dat ='';
        if(isset($_REQUEST['start_date']) && $_REQUEST['start_date'] && isset($_REQUEST['end_date']) && $_REQUEST['end_date']){
            $start_date = $_REQUEST['start_date'];
            $end_date = $_REQUEST['end_date'];
            $condition = ' where todaydate between "'.$start_date.'" and "'.$end_date.'" ';
            $subject = "Employee Login Time Report From $start_date to $end_date";
            $file_name = "Login-Time-Report-From-". $start_date . "-To-".$end_date.".xls";
            $text = "from $start_date to $end_date";
        }else{
            $condition = " where todaydate = '$today'";
         $subject = "Employee Login Time Report for $today";
         $file_name = "Login-Time-Report-". $today . ".xls";
         $text = "for $today";
        }


     $sql = "select concat(t1.first_name,' ',t1.last_name) as username ,t.todaydate,t.in_time,t.out_time 
                from tbl_capture_img t
                inner join tbl_employee t1 on t1.emp_id = t.user_id
                {$condition} order by t.user_id"; 
//        $sql = " select CONCAT(first_name,' ',last_name) as name ,in_time,out_time,todaydate from tbl_capture_img as t inner join tbl_employee as em on (emp_id = user_id) {$condition} order by in_time asc";

        $row = Yii::app()->db->createCommand($sql)->queryAll();

        if(!empty($row)){
            $data = array();
            foreach($row as $key => $val){
                $data[$key]['username'] = $val['username'];
                $data[$key]['date'] = $val['todaydate'];
                $data[$key]['in'] = $val['in_time'];
                $data[$key]['out'] = $val['out_time'];
                if(!empty($val['in_time']) && !empty($val['out_time'])){
                    $data[$key]['total'] = $this->getTimeDiff($val['in_time'],$val['out_time']);
                }else{
                    $data[$key]['total'] = "";
                }                
            }
            $header = array('Employee Name','Date','In Time','Out Time','Total Hours');
            //$file_name = "Login-Time-Report-". $today . ".xls";
            $attachment_path = "/var/www/html/timesheet/tm_reports/";            
            $file_name1 = CommonUtility::generateExcelSaveFileOnServer($header, $data, $file_name, $attachment_path);

            $destination_path = $attachment_path.$file_name;
            $to_name = "Reema Dhanwani";
            // $to = "reema.dhanwani@infinitylabs.in";
            $to = "mudliyarp@hcl.com";
            //$cc[] = array("email" => "pm-cnaap@infinitylabs.in");
            $bcc[] = array("email" => "tirthesh.trivedi@infinitylabs.in");
            $bcc[] = array("email" => "mudliyarp@hcl.com");
            $cc = [];
            $from = "support@cnaap.net";
            $from_name = "CNAAP TEAM";
            
            $message = 'Team,'
                        . ''
                        . '<p>The attached document contains the list of all employee\'s login time & logout time '.$text
                        . '<p><p><br/><br/>'
                        . '<p>Thanks & Regards,</p>'
                        ;
            $result = CommonUtility::sendmailWithAttachment($to, $to_name, $from, $from_name, $subject, $message, $destination_path, $file_name, $cc);
            if ($result) {
                $link = "http://$_SERVER[HTTP_HOST]/timesheet/tm_reports/".$file_name1;              
                echo "<h3>Email has been sent successfully with the report as attachment, to download the report click on the link:. <a href='{$link}' download>Download</a></h3>";

                //@unlink("/tmp/" .$file_name);
            } else {
                echo "Failed\n";
            }
        }
    }
}