<?php
/* @var $this EmployeeLoginTrackerController */
/* @var $model EmployeeLoginTracker */

$this->breadcrumbs=array(
	'Employee Login Trackers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EmployeeLoginTracker', 'url'=>array('index')),
	array('label'=>'Manage EmployeeLoginTracker', 'url'=>array('admin')),
);
?>

<h1>Create EmployeeLoginTracker</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>