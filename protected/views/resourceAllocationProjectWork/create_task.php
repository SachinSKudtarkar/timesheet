<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs = array(
    'Resource Allocation Project Works' => array('index'),
    'Create_task',
);

$this->menu = array(
    array('label' => 'Manage ResourceAllocation for Project Task', 'url' => array('manageTask')),
    
);
?>

<h1>Task Allocation Project Work</h1>

<?php $this->renderPartial('_form_task', array('model' => $model, 'data' => $data, 'dataProvider' => $dataProvider,)); ?>
