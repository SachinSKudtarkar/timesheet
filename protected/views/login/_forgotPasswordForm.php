<?php 
$module_name = Yii::app()->controller->id;
?>
<div class="modal-body clearfix">
    <?php 
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'forgot-password-form',
    'action' => Yii::app()->createUrl('//'.$module_name.'/forgotPassword'),  //<- your form action here
    'enableAjaxValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'afterValidateAttribute' => new CJavaScriptExpression('function(form, attribute, data, hasError) { console.log( attribute ); if( attribute.name == "LoginForm[verifyCode]" && hasError ){ $("#newCaptcha").click(); }  return true; }'),                                
        'afterValidate' => new CJavaScriptExpression('function(form, data, hasError) { if( hasError ){ $("#newCaptcha").click(); }  return true; }'),                                
    ),			
    'htmlOptions'=>array(
    'class'=>'form-horizontal no-mr',
    ),
    ));
    ?>
    <div class="control-group no-margin">
        <?php echo $form->labelEx($model,'Email address',array('class'=>'control-label')); ?>
        <?php echo $form->textField($model,'email',array('class'=>'form-control mr-lf-5 span12 email','maxlength'=>150)); ?> 
        <?php echo $form->error($model,'email'); ?>
    </div>
    <?php /* ?>
    <div class="control-group no-margin">
        <?php if(CCaptcha::checkRequirements()): ?>
        <div>
            <?php echo $form->labelEx($model,'Please Enter Text',array('class'=>'control-label')); ?>
            <?php //$this->widget('CCaptcha'); ?>
            <?php $this->widget('CCaptcha', array(
            // Standard with button / link: set the "button" id to 'newCaptcha'
                'buttonOptions' => array(
                'id' => 'newCaptcha',
                ),
            )); ?> 
            <div class="control-group no-margin">
                <?php echo $form->textField($model,'verifyCode',array('class'=>'form-control mr-lf-5  span12')); ?>
                        <?php echo $form->error($model,'verifyCode'); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
      <?php */ ?>
     
    <div class="control-group mr-tp-20 pull-right">
        <!--<button type="button" class="btn btn-primary" id="forgot_password">Submit</button>-->
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Submit',
        )); ?>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
    </div>
    <?php $this->endWidget();?>
</div>