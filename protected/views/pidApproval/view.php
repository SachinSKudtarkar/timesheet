<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Pid Approvals'=>array('index'),
	$model->pid_id,
);

$this->menu=array(
	array('label'=>'List PidApproval', 'url'=>array('index')),
	array('label'=>'Create PidApproval', 'url'=>array('create')),
	array('label'=>'Update PidApproval', 'url'=>array('update', 'id'=>$model->pid_id)),
	array('label'=>'Delete PidApproval', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pid_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PidApproval', 'url'=>array('admin')),
);
?>

<h1>View PidApproval #<?php echo $model->pid_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'pid_id',
		'project_id',
		'sub_project_id',
		'inception_date',
		'total_est_hrs',
		'comments',
		'status',
		'created_by',
		'created_at',
		'approved',
		'is_deleted',
	),
)); ?>
