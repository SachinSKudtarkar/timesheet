<?php
/* @var $this AccessRightMasterController */
/* @var $model AccessRightMaster */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'access-right-master-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php //echo $form->errorSummary($model); ?>
        <?php 
                $checked = 0;
                if (!empty($model->is_disabled)) {
                    $checked = 1;
                }
                
                $disabled = FALSE;
                if(!empty($model->id)){
                    $disabled = TRUE;
                }
        ?>
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>20,'maxlength'=>20,'disabled' => $disabled)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'heading'); ?>
		<?php echo $form->textField($model,'heading',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'heading'); ?>
	</div>

<!--	<div class="row">
		<?php //echo $form->labelEx($model,'page_url'); ?>
		<?php //echo $form->textField($model,'page_url',array('size'=>60,'maxlength'=>200)); ?>
		<?php //echo $form->error($model,'page_url'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->labelEx($model,'heading_order'); ?>
		<?php echo $form->textField($model,'heading_order'); ?>
		<?php echo $form->error($model,'heading_order'); ?>
	</div>

<!--	<div class="row">
		<?php //echo $form->labelEx($model,'menu_icon'); ?>
		<?php //echo $form->textField($model,'menu_icon',array('size'=>60,'maxlength'=>100)); ?>
		<?php //echo $form->error($model,'menu_icon'); ?>
	</div>-->

<!--	<div class="row">
		<?php //echo $form->labelEx($model,'is_disabled'); ?>
		<?php //echo $form->textField($model,'is_disabled'); ?>
		<?php //echo $form->error($model,'is_disabled'); ?>
	</div>-->
        <div class="row">
                <?php echo $form->labelEx($model, 'is_disabled'); ?>
                <?php echo $form->checkBox($model,'is_disabled',  array('checked'=>$checked)); ?>
                <?php echo $form->error($model, 'is_disabled'); ?>
        </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->