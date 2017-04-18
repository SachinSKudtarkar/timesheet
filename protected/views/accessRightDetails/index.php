<?php
/* @var $this AccessRightDetailsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Access Right Details',
);

$this->menu=array(
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
	array('label'=>'Manage AccessRightDetails', 'url'=>array('admin')),
);
?>

<h1>Access Right Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
