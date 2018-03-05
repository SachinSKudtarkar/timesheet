<?php
/* @var $this EmployeeLoginTrackerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Employee Login Trackers',
);

$this->menu=array(
	array('label'=>'Create EmployeeLoginTracker', 'url'=>array('create')),
	array('label'=>'Manage EmployeeLoginTracker', 'url'=>array('admin')),
);
?>

<h1>Employee Login Trackers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
