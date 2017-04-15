<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs=array(
	'Day Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DayComment', 'url'=>array('index')),
	array('label'=>'Create DayComment', 'url'=>array('create')),
	array('label'=>'Update DayComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DayComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DayComment', 'url'=>array('admin')),
);
?>

<h1>View DayComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pid',
		'day',
		'comment',
		'created_by',
	),
)); ?>
