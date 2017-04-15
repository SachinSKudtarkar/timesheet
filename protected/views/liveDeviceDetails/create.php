<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */

$this->breadcrumbs=array(
	'Live Device Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LiveDeviceDetails', 'url'=>array('index')),
	array('label'=>'Manage LiveDeviceDetails', 'url'=>array('admin')),
);
?>

<h1>Create LiveDeviceDetails</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>