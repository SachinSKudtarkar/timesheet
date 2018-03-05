<?php

/**
 * Description of CdpDisable
 *
 * @author Shriram Jadhav <shriramjadhav@benchmarkitsolutions.com>
 */
class CCdpDisable {

    public $showIsisNeigh;
    public $_telnet;
    public $showInterfaceConfig;
    public $showVersion;
    public $hostname;
    public $loopback;
    public $viaHostnames = array();
    public $viaLoopback;
    public $viaHostname;
    public $disableCommands = array();
    public $log;
    public $status;
    public $commands = array(
        array('name' => 'gig_interface_config', 'command' => 'sh run | i GigabitEthernet|service instance|encapsulation untagged'),
        array('name' => 'show_isis_neighbors', 'command' => 'sh isis nei'),
        array('name' => 'show_version', 'command' => 'show version'),
    );

    public function disable($data) {
        if (!empty($data['loopback0']) && !empty($data['user_id'])) {
            $this->loopback = $data['loopback0'];
            $telnet = new TELNET();
            $status = $telnet->ping($data['loopback0']);
            if ($status == 'online') {
                $this->disableCommands = array();
                echo "\nIP Address: " . $data['loopback0'];
                $this->showIsisNeigh = null;
                $this->showInterfaceConfig = null;
                $this->showVersion = null;
                $this->doCollect($this->loopback, $this->commands);
                if (stripos($this->showVersion, 'ASR9K') && !empty($this->showIsisNeigh)) {
                    print "\nVersion ASR9K";
                    $this->showVersion = "ASR9K";
                    print "\nCreating Config commands with\n" . $this->showIsisNeigh;
                    $this->disableFromIsis();
                } elseif ($this->showInterfaceConfig) {
                    print "\nCreating Config commands with\n" . $this->showInterfaceConfig;
                    $this->showVersion = "Other";
                    $this->disableFromConfig();
                }
                if (!empty($this->disableCommands)) {
                    $status = $this->disableCdp();
                    if (empty($status)) {
                        print "\nCould not connect to device";
                        $this->status = "Failed: Could not connect to device";
                    }
                } else {
                    $this->status = "Failed: No response from BD";
                }
            } else {
                print "\nDevice is not rechable";
                $this->status = "Failed: Device is not rechable";
            }
        } else {
            $this->status = "Invalid request";
        }
        if (empty($this->status)) {
            print "\nFailed";
            $this->status = "Failed";
        }
        //Saving Request
        print "\nSaving Request";
        $this->saveRequest($data);
    }

    public function saveRequest($data) {
        $cdpDisable = new CdpDisable;
        $cdpDisable->batchid = $data['batch_id'];
        $cdpDisable->loopback = $this->loopback;
        $cdpDisable->hostname = $this->hostname;
        $cdpDisable->disable_command = implode("\n", $this->disableCommands);
        $cdpDisable->via_loopback = $this->viaHostname;
        $cdpDisable->via_hostname = $this->viaLoopback;
        $cdpDisable->log = $this->log;
        $cdpDisable->status = $this->status;
        $cdpDisable->created_by = $data['user_id'];
        $cdpDisable->created_at = date('Y-m-d H:i:s');
        if ($cdpDisable->save(FALSE)) {
            print "\nSaved\n";
        } else {
            print_r($cdpDisable->errors());
            print "\nFiled to save\n";
        }
    }

    public function disableFromConfig() {
        $lines = explode("\n", $this->showInterfaceConfig);
        if (is_array($lines)) {
            $this->disableCommands[] = "config t";
            $this->disableCommands[] = "no cdp run";
            $taggedInterfaces = array();
            foreach ($lines as $i => $line) {
                if (preg_match_all("/^interface GigabitEthernet(.*)/", $line, $intMatches) || preg_match_all("/^interface TenGigabitEthernet(.*)/", $line, $intMatches)) {
                    $key = $i;
                    $taggedInterfaces[] = $line;
                    while (1) {
                        $key++;
                        if (!isset($lines[$key]) || (isset($lines[$key]) && stripos($lines[$key], "interface") !== false)) {
                            break;
                        }
                        $nextLine = $lines[$key];
                        if (preg_match_all("/encapsulation untagged/", $nextLine)) {
                            $previousLine = $lines[$key - 1];
                            if (preg_match_all("/service instance ([0-9]{0,}) ethernet/", $previousLine)) {
                                # disable                                
                                $this->disableCommands[] = trim($line);
                                $this->disableCommands[] = "no cdp enable";
                                $this->disableCommands[] = trim($previousLine);
                                $this->disableCommands[] = "no l2protocol peer cdp";
                            }
                        }
                    }
                }
            }
            if (!empty($taggedInterfaces)) {
                foreach ($taggedInterfaces as $interfaceLine) {
                    $interfaceLine = trim($interfaceLine);
                    if (!in_array($interfaceLine, $this->disableCommands)) {

                        $this->disableCommands[] = $interfaceLine;
                        $this->disableCommands[] = "no cdp enable";
                    }
                }
            }

            $this->disableCommands[] = "end";
        }
    }

    public function disableFromIsis() {
        $lines = explode("\n", $this->showIsisNeigh);
        if (is_array($lines)) {
            $this->disableCommands[] = "config t";
            foreach ($lines as $i => $line) {
                $line = trim($line);
                if (preg_match('/^[a-zA-Z]{8}PAR[0-9]{3}$/', substr($line, 0, 14))) {
                    $line = preg_replace('/\s+/', " ", $line);
                    $cells = explode(" ", $line);
                    if (!empty($cells[1]) && in_array(substr($cells[1], 0, 1), array('G', 'g', 'T', 't'))) {
                        $this->viaHostnames[] = substr($line, 0, 14);
                        $this->disableCommands[] = "interface {$cells[1]}";
                        $this->disableCommands[] = "no cdp";
                    }
                }
            }
            $this->disableCommands[] = "commit";
            $this->disableCommands[] = "end";
            $this->disableCommands[] = "exit";
        }
    }

    public function doCollect($loopback0, $commands) {
        $this->_collectionEngine = new CCollectionEngineClient(CCollectionEngineClient::DEFAULT_COLLECTION_SERVERS);
        $this->_collectionEngine->setCompleteCallback(array($this, 'onConfigCollectComplete'));
        $this->_collectionEngine->setFailCallback(array($this, 'onConfigCollectFailure'));
        $this->_collectionEngine->collect($loopback0, $commands);
    }

    public function onConfigCollectFailure($task) {
        print "\nFailed Config Collection";
        $this->status = "Failed Config Collection";
    }

    public function onConfigCollectComplete($task) {
        echo "\nCOMPLETE: Config collection";
        $data = json_decode($task->data());
        if (property_exists($data, 'host') and property_exists($data, 'result')) {
            foreach ($data->result as $key => $val) {
                switch ($key) {
                    case "gig_interface_config":
                        $this->showInterfaceConfig = implode("", $val);
                        break;
                    case "show_isis_neighbors":
                        $this->showIsisNeigh = implode("", $val);
                        break;
                    case "show_version":
                        $this->showVersion = implode("", $val);
                        break;
                    default :
                        $this->otherCommand[$key] = implode("", $val);
                }
            }
        }
    }

    public function disableCdp() {
        if (!empty($this->loopback) and ! empty($this->disableCommands)) {
            echo "\nDisable CDP for [{$this->loopback}]: \n" . implode("\r\n", $this->disableCommands);
            $key = $this->loopback;
            $value = $this->disableCommands;
            $this->_telnet = new TELNET();
            $commit = '';
            $hostname = '';
            if ($this->showVersion == "ASR9K") {
                foreach ($this->viaHostnames as $neighHost) {
                    print "\nTrying to find out vialoopback for {$neighHost}";
                    $loopback = $this->getLoopback($neighHost);
                    if (!empty($loopback)) {
                        print "\nvialoopback for {$neighHost} is {$loopback}";
                        $hostnameNeigh = $this->telnetLogin($loopback);
                        if (!empty($hostnameNeigh)) {
                            $telnet_arry = $this->_telnet->GetOutputOf("telnet {$key}");
                            foreach ($telnet_arry as $telnet_val) {
                                if (preg_match("/Username/", $telnet_val) || preg_match("/username/", $telnet_val)) {
                                    $login_flag = 1;
                                }
                            }
                            if ($login_flag == 1) {
                                $hostname = $this->telnetLogin($key, 1);
                                $hostname = substr($hostname, -14);
                                $this->viaHostname = $hostnameNeigh;
                                $this->viaLoopback = $loopback;
                                break;
                            } else {
                                print "\n" . "Failed to connect [{$key}]:";
                                $this->log = "Failed to connect [{$key}]:";
                                return false;
                            }
                            $this->_telnet = new TELNET();
                        }
                    } else {
                        print "\nvialoopback not found for {$neighHost}";
                    }
                }
            } else {
                $hostname = $this->telnetLogin($key);
            }

            if (!empty($hostname)) {
                $this->hostname = $hostname;
                print "\nHostname of {$key} is {$hostname}";
                $commands = implode("\r\n", $value);
                $rows = $this->_telnet->GetOutputOf($commands);
                $this->log .= implode("\r\n", $rows);
                print implode("\r\n", $rows) . "\n";
                $rows = $this->_telnet->GetOutputOf("exit");
                $this->log .= implode("\r\n", $rows);
                print implode("\r\n", $rows) . "\n";
                $this->status = "Disabled";
            } else {
                print "\nCould not login on {$key}: ";
                $this->status = "Failed: Could not login on {$key}";
                $this->log .= "Could not login on {$key}";
            }
            return true;
        }
        return false;
    }

    public function getMplsLdpNeighLoopback($bdi) {
        $return = NULL;
        if (!empty($bdi)) {// && preg_match("/[^A-Za-z](.*)/", $bdi, $output_array) && !empty($output_array[0])) {
            $command = "";
//            $bdiOrVlan = "";
//            if (substr($bdi, 0, 1) == "B" || substr($bdi, 0, 1) == "b")
//                $bdiOrVlan = "bdi";
//            if (substr($bdi, 0, 1) == "V" || substr($bdi, 0, 1) == "v")
//                $bdiOrVlan = "vlan";
//            if (!empty($bdiOrVlan))
//                $command = "sh mpls ldp neighbor {$bdiOrVlan} {$output_array[0]} | i Peer LDP Ident";
            $command = "sh mpls ldp neighbor {$bdi} | i Peer LDP Ident";
            if (!empty($this->otherCommand['mpls_ldp_neighbor'])) {
                $this->otherCommand['mpls_ldp_neighbor'] = NULL;
            }
            if (!empty($command)) {
                print "\nCommand Prepared : $command";
                $this->doCollect($this->loopback0, array(array('name' => 'mpls_ldp_neighbor', 'command' => $command)));
                if (!empty($this->otherCommand['mpls_ldp_neighbor'])) {
                    $rows = explode("\n", $this->otherCommand['mpls_ldp_neighbor']);
                    foreach ($rows as $row) {
                        if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $row, $match)) {
                            if (!empty($match[0])) {
                                $return = $match[0];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

    public function getLoopback($hostname, $bdi = "") {
        $return = NULL;
        print "\nFinding loopback for {$hostname}";
        print "\nTrying to find loopback in Built data";
        if (substr($hostname, 8, 3) == "ESR" || substr($hostname, 8, 3) == "PAR") {
            $model = Router::model()->find(array("select" => "Loopback0", "condition" => "host_name=:host_name", "params" => array(":host_name" => $hostname)));
            if (!empty($model) && !empty($model->Loopback0)) {
                $return = $model->Loopback0;
            }
            if (empty($return) && !empty($bdi)) {
                print "\nTrying find with mpls ldp $bdi";
                $return = $this->getMplsLdpNeighLoopback($bdi);
            }
//            if (empty($return)) {
//                print "\nTrying to find loopback planned";
//                $loopback0 = NddRanLb::model()->find(array("select" => "ipv4", "condition" => "host_name=:host_name", "params" => array(":host_name" => $hostname)));
//                if (!empty($loopback0) && !empty($loopback0->ipv4)) {
//                    $return = $loopback0->ipv4;
//                }
//            }
        } else {
            $model = RouterAg2Details::model()->find(array("select" => "Loopback0", "condition" => "host_name=:host_name", "params" => array(":host_name" => $hostname)));
            if (!empty($model) && !empty($model->Loopback0)) {
                $return = $model->Loopback0;
            }
            if (empty($return) && !empty($bdi)) {
                print "\nTrying find with mpls ldp $bdi";
                $return = $this->getMplsLdpNeighLoopback($bdi);
            }
            if (empty($return)) {
                print "\nTrying to find loopback planned";
                $loopback0 = NddCoreIpMaster::model()->getLoopback0Ips($hostname);
                if (!empty($loopback0) && !empty($loopback0->loopback0_ipv4)) {
                    $return = $loopback0->loopback0_ipv4;
                }
            }
        }
        if (!empty($return)) {
            print "\nLoopback found is : {$return}";
        } else {
            print "\nLoopback not found";
        }
        return $return;
    }

    public function telnetLogin($loopback0, $connect = 0) {
        if (!empty($loopback0)) {
            if (!$connect) {
                $connect = $this->_telnet->Connect($loopback0);
            }
            if ($connect == 1) {
                $logArray = $this->_telnet->LogIn();
                $routername = array_pop($logArray);
                if (strpos($routername, '>') !== false) {
                    $pass = "rjil123";
                    $this->_telnet->GetOutputOf("en");
                    $logArray = $this->_telnet->GetOutputOf($pass);
                    $routername = array_pop($logArray);
                }
                if (strpos($routername, '#') !== false) {
                    $routername = str_replace("rjil123", "", str_replace("#", "", $routername));
                    $routername = trim(str_replace("Rjil123", "", str_replace("#", "", $routername)));
                    return $routername;
                }
            } else {
                print "\nCould not connect {$loopback0}: " . $connect;
            }
        }
        return FALSE;
    }

}
