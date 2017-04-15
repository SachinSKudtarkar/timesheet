<?php
/* @var $this TemplateMasterController */
/* @var $model TemplateMaster */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'template-master-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row cls_copy_from">
        <?php echo $form->labelEx($model, 'copy_from'); ?>
        <?php echo $form->dropDownList($model, 'copy_from', CHtml::listData($model->findAll(array('order' => 'name')), 'id', 'name'), array('empty' => 'Select')); ?>
        <?php echo $form->error($model, 'copy_from') ?>
    </div>
    <div class="row cls_upload_file">
        <?php echo $form->labelEx($model, 'upload_file'); ?>
        <?php echo $form->fileField($model, 'upload_file') ?>
        <?php //echo $form->error($model, 'upload_file') ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('create', "
    
    
    hideShowFileInput = function(){
        if($('#TemplateMaster_copy_from').val() == '' ){
            $('.cls_upload_file').show();
        }else{
            $('.cls_upload_file').hide();
        }
    };
    
    hideShowFileInput();
    $('#TemplateMaster_copy_from').live('change',hideShowFileInput);

  ", CClientScript::POS_END);
?>
