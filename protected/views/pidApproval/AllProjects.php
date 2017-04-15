<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs=array(
	'Pid Approvals'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PidApproval', 'url'=>array('index')),
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

<h1>Manage PID Approvals</h1>


<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php  //CHelper::debug($data); ?>
<?php $this->widget('ext.multirowheader.CGridViewPlus', array(
        'id' => 'lawfirm-grid',
       // 'filter' => $model,
        'htmlOptions' => array('class' => 'grid-view clearfix'),
        'type' => 'striped bordered',
        'template' => '{pagerCustom}{items}{pager}{summary}',
        'dataProvider' => new CArrayDataProvider($data, array()),
        //'filter' => $model,
        'mergeColumns' => array('project_id', 'sub_project_id','inception_date','total_est_hrs','comments','sr','approved'),
        'extraRowPos' => 'above',
        'emptyText' => 'No Records Found',
        'enablePagination' => true,
        'columns' => array(
                
                array(
                    'header' => 'Sr.no',
                    'name'=>'sr',
                 ),
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
		'inception_date',
		'total_est_hrs',
//            array(
//                    'header'=>'Emp',
//                    'name'=>'emp_id',
//                    ),
            array(
			'header'=>'Employee',
                    'name' => 'emp_id',
                    'type' => 'raw',
                    'value' => array($model, 'getEmpName')
                ),
            array(
			'header'=>'Type',
            'name' => 'task_id',
            'type' => 'raw',
            'value' => array($model, 'getTaskDescription')
        ),
            'sub_task_name',
            'est_hrs',
		'comments',
		/*
		'status',
		'created_by',
		'created_at',
		'approved',
		'is_deleted',

                 */
//                array(
//                    'header' => 'Approved',
//                    'type' => 'raw',
//                    'value' => '$model->approved == 1 ? "Open": "Close"',
//                ),                 
//		array(
//			'class'=>'CButtonColumn',
//		),
           // array(
           // 'class' => 'CButtonColumn',
           // 'template' => '{s4a}',
           // 'buttons' => array(
              // 's4a' => array(
                  
                 // 'visible' => 'Yii::app()->session[\'login\'][\'access_type\'] == 1',
                // 'label' => 'Send For Approval', 
                  // 'url' => 'CHtml::normalizeUrl(array("dashboard/mail/id/".rawurlencode($data->feedback_email)."/f_id/".$data->feedback_id))', //Your URL According to your wish
                     // 'imageUrl' => Yii::app()->baseUrl . '/images/reply_mail_icon.png', // image URL of the button. If not set or false, a text link is used, The image must be 16X16 pixels
                  // ),
              // ),
       // ),
            // array(
            // 'header' => 'Approval Status',
            // 'type' => 'raw',
            // 'name' => 'approved',
         //  'visible' => 'Yii::app()->session[\'login\'][\'access_type\'] == 2',
            // 'value' => array($model, 'pidApprovalbtn'),
            // ),
	),
)); ?>

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
               
<?php $csrfToken = Yii::app()->request->csrfToken; ?>
<?php
    # Connectivity modal code starts
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'approval', 'htmlOptions' => array('style' => 'width:600px;')));
?>
<?php
    $this->renderPartial('pidapprovalform', array('model' => $model), '', true);
?>
<?php
$this->endWidget();
?>

<?php
    # Connectivity modal code starts
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'rejected', 'htmlOptions' => array('style' => 'width:600px;')));
?>
<?php
    $this->renderPartial('pidrejectform', array('model' => $model), '', true);
?>
<?php
$this->endWidget();
?>