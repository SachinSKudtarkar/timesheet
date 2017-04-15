<?php

require_once Yii::app()->basePath . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'telnet_new_class.php';

/**
 * Description of DhcpIpv6Update
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class DhcpIpv6Update {

    public $loopback0;
    public $ipv6;
    public $collectionOutput;
    public $updateCommands = array();
    public $hostname;
    public $_telnet;
    public $logFilename;

    public function updateOnDevice() {
        $this->logFilename = time() . "_" . $this->loopback0 . ".log";
        $this->collectionOutput = null;
        $this->updateCommands = array();
        if (!empty($this->loopback0) && !empty($this->ipv6)) {
            $this->log("\nStarted updating dhcp ipv6 " . $this->ipv6 . " on " . $this->loopback0);
            $this->collect();
            $this->prepareConfiguration();
            $this->pushConfiguration();
        }
    }

    public function collect() {
        $commands = array(
            array("name" => "interface_bdi_103", "command" => "sh run interface BDI 103"),
            array("name" => "interface_vlan_103", "command" => "sh run interface VLAN 103")
        );
        $collectionEngine = new CCollectionEngineClient(CCollectionEngineClient::DEFAULT_COLLECTION_SERVERS);
        $collectionEngine->setCompleteCallback(array($this, "onCollectionComplete"));
        $collectionEngine->setFailCallback(array($this, "onCollectionFailure"));
        $collectionEngine->collect($this->loopback0, $commands);
    }

    public function onCollectionFailure($task) {
        echo "\nCollection Failed";
    }

    public function onCollectionComplete($task) {
        $this->log("\nCollection complete.");
        $data = json_decode($task->data());
        if (property_exists($data, 'host') and property_exists($data, 'result')) {
            foreach ($data->result as $key => $value) {
                $value = implode("", $value);
                if (!preg_match_all('/Invalid input detected/', $value)) {
                    $this->collectionOutput = $value;
                }
            }
        }
        $this->log("\nCollection result:\n" . $this->collectionOutput);
    }

    public function prepareConfiguration() {
        if (!empty($this->collectionOutput)) {
            $lines = explode("\n", $this->collectionOutput);
            if (!empty($lines)) {
                $interface = null;
                $ipv6 = null;
                $currentIpv6Command = null;
                foreach ($lines as $i => $line) {
                    $line = trim($line);
                    if (preg_match_all("/interface(.*)/", $line, $intMatches)) {
                        $interface = $line;
                    }
                    if (preg_match_all("/ipv6 dhcp relay destination (.*)/", $line, $ipv6Matches)) {
                        $currentIpv6Command = $line;
                        if (isset($ipv6Matches[1][0])) {
                            $ipv6 = $ipv6Matches[1][0];
                            break;
                        }
                    }
                }
                if (!empty($interface) && $ipv6 != $this->ipv6) {
                    $this->updateCommands[] = "config t";
                    $this->updateCommands[] = $interface;
                    if (!empty($currentIpv6Command)) {
                        $this->updateCommands[] = "no " . $currentIpv6Command;
                    }
                    $this->updateCommands[] = "ipv6 dhcp relay destination " . $this->ipv6;
                    $this->updateCommands[] = "end";
                } else {
                    $this->log("\nNo Change");
                }
            }
        }
        if (!empty($this->updateCommands)) {
            $this->log("\nUpdate Config commands:\n" . implode("\r\n", $this->updateCommands));
        }
    }

    public function pushConfiguration() {
        if (!empty($this->updateCommands)) {
            $this->_telnet = new TELNET();
            $hostname = $this->telnetLogin($this->loopback0);
            if (!empty($hostname)) {
                $this->log("\nHostname of {$this->loopback0} is {$hostname}");
                if ($this->hostname != $hostname) {
                    $this->log("\nHostname mismatch found. Plan hostname is {$hostname}.");
                    $this->log("\nexiting...");
                    return FALSE;
                }
                $commands = implode("\r\n", $this->updateCommands);
                $rows = $this->_telnet->GetOutputOf($commands);
                $this->log(implode("\r\n", $rows) . "\n");
                $rows = $this->_telnet->GetOutputOf("exit");
                $this->log(implode("\r\n", $rows) . "\n");
                return true;
            } else {
                $this->log("\nCould not login on {$this->loopback0}: ");
            }
        }
        return false;
    }

    public function telnetLogin($loopback0) {
        if (!empty($loopback0)) {
            $connect = $this->_telnet->Connect($loopback0);
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
                $this->log("\nCould not connect {$loopback0}: " . $connect);
            }
        }
        return FALSE;
    }

    public function log($message = "") {
        print $message;
        $dir = Yii::app()->basePath . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "dhcp_ipv6_update_logs";
        if (!file_exists($dir)) {
            CHelper::createDirectory($dir);
        }
        $file = $dir . DIRECTORY_SEPARATOR . $this->logFilename;
        @file_put_contents($file, $message);
    }

}
