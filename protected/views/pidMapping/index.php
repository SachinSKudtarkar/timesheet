<?php
/* @var $this PidMappingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pid Mappings',
);

$this->menu=array(
	array('label'=>'Create PidMapping', 'url'=>array('create')),
	array('label'=>'Manage PidMapping', 'url'=>array('admin')),
);
?>

<h1>Pid Mappings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
