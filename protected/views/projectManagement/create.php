<?php
/* @var $this ProjectManagementController */
/* @var $model ProjectManagement */

$this->breadcrumbs=array(
	'Program Managements'=>array('index'),
	'Create',
);

$this->menu=array(
        //array('label'=>'Genrate Project ID', 'url'=>array('ProjectManagement/genrate')),
	array('label'=>'Manage Program', 'url'=>array('admin')),
        array('label'=>'Create Project', 'url'=>array('subproject/create')),
        
);
?>

<h1>Create ProjectManagement</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
