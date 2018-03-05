<?php
/* @var $this TaskController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Type',
);

$this->menu=array(
	array('label'=>'Create Type', 'url'=>array('create')),
	array('label'=>'Manage Type', 'url'=>array('admin')),
);
?>

<h1>Tasks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
