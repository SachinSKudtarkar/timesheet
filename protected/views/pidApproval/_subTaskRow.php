<?php
/* @var $this PidApprovalController */
/* @var $data PidApproval */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->pid_id), array('view', 'id'=>$data->pid_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_project_id')); ?>:</b>
	<?php echo CHtml::encode($data->sub_project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inception_date')); ?>:</b>
	<?php echo CHtml::encode($data->inception_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_est_hrs')); ?>:</b>
	<?php echo CHtml::encode($data->total_est_hrs); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comments')); ?>:</b>
	<?php echo CHtml::encode($data->comments); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
	<?php echo CHtml::encode($data->created_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('approved')); ?>:</b>
	<?php echo CHtml::encode($data->approved); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_deleted')); ?>:</b>
	<?php echo CHtml::encode($data->is_deleted); ?>
	<br />

	*/ ?>

</div>