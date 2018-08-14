<?php
/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs = array(
    'Project' => array('index'),
    'Create',
);

$this->menu = array( 
    array('label' => 'Manage Project', 'url' => array('admin')),
    array('label' => 'Manage Program', 'url' => array('projectmanagement/admin')),
    array('label' => 'Create Program', 'url' => array('projectmanagement/create')),
    //array('label' => 'Manage Program', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?>

<h1>Create Project</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>