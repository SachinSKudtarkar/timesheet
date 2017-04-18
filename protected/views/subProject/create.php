<?php
/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs = array(
    'Project' => array('index'),
    'Create',
);

$this->menu = array( 
    array('label' => 'Manage Project', 'url' => array('admin')),
    array('label' => 'Manage Project', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?>

<h1>Create Project</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>