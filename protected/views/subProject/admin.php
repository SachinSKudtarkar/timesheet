<?php

/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs = array(
    'Task' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create Project', 'url' => array('create')),
    array('label' => 'Create Program', 'url' => array('projectmanagement/create')),
    array('label' => 'Manage Program', 'url' => array('projectmanagement/admin')),
);
?>

<h1>Manage Projects</h1>

<?php
// CHelper::debug($model);
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sub-project-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'taskId',
        //'pid',
		'project_id',
        array(
            'header'=>'Program',
            'name' => 'pid',
            'type' => 'raw',
            // 'filter' => false,
            'value' => array($model, 'getProgram')
        ),
        // 'program',
        'sub_project_name',
        'sub_project_description',
        'requester',
     // 'estimated_end_date',
        //  'total_hr_estimation_hour',
          array(
            'header'=>'Created By',
            'name' => 'created_by',
            'type' => 'raw',
           //'filter' => false,
            'value' => array($model, 'getCreatedBy')
              ),
          //'created_date',
	    array(
            'header'=>'Created Date',
            'name' => 'created_date',
            'type' => 'raw',
            // 'filter' => false,
            'value' => array($model, 'getCreatedDate')
            ),
	    array(
                    'name' => 'approval_status',
                    'header' => "Approval Status",
                    'value' => array($model, 'getApprovalStatus'),
                    'type' => 'raw',
                    'filter' => array('2' => "Pending", '1' => "Approved", "0" => "Rejected"),
                    'htmlOptions' => array('style' => "text-align:center;"),
            ),
           'status',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
