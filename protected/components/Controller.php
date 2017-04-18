<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Get View Path
     * Method overridden - Handled view not found issue for camelCase views     
     * @return string
     */
    public function getViewPath() {
        if (($module = $this->getModule()) === null)
            $module = Yii::app();

        $path = $module->getViewPath() . DIRECTORY_SEPARATOR . $this->getId();
        if (!file_exists($path)) {
            $class = str_replace('Controller', '', get_class($this));
            if (strtolower($class) === strtolower($this->getId())) {
                $path = $module->getViewPath() . DIRECTORY_SEPARATOR . lcfirst($class);
            }
        }
        return $path;
    }


    public function init() {
        parent::init();
	if(Yii::app()->request->enableCsrfValidation){  
           $this->initAjaxCsrfToken();
	}
	
        $this->setTimeStamp();
        $this->forceChangePassword();
    }
 

    // this function will work to post csrf token.
    protected function initAjaxCsrfToken() {
 
        Yii::app()->clientScript->registerScript('AjaxCsrfToken', ' $.ajaxSetup({
                         data: {"' . Yii::app()->request->csrfTokenName . '": "' . Yii::app()->request->csrfToken . '"},
                         cache:false
                    });', CClientScript::POS_READY);
    }
	
protected function setTimeStamp() {
        if (!Yii::app()->user->isGuest) {
            $session_user_id = Yii::app()->session['login']['user_id'];
            $user = Employee::model()->setActivityStamp($session_user_id);
        }
    }
    
    protected function forceChangePassword(){
        if (!Yii::app()->user->isGuest && !empty(Yii::app()->session['login']['user_id'])) {
            $session_user_id = Yii::app()->session['login']['user_id'];
            $user = Employee::model()->findByPk($session_user_id);
            if(empty($user->change_password) && (Yii::app()->controller->id != 'changepassword' && Yii::app()->controller->id != 'logout')){
                $this->redirect(array('//changepassword/index'));
            }
        }
	}

}