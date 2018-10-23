<?php
/* @var $this LevelMasterController */
/* @var $model LevelMaster */

$this->breadcrumbs=array(
	'Task'=>array('index'),
	'Update',
);

$this->menu=array( 
	array('label'=>'Manage Day Comments', 'url'=>array('adminAll'))
);
// echo '<pre>';
// print_r($model);
?>

<h1>Update <?php echo $model->sub_task_name; ?></h1>

<div class="span5">
	<div class="form">

	    <?php
	        $form=$this->beginWidget('CActiveForm', array(
	            'id'=>'approve_hrsr',
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
	            <?php echo $form->labelEx($model,'program_name'); ?>
	            <?php echo $form->textField($model,'program_name',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'program_name'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'project_name'); ?>
	            <?php echo $form->textField($model,'project_name',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'project_name'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'task_name'); ?>
	            <?php echo $form->textField($model,'task_name',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'task_name'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'user_name'); ?>
	            <?php echo $form->textField($model,'user_name',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'user_name'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'sub_task_name'); ?>
	            <?php echo $form->textField($model,'sub_task_name',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'sub_task_name'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'hours'); ?>
	            <?php // echo $form->textField($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
	            <?php echo $form->textField($model,'hours',array('readonly'=>true)); ?>
	            <?php echo $form->error($model,'hours'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'approved_hrs'); ?>
	            <?php
	            	if(!empty($model->approved_hrs))
	            	{
	            		$hrsmins = explode(':',$model->approved_hrs);
	            	}
	            	$utilizedhrsmins = explode(':',$model->hours);
					$arrHrs = array();
					$hrs = $utilizedhrsmins[0] - 1;


	                for($h=0; $h<=$hrs; $h++) {
						$h = (strlen($h) < 2) ? "0".$h : $h;
						$arrHrs[$h] = $h;
					}
					echo CHTML::dropDownList('hrs', $hrsmins[0], $arrHrs, array('style' => 'width:50px;margin-right:20px  ', 'class'=>'wrkhrsClass'));
				?>
				<?php
					$arrMins = array();
					$mins = 59;


	                for($h=0; $h<=$mins; $h++) {
						$h = (strlen($h) < 2) ? "0".$h : $h;
						$arrMins[$h] = $h;
					}
					echo CHTML::dropDownList('mins',$hrsmins[1], $arrMins, array('style' => 'width:50px;  ', 'class'=>'wrkminsClass'));
				?>
	            <?php echo $form->error($model,'approved_hrs'); ?>
		</div>
		<div class="row">
	            <?php echo $form->labelEx($model,'remarks'); ?>
	            <?php // echo $form->textField($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
	            <?php echo $form->textArea($model,'remarks'); ?>
	            <?php echo $form->error($model,'remarks'); ?>
		</div>

	        <div class="row buttons">
	            <?php echo CHtml::submitButton('Save'); ?>
	        </div>
	    <?php $this->endWidget(); ?>
	</div>
</div>
<div class="span5">
	<h1 class="text-center">Approved Hours Log</h1>
	<table class="table table-striped table-bordered ">
        <tr>
            <th>Approved Date/Time</th>
            <th>Approved Hours</th>
            <th>Remarks</th>
        </tr>
        <?php foreach($logmodel as $log){ ?>
        <tr>

            <td><?php echo (isset($log['created_at'])) ? $log['created_at'] : '-'; ?></td>
            <td><?php echo (isset($log['approved_hrs'])) ? $log['approved_hrs'] : '-'; ?></td>
            <td><?php echo (isset($log['remarks'])) ? $log['remarks'] : '-'; ?></td>
        </tr>
    	<?php } ?>
    </table>
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
    // Yii::app()->clientScript->registerScript('filters', "

    //     $('.datepicker').datepicker({
    //      dateFormat: 'yy-m-d',
    //      onSelect: function(dateText) {
    //         var type = $(this).attr('id');
    //         var date = $(this).val();
    //       },
    //     }).attr('readonly','readonly');
    // ", CClientScript::POS_READY);
?>