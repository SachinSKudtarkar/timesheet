<?php
/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs = array(
    'Project' => array('index'),
    'Create',
);

$this->menu = array( 
    array('label' => 'Manage Level', 'url' => array('levelmaster/admin')),
//    array('label' => 'Manage Program', 'url' => array('projectmanagement/admin')),
 //   array('label' => 'Create Program', 'url' => array('projectmanagement/create')),
    //array('label' => 'Manage Program', 'url' => array('ResourceAllocationProjectWork/admin')),
);
?>

<h1>Create Level</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>

