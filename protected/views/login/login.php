<?php
/* * ************************************************************
 *  File Name : login.php
 *  File Description: Display Login Form.
 *  Author: Benchmark, 
 *  Created Date: 17	/2/2014
 *  Created By: Yogesh Jadhav
 * ************************************************************* */

/* @var $this AdminController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

//$this->theme = "login";
?>

<script language="javascript1.1">
    if (navigator.userAgent.indexOf("MSIE 10") > -1) {
        document.body.classList.add("ie10");
    }
    function popUpClosed() {
        window.location.reload();
    }</script>
<style>.ie10 input[type="checkbox"] {margin-top: 0;}

</style>
<!--main-login-page-->


<header id="cisco_new_login">
    <div id="cisco_new_login_head">
<!--        <div class="new_login_logo"><a href="#"><img src="<?php echo Yii::app()->baseUrl ?>/themes/cisco/img/cis_logo.jpg"/></a></div>-->
        <nav id="login_top_right">
            <ul>
                <li><a href="#" class="no-pipe" onclick="">Home</a></li>
                <!-- <li class="no-pipe"><?php //echo CHtml::link('Register', array('//register')); ?> -->
            </ul>
        </nav>

    </div>

</header>
<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();

/* Encrypt Password using js
   $cs->registerScriptFile($baseUrl . '/js/aes.js');
   $cs->registerScriptFile($baseUrl . '/js/pbk.js');
  
 */

?>
<div class="row-fluid" style="min-height:555px;">
    <div class="login-box clearfix" style="min-height:555px;">

        <div class="span12">
<!--            <div class="span6 offset3 mr-tp-20 text-center"><img src="<?php echo Yii::app()->baseUrl ?>/themes/cisco/img/logo.png"/></div>-->
            <div class="span5 offset3 mr-tp-20">
                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => false,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'afterValidateAttribute' => new CJavaScriptExpression('function(form, attribute, data, hasError) { console.log( attribute ); if( attribute.name == "LoginForm[verifyCode]" && hasError ){ $("#newCaptcha").click(); }  return true; }'),
                        'afterValidate' => new CJavaScriptExpression('function(form, data, hasError) { if( hasError ){ $("#newCaptcha").click(); }  return true; }'),
                    ),
                    'htmlOptions' => array(
                        'style' => '  border: 1px solid #1b427a;    border-radius: 11px;    margin: 20px;    padding: 30px 0 10px;',
                        'class' => 'form-horizontal',
 //                       'onsubmit'=>'js:return raw();'
                    ),
                ));
                ?>
                <div class="control-group">
                    <?php echo $form->labelEx($model, 'Email Address', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><span class="icon-user"></span></span><?php echo $form->textField($model, 'email', array('class' => 'span8')); ?>
                        </div>
                        <?php echo $form->error($model, 'email', array('class' => 'error')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo $form->labelEx($model, 'Password', array('class' => 'control-label')); ?>
                    <div class="controls"> 
                        <div class="input-prepend">
                            <span class="add-on"><span class="icon-lock"></span></span><?php echo $form->passwordField($model, 'password', array('class' => 'span8', 'autocomplete'=>"off")); ?>
                        </div>
                        <?php echo $form->error($model, 'password', array('class' => 'error')); ?>
                    </div>

                </div>
                <div>
                    <?php echo CHtml::hiddenField('benchraw[raw1]', 'value', array('id' => 'benchraw1')); ?>
                </div>
                <div>
                    <?php echo CHtml::hiddenField('benchraw[raw2]', 'value', array('id' => 'benchraw2')); ?>
                </div>
                <div>
                    <?php echo CHtml::hiddenField('benchraw[raw3]', 'value', array('id' => 'benchraw3')); ?>
                </div>

                <div class="control-group">
                    <?php if (CCaptcha::checkRequirements() && $model->scenario == 'login_with_captcha'): ?>
                        <?php echo $form->labelEx($model, 'Please Enter Text', array('class' => 'control-label')); ?>
                        <div class="controls">

                            <?php echo $form->textField($model, 'verifyCode', array('class' => 'form-control mr-lf-5  span3')); ?>

                            <?php
                            $this->widget('CCaptcha', array(
                                // Standard with button / link: set the "button" id to 'newCaptcha'
                                'buttonOptions' => array(
                                    'id' => 'newCaptcha',
                                ),
                            ));
                            ?> 
                            <?php echo $form->error($model, 'verifyCode'); ?>
                        </div>

                    <?php endif; ?>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox text-left chk-box"><?php echo $form->checkBox($model, 'rememberMe'); ?> Remember me</label>
                        <?php echo CHtml::htmlButton('Sign in', array('class' => 'btn mr-lf-5', 'type' => 'submit')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <!-- <a  href="#forgotpassword1" role="button" data-toggle="modal">Forgot password?</a> -->
                    </div>
                    <div class="controls">
                        <?php //echo CHtml::link('Register Now', array('//register')); ?>   
                    </div>

                </div>
                <?php $this->endWidget(); ?>
                <!--Display popup code start for forgot password -->
                <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'forgotpassword')); ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="popUpClosed();">&times;</button>
                            <h5 class="modal-title bold-text" id="myModalLabel">Forgot Password</h5>
                        </div>
                        <?php $this->renderPartial('//login/_forgotPasswordForm', array('model' => $model)); ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <!--Display popup code end for forgot password -->
                <!--Display popup code start for forgot user name -->
                <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'forgotusername')); ?>
                <div class="modal-dialog"> 
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close icon-remove" data-dismiss="modal" aria-hidden="true"> </button>
                            <h5 class="modal-title bold-text" id="myModalLabel">Forgot Username</h5>
                        </div>
                        <?php $this->renderPartial('//login/_forgotUsernameForm', array('model' => $model)); ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <!--Display popup code end for forgot user name  -->
                <?php if (CHelper::user()->isGuest): ?>

                    <?php if (CHelper::hasFlash('success') && CHelper::getFlash('success') != ''): ?>				
                        <div class="alert alert-success text-center">
                            <?php
                            echo CHelper::getFlash('success');
                            CHelper::setFlash('success', '');
                            ?>
                        </div>
                    <?php endif ?>
                    <?php if (CHelper::hasFlash('error') && CHelper::getFlash('error') != ''): ?>
                        <div class="alert alert-error text-center">
                            <?php
                            echo CHelper::getFlash('error');
                            CHelper::setFlash('error', '');
                            ?>
                        </div>
                    <?php endif ?>
                    <?php if (CHelper::hasFlash('notice') && CHelper::getFlash('notice') != ''): ?>
                        <div class="alert alert-notice text-center">
                            <?php
                            echo CHelper::getFlash('notice');
                            CHelper::setFlash('notice', '');
                            ?>
                        </div>
                    <?php endif ?>
                <?php endif ?>
                <?php
                Yii::app()->clientScript->registerScript(
                        'myHideEffect', '$(".alert-success,.alert-notice,.alert-error").animate({opacity: 1.0}, 5000).fadeOut("slow");', CClientScript::POS_READY
                );
                ?>
            </div>

        </div>
    </div>   
</div>   

<script>
    
</script>
