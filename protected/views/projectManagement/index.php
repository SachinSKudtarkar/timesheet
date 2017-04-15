<?php
/* @var $this ProjectManagementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Project Managements',
);

$this->menu=array(
	array('label'=>'Create Program', 'url'=>array('create')),
	array('label'=>'Manage Program', 'url'=>array('admin')),
);
?>

<h1>Project Managements</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
