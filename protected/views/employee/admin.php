<?php
/* @var $this EmployeeController */
/* @var $model Employee */

$this->layout = 'column1';
$this->customeModel = $model;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#employee-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'htmlOptions' => array('class' => 'btn-group-vertical mr-tp-7 pull-right'),
    'buttons' => array(
        array(
            'label' => 'Add Employee',
            'url' => array('employee/create'),
            'visible' => CHelper::isAccess("EMPLOYEE", "employee.create"),
            'htmlOptions' => array('class' => 'form-group'),
        ),
    ),
));
?>

<h1>Manage Employees</h1>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'employee-grid',
    'filter' => $model,
    'type' => 'striped bordered',
    'template' => '{pagerCustom}{items}{pager}{summary}',
    'summaryText' => 'Showing {start} to {end} of {count} | Show ' .
    CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']), CHelper::pageLimitDropDown(), array('onchange' => "$.fn.yiiGridView.update('employee-grid',{ data:{pageSize: $(this).val() }})", 'class' => 'span2 page-dropdown',)
    ) . ' entries per page.',
    'emptyText' => 'No Records Found',
    'summaryCssClass' => 'pull-left page-dropdown',
    'pagerCssClass' => 'pagination pull-right',
    'dataProvider' => $model->search(),
    'enablePagination' => true,
    'columns' => array(
        'email',
        'first_name',
        'last_name',
        array(
            'name' => 'access_type',
            'value' => '$data->access_type',
            'header' => 'Access Type',
            'filter' =>
            CHtml::activeDropDownList(
                    $model, 'access_type', CHtml::listData(Roles::model()->findAll(
                                    array('order' => 'name ASC', 'condition' => 'is_deleted=0')), 'id', 'name'
                    ) + array("1" => "Admin"), array('prompt' => 'All')
            )
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
            'buttons' => array
                ('update' => array
                    (
                    'label' => 'Update',
                    'url' => 'Yii::app()->createUrl("/employee/update", array("id"=>$data->emp_id))',
                    'visible' => "CHelper::isAccess('EMPLOYEE','update')", 
                ),
                'delete' => array
                    (
                    'label' => 'Delete',
                    'url' => 'Yii::app()->createUrl("/employee/delete", array("id"=>$data->emp_id))',
                    'visible' => "CHelper::isAccess('EMPLOYEE','delete')",
                ),
            ),
        ),
    ),
));
?>
