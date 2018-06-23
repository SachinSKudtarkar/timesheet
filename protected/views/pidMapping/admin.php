<?php
/* @var $this PidMappingController */
/* @var $model PidMapping */

$this->breadcrumbs=array(
	'Pid Mappings'=>array('index'),
	'Manage',
);

// $this->menu=array(
	// array('label'=>'List PidMapping', 'url'=>array('index')),
	// array('label'=>'Create PidMapping', 'url'=>array('create')),
// );

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pid-mapping-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Pid Mappings</h1>



<?php //CHelper::debug($model->search()); 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'pid-mapping-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'pid',
		'year_month',
		//'project_id',
		array(
			'header'=>'Program',
            'name' => 'project_id',
            'type' => 'raw',
            'value' => array($model, 'getProjectDescription')
        ),
		//'sub_project_id',
		array(
			'header'=>'Project',
            'name' => 'sub_project_id',
            'type' => 'raw',
            'value' => array($model, 'getSubProjectDescription')
        ),
		array(
			'header'=>'Type',
            'name' => 'task_id',
            'type' => 'raw',
            'value' => array($model, 'getTaskDescription')
        ),
		array(
			'header'=>'Task',
            'name' => 'sub_task_id',
            'type' => 'raw',
            'value' => array($model, 'getSubTaskDescription')
        ),
            array(
            'header'=>'Employee',
            'name' => 'emp_id',
            'type' => 'raw',
            'value' => array($model, 'getemp_name'),
        ),
		array(
            'type' => 'raw',
            'name' => 'Status',
             'value' => '($data->status == 1 ? "IN Queue" : ($data->status == 2 ? "WIP" : ($data->status == 3 ? "Completed":"Completed")))',
            'filter' => CHtml::dropDownList('PidMapping[status]', $model->status, array(''=>'Select',1=>"IN Queue",2=>"WIP",3=>"Completed"), array('empty' => 'All')),
        ),
		
		/*
		'created_at',
		'created_by',
		'modified_at',
		'modified_by',
		
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
