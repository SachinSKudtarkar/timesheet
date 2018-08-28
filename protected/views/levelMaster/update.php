<?php
/* @var $this LevelMasterController */
/* @var $model LevelMaster */

$this->breadcrumbs=array(
	'Task'=>array('index'),
	$model->level_id=>array('view','id'=>$model->level_id),
	'Update',
);

$this->menu=array( 
	array('label'=>'Create Level', 'url'=>array('create')),
	array('label'=>'View Level', 'url'=>array('view', 'id'=>$model->level_id)),
	array('label'=>'Manage Level', 'url'=>array('admin')),
);
?>

<h1>Update Level <?php echo $model->level_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>