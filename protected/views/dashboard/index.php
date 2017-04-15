<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>
<!---------first-box----------->
<div class="top-dash">
    <div class="input-append">
        <label>Completed on Date:</label>

        <input  name="date_current" id="date_current" value="<?= date('d-m-Y') ?>"  type="text" class="datepicker" placeholder="Select Date" />
    </div></div>

<div class="cisco-dashboard">
    <div class="dash-box"> <a class="setting" href="#"><span>Build Survey Completed</span>
            <div class="circle-wrap build-site-wrap">0</div></a>
    </div>

    <div class="dash-box"> <a class="setting" href="#"><span>Installation Completed</span>
            <div class="circle-wrap installation_completed_on_date">0</div></a>
    </div>

    <div class="dash-box"> <a class="my-profile" href="#">
            <span>ATP 1A Completed</span>
            <div class="circle-wrap">0</div></a> 
    </div>

    <div class="dash-box"> <a class="setting" href="#"><span>NDD Delivered</span>
            <div class="circle-wrap ndd-wrap">&nbsp;</div></a>
    </div>
    <div class="dash-box"> <a class="setting" href="#"><span>Unique NDD Delivered</span>
            <div class="circle-wrap unique-ndd-wrap">&nbsp;</div></a>
    </div>

    <div class="dash-box"> <a class="content" href="integrationsitereport/admin">
            <span>Devices Integrated</span>
            <div class="circle-wrap site-wrap">&nbsp;</div></a> 
    </div>
    <div class="dash-box"> <a class="content" href="integrationsitereport/admin">
            <span>Unique Devices Integrated</span>
            <div class="circle-wrap site-wrap-unique">&nbsp;</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>HOTO Checklist Approved</span>
            <div class="circle-wrap hoto-approved-wrap">0</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>ATP 1B QA Approved</span>
            <div class="circle-wrap qa-approved-wrap">0</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>SO6 Completed</span>
            <div class="circle-wrap">0</div></a> 
    </div>
    
</div>



<hr />  
<!---------second-box----------->
<div class="top-dash">
    <div class="input-append">
        <label>Completed as of Date:</label>
        <input  name="date_till" id="date_till" value="<?= date('d-m-Y') ?>"  type="text" class="datepicker" placeholder="Select Date" />
    </div></div>

<div class="cisco-dashboard">
    <div class="dash-box"> <a class="setting" href="#"><span>Build Survey Completed</span>
            <div class="circle-wrap build-site-summary-wrap">0</div></a>
    </div>
    <div class="dash-box"> <a class="setting" href="#"><span>Installation Completed</span>
            <div class="circle-wrap installation_completed_till_date">0</div></a>
    </div>

    <div class="dash-box"> <a class="my-profile" href="#">
            <span>ATP 1A Completed</span>
            <div class="circle-wrap">0</div></a> 
    </div>

    <div class="dash-box"> <a class="setting" href="#"><span>NDD Delivered</span>
            <div class="circle-wrap ndd-summary-wrap">&nbsp;</div></a>
    </div>
    <div class="dash-box"> <a class="setting" href="#"><span>Unique NDD Delivered</span>
            <div class="circle-wrap unique-ndd-summary-wrap">&nbsp;</div></a>
    </div>

    <div class="dash-box"> <a class="content" href="integrationsitereport/admin">
            <span>Devices Integrated</span>
            <div class="circle-wrap site-summary-wrap">&nbsp;</div></a> 
    </div>
    <div class="dash-box"> <a class="content" href="integrationsitereport/admin">
            <span>Unique Devices Integrated</span>
            <div class="circle-wrap site-summary-wrap-unique">&nbsp;</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>HOTO Checklist Approved</span>
            <div class="circle-wrap hoto-approved-wrap-summary">0</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>ATP 1B QA Approved</span>
            <div class="circle-wrap 1b-summary-wrap">0</div></a> 
    </div>
    <div class="dash-box"> <a class="fault" href="#">
            <span>SO6 Completed</span>
            <div class="circle-wrap">0</div></a> 
    </div>
    
</div>

<?php
Yii::app()->clientScript->registerScript('filters', "
    var currentDate = 0;
    var tillDate = 0;
    $('.datepicker').datepicker({
     dateFormat: 'd-m-yy',
     maxDate:0,
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();
        getDashboardUpdates(type, date);
      },
    }).attr('readonly','readonly');

    //Get dashboard update for current date/on date.
    getDashboardUpdatesEventTop = function(){
        getDashboardUpdates('date_current',$('#date_current').val());
    };

    //Get dashboard update till current date/on date.
    getDashboardUpdatesEventBottom = function(){
        getDashboardUpdates('date_till',$('#date_till').val());
    };

    //common ajax function to get dashboard updated.
    getDashboardUpdates = function(type,date){
        if(type == 'date_current' && currentDate == 0){
            currentDate = 1;
        }else if(type == 'date_till' && tillDate == 0){
            tillDate = 1;
        }else{
            return;
        }
        $.ajax({
            url: '" . CHelper::createUrl("AssignedSites/GetDashboardUpdates") . "',
            type: 'POST',
            dataType: 'json',
            data:{'type':type,'date':date},
            success: function(data) {
                if(data){
                    if(type == 'date_current'){
                        currentDate = 0;
                    }else if(type == 'date_till'){
                        tillDate = 0;
                    }
                    $.each(data, function(index, element) {
                        $('.'+index).html(element);
                    });
                }
            },
        });
    };

    //var int = self.setInterval(getDashboardUpdatesEventTop, 30000); 
    //var int = self.setInterval(getDashboardUpdatesEventBottom, 30000); 
    getDashboardUpdatesEventTop();
    getDashboardUpdatesEventBottom();
    
", CClientScript::POS_READY);
?>