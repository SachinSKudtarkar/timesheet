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
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'resource-allocation-project-work-grid',
	'dataProvider'=> new CArrayDataProvider($rawData, array()),
	'filter' => $model,
	'columns'=>array(		
		'name',
		'Program',
		'Project',
		'Task',
		//'estimated_end_date',
		'Estimated_hours',
		'consumed_hours',
		//'Priority',
		 
		
	),
)); 

?>






