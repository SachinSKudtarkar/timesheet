<?php
/* @var $this TemplateMasterController */
/* @var $model TemplateMaster */

$this->breadcrumbs=array(
	'Template Masters'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TemplateMaster', 'url'=>array('index')),
	array('label'=>'Create TemplateMaster', 'url'=>array('create')),
	array('label'=>'View TemplateMaster', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TemplateMaster', 'url'=>array('admin')),
);
?>

<h1>Update TemplateMaster <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>