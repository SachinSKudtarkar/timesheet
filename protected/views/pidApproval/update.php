<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Tasks'=>array('index'),
	$model->pid_id=>array('view','id'=>$model->pid_id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Tasks', 'url'=>array('index')),
	array('label'=>'Create Task', 'url'=>array('create')),
	array('label'=>'View Task', 'url'=>array('view', 'id'=>$model->pid_id)),
	array('label'=>'Manage Tasks', 'url'=>array('/subtask/admin')),
);
?>

<h1>Update Task <i><?php echo $model->task_title; ?></i></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'subtask'=>$subtask, 'completeStatus'=>$completeStatus,'projectCStatus' => $projectCStatus)); ?>