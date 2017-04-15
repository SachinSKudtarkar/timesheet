<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $model NddLinkEnggCatEmployeeMapping */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ndd-link-engg-cat-employee-mapping-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'emp_id'); ?>
		<?php echo $form->textField($model,'emp_id'); ?>
		<?php echo $form->error($model,'emp_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cat_key_id'); ?>
		<?php echo $form->textField($model,'cat_key_id'); ?>
		<?php echo $form->error($model,'cat_key_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php echo $form->textField($model,'is_active'); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mapping_date_time'); ?>
		<?php echo $form->textField($model,'mapping_date_time'); ?>
		<?php echo $form->error($model,'mapping_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mapped_by'); ?>
		<?php echo $form->textField($model,'mapped_by'); ?>
		<?php echo $form->error($model,'mapped_by'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->