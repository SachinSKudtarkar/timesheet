<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs = array(
    // 'Resource Allocation Program Works' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage Resource Allocation', 'url' => array('admin')),
    array('label'=>'Manage Program', 'url'=>array('admin')),
   
);
?>

<h1>Resource Allocation Program Work</h1>

<?php $this->renderPartial('_form', array('model' => $model, 'data' => $data, 'dataProvider' => $dataProvider,)); ?>
