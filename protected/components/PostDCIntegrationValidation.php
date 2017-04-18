<?php

/**
 * Description of Post Integration Validation
 *
 * @author Vishwas Zambre <vishwaszambre@benchmarkitsolutions.com>
 */
class PostDCIntegrationValidation {

    public $log;
    public $id;
    public $data;
    public $finalResult = array();
    public $commandStatus = array();
    public $integrationLogFile;
    public $supportServerElements;

    public function __construct($id, $logFileName) {
        $this->id = $id;
        $this->integrationLogFile = $logFileName;
    }

    public function runTests() {
        $model = DcIntegrationMaster::model()->findByPk($this->id);
        $outputmaserResult = DatacenterOutputmaster::model()->with('dcInputMaster')->findByPk($model->output_master_id);

        $model->integrationLogFile = $this->integrationLogFile;

        if (empty($outputmaserResult)) {
            throw new Exception('NIP Data missing.');
        }

        $this->data = $outputmaserResult;

        $this->supportServerElements = DatacenterSnmpserverMapping::model()->findByAttributes(array('city' => $this->data->dcInputMaster->city, 'dc_no' => $this->data->dcInputMaster->dc_no));

        $this->log = "<b>*********************************************************************************************************************************************************************************************</b>\n\n";

        $model->writeIntegrationLog($this->log);
        $this->log = 'Post Integration check' . "\n\n";
        $model->writeIntegrationLog($this->log);
        $this->log = "<b>*********************************************************************************************************************************************************************************************</b>\n\n";
        $model->writeIntegrationLog($this->log);

        $this->runPingTests($model);
        $this->dcValidator($model);
    }

    public function ping($ip, $hosts, $model) {
        $sshDTOR = new SshConnect;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $sshDTOR->connect($ip, $credentials);
        $sshDTOR->getCommandOutput("terminal length 0");
        $count = 1;
        if (is_array($hosts)) {
            foreach ($hosts as $host) {
                if (empty($host)) {
                    continue;
                }
                $output = $line = '';
                $cmd = "ping {$host} vrf management";
                $this->log = "\n<b>Command</b>: " . $cmd;
                $model->writeIntegrationLog($this->log);
                $sshDTOR->reconnect();
                $output = $sshDTOR->getCommandOutput($cmd);
                $this->log = "\n<b>Output</b> :\n" . $output;
                $model->writeIntegrationLog($this->log);
                if (!empty($output)) {
                    $lines = explode("\n", $output);
                    foreach ($lines as $line) {
                        if (preg_match("/packet loss$/", trim($line))) {
                            if (!(strcmp(substr($line, -19, 19), "100.00% packet loss") == 0)) {
                                array_push($this->finalResult, "Success");
                                $this->commandStatus[$host] = "Success";
                                $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
                                $model->writeIntegrationLog($this->log);
                            } else {
                                array_push($this->finalResult, "Failed");
                                $this->commandStatus[$host] = "Failed";
                                $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                                $model->writeIntegrationLog($this->log);
                            }
                        }
                    }
                } else {
                    $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                    $model->writeIntegrationLog($this->log);
                }
                $count++;
            }
        }
    }

    public function dcValidator($model) {
        $vpcResult = array();
        $sshDTOR = new SshConnect;
        $credentials = Yii::app()->params['dc_ssh_dtor_connect'];
        $sshDTOR->connect($this->data->int_mgmt0_ipv4, $credentials);
        $sshDTOR->getCommandOutput("terminal length 0");

        $this->log = "\n\n" . '<span style="color:blue"><b><i>NTP Peer Status</span></i></b>' . "\n\n";
        $model->writeIntegrationLog($this->log);

        $this->log = "\n<b>Command</b> : show ntp peer-status";
        $model->writeIntegrationLog($this->log);

        $output = $sshDTOR->getCommandOutput("show ntp peer-status");

        $this->log = "\n<b>Output</b> :\n" . $output;
        $model->writeIntegrationLog($this->log);
        if (!empty($output)) {
            $lines = explode("\n", $output);
            foreach ($lines as $line) {
                if (preg_match("/^\*[0-9]{0,3}\.[0-9]{0,3}\.[0-9]{0,3}\.[0-9]{0,3}\s{0,}[0-9]{0,3}\.[0-9]{0,3}\.[0-9]{0,3}\.[0-9]{0,3}\s{0,}([0-9]{0,})\s{0,}([0-9]{0,})\s{0,}([0-9]{0,})\s{0,}([0-9\.]{0,})\s([a-z]{0,})/", trim($line), $ntpMatches)) {
                    if (isset($ntpMatches[1]) && (int) $ntpMatches[1] === 2 && isset($ntpMatches[4]) && $ntpMatches[4] > 0) {
                        array_push($this->finalResult, "Success");
                        $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
                        $model->writeIntegrationLog($this->log);
                    } else {
                        array_push($this->finalResult, "Failed");
                        $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                        $model->writeIntegrationLog($this->log);
                    }
                    break;
                }
            }
        } else {
            $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
            $model->writeIntegrationLog($this->log);
        }

        $this->log = "\n\n" . '<span style="color:blue"><b><i>Show VPC</span></i></b>' . "\n\n";
        $model->writeIntegrationLog($this->log);

        $this->log = "\n<b>Command</b> : show vpc";
        $model->writeIntegrationLog($this->log);

        $output = $sshDTOR->getCommandOutput("show vpc");

        $this->log = "\n<b>Output</b> :\n" . $output;
        $model->writeIntegrationLog($this->log);
        if (!empty($output)) {
            $lines = explode("\n", $output);
            $vpcResult = array();
            foreach ($lines as $line) {
                if (preg_match("/^Peer status\s{0,}\:(.*)/", trim($line), $vpcChk1Match)) {
                    if (isset($vpcChk1Match[1]) && stripos($vpcChk1Match[1], "ok") !== false) {
                        $vpcResult[] = "Success";
                    } else {
                        $vpcResult[] = "Failed";
                    }
                }
                if (preg_match("/^vPC keep-alive status\s{0,}\:(.*)/", trim($line), $vpcChk2Match)) {
                    if (isset($vpcChk2Match[1]) && stripos($vpcChk2Match[1], "alive") !== false) {
                        $vpcResult[] = "Success";
                    } else {
                        $vpcResult[] = "Failed";
                    }
                }
                if (preg_match("/^Configuration consistency status\s{0,}\:(.*)/", trim($line), $vpcChk3Match)) {
                    if (isset($vpcChk3Match[1]) && stripos($vpcChk3Match[1], "success") !== false) {
                        $vpcResult[] = "Success";
                    } else {
                        $vpcResult[] = "Failed";
                    }
                }
                if (preg_match("/^Per-vlan consistency status\s{0,}\:(.*)/", trim($line), $vpcChk4Match)) {
                    if (isset($vpcChk4Match[1]) && stripos($vpcChk4Match[1], "success") !== false) {
                        $vpcResult[] = "Success";
                    } else {
                        $vpcResult[] = "Failed";
                    }
                }
                if (preg_match("/^Type-2 consistency status\s{0,}\:(.*)/", trim($line), $vpcChk5Match)) {
                    if (isset($vpcChk5Match[1]) && stripos($vpcChk5Match[1], "success") !== false) {
                        $vpcResult[] = "Success";
                    } else {
                        $vpcResult[] = "Failed";
                    }
                }
            }

            if (empty($vpcResult) || in_array("Failed", $vpcResult)) {
                array_push($this->finalResult, "Failed");
                $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                $model->writeIntegrationLog($this->log);
            } else {
                array_push($this->finalResult, "Success");
                $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
                $model->writeIntegrationLog($this->log);
            }
        } else {
            $this->log = "\n<b>Result</b> : " . '<span style="color:red">Output missing!</span>';
            $model->writeIntegrationLog($this->log);
        }

        if ($this->data->dcInputMaster->device_layer == 'L3') {
            $this->log = "\n\n" . '<span style="color:blue"><b><i>Show IP OSPF Neighbors</span></i></b>' . "\n\n";
            $model->writeIntegrationLog($this->log);

            $this->log = "\n<b>Command</b> : show ip ospf neighbors";
            $model->writeIntegrationLog($this->log);

            $output = $sshDTOR->getCommandOutput("show ip ospf neighbors");

            $this->log = "\n<b>Output</b> :\n" . $output;
            $model->writeIntegrationLog($this->log);
            if (!empty($output)) {
                $lines = explode("\n", $output);
                $eth21ip = $this->data->int_eth_2_1_ipv4;
                $values = explode(".", $eth21ip);
                $lastSlot21 = ++$values[3];
                $eth21iparr = array($values[0], $values[1], $values[2], $lastSlot21);
                $neweth21ip = implode(".", $eth21iparr);

                $eth22ip = $this->data->int_eth_2_2_ipv4;
                $values = explode(".", $eth22ip);
                $lastSlot22 = ++$values[3];
                $eth22iparr = array($values[0], $values[1], $values[2], $lastSlot22);
                $neweth22ip = implode(".", $eth22iparr);
                $ip21 = $ip22 = '';
                $res1 = $res2 = array();
                foreach ($lines as $line) {
                    if (preg_match("/Eth2\/1/", $line)) {
                        $res1 = array_values(array_filter(explode(" ", $line)));
                        $ip21 = $res1[5];
                    }
                    if (preg_match("/Eth2\/2/", $line)) {
                        $res2 = array_values(array_filter(explode(" ", $line)));
                        $ip22 = $res2[5];
                    }
                }
                if (strcmp($ip21, $neweth21ip) == 0 && strcmp($ip22, $neweth22ip) == 0) {
                    $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
                    $model->writeIntegrationLog($this->log);
                } else {
                    $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                    $model->writeIntegrationLog($this->log);
                }
            } else {
                $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
                $model->writeIntegrationLog($this->log);
            }
        }
    } 

//    public function tacacsSshConnectivity($model) {
//        $this->log = "\n\n" . '<span style="color:blue"><b><i>11.TACACS SSH Connectivity</span></i></b>' . "\n\n";
//        $model->writeIntegrationLog($this->log);
//
//
//        $ssh = new SshConnect;
//        $credentials = Yii::app()->params['dc_ssh_connect'];
//        $result = false;
//        try {
//            $result = $ssh->connect($this->data->int_mgmt0_ipv4, $credentials);
//        } catch (Exception $ex) {
//            
//        }
//        if ($result) {
//            array_push($this->finalResult, "Success");
//            $this->log = "\n<b>Result</b> : " . '<span style="color:green">Success</span>';
//            $model->writeIntegrationLog($this->log);
//        } else {
//            array_push($this->finalResult, "Failed");
//            $this->log = "\n<b>Result</b> : " . '<span style="color:red">Failed</span>';
//            $model->writeIntegrationLog($this->log);
//        }
//    }

    public function runPingTests($model) {
        //ping NNMi server        
        $this->log = '<span style="color:blue"><b><i>NNMi Server</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $this->log = '<span style="color:blue"><b><i>NNMi#1</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array('jiodchpnnmi1.rjil.ril.com');
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        //ping NNMi server        
        $this->log = "\n\n" . '<span style="color:blue"><b><i>NNMi#2</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array('jiodchpnnmi2.rjil.ril.com');
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        $this->log = "\n\n" . '<span style="color:blue"><b><i>DCNM</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->data->snmp_server);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        //Ping CSPC Server 
        $this->log = "\n\n" . '<span style="color:blue"><b><i>NCCM / CSPC</span></i></b>' . "\r\n";
        $model->writeIntegrationLog($this->log);
        $command = array('jiodccspc.rjil.ril.com');
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        //Ping NTP Server
        if ($this->data->dcInputMaster->city == 'NVMB') {
            $this->log = "\n\n" . '<span style="color:blue"><b><i>Mumbai NTP</span></i></b>' . "\n";
            $model->writeIntegrationLog($this->log);
            $command = array('jiodcntp.rjil.ril.com');
            $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        }

        $this->log = "\n\n" . '<span style="color:blue"><b><i>' . $this->data->dcInputMaster->city . ' NTP</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->data->ntp_server);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);



        //Ping TACACS Server
//        $this->log = "\n\n" . '<span style="color:blue"><b><i>ACS</span></i></b>' . "\n";
//        $model->writeIntegrationLog($this->log);
//        $command = array('nvmbacs1.rjil.ril.com','nvmbacs2.rjil.ril.com');
//        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        //Admin N/w
        $this->log = "\n\n" . '<span style="color:blue"><b><i>Admin N/w</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->data->mgmt0_gateway_ipv4);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        //CLMS
        $this->log = "\n\n" . '<span style="color:blue"><b><i>CLMS</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->supportServerElements->clms);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        //SIEM
        $this->log = "\n\n" . '<span style="color:blue"><b><i>SIEM</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->supportServerElements->siem);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        //DNS
        $this->log = "\n\n" . '<span style="color:blue"><b><i>DNS</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->supportServerElements->dns1, $this->supportServerElements->dns2);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);
        //ACS
        $this->log = "\n\n" . '<span style="color:blue"><b><i>ACS 1</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->supportServerElements->acs1);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        $this->log = "\n\n" . '<span style="color:blue"><b><i>ACS 2</span></i></b>' . "\n";
        $model->writeIntegrationLog($this->log);
        $command = array($this->supportServerElements->acs2);
        $this->ping($this->data->int_mgmt0_ipv4, $command, $model);

        $this->UpdateCommandStatus();
    }

    public function getLogs() {
        return $this->log;
    }

    public function UpdateCommandStatus() {
        $model = new DcPostIntCommandStatus;
        $model1 = DcPostIntCommandStatus::model()->findByAttributes(array('inte_id' => $this->id));
        if (!empty($model1)) {
            $model = $model1;
        }
        $model->inte_id = $this->id;
        $model->snmp_nnmi1 = isset($this->commandStatus['jiodchpnnmi1.rjil.ril.com']) ? $this->commandStatus['jiodchpnnmi1.rjil.ril.com'] : "Failed";
        $model->snmp_nnmi2 = isset($this->commandStatus['jiodchpnnmi2.rjil.ril.com']) ? $this->commandStatus['jiodchpnnmi2.rjil.ril.com'] : "Failed";
        $model->snmp_cspc = isset($this->commandStatus['jiodccspc.rjil.ril.com']) ? $this->commandStatus['jiodccspc.rjil.ril.com'] : "Failed";
        $model->snmp_dyn = isset($this->commandStatus[$this->data->snmp_server]) ? $this->commandStatus[$this->data->snmp_server] : "Failed";
        $model->ntp_city = isset($this->commandStatus[$this->data->ntp_server]) ? $this->commandStatus[$this->data->ntp_server] : "Failed";
        $model->clms = isset($this->commandStatus[$this->supportServerElements->clms]) ? $this->commandStatus[$this->supportServerElements->clms] : "Failed";
        $model->siem = isset($this->commandStatus[$this->supportServerElements->siem]) ? $this->commandStatus[$this->supportServerElements->siem] : "Failed";
        $model->dns1 = isset($this->commandStatus[$this->supportServerElements->dns1]) ? $this->commandStatus[$this->supportServerElements->dns1] : "Failed";
        $model->dns2 = isset($this->commandStatus[$this->supportServerElements->dns2]) ? $this->commandStatus[$this->supportServerElements->dns2] : "Failed";
        $model->acs1 = isset($this->commandStatus[$this->supportServerElements->acs1]) ? $this->commandStatus[$this->supportServerElements->acs1] : "Failed";
        $model->acs2 = isset($this->commandStatus[$this->supportServerElements->acs2]) ? $this->commandStatus[$this->supportServerElements->acs2] : "Failed";
        $model->admin_nw_gateway = isset($this->commandStatus[$this->data->mgmt0_gateway_ipv4]) ? $this->commandStatus[$this->data->mgmt0_gateway_ipv4] : "Failed";
        if (!$model->save()) {
            echo "\nErrors:-";
            print_r($model->getErrors());
        } else {
            echo "\nUpdated status";
        }
    }

    public function getFinalResult() {
        if (in_array("Failed", $this->finalResult) && count($this->finalResult)) {
            return 'Failed';
        } else {
            return 'Success';
        }
    }

}
