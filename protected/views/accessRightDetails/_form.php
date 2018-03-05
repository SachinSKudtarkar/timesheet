<?php
/* @var $this AccessRightDetailsController */
/* @var $model AccessRightDetails */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'access-right-details-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php //echo $form->errorSummary($model); ?>
    <?php
    $criteria = new CDbCriteria;
    $criteria->select = array('id', 'type', 'heading');
    $criteria->addCondition('t.is_disabled = 0');
   // $criteria->addCondition('accessRightDetails.is_disabled = 0');
    // Super admin access
    // $criteria->addCondition('t.type in("A","E")');
   // $criteria->with = array('accessRightDetails');
    $criteria->order = 'heading_order';

    // Retrive all criteria
    $access_rights = AccessRightMaster::model()->findAll($criteria);
    $access_rights_list = CHtml::listData($access_rights, 'id', 'type');
    $parent_selected = '';
    if (!empty($model->parent_id)) {
        $parent_selected = $model->parent_id;
    }
    $checked = 0;
    if(!empty($model->is_disabled)){
        $checked = 1;
    }
    $disabled = FALSE;
    if(!empty($model->id)){
        $disabled = TRUE;
    }
    ?>

    <!--	<div class="row">
    <?php //echo $form->labelEx($model,'parent_id');  ?>
    <?php //echo $form->textField($model,'parent_id');  ?>
    <?php //echo $form->error($model,'parent_id');  ?>
            </div>-->

    <div class="row">
        <?php echo $form->labelEx($model, 'parent_id'); ?>
        <?php echo CHtml::dropdownlist("AccessRightDetails[parent_id]", $parent_selected, CHtml::listData($access_rights, 'id', 'type'), array('empty' => 'Select a Access Right')); ?>
        <?php echo $form->error($model, 'parent_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'value'); ?>
        <?php echo $form->textField($model, 'value', array('size' => 60, 'maxlength' => 150)); ?>
        <?php echo $form->error($model, 'value'); ?>
    </div>

<!--    <div class="row">
        <?php //echo $form->labelEx($model, 'page_url'); ?>
        <?php //echo $form->textField($model, 'page_url', array('size' => 60, 'maxlength' => 200)); ?>
        <?php //echo $form->error($model, 'page_url'); ?>
    </div>-->

    <div class="row">
        <?php echo $form->labelEx($model, 'menu_order'); ?>
        <?php echo $form->textField($model, 'menu_order'); ?>
        <?php echo $form->error($model, 'menu_order'); ?>
    </div>

<!--    <div class="row">
        <?php //echo $form->labelEx($model, 'menu_icon'); ?>
        <?php //echo $form->textField($model, 'menu_icon', array('size' => 60, 'maxlength' => 200)); ?>
        <?php //echo $form->error($model, 'menu_icon'); ?>
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