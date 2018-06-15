<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */
/* @var $form CActiveForm */
?>

<div class="form-group clearfix test-panal" style="margin-top: 20px;">



<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

    <?php //echo $form->errorSummary($model); ?>
    <form class="form no-mr clearfix" action="<?php echo Yii::app()->baseUrl; ?>/resourceallocationprojectwork/allocate" method="post" id="allocaterform"> 
        <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

        <div class="row">
            <h3>Resource Allocation</h3>
            <?php echo CHTML::label('Program Name', ''); ?>
            <?php echo CHTML::dropDownList('ProjectName', 'pid', CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please select Program'));
            ?>		 
        </div>

        <div class="row">
            <?php echo CHTML::label('Available Resource', ''); ?>
            <?php
            $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1 and is_password_changed="yes"'));
            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
            } 
            echo CHtml::dropDownList("txtarea1", 'id', $emp_list, array('multiple' => 'multiple', 'style' => 'height:200px;margin-top:20px;'));
            echo CHtml::link('Allocate', array(''), array('style' => 'margin-left:20px;margin-top:50px; float:left', 'class' => 'btn allcateresource'));
            echo CHtml::link('De-Allocate', array(''), array('style' => 'margin-left:20px;margin-top:50px; float:left', 'class' => 'btn dallocate'));
            ?>

            <?php
            echo CHtml::dropDownList("txtarea2", '', array(""), array('multiple' => 'multiple', 'style' => 'height:200px;margin-left:20px;margin-top:20px;'));
            ?>
        </div>

<?php echo CHtml::submitButton('Done', array('id' => 'allocateR', 'style' => 'margin-top:20px;')); ?>

    </form>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('MoveElements', "  
   
$('#ProjectName').change(function(){
var pid = $(this).val();
 
 $('#txtarea2 option').each(function(index, option) {
    $(option).remove();
});
var tval = $(this).val();

 if(tval != '')
{
$('.custom-loader').show();
$.ajax({               
				url: '" . CHelper::createUrl("resourceallocationprojectwork/fetchAllocatedResource") . "',
                type:'POST',               
			    data: {pid : pid},
                success:function(data)
                {   		
                $('.custom-loader').hide(); 
                 if(data != 0)
                 {
                    
                    var allocation = data.split(','); 
                    var length = allocation.length; 
                    for(var i = 0; i < length; i++)
                    { 
                        var nextsplit = allocation[i].split('=='); 
                        $('#txtarea2').append('<option value = '+nextsplit[0]+' >'+nextsplit[1]+'</option>'); 
                        //$('#txtarea1 option[value='+nextsplit[0]+']').remove();
                    }
                    
                    }
                }
                
            }); 
            }
}); 

    $('.allcateresource').click(function(){    
    return !$('#txtarea1 option:selected').remove().appendTo('#txtarea2');
    });
    
$('.dallocate').click(function(){    
    return !$('#txtarea2 option:selected').remove().appendTo('#txtarea1');
    });
    
//$('#txtarea2').click(function(){
//$('.allcateresource').hide();
//$('.dallocate').show();
//});
//
//$('#txtarea1').click(function(){
//$('.allcateresource').show();
//$('.dallocate').hide();
//});
 
$('#allocateR').click(function(){ 
$('#txtarea2 option').prop('selected', true);
if($('#ProjectName').val() == '')
{
    alert('Please select project.');
    return false;
}

});



 

    ", CClientScript::POS_READY);
?>

<?php
Yii::app()->clientScript->registerScript('ShowResource', "
  
  $('#ProjectName').change(function(){
  
  
});

    ", CClientScript::POS_READY);
?>

