<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Pid Approvals'=>array('index'),
	'Manage',
);

$this->menu=array(
	// array('label'=>'List PidApproval', 'url'=>array('index')),
	array('label'=>'Create PidApproval', 'url'=>array('create')),
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

<h1>Project status</h1>


<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php  //CHelper::debug($data); ?>
<?php $x = $this->widget('ext.multirowheader.CGridViewPlus', array(
        'id' => 'lawfirm-grid',
       'filter' => $model,
        'htmlOptions' => array('class' => 'grid-view clearfix'),
        'type' => 'striped bordered',
        'template' => '{pagerCustom}{items}{pager}{summary}',
        'dataProvider' => new CArrayDataProvider($data, array()),
        'filter' => $model,
        'mergeColumns' => array('project_id', 'sub_project_id','inception_date','total_est_hrs','comments','sr'),
        'extraRowPos' => 'above',
        'emptyText' => 'No Records Found',
        'enablePagination' => true,
        'columns' => array(
                
                array(
                    'header' => 'Sr.no',
                    'name'=>'sr',
                 ),
                'jira_id',
//                array(
//                    'header'=>'Program',
//                    'name'=>'project_id',
//                    ),
//                array(
//                    'header'=>'Project',
//                    'name'=>'sub_project_id',
//                    ),
		array(
			'header'=>'Program',
                        'name' => 'project_id',
                        'type' => 'raw',
                        'value' => array($model, 'getProjectDescription')
                    ),
		//'sub_project_id',
		array(
			'header'=>'Project',
                        'name' => 'sub_project_id',
                        'type' => 'raw',
                        'value' => array($model, 'getSubProjectDescription')
                    ),
        array(
            'header'=>'Type',
            'name' => 'task_id',
            'type' => 'raw',
            'value' => array($model, 'getTaskDescription')
        ),
           // 'sub_task_name',
            array(
			'header'=>'Task',
                        'name' => 'sub_task_name',
                        'type' => 'raw',
                        
                    ),
            array(
            'header'=>'Employee',
                    'name' => 'emp_id',
                    'type' => 'raw',
                    'value' => array($model, 'getEmpName'),
                    
                ),
              array(
			'header'=>'Start Date',
                        'name' => 'inception_date',
                        'type' => 'raw',
                        
                    ),
             array(
			'header'=>'Assign Hours',
                        'name' => 'total_est_hrs',
                        'type' => 'raw',
                        
                    ),
            
//		'inception_date',
//		'total_est_hrs',
//            array(
//                    'header'=>'Emp',
//                    'name'=>'emp_id',
//                    ),
            
            
            'est_hrs',
		'comments',
     array(
                'header' => 'Approved',
                'name' => 'approved',
                'filter' => CHtml::dropDownList('PidApproval[approved]', $model->approved, array(''=>'Select',1=>"In Queue",2=>"Approved",3=>"Rejected")),
               
                'type' => 'raw',
        ),
      //  'approved',
		/*
		'status',
		'created_by',
		'created_at',
		'approved',
		'is_deleted',

                 */

	),
));

echo $this->renderExportGridButton($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));?>

<?php
Yii::app()->clientScript->registerScript('some-name', "
       $('.approval').on('click',function(){
//       alert($(this).data('value'));
       $('#sendapproval').val($(this).data('value'));
       $('#sendreject').val($(this).data('value'));
        result_id = $(this).data('value');
    }); 
");
?>
               
