<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'link-engg-cr-master-feedback-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'htmlOptions'=>array('onSubmit'=>'js:if( $("#approval_status").val() == "rejected" ){ if( $(".feedback").val() == "" || $(".feedback").val() == null ){ $(".feed_error").html("Feedback is mandatory!"); $(".feed_error").addClass("custombackred"); return false; } }')
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'Enter your feedback/comments:'); ?>
        <?php echo $form->textArea($model, 'feedback', array('rows' => 6, 'style' => 'width:500px;','class'=>'feedback')); ?>
        <?php echo $form->error($model, 'feedback',array('class'=>'error_feed')); ?>
        <?php echo $form->hiddenField($model, 'id'); ?>
        <?php echo CHtml::hiddenField('approval_status', $approval_status); ?>
    </div>	
    <div class="feed_error " style="color: red;padding-left: 11% !important;">
        
    </div>
    <div class="row buttons" style="padding-top: 10px;">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->