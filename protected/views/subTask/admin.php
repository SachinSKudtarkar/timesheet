<?php
/* @var $this SubTaskController */
/* @var $model SubTask */

$this->breadcrumbs=array(
	'Tasks'=>array('index'),
	'Manage',
);

 $this->menu=array(
 	array('label'=>'Create Tasks', 'url'=>array('pidapproval/create')),
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
//		'employee',
//		'task_name',
		 array(
                     'name'=> 'project_id',
                     'type'=> 'raw',
                     'value'=>array($model,'getNameById'),
                 ),
		array(
                     'name'=> 'sub_project_id',
                     'type'=> 'raw',
                     'value'=>array($model,'getSubProject'),
                ),
		//'task_id',
//		 array(
//                     'header'=> 'Jira Id',
//                     'name'=> 'jira_id',
//                     'type'=> 'raw',
//                 ),
		 array(
                     'name'=> 'task_id',
                     'type'=> 'raw',
                     'value'=>array($model,'getType'),
                 ),
		 array(
                     'header'=> 'Task Title',
                     'name'=> 'pid_approval_id',
                     'type'=> 'raw',
                     'value'=>array($model,'getTaskTitle'),
                 ),
		 array(
                     'header'=> 'Sub Jira Id',
                     'name'=> 'st_jira_id',
                     'type'=> 'raw',
                 ),
		 'sub_task_name',
                 'est_hrs',
		//'emp_id',
		 array(
                     'header'=> 'Assigned to',
                     'name'=> 'emp_id',
                     'type'=> 'raw',
                     'value'=>array($model,'GetResourceName'),
                 ),
		 array(
                     'header'=> 'Assigned By',
                     'name'=> 'created_by',
                     'type'=> 'raw',
                     'value'=>array($model,'GetCreatedBy'),
                 ),
		'created_at',
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
            'template' => '{update}{delete}',
            'buttons' => array
                ('update' => array
                    (
                    'label' => 'Update',
                    'url' => 'Yii::app()->createUrl("/pidapproval/update", array("id"=>$data["pid_approval_id"]))',
                    'visible' => "CHelper::isAccess('MANAGER','update')",
                ),
                'delete' => array
                    (
                    'label' => 'Delete',
                    'url' => 'Yii::app()->createUrl("/pidapproval/delete", array("id"=>$data["pid_approval_id"]))',
                    'visible' => "CHelper::isAccess('MANAGER','delete')",
                ),
            ),
        ),
	),
)); ?>
