<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */

$this->breadcrumbs=array(
	'Link Engg CR'=> array('admin'),	
	'Update',
);

$this->menu=array(
	array('label'=>'Manage', 'url'=>array('admin')),	
);
?>

<h1>Update change request #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>