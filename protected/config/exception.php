<?php
return function($event)
        {
            if ($event->exception instanceof CDbException)
            {
               $module =  Yii::app()->controller->module->id;  
               Yii::app()->request->redirect(CHelper::baseUrl(true).'/'.$module);
              // true means, mark the event as handled so that no other handler will
              // process the event (the Yii exception handler included).
              $event->handled = true;

            }
        };
//echo $route = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
/*exit;*/