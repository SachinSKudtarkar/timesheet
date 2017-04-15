<?php
/* @var $this AccessRightDetailsController */
/* @var $model AccessRightDetails */

$this->breadcrumbs=array(
	'Access Right Details'=>array('index'),
	'Create',
);

//$this->menu=array(
//	array('label'=>'List AccessRightDetails', 'url'=>array('index')),
//	array('label'=>'Manage AccessRightDetails', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightDetails', 'url'=>array('admin')),
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
);
?>

<h1>Create AccessRightDetails</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>