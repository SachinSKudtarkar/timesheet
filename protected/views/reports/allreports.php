<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('allreports'),
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

<h1>Manage Day Comments</h1> 
<?php 
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'filter_date',
    ));
?>
<div class="row span5" style="margin-left: 25%">
    <table class="table table-bordered">
        <tr>
            <td><h6><strong>Filter By Date</strong></h6></td>
            <td>
                <?php echo CHtml::label('From ',''); ?>

                <?php echo CHtml::textField('from_date', $_POST['from_date'], array('id' => 'from_date', "name" => "from_date", "class" => "st_inception_date datepicker", 'required'=>true)); ?>
                
            </td>
            <td>
                <?php echo CHtml::label('To ',''); ?>

                <?php echo CHtml::textField('to_date', $_POST['to_date'], array('id' => 'to_date', "name" => "to_date", "class" => "st_inception_date datepicker", 'required'=>true)); ?>
                
            </td>
            <td width="5%">
                <?php echo CHtml::submitButton('Search', array('id' => 'search','name'=> 'submit','style'=>'margin-left:0px;margin-top:10px'));?>    
                <?php echo CHtml::Button('Reset', array('id' => 'reset','name'=> 'reset','style'=>'margin-left:0px;margin-top:5px','onclick'=>'window.location.href=window.location.href'));?>    
            </td>

        </tr>
    </table>
</div>
<?php $this->endWidget(); ?>
<div class="row span12">
<?php  
  
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    // 'dataProvider' => $model->searchAll(),
   'dataProvider' => new CArrayDataProvider($data, array()),
    'filter' => $model,
    'columns' => array(
        //'pid',

        array(
            'header' => 'Program ID',
            'name' => 'program_id'
        ),
        array(
            'header' => 'Program Name',
            'name' => 'program_name',
        ),
        array(
            'header' => 'Project ID',
            'name' => 'project_id',

        ),
        array(
            'header' => 'Project Name',
            'name' => 'project_name',

        ),
        array(
            'header' => 'Allocated Hrs',
            'name' => 'allocated',     
        ),
        array(
            'header' => 'Task Type',
            'name' => 'task_name',

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
            'header' => 'Utilized Hrs',
            'name' => 'utilized_hours',
        ),
        array(
            'header' => 'Created By',
            'name' => 'created_by',
        ),
        array(
            'header' => 'Created At',
            'name' => 'created_at',
        ),
         
    ),
));
 
echo $this->renderExportGridButton_Report($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20','style'=> 'position:absolute;top:45%;'));
 
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