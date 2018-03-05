<?php
/* @var $this TemplateMasterController */
/* @var $model TemplateMaster */

$this->breadcrumbs = array(
    'Template Masters' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List TemplateMaster', 'url' => array('index')),
    array('label' => 'Create TemplateMaster', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#template-master-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1 style="float: left; width: 88%">Manage Template Masters <h5 style="float: right; width: 12%"><a href="<?php echo  Yii::app()->createUrl('/templatemaster/create'); ?>">Create New BOM Template</a></h5></h1>


<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
//    $this->renderPartial('_search', array(
//        'model' => $model,
//    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'template-master-grid',
    'dataProvider' => $model->search(),
    //'filter'=>$model,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => array($this, 'getBomTemplateDetailView'),
        ),
        'file_id',
        array(
            'name' => 'file_name',
            'type' => 'raw',
            'value' => array($this, 'getBomFileDownloadLink'),
        ),
        array(
            'name' => 'created_by_name',
            'header' => 'Created By'
        ),
        'date_created',
    ),
));
?>
