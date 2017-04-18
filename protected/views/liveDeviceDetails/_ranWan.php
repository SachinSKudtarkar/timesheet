<?php
/* @var $this NddRanWanController */
/* @var $model NddRanWan */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'ndd-ran-wan-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        //'action' => Yii::app()->createUrl('//nddRanLb/CleanupUpdate'),
        'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
    ));
    ?>
<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'from_modified_sapid'); ?>
        <?php echo $form->textField($model, 'from_modified_sapid'); ?>
        <?php echo $form->error($model, 'from_modified_sapid'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'to_modified_sapid'); ?>
        <?php echo $form->textField($model, 'to_modified_sapid'); ?>
        <?php echo $form->error($model, 'to_modified_sapid'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'from_host_name'); ?>
        <?php echo $form->textField($model, 'from_host_name'); ?>
        <?php echo $form->error($model, 'from_host_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'to_host_name'); ?>
        <?php echo $form->textField($model, 'to_host_name'); ?>
        <?php echo $form->error($model, 'to_host_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'from_addr'); ?>
        <?php echo $form->textField($model, 'from_addr', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'from_addr'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'to_addr'); ?>
        <?php echo $form->textField($model, 'to_addr', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'to_addr'); ?>
    </div>
    <?php echo $form->hiddenField($model, 'ran_wan_id'); ?>
    <input type="hidden" name=<?php echo Yii::app()->request->csrfTokenName; ?> value=<?php echo Yii::app()->request->csrfToken; ?>>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Update', array('class' => 'updateBtn')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php
Yii::app()->clientScript->registerScript('search', "
    $('.updateBtn').click(function(){
        $.ajax({
            url: '" . CHelper::createUrl("nddRanWan/CleanupUpdate") . "',
            type: 'POST',
            data: $('form#ndd-ran-wan-form').serialize(),
            success: function(data) { 
                if(data == 'OK'){
                    $('#cleanup_modal').modal('hide');
                    $('#searchBtn').trigger('click');
                    alert('Record updated successfully.');
                }
            },
        });
        return false;
    });
");
?>