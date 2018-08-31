<?php
/* @var $this LevelMasterController */
/* @var $model LevelMaster */

$this->breadcrumbs = array(
    'LevelMaster' => array('index'),
    'Create',
);

$this->menu = array( 
    array('label' => 'Manage Level', 'url' => array('admin')),
    //array('label' => 'Manage Program', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?>

<h1>View Level #<?php echo $model->level_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'level_id',
		'level_name',
		'budget_per_hour',
		'created_by',
		'created_at',
		'modified_by',
		'updated_at',
	),
)); ?>
