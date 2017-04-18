<?php
$this->breadcrumbs=array(
	'Profile',
);

?>

<h1>Profile</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'model_employee_details'=>$model_employee_details)); ?>