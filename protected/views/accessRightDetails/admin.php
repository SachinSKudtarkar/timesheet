<?php
/* @var $this AccessRightDetailsController */
/* @var $model AccessRightDetails */

$this->breadcrumbs=array(
	'Access Right Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AccessRightDetails', 'url'=>array('admin')),
	array('label'=>'Create AccessRightDetails', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#access-right-details-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Access Right Details</h1>


<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
//	'model'=>$model,
//)); ?>
<!--</div> search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'access-right-details-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'parent_id',
		'name',
		'value',
		//'page_url',
		'menu_order',
		//'menu_icon',
		'is_disabled',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
