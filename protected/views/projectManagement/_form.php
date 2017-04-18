<?php
/* @var $this ProjectManagementController */
/* @var $model ProjectManagement */
/* @var $form CActiveForm */
?>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'project-management-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php //echo $form->errorSummary($model);  ?>

    <!-- <div class="row">
// <?php// echo $form->labelEx($model, 'project_id'); ?>
// <?php
// $list = CHtml::listData(Project::model()->findAll(array('order' => 'project_name')), 'id', 'project_name');
        // echo $form->dropDownList($model, 'project_id', $list, array('prompt' =>'Please Select'));
// ?>
    <?php// echo $form->error($model, 'project_id'); ?>
    // </div>
-->    
    <div class="row">
        <?php echo $form->labelEx($model, 'project_name'); ?>
<?php echo $form->textField($model, 'project_name', array('size' => 60, 'maxlength' => 250)); ?>
<?php echo $form->error($model, 'project_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'project_description'); ?>
<?php echo $form->textArea($model, 'project_description', array('size' => 60, 'maxlength' => 250)); ?>
<?php echo $form->error($model, 'project_description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'requester'); ?>
<?php echo $form->textField($model, 'requester', array('size' => 60, 'maxlength' => 250)); ?>
<?php echo $form->error($model, 'requester'); ?>
    </div>

     
    <!--
            <div class="row">
    <?php echo $form->labelEx($model, 'hr_clocked'); ?>
    <?php echo $form->textField($model, 'hr_clocked', array('size' => 10, 'maxlength' => 10, 'style' => 'width:50px;')); ?>
    <?php echo $form->error($model, 'hr_clocked'); ?>
            </div>
     */
            <div class="row">
    <?php echo $form->labelEx($model, 'created_by'); ?>
    <?php echo $form->textField($model, 'created_by'); ?>
    <?php echo $form->error($model, 'created_by'); ?>
            </div>
    
            <div class="row">
    <?php echo $form->labelEx($model, 'created_date'); ?>
    <?php echo $form->textField($model, 'created_date'); ?>
    <?php echo $form->error($model, 'created_date'); ?>
            </div>
    
            <div class="row">
    <?php echo $form->labelEx($model, 'updated_by'); ?>
    <?php echo $form->textField($model, 'updated_by'); ?>
    <?php echo $form->error($model, 'updated_by'); ?>
            </div>
    
            <div class="row">
    <?php echo $form->labelEx($model, 'updated_date'); ?>
    <?php echo $form->textField($model, 'updated_date'); ?>
    <?php echo $form->error($model, 'updated_date'); ?>
            </div>
    
            <div class="row">
        <?php echo $form->labelEx($model, 'is_deleted'); ?>
<?php echo $form->textField($model, 'is_deleted'); ?>
    <?php echo $form->error($model, 'is_deleted'); ?>
            </div>-->

    <div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('filters', "
  
    $('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
  
       $('.datepicker2').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
", CClientScript::POS_READY);
?>