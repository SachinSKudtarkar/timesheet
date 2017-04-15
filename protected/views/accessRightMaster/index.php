<?php
/* @var $this AccessRightMasterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Access Right Masters',
);

//$this->menu=array(
//	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
//	array('label'=>'Manage AccessRightMaster', 'url'=>array('admin')),
//);
$this->menu=array(
	array('label'=>'List AccessRightMaster', 'url'=>array('admin')),
	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
);
?>

<h1>Access Right Masters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
