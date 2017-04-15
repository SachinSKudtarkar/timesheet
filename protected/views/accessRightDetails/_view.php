<?php
/* @var $this AccessRightDetailsController */
/* @var $data AccessRightDetails */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('page_url')); ?>:</b>
	<?php //echo CHtml::encode($data->page_url); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('menu_order')); ?>:</b>
	<?php echo CHtml::encode($data->menu_order); ?>
	<br />

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('menu_icon')); ?>:</b>
	<?php //echo CHtml::encode($data->menu_icon); ?>
	<br />-->

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_disabled')); ?>:</b>
	<?php echo CHtml::encode($data->is_disabled); ?>
	<br />

	*/ ?>

</div>