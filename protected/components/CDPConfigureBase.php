<?php

require(Yii::app()->basePath . "/controllers/telnet_new_tmp_class.php");

/**
 * Description of CDPConfigureBase
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class CDPConfigureBase {

    public $juniperMac = array('000585', '0010DB', '00121E', '0014F6', '0017CB', '0019E2', '001BC0', '001DB5', '001F12', '002159', '002283', '00239C', '0024DC', '002688', '009069', '0881F4', '0C8610', '100E7E', '288A1C', '28C0DA', '2C2172', '2C6BF5', '307C5E', '3C6104', '3C8AB0', '3C94D5', '40A677', '40B4F0', '44F477', '4C9614', '50C58D', '541E56', '54E032', '5C4527', '5C5EAB', '64649B', '648788', '7819F7', '78FE3D', '80711F', '841888', '84B59C', '88A25E', '88E0F3', 'A8D0E5', 'AC4BC8', 'B0A86E', 'B0C69A', 'CCE17F', 'DC38E1', 'F01C2D', 'F4B52F', 'F8C001');
    public $interfaceConfig;
    public $showCdpNeighborDetails;
    public $showISISNeighborDetails;
    public $showCdpNeighbor;
    public $showIpInterfaceBrief;
    public $showVersion;
    public $showArp;
    public $enableCommands = array();
    public $disableCommands = array();
    public $isisNeighbourIps = array();
    public $enableProcedure = array();
    public $disableProcedure = array();
    public $loopback0;
    public $collectionId;
    public $status;
    public $hostname;
    public $otherCommand = array();
    public $juniperDevices = array();
    public $showISISNeighMac;
    public $_telnet;
    public $juniperDeviceDetails = array();
    public $showBridgeDomainDetails = array();

    const CDP_CONFIGURE_COLLECT_COMMAND = "RJILAuto::Gearman::CDPCollect::run";
    const CDP_CONFIGURE_ENABLE_COMMAND = "RJILAuto::Gearman::CDPEnable::run";
    const CDP_CONFIGURE_DISABLE_COMMAND = "RJILAuto::Gearman::CDPDisable::run";

    public static $baseCommands = array(
        array('name' => 'gig_interface_config', 'command' => 'sh run | i GigabitEthernet|service instance|encapsulation untagged'),
        array('name' => 'show_cdp_neighbor_detail', 'command' => 'show cdp neighbor detail'),
        array('name' => 'show_isis_neighbors', 'command' => 'sh isis nei'),
        array('name' => 'show_ip_interface_brief', 'command' => 'show ip interface brief'),
        array('name' => 'show_version', 'command' => 'show version'),
        array('name' => 'show_isis_neighbors_detail', 'command' => 'sh isis neighbors detail | i L1|L2|SNPA'),
        array('name' => 'show_bridge_domain_detail', 'command' => 'show bridge-domain | inc Bridge-domain|service'),
    );

    public function configureCdp($data) {
        $commands = self::$baseCommands;
        $this->enableCommands = array();
        $this->disableCommands = array();
        $loopback0 = $data['loopback0'];
        echo "\nIP Address: " . $loopback0;
        $this->interfaceConfig = null;
        $this->showCdpNeighborDetails = null;
        $this->showCdpNeighbor = null;
        $this->showVersion = null;
        $this->doCollect($loopback0, $commands);
//        if (empty($this->interfaceConfig)) {
//            $this->doSecondaryCollect($loopback0, $commands);
//        }
        $data['input'] = '';
        if (stripos($this->showVersion, 'ASR9K') && !empty($this->showISISNeighborDetails)) {
            $data['input'] = $this->showISISNeighborDetails;
            $data['version'] = 'ASR9K';
            print "\nVersion ASR9K";
            print "\nCreating Config commands with\n" . $this->showISISNeighborDetails;
        } elseif ($this->interfaceConfig) {
            $data['input'] = $this->interfaceConfig;
            $data['version'] = 'Other';
            print "\nVersion Other";
            print "\nCreating Config commands with\n" . $this->interfaceConfig;
        }
        if (!empty($data['input'])) {
            $this->prepareISISNeighbourDetails();
            if ($data['version'] == "ASR9K") {
                $this->prepareCdpFromIsis($data);
            } else {
                $this->prepareCdpConfiguration($data);
            }
            $this->saveCollectionState($data, "CollectionComplete"); // AG1 and CSS Specific.. not declared in base class
        } else {
            echo "\nNo response.";
        }
    }

    public function onConfigCollectComplete($task) {
        echo "\nCOMPLETE: Config collection";
        $data = json_decode($task->data());
        if (!empty($data) && property_exists($data, 'host') and property_exists($data, 'result')) {
            foreach ($data->result as $key => $val) {
                switch ($key) {

                    case "gig_interface_config":
                        $this->interfaceConfig = implode("", $val);
                        break;
                    case "show_cdp_neighbor_detail":
                        $this->showCdpNeighborDetails = implode("", $val);
                        break;
                    case "show_isis_neighbors":
                        $this->showISISNeighborDetails = implode("", $val);
                        break;
                    case "show_cdp_neighbor":
                        $this->showCdpNeighbor = implode("", $val);
                        break;
                    case "show_ip_interface_brief":
                        $this->showIpInterfaceBrief = implode("", $val);
                        break;
                    case "show_arp":
                        $this->showArp = implode("", $val);
                        break;
                    case "show_isis_neighbors_detail":
                        $this->showISISNeighMac = implode("", $val);
                        break;
                    case "show_version":
                        $this->showVersion = implode("", $val);
                        break;
                    case "show_bridge_domain_detail":
                        $this->showBridgeDomainDetails = implode("", $val);
                        break;
                    default :
                        $this->otherCommand[$key] = implode("", $val);
                }
            }
        }
    }

    public function onConfigCollectFailure($task) {
        echo "\nFailed: Config collection";
//        $data = $task->data();
//        $data = json_decode($data, true);
//        $loopback = $data['host'];
//        $telnet = new TELNET();
//        $ping = $telnet->ping($loopback);
//        if ($ping == 'online') {
//            $this->doSecondaryCollect($data['host'], $data['commands']);
//        }
//        print "\n" . $task->error();
    }

    public function onSecConfigCollectFailure($task) {
        echo "\nSecondary Failed: Config collection";
//        print "\n" . $task->error();
    }

    public function prepareCdpFromIsis($data) {
        $input = $data['input'];
        print "\nCollection Input: \n" . $input;
        $lines = explode("\n", $input);
        if (is_array($lines)) {
            $this->enableCommands[] = "config t";
            $this->disableCommands[] = "config t";
            foreach ($lines as $i => $line) {
                $line = trim($line);
                if (preg_match('/^[a-zA-Z]{8}PAR[0-9]{3}$/', substr($line, 0, 14))) {
                    $line = preg_replace('/\s+/', " ", $line);
                    $cells = explode(" ", $line);
                    if (!empty($cells[1]) && in_array(substr($cells[1], 0, 1), array('G', 'g', 'T', 't'))) {
                        $this->enableCommands[] = "interface {$cells[1]}";
                        $this->enableCommands[] = "cdp";
                        $this->disableCommands[] = "interface {$cells[1]}";
                        $this->disableCommands[] = "no cdp";
                    }
                }
            }
            $this->enableCommands[] = "end";
            $this->disableCommands[] = "end";
        }
    }

    public function prepareCdpConfiguration($data) {
        $input = $data['input'];
        print "\nCollection Input: \n" . $input;
        $lines = explode("\n", $input);
        if (is_array($lines)) {
            $this->enableCommands[] = "config t";
            $this->enableCommands[] = "cdp run";
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
                                # enable                                
                                $this->enableCommands[] = trim($line);
                                $this->enableCommands[] = "cdp enable";
                                $this->enableCommands[] = trim($previousLine);
                                $this->enableCommands[] = "l2protocol peer cdp";
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
                    if (!in_array($interfaceLine, $this->enableCommands)) {

                        $this->enableCommands[] = $interfaceLine;
                        $this->enableCommands[] = "cdp enable";

                        $this->disableCommands[] = $interfaceLine;
                        $this->disableCommands[] = "no cdp enable";
                    }
                }
            }

            $this->enableCommands[] = "end";
            $this->disableCommands[] = "end";
        }
    }

    public function doCollect($loopback0, $commands) {
        $this->_collectionEngine = new CCollectionEngineClient(CCollectionEngineClient::DEFAULT_COLLECTION_SERVERS);
        $this->_collectionEngine->setCompleteCallback(array($this, 'onConfigCollectComplete'));
        $this->_collectionEngine->setFailCallback(array($this, 'onConfigCollectFailure'));
        $this->_collectionEngine->collect($loopback0, $commands);
    }

    public function doSecondaryCollect($loopback0, $commands) {
        if (!empty($loopback0) && is_array($commands)) {
            $collectionEngine = new CGearmanClient();
            $collectionEngine->setCompleteCallback(array($this, 'onConfigCollectComplete'));
            $collectionEngine->setFailCallback(array($this, 'onSecConfigCollectFailure'));
            $input = array('host' => $loopback0, 'commands' => $commands);
            $inputJson = json_encode($input);
            $collectionEngine->getClient()->addTask(CCollectionEngineClient::COLLECTION_ENGINE_COMMAND_COLLECT, $inputJson, null, time() . "_" . uniqid('collect-'));
            $collectionEngine->getClient()->runTasks();
        }
    }

    public function captureCdpNeighbourDetails() {
        $commands = array(
            array('name' => "show_cdp_neighbor", "command" => "show cdp neighbor"),
            array('name' => "show_cdp_neighbor_detail", "command" => "show cdp neighbor detail")
        );
        $this->doCollect($this->loopback0, $commands);
    }

    public function captureShowArp() {
        $commands = array(
            array('name' => "show_arp", "command" => "show arp")
        );
        $this->doCollect($this->loopback0, $commands);
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
            if (empty($return)) {
                print "\nTrying to find loopback planned";
                $loopback0 = NddRanLb::model()->find(array("select" => "ipv4", "condition" => "host_name=:host_name", "params" => array(":host_name" => $hostname)));
                if (!empty($loopback0) && !empty($loopback0->ipv4)) {
                    $return = $loopback0->ipv4;
                }
            }
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

    public function getJuniperDevicesFromISISneigh() {
        $return = array();
        if (!empty($this->showISISNeighMac)) {
            $lines = explode("\n", $this->showISISNeighMac);
            $count = 0;
            foreach ($lines as $i => $line) {
                $count++;
                $row = trim($line);
                if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $row) && preg_match('/^[a-zA-Z]{11}[0-9]{3}$/', substr($row, 0, 14)) && !empty($lines[$count])) {
                    $macLine = trim($lines[$count]);
                    if (preg_match_all('/SNPA:\s+(.*)/', $macLine, $match)) {
                        if (!empty($match[1][0])) {
                            $mac = substr(str_replace(".", "", $match[1][0]), 0, 6);
                            $mac = strtoupper($mac);
                            if (!empty($mac) && in_array($mac, $this->juniperMac)) {
                                $return[] = substr($row, 0, 14);
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

    public function prepareISISNeighbourDetails() {
        $isisNeighbourIps = array();
        $juniperDevices = $this->getJuniperDevicesFromISISneigh();
//        $this->juniperDevices = $return;
        if (!empty($this->showISISNeighborDetails)) {
            $lines = explode("\n", $this->showISISNeighborDetails);
            if (is_array($lines)) {
                $taken = array();
                foreach ($lines as $i => $line) {
                    $loopback0 = NULL;
                    $row = trim($line);
//                    preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $row, $match)
//                    (preg_match('/^[a-zA-Z]{11}[0-9]{3}$/', substr($row, 0, 14)) 
                    if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $row, $remoteInterfaceIpMatches) && !in_array(substr($row, 0, 14), $taken)) {
                        $remoteIntIP = $remoteInterfaceIpMatches[0];
                        $hostname = substr($row, 0, 14);
                        $bdi = "";
                        $line = preg_replace('/\s+/', " ", $row);
                        $cells = explode(" ", $line);
                        if (!empty($cells[2])) {
                            $bdi = $cells[2];
                        }
                        $loopback0 = $this->getLoopback($hostname, $bdi);
                        if (in_array($hostname, $juniperDevices) && !empty($loopback0)) {
                            $localInterface = $this->getLocalInterface($bdi);
                            $this->juniperDevices[$hostname] = $loopback0;
                            $this->juniperDeviceDetails[$hostname]['interface_ip'] = $remoteIntIP;
                            $this->juniperDeviceDetails[$hostname]['loopback0'] = $loopback0;
                            $this->juniperDeviceDetails[$hostname]['local_interface'] = ""; #Compute later
                            $this->juniperDeviceDetails[$hostname]['remote_interface'] = $localInterface;
                        } elseif (!empty($loopback0)) {
                            $isisNeighbourIps[] = $loopback0;
                        }
                        $taken[] = $hostname;
                    }
                }
            }
        }
        $this->isisNeighbourIps = $isisNeighbourIps;
    }

    public function disableCdp($data) {
        if (!empty($data['device']) and ! empty($data['procedure'])) {
            echo "\nDisable CDP for [{$data['device']}]: \n" . implode("\r\n", $data['procedure']);
            $key = $data['device'];
            $value = $data['procedure'];
            $this->_telnet = new TELNET();
            $commit = '';
            $loopback = $data['loopback'];
            if (substr($key, 0, 2) == '49' && !empty($loopback)) {
                $hostname = $this->telnetLogin($loopback);
                if (!empty($hostname)) {
                    $telnet_arry = $this->_telnet->GetOutputOf("telnet {$key}");
                    foreach ($telnet_arry as $telnet_val) {
                        if (preg_match("/Username/", $telnet_val) || preg_match("/username/", $telnet_val)) {
                            $login_flag = 1;
                        }
                    }
                    if ($login_flag == 1) {
                        $commit = "commit";
                        $hostname = $this->telnetLogin($key, 1);
                        $hostname = substr($hostname, -14);
                    } else {
                        return false;
                        print "\n" . "Failed to connect [{$key}]:";
                    }
                }
            } else {
                $hostname = $this->telnetLogin($key);
            }

            $hostname = $this->telnetLogin($key);
            if (!empty($hostname)) {
                if (!empty($commit)) {
                    $end = array_pop($value);
                    $value[] = $commit;
                    $value[] = $end;
                }
                $this->hostname = $hostname;
                print "\nHostname of {$key} is {$hostname}";
                $commands = implode("\r\n", $value);
                $rows = $this->_telnet->GetOutputOf($commands);
                print implode("\r\n", $rows) . "\n";
                $rows = $this->_telnet->GetOutputOf("exit");
                print implode("\r\n", $rows) . "\n";
            } else {
                print "\nCould not login on {$key}: ";
            }
            return true;
        }
        return false;
    }

    public function telnetLogin($loopback0, $connect = 0) {
        if (!empty($loopback0)) {
            if (!$connect) {
                $connect = $this->_telnet->Connect($loopback0);
            }
            if ($connect == 1) {
                $logArray = $this->_telnet->LogInGeneric();
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

    public function telnetLoginJuniper($loopback0) {
        if (!empty($loopback0)) {
            $promptOut = $this->_telnet->GetOutputOf("telnet {$loopback0}\n");
            $login_flag = false;
            foreach ($promptOut as $telnet_val) {
                if (preg_match("/login: /", $telnet_val) || preg_match("/login:/", $telnet_val)) {
                    $login_flag = true;
                }
            }
            if ($login_flag) {
                $loginOut = $this->_telnet->GetOutputOf("rjil\n");
                $loginOut = $this->_telnet->GetOutputOf("Rjil123\n");
                $promptOut = array_pop($loginOut);
                $routername = null;
                if (strpos($promptOut, '>') !== false) {
                    if (preg_match_all("/^rjil@(.*)\>/", $promptOut, $matches)) {
                        $routername = isset($matches[1][0]) ? $matches[1][0] : null;
                    }
                }
                if (empty($promptOut) && empty($routername)) {
                    print "\nCould not connect {$loopback0}: ";
                } else if (!empty($promptOut) && empty($routername)) {
                    print "\nHostname not found";
                }
                return $routername;
            } else {
                print "\nCould not connect {$loopback0}: ";
            }
        }
        return FALSE;
    }

    public function getLocalInterface($bdi) {
        $interface = null;
        if (!empty($this->showBridgeDomainDetails) && !empty($bdi)) {
            $bridgeDomainLines = explode("\n", $this->showBridgeDomainDetails);
            $bdi = str_replace("BD", "", $bdi);
            foreach ($bridgeDomainLines as $i => $line) {
                $line = trim($line);
                if (preg_match_all("/^Bridge-domain\s{$bdi}\s/", $line)) {
                    $nextkey = ++$i;
                    $nextLine = isset($bridgeDomainLines[$nextkey]) ? $bridgeDomainLines[$nextkey] : null;
                    $nextLine = trim($nextLine);
                    if (preg_match_all("/^GigabitEthernet(.*)/", $nextLine, $intMatches) || preg_match_all("/^TenGigabitEthernet(.*)/", $nextLine, $intMatches)) {
                        $tempArr = explode(" ", $nextLine);
                        $interface = isset($tempArr[0]) ? $tempArr[0] : null;
                    }
                    break;
                }
            }
        }
        return $interface;
    }

    public function captureJuniperDetails() {
        if (!empty($this->juniperDeviceDetails)) {
            print "\nCapturing Juniper Details:";
            print_r($this->juniperDeviceDetails);
            foreach ($this->juniperDeviceDetails as $hnKey => $juniperDevice) {
                $juniperHost = $this->telnetLoginJuniper($juniperDevice['loopback0'], 0);
                if ($juniperHost === $hnKey) {
                    $output = $this->_telnet->GetOutputOf("show interface terse | match {$juniperDevice['interface_ip']}");
                    $interfaceLine = $output[1];
                    $interfaceLineArr = explode(" ", $interfaceLine);
                    if (!empty($interfaceLineArr[0])) {
                        $interface = trim($interfaceLineArr[0]);
                        $this->juniperDeviceDetails[$hnKey]['local_interface'] = $interface;
                    } else {
                        unset($this->juniperDeviceDetails[$hnKey]);
                    }
                }
            }
            print "\nCaptured Juniper Details:";
            print_r($this->juniperDeviceDetails);
            $this->saveJuniperDetails();
        }
    }

}
