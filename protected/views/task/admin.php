<?php
/* @var $this TaskController */
/* @var $model Task */

$this->breadcrumbs=array(
	'Type'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Type', 'url'=>array('index')),
	array('label'=>'Create Type', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#task-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Type</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'task-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'task_id',
		'task_name',
		'description',
		//'status',
		//'is_approved',
		//'created_by',
		/*
		'created_at',
		'is_delete',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
