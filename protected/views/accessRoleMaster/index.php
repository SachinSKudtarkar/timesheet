<?php
/* @var $this AccessRoleMasterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Access Role Masters',
);

$this->menu=array(
	array('label'=>'Create AccessRoleMaster', 'url'=>array('create')),
	array('label'=>'Manage AccessRoleMaster', 'url'=>array('admin')),
);
?>

<h1>Access Role Masters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
