<?php
/**
 * BaseController is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseController extends Controller
{
	/**
	 * @var string the contain left page which will use for load custome serch page.
	 */
	public $leftpage    ='';
	/**
	 * @var string the contain right page name which will use for load custome right page.
	 */
	public $rightpage   ='';
	
	public $customeModel='';
        
        public $defaultLawFirm = '';
        
        public $autoSaveAttributes = array();
        
	public function init()
	{
            
            #--------------------------------
            # Geting Action Of URI
            #-------------------------------
            
            
            $action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
            
            #--------------------------------
            # keep Previous URL If not Logut from each module
            #-------------------------------

            if( ($action != 'logout' || !empty($action)))
            {
                Yii::app()->user->returnUrl = Yii::app()->request->url;
            } 
           
            parent::init();
            //$this->initAjaxCsrfToken();
            if(!isset(Yii::app()->session['login']['user_id']))
            {       
                if(Yii::app()->controller->id != 'register'){
                        $this->redirect(array('/login'));
                    }
                 $this->redirect(array('/register'));    
            }
	}
        
 
        // this function will work to post csrf token.
        protected function initAjaxCsrfToken()
        {

            Yii::app()->clientScript->registerScript('AjaxCsrfToken', ' $.ajaxSetup({
                             data: {"' . Yii::app()->request->csrfTokenName . '": "' . Yii::app()->request->csrfToken . '"},
                             cache:false
                        });', CClientScript::POS_HEAD);
        }
}