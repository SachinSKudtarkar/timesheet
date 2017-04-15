<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $model NddLinkEnggCatEmployeeMapping */

$this->breadcrumbs=array(
	'Ndd Link Engg Cat Employee Mappings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List NddLinkEnggCatEmployeeMapping', 'url'=>array('index')),
	array('label'=>'Create NddLinkEnggCatEmployeeMapping', 'url'=>array('create')),
	array('label'=>'Update NddLinkEnggCatEmployeeMapping', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete NddLinkEnggCatEmployeeMapping', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NddLinkEnggCatEmployeeMapping', 'url'=>array('admin')),
);
?>

<h1>View NddLinkEnggCatEmployeeMapping #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'emp_id',
		'cat_key_id',
		'is_active',
		'mapping_date_time',
		'mapped_by',
	),
)); ?>
