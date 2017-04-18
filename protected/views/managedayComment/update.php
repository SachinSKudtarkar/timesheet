<?php
/* @var $this ManagedayCommentController */
/* @var $model DayComment */

$this->breadcrumbs=array(
	'Day Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DayComment', 'url'=>array('index')),
	array('label'=>'Create DayComment', 'url'=>array('create')),
	array('label'=>'View DayComment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DayComment', 'url'=>array('admin')),
);
?>

<h1>Update DayComment <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>