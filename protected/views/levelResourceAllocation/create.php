<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs = array(
    'Resource Allocation Program Works' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage Resource Level Allocation', 'url' => array('admin')),
);
?>

<h1>Resource Level Allocation</h1>

<?php $this->renderPartial('_form', array('model' => $model, 'data' => $data, 'dataProvider' => $dataProvider,)); ?>
