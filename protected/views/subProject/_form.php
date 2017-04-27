<?php
/* @var $this SubProjectController */
/* @var $model SubProject */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sub-project-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
	<?php echo CHTML::label('Task ID',''); ?>
	<?php 
   $TaskId = Yii::app()->db->createCommand('Select max(spid) as TaskId from tbl_sub_project ')->queryRow(); 
	echo CHtml::textField("insert_id",$TaskId['TaskId']+1,array('readonly'=>true)); ?>
</div>
	<div class="row">
		<?php echo CHTML::label('Project Name',''); ?>
		<?php echo $form->dropDownList($model, 'pid', CHtml::listData(ProjectManagement::model()->findAll(array('order'=>'project_name','condition'=>'is_deleted=0')), 'pid', 'project_name'),array('prompt'=>'Please select Project'));
                ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sub_project_name'); ?>
		<?php echo $form->textField($model,'sub_project_name',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'sub_project_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sub_project_description'); ?>
		<?php // echo $form->textField($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->textArea($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'sub_project_description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'requester'); ?>
		<?php echo $form->textField($model,'requester',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'requester'); ?>
	</div>
<!--<div class="row">
        <?php //echo $form->labelEx($model, 'estimated_start_date'); ?>
        <?php
        //echo $form->textField($model,'estimated_end_date');
        //echo $form->textField($model, 'estimated_start_date', array('id' => 'start_date', 'class' => 'datepicker', 'placeholder' => 'Select Date', 'style' => 'width:200px;'));
        ?>
        <?php //echo $form->error($model, 'estimated_start_date'); ?>
    </div>
	<div class="row">
		<?php //echo $form->labelEx($model,'estimated_end_date'); ?>
		<?php //echo  $form->textField($model,'estimated_end_date',array('id' => 'end_date', 'class'=>'datepicker', 'placeholder' => 'Select Date', 'style'=>'width:200px;')); ?>
		<?php //echo $form->error($model,'estimated_end_date'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'total_hr_estimation_hour'); ?>
		<?php //echo $form->textField($model,'total_hr_estimation_hour',array('size'=>10,'maxlength'=>10)); ?>
		<?php //echo $form->error($model,'total_hr_estimation_hour'); ?>
	</div>-->
	

    <div class="row">
        <?php echo $form->labelEx($model, 'status'); ?>
<?php
//echo $form->textField($model,'status',array('size'=>25,'maxlength'=>25)); 
echo $form->dropDownList($model, 'status', array('Completed' => 'Completed', 'Newly Created' => 'Newly Created', 'Partially Completed' => 'Partially Completed'), array('prompt' => '(Select Status)', 'class' => 'sts'));
?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <div class="row">
<?php echo $form->labelEx($model, 'Priority'); ?>
<?php
//echo $form->textField($model,'type');
echo $form->dropDownList($model, 'Priority', array('1' => 'High', '2' => 'Medium', '3' => 'Low'), array('prompt' => '(Select Priority)', 'class' => 'prio'));
?>
    <?php echo $form->error($model, 'Priority'); ?>
    </div>

	<div class="row">
		<?php // echo $form->labelEx($model,'created_by'); ?>
		<?php // echo $form->textField($model,'created_by'); ?>
		<?php // echo $form->error($model,'created_by'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'created_date'); ?>
		<?php // echo $form->textField($model,'created_date'); ?>
		<?php // echo $form->error($model,'created_date'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'updated_by'); ?>
		<?php // echo $form->textField($model,'updated_by'); ?>
		<?php // echo $form->error($model,'updated_by'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'updated_date'); ?>
		<?php // echo $form->textField($model,'updated_date'); ?>
		<?php // echo $form->error($model,'updated_date'); ?>
	</div>

	<div class="row">
		<?php // echo $form->labelEx($model,'is_deleted'); ?>
		<?php // echo $form->textField($model,'is_deleted'); ?>
		<?php // echo $form->error($model,'is_deleted'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
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
<?php
Yii::app()->clientScript->registerScript('filters', "
  
    $('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
  
    
", CClientScript::POS_READY);
?>