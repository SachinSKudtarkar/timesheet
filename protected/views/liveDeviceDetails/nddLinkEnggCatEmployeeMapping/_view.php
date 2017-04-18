<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $data NddLinkEnggCatEmployeeMapping */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('emp_id')); ?>:</b>
	<?php echo CHtml::encode($data->emp_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cat_key_id')); ?>:</b>
	<?php echo CHtml::encode($data->cat_key_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mapping_date_time')); ?>:</b>
	<?php echo CHtml::encode($data->mapping_date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mapped_by')); ?>:</b>
	<?php echo CHtml::encode($data->mapped_by); ?>
	<br />


</div>