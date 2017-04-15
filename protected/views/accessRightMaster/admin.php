<?php
/* @var $this AccessRightMasterController */
/* @var $model AccessRightMaster */

$this->breadcrumbs=array(
	'Access Right Masters'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AccessRightMaster', 'url'=>array('admin')),
	array('label'=>'Create AccessRightMaster', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#access-right-master-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Access Right Masters</h1>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
	//'model'=>$model,
//)); ?>
<!--</div> search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'access-right-master-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'type',
		'heading',
		//'page_url',
		'heading_order',
		//'menu_icon',
		
		'is_disabled',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
