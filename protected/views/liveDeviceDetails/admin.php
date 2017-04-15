<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */

$this->breadcrumbs=array(
	'Live Device Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List LiveDeviceDetails', 'url'=>array('index')),
	array('label'=>'Create LiveDeviceDetails', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#live-device-details-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Live Device Details</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'live-device-details-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
//		'id',
//		'circle',
//		'lsmr_name',
//		'group_name',
		'enb',
                'host_name',
                'loopback0_1',
		'loopback0_2',
		//'neid',
                'enb_ip',
		//'b11_status',
		'show_run_enb_ip',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
