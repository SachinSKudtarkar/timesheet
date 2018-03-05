<!-- The file upload form used as target for the file upload widget -->

<?php
if ($this->showForm)
    echo CHtml::beginForm($this->url, 'post', $this->htmlOptions);
?>

<div class="container">
    <h5 style="float :center">Upload Old Log File</h5>
    <div class="row">
        <div class='col-sm-6'>

            <label>Plan Date & Time:</label>
            <input type='text' class="datepicker" id='planned_date'/>        
        </div>
        <script type="text/javascript">
            $(function() {
                $('.datepicker').datetimepicker({
                    hourGrid: 4,
                    minuteGrid: 15,
                    minDate: 0, }).attr('readonly','readonly');
            });
        </script>
    </div>
</div>
<div class="row fileupload-buttonbar">
    <div class="span7">
        <span class="btn btn-success fileinput-button" style="float: right">
            <i class="icon-plus icon-white" style="float:right"></i>
            <span><?php echo $this->t('1#Choose file|0#Add Files', $this->multiple); ?></span>
            <?php
            if ($this->hasModel()) :
                echo CHtml::activeFileField($this->model, $this->attribute, $htmlOptions) . "\n";
            else :
                echo CHtml::fileField($name, $this->value, $htmlOptions) . "\n";
            endif;
            ?>
        </span>
        <input type="hidden" name="row_id" id="row_id" value=""/>
        <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>">

    </div>
    <h5 style="float :left;color: #D60000;">Use File Extension .txt type only.</h5>
    <div class="span5">
        <!-- The global progress bar -->
        <div class="progress progress-success progress-striped active fade">
            <div class="bar" style="width:0%;"></div>
        </div>
    </div>
</div>
<!-- The loading indicator is shown during image processing -->
<div class="fileupload-loading"></div>
<br>
<!-- The table listing the files available for upload/download -->
<table class="table table-striped log_upload">
    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
</table>
<?php if ($this->showForm) echo CHtml::endForm(); ?>
    