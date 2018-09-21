<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('timesheetreports'),
);
//$this->menu = array(
//    array('label' => 'DayComment', 'url' => array('index')),
//    array('label' => 'View My Status', 'url' => array('admin')),
//);
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->baseUrl ."/css/jquery.dataTables.min.css");
$cs->registerCssFile(Yii::app()->baseUrl ."/css/buttons.dataTables.min.css");
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");


?> 
<h1>Timesheet Report</h1> 
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

<div class="row span10">
<table class="table table-bordered responsive" id="projectreports">
    <thead>
        <tr>
            <th>Day</th>
            <th>Program Name</th>
            <th>Project Name</th>
            <th>Task ID</th>
            <th>Task Title</th>
            <th>Task Description</th>
            <th>Sub Task Id</th> 
            <th>Sub Task Name</th>
            <th>Estimated Hours</th>
            <th>User name</th>
            <th>Comment</th>
            <th>Utilized Hours</th>
        </tr>
    </thead>

    <tbody>
        <?php if(!empty($records)){ ?>
    
            <?php foreach($records as $record){ ?>
            <tr>
                <td><?php echo $record['day']; ?></td>
                <td><?php echo $record['program_name']; ?></td>
                <td><?php echo $record['project_name']; ?></td>
                <td><?php echo $record['project_task_id']; ?></td>
                <td><?php echo $record['task_title']; ?></td>
                <td><?php echo $record['task_description']; ?></td>
                <td><?php echo $record['sub_task_id']; ?></td>
                <td><?php echo $record['sub_task_name']; ?></td>
                <td><?php echo $record['est_hrs']; ?></td>
                <td><?php echo $record['username']; ?></td> 
                <td><?php echo $record['comment']; ?></td>
                <td><?php echo $record['hours']; ?></td>

            </tr>

            <?php } ?>
        <?php } ?>
    </tbody>
</table>

</div>

    <!-- <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/dataTables.buttons.min.js"></script>
    <!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script> -->
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jszip.min.js"></script>

    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/buttons.html5.min.js"></script>
    


<script type="text/javascript">
    $(document).ready(function() {
        $('#projectreports').DataTable( {
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'copy','excel'
            ]
        } );

        $('.datepicker').datepicker({
             dateFormat: 'yy-m-d',
             onSelect: function(dateText) {
                var type = $(this).attr('id');
                var date = $(this).val();
              },
            }).attr('readonly','readonly');
    } );
</script>