<?php
/* @var $this EmployeeLoginTrackerController */
/* @var $model EmployeeLoginTracker */

$this->breadcrumbs=array(
	'Employee Login Trackers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EmployeeLoginTracker', 'url'=>array('index')),
	array('label'=>'Create EmployeeLoginTracker', 'url'=>array('create')),
	array('label'=>'View EmployeeLoginTracker', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EmployeeLoginTracker', 'url'=>array('admin')),
);
?>

<h1>Update EmployeeLoginTracker <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>