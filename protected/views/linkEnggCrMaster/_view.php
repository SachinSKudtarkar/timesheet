<?php
/* @var $this LinkEnggCrMasterController */
/* @var $data LinkEnggCrMaster */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('link_no')); ?>:</b>
	<?php echo CHtml::encode($data->link_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('approval_1_status')); ?>:</b>
	<?php echo CHtml::encode($data->approval_1_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('approval_2_status')); ?>:</b>
	<?php echo CHtml::encode($data->approval_2_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('approval_3_status')); ?>:</b>
	<?php echo CHtml::encode($data->approval_3_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
	<?php echo CHtml::encode($data->created_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_by')); ?>:</b>
	<?php echo CHtml::encode($data->modified_by); ?>
	<br />

	*/ ?>

</div>