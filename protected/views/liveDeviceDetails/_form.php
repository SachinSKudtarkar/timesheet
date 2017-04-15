<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'live-device-details-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'circle'); ?>
		<?php echo $form->textField($model,'circle',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'circle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lsmr_name'); ?>
		<?php echo $form->textField($model,'lsmr_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'lsmr_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_name'); ?>
		<?php echo $form->textField($model,'group_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'group_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enb'); ?>
		<?php echo $form->textField($model,'enb',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'enb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enb_ip'); ?>
		<?php echo $form->textField($model,'enb_ip',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'enb_ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'neid'); ?>
		<?php echo $form->textField($model,'neid',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'neid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'b11_status'); ?>
		<?php echo $form->textField($model,'b11_status',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'b11_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'show_run_enb_ip'); ?>
		<?php echo $form->textField($model,'show_run_enb_ip',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'show_run_enb_ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loopback0_1'); ?>
		<?php echo $form->textField($model,'loopback0_1',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'loopback0_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loopback0_2'); ?>
		<?php echo $form->textField($model,'loopback0_2',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'loopback0_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'host_name'); ?>
		<?php echo $form->textField($model,'host_name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'host_name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->