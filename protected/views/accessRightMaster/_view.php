<?php
/* @var $this AccessRightMasterController */
/* @var $data AccessRightMaster */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('heading')); ?>:</b>
	<?php echo CHtml::encode($data->heading); ?>
	<br />

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('page_url')); ?>:</b>
	<?php //echo CHtml::encode($data->page_url); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('heading_order')); ?>:</b>
	<?php echo CHtml::encode($data->heading_order); ?>
	<br />

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('menu_icon')); ?>:</b>
	<?php //echo CHtml::encode($data->menu_icon); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_disabled')); ?>:</b>
	<?php echo CHtml::encode($data->is_disabled); ?>
	<br />


</div>