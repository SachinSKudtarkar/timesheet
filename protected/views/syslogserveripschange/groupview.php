<?php
/* @var $this SyslogserveripschangeController */
/* @var $model Syslogserveripschange */

//
//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//	$('.search-form').toggle();
//	return false;
//});
//$('.search-form form').submit(function(){
//	$('#syslogserveripschange-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
$this->menu = array(
    //array('label'=>'List ApplicableCommandsNew', 'url'=>array('index')),
    array('label' => 'Uploads loopback0', 'url' => array('UploadIps')),
);
?>

<!-- <div class="span2" style="float:right;"> 
            <h5>
<?php
//                echo CHtml::link('Upload loopback0 file', array('//Syslogserveripschange/UploadIps'));
?>
            </h5>
        </div>-->
<h1>Manage Syslog server ips changes</h1> 

<?php
$order_device_type = array('ESR' => 'CSS', 'PAR' => 'AG1', 'AAR' => 'AG2', 'CCR' => 'AG3', 'CSR' => 'SAR', 'CRR' => 'CRR',
    'URR' => 'URR', 'IAR' => 'IBR', 'CMR' => 'DCN', 'AMR' => 'AMR', 'IRR' => 'IRR', 'AUS' => 'AUS', 'CNR' => 'IPSLA',
    'CBR' => 'RTBH','CAS' => 'Nexus' );
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'syslogserveripschange-grid',
    'dataProvider' => $model->view(),
    'filter' => $model,
    'columns' => array(
        /* 'id', */
        'request_id',
        'sequence_no',
        'ip_address',
        'hostname',
        'site_sap_name',
        'sys_cha_ip',
        'ip_found',
        array(
            'header' => 'Sys Logs',
            'type' => 'raw',
            'value' => array($this, 'downloadLogFile'),
            'htmlOptions' => array('style' => 'width: 10%;'),
        ),
        'created_at',
        'status_at',
//        'device_type',
//                'status',
        array(
            'name' => 'device_type',
            'type' => 'raw',
            'filter' => CHtml::dropDownList('Syslogserveripschange[device_type]', $model->device_type, $order_device_type, array('empty' => 'All')),
        ),
        array(
            'name' => 'Status',
            'type' => 'raw',
            'value' => array($model, 'getLogStatus'),
            'filter' => CHtml::dropDownList('Syslogserveripschange[Status]', $model->Status, array('0' => 'Pending', '1' => 'Done', '2' => 'Not Done', '3' => 'Not Reachable', '4' => 'Connection time out', '5' => 'Master not connected'), array('empty' => 'All')),
        ),
//            array(
//                'name' => 'Opearation', 
//                'type' => 'raw',
//                'value' => array($model, 'getDeleteButton'),
//                'filter' => false,
//            ), 
    ),
        ));
?>

<?php
echo $this->renderExportGridButton($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));
?>
<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on('click', '#syslogserveripschange-grid a.delete', function () {
            var url = jQuery(this).attr('href');
            $.confirm({
                backgroundDismiss: true,
                content: 'Are you sure you want to remove this item ?',
                confirm: function () {
                    var th = this,
                            afterApplicable = function () {
                            };
                    jQuery('#syslogserveripschange-grid').yiiGridView('update', {
                        type: 'POST',
                        url: url,
                        success: function (data) {
                            jQuery('#syslogserveripschange-grid').yiiGridView('update');
                            afterApplicable(th, true, data);
                        },
                        error: function (XHR) {
                            return afterApplicable(th, false, XHR);
                        }
                    });
                },
                cancel: function () {
                }
            });
            return false;
        });

    });
</script>
<?php
$jsUrl = CHelper::projectUrl();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($jsUrl . '/themes/cisco/js/libs/jquery-confirm.js', CClientScript::POS_END);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-confirm.css" />
<style>
    .grid-view .filters input, .grid-view .filters select {
        border: 1px solid #ccc;
        width: 95%;
    }
</style>
