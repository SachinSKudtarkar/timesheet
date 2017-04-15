<?php
/* @var $this EmployeeLoginTrackerController */
/* @var $model EmployeeLoginTracker */

$this->breadcrumbs=array(
	'Employee Login Trackers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EmployeeLoginTracker', 'url'=>array('index')),
	array('label'=>'Create EmployeeLoginTracker', 'url'=>array('create')),
	array('label'=>'Update EmployeeLoginTracker', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EmployeeLoginTracker', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EmployeeLoginTracker', 'url'=>array('admin')),
);
?>

<h1>View EmployeeLoginTracker #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'emp_id',
		'login_time',
		'logout_time',
	),
)); ?>
