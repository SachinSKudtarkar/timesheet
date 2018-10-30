<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('alltimesheet'),
);

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

?>

<h1>Timesheet Reports</h1> 

<?php //$this->renderPartial('_count', array('model'=>$model,'allcount'=>$allcount)); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>


<div class="row span11" style="margin-bottom: 100px;">
<?php  
  
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    // 'dataProvider' => $model->searchAll(),
   'dataProvider' => new CArrayDataProvider($data, array()),
    'filter' => $model,
    'columns' => array(
        //'pid',

        array(
            'header' => 'Day',
            'name' => 'day'
        ),
        array(
            'header' => 'Program Name',
            'name' => 'program_name',
        ),
        array(
            'header' => 'Project Name',
            'name' => 'project_name',

        ),
        array(
            'header' => 'Task ID',
            'name' => 'project_task_id',
        ),
        array(
            'header' => 'Task Title',
            'name' => 'task_title',
        ),
        array(
            'header' => 'Task Description',
            'name' => 'task_description',
        ),
        array(
            'header' => 'Sub Task ID',
            'name' => 'sub_task_id',
        ),
        array(
            'header' => 'Sub Task Name',
            'name' => 'sub_task_name',
        ),
        array(
            'header' => 'Estimated Hrs',
            'name' => 'est_hrs',
        ),
        array(
            'header' => 'Username',
            'name' => 'username',
        ),
        array(
            'header' => 'Comment',
            'name' => 'comment',
        ),
        array(
            'header' => 'Utilized Hrs',
            'name' => 'hours',
        ),
         
    ),
));
 
echo $this->renderExportGridButton_Report($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));
?>
</div>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>

<script type="text/javascript">
    $(document).ready(function() {
        
        $('.datepicker').datepicker({
             dateFormat: 'yy-m-d',
             onSelect: function(dateText) {
                var type = $(this).attr('id');
                var date = $(this).val();
              },
            }).attr('readonly','readonly');
    });
</script>