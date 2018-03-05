<?php

/**
 * Description of Post Integration Validation
 *
 */
class DcFailoverTesting {

    public $log;
    public $id;
    public $data;
    public $finalResult = array();
    public $commandStatus = array();
    public $integrationLogFile;
    public $logFileName;
    public $supportServerElements;
    public $failover_testinglog;
    public $integration_id;
    public $model;

    public function __construct($id) {
        $this->id = $id;

        $model = DcIntegrationMaster::model()->findByPk($this->id);
        $this->model = $model;

        if (empty($this->model)) {
            throw new Exception("Integration request not found.");
        }

        $outputmaserResult = DatacenterOutputmaster::model()->with('dcInputMaster')->findByPk($this->model->output_master_id);

        if (empty($outputmaserResult)) {
            throw new Exception('NIP Data missing.');
        }

        $this->data = $outputmaserResult;
        $this->model->failover_testinglog = $this->model->integrationLogFile = $this->integrationLogFile = $this->model->hostname . "_" . $this->model->id . "_" . date("Y_m_d_h_i_s") . "_failover_testing" . ".log";

        $this->model->update();
    }

    public function getTargetHosts() {
        
         $model = $this->model;
         $hostsip= $this->data->gateway_inband_ipv4;
         
         if(isset($hostsip)&&($hostsip!='')){
             $hostip1= $hostsip;
             $hostip2= IpAddressHelper::incrementIpAddress($hostip1,1);
             $hostip3= IpAddressHelper::incrementIpAddress($hostip2,1);
         }
       
        $hosts = array($hostip1, $hostip2, $hostip3);
        return $hosts;
    }

    public function pingall() {
        
        $sshDTOR = new SshConnect;
        $gm_collect=false;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $model = $this->model;
        $hosts = $this->getTargetHosts(); //
        $sshDTOR->connect($this->data->int_mgmt0_ipv4, $credentials);
        $sshDTOR->getCommandOutput("terminal length 0");
        $count = 1;

        if (is_array($hosts)) {

            $this->log = "\n<b>Test</b>: Testing Normal Scenario \n";
            $model->writeIntegrationLog($this->log);
            $this->log = "\n<b>Ping Test :</b>";
            $model->writeIntegrationLog($this->log);

            foreach ($hosts as $host) {
                if (empty($host)) {
                    continue;
                }
                $output = $line = '';
                $this->log = "\n" . $count . ":";
                $model->writeIntegrationLog($this->log);
                $cmd = "ping {$host} vrf vrf097";
                $this->log = "\n" . "<b>Command</b>: " . $cmd . "\n";

                $model->writeIntegrationLog($this->log);
                $sshDTOR->reconnect();
                $output = $sshDTOR->getCommandOutput($cmd);

                $this->log = "\n<b>Output</b> :" . $output;
                $model->writeIntegrationLog($this->log);

                if (!empty($output)) {
                    $lines = explode("\n", $output);

                    foreach ($lines as $line) {
                        if (preg_match("/packet loss$/", trim($line))) {

                            if (!(strcmp(substr($line, -19, 19), "100.00% packet loss") == 0)) {
                                array_push($this->finalResult, "Success");
                                $this->commandStatus[$host] = "Success";
                                $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
                            } else {
                                array_push($this->finalResult, "Failed");
                                $this->commandStatus[$host] = "Failed";
                                $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                            }
                        }
                    }
                    $model->writeIntegrationLog($this->log);
                } else {
                    $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                    $model->writeIntegrationLog($this->log);
                }
                $count++;
            }

            $this->log = "\n<b>Ping Test Result: </b>";
            $model->writeIntegrationLog($this->log);

            if (in_array("Failed", $this->commandStatus)) {
                foreach ($this->commandStatus as $key => $value) {
                    if ($value == "Failed") {
                        $output = "Failover testing is failed for the host" . $key;
                        $this->log = "Failed";
                    }
                }
            } else {
                $output = "Failover testing is success for the all the host";
                $this->log = "Success";
            }
            $model->writeIntegrationLog($this->log);
            return $output;
        }
    }

    public function traceall() {

        $sshDTOR = new SshConnect;

        $gm_collect=false;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $model = $this->model;
        $hosts = $this->getTargetHosts();

        $sshDTOR->connect($this->data->int_mgmt0_ipv4, $credentials);
        $sshDTOR->getCommandOutput("terminal length 0");
        $count = 1;

        $vlan_inband_ipv4 = $this->data->vlan_inband_ipv4;
        $vlan_no = $this->data->vlan_no;
        $device_layer = $this->data->dcInputMaster->device_layer;

        if (is_array($hosts)) {

            $this->log = "\n<b>Test</b>: Testing Normal Scenario \n";
            $model->writeIntegrationLog($this->log);
            $this->log = "\n<b>Trace Test :</b>";
            $model->writeIntegrationLog($this->log);

            foreach ($hosts as $host) {

                if (empty($host)) {
                    continue;
                }

                $output = $line = '';
                $this->log = "\n" . $count . ":";
                $model->writeIntegrationLog($this->log);

                if ($device_layer == 'L3') {
                    $cmd = "traceroute {$host} source loopback {$vlan_no}";
                } else {
                    $cmd = "trace {$host} source {$vlan_inband_ipv4} vrf vrf097";
                }

                $this->log = "\n" . "<b>Command</b>: " . $cmd . "\n";

                $model->writeIntegrationLog($this->log);
                $sshDTOR->reconnect();
                $output = $sshDTOR->getCommandOutput($cmd);

                $this->log = "\n<b>Output</b> :" . $output;
                $model->writeIntegrationLog($this->log);

                if (!empty($output)) {
                    $lines = explode("\n", $output);
                    $cnt = count($lines);

                   $lastline_content = $lines[$cnt-1]; 
                   $findstr="!";
                    //echo $lastline_content; 

if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/i", trim($lastline_content), $match)) {

                        $recoutput = $match[0];
        $pos = strpos($lastline_content,$findstr);

                                    if($pos === false){
                                       $resoutput="Success"; 
                            array_push($this->finalResult, "Success");
                            $this->commandStatus[$host] = "Success";
                            $this->log = "\n<b>Result</b> :" . $resoutput;
                                    }else{
                                        $resoutput="Failed"; 
                            array_push($this->finalResult, "Failed");
                            $this->commandStatus[$host] = "Failed";
                            $this->log = "\n<b>Result</b> :" . $resoutput;
                        }
                        $model->writeIntegrationLog($this->log);

                            }else{                              
                                
                                $resoutput="Failed"; 
                        $this->log = "\n<b>Result</b> :" . $resoutput;
                        $model->writeIntegrationLog($this->log);
                    }
                } else {
                    $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                    $model->writeIntegrationLog($this->log);
                }

                $count++;
            }

            $this->log = "\n<b>Trace Test Result: </b>";
            $model->writeIntegrationLog($this->log);

            if (in_array("Failed", $this->commandStatus)) {
                foreach ($this->commandStatus as $key => $value) {
                    if ($value == "Failed") {
                        $output .= "<br/>Failover Tracing of testing is failed for the host" . $key;
                        $this->log = "Failed";
                    }
                }
            } else {
                $output = "Failover testing is success for the all the host";
                $this->log = "Success";
            }
            $model->writeIntegrationLog($this->log);
            return $output;
        }
    }

   public function runpingtraceTests() {

        $model = $this->model;
        $this->log="\n<b>Test 1*******************************************</b>\n";
        $model->writeIntegrationLog($this->log);
        $this->log="\n<b>Testing scenario 1 - Primary and secondary interfaces are up.</b>\n";
        $model->writeIntegrationLog($this->log);
        $this->pingall();
        $this->log="\n<b>***************************************************</b>\n";
        $model->writeIntegrationLog($this->log);
        $this->traceall();
        
        $this->log = "\n<b>Test 2*******************************************</b>\n";
        $model->writeIntegrationLog($this->log);
        
        $this->log = "\n<b>Testing scenario 2 - Shutting primary interface and performing tests. Unshut primary interface after performing tests</b>\n";
        $model->writeIntegrationLog($this->log);
        
        $shutoutput1=$this->interfaceShut('ethernet 2/1');
        
        $this->log = "\n<b> Shutting primary interface output</b>\n".$shutoutput1;
        $model->writeIntegrationLog($this->log);
        
        $this->pingall();
        $this->traceall();
        
        $unshutoutput1= $this->interfaceUnshut('ethernet 2/1');
        
        $this->log = "\n<b> Unshutting primary interface output</b>\n".$unshutoutput1;
        $model->writeIntegrationLog($this->log);
        

        $this->log = "\n<b>Test 3**********************************************</b>\n";
        $model->writeIntegrationLog($this->log);

        $this->log = "\n<b>Testing scenario 3 - Shutting secondary interface and performing tests. Unshut secondary interface after performing tests</b>\n";
        $model->writeIntegrationLog($this->log);
        
        $shutoutput2= $this->interfaceShut('ethernet 2/2');
        
        $this->log = "\n<b>Shutting secondary interface Output</b>\n".$shutoutput2;
        $model->writeIntegrationLog($this->log);
        
        $this->pingall();
        $this->traceall();
        $unshutoutput2= $this->interfaceUnshut('ethernet 2/2');

        $this->log = "\n<b>Unshutting secondary interface Output</b>\n".$unshutoutput2;
        $model->writeIntegrationLog($this->log);
        
        
        $this->log = "\n<b>Test 4******************************************</b>\n";
        $model->writeIntegrationLog($this->log);
        
        $this->log = "\n<b>Re-testing scenario 1 - Primary and secondary interfaces are up</b>\n";
        $model->writeIntegrationLog($this->log);
        
        $this->pingall();
        $this->traceall();
        
        die();    
      } 
      

    public function interfaceShut($interface = null) {

        $sshDTOR = new SSHCollect;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $model = $this->model;
        $sshDTOR->setCredentials($credentials);
        $output =  '';
        $commands=array();
        $commands = array("conf t" ,"interface $interface","shut","end");
        $output= $sshDTOR->configTCommands($this->data->int_mgmt0_ipv4,$commands);
        $this->log = "\n" . "<b>Output</b>: " . $output . "\n";
        $model->writeIntegrationLog($this->log);

        return $output;
      }
      
      public function interfaceUnshut($interface=null){
          
        $sshDTOR = new SSHCollect;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $model = $this->model;
        $sshDTOR->setCredentials($credentials);
        $output =  '';
        $commands = array("conf t" ,"interface $interface","no shut","end");
        $output= $sshDTOR->configTCommands($this->data->int_mgmt0_ipv4,$commands);
        $this->log = "\n" . "<b>Output</b>: " . $output . "\n";
        $model->writeIntegrationLog($this->log);

        return $output;
    }
      
    }

