<?php
/* @var $this EmployeeController */
/* @var $model Employee */
$this->layout='column1';
$this->customeModel=$model;
$this->breadcrumbs=array(
	'Manage Roles'=>array('index'),
	'Update',
);

?>

<h1>Update Roles ( <?php echo $model->name; ?> )</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>