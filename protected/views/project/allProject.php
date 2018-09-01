<?php
/* @var $this ProjectController */
/* @var $model Project */
$this->breadcrumbs=array(
	'Projects'=>array('index'),
	'Manage',
);
$this->menu=array(
	array('label'=>'Manage Project', 'url'=>array('subproject/admin')),
	array('label'=>'Create Project', 'url'=>array('subproject/create')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#project-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Individual Employee Details </h1>
</div><!-- search-form -->
<?php  $x = $this->widget('ext.multirowheader.CGridViewPlus', array(
        'id' => 'lawfirm-grid',
        //'filter' => $model,
        'htmlOptions' => array('class' => 'grid-view clearfix'),
        'type' => 'striped bordered',
        'template' => '{pagerCustom}{items}{pager}{summary}',
        'dataProvider' => new CArrayDataProvider($data, array()),
        'filter' => $model,
        'mergeColumns' => array('Name','Program','Project'),
        'extraRowPos' => 'above',
        'emptyText' => 'No Records Found',
        'enablePagination' => true,
        'columns' => array(
            'id',
            'Name',
            'Program',
            'Project',
            'Task',
            'Type',
             array(
                 'header'=>'Estimated Hours',
                    'name' => 'Estimated_Hours',
                    'type' => 'raw',
                     'filter'=>false,
                ),
             array(
                 'header'=>'Consumed Hourse',
                    'name' => 'Consumed_Hours',
                    'type' => 'raw',
                     'filter'=>false,
                ),
            array(
                 'header'=>'Remaining_Hours',
                    'name' => 'Remaining_Hours',
                    'type' => 'raw',
                     'filter'=>false,
                ),
//            array(
//                'class' => 'bootstrap.widgets.TbButtonColumn',
//                'template' => '{update}{delete}',
//                'buttons' => array
//                    ('update' => array
//                        (
//                        'label' => 'Update',
//                        'url' => 'Yii::app()->createUrl("/Project/update", array("id"=>$data[\'id\']))',
//                        'visible' => "CHelper::isAccess('MANAGER','update')",
//                    ),
//                    'delete' => array
//                        (
//                        'label' => 'Delete',
//                        'url' => 'Yii::app()->createUrl("/Project/delete", array("id"=>$data[\'id\']))',
//                        'visible' => "CHelper::isAccess('MANAGER','delete')",
//                    ),
//                ),
//            ),
	),
));
echo $this->renderExportGridButton($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));
?>