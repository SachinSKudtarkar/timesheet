<?php
/* @var $this LiveDeviceDetailsController */
/* @var $data LiveDeviceDetails */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('circle')); ?>:</b>
	<?php echo CHtml::encode($data->circle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lsmr_name')); ?>:</b>
	<?php echo CHtml::encode($data->lsmr_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_name')); ?>:</b>
	<?php echo CHtml::encode($data->group_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enb')); ?>:</b>
	<?php echo CHtml::encode($data->enb); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enb_ip')); ?>:</b>
	<?php echo CHtml::encode($data->enb_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('neid')); ?>:</b>
	<?php echo CHtml::encode($data->neid); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('b11_status')); ?>:</b>
	<?php echo CHtml::encode($data->b11_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('show_run_enb_ip')); ?>:</b>
	<?php echo CHtml::encode($data->show_run_enb_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loopback0_1')); ?>:</b>
	<?php echo CHtml::encode($data->loopback0_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loopback0_2')); ?>:</b>
	<?php echo CHtml::encode($data->loopback0_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('host_name')); ?>:</b>
	<?php echo CHtml::encode($data->host_name); ?>
	<br />

	*/ ?>

</div>