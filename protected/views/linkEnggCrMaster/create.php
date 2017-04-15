<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */

$this->breadcrumbs = array(
    'Link Engg Change Requests' => array('admin'),
    'Create',
);

$this->menu = array(    
    array('label' => 'Manage Change Requests', 'url' => array('admin')),
    array('label' => 'Create New Change Request', 'url' => array('create')),
);
?>

<h1>Create new change request</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>