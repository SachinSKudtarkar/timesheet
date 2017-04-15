<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */

$this->breadcrumbs = array(
    'Link Engg Change Requests' => array('admin'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Manage Change Requests', 'url' => array('admin')),
    array('label' => 'Create New Change Request', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#link-engg-cr-master-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Link Engineering Change Requests</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'link-engg-cr-master-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered',
    'filter' => $model,
    'columns' => array(
        'id',
        'link_no',
        'status',
        array('type' => 'raw', 'name' => 'approval_1_cat_1_status', 'value' => array($model, 'getApproval1C1VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:#CC3300')),
        array('type' => 'raw', 'name' => 'approval_2_cat_1_status', 'value' => array($model, 'getApproval2C1VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:#CC3300')),
        array('type' => 'raw', 'name' => 'approval_3_cat_1_status', 'value' => array($model, 'getApproval3C1VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:#CC3300')),
        array('type' => 'raw', 'name' => 'approval_1_cat_2_status', 'value' => array($model, 'getApproval1C2VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:orange')),
        array('type' => 'raw', 'name' => 'approval_2_cat_2_status', 'value' => array($model, 'getApproval2C2VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:orange')),
        array('type' => 'raw', 'name' => 'approval_3_cat_2_status', 'value' => array($model, 'getApproval3C2VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:orange')),
        array('type' => 'raw', 'name' => 'approval_1_cat_3_status', 'value' => array($model, 'getApproval1C3VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:blue')),
        array('type' => 'raw', 'name' => 'approval_2_cat_3_status', 'value' => array($model, 'getApproval2C3VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:blue')),
        array('type' => 'raw', 'name' => 'approval_3_cat_3_status', 'value' => array($model, 'getApproval3C3VerifyLinkUrl'), 'htmlOptions' => array('style' => 'color:blue')),
        'created_at',
        'modified_at',
        array(
            'name' => 'created_by',
            'type' => 'raw',
            'value' => array(new CommonUtility, 'getCreatedByName'),
            'filter' => CHtml::dropDownList('NddAg1x2[created_by]', $model->created_by, CHtml::listData(CommonUtility::getCreaterDropdown($model->tableName()), 'emp_id', 'first_name'), array('empty' => 'All')),
        ),
        array(
            'name' => 'modified_by',
            'type' => 'raw',
            'value' => array(new CommonUtility, 'getModifiedByName'),
            'filter' => CHtml::dropDownList('NddAg1x2[modified_by]', $model->modified_by, CHtml::listData(CommonUtility::getModifierDropdown($model->tableName()), 'emp_id', 'first_name'), array('empty' => 'All')),
        ),
        array('type' => 'raw', 'header' => 'Actions', 'value' => array($model, 'getManageCRLinkUrl')),
        array('type' => 'raw', 'header' => 'Feedbacks', 'value' => array($model, 'getFeedbackLink')),
        array('type' => 'raw', 'header' => 'View Modifications', 'value' => array($model, 'viewLERChanges')),
        array(
            'type' => 'raw',
            'header' => 'Copy',
            'value' => function($model) {
                if (in_array($model->status, array("complete", "rejected")) && CHelper::isAccess("MANAGE_LINK_ENGG_CR")) {
                    return $model->copyLer($model);
                } else {
                    return "";
                }
            },
                ),
            ),
));

        $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'feedback_modal', 'htmlOptions' => ['style' => 'width: 80%; margin-left: -40%;']));
?>
<div class = "modal-header">
    <a class = "close" data-dismiss = "modal">&times;
    </a>
    <h4 class="request_id_span"></h4>
</div>
<div class = "modal-body-feedbacks" style="min-height:100px; height:300px; ">

</div>
<?php
$this->endWidget();

Yii::app()->clientScript->registerScript('feedback_script', "
    $('.feedback_link').live('click',function(){
    $('.request_id_span').html('Feedback - Request ID: '+$(this).attr('data-key'));
     $.ajax({
                url: '" . CHelper::createUrl("LinkEnggCrMaster/getFeedbackOfRequest") . "',
                type: 'POST',
                dataType: 'json',
                data: {request_id : $(this).attr('data-key')},
                success: function(data){
                    $('.modal-body-feedbacks').html(data);
                    $('#feedback_modal').modal('show');

                },
            });
    }); ", CClientScript::POS_HEAD);
?>
