<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs=array(
	'Resource Allocation Project Works'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ResourceAllocationProjectWork', 'url'=>array('index')),
	array('label'=>'Create ResourceAllocationProjectWork', 'url'=>array('create')),
	array('label'=>'Update ResourceAllocationProjectWork', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ResourceAllocationProjectWork', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ResourceAllocationProjectWork', 'url'=>array('admin')),
        array('label'=>'View Resource Statistics', 'url'=>array('resourcemanagement')),
);
?>

<h1>View ResourceAllocationProjectWork #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pid',
		'day',
		'date',
		'allocated_resource',
		'comment',
		'created_by',
	),
)); ?>
