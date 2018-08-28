<?php
/* @var $this LevelResourceAllocationController */
/* @var $model LevelResourceAllocation */

$this->breadcrumbs=array(
	'Resource Allocation Program Works'=>array('index'),
	'Manage',
);

$this->menu=array(	 
	array('label'=>'Allocate Resources For Program', 'url'=>array('create')),
	      
); ?>
<h1>Manage Resource Allocation Program Works</h1>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'level-resource-allocation-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		 
		'id',
         array(
            'header'=>'Employee Name',
            'name' => 'emp_id',
            'type' => 'raw',
            // 'filter' => false,
            'value' => array($this, 'getEmployee')
        ),
		 array(
            'header'=>'Level Name',
            'name' => 'level_id',
            'type' => 'raw',
            // 'filter' => false,
            'value' => array($this, 'getLevel')
        ),
		'created_by',
        'created_at',
		'modified_by',
        'updated_at',
		/* array(
			'class'=>'CButtonColumn',
		), */
	),
)); ?>
