<?php
/* @var $this NddLinkEnggCatEmployeeMappingController */
/* @var $model NddLinkEnggCatEmployeeMapping */

$this->breadcrumbs=array(
	'Ndd Link Engg Cat Employee Mappings'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NddLinkEnggCatEmployeeMapping', 'url'=>array('index')),
	array('label'=>'Create NddLinkEnggCatEmployeeMapping', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ndd-link-engg-cat-employee-mapping-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ndd Link Engg Cat Employee Mappings</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ndd-link-engg-cat-employee-mapping-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'emp_id',
		'cat_key_id',
		'is_active',
		'mapping_date_time',
		'mapped_by',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
