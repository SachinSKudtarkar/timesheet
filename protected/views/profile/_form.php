<?php
/* @var $this EmployeeController */
/* @var $model Employee */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'employee-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
        'clientOptions'=>array('validateOnSubmit'=>true,'validateOnChange'=>true,),
	//'focus'=>array($model,'first_name'),
));?>
<div class="form">
    <div class="row-fluid">
        <div class="span12">
        <div class="span6">

<?php /*$form=$this->beginWidget('CActiveForm', array(
	'id'=>'employee-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));*/
 
$currentUserAccesstype = $model->access_type;
$roleArray = Roles::model()->findAll(array('order' => 'name ASC','condition'=>'is_deleted=0')); 
$rolles = CHtml::listData($roleArray, 'id', 'name');
 
?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>150,'disabled'=>true)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'middle_name'); ?>
		<?php echo $form->textField($model,'middle_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'middle_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>

        <div class="row">
                <?php echo $form->labelEx($model_employee_details,'phone',array('class'=>'')); ?>

                <?php
                    $this->widget('CMaskedTextField', array(
                    'model' => $model_employee_details,
                    'attribute' => 'phone',
                    'mask' => '999-999-9999',
                    'htmlOptions' => array('size' => 20),

                    ));
               ?>
               <?php echo $form->error($model_employee_details,'phone'); ?>
        </div>
        <div class="row">
		<?php echo $form->label($model_employee_details,'mobile',array('class'=>'')); ?>
		<?php 
		   $this->widget('CMaskedTextField', array(
			'model' => $model_employee_details,
			'attribute' => 'mobile',
			'mask' => '999-999-9999',
			'htmlOptions' => array('size' => 12)
			));
		   ?>
		<?php echo $form->error($model_employee_details,'mobile'); ?>
	</div>
            <div class="row">
        <?php echo $form->labelEx($model_employee_details,'company_name'); ?>
        <?php echo $form->textField($model_employee_details,'company_name'); ?>
        <?php echo $form->error($model_employee_details,'company_name'); ?>
    </div>
            
              <div class="row">
        <?php echo $form->labelEx($model_employee_details,'rjil_ext'); ?>
        <?php echo $form->textField($model_employee_details,'rjil_ext'); ?>
        <?php echo $form->error($model_employee_details,'rjil_ext'); ?>
    </div>
            
                <div class="row">
        <?php echo $form->labelEx($model_employee_details,'building_name'); ?>
        <?php echo $form->textField($model_employee_details,'building_name'); ?>
        <?php echo $form->error($model_employee_details,'building_name'); ?>
    </div>
              <div class="row">
        <?php echo $form->labelEx($model_employee_details,'floor'); ?>
        <?php echo $form->textField($model_employee_details,'floor'); ?>
        <?php echo $form->error($model_employee_details,'floor'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model_employee_details,'work_location'); ?>
        <?php echo $form->textField($model_employee_details,'work_location'); ?>
        <?php echo $form->error($model_employee_details,'work_location'); ?>
    </div>
        </div>
       <div class="span6">
        <div class="row">
		<?php echo $form->label($model_employee_details,'address_1',array('class'=>'')); ?>
		 <?php echo $form->textField($model_employee_details,'address_1',array('maxlength'=>100,'placeholder'=>'')); ?> 
		<?php echo $form->error($model_employee_details,'address_1'); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model_employee_details,'address_2',array('class'=>' ')); ?>
		<?php echo $form->textField($model_employee_details,'address_2',array('class'=>'','maxlength'=>150,'placeholder'=>'')); ?> 
		<?php echo $form->error($model_employee_details,'address_2'); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model_employee_details,'city',array('class'=>'')); ?>
		<?php echo $form->textField($model_employee_details,'city',array('class'=>'','maxlength'=>50,'placeholder'=>'')); ?> 
		<?php echo $form->error($model_employee_details,'city'); ?>
	</div>
<!--         <div class="row">
		<?php echo $form->labelEx($model,'access_type'); ?>
                <?php echo $form->dropDownList($model,'access_type',array("A"=>"Admin","E"=>"Employee"),array('disabled'=>true)); ?>
             <?php echo $form->error($model,'access_type'); ?>
         </div>   <div class="row">
		<?php echo $form->label($model,'access_type',array('class'=>'')); ?>
                 <?php echo $form->dropDownList($model,'access_type',
                       CHtml::listData(Roles::model()->findAll(
								array('order' => 'name ASC','condition'=>'is_deleted=0')),
								'access_type','name'
							),array('disabled'=>true)
                        ); ?>
		
         </div>-->
<div class="row">
             <?php
                echo $form->labelEx($model,'access_type');
                 echo CHtml::dropDownList('URoles', $currentUserAccesstype, $rolles, array('disabled'=>true));
             ?>
         </div>
	 
        <div class="row">
		<?php echo $form->label($model_employee_details,'state_id',array('class'=>'')); ?>
		<?php echo $form->dropDownList(
	  						$model_employee_details,
							'state_id',
							CHtml::listData(StateMaster::model()->findAll(
								array('order' => 'state_name_short ASC','condition'=>'is_disabled=0')),
								'state_id','state_name'
							),
							array('empty'=>'Select State','class'=>'')
							); ?>
	
	</div>
      <div class="row">
        <?php echo $form->labelEx($model_employee_details,'facebook_link'); ?>
        <?php echo $form->textField($model_employee_details,'facebook_link'); ?><span>Ex: https://www.facebook.com/yourname</span>
        <?php echo $form->error($model_employee_details,'facebook_link'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model_employee_details,'linkedin_link'); ?>
        <?php echo $form->textField($model_employee_details,'linkedin_link'); ?><span>Ex: https://www.linkedin.com/in/yourname</span>
        <?php echo $form->error($model_employee_details,'linkedin_link'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model_employee_details,'twitter_link'); ?>
        <?php echo $form->textField($model_employee_details,'twitter_link'); ?><span>Ex: https:// www.twitter.com/yourname</span>
        <?php echo $form->error($model_employee_details,'twitter_link'); ?>
    </div>
       </div>
        <div class="span3 offset5">
	  <div class="form-group">
             
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                             'buttonType'=>'submit',
                             'label'=>$model->isNewRecord ? 'Create' : 'Save',
                            
                             'htmlOptions'=>array(
                                             'class'=>'btn-primary',
                                     ),
                     )); 
                   ?>             
                   
          </div>
        </div>
        
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php 
CHelper::registerScript('generateAccess',"
/* Control Access Rights */
$('.access_rights td input').change(function(){
	var view = $(this).parents('tr').find('input[value$=index]');
    var checked_length = $(this).parents('tr').find('input:checked').length;
    var current_value = $(this).val().indexOf('index');
    
    if( ( !view.is(':checked') &&  current_value == -1 ) || checked_length >= 1 ) {
        view.prop('checked', true);
    }else if( checked_length <= 0 ){
        view.prop('checked', false);
    }

});
$('.access_rights input[value^=noitem_]').parents('td').html('<p align=\"center\">-</p>');
"

, CClientScript::POS_READY);
?>