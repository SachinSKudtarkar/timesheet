<?php
/* @var $this TaskController */
/* @var $model Task */

$this->breadcrumbs=array(
	'Type'=>array('index'),
	$model->task_id=>array('view','id'=>$model->task_id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Type', 'url'=>array('index')),
	array('label'=>'Create Type', 'url'=>array('create')),
	array('label'=>'View Type', 'url'=>array('view', 'id'=>$model->task_id)),
	array('label'=>'Manage Type', 'url'=>array('admin')),
);
?>

<h1>Update Type <?php echo $model->task_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>