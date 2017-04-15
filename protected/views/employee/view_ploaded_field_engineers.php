<h5>Total <?= count($rowsUploaded); ?> Field Engineers Added/Updated.</h5>
<?php
if (count($rowsNotUploaded) > 1) {
    echo "<a style='color:red' href='javascript:void(0)' class='downloadFailedData'>" . (count($rowsNotUploaded) - 1) . " Records faild to upload, Click here to download the entries which were not added.</a>";
}
?>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'field-engineer-grid',
    'dataProvider' => $model->viewUploadedFieldEngineers($rowsUploaded),
    //'filter' => $model,
    'columns' => array(
        'first_name',
        'last_name',
        'mobile',
    ),
));
?>

<?php
Yii::app()->clientScript->registerScript('filters', "
                
    $('.downloadFailedData').live('click',function(){
        event.preventDefault();
        var newForm = jQuery('<form>', {
            'action': 'GetFailedEntriesOfFieldEngineers',
            'target': '_blank',
            'method': 'POST'
        }).append(jQuery('<input>', {
            'name': 'data',
            'value': '" . serialize($rowsNotUploaded) . "',
            'type': 'hidden'
        })).append(jQuery('<input>', {
            'name': '" . Yii::app()->request->csrfTokenName . "',
            'value': '" . Yii::app()->request->csrfToken . "',
            'type': 'hidden'
        }));
        newForm.submit();
    });

    ", CClientScript::POS_END);
?>
