<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EHttpRequest
 *
 * @author parag
 */
class EHttpRequest extends CHttpRequest {
    
    public $noCsrfValidationRoutes = array();
     
    public function validateCsrfToken($event) {
        if ($this->getIsPostRequest() ||
                $this->getIsPutRequest() ||
                $this->getIsDeleteRequest()) {
            $cookies = $this->getCookies();

            $method = $this->getRequestType();
            switch ($method) {
                case 'POST':
                    $userToken = $this->getPost($this->csrfTokenName);
                    break;
                case 'PUT':
                    $userToken = $this->getPut($this->csrfTokenName);
                    break;
                case 'DELETE':
                    $userToken = $this->getDelete($this->csrfTokenName);
            }

            if (empty($userToken) && !empty($this->getParam($this->csrfTokenName))) {
                $userToken = $this->getParam($this->csrfTokenName);
            }

            if (!empty($userToken) && $cookies->contains($this->csrfTokenName)) {
                $cookieToken = $cookies->itemAt($this->csrfTokenName)->value;
                $valid = $cookieToken === $userToken;
            } else
                $valid = false;
            if (!$valid)
                throw new CHttpException(400, Yii::t('yii', 'The CSRF token could not be verified.'));
        }
    }
   
    protected function normalizeRequest() {

        parent::normalizeRequest();
        if ($this->getIsPostRequest()) {
            if($this->enableCsrfValidation &&  $this->checkPaths() !== false)
                Yii::app()->detachEventHandler('onbeginRequest',array($this,'validateCsrfToken'));
        }
    }

    private function checkPaths() {
        foreach ($this->noCsrfValidationRoutes as $checkPath) {
            // allows * in check path
            if(strstr($checkPath, "*")) {
                $pos = strpos($checkPath, "*");
                $checkPath = substr($checkPath, 0, $pos);
                if(strstr($this->pathInfo, $checkPath)) {
                    return true;
                }
            } else {
                if($this->pathInfo == $checkPath) {
                    return true;
                }
            }
        }
        return false;
    }

}
