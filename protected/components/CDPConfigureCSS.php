<?php

/**
 * Description of CDPConfigureCSS
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class CDPConfigureCSS extends CDPConfigureBase {

    public function saveCollectionState($data, $status = "") {
        //'id, hostname, loopback, interface_output, enable_command, disable_command,  
        //cdp_neighbor, isis_neighbor, log, status, created_by, created_at, modified_at
        $cdpCssConfigure = new CdpCssConfigure;
        $cdpCssConfigure->isNewRecord = TRUE;
        $cdpCssConfigure->loopback = $data['loopback0'];
        $cdpCssConfigure->parent_loopback = $data['parent_loopback0'];
        $cdpCssConfigure->ag1_ref_id = $data['parent_collection_id'];
        $cdpCssConfigure->status = $status;
        $cdpCssConfigure->interface_output = $this->interfaceConfig;
        $cdpCssConfigure->cdp_neighbor = $this->showCdpNeighborDetails;
        $cdpCssConfigure->isis_neighbor = $this->showISISNeighborDetails;
        $cdpCssConfigure->enable_command = implode("\r\n", $this->enableCommands);
        $cdpCssConfigure->disable_command = implode("\r\n", $this->disableCommands);
        $cdpCssConfigure->created_at = date("Y-m-d H:i:s");
        $cdpCssConfigure->created_by = $data['userid'];
        if ($cdpCssConfigure->save(false)) {
            $this->collectionId = $cdpCssConfigure->id;
        }
//        $this->updateHotoCDP($cdpCssConfigure->loopback);
    }

    public function updateStatus($status) {
        $CdpCssConfigure = CdpCssConfigure::model()->findByPk($this->collectionId);
        if (!empty($CdpCssConfigure)) {
            $CdpCssConfigure->status = $status;
            $CdpCssConfigure->save(false);
        }
    }

    public function saveFailedStatus($data) {
        $cdpCssConfigure = new CdpCssConfigure;
        $cdpCssConfigure->status = "No response for collection OR No isis neighbors found";
        $cdpCssConfigure->loopback = $data['loopback0'];
        $cdpCssConfigure->parent_loopback = !empty($data['parent_loopback0']) ? $data['parent_loopback0'] : 'x.x.x.x';
        $cdpCssConfigure->ag1_ref_id = $data['parent_collection_id'];
        $cdpCssConfigure->created_at = date("Y-m-d H:i:s");
        $cdpCssConfigure->created_by = $data['userid'];
        $cdpCssConfigure->is_active = 0;
        $cdpCssConfigure->save(FALSE);
        $this->collectionId = $cdpCssConfigure->id;
//        $this->updateHotoCDP($cdpCssConfigure->loopback);
    }

    public function updateHotoCDP($ipv4) {
        $output = Yii::app()->db->createCommand("SELECT id FROM tbl_hoto_cdp_details WHERE loopback0 = '$ipv4'")->queryRow();
        if (!empty($output)) {
            echo "Data Found in Hoto CDP.";
            Yii::app()->db->createCommand("UPDATE tbl_hoto_cdp_details SET status = 'Complete' WHERE id = " . $output['id'])->execute();
        }
    }

}
