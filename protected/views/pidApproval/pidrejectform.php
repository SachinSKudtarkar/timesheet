<?php 
//CHelper::debug($model);
$module_name = Yii::app()->controller->id;
?>
<div class="modal-body clearfix">
    <?php 
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'forgot-password-form',
    'action' => Yii::app()->createUrl('//'.$module_name.'/approvalstatus'),  //<- your form action here
//    'enableAjaxValidation' => true,
//    'clientOptions'=>array(
//        'validateOnSubmit'=>true,
//        'afterValidateAttribute' => new CJavaScriptExpression('function(form, attribute, data, hasError) { console.log( attribute ); if( attribute.name == "LoginForm[verifyCode]" && hasError ){ $("#newCaptcha").click(); }  return true; }'),                                
//        'afterValidate' => new CJavaScriptExpression('function(form, data, hasError) { if( hasError ){ $("#newCaptcha").click(); }  return true; }'),                                
//    ),			
    'htmlOptions'=>array(
    'class'=>'form-horizontal no-mr',
       
    ),
    ));
    ?>
<!--    <div class="control-group no-margin">
        <?php echo $form->labelEx($model,'Comments',array('class'=>'control-label')); ?>
        <?php echo $form->textArea($model,'comments',array('class'=>'form-control mr-lf-5 span12 ','maxlength'=>150)); ?> 
        <?php echo $form->error($model,'comments'); ?>
    </div>
    <div class="control-group no-margin">
        <?php echo $form->labelEx($model,'pid_id',array('class'=>'control-label')); ?>
        <?php echo $form->hiddenField($model,'pid_id',array('class'=>'form-control mr-lf-5 span12 ','maxlength'=>150)); ?> 
        <?php echo $form->error($model,'pid_id'); ?>
    </div>-->
<textarea name='comments' type='text' value=''></textarea>
<input type='hidden' name='approval_id' id='sendreject' />
     
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