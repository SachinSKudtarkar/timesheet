<?php

/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs = array(
    'Task' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create Project', 'url' => array('create')),
    array('label' => 'Manage Project', 'url' => array('admin')),
    array('label' => 'Manage Program', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?> 

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sub-project-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'taskId',
        //'pid',
        'sub_project_name',
        'sub_project_description',
        'requester',
        'estimated_end_date',
          'total_hr_estimation_hour',
          /*'created_by',
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
