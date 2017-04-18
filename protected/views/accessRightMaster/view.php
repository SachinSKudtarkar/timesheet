<?php
/* @var $this AccessRightMasterController */
/* @var $model AccessRightMaster */

$this->breadcrumbs=array(
	'Access Right Masters'=>array('index'),
	$model->id,
);

//$this->menu=array(
//	array('label'=>'List AccessRightMaster', 'url'=>array('index')),
//	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
//	array('label'=>'Update AccessRightMaster', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete AccessRightMaster', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage AccessRightMaster', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightDetails', 'url'=>array('admin')),
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
);
?>

<h1>View AccessRightMaster #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'heading',
	//	'page_url',
		'heading_order',
	//	'menu_icon',
		'is_disabled',
	),
)); ?>
