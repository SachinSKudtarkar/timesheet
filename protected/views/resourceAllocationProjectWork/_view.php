<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $data ResourceAllocationProjectWork */
?>

<div class="view">
<tr align="center"><td ><?php echo CHtml::encode($data->id); ?> </td>
                <td><span style="float:left; margin-left: 20px;"><?php echo CHtml::encode($data->pid); ?><span> </td>
                            <td><span style="float:left; margin-left: 20px;"><?php echo CHtml::encode($data->day); ?></span> </td></tr>
                            
                            
<!--	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('day')); ?>:</b>
	<?php echo CHtml::encode($data->day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('allocated_resource')); ?>:</b>
	<?php echo CHtml::encode($data->allocated_resource); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
	<?php echo CHtml::encode($data->created_by); ?>
	<br />-->


</div>