<?php
/* @var $this PidMappingController */
/* @var $model PidMapping */

$this->breadcrumbs=array(
	'Pid Mappings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PidMapping', 'url'=>array('index')),
	array('label'=>'Manage PidMapping', 'url'=>array('admin')),
);
?>

<h1>Create PidMapping</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>