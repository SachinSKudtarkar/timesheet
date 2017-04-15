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
                'spid',
	        'sub_project_name',
		'total_hr_estimation_hour',
		'allocated_resource',
                array('type' => 'raw', 'header' => 'Allocate_Hours', 'value' => array($model, 'getAllocateHrLink')),
		
	),
)); 


//$this->endWidget();

Yii::app()->clientScript->registerScript('AllocateHrLink_script', "
    $('.AllocateHrLink').live('click',function(){
    var spid = this.value;
   alert(spid)
    }); ", CClientScript::POS_HEAD);
?>







