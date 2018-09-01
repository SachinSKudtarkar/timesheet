<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */

$this->breadcrumbs = array(
    'Create Pid Approvals' => array('create'),
    'Manage',
);

// $this->menu=array(
// 	array('label'=>'List PidApproval', 'url'=>array('index')),
// 	array('label'=>'Create PidApproval', 'url'=>array('create')),
// );

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
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<?php //CHelper::debug($data); ?>
<?php
$this->widget('ext.multirowheader.CGridViewPlus', array(
    'id' => 'lawfirm-grid',
    'filter' => $model,
    'htmlOptions' => array('class' => 'grid-view clearfix'),
    'type' => 'striped bordered',
    'template' => '{pagerCustom}{items}{pager}{summary}',
    'dataProvider' => new CArrayDataProvider($data, array()),
    'filter' => $model,
    'mergeColumns' => array('project_id', 'sub_project_id', 'inception_date', 'total_est_hrs', 'comments', 'sr', 'approved'),
    'extraRowPos' => 'above',
    'emptyText' => 'No Records Found',
    'enablePagination' => true,
    'columns' => array(
        array(
            'header' => 'Sr.no',
            'name' => 'sr',
        ),
        'jira_id',
        array(
            'header' => 'Program',
            'name' => 'project_id',
            'type' => 'raw',
            'value' => array($model, 'getProjectDescription')
        ),
        //'sub_project_id',
        array(
            'header' => 'Project',
            'name' => 'sub_project_id',
            'type' => 'raw',
            'value' => array($model, 'getSubProjectDescription')
        ),
        array(
            'header' => 'Task Id',
            'name' => 'project_task_id',
        ),
        array(
            'header' => 'Task Title',
            'name' => 'task_title',
        ),
        array(
            'header' => 'Task Description',
            'name' => 'task_description',
        ),
        array(
            'header' => 'Type',
            'name' => 'task_id',
            'type' => 'raw',
            'value' => array($model, 'getTaskDescription')
        ),
        array(
            'header' => 'Sub Task Id',
            'name' => 'sub_task_id',
            'type' => 'raw',
        ),
        array(
            'header' => 'Sub Task Name',
            'name' => 'sub_task_name',
            'type' => 'raw',
        ),
        array(
            'header' => 'Assigned To',
            'name' => 'emp_id',
            'type' => 'raw',
            'value' => array($model, 'getEmpName'),
            'filter' => false,
        ),
        array(
            'header' => 'Estimated Hours',
            'name' => 'est_hrs',
            'type' => 'raw',
            'filter' => false,
        ),
//        array(
//            'header' => 'Toatal Estimated Hours',
//            'name' => 'total_est_hrs',
//            'type' => 'raw',
//            'filter' => false,
//        ),
        array(
            'header' => 'Inception Date',
            'name' => 'inception_date',
            'type' => 'raw',
            'filter' => false,
        ),
        // 'est_hrs',
        // 'total_est_hrs',
        // 'inception_date',
        array(
            'header' => 'Comments',
            'name' => 'comments',
            'type' => 'raw',
            'filter' => false,
        ),
        //	'comments',
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
        array(
            'header' => 'Approval Status',
            'type' => 'raw',
            'name' => 'approved',
            'filter' => false,
            // 'visible' => 'Yii::app()->session[\'login\'][\'access_type\'] == 2',
            'value' => array($model, 'pidApprovalbtn'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
            'buttons' => array
                ('update' => array
                    (
                    'label' => 'Update',
                    'url' => 'Yii::app()->createUrl("/pidapproval/update", array("id"=>$data[\'sr\']))',
                    'visible' => "CHelper::isAccess('MANAGER','update')",
                ),
                'delete' => array
                    (
                    'label' => 'Delete',
                    'url' => 'Yii::app()->createUrl("/pidapproval/delete", array("id"=>$data[\'sr\']))',
                    'visible' => "CHelper::isAccess('MANAGER','delete')",
                ),
            ),
        ),
    ),
));
?>

<?php
Yii::app()->clientScript->registerScript('some-name', "
       $('.approval').live('click',function(){
//       alert($(this).data('value'));
       $('#sendapproval').val($(this).data('value'));
       $('#sendreject').val($(this).data('value'));
        result_id = $(this).data('value');
    });
");
?>

<?php $csrfToken = Yii::app()->request->csrfToken; ?>
<?php
/*
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
 */
?>