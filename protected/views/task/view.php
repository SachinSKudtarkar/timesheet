<?php
/* @var $this TaskController */
/* @var $model Task */

$this->breadcrumbs=array(
	'Type'=>array('index'),
	$model->task_id,
);

$this->menu=array(
	//array('label'=>'List Type', 'url'=>array('index')),
	array('label'=>'Create Type', 'url'=>array('create')),
	array('label'=>'Update Type', 'url'=>array('update', 'id'=>$model->task_id)),
	//array('label'=>'Delete Type', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->task_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Type', 'url'=>array('admin')),
);
?>

<h1>View Type #<?php echo $model->task_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'task_id',
		'task_name',
		'description',
		'status',
		'is_approved',
		'created_by',
		'created_at',
		'is_delete',
	),
)); ?>
