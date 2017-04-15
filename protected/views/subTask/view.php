<?php
/* @var $this SubTaskController */
/* @var $model SubTask */

$this->breadcrumbs=array(
	'Tasks'=>array('index'),
	$model->stask_id,
);

$this->menu=array(
	array('label'=>'List Task', 'url'=>array('index')),
	array('label'=>'Create Task', 'url'=>array('create')),
	array('label'=>'Update Task', 'url'=>array('update', 'id'=>$model->stask_id)),
	array('label'=>'Delete Task', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->stask_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Task', 'url'=>array('admin')),
);
?>

<h1>View Task #<?php echo $model->stask_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'stask_id',
		'task_id',
		'project_id',
		'sub_project_id',
		'emp_id',
		'sub_task_name',
		'description',
		'status',
		'created_by',
		'created_at',
		'is_approved',
		'is_delete',
	),
)); ?>
