<?php
$this->breadcrumbs = array(
    'Manage Approvers' => array('admin'),
    'assign/unassign categories to employees',
);
?>
<span class="" id="result_msg"></span>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'employee-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
        //'focus'=>array($model,'first_name'),
        ));
?>
<div class="circlebox">
    <h5>Select Category</h5>
    <?php 
    echo $form->dropDownList($model, 'cat_key_id', Yii::app()->params['ler_updation_categories'], array('class' => 'span12', 'prompt' => 'Select Category')); 
//    echo CHtml::dropDownList('categories', '', Yii::app()->params['ler_updation_categories'], array('empty' => 'Select'));
    ?>
    <h5>Select Level</h5>
    <?php 
    echo $form->dropDownList($model, 'level_id', Yii::app()->params['ler_updation_levels'], array('class' => 'span12', 'prompt' => 'Select Level')); 
//    echo CHtml::dropDownList('categories', '', Yii::app()->params['ler_updation_categories'], array('empty' => 'Select'));
    ?>

</div>
<div class="circlebox">
    <h5></h5>
    <?php

//    echo $form->dropDownList($modelSolutionPartner, 'circles', $circleList, array('class' => 'span12', 'prompt' => 'Select Circle'));
    ?>

</div>
<div class="row-fluid">
    <div class="span5">
        <h5>Employees List</h5>
        <?php 
//            CHelper::debug($unmapped_employee_list);
 echo $form->dropDownList(Employee::model(), 'emp_id', CHtml::listData($unmapped_employee_list,'value','text'), array('name' => 'from', "id" => "multiselect", "class" => "span12", "size" => "8", "multiple" => "multiple")); 
// echo CHtml::dropDownList('upmapped_employees', '', '',array('name' => 'from', "id" => "multiselect", "class" => "span12", "size" => "8", "multiple" => "multiple"))  ?>

    </div>

    <div class="span2">
        <h5>&nbsp;</h5>
        <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="icon-forward"></i></button>
        <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="icon-chevron-right"></i></button>
        <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="icon-chevron-left"></i></button>
        <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="icon-backward"></i></button>
    </div>


    <div class="span5">
        <h5>Employees Mapped With Selected Category</h5>
        <?php echo $form->dropDownList($model, 'emp_id', CHtml::listData($mapped_employees_list,'value','text'), array('name' => 'to', "id" => "multiselect_to", "class" => "span12", "size" => "8", "multiple" => "multiple")); ?>
        <?php // echo CHtml::dropDownList('mapped_employees', '', '',array('name' => 'from', "id" => "multiselect", "class" => "span12", "size" => "8", "multiple" => "multiple"))  ?>
    </div>
</div>

<?php $this->endWidget(); ?>
<?php
$jsUrl = CHelper::projectUrl();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($jsUrl . '/themes/cisco/js/multiselect.js', CClientScript::POS_END);
$cs->registerScriptFile($jsUrl . '/themes/cisco/js/link_engg_cat_mapping.js', CClientScript::POS_END);
?>
