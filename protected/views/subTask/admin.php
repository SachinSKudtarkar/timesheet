<?php
/* @var $this SubTaskController */
/* @var $model SubTask */

$this->breadcrumbs=array(
	'Tasks'=>array('index'),
	'Manage',
);

$this->menu=array(
//	array('label'=>'List Task', 'url'=>array('index')),
//	array('label'=>'Create Task', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sub-task-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tasks</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sub-task-grid',
	'dataProvider'=>$model->search(),//new CArrayDataProvider($data, array()),
	'filter'=>$model,
	'columns'=>array(
		'stask_id',
		//'project_id',
//		'project_name',
//		'sub_project_name',
//		'task_name',
//		'employee',
		// array(
                    // 'name'=> 'project_id',
                    // 'type'=> 'raw',
                    // 'value'=>array($model,'getNameById'),
                // ),
		//'sub_project_id',
		// array(
                    // 'name'=> 'sub_project_id',
                    // 'type'=> 'raw',
                    // 'value'=>array($model,'getSubProject'),
                // ),
		//'task_id',
		// array(
                    // 'name'=> 'task_id',
                    // 'type'=> 'raw',
                    // 'value'=>array($model,'getType'),
                // ),
		 'sub_task_name',
                 'est_hrs',
		//'emp_id',
		 array(
                     'name'=> 'emp_id',
                     'filter'=>false,
                     'type'=> 'raw',
                     'value'=>array($model,'GetResourceName'),
                 ),
		/*
		'description',
		'status',
		'created_by',
		'created_at',
		'is_approved',
		'is_delete',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
