<?php
/* @var $this AccessRightDetailsController */
/* @var $model AccessRightDetails */

$this->breadcrumbs=array(
	'Access Right Details'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

//$this->menu=array(
//	array('label'=>'List AccessRightDetails', 'url'=>array('index')),
//	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
//	array('label'=>'View AccessRightDetails', 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>'Manage AccessRightDetails', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightDetails', 'url'=>array('admin')),
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
);
?>

<h1>Update AccessRightDetails <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>