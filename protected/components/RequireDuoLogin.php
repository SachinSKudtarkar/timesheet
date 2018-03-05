<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RequireDuoLogin extends CBehavior {

    public function attach($owner) {

        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }

    public function handleBeginRequest($event) {
        $app = Yii::app();
        $request = $app->urlManager->parseUrl(Yii::app()->request);
        $requestArr = explode("/", $request);
        $controllerId = null;
        $actionId = null;
        if (isset($requestArr[0]))
            $controllerId = $requestArr[0];
        if (isset($requestArr[1]))
            $actionId = $requestArr[1];
        $duo_auth = Yii::app()->session['duo_auth'];
//        if ($controllerId != 'login' && (stristr($controllerId, 'nddTempInput') === false) && (stristr($controllerId, 'nddMetroCSSInput') === false) && (stristr($controllerId, 'nddRcomInput') === false) && (stristr($controllerId, 'ndd') != false) && (strpos($actionId, 'create') != false || stristr($actionId, 'upload') != false ) && $duo_auth == FALSE) {
//          $user_id = Yii::app()->session['login']['user_id'];
//            //$model = Employee::model()->findByPk($user_id);
//            //Yii::app()->session['login']['is_duo_added'];                        
//            //Yii::app()->request->redirect(Yii::app()->createUrl('duologin/auth'));
		return true;
//        } else {
          return true;
//        }
    }

}

?>
