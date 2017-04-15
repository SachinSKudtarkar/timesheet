<?php
/* @var $this PidApprovalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pid Approvals',
);

$this->menu=array(
	array('label'=>'Create PidApproval', 'url'=>array('create')),
	array('label'=>'Manage PidApproval', 'url'=>array('admin')),
);
?>

<h1>Pid Approvals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
