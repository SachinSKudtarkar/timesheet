<?php
/* @var $this SubTaskController */
/* @var $model SubTask */

$this->breadcrumbs=array(
	'Sub Tasks'=>array('index'),
	$model->stask_id=>array('view','id'=>$model->stask_id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List SubTask', 'url'=>array('index')),
//	array('label'=>'Create SubTask', 'url'=>array('create')),
//	array('label'=>'View SubTask', 'url'=>array('view', 'id'=>$model->stask_id)),
	array('label'=>'Manage SubTask', 'url'=>array('admin')),
);
?>

<h1>Update SubTask <?php echo $model->stask_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>