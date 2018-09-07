<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs = array(
    'Tasks' => array('create'),
    'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pid-approval-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tasks</h1>

<?php
// CHelper::debug($model);
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sub-project-grid',
    'dataProvider' => $model->searchList(),
    'filter' => $model,
    'columns' => array(
        //'taskId',
        //'pid',
		'project_id',
        array(
            'header'=>'Program',
            'name' => 'project_id',
            'type' => 'raw',
            // 'filter' => false,
            //'value' => array($model, 'getProgram')
        ),
        // 'program',
        'sub_project_name',
        //'sub_project_description',
        //'requester',
       // 'estimated_end_date',
        //  'total_hr_estimation_hour',
          array(
            'header'=>'Created By',
            'name' => 'created_by',
            'type' => 'raw',
           //'filter' => false,
            //'value' => array($model, 'getCreatedBy')
              ),
          'created_at',
          'is_deleted',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
