<?php

class CustomBehavior extends CBehavior {

    public function attach($owner) {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }

    public function handleBeginRequest($event) {
       // CHelper::debug(Yii::app()->session['login']);
         //CHelper::debug(Yii::app()->session['login']);
        
    }

}

?>