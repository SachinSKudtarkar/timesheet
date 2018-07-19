
<?php
/* @var $this DayCommentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Day Comments',
);

$this->menu = array(
    array('label' => 'DayComment', 'url' => array('index')),
    array('label' => 'View My Status', 'url' => array('admin')),
);

Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);

$emp_id = Yii::app()->session['login']['user_id'];





$form = $this->beginWidget('CActiveForm', array(
    'id' => 'day-comment-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => true,
//    'clientOptions'=>array(
//        'validateOnSubmit'=>true,
//        'afterValidate'=>'js:yiiFix.ajaxSubmit.afterValidate'
//    )
        ));
$this->widget('ext.yiicalendar.YiiCalendar', array
    (
    'dataProvider' => array
        (
        'pagination' => array
            (
            'pageSize' => 'week',
            'isMondayFirst' => TRUE
        )
    )
));
?>


<link  rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">



<div class="info well well-large">
    <h1>Time Sheet Calculator</h1>
    <p>Fill in the table on the left with the start and end times of a task, and the task being completed. Then click <code>submit</code>, which will sum the hours and place them in the proceeding table.</p>
</div>


<div id="centerContainer">

    <div class="info well-large cloneclass">
        <!-- Example row of columns -->
        <div class="row">
            <div class="block-left ">
                <form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addcomment" method="post" id="addcommentfrm">
                    <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />


                    <div class="row">
                        <?php echo $form->labelEx($model, 'day'); ?>
                        <?php // echo $form->textField($model,'inception_date'); ?>
                        <?php echo $form->textField($model, 'day', array('id' => 'day', 'class' => 'datepicker', 'placeholder' => 'Select Date', 'style' => 'width:200px;')); ?>
                        <?php echo $form->error($model, 'day'); ?>
                    </div>

                    <div class="span6">
                        <?php
                        $shift = array('1'=>'Day', '2'=>'Night');
                    echo $form->radioButtonList($model,'shift',$shift,array('separator'=>' '));

                        ?>
                    </div>


                    </p>

                    <table  class="table table-hover small-text" id="tb">

                        <thead>
                            <tr class="tr-header">

                                <th>Work Hours</th>
                                <!--<th>End Time</th>-->
                                <th>Program</th>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Comments</th>
                                <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" title="Add More Person"><span class="glyphicon glyphicon-plus"></span></a></th>
                            </tr>
                        </thead>
                        <tbody id="hoursBody">
                            

                            <tr>

                                <td><input type="text" placeholder="HH:MM" name ="hours[]" class="timepicker"></td>
                                <!--<td><input type="text" placeholder="HH:MM" name ="end[]" class="timepicker"></td>-->
                                <td class="Program">
                                   <?php echo CHTML::dropDownList('ProjectName[]', $eachproject['pid'], CHtml::listData($allProjects, 'pid', 'project_name'), array('style' => 'width:150px;', 'disabled' => ($is_submitted ? 'disabled' : ''), 'prompt' => 'Please select Program', 'class' => 'proclass', 'id' => $date_id ."_". $pid, 'nxt' => $date_id ."_". $nxt)); ?>
                                    <!--<input  type="text" placeholder="Select Program" name ="Program[]" class="form-control">-->

                                </td>

                                <td class="Project">
                                   
                                    <!--<input type="text" placeholder="Select Project" name ="SubProject[]" class="form-control">-->
                                <?php
                                $result = array();
                                echo CHTML::dropDownList('SubProjectName[]', 'spid', $result, array('class'=>'sub-project','style' => 'width:150px;  ', 'prompt' => 'Please select  Project', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subproclass' . $date_id ."_". $eachproject['pid']));
                                 ?>
                                </td>
                                <td class="Task">
                                    <!--<input  type="text" placeholder="Select Task" name ="Task[]"class="form-control">-->
                                <?php
                                $result = array();
                                echo CHTML::dropDownList('SubTaskName[]', 'stask_id', $result, array('class'=>'sub-task','style' => 'width:150px;  ', 'prompt' => 'Please select  Task', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subtasks' . $date_id ."_". $eachproject['pid']));
				?>
                                </td>
                                <td class="Comment">
                                    <!--<input  type="text" placeholder="Please right comment here" name ="Comment[]"class="form-control">-->
                                <?php // echo CHTML::label('Comment :', '', array('style' => 'width:130px;font-weight:bold; ')); 
				  echo CHtml::textArea('procomment[]','', array('style' => ' height:40px; width:200px; ','class' =>'DayComment', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'comment' . $date_id ."_". $eachproject['pid']));
				?>
                                </td>
                                <td><a href='javascript:void(0);'  class='remove'><span class='glyphicon glyphicon-remove'></span></a></td>
                            </tr>


                        </tbody>
                    </table>
                    
                    <br />
                    <div class="row">
                        <?php
                        echo CHtml::submitButton('submit', array('id' => 'hoursBody', 'style' => 'margin-top:10px;'));

                        //if(!$is_submitted && ( strtotime(date('Y-m-d')) >= strtotime("saturday this week") && strtotime(date('Y-m-d')) <= strtotime("monday next week 1 pm") ) ) {
                        //echo CHtml::submitButton('Submit', array('id' => 'btnSubmit', 'style' => 'margin-top:10px;', 'value'=>'Submit'));
                        ?>
                    </div>
                    <!--<button type="button" class="btn span3" id="hrSubmit">Submit</button>-->
                </form>
                <!-- <div id="error">This is a test error message.</div> -->
            </div>


        </div>
    </div> <!-- /container -->
</div>

<?php $this->endWidget(); ?>


<script>
    $(function () {
        $('#addMore').on('click', function () {
//        var $div = $('div[id^="klon"]:last');

            var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
            data.find("input").val('');
        });
        $(document).on('click', '.remove', function () {
            var trIndex = $(this).closest("tr").index();
            if (trIndex > 0) {
                $(this).closest("tr").remove();
            } else {
                alert("Sorry!! Can't remove first row!");
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

$(document).ready(function(){
    $('.timepicker').timepicker({
        timeFormat:'hh:mm',
        onSelect: function(dateTxt){
            var type = $(this).attr('id');
            var time = $(this).val();
        }
    });
//    var date = $(this).val();
//    alert(date);
})



//$('.timepicker').timepicker({
//    timeFormat: 'h:mm p',
//    interval: 5,
//    minTime: '10',
////    maxTime: '6:00pm',
//    defaultTime: '11',
////    startTime: '10:00',
//    dynamic: false,
//    dropdown: true,
//    scrollbar: true
//});



</script>



<!--</body>-->
<!--</html>-->


