<?php
/**
 * BaseController is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */ 
class BaseLoginController extends Controller
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
        
	public function init()
	{
            
            parent::init();
            #---------------------------------
            # Redirct on dashboard if Logged in
            #---------------------------------
           if(isset(Yii::app()->session['login']['user_id']))
           {        
                 if(!empty(Yii::app()->user->returnUrl))
                    $this->redirect(Yii::app()->user->returnUrl);
                 else
                    $this->redirect(array('//dashboard'));
           }
              
                // Redirect to Super admin dashbord if session is already set
                
	}
}