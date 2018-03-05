<?php
/* @var $this NddCoreWanController */
/* @var $model NddCoreWan */

/*$this->breadcrumbs=array(
	'CORE-WAN'=>array('create'),
	'Upload',
);*/

/*$this->menu=array(
	array('label'=>'Upload CORE-WAN', 'url'=>array('create')),
	array('label'=>'Manage CORE-WAN', 'url'=>array('admin')),
);*/
?>

<h1>Upload Input File</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<div style="text-align:center; "><?php //echo CHtml::button('Runutility', array('onclick' => 'js:document.location.href="RunUtility"')); ?></div>

