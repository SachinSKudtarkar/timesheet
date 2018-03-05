<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'chnage-password-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'htmlOptions' => array('class' => 'well', 'autocomplete' => 'off'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <div class="row"> <?php echo $form->labelEx($model, 'Current Password'); ?> <?php echo $form->passwordField($model, 'password', array('value' => '')); ?> <?php echo $form->error($model, 'password'); ?> </div>

    <div class="row"> <?php echo $form->labelEx($model, 'new_password'); ?> <?php echo $form->passwordField($model, 'new_password'); ?> <?php echo $form->error($model, 'new_password'); ?> </div>
    <div class="row" style="height: 29px;">
        <label style="padding-top: 0px;"><b>Note:</b></label> 	
        <span style="color: green" >Password must be at least eight characters long including one uppercase letter, one lowercase letter, one special character & one number with no spaces.</span>
    </div>
    <div class="row"> <?php echo $form->labelEx($model, 'confirm_new_password'); ?> <?php echo $form->passwordField($model, 'confirm_new_password'); ?> <?php echo $form->error($model, 'confirm_new_password'); ?> </div>

    <div class="row submit">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Change password')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>

