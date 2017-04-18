<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs=array(
	'Resource Allocation Project Works'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ResourceAllocationProjectWork', 'url'=>array('index')),
	array('label'=>'Create ResourceAllocationProjectWork', 'url'=>array('create')),
	array('label'=>'View ResourceAllocationProjectWork', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ResourceAllocationProjectWork', 'url'=>array('admin')),
        array('label'=>'View Resource Statistics', 'url'=>array('resourcemanagement')),
);
?>

<h1>Update ResourceAllocationProjectWork <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>