<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('allreports'),
);

$date = date('Y-m-d');
?>

<link href="/timesheet/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="/timesheet/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<h1>Weekly Reports</h1>

<div class="row span11" style="margin-bottom: 100px">

<?php  if(Yii::app()->session['login']['user_id'] == 3616 || Yii::app()->session['login']['user_id'] == 6) { ?>
    
<table id="weeklyreport" class="display table table-bordered">
    <thead>
        <tr>
            <th>Program Name</th>
            <th>Project Name</th>
            <th>Estimated Hours</th>
            <th>Utilized Hours</th>
            <th><?php echo date('Y-m-d'); ?></th>
            <th><?php echo date('Y-m-d', strtotime('-1 day', strtotime($date))); ?></th>
            <th><?php echo date('Y-m-d', strtotime('-2 day', strtotime($date))); ?></th>
            <th><?php echo date('Y-m-d', strtotime('-3 day', strtotime($date))); ?></th>
            <th><?php echo date('Y-m-d', strtotime('-4 day', strtotime($date))); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($result as $row) { ?>
            <tr>
                <td><?php echo $row['ProgramName']; ?></td>
                <td><?php echo $row['ProjectName']; ?></td>
                <td><?php echo $row['estimated_hrs']; ?></td>
                <td><?php echo $row['utilized_hrs']; ?></td>
                <td><?php echo $row['today']; ?></td>
                <td><?php echo $row['today_1']; ?></td>
                <td><?php echo $row['today_2']; ?></td>
                <td><?php echo $row['today_3']; ?></td>
                <td><?php echo $row['today_4']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>       

<script src="/timesheet/js/jquery.dataTables.min.js"></script>
<script src="/timesheet/js/dataTables.buttons.min.js"></script>
<script src="/timesheet/js/buttons.flash.min.js"></script>
<script src="/timesheet/js/jszip.min.js"></script>
<script src="/timesheet/js/buttons.html5.min.js"></script>
<script>
    $(document).ready( function () {
        $('#weeklyreport').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        });
    } );
</script>

<?php }else { ?>
    <h1> You are not authorized to access the page.</h1>
<?php } ?>
