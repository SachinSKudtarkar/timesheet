<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs=array(
	'Resource Allocation Project Works'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Allocate Resources For Project', 'url'=>array('create')),

); ?>
<h1>Manage Resource Allocation Project Works</h1>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'area-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
		'spid',
                'sub_project_name',
		'total_asign_hours',
		'consumed_hours',
//                array(
//                    'name'=> 'allocated_resource',
//                    'type'=> 'raw',
//                    'value'=>array($this,'getReource'),
//                ),
		/*'comment',
		'created_by',*/
		array(
			'class'=>'CButtonColumn',
		),
	),
    'filter'=>$filtersForm,
));
?>