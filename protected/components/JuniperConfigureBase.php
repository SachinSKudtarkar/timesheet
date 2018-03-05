<?php

require(Yii::app()->basePath . "/controllers/telnet_new_class.php");

/**
 * Description of CDPConfigureBase
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class JuniperConfigureBase {
    
    public $interfaceConfig;
    public $showCdpNeighborDetails;
    public $showISISNeighborDetails;
    public $showCdpNeighbor;
    public $enableCommands = array();
    public $disableCommands = array();
    public $isisNeighbourIps = array();
    public $enableProcedure = array();
    public $disableProcedure = array();
    public $loopback0;
    public $collectionId;
    public $status;

    const JUNIPER_CONFIGURE_COLLECT_COMMAND = "RJILAuto::Gearman::JNPCollect::run";
    const JUNIPER_CONFIGURE_ENABLE_COMMAND = "RJILAuto::Gearman::JNPEnable::run";
    const JUNIPER_CONFIGURE_DISABLE_COMMAND = "RJILAuto::Gearman::JNPDisable::run";

    public function configureCdp($data) {
        
        $commands = array(
            array('name' => 'gig_interface_config', 'command' => 'sh run | i GigabitEthernet|service instance|encapsulation untagged'),
            array('name' => 'show_cdp_neighbor_detail', 'command' => 'show cdp neighbor detail'),
            array('name' => 'show_isis_neighbors', 'command' => 'sh isis nei')
        );
        $this->enableCommands = array();
        $this->disableCommands = array();
        $loopback0 = $data['loopback0'];
        echo "\nIP Address: " . $loopback0;
        $this->interfaceConfig = null;
        $this->showCdpNeighborDetails = null;
        $this->showCdpNeighbor = null;
        $this->doCollect($loopback0, $commands);
        print "\n" . $this->interfaceConfig;
        if (!empty($this->interfaceConfig)) {
            $data['input'] = $this->interfaceConfig;
//            $this->prepareISISNeighbourDetails();
            $this->prepareCdpConfiguration($data);
            $status = 'CollectionComplete'; // AG1 and CSS Specific.. not declared in base class
        } else {
            echo "\nNo response.";
            $status = 'CollectionFailed';
        }
        $this->status = $status;
        $this->saveCollectionState($data, $status);
        return $status;
    }

    public function onConfigCollectComplete($task) {
        echo "\nCOMPLETE: Config collection";
        $data = json_decode($task->data());

        if (property_exists($data, 'host') and property_exists($data, 'result')) {
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
                }
            }
        }
    }

    public function onConfigCollectFailure($task) {
        echo "\nFailed: Config collection";
    }

//    public function prepareCdpConfiguration($data) {
//        $input = $data['input'];
//        print "\nCollection Input: \n" . $input;
//        $lines = explode("\n", $input);
//        if (is_array($lines)) {
//            $this->enableCommands[] = "config t";
//            $this->enableCommands[] = "cdp run";
//            $this->disableCommands[] = "config t";
//            $this->disableCommands[] = "no cdp run";
//            foreach ($lines as $i => $line) {
//                if (preg_match_all("/interface GigabitEthernet(.*)/", $line, $intMatches) || preg_match_all("/interface TenGigabitEthernet(.*)/", $line, $intMatches)) {
//                    $key = $i;
//                    while (1) {
//                        $key++;
//                        if (!isset($lines[$key]) || (isset($lines[$key]) && stripos($lines[$key], "interface") !== false)) {
//                            break;
//                        }
//                        $nextLine = $lines[$key];
//                        if (preg_match_all("/encapsulation untagged/", $nextLine)) {
//                            $previousLine = $lines[$key - 1];
//                            if (preg_match_all("/service instance ([0-9]{0,}) ethernet/", $previousLine)) {
//                                # enable                                
//                                $this->enableCommands[] = trim($line);
//                                $this->enableCommands[] = "cdp enable";
//                                $this->enableCommands[] = trim($previousLine);
//                                $this->enableCommands[] = "l2protocol peer cdp";
//                                # disable                                
//                                $this->disableCommands[] = trim($line);
//                                $this->disableCommands[] = "no cdp enable";
//                                $this->disableCommands[] = trim($previousLine);
//                                $this->disableCommands[] = "no l2protocol peer cdp";
//                            }
//                        }
//                    }
//                }
//            }
//            $this->enableCommands[] = "end";
//            $this->disableCommands[] = "end";
//        }
//    }

    public function doCollect($loopback0, $commands) {
        echo "in doCollect...";
        $this->_collectionEngine = new CCollectionEngineClient(CCollectionEngineClient::DEFAULT_COLLECTION_SERVERS);
        $this->_collectionEngine->setCompleteCallback(array($this, 'onConfigCollectComplete'));
        $this->_collectionEngine->setFailCallback(array($this, 'onConfigCollectFailure'));
        $this->_collectionEngine->collect($loopback0, $commands);
    }

//    public function captureCdpNeighbourDetails() {
//        $commands = array(
//            array('name' => "show_cdp_neighbor", "command" => "show interfaces terse | match "),
//            array('name' => "show_cdp_neighbor_detail", "command" => "show cdp neighbor detail")
//        );
//        $this->doCollect($this->loopback0, $commands);
//    }
//    public function telnetLogin($loopback0) {
//        if (!empty($loopback0)) {
//            $connect = $this->_telnet->Connect($loopback0);
//            if ($connect == 1) {
//                $logArray = $this->_telnet->LogIn();
//                $routername = array_pop($logArray);
//                if (strpos($routername, '>') !== false) {
//                    $pass = "rjil123";
//                    $this->_telnet->GetOutputOf("en");
//                    $logArray = $this->_telnet->GetOutputOf($pass);
//                    $routername = array_pop($logArray);
//                }
//                if (strpos($routername, '#') !== false) {
//                    $routername = str_replace("rjil123", "", str_replace("#", "", $routername));
//                    $routername = trim(str_replace("Rjil123", "", str_replace("#", "", $routername)));
//                    return $routername;
//                }
//            } else {
//                print "\nCould not connect {$loopback0}: " . $connect;
//            }
//        }
//        return FALSE;
//    }
//    public function updateStatus($status = '') {
//        if (!empty($this->collectionId) && !empty($status)) {
//            CdpConfigure::model()->updateByPk($this->collectionId, array('status' => $status));
//        }
//    }
}
