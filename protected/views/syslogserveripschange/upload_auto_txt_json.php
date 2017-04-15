<div class="modal-header">
    <a class="close" style="color:red; opacity: 1" data-dismiss="modal">X</a>
    <h4 class="modal-title" id="myModalLabel">Upload automation CSV File for Syslog</h4>
</div> 
<div class="modal-body">
    <div class="control-group clearfix">
        <label for="lawfirmcsv" class="span5 control-label">Upload CSV:</label>
        <div class="span7">
            <?php
            $device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : 0;  
          
            $this->widget('ext.EAjaxUpload.EAjaxUpload', array(
                'id' => 'uploadAutoZipFile',
                'config' => array(
                    'action' => Yii::app()->createUrl('//syslogserveripschange/autoTxtimport?device_id=' . $device_id),
                    'allowedExtensions' => array("CSV"), //array("jpg","jpeg","gif","exe","mov" and etc...
                    'sizeLimit' => 100 * 1024 * 1024, // maximum file size in bytes
                    //'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
                    'onComplete' => "js:function(id, fileName, responseJSON){  
                                        refreshGrid(id,fileName,responseJSON);  
                                  }",
                    'messages' => array(
                        'typeError' => "{file} has invalid extension. Only {extensions} are allowed.",
                        'sizeError' => "{file} is too large, maximum file size is {sizeLimit}.",
                        //                'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                        'emptyError' => "{file} is empty, please select files again without it.",
                        'onLeave' => "The files are being uploaded, if you leave now the upload will be cancelled."
                    ), 
                    //'success' =>'function(data){ alert(data);}',
                    'showMessage' => "js:function(message){ alert(message);}"
                ),
                    //'success'=>'function(){alert("hello");}'
            ));
            ?>
        </div>
    </div>

    <div class="control-group clearfix">
        <label for="lawfirmcsv" class="span5 control-label" style="width: 100%"><b>Note :</b> <b>Please upload file in given format CSV </b>  </label>
    </div>

</div>

<script type="text/javascript">
    function  refreshGrid(id, fileName, responseJSON) {         
        var url = responseJSON.redirect; 
//        window.location =url;
        window.location.reload();
//         $('.close').trigger('click');    //for close the box
    }
</script>
