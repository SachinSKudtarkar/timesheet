<?php
/* @var $this NddRanLbController */
/* @var $model NddRanLb */
/* @var $form CActiveForm */
?>
<div class="form">
    <?php
//    $form = $this->beginWidget('CActiveForm', array(
//        'id' => 'ndd-ran-lb-form',
//        // Please note: When you enable ajax validation, make sure the corresponding
//        // controller action is handling ajax validation correctly.
//        // There is a call to performAjaxValidation() commented in generated controller code.
//        // See class documentation of CActiveForm for details on this.
//        'enableAjaxValidation' => true,
//        //'htmlOptions' => array('enctype' => 'multipart/form-data'),
//    ));

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'ndd-ran-lb-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        //'action' => Yii::app()->createUrl('//nddRanLb/CleanupUpdate'),
        'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
    ));
    ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'ipv4'); ?>
        <?php echo $form->textField($model, 'ipv4', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'ipv4'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'modified_sapid'); ?>
        <?php echo $form->textField($model, 'modified_sapid'); ?>
        <?php echo $form->error($model, 'modified_sapid'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'host_name'); ?>
        <?php echo $form->textField($model, 'host_name'); ?>
        <?php echo $form->error($model, 'host_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'ipv6'); ?>
        <?php echo $form->textField($model, 'ipv6'); ?>
        <?php echo $form->error($model, 'ipv6'); ?>
    </div>
    <?php echo $form->hiddenField($model, 'ran_lb_id'); ?>
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
            url: '" . CHelper::createUrl("nddRanLb/CleanupUpdate") . "',
            type: 'POST',
            data:$('form#ndd-ran-lb-form').serialize(),
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