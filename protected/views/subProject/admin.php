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
          'created_date',
          array(
            'header'=>'Updated By',
            'name' => 'updated_by',
            'type' => 'raw',
            //'filter' => false,
            'value' => array($model, 'getUpdatedBy')
              ),
           array(
            'header'=>'Approval Status',
            'name' => 'approval_status',
            'type' => 'raw',
            //'filter' => false,
            'value' => array($model, 'getApprovalStatus')
              ),
          'updated_date',
          'is_deleted',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
