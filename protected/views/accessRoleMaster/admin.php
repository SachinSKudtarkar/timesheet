<?php
/* @var $this AccessRoleMasterController */
/* @var $model AccessRoleMaster */

$this->breadcrumbs=array(
	'Access Role Masters'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label' => 'Set Manager', 'url' => array('SetManager')),
    array('label' => 'Assign Resource Under Manager', 'url' => array('SetRoles')),
    array('label' => 'View Resource Allocation List', 'url' => array('Admin')),
);


?>

<h1>Manage Access Role Masters</h1>



<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'access-role-master-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		// 'parent_id',
		array(
			'name' =>'parent_id',
			'type' => 'raw',
			'value'=> 'Employee::model()->getUserFullNameById($data->parent_id)',
			// 'filter' => 

			),
		array(
			'name' =>'emp_id',
			'type' => 'raw',
			'value'=> 'Employee::model()->getUserFullNameById($data->emp_id)'
			),
		// array(
  //                   'name' => 'parent_id',
  //                   'type' => 'raw',
  //                   'value' => array(new CommonUtility, 'getCreatedByName'),
  //                   'filter' => CHtml::dropDownList('AccessRoleMaster[parent_id]', $model->parent_id, CHtml::listData(CommonUtility::getCreaterDropdown($model->tableName()), 'emp_id', 'first_name'), array('empty' => 'All')),
  //               ),
		// array(
  //                   'name' => 'emp_id',
  //                   'type' => 'raw',
  //                   'value' => array(new CommonUtility, 'getCreatedByName'),
  //                   'filter' => CHtml::dropDownList('AccessRoleMaster[emp_id]', $model->parent_id, CHtml::listData(CommonUtility::getCreaterDropdown($model->tableName()), 'emp_id', 'first_name'), array('empty' => 'All')),
  //               ),
		// 'emp_id',

		 array(
                'header' => 'access_type',
                'filter' => CHtml::dropDownList('AccessRoleMaster[access_type]', $model->access_type, array(''=>'Select',1=>"Manager",2=>"Resource")),
                //'value' => array($model, "statusReturn"),
                'value' => '($data->access_type == 1 ? "Manager" : ($data->access_type == 2 ? "Resource" :"" ))',
                'type' => 'raw',
        ),
		'access_type',
		'is_active',
		// 'created_by',
		/*
		'created_date',
		'updated_date',
		'updated_by',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),
	),
)); ?>
