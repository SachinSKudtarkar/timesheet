<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs=array(
	// 'Resource Allocation Program Works'=>array('index'),
	'Manage',
);

$this->menu=array(	 
	array('label'=>'Allocate Resources For Program', 'url'=>array('create')),
	      
); ?>
<h1>Manage Resource Allocation Program Works</h1>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'resource-allocation-project-work-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		 
		//'pid',                
                'project_name',
		//'day',
		'date',		
                array(
                    'name'=> 'allocated_resource',
                    'type'=> 'raw',
                    'value'=>array($this,'getReource'),
                ),
		/*'comment',
		'created_by',*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
