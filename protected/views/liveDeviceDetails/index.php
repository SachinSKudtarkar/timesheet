<?php
/* @var $this LiveDeviceDetailsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Live Device Details',
);

$this->menu=array(
	array('label'=>'Create LiveDeviceDetails', 'url'=>array('create')),
	array('label'=>'Manage LiveDeviceDetails', 'url'=>array('admin')),
);
?>

<h1>Live Device Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
