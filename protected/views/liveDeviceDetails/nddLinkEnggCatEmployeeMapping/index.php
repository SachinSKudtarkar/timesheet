<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ndd Link Engg Cat Employee Mappings',
);

$this->menu=array(
	array('label'=>'Create NddLinkEnggCatEmployeeMapping', 'url'=>array('create')),
	array('label'=>'Manage NddLinkEnggCatEmployeeMapping', 'url'=>array('admin')),
);
?>

<h1>Ndd Link Engg Cat Employee Mappings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
