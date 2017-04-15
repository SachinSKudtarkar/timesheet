<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Pid Approvals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PidApproval', 'url'=>array('index')),
	array('label'=>'Manage PidApproval', 'url'=>array('admin')),
);
?>

<h1>Create PidApproval</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'subtask'=>$subtask)); ?>