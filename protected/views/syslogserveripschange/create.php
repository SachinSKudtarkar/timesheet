<?php
/* @var $this SyslogserveripschangeController */
/* @var $model Syslogserveripschange */

$this->breadcrumbs=array(
	'Syslogserveripschanges'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Syslogserveripschange', 'url'=>array('index')),
	array('label'=>'Manage Syslogserveripschange', 'url'=>array('admin')),
);
?>

<h1>Create Syslogserveripschange</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>