<?php
/* @var $this NddCoreWanController */
/* @var $model NddCoreWan */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lic-install-tool-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>
       
         <div class="row">
            <?php echo $form->labelEx($model, 'Upload Excel File'); ?>
            <?php echo $form->fileField($model, 'file_name'); ?>
            <?php echo $form->error($model, 'file_name'); ?>
        </div>	
    
        <div class="row">
            <?php echo $form->labelEx($model, 'Lic Zip File Upload'); ?>
            <?php echo $form->fileField($model, 'zip_file'); ?>
            <?php echo $form->error($model, 'zip_file'); ?>
        </div>	
	
<div class="row buttons">
    
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Upload' : 'Save', array('onClick' => '$(\'div.custom-loader\').show();')); ?>
                
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->