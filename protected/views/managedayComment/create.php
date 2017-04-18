<?php
/* @var $this ManagedayCommentController */
/* @var $model DayComment */

$this->breadcrumbs=array(
	'Day Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DayComment', 'url'=>array('index')),
	array('label'=>'Manage DayComment', 'url'=>array('admin')),
);
?>

<h1>Create DayComment</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>