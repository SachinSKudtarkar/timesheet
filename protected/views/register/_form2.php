<?php
/* @var $this EmployeeController */
/* @var $model Employee */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'registration-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'afterValidateAttribute' => new CJavaScriptExpression('function(form, attribute, data, hasError) { console.log( attribute ); if( attribute.name == "Employee[verifyCode]" && hasError ){ $("#newCaptcha").click(); }  return true; }'),
        'afterValidate' => new CJavaScriptExpression('function(form, data, hasError) { if( hasError ){ $("#newCaptcha").click(); }  return true; }'),
    ),
        //'focus'=>array($model,'first_name'),
        ));
?>
<div class="form">
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">

                <?php /* $form=$this->beginWidget('CActiveForm', array(
                  'id'=>'employee-form',
                  // Please note: When you enable ajax validation, make sure the corresponding
                  // controller action is handling ajax validation correctly.
                  // There is a call to performAjaxValidation() commented in generated controller code.
                  // See class documentation of CActiveForm for details on this.
                  'enableAjaxValidation'=>false,
                  )); */ ?>

                <div class="row">
                    <?php echo $form->labelEx($model, 'first_name'); ?>
                    <?php echo $form->textField($model, 'first_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'first_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'middle_name'); ?>
                    <?php echo $form->textField($model, 'middle_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'middle_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'last_name'); ?>
                    <?php echo $form->textField($model, 'last_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'last_name'); ?>
                </div>    

                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 150)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>

                <div class="row ermessageemail" style="color:#9f2b1e; margin-left:140px;margin-top:-25px;display: none;">Please register under @cisco.com, @ril.com OR @rjil.com domain.</div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'phone', array('class' => '')); ?>

                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'phone',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 20),
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'phone'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'mobile', array('class' => '')); ?>
                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'mobile',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 12)
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'mobile'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'company_name'); ?>
                    <?php echo $form->textField($model_employee_details, 'company_name'); ?>
                    <?php echo $form->error($model_employee_details, 'company_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'rjil_ext'); ?>
                    <?php echo $form->textField($model_employee_details, 'rjil_ext'); ?>
                    <?php echo $form->error($model_employee_details, 'rjil_ext'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'building_name'); ?>
                    <?php echo $form->textField($model_employee_details, 'building_name'); ?>
                    <?php echo $form->error($model_employee_details, 'building_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'floor'); ?>
                    <?php echo $form->textField($model_employee_details, 'floor'); ?>
                    <?php echo $form->error($model_employee_details, 'floor'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'work_location'); ?>
                    <?php echo $form->textField($model_employee_details, 'work_location'); ?>
                    <?php echo $form->error($model_employee_details, 'work_location'); ?>
                </div>

            </div>
            <div class="span6">
                <div class="row">
                    <?php echo $form->labelEx($model, 'password', array('class' => '')); ?>
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->passwordField($model, 'password');
                    } else {
                        echo $form->passwordField($model, 'password', array('value' => ''));
                    }
                    ?> 
                    <?php echo $form->error($model, 'password'); ?>
                </div>
                <div class="row">
                    <label><b>Note:</b></label> 	
                    <span style="color: green" >Password must be at least eight characters long including one uppercase letter, one lowercase letter, one special character & one number with no spaces.</span>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'repeat_password', array('class' => '')); ?>
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->passwordField($model, 'repeat_password');
                    } else {
                        echo $form->passwordField($model, 'repeat_password', array('value' => ''));
                    }
                    ?> 
                    <?php echo $form->error($model, 'repeat_password'); ?>
                </div>   
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'address_1', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'address_1', array('maxlength' => 100, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'address_1'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'address_2', array('class' => ' ')); ?>
                    <?php echo $form->textField($model_employee_details, 'address_2', array('class' => '', 'maxlength' => 150, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'address_2'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'city', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'city', array('class' => '', 'maxlength' => 50, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'city'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'state_id', array('class' => '')); ?>
                    <?php
                    echo $form->dropDownList(
                            $model_employee_details, 'state_id', CHtml::listData(StateMaster::model()->findAll(
                                            array('order' => 'state_name_short ASC', 'condition' => 'is_disabled=0')), 'state_id', 'state_name'
                            ), array('empty' => 'Select State', 'class' => '')
                    );
                    ?>

                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'facebook_link'); ?>
                    <?php echo $form->textField($model_employee_details, 'facebook_link'); ?><span>Ex: https://www.facebook.com/yourname</span>
                    <?php echo $form->error($model_employee_details, 'facebook_link'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'linkedin_link'); ?>
                    <?php echo $form->textField($model_employee_details, 'linkedin_link'); ?><span>Ex: https://www.linkedin.com/in/yourname</span>
                    <?php echo $form->error($model_employee_details, 'linkedin_link'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'twitter_link'); ?>
                    <?php echo $form->textField($model_employee_details, 'twitter_link'); ?><span>Ex: https:// www.twitter.com/yourname</span>
                    <?php echo $form->error($model_employee_details, 'twitter_link'); ?>
                </div>

                <?php if (CCaptcha::checkRequirements()): ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'verifyCode'); ?>
                        <?php echo $form->textField($model, 'verifyCode'); ?>
                        <?php
                        $form->widget('CCaptcha', array(
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
            
            <div class="span3 offset5">
                <div class="form-group">

                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'label' => 'Submit',
                        'htmlOptions' => array(
                            'class' => 'btn-primary sbtn',
                        ),
                    ));
                    ?>             
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => array('/login/index'),
                        'label' => 'Cancel',
                        'htmlOptions' => array(
                            'class' => 'btn-primary',
                        ),
                    ));
                    ?>
                </div>
            </div>

        </div>
    </div>
    <?php
// check created by && Super admin can only access this portion
    /* if( CHelper::isAccess('SA', 'admin.SubAdmin.AccessRights') )
      { */
    ?>
    <?php $this->endWidget(); ?>

</div>
<?php
CHelper::registerScript('email restrictions', "
/* restring user to  etner the email in abc@cisco.com/abc@rjil.com/abc@ril.com format */
 $('.sbtn').live('click',function(){
                                            var emailval = $('#Employee_email').val();
                                           
                                            if(emailval != '')
                                            {  
                                                var splt = emailval.split('@');
                                                var str_email = splt[1].toLowerCase();
                                                
                                                if(str_email == 'cisco.com' || str_email == 'rjil.com' || str_email == 'ril.com')
                                                {
                                                    return true;
                                                }
                                                else
                                                {                                                    
                                                    $('.ermessageemail').show();
                                                    return false;
                                                }
                                            }
                                    });
                                    
"
        , CClientScript::POS_READY);
?>