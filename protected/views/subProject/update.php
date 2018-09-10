<?php
/* @var $this SubProjectController */
/* @var $model SubProject */

$this->breadcrumbs=array(
	'Task'=>array('index'),
	$model->spid=>array('view','id'=>$model->spid),
	'Update',
);

$this->menu=array( 
	array('label'=>'Create Project', 'url'=>array('create')),
	array('label'=>'View Project', 'url'=>array('view', 'id'=>$model->spid)),
	array('label'=>'Manage Project', 'url'=>array('admin')),
);
?>

<h1>Update Project <?php echo $model->sub_project_name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'levels'=>$levels,'hours_label'=>$hours_label)); ?>