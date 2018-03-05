<?php
/* @var $this EmployeeController */
/* @var $model Employee */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'employee-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
        //'focus'=>array($model,'first_name'),
        ));
?>
<div class="form">
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">

                <?php /* $form=$this->beginWidget('CActiveForm', array(
                  'id'=>'employee-form',
                  // Please note: When you enable ajax validation, make sure the corresponding
                  // controller action is handling ajax validation correctly.
                  // There is a call to performAjaxValidation() commented in generated controller code.
                  // See class documentation of CActiveForm for details on this.
                  'enableAjaxValidation'=>false,
                  )); */ ?>

                <div class="row">
                    <?php echo $form->labelEx($model, 'first_name'); ?>
                    <?php echo $form->textField($model, 'first_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'first_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'middle_name'); ?>
                    <?php echo $form->textField($model, 'middle_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'middle_name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'last_name'); ?>
                    <?php echo $form->textField($model, 'last_name', array('size' => 30, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model, 'last_name'); ?>
                </div>    

                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 150)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
                <div class="row ermessageemail" style="color:#9f2b1e; margin-left:140px;margin-top:-25px;display: none;">Please register under @cisco.com, @ril.com OR @rjil.com domain.</div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'phone', array('class' => '')); ?>

                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'phone',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 20),
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'phone'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'mobile', array('class' => '')); ?>
                    <?php
                    $this->widget('CMaskedTextField', array(
                        'model' => $model_employee_details,
                        'attribute' => 'mobile',
                        'mask' => '999-999-9999',
                        'htmlOptions' => array('size' => 12)
                    ));
                    ?>
                    <?php echo $form->error($model_employee_details, 'mobile'); ?>
                </div> 

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'work_location'); ?>
                    <?php echo $form->textField($model_employee_details, 'work_location'); ?>
                    <?php echo $form->error($model_employee_details, 'work_location'); ?>
                </div>

            </div>
            <div class="span6">
                <div class="row">
                    <?php
                     echo $form->labelEx($model,'access_type'); 
                  echo $form->dropDownList($model,'access_type',
                        array("0"=>"Please select role","1"=>"Admin")+CHtml::listData(Roles::model()->findAll(
								array('order' => 'name ASC','condition'=>'is_deleted=0')),
								'id','name'
							)
                        );  
		   echo $form->error($model,'access_type'); 
                    ?> 
                </div>
                <?php //if(Yii::app()->controller->action->id == 'create') { ?>
                <div class="row">
                    <?php echo $form->labelEx($model, 'password', array('class' => '')); ?>
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->passwordField($model, 'password');
                    } else {
                        echo $form->passwordField($model, 'password', array('value' => ''));
                    }
                    ?> 
<?php echo $form->error($model, 'password'); ?>
                </div>
                <div class="row">
                    <label><b>Note:</b></label> 	
                    <span style="color: green" >Password must be at least eight characters long including one uppercase letter, one lowercase letter, one special character & one number with no spaces.</span>
                </div>
                    <?php if (Yii::app()->controller->action->id == 'create') { ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'repeat_password', array('class' => '')); ?>
                        <?php
                        if ($model->isNewRecord) {
                            echo $form->passwordField($model, 'repeat_password');
                        } else {
                            echo $form->passwordField($model, 'repeat_password', array('value' => ''));
                        }
                        ?> 
                        <?php echo $form->error($model, 'repeat_password'); ?>
                    </div>   
                    <?php }
                    ?>
                <div class="row">
<?php echo $form->labelEx($model_employee_details, 'address_1', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'address_1', array('maxlength' => 100, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'address_1'); ?>
                </div>

                <div class="row">
<?php echo $form->labelEx($model_employee_details, 'city', array('class' => '')); ?>
                    <?php echo $form->textField($model_employee_details, 'city', array('class' => '', 'maxlength' => 50, 'placeholder' => '')); ?> 
                    <?php echo $form->error($model_employee_details, 'city'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model_employee_details, 'state_id', array('class' => '')); ?>
                    <?php
                    echo $form->dropDownList(
                            $model_employee_details, 'state_id', CHtml::listData(StateMaster::model()->findAll(
                                            array('order' => 'state_name_short ASC', 'condition' => 'is_disabled=0')), 'state_id', 'state_name'
                            ), array('empty' => 'Select State', 'class' => '')
                    );
                    ?>

                </div> 

            </div>
            <div class="span3 offset5">
                <div class="form-group">

                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'label' => 'Submit',
                        'htmlOptions' => array(
                            'class' => 'btn-primary sbtn',
                        ),
                    ));
                    ?>             
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => Yii::app()->request->urlReferrer,
                        'label' => 'Cancel',
                        'htmlOptions' => array(
                            'class' => 'btn-primary',
                        ),
                    ));
                    //echo CHtml::button('Back',array('onclick'=>'js:history.go(-1);returnFalse;','style'=>'font-size: 14px;font-weight: bold;')); 
                    ?>
                </div>
            </div>

        </div>
    </div>
    <?php
// check created by && Super admin can only access this portion
    /* if( CHelper::isAccess('SA', 'admin.SubAdmin.AccessRights') )
      { */
    ?>
<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
CHelper::registerScript('generateAccess', "
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

CHelper::registerScript('showhideAccessTable', "
/* For hiding and showing the access right div */ 
$('#Employee_access_type').change(function(){
    return true;
    /*switch ($(this).val()) 
    {
        case '1': 
           $('.access_rights1').hide();
        break;
        case '0': 
            $('.access_rights1').show();
        break;
        default:
         $('.access_rights1').hide();
            var id = $(this).val();
            $.ajax(
            {       
                url : '" . CHelper::createUrl("//employee/getroles/") . "',
                type : 'post',
                dataType : 'json',
                data : {id : id},
                success : function(data){                    
                    alert(data);
                    
                }
        });
    }*/
	
});
"
        , CClientScript::POS_READY);


CHelper::registerScript('email restrictions', "
/* restring user to  etner the email in abc@cisco.com/abc@rjil.com/abc@ril.com format */
/* $('.sbtn').live('click',function(){
                                            var emailval = $('#Employee_email').val();
                                           
                                            if(emailval != '')
                                            {  
                                                var splt = emailval.split('@');
                                                var str_email = splt[1].toLowerCase();
                                                
                                                if(str_email == 'cisco.com' || str_email == 'rjil.com' || str_email == 'ril.com')
                                                {
                                                    return true;
                                                }
                                                else
                                                {                                                    
                                                    $('.ermessageemail').show();
                                                    return false;
                                                }
                                            }
                                    });*/
                                    
"
        , CClientScript::POS_READY);
?>
