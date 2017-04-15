<?php
/* @var $this AccessRightMasterController */
/* @var $model AccessRightMaster */

$this->breadcrumbs=array(
	'Access Right Masters'=>array('index'),
	'Create',
);

//$this->menu=array(
//	array('label'=>'List AccessRightMaster', 'url'=>array('index')),
//	array('label'=>'Manage AccessRightMaster', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightMaster', 'url'=>array('admin')),
	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
);
?>

<h1>Create AccessRightMaster</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>