<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Program'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Program', 'url'=>array('index')),
	array('label'=>'Manage Program', 'url'=>array('admin')),
);
?>

<h1>Create Project</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>