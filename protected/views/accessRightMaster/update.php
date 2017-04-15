<?php
/* @var $this AccessRightMasterController */
/* @var $model AccessRightMaster */

$this->breadcrumbs=array(
	'Access Right Masters'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
//
//$this->menu=array(
//	array('label'=>'List AccessRightMaster', 'url'=>array('index')),
//	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
//	array('label'=>'View AccessRightMaster', 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>'Manage AccessRightMaster', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightMaster', 'url'=>array('admin')),
	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
);
?>

<h1>Update AccessRightMaster <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>