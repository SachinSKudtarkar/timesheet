<?php
/* @var $this SyslogserveripschangeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Syslogserveripschanges',
);

$this->menu=array(
	array('label'=>'Create Syslogserveripschange', 'url'=>array('create')),
	array('label'=>'Manage Syslogserveripschange', 'url'=>array('admin')),
);
?>

<h1>Syslogserveripschanges</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
