<?php
/* @var $this AccessRoleMasterController */
/* @var $model AccessRoleMaster */

$this->breadcrumbs=array(
	'Access Role Masters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AccessRoleMaster', 'url'=>array('index')),
	array('label'=>'Manage AccessRoleMaster', 'url'=>array('admin')),
);
?>

<h1>Create AccessRoleMaster</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>