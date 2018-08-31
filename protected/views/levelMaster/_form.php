<?php
/* @var $this SubProjectController */
/* @var $model SubProject */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
        $form=$this->beginWidget('CActiveForm', array(
            'id'=>'level_master',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
        ));
    ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php //echo $form->errorSummary($model); ?>

	<div class="row">
            <?php echo $form->labelEx($model,'level_name'); ?>
            <?php echo $form->textField($model,'level_name',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->error($model,'level_name'); ?>
	</div>
	<div class="row">
            <?php echo $form->labelEx($model,'budget_per_hour'); ?>
            <?php // echo $form->textField($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->textField($model,'budget_per_hour',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->error($model,'budget_per_hour'); ?>
	</div>

        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        </div>
    <?php $this->endWidget(); ?>
</div>
<!-- form -->
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