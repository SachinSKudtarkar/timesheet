<?php
/* @var $this CaptureImageController */
$this->breadcrumbs=array(
	'Capture Image',
);?>

<style>
.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xs,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}
.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xs,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{padding-right:15px;padding-left:15px}
@media (min-width:992px){.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xs,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{padding-right:15px;padding-left:15px}}
@media (min-width:768px){.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xs,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{padding-right:15px;padding-left:15px}}
@media (min-width:1200px){.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xs,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{padding-right:15px;padding-left:15px}}
.col-xs-1 {
    float: left;
    width: 8.333333%
}
.col-md-2 {
    float: left;
    width: 16.666667%
}
.col-md-3 {
    float: left;
    width: 25%
}

.col-md-4 {
    float: left;
    width: 33.333333%
}

.col-md-5 {
    float: left;
    width: 41.666667%
}
.col-md-6 {
    float: left;
    width: 50%
}
.col-md-7 {
    float: left;
    width: 58.333333%
}
.col-md-8 {
    float: left;
    width: 66.666667%
}
.col-md-9 {
    float: left;
    width: 75%
}
.col-md-10 {
    float: left;
    width: 83.333333%
}
.col-md-11 {
    float: left;
    width: 91.666667%
}
.col-md-12 {
    float: left;
    width: 100%
}
.paddingt{padding-top:20px;}
.expectedOuttime{
    padding-bottom: 10px;
}
div#dialogSiteForm.ui-dialog-content.ui-widget-content
{
    padding: .5em 2em !important;
}
</style>
<?php  
    $cs=Yii::app()->clientScript;
    $cs->registerCSSFile('themes/cisco/css/bootstrap-responsive.css');
?>
<?php 
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'update-capture-image',
        'enableAjaxValidation'=>false,
        'action' => Yii::app()->createUrl('/captureimage/create'),
    ));         


?>
    <div class="row">
        <div class="col-md-8">
            <?php
            $model = new TblCaptureImg();
            $row = $model->GetTodayUSerrecord(); 

            $user_record = $model->Getloginrecord(); 

            if(empty($row))
            {   echo CHtml::dropDownList('listname',$model->genderOptions, array('0' => 'IN TIME')); }
            else 
            { 
                if($row['in_time']=='00:00:00')
                {
                    echo CHtml::dropDownList('listname',$model->genderOptions, array('0' => 'IN TIME')); 
                }
                if($row['out_time']=='00:00:00')
                {
                    $expected_OutTime = date("Y-m-d H:i:s", strtotime('+9 hours', strtotime($row['todaydate'].$row['in_time'])));
                    echo "<div class='expectedOuttime'>Expected Out Time : {$expected_OutTime}</div>";
                    //echo CHtml::label('Expected Out Time : ', $expected_OutTime);
                    echo CHtml::dropDownList('listname', 
                    $model->genderOptions, array('1' => 'OUT TIME')); 
                }
            }
            ?>            
        </div>            
    </div>
    <div class="row">       
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-10">
                    <video id="player" width="350" height="300" controls autoplay></video>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <a id="capture" href="#" class="btn-primary btn" data-intime = '<?php echo $row['in_time'];?>'
                     data-user-timesheet ='<?php echo $user_record['is_timesheet'];?>'>Capture</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-10 paddingt">
                    <canvas id="snapshot" width=320 height=260 style="border:1px solid #000;"></canvas>
                </div>
            </div>
            <!--<button id="capture">Save</button>-->
            <div class="row">
                <div class="col-md-10 paddingt" >
                    <input type="hidden" name="capture_img" value="" id="imageid"/>
                    <?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType'=>'submit',
                            'label'=>'Submit',                           
                            'htmlOptions'=>array('class'=>'btn-primary','id'=>'submitBtn','disabled'=>'disabled'),
                        ));                     
                     ?> 
                 </div>
            </div>
        </div>   
    </div>
 <?php $this->endWidget(); ?>
 <div class="row"></div>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
    'id' => 'dialogSiteForm',
    'options' => array(
        'title' => 'Alert!!',
        'autoOpen' => false,
        'modal' => true
    ),
));
?>
<div class="siteInfoFormContainer">

</div>
<?php $this->endWidget(); ?>

<script>
    var player = document.getElementById('player'); 
    var snapshotCanvas = document.getElementById('snapshot');
    var captureButton = document.getElementById('capture');
    var submitButton = document.getElementById('submitBtn');
    var img='';
    var handleSuccess = function(stream) {
        // Attach the video stream to the video element and autoplay.
        player.srcObject = stream;
    };
    captureButton.addEventListener('click', function() {

      var intime = $(this).data("intime");
      var is_timesheet = $(this).data("user-timesheet");

      if(is_timesheet == 0 && intime != "" )
      {

           $('#dialogSiteForm').dialog('close');
            $('#dialogSiteForm').dialog('open');
            var form_data = $("#update-capture-image").serialize();
             jQuery.ajax({
                url: "<?php echo Yii::app()->createUrl('/captureimage/create') ?>",
                type: 'post',
                dataType: 'json',
                data: form_data,
                success: function (data)
                {
                    if (data.status == 'failure')
                    { 
                        $('#dialogSiteForm div.siteInfoFormContainer').html(data.html);
                    }
                    else {
                        $('#dialogSiteForm').dialog('close');
                    }

                }
            });
            
      }
      else
      {

        var context = snapshot.getContext('2d');
        // Draw the video frame to the canvas.
        context.drawImage(player, 0, 0, snapshotCanvas.width,snapshotCanvas.height);
        var date = new Date();
        var n = date.toDateString();
        var time = date.toLocaleTimeString();
        context.fillText(n + ' ' + time, 20, 20);
        img    = snapshotCanvas.toDataURL("image/png");
        document.getElementById('imageid').value=img;
        submitButton.removeAttribute("disabled");//Enable Submit Button if image captured.
        
      }

    });
    navigator.mediaDevices.getUserMedia({video: true}).then(handleSuccess);
</script>