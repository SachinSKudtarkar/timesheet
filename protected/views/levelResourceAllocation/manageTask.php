<<<<<<< HEAD
﻿<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model TaskAllocation */

$this->breadcrumbs=array(
	'Resource Allocation Project Works'=>array('index'),
	'Manage',
);

$this->menu=array(	 
	array('label'=>'Allocate Resources For Task', 'url'=>array('create_task')),
                
); ?>
<h1>Manage Resource Allocation Project Works</h1>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'resource-allocation-project-work-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		 
		'pid', 
		'taskId',
        'project_name',
		'sub_project_name',
		'estimated_start_date',
	'estimated_end_date',
	'total_hr_estimation_hour',
	'requester',
		//'day',
		'date',		
                array(
                    'name'=> 'allocated_resource',
                    'type'=> 'raw',
                    'value'=>array($this,'getReource'),
                ),
		/*'comment',
		'created_by',*/
		/* array(
			'class'=>'CButtonColumn',
		), */
	),
)); ?>
=======
﻿<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model TaskAllocation */

$this->breadcrumbs=array(
	'Resource Allocation Project Works'=>array('index'),
	'Manage',
);

$this->menu=array(	 
	array('label'=>'Allocate Resources For Task', 'url'=>array('create_task')),
                
); ?>
<h1>Manage Resource Allocation Project Works</h1>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'resource-allocation-project-work-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		 
		'pid', 
		'taskId',
        'project_name',
		'sub_project_name',
		'estimated_start_date',
	'estimated_end_date',
	'total_hr_estimation_hour',
	'requester',
		//'day',
		'date',		
                array(
                    'name'=> 'allocated_resource',
                    'type'=> 'raw',
                    'value'=>array($this,'getReource'),
                ),
		/*'comment',
		'created_by',*/
		/* array(
			'class'=>'CButtonColumn',
		), */
	),
)); ?>
>>>>>>> ca220980247a9c3a8f585c829fff41deab1c5ac4
