<?php
/* @var $this NddCoreWanController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lic Install Tool',
);

$this->menu=array(
	array('label'=>'Upload Input File', 'url'=>array('create')),
	array('label'=>'Manage', 'url'=>array('admin')),
);
?>

<h1>CORE WAN</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
