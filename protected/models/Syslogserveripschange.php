<?php

/**
 * This is the model class for table "syslogserveripschange".
 *
 * The followings are the available columns in table 'syslogserveripschange':
 * @property integer $id
 * @property string $ip_address
 * @property string $hostname
 * @property string $site_sap_name
 * @property string $sys_cha_ip
 * @property integer $ip_found
 * @property string $sys_log_path
 * @property string $created_at
 * @property string $Status
 * @property string $status_at
 */
class Syslogserveripschange extends CActiveRecord {

    public $pool_ips;
    public $status;
    public $employee_name;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'syslogserveripschange';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('file_name', 'file', 'allowEmpty' => false, 'safe' => true, 'types' => 'xlsx, xls, xlsm,csv', 'on' => 'uploadCoreDevices'),
            array('eusername,epassword', 'required', 'on' => 'uploadCoreDevices'),
//            array('ip_address, hostname, site_sap_name, sys_cha_ip, ip_found, sys_log_path, created_at, Status, status_at', 'required'),
//            array('ip_found', 'numerical', 'integerOnly' => true),
//            array('ip_address, hostname, sys_cha_ip, Status', 'length', 'max' => 20),
//            array('site_sap_name', 'length', 'max' => 50),
//            array('sys_log_path', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, ip_address, hostname, site_sap_name, sys_cha_ip, ip_found, sys_log_path, created_at, Status,device_type, status_at ,request_id ,sequence_no,employee_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ip_address' => 'Ip Address',
            'hostname' => 'Hostname',
            'site_sap_name' => 'Site Sap Name',
            'sys_cha_ip' => 'Sys Cha Ip',
            'ip_found' => 'Ip Found',
            'sys_log_path' => 'Sys Log Path',
            'created_at' => 'Created At',
            'Status' => 'Status',
            'status_at' => 'Status At',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
//        $criteria->select  ="t.*,CONCAT(emp.first_name,' ',emp.last_name) as employee_name";
        $criteria->select = " t.*,CONCAT(emp.first_name,' ',emp.last_name) as employee_name,
                                     CASE Status
                                      WHEN 0 THEN 'Pending'
                                      WHEN '1' THEN 'Done'
                                      WHEN '2' THEN 'Not Done'
                                      WHEN '3' THEN 'Not Reachable'
                                      WHEN '4' THEN 'Connection time out'
                                      WHEN '5' THEN 'Master not connected'
                                      WHEN '8' THEN 'Done by Circle Code'
                                     ELSE '-' END as status 
                                     ,
                                     CASE device_type
                                      WHEN 'ESR' THEN 'CSS'
                                      WHEN 'PAR' THEN 'AG1'
                                      WHEN 'AAR' THEN 'AG2'
                                      WHEN 'CCR' THEN 'AG3' 
                                      WHEN 'CSR' THEN 'SAR'
                                      WHEN 'CRR' THEN 'CRR'
                                      WHEN 'URR' THEN 'URR'
                                      WHEN 'IAR' THEN 'IBR'
                                      WHEN 'CMR' THEN 'DCN Core'
                                      WHEN 'CAR' THEN 'DCN Edge'
                                      WHEN 'AMR' THEN 'AMR'
                                      WHEN 'IRR' THEN 'IRR'
                                      WHEN 'AUS' THEN 'AUS'
                                      WHEN 'CNR' THEN 'IPSLA'
                                      WHEN 'CAS' THEN 'Nexus' 
                                      WHEN 'CBR' THEN 'RTBH'
                                     ELSE device_type END as device_type 
                                  ";
//        $criteria->compare('id', $this->id);
        $criteria->compare('ip_address', $this->ip_address);
        $criteria->compare('hostname', $this->hostname, true);
        $criteria->compare('site_sap_name', $this->site_sap_name, true);
        $criteria->compare('sys_cha_ip', $this->sys_cha_ip, true);
        $criteria->compare('ip_found', $this->ip_found);
//        $criteria->compare('sys_log_path', $this->sys_log_path, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('Status', $this->Status, true);
        $criteria->compare('status_at', $this->status_at, true);
        $criteria->compare('device_type', $this->device_type, true);

        $criteria->compare('request_id', $this->request_id);
        $criteria->compare('sequence_no', $this->sequence_no);
        $criteria->compare('CONCAT(first_name," ",last_name)', $this->employee_name, true);
        $criteria->join = "LEFT JOIN tbl_employee emp on t.user_id=emp.emp_id";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
            'pagination' => array('pageSize' => 20,),
        ));
    }

    public function getLogStatus($data) {
        $status = '-';
        if ($data->Status == 1) {
//            $status = CHtml::link("Plan-vs-Build", Yii::app()->createUrl('Atp1bRequestMaster/ViewPlanVsBuild/id/' . $data->id));
            $status = "Done";
        } elseif ($data->Status == 2) {
            $status = "Not Done";
        } elseif ($data->Status == 3) {
            $status = "Not Reachable";
        } elseif ($data->Status == 4) {
            $status = "Connection time out";
        } elseif ($data->Status == 0) {
            $status = "Pending";
        } elseif ($data->Status == 5) {
            $status = "Master no reachable";
        }elseif ($data->Status == 8) {
            $status = "Done by Circle Code";
        }
        return $status;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Syslogserveripschange the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function filter_ip_address($pool_ips_Content, $device_type) {
        $array_data = $ip_details = $loopbackArray = $hostnamenotf = $duplicates_ips = $missmatch_date = $submitted = $ips = array();
        $user_id = Yii::app()->session['login']['user_id'];
        $pool_ips_Content = array_filter($pool_ips_Content, 'strlen');
        $regularExp_IP = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/";

        foreach ($pool_ips_Content as $value) {
            $value = trim($value);
            if ($value == "" && empty($value))
                continue;

            if (!preg_match($regularExp_IP, $value)) {
                $missmatch_date[] = $value;
            } else {
                if (!in_array($value, $ips)) {
                    $ips[] = $value;
                    if ($this->Check_ip_submit($value) == 0) {
                        $ip_details = $this->getIp_details($value, $device_type);
                        if (!empty($ip_details)) {
                            $ip_details['user_id'] = $user_id;
                            $ip_details['device_type'] = $device_type;
                            $loopbackArray[] = $ip_details;
                        } else {
                            $hostnamenotf[] = $value;
                        }
                    } else {
                        $submitted[] = $value;
                    }
                } else {
                    $duplicates_ips[] = $value;
                }
            }
        }

        $duplicates_ips = array_unique($duplicates_ips);
        $hostnamenotf = array_unique($hostnamenotf);
        $missmatch_date = array_unique($missmatch_date);
        $array_data = array('loopbacks' => $loopbackArray, 'not_found' => $hostnamenotf, 'duplicates' => $duplicates_ips, 'missmatch_date' => $missmatch_date, 'submitted' => $submitted);
//        CHelper::dump($array_data);
        return $array_data;
    }

    public function getIp_details($ip_address, $device_type) {
        $hostname = $circle_code = $site_sap_name = $primary_ips_state = $id = "";
        $In_circle_code = "'MU','MH','GO','KO','JH','BR','NE','DL','HP','UE','JK','KL','PB','HR','RJ','UW','OR','KA','AS','MP','CG','AP','TN','GJ','WB','PY'";
        $sql_query = "select ip_address,SUBSTRING(trim(site_sap_name),3,2) as circle_code,site_sap_name from tbl_ip_master where SUBSTRING(trim(site_sap_name),3,2) IN(" . $In_circle_code . ") AND SUBSTRING(trim(hostname),9,3) IN('{$device_type}') AND ip_address ='{$ip_address}' AND Status=1";
        $result = Yii::app()->db->createCommand($sql_query)->queryRow();
        return $result;
    }

    public function Check_ip_submit($ip_address) {
        $sql_query = "select count(*) as count from syslogserveripschange where ip_address ='{$ip_address}' AND Status = 1";
        $result = Yii::app()->db->createCommand($sql_query)->queryRow();
        return $result['count'];
    }

    public function getDeleteButton($model) {

        $id = $model['id'];
        $base_url = Yii::app()->request->baseUrl;
        if ($model['Status'] != 1) {
            $str = '<a class="delete" href="' . $base_url . '/syslogserveripschange/delete/' . $id . '" title="Delete">
        <img alt="Delete" src="' . $base_url . '/themes/cisco/img/delete.png">
</a>';
        }
        return $str;
    }

    public function sentForProcess($result) {
        $ip_address = $hostname = $circle_code = $site_sap_name = $primary_ips_state = $id = "";
//        $In_circle_code = "'MU','MH','GO','KO','JH','BR','NE','DL','HP','UE','JK','KL','PB','HR','RJ','UW','OR','KA','AS','MP','CG','AP','TN','GJ','WB','PY'";
        $primary_ips = array('MU' => "10.70.11.32", 'MH' => "10.70.56.49", 'GO' => "10.70.12.15", 'KO' => "10.70.64.24", 'JH' => "10.70.74.49", 'BR' => "10.70.74.49", 'NE' => "10.70.72.49", 'DL' => "10.70.32.25", 'HP' => "10.70.104.49", 'UE' => "10.70.48.49", 'JK' => "10.70.90.49", 'KL' => "10.70.76.45", 'PB' => "10.70.104.49", 'HR' => "10.70.104.49", 'RJ' => "10.70.92.45", 'UW' => "10.70.94.45", 'OR' => "10.70.88.45", 'KA' => "10.70.96.25", 'AS' => "10.70.72.49", 'MP' => "10.70.78.45", 'CG' => "10.70.78.45", 'AP' => "10.70.24.49", 'TN' => "10.70.81.47", 'GJ' => "10.70.16.113", 'WB' => "10.70.108.14",
            'PY' => "10.70.81.47");
        $request_id = date('d_m_Y') . "_" . uniqid();
        $seuence_no = 0;
        foreach ($result as $value) {
            $seuence_no = $seuence_no + 1;
            $ip_address = trim($value['ip_address']);
            $circle_code = trim($value['circle_code']);
            $site_sap_name = $value['site_sap_name'];
            $user_id = $value['user_id'];
            $device_type = $value['device_type'];
            /* Note :- for AUS , DCN devices pick syslog ip by given zone */
            if ($device_type == "CMR" || $device_type == "CAR" || $device_type == "AUS" || $device_type == "AMR") {
                if ($circle_code == "AS" || $circle_code == "BR" || $circle_code == "JH" || $circle_code == "KO" || $circle_code == "NE" || $circle_code == "OR" || $circle_code == "WB") {
                    $primary_ips_state = trim($primary_ips['KO']);
                } else if ($circle_code == "DL" || $circle_code == "HR" || $circle_code == "HP" || $circle_code == "JK" || $circle_code == "PB" || $circle_code == "RJ" || $circle_code == "UE" || $circle_code == "UW") {
                    $primary_ips_state = trim($primary_ips['DL']);
                } else if ($circle_code == "AP" || $circle_code == "KA" || $circle_code == "KL" || $circle_code == "TN") {
                    $primary_ips_state = trim($primary_ips['KA']);
                } else if ($circle_code == "CG" || $circle_code == "GO" || $circle_code == "GJ" || $circle_code == "MP" || $circle_code == "MH" || $circle_code == "MU") {
                    $primary_ips_state = trim($primary_ips['MU']);
                }
            } else {
                $primary_ips_state = trim($primary_ips[$circle_code]);
            }

            $username = $password = "";
            if (isset($value['eusername'])) {
                $username = trim($value['eusername']);
            }
            if (isset($value['epassword'])) {
                $password = trim($value['epassword']);
            }

//            $findIps = Syslogserveripschange::model()->find($criteria);
            if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state)) {
                $model = new Syslogserveripschange();
                $model->ip_address = $ip_address;
                $model->site_sap_name = $site_sap_name;
                $model->sys_cha_ip = $primary_ips_state;
                $model->created_at = new CDbExpression('NOW()');
                $model->request_id = $request_id;
                $model->sequence_no = $seuence_no;
                $model->user_id = $user_id;
                $model->device_type = $device_type;
                $model->Status = 0;
                $model->eusername = $username;
                $model->epassword = $password;
                $model->save(FALSE);
//                Syslogserveripschange::model()->SyslogLocalIPchange($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no );
                $gearman = new CSyslogServerChangeIpClient();
                $gearman->sendToWorker($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no, $device_type);
            }
        }
    }

    public function SyslogLocalIPchange($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no) {
        ini_set('display_errors', 0);
        error_reporting(0);
//         echo "<br /> ip==".$ip_address,"  circle code==" ,$circle_code, " sap nme==",$site_sap_name." primary ips ==". $primary_ips_state; 
        if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state)) {
            $commands = $datanew = array();
            $file_path = "/uploads/SyslogServerIPs/" . $ip_address . "__" . date('d_m_Y') . "__" . time() . ".txt";
            $filepath_steps_log = dirname(Yii::app()->basePath) . $file_path;
            $removable_logging_host_found = $adding_primary_ip_found = $need_to_hit_command = $hostname = "";
            $removable_logging_host = "logging host 10.70.4.8";
            $Status = 0;
            $check_configuration_lines = array();
            //===================================update into table===============================================//
            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->addCondition("request_id ='{$request_id}' AND sequence_no ='{$seuence_no}' AND ip_address = '{$ip_address}' ");
            $findIps = Syslogserveripschange::model()->find($criteria);
            if (!empty($findIps)) {
                $findIps->status_at = new CDbExpression('NOW()');
                $findIps->sys_log_path = $file_path;
                //========================================   END   =============================================//
                $content = "";
                $content.= "\r\n" . "Input Loopback0 IP : " . $ip_address;
//                require_once('telnet_new_class.php');
                require_once('telnet_new_tmp_class.php');
                $t = new TELNET();
                $time_start = microtime(true);
                $loopback = $ip_address;
                $content.= "\r\n" . "Checking Reachability";
                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                $connectLocal = $t->Connect($loopback);
                if ($connectLocal == 1) {
//                    $logArray = $t->LogIn();
                    $logArray = $t->LogInGeneric('rjilautonms', 'nms@#2015');
                    $logArrayText = implode('<br/>', $logArray);

                    $routernameLocal = array_pop($logArray);
                    if (strpos($routernameLocal, '>') !== false) {
                        $pass = "rjil123";
                        $t->GetOutputOf("en");
                        $logArray = $t->GetOutputOf($pass);
                    }
                    if (strpos($routernameLocal, '#') !== false) {
//                    $hostname = substr($routernameLocal, -15);
                        $hostname = substr($routernameLocal, 0, 15);
                        $content .= "\r\n\r\nSucessfully Telnet on {$loopback} with hostname {$hostname}";
                        $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        $content .= "\r\nCommand Name :</b> show startup-config | in logging";
                        $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        $data = $t->GetOutputOf("show startup-config | in logging");

                        $adding_ips = "logging host " . $primary_ips_state;
                        $check_configuration_lines[] = $adding_ips;
                        foreach ($data as $line) {
                            $line = preg_replace("/\s+/", " ", trim($line));
                            $content .="\r\n" . $line;
                            if ($removable_logging_host == strtolower($line)) {
                                $removable_logging_host_found = 1;
                            }
                            if ($adding_ips == strtolower($line)) {
                                $adding_primary_ip_found = 1;
                            }
                        }
                        if ($removable_logging_host_found == 1 && $adding_primary_ip_found != 1) {
                            $commands = array(
                                "conf t",
                                "no " . $removable_logging_host,
                                $adding_ips,
                                "end",
//                                "write"
                            );
                            $need_to_hit_command = 1;
                        } else if ($removable_logging_host_found != 1 && $adding_primary_ip_found != 1) {
                            $commands = array(
                                "conf t",
                                $adding_ips,
                                "end",
//                                "write"
                            );
                            $need_to_hit_command = 1;
                        } else if ($removable_logging_host_found == 1 && $adding_primary_ip_found == 1) {
                            $commands = array(
                                "conf t",
                                "no " . $removable_logging_host,
                                "end",
//                                "write"
                            );
                            $need_to_hit_command = 1;
                        }
//                        }


                        if ($need_to_hit_command == 1) {
                            $newCommands = implode("\n", $commands);
                            $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n Hit commands on device";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n" . $newCommands;
                            $content .="\r\n" . "write";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            $temp = $t->GetOutputOf($newCommands);

                            sleep(5);
                            $datanew = $t->GetOutputOf("sh run | in logging");

                            $found_configuration_lines = $remove_line_found = 0;
                            $found_key = -1;
                            $content .= "\r\n\r\nAfter configuring check the output of command";
                            $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .= "\r\nCommand Name :</b> sh run | in logging";
                            $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            foreach ($datanew as $line) {
                                $line = preg_replace("/\s+/", " ", trim($line));
                                $content .="\r\n" . $line;
                                if ($removable_logging_host == strtolower($line)) {
                                    $remove_line_found = 1;
                                }
                                $found_key = array_search($line, $check_configuration_lines);
                                if (($found_key >= 0) && (count($check_configuration_lines) > 0) && trim($found_key) != "") {
                                    unset($check_configuration_lines[$found_key]);
                                    $found_configuration_lines = $found_configuration_lines + 1;
                                    $found_key = -1;
                                }
                            }
                            $temp = $t->GetOutputOf("write");
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n Result of commands";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            if (count($check_configuration_lines) == 0 && $remove_line_found == 0) {
                                $content .= "\r\nConfiguration Done success fully";
                                $Status = 1;
                            } else {
                                $content .= "\r\nConfiguration Not done successfully";
                                $Status = 2;
                            }
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        } else {
                            $Status = 1;
                            $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n No need to change configuration";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        }
                        $t->GetOutputOf("exit");
                    } else {
                        $Status = 4;
                        $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
                    }
                } else {
                    $Status = 3;
                    $content .= "\r\nDevice not reachable<b/>";
                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                }
                $findIps->hostname = trim($hostname, "#");
                $findIps->Status = $Status;
                $findIps->save(FALSE);
                $this->writeLog($content, $filepath_steps_log);
            }
        }
    }

    //===============================================CRR ,CMR ,cbr,urr=====================================================================
    public function SyslogLocalIPchangeCRR_CMR($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no) {
        ini_set('display_errors', 0);
        error_reporting(0);
        if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state)) {
            $commands = $datanew = array();
            $file_path = "/uploads/SyslogServerIPs/" . $ip_address . "__" . date('d_m_Y') . "__" . time() . ".txt";
            $filepath_steps_log = dirname(Yii::app()->basePath) . $file_path;
            $removable_logging_host_found = $adding_primary_ip_found = $need_to_hit_command = $hostname = $eusername = $epassword = $adding_ips = "";
            $removable_logging_host = "logging host 10.70.4.8";
            $Status = 0;
            $check_configuration_lines = array();
            //===================================update into table===============================================//
            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->addCondition("request_id ='{$request_id}' AND sequence_no ='{$seuence_no}' AND ip_address = '{$ip_address}' ");
            $findIps = Syslogserveripschange::model()->find($criteria);

            if (!empty($findIps)) {
                $eusername = $findIps->eusername;
//                $eusername = "rjil";
                $epassword = base64_decode($findIps->epassword);
//                $epassword = "rjil123";
                $findIps->status_at = new CDbExpression('NOW()');
                $findIps->sys_log_path = $file_path;
                //========================================   END   =============================================//
                $content = "";
                $content.= "\r\n" . "Input Loopback0 IP : " . $ip_address;
//                require_once('telnet_new_class.php');
                require_once('telnet_new_tmp_class.php');
                $t = new TELNET();
                $time_start = microtime(true);
                $loopback = $ip_address;
                $content.= "\r\n" . "Checking Reachability";
                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                $connectLocal = $t->Connect($loopback);
                if ($connectLocal == 1) {
//                    $logArray = $t->LogIn();
                    $logArray = $t->LogInGeneric($eusername, $epassword);

                    $logArrayText = implode('<br/>', $logArray);

                    $routernameLocal = array_pop($logArray);
                    if (strpos($routernameLocal, '>') !== false) {
                        $pass = "rjil123";
                        $t->GetOutputOf("en");
                        $logArray = $t->GetOutputOf($pass);
                    }
                    if (strpos($routernameLocal, '#') !== false) {
                        $hostname = substr($routernameLocal, 0, 15);
                        $content .= "\r\n\r\nSucessfully Telnet on {$loopback} with hostname {$hostname}";
                        $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        $content .= "\r\nCommand Name :</b> show startup-config | in logging";
                        $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        $data = $t->GetOutputOf("show startup-config | in logging");

                        $adding_ips = "logging host " . $primary_ips_state;
                        $check_configuration_lines[] = $adding_ips;
                        foreach ($data as $line) {
                            $line = preg_replace("/\s+/", " ", trim($line));
                            $content .="\r\n" . $line;
                            if ($removable_logging_host == strtolower($line)) {
                                $removable_logging_host_found = 1;
                            }
                            if ($adding_ips == strtolower($line)) {
                                $adding_primary_ip_found = 1;
                            }
                        }
                        if ($removable_logging_host_found == 1 && $adding_primary_ip_found != 1) {
                            $commands = array(
                                "conf t",
                                "no " . $removable_logging_host,
                                $adding_ips,
                                "end"
                            );
                            $need_to_hit_command = 1;
                        } else if ($removable_logging_host_found != 1 && $adding_primary_ip_found != 1) {
                            $commands = array(
                                "conf t",
                                $adding_ips,
                                "end"
                            );
                            $need_to_hit_command = 1;
                        } else if ($removable_logging_host_found == 1 && $adding_primary_ip_found == 1) {
                            $commands = array(
                                "conf t",
                                "no " . $removable_logging_host,
                                "end"
                            );
                            $need_to_hit_command = 1;
                        }

                        if ($need_to_hit_command == 1) {
                            $newCommands = implode("\n", $commands);
                            $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n Hit commands on device";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n" . $newCommands;
                            $content .="\r\n" . "write";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            foreach ($commands as $ckey => $cval) {
                                $temp = $t->GetOutputOf($cval);
                                $temp = array_map('trim', $temp);
                                $content .= implode("\r\n", $temp);
                                //$content .= $temp[0].'<br/>';
                                $temp = "";
                            } 
                            sleep(5);
                            $datanew = $t->GetOutputOf("sh run | in logging");

                            $found_configuration_lines = $remove_line_found = 0;
                            $found_key = -1;
                            $content .= "\r\n\r\nAfter configuring check the output of command";
                            $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .= "\r\nCommand Name :</b> sh run | in logging";
                            $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            foreach ($datanew as $line) {
                                $line = preg_replace("/\s+/", " ", trim($line));
                                $content .="\r\n" . $line;
                                if ($removable_logging_host == strtolower($line)) {
                                    $remove_line_found = 1;
                                }
                                $found_key = array_search($line, $check_configuration_lines);
                                if (($found_key >= 0) && (count($check_configuration_lines) > 0) && trim($found_key) != "") {
                                    unset($check_configuration_lines[$found_key]);
                                    $found_configuration_lines = $found_configuration_lines + 1;
                                    $found_key = -1;
                                }
                            }
                            $temp = $t->GetOutputOf("write");
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n Result of commands";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                            if (count($check_configuration_lines) == 0 && $remove_line_found == 0) {
                                $content .= "\r\nConfiguration Done success fully";
                                $Status = 1;
                            } else {
                                $content .= "\r\nConfiguration Not done successfully";
                                $Status = 2;
                            }
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        } else {
                            $Status = 1;
                            $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            $content .="\r\n No need to change configuration";
                            $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                        }
                        $t->GetOutputOf("exit");
                    } else {
                        $Status = 4;
                        $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
                    }
                } else {
                    $Status = 3;
                    $content .= "\r\nDevice not reachable<b/>";
                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                }
                $findIps->hostname = trim($hostname, "#");
                $findIps->Status = $Status;
                $findIps->save(FALSE);
                $this->writeLog($content, $filepath_steps_log);
            }
        }
    }

    //=========================================================END=========================================================================
    //=============================================For Core devices ======================================================================= 
    public function SyslogLocalIPchangeCoreDevices($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state)) {
            $commands = $datanew = array();
            $file_path = "/uploads/SyslogServerIPs/" . $ip_address . "__" . date('d_m_Y') . "__" . time() . ".txt";
            $filepath_steps_log = dirname(Yii::app()->basePath) . $file_path;
            $removable_logging_host_found = $adding_primary_ip_found = $need_to_hit_command = $hostname = "";
            $removable_logging_host = "logging 10.70.4.8 vrf default severity info";
            $Status = 0;
            $check_configuration_lines = array();
            //===================================update into table===============================================//
            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->addCondition("request_id ='{$request_id}' AND sequence_no ='{$seuence_no}' AND ip_address = '{$ip_address}' ");
            $findIps = Syslogserveripschange::model()->find($criteria);
            if (!empty($findIps)) {
                $eusername = $findIps->eusername;
//                $eusername = "rjil";
                $epassword = base64_decode($findIps->epassword);
//                $epassword = "rjil123";
                $findIps->status_at = new CDbExpression('NOW()');
                $findIps->sys_log_path = $file_path;
                //========================================   END   =============================================//
                $content = "";
                $content.= "\r\n" . "Input Loopback0 IP : " . $ip_address;
//                echo "<br /> ip address =" . $ip_address . "  circle code =" . $circle_code . "   primary_ips_state =" . $primary_ips_state;
                require_once('telnet_new_tmp_class.php');
                $t = new TELNET();
                $time_start = microtime(true);

                        $loopback = $ip_address;
                $connectLocal = $t->Connect($loopback);
                        if ($connectLocal == 1) {
                            $logArray = $t->LogInGeneric($eusername, $epassword);
                            $logArrayText = implode('<br/>', $logArray);

                            $routernameLocal = array_pop($logArray);
                            if (strpos($routernameLocal, '>') !== false) {
                                $pass = "rjil123";
                                $t->GetOutputOf("en");
                                $logArray = $t->GetOutputOf($pass);
                            }

                            if (strpos($routernameLocal, '#') !== false) {
                                $hostname = substr($routernameLocal, -15);

                                $content .= "\r\n\r\nSucessfully Telnet on {$loopback} with hostname {$hostname}";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $content .= "\r\nCommand Name :</b> show run | in logging";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $data = $t->GetOutputOf("show run | in logging");

                                $adding_ips = "logging " . $primary_ips_state . " vrf default severity info";
                                $check_configuration_lines[] = $adding_ips;
                                foreach ($data as $line) {
                                    $line = preg_replace("/\s+/", " ", trim($line));
                                    $content .="\r\n" . $line;
                                    if ($removable_logging_host == strtolower($line)) {
                                        $removable_logging_host_found = 1;
                                    }
                                    if ($adding_ips == strtolower($line)) {
                                        $adding_primary_ip_found = 1;
                                    }
                                }
                                if ($removable_logging_host_found == 1 && $adding_primary_ip_found != 1) {
                                    $commands = array(
                                        "conf t",
                                        "no " . $removable_logging_host,
                                        $adding_ips,
                                        "Commit"
                                    );
                                    $need_to_hit_command = 1;
                                } else if ($removable_logging_host_found != 1 && $adding_primary_ip_found != 1) {
                                    $commands = array(
                                        "conf t",
                                        $adding_ips,
                                        "Commit"
                                    );
                                    $need_to_hit_command = 1;
                                } else if ($removable_logging_host_found == 1 && $adding_primary_ip_found == 1) {
                                    $commands = array(
                                        "conf t",
                                        "no " . $removable_logging_host,
                                        "Commit"
                                    );
                                    $need_to_hit_command = 1;
                                }
//                        }

                                if ($need_to_hit_command == 1) {
                                    $newCommands = implode("\n", $commands);

                                    $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .="\r\n Hit commands on device";
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .="\r\n" . $newCommands;
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    foreach ($commands as $ckey => $cval) {
                                        $temp = $t->GetOutputOf($cval);
                                $temp = array_map('trim', $temp);
                                $content .= implode("\r\n", $temp);
                                        //$content .= $temp[0].'<br/>';
                                        $temp = "";
                                    }
                            sleep(3);
                                    $datanew = $t->GetOutputOf("show run | in logging");

                                    $found_configuration_lines = $remove_line_found = 0;
                                    $found_key = -1;
                                    $content .= "\r\n\r\nAfter configuring check the output of command";
                                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .= "\r\nCommand Name :</b>show run | in logging";
                                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                    foreach ($datanew as $line) {
                                        $line = preg_replace("/\s+/", " ", trim($line));
                                        $content .="\r\n" . $line;
                                        if ($removable_logging_host == strtolower($line)) {
                                            $remove_line_found = 1;
                                        }
                                        $found_key = array_search($line, $check_configuration_lines);
                                        if (($found_key >= 0) && (count($check_configuration_lines) > 0) && trim($found_key) != "") {
                                            unset($check_configuration_lines[$found_key]);
                                            $found_configuration_lines = $found_configuration_lines + 1;
                                            $found_key = -1;
                                        }
                                    }
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .="\r\n Result of commands";
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                    if (count($check_configuration_lines) == 0 && $remove_line_found == 0) {
                                        $content .= "\r\nConfiguration Done success fully";
                                        $Status = 1;
                                    } else {
                                        $content .= "\r\nConfiguration Not done successfully";
                                        $Status = 2;
                                    }
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                } else {
                                    $Status = 1;
                                    $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .="\r\n No need to change configuration";
                                    $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                }
                                $t->GetOutputOf("exit");
                            } else {
                                $Status = 4;
                                $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
                            }
                        } else {
                            $Status = 4;
                            $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
                            $content .= "\r\nSite is not NOC reachable 2";
                        }
                        $t->GetOutputOf("exit");
                        $content .= "\r\nEXIT from {$loopback}..";
                $findIps->hostname = trim($hostname, "#");
                $findIps->Status = $Status;
                $findIps->save(FALSE);
                $this->writeLog($content, $filepath_steps_log);
            }
        }
    }

//==================================================End of core device===================================================================================    
//=============================================For Core Nexus devices ======================================================================= 
    public function SyslogLocalIPchangeCoreDevicesNexus($ip_address, $circle_code, $site_sap_name, $primary_ips_state, $request_id, $seuence_no) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state)) {
            $commands = $datanew = array();
            $file_path = "/uploads/SyslogServerIPs/" . $ip_address . "__" . date('d_m_Y') . "__" . time() . ".txt";
            $filepath_steps_log = dirname(Yii::app()->basePath) . $file_path;
            $removable_logging_host_found = $adding_primary_ip_found = $need_to_hit_command = $hostname = $sub_host = "";
            $removable_logging_host = "logging server 10.70.4.8";
//            $removable_logging_host_with_6 = "logging server 10.70.4.8 6";
            $Status = 0;
            $check_configuration_lines = array();
            //===================================update into table===============================================//
            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->addCondition("request_id ='{$request_id}' AND sequence_no ='{$seuence_no}' AND ip_address = '{$ip_address}' ");
            $findIps = Syslogserveripschange::model()->find($criteria);
            if (!empty($findIps)) {
                $eusername = $findIps->eusername;
//                $eusername = 'rjilautoro';
                $epassword = base64_decode($findIps->epassword);
//                $epassword = 'rj!l@ut0r0';
                $findIps->status_at = new CDbExpression('NOW()');
                $findIps->sys_log_path = $file_path;
                //========================================   END   =============================================//
                $content = "";
                $content.= "\r\n" . "Input Loopback0 IP : " . $ip_address;
                require_once('telnet_new_tmp_class.php');
                $t = new TELNET();
                $time_start = microtime(true);
                $master = "10.137.39.204";
//             $connectMaster = $t->Connect($master);
//                if ($connectMaster == 1)
                if (1) {
//                    $logArray = $t->LogInGeneric("cisco", "cisco$123"); //$t->LogInGeneric("collectorlogin", "cisco123"); //$t->LogInGeneric("root", "cisco123");
//                    print_r($logArray);
//                    $routernameMaster = array_pop($logArray);
//                    $content .= "<br/><b>Successfull Login on Master Device : {$master}</b>";
//                    if (strpos($routernameMaster, '>') !== false) {
//                        $pass = "rjil123";
//                        $t->GetOutputOf("en");
//                        $logArray = $t->GetOutputOf($pass);
//                    }
                    if (1) {
                        $loopback = $ip_address;
                        $connectLocal = $t->Connect($loopback);
                        if ($connectLocal == 1) {
                            $logArray = $t->LogInGeneric($eusername, $epassword);
                            $logArrayText = implode('<br/>', $logArray);
                            $routernameLocal = array_pop($logArray);
                            if (strpos($routernameLocal, '>') !== false) {
                                $pass = "rjil123";
                                $t->GetOutputOf("en");
                                $logArray = $t->GetOutputOf($pass);
                            }

                            if (strpos($routernameLocal, '#') !== false) {
                                $hostname = substr($routernameLocal, 0, 15);
                                $sub_host = substr($hostname, 9, 4);
                                $content .= "\r\n\r\nSucessfully Telnet on {$loopback} with hostname {$hostname}";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $content .= "\r\nCommand Name :</b> sh startup-config | inc logging";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                $data = $t->GetOutputOf("sh startup-config | inc logging");

                                $data_23 = array();
                                $data_23 = $data;
                                $loggin_servers = array();
                                $loggin_servers = $this->getLoggingServer($data_23);

                                foreach ($data as $line) {
                                    $line = preg_replace("/\s+/", " ", trim($line));
                                    $content .="\r\n" . $line;
                                }

                                $adding_ips = "logging server " . $primary_ips_state;
                                $loggin_servers[] = $adding_ips;
                                $loggin_servers = array_unique($loggin_servers);

                                $commands = array(
                                    "conf t",
                                    "no " . $removable_logging_host);
                                if ((strpos($sub_host, 'CES3') !== false) || (strpos($sub_host, 'CES6') !== false) || (strpos($sub_host, 'CES5') !== false)) {
                                    foreach ($loggin_servers as $value) {
                                        $adding_ips = "";
                                        $adding_ips = trim($value) . " 6 use-vrf default";
                                        $commands[] = $adding_ips;
                                        $check_configuration_lines[] = $adding_ips;
                                    }
                                } else {
                                    foreach ($loggin_servers as $value) {
                                        $adding_ips = "";
                                        $adding_ips = trim($value) . " 6";
                                        $commands[] = $adding_ips;
                                        $check_configuration_lines[] = $adding_ips;
                                    }
                                }
                                $commands[] = "end";

                                $newCommands = implode("\r\n", $commands);

                                $content .="\r\n\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $content .="\r\n Hit commands on device";
                                $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $content .="\r\n" . $newCommands;
                                $content .="\r\n" . "wr";
                                $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
//                                    $temp = $t->GetOutputOf($newCommands);
                                foreach ($commands as $ckey => $cval) {
                                    $temp = $t->GetOutputOf($cval);
                                    $content .= implode("<br/>", str_replace("\n", " ", $temp));
                                    //$content .= $temp[0].'<br/>';
                                    $temp = "";
                                }
                                sleep(3);
                                $datanew = $t->GetOutputOf("sh run | inc logging");
                                $found_configuration_lines = $remove_line_found = 0;
                                $found_key = -1;
                                $content .= "\r\n\r\nAfter configuring check the output of command";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                $content .= "\r\nCommand Name :</b>sh run | inc logging";
                                $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                foreach ($datanew as $line) {
                                    $line = preg_replace("/\s+/", " ", trim($line));
                                    $content .="\r\n" . $line;
                                    if ($removable_logging_host == strtolower($line)) {
                                        $remove_line_found = 1;
                                    }

                                    if (in_array($line, $check_configuration_lines)) {
                                        $found_key = array_search($line, $check_configuration_lines);
                                        unset($check_configuration_lines[$found_key]);
                                    }
                                }


//                                $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
//                                $content .="\r\n Result of commands";
//                                $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                if (count($check_configuration_lines) == 0 && $remove_line_found == 0) {
                                    /* --check service reachablee unreachable                         */

                                    $sh_logging = $t->GetOutputOf("sh logg | i 'Logging server' next 12");
                                    $content .= "\r\n\r\nCheck server service";
                                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                                    $content .= "\r\nCommand Name :</b> sh logg | i 'Logging server' next 12";
                                    $content .= "\r\n-------------------------------------------------------------------------------------------------------------------------------------";

                                    foreach ($sh_logging as $line) {
                                        $line = preg_replace("/\s+/", " ", trim($line));
                                        $content .="\r\n" . $line;
                                        if ("this server is temporarily unreachable" == strtolower($line)) {
//                                            $Status = 7;
                                            break;
                                        }
                                    }
                                    $temp = $t->GetOutputOf("wr");
                                    $content .= implode("\r\n", $temp);
                                    if ($Status == 7) {
                                        $content .= "\r\nConfiguration Done success fully but Service Problem";
                                    } else {
                                        $content .= "\r\nConfiguration Done success fully";
                                        $Status = 1;
                                    }
                                } else {
                                    $content .= "\r\nConfiguration Not done successfully";
                                    $Status = 2;
                                }
                                $content .="\r\n-------------------------------------------------------------------------------------------------------------------------------------";
                            }
                            $t->GetOutputOf("exit");
                        } else {
                            $Status = 4;
                            $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
                        }
                    } else {
//                        $Status = 4;
//                        $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
//                        $content .= "\r\nSite is not NOC reachable 2";
                    }
                    $t->GetOutputOf("exit");
                    $content .= "\r\nEXIT from {$loopback}..";
                } else {
//                    $Status = 4;
//                    $content.= "\r\nConnection Time out :" . "\r\n\r\nTerminating script, Nothing got changed";
//                    $content .= "\r\nSite is not NOC reachable 1";
                }
//                $t->GetOutputOf("exit");

                $findIps->hostname = trim($hostname, "#");
                $findIps->Status = $Status;
                $findIps->save(FALSE);
                $this->writeLog($content, $filepath_steps_log);
            }
        }
    }

//==================================================End of core  Nexus device===================================================================================    




    public function writeLog($steps_log, $filepath_steps) {
        //Steps Log Start
        if ($steps_log != "") {
            @chmod($filepath_steps, 0777);
            file_put_contents($filepath_steps, $steps_log, FILE_APPEND) or die("Steps log creation Failed");
            @chmod($filepath_steps, 0777);
        }
        //Steps Log End
    }

    public function Passips() {
        die("Dont hit command srcipt ");
        ini_set('max_execution_time', 86400);
        $pass_ip = $skip_ip = array();
        $ip_address = $hostname = $circle_code = $site_sap_name = $primary_ips_state = $id = "";
        $In_circle_code = "'MU','MH','GO','KO','JH','BR','NE','DL','HP','UE','JK','KL','PB','HR','RJ','UW','OR','KA','AS','MP','CG','AP','TN','GJ','WB','PY'";
        $primary_ips = array('MU' => "10.70.11.32", 'MH' => "10.70.56.49", 'GO' => "10.70.12.15", 'KO' => "10.70.64.24", 'JH' => "10.70.74.49", 'BR' => "10.70.74.49", 'NE' => "10.70.72.49", 'DL' => "10.70.32.25", 'HP' => "10.70.104.49", 'UE' => "10.70.48.49", 'JK' => "10.70.90.49", 'KL' => "10.70.76.45", 'PB' => "10.70.104.49", 'HR' => "10.70.104.49", 'RJ' => "10.70.92.45", 'UW' => "10.70.94.45", 'OR' => "10.70.88.45", 'KA' => "10.70.96.25", 'AS' => "10.70.72.49", 'MP' => "10.70.78.45", 'CG' => "10.70.78.45", 'AP' => "10.70.24.49", 'TN' => "10.70.81.47", 'GJ' => "10.70.16.113", 'WB' => "10.70.108.14",
            'PY' => "10.70.81.47");
        $sql_query = "select ip_address,SUBSTRING(trim(site_sap_name),3,2) as circle_code,site_sap_name from tbl_ip_master where SUBSTRING(trim(site_sap_name),3,2) IN(" . $In_circle_code . ") AND SUBSTRING(trim(hostname),9,3) IN('ESR','PAR') AND ip_address !='' AND Status=1 AND trim(ip_address) like '172%' limit 20";
        $result = Yii::app()->db->createCommand($sql_query)->queryAll();

        foreach ($result as $value) {
            $ip_address = trim($value['ip_address']);
            $circle_code = $value['circle_code'];
            $site_sap_name = $value['site_sap_name'];
            $primary_ips_state = $primary_ips[$circle_code];
            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->addCondition("ip_address = '{$ip_address}'");
            $findIps = Syslogserveripschange::model()->find($criteria);
            if (!empty($ip_address) && !empty($circle_code) && !empty($primary_ips_state) && empty($findIps)) {
//                Syslogserveripschange::model()->SyslogLocalIPchange($ip_address, $circle_code, $site_sap_name, $primary_ips_state);
//                $gearman = new CSyslogServerChangeIpClient();
//                $gearman->sendToWorker($ip_address, $circle_code, $site_sap_name, $primary_ips_state);
            }
        }
    }

    public function filter_uploaded_data($data, $device_id, $user_id) {
        $myarray = $invalid_ips = $invalid_device_type = $ips = $duplicates = $submitted = array();
        $regularExp_IP = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/";
        foreach ($data as $key => $value) {
            $site_sap_name = trim($value[0]);
            $hostname = trim($value[1]);
            $ip_address = trim($value[2]);

            if (empty($site_sap_name) || empty($hostname) || empty($ip_address))
                continue;
            if (!preg_match($regularExp_IP, $ip_address)) {
                $invalid_ips[] = $value;
            } else {

                if (!in_array($ip_address, $ips)) {
                    $ips[] = $ip_address;

                    $circle_code = substr($site_sap_name, 2, 2);
                    $host_device = substr($hostname, 8, 3);
                    if (($device_id == $host_device) || ($device_id == "CAS" && $host_device == "CES")) {
                        if ($this->Check_ip_submit($ip_address) == 0) {
                            $myarray[] = array('ip_address' => $ip_address, 'circle_code' => $circle_code, 'site_sap_name' => $site_sap_name, 'user_id' => $user_id, 'device_type' => $device_id, 'mydevice' => $host_device);
                        } else {
                            $submitted[] = $ip_address;
                        }
                    } else {
                        $invalid_device_type[] = $ip_address;
                    }
                } else {
                    $duplicates[] = $ip_address;
                }
            }
        }
        $duplicates = array_unique($duplicates);
        $invalid_ips = array_unique($invalid_ips);

        return array('loopbacks' => $myarray, 'missmatch_date' => $invalid_ips, 'invalid_device_type' => $invalid_device_type, 'duplicates' => $duplicates, 'submitted' => $submitted);
    }

    public function readFile($filePath) {
        $arraydata = array();
        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        // Turn off our amazing library autoload 
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        /* Call the excel file and read it */
        $inputFileType = PHPExcel_IOFactory::identify($filePath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filePath);

        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($row = 2; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
                $arraydata[$row - 1][$col] = trim($value);
            }
        }
        spl_autoload_register(array('YiiBase', 'autoload'));
        return $arraydata;
    }

    public function uploadFile($model, $fromRow = 1, $path = 'uploads', $filetype = null) {
        $model->file_name = CUploadedFile::getInstance($model, 'file_name');
        $filename = date('dmYhis') . '_' . $model->file_name;
        $filePath = $path;
        if (!empty($filetype) && $filetype == "mw") {
            $filePath.= "/Microwave/" . date('dmY');
        }
        if (!empty($filetype) && $filetype == "enb") {
            $filePath.= "/ENB/" . date('dmY');
        }
        if (!empty($filetype) && $filetype == "utility") {
            $filePath.= "/UTILITY/" . date('dmY');
        }

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $filePath.= "/" . $filename;
        $model->file_name->saveAs($filePath, true);
        chmod($filePath, 0777);
        //------------------------------read CSV file --------------------------------//
        $file = fopen($filePath, "r");
          while (!feof($file)) { 
            $r = fgetcsv($file);
            //  preg_match('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/', $r[2], $matches);
            if(!empty($r))
            {
            preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $r[2], $matches);
            if(!empty($matches))
                $r[2] = $matches[0];

            $arraydata[] = $r;
                }
            }
        fclose($file);
        unset($arraydata[0]); 
        //---------------------------------END---------------------------------------//
//        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
//        // Turn off our amazing library autoload 
//        spl_autoload_unregister(array('YiiBase', 'autoload'));
//        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
//        /* Call the excel file and read it */
//        $inputFileType = PHPExcel_IOFactory::identify($filePath);
//        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//        $objReader->setReadDataOnly(true);
//        $objPHPExcel = $objReader->load($filePath);
//        //$total_sheets = $objPHPExcel->getSheetCount();
//        //$allSheetName = $objPHPExcel->getSheetNames();
//        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
//        $highestRow = $objWorksheet->getHighestRow();
//        $highestColumn = $objWorksheet->getHighestColumn();
//        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
//        try {
//            for ($row = $fromRow; $row <= $highestRow; ++$row) {
//                for ($col = 0; $col < $highestColumnIndex; ++$col) { 
//                     $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue(); 
//                    $arraydata[$row - 1][$col] = trim($value);
//                }
//            }
//        } catch (Exception $exc) {
//            Yii::app()->user->setFlash('notice', 'There is some problem while import file');
//            //echo $exc->getCode();
//            //echo $exc->getMessage();
//            //echo $exc->getTraceAsString();
//        }
//        spl_autoload_register(array('YiiBase', 'autoload'));   
        return array('fileName' => $filename, 'data' => $arraydata);
    }

    public function getLoggingServer($data) {
        $logging_servers = array();
        foreach ($data as $value) {
            $line = strtolower(trim($value));
            if (preg_match("/^logging server \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $line, $match) && !preg_match("/10.70.4.8/", $line)) {
                $logging_servers[] = $match[0];
            }
        }
        return $logging_servers;
    }

}
