<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $model NddLinkEnggCatEmployeeMapping */

$this->breadcrumbs=array(
	'Ndd Link Engg Cat Employee Mappings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List NddLinkEnggCatEmployeeMapping', 'url'=>array('index')),
	array('label'=>'Create NddLinkEnggCatEmployeeMapping', 'url'=>array('create')),
	array('label'=>'View NddLinkEnggCatEmployeeMapping', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage NddLinkEnggCatEmployeeMapping', 'url'=>array('admin')),
);
?>

<h1>Update NddLinkEnggCatEmployeeMapping <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>