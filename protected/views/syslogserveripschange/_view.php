<?php
/* @var $this SyslogserveripschangeController */
/* @var $data Syslogserveripschange */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip_address')); ?>:</b>
	<?php echo CHtml::encode($data->ip_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hostname')); ?>:</b>
	<?php echo CHtml::encode($data->hostname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('site_sap_name')); ?>:</b>
	<?php echo CHtml::encode($data->site_sap_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sys_cha_ip')); ?>:</b>
	<?php echo CHtml::encode($data->sys_cha_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip_found')); ?>:</b>
	<?php echo CHtml::encode($data->ip_found); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sys_log_path')); ?>:</b>
	<?php echo CHtml::encode($data->sys_log_path); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Status')); ?>:</b>
	<?php echo CHtml::encode($data->Status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_at')); ?>:</b>
	<?php echo CHtml::encode($data->status_at); ?>
	<br />

	*/ ?>

</div>