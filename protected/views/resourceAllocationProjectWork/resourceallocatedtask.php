<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs=array(
	'Resource Arrangement With Project'=>array('index'),
	'Create',
);

$this->menu=array(
	 
	array('label'=>'Manage ResourceAllocation for Project Work', 'url'=>array('admin')),
        array('label'=>'Create Project', 'url'=>array('projectmanagement/create')),
        array('label'=>'View Resource Statistics', 'url'=>array('resourcemanagement')),
);
?>

<h1>Resource Arrangement With Project</h1>

<?php 
$this->renderPartial('_resourceallocatedtask', array(
                    'rawData' => $rawData,
					'model'=>$model
					
));

/* $this->renderPartial('_resourceallocatedtask', array(
'filtersForm' => $filtersForm,
    'dataProvider' => $dataProvider,
)); */
 ?>