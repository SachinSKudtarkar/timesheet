<div class="form-group clearfix test-panal" style="margin-top: 20px;">
    <h4>Upload Field Engineers Data</h4>    
    <?php
    echo CHtml::link('Download Sample CSV To Upload', CHelper::createUrl("//cisco/DownloadSampleFileNew", array("filename" => "sample_field_engineer_data.csv")));

    $form = $this->beginWidget(
            'CActiveForm', array(
        'id' => 'upload-form',
        'enableAjaxValidation' => true,
        //'action' => Yii::app()->createUrl('sitemaster/UploadBuildSiteDataCSV'),
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )
    );
// ...
    echo $form->labelEx($model, 'file');
    echo $form->fileField($model, 'file');
    echo $form->error($model, 'file');
// ...
    echo CHtml::submitButton('Upload File');
    $this->endWidget();
    ?>



</div>

