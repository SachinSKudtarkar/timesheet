<?php

/**
 * Description of CDPConfigureAG1
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class JuniperConfigureAG1 extends JuniperConfigureBase {

    public $data = array();

    public function collectJNPConfiguration($data) {
        echo "In JNP config";
        $loopback0 = $data['loopback0'];
        $this->loopback0 = $loopback0;
        $this->configureCdp($data);
        
//        print "\nShow ISIS Neighbors: ";
//        print "\n" . $this->showISISNeighborDetails;
//        $this->enableProcedure[$loopback0] = $this->enableCommands;
////        $this->disableProcedure[$loopback0] = $this->disableCommands;
//        if (!empty($this->isisNeighbourIps)) {
//            if (!empty($this->collectionId)) {
//                $parentCollectionId = $this->collectionId;
//
//                foreach ($this->isisNeighbourIps as $neigh) {
//                    $input = array('loopback0' => $neigh, 'parent_loopback0' => $loopback0, 'parent_collection_id' => $parentCollectionId);
//                    $_cdpConfigureCSS = new CDPConfigureCSS();
//                    $_cdpConfigureCSS->configureCdp($input);
//                    $this->enableProcedure[$neigh] = $_cdpConfigureCSS->enableCommands;
//                    $this->disableProcedure[$neigh] = $_cdpConfigureCSS->disableCommands;
//                }
//                return true;
//            }
//        }
//        return false;
    }

    public function getEnableProcedure() {
        return $this->enableProcedure;
    }

    public function enableCdp() {
        if (!empty($this->enableProcedure)) {
            print "\nEnable Procedure Started: ";
            $count = 0;
            foreach ($this->enableProcedure as $key => $value) {
                $count++;
                print "\n" . "Enable CDP for [{$key}]:";
                print "\n" . implode("\r\n", $value);
                $this->_telnet = new TELNET();
                $hostname = $this->telnetLogin($key);
                $log = "";
                if (!empty($hostname)) {
                    print "\nHostname of {$key} is {$hostname}";
                    $commands = implode("\r\n", $value);
                    $rows = $this->_telnet->GetOutputOf($commands);
                    $log .= implode("\r\n", $rows) . "\r\n";
                    print implode("\r\n", $rows) . "\n";
                    $rows = $this->_telnet->GetOutputOf("exit");
                    $log .= implode("\r\n", $rows) . "\r\n";
                    print implode("\r\n", $rows) . "\n";
                } else {
                    $log .= "\nCould not login on {$key}: ";
                    print "\nCould not login on {$key}: ";
                }
                if ($count === 1 && !empty($this->collectionId) && is_numeric($this->collectionId)) {
                    $model = CdpConfigure::model()->findByPk($this->collectionId);
                    if (!empty($model)) {
                        $model->log = $log;
                        $model->hostname = $hostname;
                        $model->save(false);
                    }
                }
                if ($count > 1 && !empty($this->collectionId) && is_numeric($this->collectionId)) {
                    $model = CdpCssConfigure::model()->findByAttributes(array("ag1_ref_id" => $this->collectionId, "loopback" => $key));
                    if (!empty($model)) {
                        $model->log = $log;
                        $model->hostname = $hostname;
                        $model->save(false);
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function saveCollectionState($data, $status) {
        //'id, hostname, loopback, interface_output, enable_command, disable_command,  
        //cdp_neighbor, isis_neighbor, log, status, created_by, created_at, modified_at
        $models = CdpConfigure::model()->findAllByAttributes(array("loopback" => $data['loopback0']));
        foreach ($models as $model) {
            $modelStatus = CdpConfigure::model()->findByPk($model['id']);
            if (!empty($modelStatus)) {
                $modelStatus->is_active = 0;
                $modelStatus->save(false);
            }
        }
        $cdpConfigure = new CdpConfigure;
        $cdpConfigure->isNewRecord = TRUE;
        $cdpConfigure->loopback = $data['loopback0'];
        $cdpConfigure->status = $status;
        $cdpConfigure->interface_output = $this->interfaceConfig;
        $cdpConfigure->cdp_neighbor = $this->showCdpNeighborDetails;
        $cdpConfigure->isis_neighbor = $this->showISISNeighborDetails;
        $cdpConfigure->enable_command = implode("\r\n", $this->enableCommands);
        $cdpConfigure->disable_command = implode("\r\n", $this->disableCommands);
        $cdpConfigure->created_at = date("Y-m-d H:i:s");
//        $cdpCssConfigure->created_by = $data['userid'];
        $cdpConfigure->save(false);
        $this->collectionId = $cdpConfigure->id;
    }

    public function saveCdpNeighbourDetailsAfterEnable() {
        print "\nSaving cdp neighbors after enabled... ";
        if (!empty($this->collectionId) && is_numeric($this->collectionId)) {
            $CdpConfigure = CdpConfigure::model()->findByPk($this->collectionId);
            if (!empty($CdpConfigure)) {
                $CdpConfigure->cdp_neighbor_after = $this->showCdpNeighborDetails;
                $CdpConfigure->cdp_neighbor_short = $this->showCdpNeighbor;
                $CdpConfigure->save(false);
            }
        }
    }

}
