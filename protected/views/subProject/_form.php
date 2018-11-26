<?php
/* @var $this SubProjectController */
/* @var $model SubProject */
/* @var $form CActiveForm */

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
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'sub-project-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="span5">
        <div class="row">
            <?php echo CHTML::label('Project ID', ''); ?>
            <?php
            echo CHtml::textField("sub_project_id", $model->project_id, array('readonly' => true));
            ?>
        </div>
        <div class="row">
            <?php echo CHTML::label('Program Name', ''); ?>
            <?php echo $form->dropDownList($model, 'pid', CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please select Program')); ?>
            <?php echo $form->error($model, 'pid'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'sub_project_name'); ?>
            <?php echo $form->textField($model, 'sub_project_name', array('size' => 60, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'sub_project_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'sub_project_description'); ?>
            <?php // echo $form->textField($model,'sub_project_description',array('size'=>60,'maxlength'=>250)); ?>
            <?php echo $form->textArea($model, 'sub_project_description', array('size' => 60, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'sub_project_description'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'requester'); ?>
            <?php echo $form->textField($model, 'requester', array('size' => 60, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'requester'); ?>
        </div>
        <!--<div class="row">
        <?php //echo $form->labelEx($model, 'estimated_start_date'); ?>
        <?php
        //echo $form->textField($model,'estimated_end_date');
        //echo $form->textField($model, 'estimated_start_date', array('id' => 'start_date', 'class' => 'datepicker', 'placeholder' => 'Select Date', 'style' => 'width:200px;'));
        ?>
        <?php //echo $form->error($model, 'estimated_start_date'); ?>
        </div>
        <div class="row">
        <?php //echo $form->labelEx($model,'estimated_end_date'); ?>
        <?php //echo  $form->textField($model,'estimated_end_date',array('id' => 'end_date', 'class'=>'datepicker', 'placeholder' => 'Select Date', 'style'=>'width:200px;')); ?>
        <?php //echo $form->error($model,'estimated_end_date'); ?>
        </div>
        <div class="row">
        <?php //echo $form->labelEx($model,'total_hr_estimation_hour'); ?>
        <?php //echo $form->textField($model,'total_hr_estimation_hour',array('size'=>10,'maxlength'=>10)); ?>
        <?php //echo $form->error($model,'total_hr_estimation_hour'); ?>
        </div>-->
        <div class="row">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php
            //echo $form->textField($model,'status',array('size'=>25,'maxlength'=>25));
            echo $form->dropDownList($model, 'status', array('Completed' => 'Completed', 'Newly Created' => 'Newly Created', 'Partially Completed' => 'Partially Completed'), array('prompt' => '(Select Status)', 'class' => 'sts'));
            ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'priority'); ?>
            <?php
            //echo $form->textField($model,'type');
            echo $form->dropDownList($model, 'priority', array('1' => 'High', '2' => 'Medium', '3' => 'Low'), array('prompt' => '(Select Priority)', 'class' => 'prio'));
            ?>
            <?php echo $form->error($model, 'priority'); ?>
        </div>
        <div class="row">
        </div>
    </div>
    <div class="span5">
        <?php if (isset($hours_label)) { ?>
            <div class="row">
                <table class="table table-striped table-bordered hours_label">
                    <tr>
                        <th>Estimated (Hrs)</th>
                        <th>Allocated (Hrs)</th>
                        <th>Utilized (Hrs)</th>
                    </tr>
                    <tr>

                        <td id="estimated_hrs"><?php echo $hours_label['estimated']['estimated_hrs']; ?></td>
                        <td id="allocated_hrs"><?php echo $hours_label['allocated']['allocated_hrs']; ?></td>
                        <td><?php echo (isset($hours_label['utilized']['utilized_hrs'])) ? $hours_label['utilized']['utilized_hrs'] : 0; ?></td>
                    </tr>
                </table>

            </div>
        <?php } ?>
        <div class="row" id="estimationBlock">
            <?php if (isset($estimatedArr) && count($estimatedArr) > 0) {
                ?>
                <table class="table table-striped table-bordered">
                <caption style="text-align:left">
                    Estimation:
                    <span style="float: right; cursor: pointer" ><small id="showLogs">Show Logs</small></span>
                </caption>
                    <tr>
                        <th width="30%">Level</th>
                        <th>Hours</th>
                    </tr>
                    <?php foreach ($estimatedArr as $key => $row) { ?>
                        <tr>
                            <td><?php echo $row['level_name']; ?></td>
                            <td><?php echo $row['level_hours']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
        <div class="row" >
            <?php if ($levels[0]['level_id'] != '') {
                $levelhours = '';
                ?>
                <table class="table table-striped table-bordered"id="crLogs" style="display: none" >
                <caption style="text-align:left">Change Request Logs:</caption>
                    <tr>
                        <th width="30%">Change Request</th>
                        <th>Level (Hours)</th>
                        <th>Comment</th>
                    </tr>
                    <?php foreach ($levels_log as $key => $value) { ?>
                        <tr>
                            <td><?php echo $value['cr']; ?></td>
                            <td><?php echo $value['log']; ?></td>
                            <td><?php echo $value['comments']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
            <?php /*if ($levels[0]['level_id'] != '') { ?>
                <div class="span10 row">
                </div>
                <?php echo CHtml::Button('Update Hours', array('id' => 'update_hours')); ?>
            <?php }*/ ?>
            <?php echo CHtml::Button('Update Hours', array('id' => 'update_hours')); ?>
            <div class="span10 row updhrs"><p> Allocated Level and its associated Hours to project.</p></div>

            <div class="repeater updhrs">
                <div data-repeater-list="group-a">
                    <?php
                    $count = 0;
                    $options_data = [];
                    if ($levels[0]['level_id'] != '') {
                        foreach ($levels as $key => $value) {

                            $options_data[$value->level_id] = array('data-oldhrs' => $value->level_hours);
                        }
                        // echo '<pre>';print_r($options_data);die;
                    }
                    ?>
                    <div data-repeater-item class="row">
                        <?php echo CHtml::dropDownList('level_id', "", CHtml::listData(LevelMaster::model()->findAll(array('order' => 'level_name ASC')), 'level_id', 'level_name'), array('data-name' => 'level_id', 'style' => "width:150px;margin-right:20px", "class" => "level_id_hrs", "options" => $options_data)); ?>
                        <?php echo CHtml::textField('level_hours', '', array('data-name' => 'level_hours', 'style' => "width:100px;", "name" => "data[0][level_hours]", "class" => "level_hours","required"=>true)); ?>
                        <input data-repeater-delete type="button" style="background: red;padding: 0px 5px; font-weight: bold;" value="x"/>
                    </div>
                </div>
                <input data-repeater-create type="button" style="background: limegreen;padding: 0px 5px; font-weight: bold;margin-left: 0" value="+"/>
            </div>
        </div>
            <?php if (!$model->isNewRecord) { ?>
            <div class="row span10 updhrs">
                <?php echo CHtml::label('Comments', ''); ?>
                <?php
                //echo $form->textField($model,'type');
                echo CHtml::textArea("level_comments", '', array('data-name' => 'level_comments', "name" => "data[0][comments]", "class" => "level_comments", "disabled" => false));
                ?>
            </div>
    <?php } ?>
    </div>
    <?php // echo $form->labelEx($model,'is_deleted'); ?>
    <?php // echo $form->textField($model,'is_deleted'); ?>
<?php // echo $form->error($model,'is_deleted');   ?>

    <div class="row buttons span10">
        <?php echo $form->hiddenField($model, 'spid'); ?>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('id' => 'ISSUB')); ?>
    <?php if(!$model->isNewRecord && Yii::app()->session['login']['user_id'] == '6'  && $model->approval_status == '2') { 
        $baseurl = Yii::app()->getBaseUrl(true);
        $approvalLink = "{$baseurl}/subproject/updateStatus/1{$model->spid}";
        $rejectLink = "{$baseurl}/subproject/updateStatus/0{$model->spid}"; 

    ?>
        <a href="<?php echo $approvalLink; ?>" class="abutton">Approve</a>
        <a href="<?php echo $rejectLink; ?>" class="abutton rejectBtn" >Reject</a>
    <?php } ?>
    </div>
<?php $this->endWidget(); ?>
</div>
<!-- form -->
<script>
    $(document).ready(function(){
        //$('#crLogs').slideUp();
        $('#showLogs').click(function(){
            if($(this).html() == 'Show Logs'){
                $('#crLogs').slideDown();
                $(this).html('Hide Logs');
            }else{
                $('#crLogs').slideUp();
                $(this).html('Show Logs')
            }
        })
    })

    $(document).on('change', ' .level_id_hrs', function () {
        checkDuplicateLevel();
    });

    function checkDuplicateLevel() {
        var values = [];
        $('.level_id_hrs').each(function () {
            if ($.inArray($(this).val(), values) >= 0) {
                alert('You have already selected this level. Please check and try again.');
                $("#ISSUB").attr('disabled', true);
                // return false;
            } else {
                $("#ISSUB").attr('disabled', false);
                //return true;
            }
            values.push(this.value);
        });
    }
    $(document).on('change', ' .level_hours', function () {
        checkDuplicateLevel();
        getWrkHoursTotal();
    });
    function getWrkHoursTotal() {
        var allhrs = $("#SubProject_spid").val() > 0 ? $('#allocated_hrs').text() : 0;
        var allmnts = 0;
        var totalhrs = $("#SubProject_spid").val() > 0 ? $('#estimated_hrs').text() : 0;
        /* var project_id = $('#PidApproval_sub_project_id').val(); */
        // alert(allhrs);return false;
        if ($("#SubProject_spid").val() > 0) {
            $('.level_id_hrs').each(function () {
                var thisval = $(this).val();
                var oldval = $(this).find("option:selected").data('oldhrs');

                if (thisval != '') {
                    totalhrs = parseFloat(totalhrs) - parseFloat(oldval);
                }
            });

        }

        $('.level_hours').each(function () {
            var thisval = $(this).val();
            if (thisval != '') {
                totalhrs = parseFloat(totalhrs) + parseFloat(thisval);
                $("#level_comments").prop('required', true);
            } else {
                $("#level_comments").prop('required', false);
            }
        });


        if (totalhrs < allhrs) {
            alert('Estimated hours(' + totalhrs + ') should be greater than allocated hours(' + allhrs + '). Please check and try again.');
            $("#ISSUB").attr('disabled', true);
            // return false;
        } else {
            $("#ISSUB").attr('disabled', false);
            //return true;
        }

        var totHrs = (parseFloat(allhrs) + parseInt((allmnts / 60))) + ':' + (parseFloat((allmnts % 60)));
        $('#tworkedHrs').val(totHrs);
    }

    if ($("#SubProject_spid").val() > 0) {
        $(".updhrs").hide();
        $("#update_hours").click(function () {
            $(".updhrs").toggle();
            $(".level_id_hrs").change();
        });
    }

    
    $('.level_hours').each(function(){
        if($(this).is(':visible') == true){
            $(this).attr('required',true);
        }else{
            $(this).attr('required',false);
        }
    });
    

</script>
<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery.repeater.js");
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>
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

<?php
Yii::app()->clientScript->registerScript('projectonchange', "

$('#SubProject_pid').change(function(){

	var project_id = $(this).val();
	var update_id = $('#SubProject_spid').val();
	var tval = $(this).val();
	if(tval != '')
	{
		$('.custom-loader').show();
		$.ajax({
			url: '" . CHelper::createUrl("subproject/fetchProjectId") . "',
			type:'POST',
			data: {project_id : project_id,update_id: update_id},
			success:function(data)
			{
			$('.custom-loader').hide();

				if(data != 0)
				{
					$('#sub_project_id').val(data);
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
if($('#LevelName').val() == '')
{
    alert('Please select level.');
    return false;
}

});

 $('.repeater').repeater();

    ", CClientScript::POS_READY);
?>