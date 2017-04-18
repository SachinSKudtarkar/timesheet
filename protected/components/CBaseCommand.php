<?php

/**
 * Description of CBaseCommand
 *
 * This is a general purpose command which can be used externally.
 * 
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class CBaseCommand extends CConsoleCommand {

    public function actionGenerateHostname($neid = '') {
        printf("\nNEID:" . $neid);
        $hostname = NddGis::model()->generateGISHostname($neid);
        printf("\nHostname:" . $hostname);
        printf("\n");
    }

    private $_controller = false;

    public function getController() {
        if ($this->_controller === false)
            $this->_controller = new Controller('BaseController');

        return $this->_controller;
    }

    public function getViewRenderer() {
        return null;
    }

    public function getViewPath() {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . 'views';
    }

    public function getTheme() {
        return NULL;
    }

}
