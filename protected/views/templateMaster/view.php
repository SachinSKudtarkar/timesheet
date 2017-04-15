<?php
/* @var $this TemplateMasterController */
/* @var $model TemplateMaster */

$this->breadcrumbs = array(
    'Template Masters' => array('index'),
    $model->name,
);

//$this->menu = array(
//    array('label' => 'List TemplateMaster', 'url' => array('index')),
//    array('label' => 'Create TemplateMaster', 'url' => array('create')),
//    array('label' => 'Update TemplateMaster', 'url' => array('update', 'id' => $model->id)),
//    array('label' => 'Delete TemplateMaster', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
//    array('label' => 'Manage TemplateMaster', 'url' => array('admin')),
//);
?>

<h1>View Template Master #<?php echo $model->id; ?></h1>
<h5><a href="<?php echo yii::app()->baseUrl . '/templatemaster/create' ?>">Create New BOM Template</a></h5>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        'file_id',
        array(
            'name' => 'file_name',
            'type' => 'raw',
            'value' => $this->getBomFileDownloadLink($model),
        ),
        array(
            'name' => 'created_by',
            'type' => 'raw',
            'value' => Employee::model()->getUserFullNameById($model->created_by),
        ),
        'date_created',
    ),
));
?>
