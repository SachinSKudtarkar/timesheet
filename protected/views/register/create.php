<?php
$this->layout='//layouts/column1';
/* @var $this EmployeeController */
/* @var $model Employee */

$this->breadcrumbs=array(
	'Login'=>array('login/index'),
	'Register',
);

$this->menu=array(
	array('label'=>'List Employee', 'url'=>array('index')),
	array('label'=>'Manage Employee', 'url'=>array('admin')),
);
?>

<h1>Registration Form</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'model_employee_details'=>$model_employee_details)); ?>