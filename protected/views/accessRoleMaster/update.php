<?php
/* @var $this AccessRoleMasterController */
/* @var $model AccessRoleMaster */

$this->breadcrumbs=array(
	'Access Role Masters'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AccessRoleMaster', 'url'=>array('index')),
	array('label'=>'Create AccessRoleMaster', 'url'=>array('create')),
	array('label'=>'View AccessRoleMaster', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccessRoleMaster', 'url'=>array('admin')),
);
?>

<h1>Update AccessRoleMaster <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>