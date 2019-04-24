<style>

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
    background: #fff;
  }

  .cont{
    background: #fff!important;
  }
  .timesheet-main{
    width:100%;
    display: flex;
    margin-top: 1%;
  }

  #calendar {
    width: 35%;
    padding:10px;
    /*margin: 0 auto;*/
    background: #fff;
    border:2px solid #eee;
  }
  #tasksdiv {
    width:72%;
    border:2px solid #eee;
    padding: 10px;
  }
  #tasksdiv .numinput{
    width:40px;
    border:3px solid #ccc;
  }
  #tasksdiv table td, #tasksdiv table th{
    text-align: center;
    vertical-align: center;
  }
  #tasksdiv table td:first-child{
    text-align: left;
    vertical-align: center;
  }
  #tasksdiv table th{
    font-size: 16px;
    padding: 20px;
    border-bottom: 5px solid #eee;
  }
   #tasksdiv table td{
    font-size: 14px;
    padding: 15px;
    border-bottom: 2px solid #eee;
    vertical-align: center;
  }

td span{
    width: 35px;
}

.label-danger{
    background: #d9534f;
}
.yesborder{
    border-color: #d9534f!important; 
}
</style>
<?php
/* @var $this DayCommentController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs = array(
//     'Day Comments',
// );


?>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');

$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/main-calendar-core.css");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/main-calendar-daygrid.css");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-core.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-interaction.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-daygrid.js");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'


);
// echo '<pre>';print_r($arrData);echo '</pre>';exit;
//echo 'asdasd';die;
?>
<div class="timesheet-main">
    <div id='calendar'></div>
    <div id="tasksdiv">
        <h1 class="text-center">Add your today's timecard details</h1>
        <form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addhours" method="post" id="addhoursfrm">
            <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />
            <table class="table responsive">
                <thead>
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    <col width="25%">
                    <col width="25%">

                    <tr>
                        <!-- <th>Project Name</th> -->
                        <th>Task Name</th>
                        <th>Estimated Time</th>
                        <th>Utilized Hours</th>
                        <th>Hours<br> H:Hours, M:Mins</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tasks as $task){ ?>
                    <tr>
                        <!-- <td><?php echo '('.$task['project_name'].') '.$task['sub_project_name'];?></td> -->
                        <td><?php echo $task['sub_task_name'];?></td>
                        <td><span class="label label-success"><?php echo $task['est_hrs'];?></span></td>
                        <td><span class="label label-danger"><?php echo ($task['utilized_hrs']) ? $task['utilized_hrs'] :  $task['est_hrs'].':00';?></span></td>
                        <td>
                            <?php 
                                $status_border = '';
                                $rem_arr = explode(':', $task['remaining_hours']);
                                // $hours = $rem_arr[0] > 24 ? 24 : $rem_arr[0];
                                $hours = 24;
                                $mins = 59;
                                $today_hours = explode(":",$task['today_hrs']);
                                
                                $tasks_name = "timesheet['{$task["key"]}'][task_name]";
                                $hours_name = "timesheet['{$task["key"]}'][hours]";
                                $mins_name = "timesheet['{$task["key"]}'][mins]";
                                $comment_name = "timesheet['{$task["key"]}'][comment]";
                                $taskkey = $task['key'];
                                $final_hrs = isset($today_hours[0]) ? $today_hours[0] : '';
                                if(isset($oldvalues["'{$taskkey}'"]['hours']) && !empty($oldvalues["'{$taskkey}'"]['hours']))
                                {
                                    $final_hrs = $oldvalues["'{$taskkey}'"]['hours'];
                                }
                                $final_mins = isset($today_hours[1]) ? $today_hours[1] : '';
                                if(isset($oldvalues["'{$taskkey}'"]['mins']) && !empty($oldvalues["'{$taskkey}'"]['mins']))
                                {
                                    $final_mins = $oldvalues["'{$taskkey}'"]['mins'];
                                }

                                $final_comment = isset($task['today_comment']) ? $task['today_comment'] : '';
                                if(isset($oldvalues["'{$taskkey}'"]['comment']) && !empty($oldvalues["'{$taskkey}'"]['comment']))
                                {
                                    $final_comment = $oldvalues["'{$taskkey}'"]['comment'];
                                }

                                if(!empty($output[$task['stask_id']])){ 
                                    $status = ($output[$task['stask_id']]['status'] == "Success!") ? "alert-success" : "alert-danger";
                                    $status_border = ($output[$task['stask_id']]['status'] == "Error!") ? "yesborder" : '';
                                    $message = $output[$task['stask_id']]['message'];
                                }

                            ?>
                            <span><b> H </b></span> 
                            <input name="<?php echo $hours_name;?>" type="number" min="0" 
                            value="<?php echo $final_hrs; ?>" max="<?php echo $hours;?>" class="change_hrs numinput <?php echo $status_border; ?>"> 
                            <span><b> : </b></span> 
                            <span><b> M </b></span> <input name="<?php echo $mins_name;?>" type="number" min="0" value="<?php echo $final_mins; ?>" max="<?php echo $mins;?>" class="numinput change_mins <?php echo $status_border; ?>">

                        </td>
                        <td>
                            <textarea name="<?php echo $comment_name;?>" id="comment" class="<?php echo $status_border; ?>"><?php echo $final_comment; ?></textarea>
                             <input type="hidden" name="<?php echo $tasks_name;?>" value="<?php echo $task['sub_task_name'];?>">
                        </td>
                    </tr>
                        <?php if(!empty($output[$task['stask_id']])){ 
                            $status = ($output[$task['stask_id']]['status'] == "Success!") ? "alert-success" : "alert-danger";
                            $message = $output[$task['stask_id']]['message'];

                        ?>
                        <tr>
                            <td colspan="5">
                                <div class="alert <?php echo $status; ?>">
                                  <strong><?php echo $output[$task['stask_id']]['status']; ?></strong> <?php echo $message; ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <td><b><h4>Total Hours</h4></b></td>
                        
                        <td colspan="4" class="text-align:right"><b><h1>19:00</h1></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="submit" class="btn btn-primary" value="Save" style="margin-left:0px;border:0;">
                        </td>
                        <td><input type="reset" class="btn btn-primary" value="Reset" style="margin-left:0px;border:0;"></td>
                    </tr>
                </tbody>
            </table>
        </form>

    </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid' ],
        defaultDate: '2019-04-12',
        editable: true,
        selectable: false,
        eventDurationEditable: true,
        eventLimit: true, // allow "more" link when too many events
        dateClick: function(info) {
            alert('Clicked on: ' + info.dateStr);
            alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
            alert('Current view: ' + info.view.type);
            // change the day's background color just for fun
            info.dayEl.style.backgroundColor = 'red';
        },        
        eventSources: [
                // your event source
                {
                    url: '/timesheet/daycomment/fetchUserTimesheetRecords',
                    type: 'get',
                    error: function(data) {
                        alert(data);
                    },
                }
            ],
        eventClick:  function(event, jsEvent, view) {
            //set the values and open the modal
            //console.log(event);
            $('#modalTitle').html("Timesheet Details for day: "+ event.event.extendedProps.tdate);
            $('#modalBody p#stn').html("<strong>Task Name: </strong>" + event.event.extendedProps.sub_task_name);
            $('#modalBody p#hrsp').html("<strong>Hours Added: </strong>" + event.event.title);
            $('#modalBody small').html("<strong>Comment: </strong>" + event.event.extendedProps.comment);
            //$('#eventUrl').attr('href',event.url);
            $('#fullCalModal').modal();
        }
       // events:user_records_json
    });

    calendar.render();
});

$(".change_hrs, .change_mins").change(function(e){
    alert($(this).val());
    var hrsarr = [];
    var minsarr = [];
    $(".change_hrs").each(function(e){
        hrsarr.push($(this).val());
    });
    $(".change_mins").each(function(e){
        minsarr.push($(this).val());
    });
    console.log(hrsarr);
    console.log(minsarr);
});
</script>

<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body">
                <blockquote>
                  <p id="stn"></p>
                  <p id="hrsp"></p>
                  <small></small>
                </blockquote>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>