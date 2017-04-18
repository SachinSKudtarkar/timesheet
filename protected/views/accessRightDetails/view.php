<?php
/* @var $this AccessRightDetailsController */
/* @var $model AccessRightDetails */

$this->breadcrumbs=array(
	'Access Right Details'=>array('index'),
	$model->name,
);

//$this->menu=array(
//	array('label'=>'List AccessRightDetails', 'url'=>array('index')),
//	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
//	array('label'=>'Update AccessRightDetails', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete AccessRightDetails', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage AccessRightDetails', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightDetails', 'url'=>array('admin')),
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
);
?>

<h1>View AccessRightDetails #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'name',
		'value',
		//'page_url',
		'menu_order',
		//'menu_icon',
		'is_disabled',
	),
)); ?>
