<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */
/* @var $form CActiveForm */
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

<div class="form-group clearfix test-panal" style="margin-top: 20px;">
<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->
    <?php //echo $form->errorSummary($model); ?>
    <form class="form no-mr clearfix" action="<?php echo Yii::app()->baseUrl; ?>/resourceallocationprojectwork/resourcearrangement" method="post" id="arrangeResource"> 


        <div class="row">
            <h3>Resource Arrangement With Project</h3>
            <?php echo CHTML::label('Project Name', ''); ?>
            <?php
            $pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
            if ($pid != 0) {
                $projectdata = ProjectManagement::model()->findByPk($pid);
                echo "<b>" . $projectdata->project_name . "</b>";
                echo " <span style='margin-left:150px;'></span>";
                ResourceAllocationProjectWork::model()->renderButtonsSelectProject();
            } else {
                echo CHTML::dropDownList('pid', $pid, CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please Select Project',));
            }
            ?>	
            <input type="hidden" name ="projectid" value="<?php echo $pid; ?>"  />
        </div>

            <?php foreach ($displaydata as $key => $val) { ?>    
            <div class="row"> 
    <?php echo CHTML::label($val, ''); ?>
            <?php
            echo CHTML::textField("Date[$key]", $datedata[$key], array('id' => $key, 'class' => 'datepicker', 'placeholder' => 'Select Deadline Date', 'style' => 'width:200px;'));
            ?></div>
            <?php } ?>

            <?php echo CHtml::submitButton('Submit', array('id' => 'allocateR', 'style' => 'margin-top:20px;')); ?>

    </form>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('ArrangeResource', " 
     
$('#pid').change(function(){ 

$('#arrangeResource').submit();
}); 

$('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
    ", CClientScript::POS_READY);
?>



