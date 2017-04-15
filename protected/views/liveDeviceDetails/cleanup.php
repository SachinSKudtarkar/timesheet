<?php
/* @var $this LiveDeviceDetailsController */
/* @var $model LiveDeviceDetails */

Yii::app()->clientScript->registerScript('search', " 
$('div#databox ul li').hide();    
$('.showdata').click(function(){
        var liID = $(this).attr('rel');
        if($(this).is(':checked')){
            $('li#'+liID).show();
            $('li#'+liID+' ul li').show();
        }else{
            $('li#'+liID).hide();
            $('li#'+liID+' ul li').hide();
        }
});

$('#checkAll').click(function(){
    $('.showdata').attr('checked', $(this).is(':checked'));
    checkboxShowData();
});

function checkboxShowData(){
    $('.showdata').each(function(ind, val){
        var liID = $(this).attr('rel');
        if($(this).is(':checked')){
            $('li#'+liID).show();
            $('li#'+liID+' ul li').show();
        }else{
            $('li#'+liID).hide();
            $('li#'+liID+' ul li').hide();
        }
    });
}

$('#searchBtn').click(function(){
	var formdata = $('form#searchFilter').serialize();
        $('div.custom-loader').show();
        $.ajax({
            url: '" . CHelper::createUrl("LiveDeviceDetails/getSearchCleanupData") . "',
            type: 'POST',
            data:formdata,
            success: function(data) { 
                $('div.custom-loader').hide();
                if(data){
                    $('div#databox').html(data);
                    $('div#databox ul li').show();  
                    checkboxShowData();
                }
            },
        });
	return false;
});

$('a.updateRecord').live('click',function(){ 
    alert('Working');
    return false;
    var tbl = $(this).attr('tbl');
    var recID = $(this).attr('rel');
    $('div.modal-header h4').html(tbl);
    $.ajax({
            url: '" . CHelper::createUrl("LiveDeviceDetails/getRecordUpdateHtml") . "',
            type: 'POST',
            data:{tbl : tbl, recID : recID},
            success: function(data) { 
                if(data){
                    $('.modal-body-reason').html(data);
                    $('#cleanup_modal').modal('show');
                }
            },
        });
    return false;
});

$('a.freeRecord').live('click',function(){
    alert('Working');
    return false;
    var r = confirm('Do you want to free this record?');
    if (r == true) {
        var tbl = $(this).attr('tbl');
        var recID = $(this).attr('rel');
        $.ajax({
            url: '" . CHelper::createUrl("LiveDeviceDetails/freeRecord") . "',
            type: 'POST',
            data:{tbl : tbl, recID : recID},
            success: function(data) { 
                $('#searchBtn').trigger('click');
            },
        });
    }
    return false;
});

");


$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'cleanup_modal'));
?>
<div class = "modal-header">
    <a class = "close" data-dismiss = "modal">&times;
    </a>
    <h4></h4>
</div>
<div class = "modal-body-reason">

</div>
<?php $this->endWidget(); ?>

<style>
    #filterchk, #searchparm{
        margin: 0;
        padding: 0;
        list-style-type: none;
    }
    ul#filterchk li, ul#searchparm li{ 
        display: inline;
        padding-left: 5px;
    }
    .textblue{ color: blue;}
    .textgreen{ color: green;}
    .textyellow{ color: #7a43b6;}
    .ctitle{ color: gray;}
    div.row .error{margin-left: 50px;}
    .mismatch { color: red;}
    .verticalLine {border-left: thin solid #A3A3A3;height: 100px}
</style>

<div class="form-group clearfix test-panal" style="margin-top: 15px;font-family: fantasy;" >
    <h4>Cleanup</h4>
    <div style="height:120px;text-align: left;margin-left: 250px;" >
        <ul id="filterchk" type="none">
            <div style="float: left;width: 13%;padding: 10px;">
            <li><input type="checkbox" name="checkAll" id="checkAll" value="1">&nbsp;<span>Check All</span></li>
            </div>
            <div class="verticalLine" style="float: left;width: 23%;padding: 10px">
                <li><input type="checkbox" name="builtData" class="showdata" rel="routerli" value="1">&nbsp;<span class="textblue">Router as Built Data</span></li><br/>
                <li><input type="checkbox" name="cdpData" class="showdata" rel="cdpli" value="1">&nbsp;<span class="textblue">Neighbour Details - CDP</span></li><br/>
                <li><input type="checkbox" name="bgpData" class="showdata" rel="bgpli" value="1">&nbsp;<span class="textblue">Neighbour Details - BGP</span></li><br/>
                <li><input type="checkbox" name="isisData" class="showdata" rel="isisli" value="1">&nbsp;<span class="textblue">Neighbour Details - ISIS</span></li><br/>
                <li><input type="checkbox" name="deviceData" class="showdata" rel="deviceli" value="1">&nbsp;<span class="textblue">Device Version</span></li><br/>
            </div>
            <div class="verticalLine" style="float: left;width: 23%;padding: 10px">
                <li><input type="checkbox" name="nddOutputData" class="showdata" rel="nddoutputli" value="1">&nbsp;<span class="textgreen">NDD Output Master</span></li><br />
                <li><input type="checkbox" name="ag1OutputData" class="showdata" rel="ag1outputli" value="1">&nbsp;<span class="textgreen">NLD AG1 Output Master</span></li><br />
                <li><input type="checkbox" name="Mag1OutputData" class="showdata" rel="Mag1outputli" value="1">&nbsp;<span class="textgreen">Metro AG1 Output Master</span></li><br />
            </div>
            <div class="verticalLine" style="float: left;width: 15%;padding: 10px">
                <li><input type="checkbox" name="ranLBData" class="showdata" rel="ranlbli" value="1">&nbsp;<span class="textyellow">Ran LB</span></li><br />
                <li><input type="checkbox" name="coreLBData" class="showdata" rel="corelbli" value="1">&nbsp;<span class="textyellow">Core LB</span></li><br />
                <li><input type="checkbox" name="ranWanLBData" class="showdata" rel="ranwanli" value="1">&nbsp;<span class="textyellow">Ran Wan</span></li><br />
                <li><input type="checkbox" name="coreWanData" class="showdata" rel="corewanli" value="1">&nbsp;<span class="textyellow">Core Wan</span></li><br />
            </div>
        </ul>
    </div>
    <hr />
    <div>
        <form name="searchFilter" id="searchFilter" method="post" action="" style=" margin: 0px !important">
            <table align="center">
                <tr>
                    <td>Search :&nbsp;&nbsp;&nbsp;</td>
                    <td><?php echo CHtml::textField('sapid', '', array('id' => 'sapid', 'placeholder' => 'SAP ID', 'style' => 'width:150px;margin-right: 10px;', 'class' => 'clearData')); ?></td>
                    <td><?php echo CHtml::textField('hostname', '', array('id' => 'hostname', 'placeholder' => 'Hostname', 'style' => 'width:150px;margin-right: 10px;', 'class' => 'clearData')); ?></td>
                    <td><?php echo CHtml::textField('loopback', '', array('id' => 'loopback', 'placeholder' => 'Loopback0', 'style' => 'width:150px;margin-right: 10px;', 'class' => 'clearData')); ?></td>
                    <td align="left"><?php echo CHtml::button('Search', array('name' => "searchBtn", 'id' => "searchBtn", 'class' => 'btn-primary', 'style' => 'margin-left:10px; margin-bottom:8px;'));
?></td>
                    <td><?php //echo CHtml::link('Download CSS',Yii::app()->createUrl("LiveDeviceDetails/DownloadCSS"), array('style'=>'margin-left:35px;'));                      ?></td>
                </tr>
            </table>
            <div><input type="hidden" name=<?php echo Yii::app()->request->csrfTokenName; ?> value=<?php echo Yii::app()->request->csrfToken; ?>></div>
        </form>
    </div>
    <hr />
    <div id="databox">
        <?php //$this->renderPartial('_cleanup', array('model' => $model)); ?>
    </div>
</div>