<?php
/* @var $this EmployeeController */
/* @var $model Employee */
/* @var $form CActiveForm */
?>

<?php
//$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
//    'id' => 'registration-form',
//    'enableAjaxValidation' => true,
//    'enableClientValidation' => true,
//      'enableAjaxValidation' => false,
//    'enableClientValidation' => false,
//    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
//        ));

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'registration-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
        //'focus'=>array($model,'first_name'),
        ));
?>
<div class="form">
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">

                <?php /* $form=$this->beginWidget('CActiveForm', array(
                  'id'=>'employee-form',
                  // Please note: When you enable ajax validation, make sure the corresponding
                  // controller action is handling ajax validation correctly.
                  // There is a call to performAjaxValidation() commented in generated controller code.
                  // See class documentation of CActiveForm for details on this.
                  'enableAjaxValidation'=>false,
                  )); */ ?>

                <div class="row">
                    <?php echo $form->labelEx($model, 'first_name'); ?>
                    <?php echo $form->textField($model, 'first_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'first_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'middle_name'); ?>
                    <?php echo $form->textField($model, 'middle_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'middle_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'last_name'); ?>
                    <?php echo $form->textField($model, 'last_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'last_name'); ?>
                </div>    

                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 150)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
                 
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'phone', array('class' => '')); ?>

                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'phone',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 20),
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'phone'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'mobile', array('class' => '')); ?>
                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'mobile',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 12)
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'mobile'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'work_location'); ?>
                    <?php echo $form->textField($model_employee_details, 'work_location'); ?>
                    <?php echo $form->error($model_employee_details, 'work_location'); ?>
                </div>

            </div>
            <div class="span6">
                <div class="row">
                    <?php echo $form->labelEx($model, 'password', array('class' => '')); ?>
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->passwordField($model, 'password');
                    } else {
                        echo $form->passwordField($model, 'password', array('value' => ''));
                    }
                    ?> 
                    <?php echo $form->error($model, 'password'); ?>
                </div>
                <div class="row">
                    <label><b>Note:</b></label> 	
                    <span style="color: green" >Password must be at least eight characters long including one uppercase letter, one lowercase letter, one special character & one number with no spaces.</span>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'repeat_password', array('class' => '')); ?>
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->passwordField($model, 'repeat_password');
                    } else {
                        echo $form->passwordField($model, 'repeat_password', array('value' => ''));
                    }
                    ?> 
                    <?php echo $form->error($model, 'repeat_password'); ?>
                </div>   
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'address_1', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'address_1', array('maxlength' => 100, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'address_1'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'city', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'city', array('class' => '', 'maxlength' => 50, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'city'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'state_id', array('class' => '')); ?>
                    <?php
                    echo $form->dropDownList(
                            $model_employee_details, 'state_id', CHtml::listData(StateMaster::model()->findAll(
                                            array('order' => 'state_name_short ASC', 'condition' => 'is_disabled=0')), 'state_id', 'state_name'
                            ), array('empty' => 'Select State', 'class' => '')
                    );
                    ?>

                </div>
                <div>
                    <?php 
//                    CHelper::dump(CCaptcha::checkRequirements());
                    
                    if (CCaptcha::checkRequirements()): ?>
                        <div class="row">
                            <?php echo $form->labelEx($model, 'verifyCode'); ?>
                            <div>
                                <?php $this->widget('CCaptcha'); ?>
                                <?php echo $form->textField($model, 'verifyCode'); ?>
                            </div>
                            <div class="hint">Please enter the letters as they are shown in the image above.
                                <br/>Letters are not case-sensitive.</div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <div class="span3 offset5">
                <div class="form-group">

                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'label' => 'Submit',
                        'htmlOptions' => array(
                            'class' => 'btn-primary sbtn',
                        ),
                    ));
                    ?>             
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => array('/login/index'),
                        'label' => 'Cancel',
                        'htmlOptions' => array(
                            'class' => 'btn-primary',
                        ),
                    ));
                    ?>
                </div>
            </div>

        </div>
    </div>
 
    <?php $this->endWidget(); ?>

</div>
