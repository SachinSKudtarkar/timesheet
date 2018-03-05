<?php
/* @var $this EmployeeLoginTrackerController */
/* @var $model EmployeeLoginTracker */

//$this->breadcrumbs=array(
//	'Employee Login Trackers'=>array('index'),
//	'Manage',
//);

//$this->menu=array(
//	array('label'=>'List EmployeeLoginTracker', 'url'=>array('index')),
//	array('label'=>'Create EmployeeLoginTracker', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#employee-login-tracker-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Employee Login / Logout</h1>

<!--<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php // $this->renderPartial('_search',array(	'model'=>$model,)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'employee-login-tracker-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'emp_id',
                array(
                    'name' => 'emp_id',
                    'type' => 'raw',
                    'value' => array($model, 'getEmployeeName'),
                    'filter' => CHtml::dropDownList('EmployeeLoginTracker[emp_id]', $model->emp_id, CHtml::listData(EmployeeLoginTracker::getEmployeeDropdown(), 'emp_id', 'first_name'), array('empty' => 'All')),
                ),
		'login_time',
		'logout_time',
                array(
                    'name' => 'time_spend',
                    'type' => 'raw',
                    'value' => array($model, 'getEmployeeTimeSpend'),
                    'filter' => false,
                ),
//		array(
//			'class'=>'CButtonColumn',
//		),
	),
)); ?>
