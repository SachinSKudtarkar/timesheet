<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */

$this->breadcrumbs=array(
	'Link Engg CR'=> array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage', 'url'=>array('admin')),	
);
?>

<h1>View change request #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'link_no',
		'status',
		'approval_1_status',
		'approval_2_status',
		'approval_3_status',
		'created_at',
		'modified_at',
//		'created_by',
//		'modified_by',
	),
)); ?>
