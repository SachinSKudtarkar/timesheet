<?php

/* @var $this LevelMasterController */
/* @var $model LevelMaster */

$this->breadcrumbs = array(
    'Task' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create Level', 'url' => array('create')),
    array('label' => 'Allocate Level To Resource', 'url' => array('projectmanagement/create')),
    /*array('label' => 'Manage Program', 'url' => array('projectmanagement/admin')), */
);
?>

<h1>Manage Projects</h1>

<?php
// CHelper::debug($model);
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'level-master-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'level_id',
        //'pid',
       /*  array(
            'header'=>'Program',
            'name' => 'level_id',
            'type' => 'raw',
            // 'filter' => false,
            'value' => array($model, 'getProgram')
        ), */
        // 'program',
        'level_name',
        'budget_per_hour',
       // 'estimated_end_date',
        //  'total_hr_estimation_hour',
          'created_by',
          'created_at',
          'modified_by',
          'updated_at',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
