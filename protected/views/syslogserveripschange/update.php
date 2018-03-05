<?php
/* @var $this SyslogserveripschangeController */
/* @var $model Syslogserveripschange */

$this->breadcrumbs=array(
	'Syslogserveripschanges'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Syslogserveripschange', 'url'=>array('index')),
	array('label'=>'Create Syslogserveripschange', 'url'=>array('create')),
	array('label'=>'View Syslogserveripschange', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Syslogserveripschange', 'url'=>array('admin')),
);
?>

<h1>Update Syslogserveripschange <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>