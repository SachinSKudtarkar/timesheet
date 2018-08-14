<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs = array(
    'Project' => array('index'),
    'Create',
);

$this->menu = array( 
    array('label' => 'Manage Project', 'url' => array('admin')),
    //array('label' => 'Manage Program', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?>

<h1>View Project #<?php echo $model->spid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'spid',
		'pid',
		'sub_project_name',
		'sub_project_description',
		'requester',
		'status',
		'priority',
		'created_by',
		'created_date',
		'updated_by',
		'updated_date',
		'is_deleted',
	),
)); ?>
