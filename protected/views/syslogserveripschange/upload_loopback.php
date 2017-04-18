<?php
$device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : 0;
$this->menu = array(
    array('label' => 'Manage loopback0', 'url' => array('admin')),
);
?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'syslogserveripschange-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>

<!--    <p class="note">Fields with <span class="required">*</span> are required.</p>-->
    <?php echo $form->errorSummary($model); ?> 

    <h4>Upload loopback's</h4>
    <div class="row">
        <div class="span7">
            <div class="MyError"  style="color: red !important; font-weight: bold  !important; text-align: center;"></div>  
            <?php
            if (Yii::app()->user->hasFlash('notice')) {
                ?>
                <div  class="info">
                    <?php
                    echo "<center><h5>" . Yii::app()->user->getFlash('notice') . "</h5></center>";
                    ?>
                </div>
                <?php
            }
            if (Yii::app()->user->hasFlash('error')) {
                ?>
                <div style="color: red !important; font-weight: bold  !important;" class="info" >
                    <?php echo "<center><h5>" . Yii::app()->user->getFlash('error') . "</h5></center>"; ?>
                </div>
                <?php
            }
            ?> 
        </div>
    </div>
    <div class="row">
        <?php
        $order_device_type = array('ESR' => 'CSS', 'PAR' => 'AG1', 'AAR' => 'AG2', 'CCR' => 'AG3', 'CSR' => 'SAR', 'CRR' => 'CRR',
            'URR' => 'URR', 'IAR' => 'IBR', 'CMR' => 'DCN Core','CAR' => 'DCN Edge', 'AMR' => 'AMR', 'IRR' => 'IRR', 'CNR' => 'IPSLA',
            'CBR' => 'RTBH', 'CAS' => 'Nexus', 'AUS' => 'AUS');
        echo $form->labelEx($model, 'device_type');
        echo $form->dropDownList($model, 'device_type', $order_device_type, array('class' => 'span4', 'prompt' => 'Please Select Device', 'options' => array($device_id => array('selected' => true))));
        echo $form->error($model, 'device_type');
        ?> 
    </div> 
    <div class="row">
        <?php
        echo $form->labelEx($model, 'loopback ips');
        if ($device_id == "PAR" || $device_id == "ESR") { 
            echo $form->textArea($model, 'pool_ips', array('style' => 'width: 350px; height: 400px;'));
            // echo $form->error($model, 'pool_ips'); 
        }
            ?>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('id' => 'submitButton')); ?>
        </div>
        <?php $this->endWidget(); ?>
</div> 
    <?php
    Yii::app()->clientScript->registerScript('search', "
    $('#submitButton').click(function()
    { 
    var pool_ips = $('#Syslogserveripschange_pool_ips').val();
    var device_type = $('#Syslogserveripschange_device_type').val();
    pool_ips=pool_ips.trim();
    device_type=device_type.trim();
     if(device_type=='')
    {
    $('.info').html('');
    $('.MyError').html('<h5>Please select device type.</h5>');
    return false;
    }

    if(pool_ips=='')
    {
    $('.info').html('');
    $('.MyError').html('<h5>Please Enter Ip`s in given field.</h5>');
    return false;
    }
    });
    
   $('#Syslogserveripschange_device_type').change(function()
    {   
    var x=$('#Syslogserveripschange_device_type').val(); 
    if(x == 'ESR' || x == 'PAR') {
     document.location='" . CHelper::createUrl("syslogserveripschange/UploadIps") . "?device_id='+x;   
        }  
        else
        {
           document.location='" . CHelper::createUrl("syslogserveripschange/uploadCoreDevices") . "?device_id='+x;     
        }
    });
");
    ?>             
