<?php

/**
 * Description of CConsoleBehavior
 *
 * @author Shriram Jadhav <shriramjadhav@benchmarkitsolutions.com>
 */
class CConsoleBehavior extends CBehavior {

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
