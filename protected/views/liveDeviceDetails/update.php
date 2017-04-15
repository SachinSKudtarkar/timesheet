<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */

$this->breadcrumbs=array(
	'Live Device Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LiveDeviceDetails', 'url'=>array('index')),
	array('label'=>'Create LiveDeviceDetails', 'url'=>array('create')),
	array('label'=>'View LiveDeviceDetails', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LiveDeviceDetails', 'url'=>array('admin')),
);
?>

<h1>Update LiveDeviceDetails <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>