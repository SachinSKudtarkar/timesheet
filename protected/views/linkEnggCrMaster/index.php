<?php
/* @var $this LinkEnggCrMasterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Link Engg Cr Masters',
);

$this->menu=array(
	array('label'=>'Create LinkEnggCrMaster', 'url'=>array('create')),
	array('label'=>'Manage LinkEnggCrMaster', 'url'=>array('admin')),
);
?>

<h1>Link Engg Cr Masters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
