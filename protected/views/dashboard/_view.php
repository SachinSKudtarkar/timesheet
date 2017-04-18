<?php
/* @var $this NddSapidManagerController */
/* @var $data NddSapidManager */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hm_oldsapid')); ?>:</b>
	<?php echo CHtml::encode($data->hm_oldsapid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hm_newsapid')); ?>:</b>
	<?php echo CHtml::encode($data->hm_newsapid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />


</div>