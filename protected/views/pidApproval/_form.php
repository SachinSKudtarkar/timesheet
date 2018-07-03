<?php
/* @var $this PidApprovalController */
/* @var $model PidApproval */
/* @var $form CActiveForm */

//Yii::app()->clientScript->registerCoreScript('jquery.ui');
//
//$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
//$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
//
//Yii::app()->clientScript->registerCssFile(
//        Yii::app()->clientScript->getCoreScriptUrl() .
//        '/jui/css/base/jquery-ui.css'
//);

Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$cs = Yii::app()->getClientScript();
$baseUrl = Yii::app()->baseUrl;
$cs->registerScriptFile($baseUrl . '/js/custom_add_more_4k.js?28032017');
$img_path = Yii::app()->theme->baseUrl . "/img/add_image.png";

$arrHrs = array();
for($h=0; $h<=999; $h++) {
    $h = (strlen($h) < 2) ? "0".$h : $h;
    $arrHrs[$h] = $h;
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pid-approval-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'afterValidate'=>'js:yiiFix.ajaxSubmit.afterValidate'
    )
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Program'); ?>
            <?php 

echo CHTML::dropDownList('project_id', 'pid', CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 
			'condition' => 'is_deleted=0')), 'pid', 'project_name'),array(
    'prompt'=>'Select Project',
    'ajax' => array(
    'type'=>'POST', 
    'url'=>Yii::app()->createUrl('ResourceAllocationProjectWork/GetPId'),
    'update'=>'#sub_project_id',
    'data'=>array('pid'=>'js:this.value'),
  ))
			
			);			?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>
	
	 <div class="row">
            <?php echo CHTML::label('Project', ''); ?>
            <?php echo CHtml::dropDownList('sub_project_id','', array(), array('prompt'=>'Select Task'));
            ?>		 
        </div>

	

	<div class="row">
		<?php echo $form->labelEx($model,'inception_date'); ?>
		<?php // echo $form->textField($model,'inception_date'); ?>
           <?php echo $form->textField($model, 'inception_date', array('id' => 'inception_date', 'class' => 'datepicker', 'placeholder' => 'Inception Date', 'style' => 'width:200px;')); ?>
		<?php echo $form->error($model,'inception_date'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'jira_id'); ?>
                <?php echo $form->textField($model,'jira_id',array('size' => '1','style' => 'width:50px' )); ?>
        <?php echo $form->error($model,'jira_id'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_est_hrs'); ?>
		<?php // echo $form->textField($model,'total_est_hrs'); ?>
                <?php echo $form->textField($model,'total_est_hrs',array('size' => '1','style' => 'width:50px', 'class'=>'totwrkhrClass'  )); ?>
		<?php echo $form->error($model,'total_est_hrs'); ?>
	</div>
        <div class="row" id="L2_device_div">
            <table style="margin-left: 0px"   class="bottomBorder">
                <tr>
                  <!--  <td style="alignment-adjust: middle;color: #35619B"> Sr. No  </td>-->
                    <td style="alignment-adjust: middle;color: #35619B"> Type (Dev/Test/Infra)   </td>
                    <td style="alignment-adjust: middle;color: #35619B"> Resource Name  </td>
                    <td style="alignment-adjust: middle;color: #35619B"> Task   </td>
                    <td style="alignment-adjust: middle;color: #35619B"> Estimated Hours   </td>
                </tr>
                <?php
                $count = 0;
                if($subtask[0]['task_id']!=''){
                        foreach ($subtask as $key=>$value) {
                            $count++;
                            ?>   
                            <tr class="row_copy_l2_ring">
                               <!-- <td><?php// echo $count; ?> </td>-->
                                <td><?php echo CHtml::dropDownList('task_id[]', $value->task_id, CHtml::listData(Task::model()->findAll("status=1"),'task_id', 'task_name'),array('data-name' => 'task_id')); ?></td>
                                <td><?php 
								//echo CHtml::dropDownList('emp_id[]', $value->emp_id, CHtml::listData(Employee::model()->findAll("is_active=1 AND access_type!=1"),'emp_id', 'first_name'),array('data-name' => 'emp_id')); 
								$emp_list =array();
								echo CHtml::dropDownList("emp_id[]", 'id', $emp_list);
								?></td>
                                <td><?php echo CHtml::textField('sub_task_name[]', $value->sub_task_name, array('data-name' => 'sub_task_name', 'style' => "width:210px;", "name" => "data[0][sub_task_name]", "class" => "sub_task_name")); ?></td>
                                <td><?php 
                                
                                echo CHtml::textField('est_hrs[]', $value->est_hrs,array('data-name' => 'est_hrs', 'style' => "width:50px;", "name" => "data[0][est_hrs]", "class" => "est_hrs wrkhrsClass")); 
                                
                                ?></td>
                                <td><?php
                                    echo CHtml::link('', 'javascript:void(0);', array('class' => 'icon-plus-sign l2_ring customAddMorel2_ring'));
                                    if ($count > 1)
                                        
                                        ?> 
                                    <?php echo CHtml::link('', 'javascript:void(0);', array('class' => 'icon-remove-sign l2_ring customRemoveRowl2_ring')); ?></td>
                            </tr>
                            <?php
                        }
                    }else {
                        ?>
                        <tr class="row_copy_l2_ring">
                           <!-- <td><?php //echo $count+1; ?> </td>-->
                            <td><?php echo CHtml::dropDownList('task_id[]', $value->task_id, CHtml::listData(Task::model()->findAll("status=1"),'task_id', 'task_name'),array('data-name' => 'task_id')); ?></td>
                            <td><?php 
							$emp_list =array();
							echo CHtml::dropDownList("emp_id[]", 'id', $emp_list);
							
							//echo CHtml::dropDownList('emp_id[]', $value->emp_id, CHtml::listData(Employee::model()->findAll("is_active=1 AND access_type!=1"),'emp_id', 'first_name'),array('data-name' => 'emp_id')); ?></td>
                            <td><?php echo CHtml::textField('sub_task_name[]', $value->sub_task_name, array('data-name' => 'sub_task_name', 'style' => "width:210px;", "name" => "data[0][sub_task_name]", "class" => "sub_task_name")); ?></td>
                            <td><?php echo CHtml::textField('est_hrs[]', $value->est_hrs,array('data-name' => 'est_hrs', 'style' => "width:50px;", "name" => "data[0][est_hrs]", "class" => "est_hrs wrkhrsClass")); ?></td>
                            <td><?php echo CHtml::link('', 'javascript:void(0);', array('class' => 'icon-plus-sign l2_ring customAddMorel2_ring')); ?></td>
                        </tr>
                        <?php
                    } ?>

            </table> 
        </div>
	<div class="row">
		<?php echo $form->labelEx($model,'comments'); ?>
		<?php echo $form->textArea($model,'comments',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'comments'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Update',array('id'=>'ISSUB')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
Yii::app()->clientScript->registerScript('filters', "
  
    $('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
  
    
", CClientScript::POS_READY);
?>
<script>
//        $(document).on('change','.totwrkhrClass, .wrkhrsClass', function(){
        $(document).on('change',' .wrkhrsClass', function(){
getWrkHoursTotal();
        });
		function getWrkHoursTotal() {
			var allhrs = 0;
			var allmnts = 0;
                        var totelthrs = $('.totwrkhrClass').val();
			$('.wrkhrsClass').each(function(){
				var thisval = $(this).val();
				if(thisval != ''){
					allhrs = parseFloat(allhrs)+parseFloat(thisval);
				}
				
				
                                if(totelthrs < allhrs ){
                                    alert('Exceeding Estimated Hours');
									$("#ISSUB").attr('disabled',true);
                                   // return false;
                                }else{
					$("#ISSUB").attr('disabled',false);
								//return true;
								}
			});
                        
                       
    
			var totHrs = ( parseFloat(allhrs)+parseInt( (allmnts / 60)) ) +':'+ ( parseFloat( (allmnts % 60) ) );
			$('#tworkedHrs').val(totHrs);
		}
		getWrkHoursTotal();
		
		

$('#PidApproval_total_est_hrs').on('change',function(){
    var totelthrs = $(this).val();
     if(totelthrs == 0){
                            alert("Hours should be more than 0");
                            
                        }

});

$('#PidApproval_jira_id').on('change',function(){
   var jira = $(this).val(); 
   if(jira == null){
       alert("Jira Id should not be blank");
       
   }
});


 $('#project_id').on('change',function(){
	var pid = $(this).val();

	$.ajax({
		url:  '<?php  echo CHelper::createUrl('resourceallocationprojectwork/GetallocatedResource') ?>',
                type:'POST',               
			    data: {pid : pid},
                success:function(data)
                {
					 $('.custom-loader').hide(); 
					 if(data != 0)
					 {
						
						$('#emp_id').html(data);
					  
					}
				}
	});	
});


$(document).ready(function(){
 $('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
  
  });
  
//  $('#')
  
</script>
   