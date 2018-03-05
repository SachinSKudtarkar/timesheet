<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */

$this->breadcrumbs=array(
	'Live Device Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LiveDeviceDetails', 'url'=>array('index')),
	array('label'=>'Create LiveDeviceDetails', 'url'=>array('create')),
	array('label'=>'Update LiveDeviceDetails', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LiveDeviceDetails', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LiveDeviceDetails', 'url'=>array('admin')),
);
?>

<h1>View LiveDeviceDetails #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'circle',
		'lsmr_name',
		'group_name',
		'enb',
		'enb_ip',
		'neid',
		'b11_status',
		'show_run_enb_ip',
		'loopback0_1',
		'loopback0_2',
		'host_name',
	),
)); ?>
