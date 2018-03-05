<?php

/**
 * Description of DcAutomation
 * 
 * Data Provider Class for DcAutomation report
 *
 * @author Manoj Kumar
 */
class DcAutomationReportData {

    public $deltaConfig = false;
    public $context = null;
    public $additionalParams = array();
    public $data = array();
    public $startupConfig = false;

    public function getNipContentById($id) {
        return $this->getNipContentTxt($id);
    }

    public function getNipContentTxt($id = null) {
        $this->startupConfig = false;
        $this->deltaConfig = false;
        $this->loadData($id);
        $output = $this->getNipContent($this->data);
        return $output;
    }

    public function getNipContent(DatacenterOutputmaster $data) {
        $devicelayer = strtolower($data->dcInputMaster->device_layer);
        $tempname = null;
        $viewParams = array('model' => $data);
        if ($this->startupConfig) {
            $tempname = "startup_config/{$devicelayer}_conf";
        } elseif ($this->deltaConfig) {
            $tempname = "delta_config/" . $devicelayer . "_conf_on_9508";
            $viewParams = array_merge($viewParams, array('additionalParams' => $this->additionalParams));
        } else {
            if ($devicelayer === 'l2') {
                $tempname = "l2" . "_nip";
            } else if ($devicelayer === 'l3') {
                $tempname = $devicelayer . "_nip";
            }
        }

        if (empty($tempname)) {
            throw new Exception('NIP template unavailable for given device layer');
        }

        if (isset(Yii::app()->controller))
            $controller = Yii::app()->controller;
        else
            $controller = new CController('site');

        $viewPath = Yii::getPathOfAlias('application.views.dcAutomation' . '.' . $tempname) . '.php';
        $output = $controller->renderInternal($viewPath, $viewParams, true);
        $output = CommonUtility::convertNIPHtmlToText($output, 'UTF-8', true);
        return $output;
    }

    public function loadData($id = null) {
        $this->data = null;
        if (!is_null($id)) {
            $criteria = new CDbCriteria();
            $criteria->with = "dcInputMaster";
            $criteria->condition = "t.id=:id";
            $criteria->params = array(':id' => $id);
            $result = DatacenterOutputmaster::model()->find($criteria);
            $this->data = $result;
            if (empty($result)) {
                throw new Exception('Data not found for given device');
            }
        }
        return $this;
    }

    public function getPrimaryParentDeltaConfigContent() {
        if ($this->data instanceof DatacenterOutputmaster) {
            $this->deltaConfig = true;
            $this->additionalParams['b_end_hostname'] = $this->data->int_eth_2_1_rem_hostname;
            return $this->getNipContent($this->data);
        }
        return null;
    }

    public function getSecondaryParentDeltaConfigContent() {
        if ($this->data instanceof DatacenterOutputmaster) {
            $this->deltaConfig = true;
            $this->additionalParams['b_end_hostname'] = $this->data->int_eth_2_2_rem_hostname;
            return $this->getNipContent($this->data);
        }
        return null;
    }

    public function getStartupConfig() {
        if ($this->data instanceof DatacenterOutputmaster) {
            $this->startupConfig = true;
            return $this->getNipContent($this->data);
        }
    }

}

?>