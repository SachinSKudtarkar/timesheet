<?php
/* @var $this TemplateMasterController */
/* @var $model TemplateMaster */

$this->breadcrumbs = array(
    'Template Masters' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List TemplateMaster', 'url' => array('index')),
    array('label' => 'Manage TemplateMaster', 'url' => array('admin')),
);
?>

<h1>Create TemplateMaster</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
