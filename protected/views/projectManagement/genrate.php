<?php
/* @var $this ProjectManagementController */
/* @var $model ProjectManagement */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'Program Managements'=>array('index'),
	'Genrate',
);

$this->menu=array(
	array('label'=>'Manage Program', 'url'=>array('admin')),
       //array('label'=>'Manage Genrate Program', 'url'=>array('project/admin')),
);
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

    <p class="note">Genrate Project ID </p>

        <?php //echo $form->errorSummary($model);  ?>

  
    
    <div class="row">
        <?php echo $form->labelEx($model, 'project_ID'); ?>
<?php echo $form->textField($model, 'project_name', array('size' => 60, 'maxlength' => 250)); ?>
<?php echo $form->error($model, 'project_name'); ?>
    </div>

  
   

    <div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Genrate' : 'Save'); ?>
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