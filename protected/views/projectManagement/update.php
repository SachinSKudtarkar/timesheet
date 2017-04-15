<?php
/* @var $this ProjectManagementController */
/* @var $model ProjectManagement */

$this->breadcrumbs=array(
	'Program Managements'=>array('index'),
	$model->pid=>array('view','id'=>$model->pid),
	'Update',
);

$this->menu=array( 
	array('label'=>'Create Program', 'url'=>array('create')),
	array('label'=>'View Program', 'url'=>array('view', 'id'=>$model->pid)),
	array('label'=>'Manage Program', 'url'=>array('admin')),
);
?>

<h1>Update Project <?php echo $model->pid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>