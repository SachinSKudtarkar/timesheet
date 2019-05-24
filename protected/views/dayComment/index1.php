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
    font-size: 11px!important;
  }

  #calendar {
    width: 33%;
    padding:10px;
    /*margin: 0 auto;*/
    background: #fff;
    border:2px solid #eee;
    font-size: 11px!important;
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
    /*padding: 20px;*/
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
.dataTables_filter {
   float: left !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover,.dataTables_wrapper .dataTables_paginate .paginate_button:hover,#tasksTable_next,#tasksTable_previous,.calendarbtn{
    background: #2C3E50;
    border-color: #2C3E50;
    color: #fff!important;
}
.coloninput{
    border: none;
    width: 5px;
    padding: 5px;
    padding-bottom: 10px;
}
#scrolldiv{
    overflow-y: scroll;
    max-height:400px;
}
.projectComplete{
    color:#ccc!important;
    text-decoration-line: line-through;
}
.fc-today{
    background: #d02d28!important;
    color:#fff;
}
.fc-today span{
    color:#fff;
}
.fc-future{
    background: #ddd!important;
    pointer-events: none
}
#fullCalModal{
    width: 700px;
}
#fullCalModal table{
    width: 98%;
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
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery.dataTables.min.css");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-core.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-interaction.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/main-calendar-daygrid.js");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery.dataTables.min.js");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'


);
// echo '<pre>';print_r($arrData);echo '</pre>';exit;
//echo 'asdasd';die;
?>
<div class="timesheet-main">
    
        <div id='calendar'>
            <p style="margin:10px;"><strong>Note (Calendar):</strong> The above calendar shows all the previous added hours in the timesheet. To get more details for a specific date, click on hours block <span class="label label-info">00:00</span> in the calendar above to show the details.</p>
             <p style="margin:10px;"><strong>Note (Hours Form):</strong><br>1) Please add hours only for which you have worked and leave the rest of the tasks as it is. <br>2) Add hours less than the estimated hours.<br>3) Use the search and pagination filters to search for the specific tasks.<br>4) After successfully adding hours you can verify the added hours for the day in the calendar (Left).</p>
        </div>
        
    <!-- </div> -->
    <div id="tasksdiv">    
        <h1 class="text-center">Add your timecard for: <strong style="color:#d9534f"><?php echo $selected_date;?></strong></h1>
        
        <form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addhours" method="post" id="addhoursfrm">
            <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />
            <div id="scrolldiv">
            <table class="table responsive" id="tasksTable">
                <thead>
                    <tr>
                        <!-- <th>Project Name</th> -->
                        <th width="39%">Task Name</th>
                        <th width="15%">Used / Est Hours</th>
                        <th width="23%">Hours / Mins</th>
                        <th width="25%">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($tasks as $task){ 
                        $completeColor = '';
                        $completeBgColor = '';

                        if(strrpos($task['status'], 'Completed') !== FALSE && strrpos($task['status'], 'Auto Completed') === FALSE && strrpos($task['status'], 'Partially Completed') === FALSE){
                            $completeColor = "projectComplete";
                            $completeLabel = "labelComplete";
                        }

                    ?>
                    <tr>
                        <!-- <td><?php echo '('.$task['project_name'].') '.$task['sub_project_name'];?></td> -->
                        <td>
                            <small style="color:#d9534f" class="<?= $completeColor;?>"><?php echo $task['project_name'];?> / <?php echo $task['sub_project_name'];?></small><br>
                            <?php echo $task['sub_task_name'];?>
                            <?php if(!empty($output[$task['stask_id']])){ 
                                $status = ($output[$task['stask_id']]['status'] == "Success!") ? "alert-success" : "alert-danger";
                                $message = $output[$task['stask_id']]['message'];
                            ?>
                            <div class="alert <?php echo $status; ?>">
                              <strong><?php echo $output[$task['stask_id']]['status']; ?></strong> <?php echo $message; ?>
                            </div>
                        <?php } ?>
                        </td>
                        <td>
                            <span class="label label-danger"><?php echo ($task['utilized_hrs']) ? $task['utilized_hrs'] :  $task['est_hrs'].':00';?></span>
                            <span class="label label-success"><?php echo $task['est_hrs'];?></span>
                        </td>
                        <td>
                            <?php 
                                $status_border = '';
                                $final_hrs = 0;
                                $final_mins = 0;
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
                                $final_hrs = (isset($today_hours[0]) && !empty($today_hours[0]))? $today_hours[0] : '0';
                                
                                if(isset($oldvalues["'{$taskkey}'"]['hours']) && !empty($oldvalues["'{$taskkey}'"]['hours']))
                                {
                                    $final_hrs = $oldvalues["'{$taskkey}'"]['hours'];
                                }

                                $final_mins = isset($today_hours[1]) ? $today_hours[1] : '0';
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
                           
                           <?php if(strrpos($task['status'], 'Completed') !== FALSE && strrpos($task['status'], 'Auto Completed') === FALSE && strrpos($task['status'], 'Partially Completed') === FALSE){
                                echo '<small style="color:#d9534f">This Project is marked as Completed by Manager.</small>';
                            } else { ?>
                            
                            <input name="<?php echo $hours_name;?>" type="number" min="0" 
                            value="<?php echo $final_hrs; ?>" max="<?php echo $hours;?>" class="change_hrs numinput <?php echo $status_border; ?>"> 
                            <input type="input" readonly="true" value=":" class="coloninput">
                            <input name="<?php echo $mins_name;?>" type="number" min="00" value="<?php echo $final_mins; ?>" max="<?php echo $mins;?>" class="numinput change_mins <?php echo $status_border; ?>">
                            <?php } ?>
                        </td>
                        <td>
                            <?php if(strrpos($task['status'], 'Completed') !== FALSE && strrpos($task['status'], 'Auto Completed') === FALSE && strrpos($task['status'], 'Partially Completed') === FALSE){
                                echo '<small> Adding hours is restricted</small>';
                            } else { ?>
                                <textarea rows="1" name="<?php echo $comment_name;?>" id="comment" class="<?php echo $status_border; ?>"><?php echo $final_comment; ?></textarea>
                            <?php } ?>
                             <input type="hidden" name="<?php echo $tasks_name;?>" value="<?php echo $task['sub_task_name'];?>">
                        </td>
                    </tr>
                        
                    <?php } ?>
                    </div>
                </tbody>
            </table>
            </div>
            <table class="table responsive">
                <tr>    
                        <td>
                            <?php if(!isset($_GET['date']) || (isset($_GET['date']) && in_array($_GET['date'], $allowedDates))){ ?>
                            <input type="submit" class="btn btn-primary" value="Save" style="margin-left:0px;border:0;">
                            <input type="reset" class="btn btn-primary" value="Reset" style="margin-left:10px;border:0;">
                            <?php }else{ ?>
                                <p><strong>Adding Hours is restricted for this date.</strong></p>
                                <p> Allowed Dates: <?php echo implode(',',$allowedDates); ?></p>
                            <?php } ?>
                            <p>
                            <input type="hidden" name="dvalue" value="<?php echo base64_encode($selected_date); ?>">
                        </td>
                        <td><b><h4>Total Hours(Today)</h4></b></td>
                        
                        <td class="text-align:left"><b><h1 id="totalHrsTxt">00:00</h1></b></td>
                </tr>
                   
            </table>
        </form>

    </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid' ],
        defaultDate: '<?php echo date('Y-m-d') ?>',
        editable: false,
        selectable: false,
        eventDurationEditable: true,
        eventLimit: true, // allow "more" link when too many events
        dateClick: function(info) {
            console.log(info);
            
            if((info.dayEl.className).indexOf('fc-future') != -1){
                alert('Adding hours for the future days is restriced');
                return false;
            }

            if((info.dayEl.className).indexOf('fc-past') != -1){
                alert('Adding hours for the past days is restriced');
                return false;
            }
            
            //alert(Date(Date.now()).toString());
            // alert('Clicked on: ' + info.dateStr);
            // $('#changeurl').attr('href',BASE_URL+'/daycomment/index/selecting_date/'+data).trigger('click');

            // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
            // alert('Current view: ' + info.view.type);
            // // change the day's background color just for fun
            info.dayEl.style.backgroundColor = '#d9534f';
            //window.location.href = BASE_URL+'/daycomment/addhours?date='+info.dateStr;
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
            console.log(event.event);
            $('#modalTitle').html("Timesheet Details for day: "+ event.event.extendedProps.tdate);
            

            // $('#modalBody p#stn').html("<strong>Task Name: </strong>" + event.event.extendedProps.sub_task_name);
            // $('#modalBody p#hrsp').html("<strong>Hours Added: </strong>" + event.event.title);
            // $('#modalBody small').html("<strong>Comment: </strong>" + event.event.extendedProps.comment);
            //$('#eventUrl').attr('href',event.url);
            $('#modalBody tbody').html(event.event.extendedProps.task_details);
            $('#fullCalModal').modal();
        }
       // events:user_records_json
    });

    calendar.render();
    
});

$(document).ready(function(){
    $(".change_hrs").trigger("change");
    // $("#calendar").hide();
    // $('#tasksTable').DataTable({
    //     "lengthChange": false,
    //     "pageLength": 1,
    //     "language": {
    //         "paginate": {
    //             "next": '>', // or '→'
    //             "previous": '<' // or '←' 
    //         }
    //     }
    // });
    //$(".change_hrs, .change_mins").trigger('change');
});

$(".change_hrs, .change_mins").on('change',function(e){
    var finalArr = [];
    var hrsarr = [];
    var minsarr = [];
    $(".change_hrs").each(function(e){
        var hrsValue = $(this).val();
        if(parseInt(hrsValue,10)<10)
        {
            hrsValue='0'+hrsValue;
        }
        hrsarr.push(hrsValue);
    });
    $(".change_mins").each(function(e){
        var minsValue = $(this).val();
        if(parseInt(minsValue,10)<10)
        {
            minsValue='0'+minsValue;
        }
        minsarr.push(minsValue);
    });
    for(var i = 0; i < hrsarr.length; i++)
    {
        var hrstxt = (hrsarr[i] != '' ? hrsarr[i] : '00');
        var minstxt = (minsarr[i] != '' ? minsarr[i] : '00');
        finalArr.push(String(hrstxt+":"+minstxt+":00"));
    }
    var todayHours = JSON.stringify(finalArr);
    // console.log(finalArr);
    $.ajax({
        url: "<?php echo CHelper::createUrl("daycomment/addCurrentHours"); ?>",
        type:'POST',
        data: {todayHours : todayHours},
        success:function(data)
        {
            if(data != '')
            {
                $('#totalHrsTxt').text(data);
            }
        }
    });
});

$(".calendarbtn").click(function(){
    $("#calendar").toggle();
    

});
</script>

<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body">
                <!-- <blockquote>
                  <p id="stn"></p>
                  <p id="hrsp"></p>
                  <small></small>
                </blockquote> -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="40%">Task Name</th>
                            <th width="20%">Hours</th>
                            <th width="40%">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>