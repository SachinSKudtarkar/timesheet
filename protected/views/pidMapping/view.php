<?php
/* @var $this PidMappingController */
/* @var $model PidMapping */

$this->breadcrumbs=array(
	'Pid Mappings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PidMapping', 'url'=>array('index')),
	array('label'=>'Create PidMapping', 'url'=>array('create')),
	array('label'=>'Update PidMapping', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PidMapping', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PidMapping', 'url'=>array('admin')),
);
?>

<h1>View PidMapping #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'year_month',
		'project_id',
		'sub_project_id',
		'task_id',
		'sub_task_id',
		'status',
		'created_at',
		'created_by',
		'modified_at',
		'modified_by',
		'pid',
	),
)); ?>
