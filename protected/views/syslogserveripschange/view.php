<?php
/* @var $this SyslogserveripschangeController */
/* @var $model Syslogserveripschange */

$this->breadcrumbs=array(
	'Syslogserveripschanges'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Syslogserveripschange', 'url'=>array('index')),
	array('label'=>'Create Syslogserveripschange', 'url'=>array('create')),
	array('label'=>'Update Syslogserveripschange', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Syslogserveripschange', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Syslogserveripschange', 'url'=>array('admin')),
);
?>

<h1>View Syslogserveripschange #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ip_address',
		'hostname',
		'site_sap_name',
		'sys_cha_ip',
		'ip_found',
		'sys_log_path',
		'created_at',
		'Status',
		'status_at',
	),
)); ?>
