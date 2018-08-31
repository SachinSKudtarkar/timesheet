<?php
/* @var $this LevelResourceAllocationController */
/* @var $model LevelResourceAllocation */

$this->breadcrumbs=array(
	'Allocate Resources to Level'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Allocate Resources to Level', 'url'=>array('create')),

); ?>
<h1>Allocate Resource to Level</h1>
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
          array(
            'header'=>'Created By',
            'name' => 'created_by',
            'type' => 'raw',
           //'filter' => false,
            'value' => array($model, 'getCreatedBy')
              ),
        'created_at',
          array(
            'header'=>'Modified By',
            'name' => 'modified_by',
            'type' => 'raw',
           //'filter' => false,
            'value' => array($model, 'getModifiedBy')
              ),
        'updated_at',
		/* array(
			'class'=>'CButtonColumn',
		), */
	),
)); ?>
