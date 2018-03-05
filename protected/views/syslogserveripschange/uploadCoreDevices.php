<?php
$device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : 0;
$this->menu = array(
    array('label' => 'Manage loopback0', 'url' => array('admin')),
);
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'syslogserveripschange-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
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
    if (($device_id != "PAR" && $device_id != "ESR") || ($device_id != 0)) {
        echo CHtml::link('Download Sample CSV To Upload', CHelper::createUrl("/syslogserveripschange/DownloadSampleFileCircle"));
    }
    ?>
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
<div class="form">
    <div class="row">
        <?php echo $form->labelEx($model, 'file_name'); ?>
        <?php echo $form->fileField($model, 'file_name'); ?>
        <?php echo $form->error($model, 'file_name'); ?>
    </div>
    <div class="row" style="margin-top: 10px;">
        <?php echo $form->labelEx($model, 'eusername'); ?>
        <?php echo $form->textField($model, 'eusername', array()); ?>
        <?php echo $form->error($model, 'eusername'); ?>
    </div>    
    <div class="row" style="margin-top: 10px;">
        <?php echo $form->labelEx($model, 'epassword'); ?>
        <?php echo $form->passwordField($model, 'epassword', array()); ?>
        <?php echo $form->error($model, 'epassword'); ?>
    </div>    
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Upload' : 'Save', array('onClick' => '$(\'div.custom-loader\').show();')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div> 
<?php 
Yii::app()->clientScript->registerScript('search', " 
   $('#Syslogserveripschange_device_type').change(function()
    {   
    var x=$('#Syslogserveripschange_device_type').val();   
    if(x === 'ESR' || x === 'PAR') {
     document.location='" . CHelper::createUrl("syslogserveripschange/UploadIps") . "?device_id='+x;   
        }  
        else
        {
           document.location='" . CHelper::createUrl("syslogserveripschange/uploadCoreDevices") . "?device_id='+x;     
        } 
    }); 
");
?>             