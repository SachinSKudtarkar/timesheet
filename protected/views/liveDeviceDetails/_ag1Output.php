<?php
/* @var $this NddOutputMasterController */
/* @var $model NddOutputMaster */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'ndd-ag1-output-master-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        //'action' => Yii::app()->createUrl('//nddRanLb/CleanupUpdate'),
        'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
    ));
    ?>
<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'modified_sapid'); ?>
        <?php echo $form->textField($model, 'modified_sapid'); ?>
        <?php echo $form->error($model, 'modified_sapid'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'hostname'); ?>
        <?php echo $form->textField($model, 'hostname'); ?>
        <?php echo $form->error($model, 'hostname'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'loopback0'); ?>
        <?php echo $form->textField($model, 'loopback0'); ?>
        <?php echo $form->error($model, 'loopback0'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'loopback0_ipv6'); ?>
        <?php echo $form->textField($model, 'loopback0_ipv6'); ?>
        <?php echo $form->error($model, 'loopback0_ipv6'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'loopback100'); ?>
        <?php echo $form->textField($model, 'loopback100'); ?>
        <?php echo $form->error($model, 'loopback100'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'loopback100_ipv6'); ?>
        <?php echo $form->textField($model, 'loopback100_ipv6'); ?>
        <?php echo $form->error($model, 'loopback100_ipv6'); ?>
    </div>
    <?php echo $form->hiddenField($model, 'id'); ?>
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
            url: '" . CHelper::createUrl("nddAg1OutputMaster/CleanupUpdate") . "',
            type: 'POST',
            data: $('form#ndd-ag1-output-master-form').serialize(),
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