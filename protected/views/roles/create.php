<?php
$this->layout='//layouts/column1';
/* @var $this EmployeeController */
/* @var $model Employee */

$this->breadcrumbs=array(
	'Manage Roles'=>array('index'),
	'Create',
);


?>

<h1>Create Roles</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>