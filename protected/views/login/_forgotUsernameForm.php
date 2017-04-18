<?php 
/*	Display the success message for the Forgot password functionality
************************************************************************
*/
if(Yii::app()->session['suc']==1) {
Yii::app()->clientScript->registerScript('user-grid', "$(window).load(function () {
$('#forgotpassword').modal('show');
});");
}
/*
*************************************************************************	
*/
?>
<div class="modal-body clearfix">
	<?php 
	
	$form		=		$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
									'id'						=>		'forgot-username-form',
									'action'					=>		Yii::app()->createUrl('//admin/login/forgotusername'),  //<- your form action here
									'enableAjaxValidation' 		=>		true,
									'enableClientValidation'	=>		true,
									'clientOptions'				=>		array(
														'validateOnSubmit'=>true,
														),
									'htmlOptions'				=>		array(
									'class'						=>		'form-horizontal',
									),
												)); 
	?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'Email address'); ?>
		<?php echo $form->textField($model,'email',array('class'=>'form-control')); ?> 
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div class="form-group">
		<?php if(CCaptcha::checkRequirements()): ?>
		<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode',array('class'=>'form-control')); ?>
		</div>
		<?php echo $form->error($model,'verifyCode'); ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="form-group pull-right">
		<?php if(Yii::app()->session['suc']==1) {?>
		<div id="target-04" class="alert alert-success alert-dismissable">
		<strong>Success!</strong> We have sent a new auto-generated password to the registered email address</div>
		<?php unset(Yii::app()->session['suc']); }?>
		<!--<button type="button" class="btn btn-primary" id="forgot_password">Submit</button>-->
		<?php
		
		 $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'		=>		'submit',
		'type'				=>		'primary',
		'label'				=>		'Save',
		)); ?>
		<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
	</div>
	<?php $this->endWidget(); ?>
</div>