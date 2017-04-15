<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Pid Approvals'=>array('index'),
	$model->pid_id=>array('view','id'=>$model->pid_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PidApproval', 'url'=>array('index')),
	array('label'=>'Create PidApproval', 'url'=>array('create')),
	array('label'=>'View PidApproval', 'url'=>array('view', 'id'=>$model->pid_id)),
	array('label'=>'Manage PidApproval', 'url'=>array('admin')),
);
?>

<h1>Update PidApproval <?php echo $model->pid_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'subtask'=>$subtask)); ?>