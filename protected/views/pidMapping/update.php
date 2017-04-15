<?php
/* @var $this PidMappingController */
/* @var $model PidMapping */

$this->breadcrumbs=array(
	'Pid Mappings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PidMapping', 'url'=>array('index')),
	array('label'=>'Create PidMapping', 'url'=>array('create')),
	array('label'=>'View PidMapping', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PidMapping', 'url'=>array('admin')),
);
?>

<h1>Update PidMapping <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>