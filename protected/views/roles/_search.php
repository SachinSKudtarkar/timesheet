<div class="search-form" style="display:inline">
	<div class="sidebar-left-panal">
	<?php 
	// Include the Javascript File for left Panel
	//$url	=   CHelper::baseUrl().'/themes/6-in-1/js/left_panel.js';
	//CHelper::registerScriptFile($url, $position=CClientScript::POS_HEAD);

	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
		'htmlOptions'=>array(
			'class'=>'form-horizontal',
		),
	));?>
	<div class="form-group clearfix">
            <div class="span12">
                    <?php echo $form->textField($model,'first_name',array('class'=>'form-control span12','maxlength'=>50,'placeholder'=>'Name')); ?>
            </div>
	</div>
	<div class="clearfix" id="collapse1" style="display:none">
		<div class="form-group clearfix">
			<div class="span12 ">
                            <?php echo $form->textField($model,'email',array('class'=>'form-control span12','maxlength'=>150,'placeholder'=>'Email Address')); ?>
			</div>
		</div>
		
	</div>
	<div class="form-group clearfix">
		<div class="span12">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'label'=>'Search',
                                'htmlOptions'=>array('class'=>'span6'),
			)); ?>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'reset',
				'label'=>'Clear',
				'htmlOptions'=>array(
					'class'=>'reset-form span6',
				),
			)); ?>
		</div>
	</div>

	
</div>
</div>
<?php $this->endWidget(); 
Yii::app()->clientScript->registerScript('search', "
$('.nav-toggle-1').click(function(){
	//get collapse content selector	
	var collapse_content_selector = $(this).attr('href');		
	//make the collapse content to be shown or hide
	var toggle_switch = $(this);
	$(collapse_content_selector).toggle(function(){
		if($(this).css('display')=='none'){
		//change the button label to be 'Show'
			toggle_switch.html('Advanced Search');
		}else{
		//change the button label to be 'Hide'
			toggle_switch.html('Basic Search');
		}
	});
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('employee-grid', {
		data: $(this).serialize()
	});
	return false;
});
$('.reset-form').click(function(){
	$('.search-form form input, .search-form form select').each(function(i, o)
	{
		if (($(o).attr('type') != 'submit') && ($(o).attr('type') != 'reset')) $(o).val('');
	});
	$.fn.yiiGridView.update('employee-grid', {data: $('.search-form form').serialize()});
	return false;
});
");
?>

<div class="form-group">
	<div class="col-sm-10"> 
		<?php echo CHtml::link('Advanced Search','#collapse1',array('class'=>'link nav-toggle-1 search-button')); ?>
	</div>
</div>
<hr />
<div class="form-group clearfix">       
<?php
$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=> '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
        'buttons'=>array(
               array(
                   'label'=>'Add Employee', 
                   'url'=>array('employee/create'),
                   'visible'=>CHelper::isAccess("EMPLOYEE","employee.Create"),
                   'htmlOptions'=>array('class'=>'form-group'), 
                ),
            
        ),
    ));
?>
</div>