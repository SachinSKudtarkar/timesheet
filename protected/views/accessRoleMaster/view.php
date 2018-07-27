<?php
/* @var $this AccessRoleMasterController */
/* @var $model AccessRoleMaster */

$this->breadcrumbs=array(
	'Access Role Masters'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AccessRoleMaster', 'url'=>array('index')),
	array('label'=>'Create AccessRoleMaster', 'url'=>array('create')),
	array('label'=>'Update AccessRoleMaster', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AccessRoleMaster', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AccessRoleMaster', 'url'=>array('admin')),
);
?>

<h1>View AccessRoleMaster #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'emp_id',
		'access_type',
		'is_active',
		'created_by',
		'created_date',
		'updated_date',
		'updated_by',
	),
)); ?>
