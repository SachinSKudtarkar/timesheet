<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Tasks'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List PidApproval', 'url'=>array('index')),
	array('label'=>'Manage Task', 'url'=>array('/subtask/admin')),
);
?>

<h1>Create Task</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'subtask'=>$subtask)); ?>