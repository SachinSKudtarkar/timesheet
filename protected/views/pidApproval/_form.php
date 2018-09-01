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
for ($h = 0; $h <= 999; $h++) {
    $h = (strlen($h) < 2) ? "0" . $h : $h;
    $arrHrs[$h] = $h;
}
//echo '<pre>';print_r($model);die;
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pid-approval-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => false,
//        'afterValidate'=>'js:yiiFix.ajaxSubmit.afterValidate'
        )
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php //echo $form->errorSummary($model); ?>
	<div class="span5">
	<div class="row">
            <?php echo CHTML::label('Task ID',''); ?>
            <?php
				echo CHtml::textField("project_task_id",$model->project_task_id,array('readonly'=>true));
            ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'Program'); ?>
		
        <?php
            echo CHTML::dropDownList('PidApproval[project_id]', 'pid', CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name',
                                'condition' => 'is_deleted=0')), 'pid', 'project_name'), array(
                'prompt' => 'Select Project',
				'options' => array($model->project_id=>array('selected'=>true)),
                'ajax' => array(
                    'type' => 'POST',
                    'url' => Yii::app()->createUrl('ResourceAllocationProjectWork/GetPId'),
                    'update' => '#PidApproval_sub_project_id',
                    'data' => array('pid' => 'js:this.value'),
                ))
            );
        ?>
		
        <?php echo $form->error($model, 'project_id'); ?>
    </div>

    <div class="row">
        <?php echo CHTML::label('Project', ''); ?>
        <?php echo CHtml::dropDownList('PidApproval[sub_project_id]', 'spid', CHtml::listData(SubProject::model()->findAll(array('order' => 'sub_project_name','condition' => 'is_deleted=0')), 'spid', 'sub_project_name'), array('prompt' => 'Select Project','options' => array($model->sub_project_id=>array('selected'=>true))));?>
        <?php echo $form->error($model, 'sub_project_id'); ?>
    </div>
	<div class="row">
            <?php echo $form->labelEx($model,'task_title'); ?>
            <?php echo $form->textField($model,'task_title',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->error($model,'task_title'); ?>
	</div>
	<div class="row">
            <?php echo $form->labelEx($model,'task_description'); ?>
            <?php echo $form->textArea($model,'task_description',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->error($model,'task_description'); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model, 'inception_date'); ?>
        <?php // echo $form->textField($model,'inception_date'); ?>
        <?php echo $form->textField($model, 'inception_date', array('id' => 'PidApproval_inception_date', 'class' => 'datepicker', 'placeholder' => 'Inception Date', 'style' => 'width:200px;')); ?>
        <?php echo $form->error($model, 'inception_date'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'jira_id'); ?>
        <?php echo $form->textField($model, 'jira_id', array('size' => '1', 'style' => 'width:50px')); ?>
        <?php echo $form->error($model, 'jira_id'); ?>
    </div>

    <div class="row">
	
        <?php echo $form->labelEx($model, 'total_est_hrs'); ?>
        <?php // echo $form->textField($model,'total_est_hrs'); ?>
        <?php echo $form->textField($model, 'total_est_hrs', array('size' => '1', 'style' => 'width:50px', 'class' => 'totwrkhrClass')); ?>
        <?php echo $form->error($model, 'total_est_hrs'); ?>
    </div>
	</div>
	<div class="span5">
		<?php if(!isset($hours_label)){ ?>
		<div class="row">
			<table class="table table-striped table-bordered text-center hours_label">
				<tr class="text-center">
					<th>Estimated (Hrs)</th>
					<th>Allocated (Hrs)</th>
					<th>Utilized (Hrs)</th>
				</tr>
				<tr>
				
					<td id="estimated_hrs"><?php echo 0; ?></td>
					<td id="allocated_hrs"><?php echo 0; ?></td>
					<td id="utilized_hrs"><?php echo 0; ?></td>
				</tr>
			</table>
			
		</div>
		<?php } ?>
	</div>
    <div class="row" id="L2_device_div">
		
        <table style="margin-left: 0px"   class="bottomBorder">
            <tr>
              <!--  <td style="alignment-adjust: middle;color: #35619B"> Sr. No  </td>-->
                <td style="alignment-adjust: middle;color: #35619B"> Type (Dev/Test/Infra)   </td>
                <td style="alignment-adjust: middle;color: #35619B"> Resource Name  </td>
                <td style="alignment-adjust: middle;color: #35619B"> Task   </td>
				<td style="alignment-adjust: middle;color: #35619B"> Jira Id   </td>
				<td style="alignment-adjust: middle;color: #35619B"> Inception Date</td>
                <td style="alignment-adjust: middle;color: #35619B"> Estimated Hours   </td>
            </tr>
            <?php
            $count = 0;
            if ($subtask[0]['task_id'] != '') {
				
                foreach ($subtask as $key => $value) {
                    $count++;
                    ?>   
					
                    <tr class="row_copy_l2_ring">
                        <!-- <td><?php // echo $count;  ?> </td>-->
                        <td><?php echo CHtml::dropDownList('task_id[]', $value['task_id'], CHtml::listData(Task::model()->findAll("status=1"), 'task_id', 'task_name'), array('data-name' => 'task_id')); ?></td>
                        <td><?php
                                //echo CHtml::dropDownList('emp_id[]', $value->emp_id, $emp_list = CHtml::listData(Employee::model()->findAll("is_active=1 AND access_type!=1"),'emp_id', 'first_name'),array('data-name' => 'emp_id')); 
								
								
								$emp_data = Employee::model()->fetchEmployee($model->project_id);
								$emp_list = CHtml::listData($emp_data['emp_list'],'emp_id', 'name');
                               // $emp_list = array();
								echo CHtml::dropDownList("emp_id[]", $value['emp_id'], $emp_list,array('class'=>'emp_id_bud','options'=>$emp_data['options_data']));
								
								
                            ?>
                        </td>
                        <td><?php echo CHtml::textField('sub_task_name[]', $value['sub_task_name'], array('data-name' => 'sub_task_name', 'style' => "width:210px;", "name" => "data[0][sub_task_name]", "class" => "sub_task_name")); ?></td>
						<td>
							<?php echo CHtml::textField('st_jira_id[]',$value['st_jira_id'], array('data-name' => 'st_jira_id', 'style' => "width:210px;", "name" => "data[0][st_jira_id]", "class" => "st_jira_id")); ?>
						</td>
						<td>
							<?php echo CHtml::textField('st_inception_date[]',$value['st_inception_date'], array('data-name' => 'st_inception_date', 'style' => "width:100px;", "name" => "data[0][st_inception_date]", "class" => "st_inception_date datepicker")); ?>
						</td>
                        <td><?php
                                echo CHtml::textField('est_hrs[]', $value['est_hrs'], array('data-name' => 'est_hrs', 'style' => "width:50px;", "name" => "data[0][est_hrs]", "class" => "est_hrs wrkhrsClass"));
                            ?>
							
                        </td>
						 <td><?php echo CHtml::link('', 'javascript:void(0);', array('class' => 'icon-remove-sign l2_ring customRemoveRowl2_ring')); ?></td>
						 
						
                    </tr>
                    <?php
                }
            } else {
                
				?>
				
                <tr class="row_copy_l2_ring">
                   <!-- <td><?php //echo $count+1;  ?> </td>-->
                    <td><?php echo CHtml::dropDownList('task_id[]', $value->task_id, CHtml::listData(Task::model()->findAll("status=1"), 'task_id', 'task_name'), array('data-name' => 'task_id')); ?></td>
                    <td><?php
                            $emp_list = array();
                            echo CHtml::dropDownList("emp_id[]", 'id', $emp_list,array('class'=>'emp_id_bud'));
                            //echo CHtml::dropDownList('emp_id[]', $value->emp_id, CHtml::listData(Employee::model()->findAll("is_active=1 AND access_type!=1"),'emp_id', 'first_name'),array('data-name' => 'emp_id')); 
                        ?>
                    </td>
                    <td><?php echo CHtml::textField('sub_task_name[]', $value->sub_task_name, array('data-name' => 'sub_task_name', 'style' => "width:210px;", "name" => "data[0][sub_task_name]", "class" => "sub_task_name")); ?></td>
					<td>
						<?php echo CHtml::textField('st_jira_id[]',$value->st_jira_id, array('data-name' => 'st_jira_id', 'style' => "width:50px;", "name" => "data[0][st_jira_id]", "class" => "st_jira_id")); ?>
					</td>
					<td>
						<?php echo CHtml::textField('st_inception_date[]',$value->st_inception_date, array('data-name' => 'st_inception_date', 'style' => "width:100px;", "name" => "data[0][st_inception_date]", "class" => "st_inception_date datepicker")); ?>
					</td>
					<td><?php echo CHtml::textField('est_hrs[]', $value->est_hrs, array('data-name' => 'est_hrs', 'style' => "width:50px;", "name" => "data[0][est_hrs]", "class" => "est_hrs wrkhrsClass", "min"=> '1')); ?></td>
					
                    <td><?php echo CHtml::link('', 'javascript:void(0);', array('class' => 'icon-plus-sign l2_ring customAddMorel2_ring')); ?></td>
                </tr>
            <?php } ?>

        </table> 
		
		<?php if (!empty($model->project_task_id)) {
			echo '<p>'.CHtml::link('', 'javascript:void(0);', array('class' => 'icon-plus-sign l2_ring customAddMorel2_ring')).'</p>'; 
		}?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'comments'); ?>
        <?php echo $form->textArea($model, 'comments', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'comments'); ?>
    </div>
    <div class="row buttons">
		<?php if (!empty($model->project_task_id)) { ?>
		<?php echo $form->hiddenField($model,'pid_id'); ?>
        <?php } ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Update', array('id' => 'ISSUB')); ?>
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
    });
  
    
    ", CClientScript::POS_READY);
?>
<script>
//        $(document).on('change','.totwrkhrClass, .wrkhrsClass', function(){
    $(document).on('change', ' .wrkhrsClass', function () {
		
        getWrkHoursTotal();
    });
    function getWrkHoursTotal() {
		var pidid = $("#PidApproval_pid_id").val();
		if(pidid != '' && pidid > 0){
			var update_pid = pidid;
			
			var allhrs = 0;
		}else{
			var update_pid = 0;
			var allhrs = ($('#allocated_hrs').text() == '') ? 0 : $('#allocated_hrs').text();
		}
        
        var allmnts = 0;
        var totalhrs = $('#estimated_hrs').text();
		var project_id = $('#PidApproval_sub_project_id').val();
		var hrs_array = [];
		var remaining_budget = 0;
		$i = 0;
        $('.wrkhrsClass').each(function () {
            var thisval = $(this).val();
            hrs_array.push(thisval);
			if (thisval != '') {
                allhrs = parseFloat(allhrs) + parseFloat(thisval);
            }
			
        });
		
		/* var hours_array = $("input[name='est_hrs[]']").map(function(){return $(this).val();}).get(); */
		 $('.emp_id_bud').each(function () {
            var thisval = $(this).val();
			var emp_b = $(this).children(":selected").attr("id");
			var old_hrs = $(this).children(":selected").attr("oldhrs");

			
			remaining_budget = remaining_budget + (hrs_array[$i] * emp_b);
		
			
			$i++;	
         });
		 
		
		$.ajax({
            url: '<?php echo CHelper::createUrl('pidapproval/checkHoursAndBudget') ?>',
            type: 'POST',
            data: {project_id: project_id,allhrs: allhrs,totalhrs: totalhrs,remaining_budget: remaining_budget,update_pid: update_pid},
            success: function (data){
                $('.custom-loader').hide();
                
				if (data != 0) {
					alert(data);
					$("#ISSUB").attr('disabled', true);
					// return false;
				} else {
					$("#ISSUB").attr('disabled', false);
					//return true;
				} 
            }
        });

        var totHrs = (parseFloat(allhrs) + parseInt((allmnts / 60))) + ':' + (parseFloat((allmnts % 60)));
        $('#tworkedHrs').val(totHrs);
    }
   // getWrkHoursTotal();

    $('#PidApproval_total_est_hrs').on('change', function () {
        var totalhrs = $(this).val();
        if (totalhrs == 0) {
            alert("Hours should be more than 0");

        }

    });

    $('#PidApproval_jira_id').on('change', function () {
        var jira = $(this).val();
        if (jira == null) {
            alert("Jira Id should not be blank");

        }
    });


    $('#PidApproval_project_id').on('change', function () {
        var pid = $(this).val();

        $.ajax({
            url: '<?php echo CHelper::createUrl('resourceallocationprojectwork/GetallocatedResource') ?>',
            type: 'POST',
            data: {pid: pid},
            success: function (data){
                $('.custom-loader').hide();
                if (data != 0){
                    $('#emp_id').html(data);
                }
            }
        });
    });



	
    $(document).ready(function () {
	
        $('.datepicker').datepicker({
            dateFormat: 'yy-m-d',
            onSelect: function (dateText) {
                var type = $(this).attr('id');
                var date = $(this).val();
            },
        });
		
		
    });

	function addDatepicker(element)
	{
		//alert(element);
		
		$(element).removeClass('hasDatepicker');
		
		$(element).datepicker();
		
	}
	

</script>
<?php if(isset($model->project_id) && !empty($model->project_id)){ ?>


<?php 
Yii::app()->clientScript->registerScript('projecttaskid1', "
	$(window).load(function () {
	
		var project_id = $('#PidApproval_sub_project_id').val();
		
		fetchProjectId(project_id,'update');
		
	});
	
	
   ", CClientScript::POS_READY);
   
   
?>
<?php 
/* Yii::app()->clientScript->registerScript('empload','jQuery(function($) {
			$("#PidApproval_project_id").trigger("change"); 
			
	});
'); */
?>


<?php } ?>

<?php

Yii::app()->clientScript->registerScript('projecttaskid', "

$('#PidApproval_sub_project_id').change(function(){
	var project_id = $(this).val();
	
	fetchProjectId(project_id,'create');
});

$('#PidApproval_project_id').change(function(){
	setTimeout(function(){ 
		var project_id = $('#PidApproval_sub_project_id').val();
		fetchProjectId(project_id,'create');
	}, 500);
	
});

function fetchProjectId(project_id,update){

var tval = project_id;
var update_id = $('#PidApproval_pid_id').val();

if(tval != '')
{
$('.custom-loader').show();
$.ajax({
				url: '" . CHelper::createUrl("pidapproval/fetchSubProjectIdAndHours") . "',
                type:'POST',
				dataType: 'json',
			    data: {project_id : project_id,update_id: update_id},
                success:function(data)
                {
					
					$('.custom-loader').hide();
					
					if(data == 0){
						alert('There is no project associated with the Program. Please create new project');
						return false;
					}
					
					if(data != '')
					{
						if (update == 'create') {
							$('#project_task_id').val(data.project_id);
						}
						
						$('#allocated_hrs').text((data.allocated.allocated_hrs > 0) ? data.allocated.allocated_hrs : '0');
						$('#estimated_hrs').text((data.estimated.estimated_hrs > 0) ? data.estimated.estimated_hrs : '0');
						$('#utilized_hrs').text((data.utilized.utilized_hrs != null) ? data.utilized.utilized_hrs : '0');
					}
				}
            });
            }
}







    ", CClientScript::POS_READY);
?>