<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $model NddLinkEnggCatEmployeeMapping */

$this->breadcrumbs=array(
	'Ndd Link Engg Cat Employee Mappings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NddLinkEnggCatEmployeeMapping', 'url'=>array('index')),
	array('label'=>'Manage NddLinkEnggCatEmployeeMapping', 'url'=>array('admin')),
);
?>

<h1>Create NddLinkEnggCatEmployeeMapping</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>