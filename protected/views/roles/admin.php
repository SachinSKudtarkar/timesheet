<?php
/* @var $this EmployeeController */
/* @var $model Employee */

$this->layout='column1';
$this->customeModel=$model;

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


<h1>Manage Roles</h1>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'employee-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'type' => 'striped bordered',
        'template'=>'{pagerCustom}{items}{pager}{summary}',
        'summaryText'=>'Showing {start} to {end} of {count} | Show '.
                    CHtml::dropDownList('pageSize', 
                        Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']), 
                        CHelper::pageLimitDropDown(),
                        array('onchange'=>"$.fn.yiiGridView.update('employee-grid',{ data:{pageSize: $(this).val() }})",'class'=>'span2 page-dropdown',)
                        ).' entries per page.',
        'emptyText' => 'No Records Found',
        'summaryCssClass'=>'pull-left page-dropdown',
        'pagerCssClass'=>'pagination pull-right',
        'enablePagination' => true,
	
	      
	'columns'=>array(
		'name',
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}',
                        'buttons' =>array
                            ('update' => array
                                (
                                    'label' => 'Update',
                                    'url' => 'Yii::app()->createUrl("/roles/update", array("id"=>$data->id))',
                                    'visible'=>"CHelper::isAccess('ROLES','update')", 
                                ),
                             'delete' => array
                                 (
                                    'label' => 'Delete',
                                    'url' => 'Yii::app()->createUrl("/roles/delete", array("id"=>$data->id))',
                                    'visible'=>"CHelper::isAccess('ROLES','delete')",
                                 ),
                            ),
		),
	),
)); 

?>
