<?php
/* @var $this SyslogserveripschangeController */
/* @var $model Syslogserveripschange */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'syslogserveripschange-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_address'); ?>
		<?php echo $form->textField($model,'ip_address',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ip_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hostname'); ?>
		<?php echo $form->textField($model,'hostname',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'hostname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'site_sap_name'); ?>
		<?php echo $form->textField($model,'site_sap_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'site_sap_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sys_cha_ip'); ?>
		<?php echo $form->textField($model,'sys_cha_ip',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'sys_cha_ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_found'); ?>
		<?php echo $form->textField($model,'ip_found'); ?>
		<?php echo $form->error($model,'ip_found'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sys_log_path'); ?>
		<?php echo $form->textField($model,'sys_log_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sys_log_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Status'); ?>
		<?php echo $form->textField($model,'Status',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'Status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_at'); ?>
		<?php echo $form->textField($model,'status_at'); ?>
		<?php echo $form->error($model,'status_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->