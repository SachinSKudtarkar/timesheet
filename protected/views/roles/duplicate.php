<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'employee-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
    'method' => 'post',
    'action' => Yii::app()->createUrl('roles/duplicateSave'),
        ));
?>
<div class="form">
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->hiddenField($model, 'id'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Create Duplicate'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>