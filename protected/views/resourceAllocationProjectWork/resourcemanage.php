<?php
// Page: site summary page
// Developed on: 15/10/2014
// Developed by: Gorakh Wagh
// Purpose: Show graphical representation of sites competed data
?>
<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Resource Allocation Project Works',
);

$this->menu = array(
//    array('label' => 'Resource Arrangement', 'url' => array('resourcearrangement')),
);

$project = isset($_POST['ProjectName']) ? $_POST['ProjectName'] : 0;
$employee = isset($_POST['employee']) ? $_POST['employee'] : 0;
?>

<div class="form-group clearfix test-panal" style="margin-top: 20px;">
    <h4>Resource-Projects Statistics</h4>    
    <form class="form-inline no-mr clearfix" action="" method="post" id="graphFiltersForm">

        <div class="row"> 
            <?php echo CHTML::label('Project Name', ''); ?>
            <?php echo CHTML::dropDownList('ProjectName', $project, CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please select Project'));
            ?>		 
        </div>

        <div class="row"> 
            <?php echo CHTML::label('Employee', ''); ?>
            <?php
            $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1'));
            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
            }
            echo CHTML::dropDownList('employee', $employee, $emp_list, array('prompt' => 'Please select Emlployee')); 
            ?>		 
        </div>


    </form>
    <div class="hr-line"> <span class=""></span></div>

    <div  id="dynGraphs">
<?php
//CHelper::debug((int)$nddUtility);
$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
    'scripts' => array(
        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
        'themes/grid-light'        // applies global 'grid' theme to all charts
    ),
    'options' => array(
        'colors' => array("#aaeeee", "#7798BF", "#f7a35c", "#90ee7e", "#eeaaee", "#55BF3B", "#f45b5b", "#7cb5ec", "#FF00FF", "#FF0000"),
        'chart' => array(
            //'plotBackgroundColor' => '#EFF6F7', 
            'height' => 400,
            'width' => 900,
            'type' => 'pie',
            'option3d' => array('enabled' => true, 'alpha' => 45),
        ),
        'title' => true,
        'cursor' => 'pointer',
        'plotOptions' => array('pie' => array('innerSize' => 100, 'depth' => 45)),
        'series' => array(
            array(
                'name' => 'Resources Work Status',
                'data' => $arraydata,
                'dataLabels' => array(
                    'enabled' => true,
                ),
                'showInLegend' => true,
                'size' => '30',
                'center' => [450, 150],
            ),
        ),
    )
));
?>

    </div>



</div>

<?php
Yii::app()->clientScript->registerScript('ResourceManage', "
  
  $('#ProjectName').change(function(){  
  
    $('#employee').val('');  
    $('#graphFiltersForm').submit();
  
  
});

 $('#employee').change(function(){  
  
    $('#ProjectName').val('');  
    $('#graphFiltersForm').submit();
  
  
});

    ", CClientScript::POS_READY);
?>





