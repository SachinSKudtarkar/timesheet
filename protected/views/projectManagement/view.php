<?php
/* @var $this ProjectManagementController */
/* @var $model ProjectManagement */

$this->breadcrumbs = array(
    'Project Managements' => array('index'),
	$model->pid,
);

$this->menu = array( 
    array('label' => 'Create ProjectManagement', 'url' => array('create')),
    array('label' => 'Update ProjectManagement', 'url' => array('update', 'id' => $model->pid)),
    array('label' => 'Delete ProjectManagement', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->pid), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage ProjectManagement', 'url' => array('admin')),
);
?>

<h1>View Project Details</h1>

<?php
$this->widget('ext.groupgridview.GroupGridView', array(
    'id' => 'project-management-grid',
    'htmlOptions' => array('class' => 'grid-view clearfix'),
    'dataProvider' => $model->search(),
    'mergeColumns' => array('pid','project_name', 'project_description', 'requester', 'estimated_end_date', 'total_hr_estimation_hour', 'status', 'type', 'hr_clocked',),
    'filter' => $model,
    'type' => 'striped bordered',
    'columns' => array(
		'pid',
		'project_name',
        'sub_project_name',
		'project_description',
		'requester',
		'estimated_end_date',
		'total_hr_estimation_hour',
		'status',
		'type',
		'hr_clocked',
        /* 'created_by',
		'created_date',
		'updated_by',
		'updated_date',
		'is_deleted',
         */
        array(
            'class' => 'CButtonColumn',
	),
    ),
));
?> 
