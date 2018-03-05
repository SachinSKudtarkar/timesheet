<?php
/* @var $this EmployeeController */
/* @var $model Employee */
$this->layout='column1';
$this->customeModel=$model;
$this->breadcrumbs=array(
	'Employees'=>array('index'),
	'Update',
);

?>

<h1>Update Employee <?php echo $model->emp_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'model_employee_details'=>$model_employee_details)); ?>