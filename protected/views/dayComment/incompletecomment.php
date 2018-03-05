<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Day Comments' => array('index'),
    'Manage',
);
$this->menu = array(
    array('label' => 'DayComment', 'url' => array('index')),
    array('label' => 'View My Status', 'url' => array('admin')),
);
?>

<h1>Day Comments not filled</h1> 
<form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
    <div style="position: relative; ">    
        <table>
            <tr>
                <td><label><b>From</b></label></td>
                <td><label><b>To</b></label></td>
                <td><label><b>Total Days</b></label></td>
                <td width="40px;"></td>
                <td><label><b>Project</b></label></td> 
                <td></td>
            </tr>    
            <tr>
                <td>
                    <?php
                    $fromForTextbox = "";
                    if (!empty($model->from)) {
                        $fromForTextbox = date('d-m-Y', strtotime($model->from));
                    }
                    echo CHtml::textField('from_date', $fromForTextbox, array('id' => 'from_date', 'class' => 'datepicker', 'placeholder' => 'From Date', 'style' => 'width:100px;'));
                    ?>
                </td>
                <td>
                    <?php
                    $toForTextbox = "";
                    if (!empty($model->to)) {
                        $toForTextbox = date('d-m-Y', strtotime($model->to));
                    }
                    echo CHtml::textField('to_date', $toForTextbox, array('id' => 'to_date', 'class' => 'datepicker', 'placeholder' => 'To Date', 'style' => 'width:100px;'));
                    ?>
                </td> 
                <td>
                    <?php
                    $dateTextbox = "";
                        $date1 = date_create($model->from);
                        $date2 = date_create($model->to);
                        $diff  = date_diff($date1, $date2);
                        //$model->range_days = ((int) $diff->format("%a")) + 1;

                    echo $model->to;
                    ?>
                </td>   
                <td width="40px;"></td> 
                <td>
                    <?php echo CHTML::dropDownList('ProjectName', $model->pid, CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please select Project'));
                    ?>	
                </td>
                <td align="left"><?php
                    echo CHtml::button('Submit', array('name' => "searchButton", 'id' => "searchButton", 'class' => 'btn-primary', 'style' => 'margin-left:10px; margin-bottom:8px;'));
                    
                    ?></td>
            </tr>
        </table>
    </div>    
</form>  

<?php 
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    'dataProvider' => $model->searchStatusIncomplete(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'name',
            'filter' => FALSE,
        ),
//        array(
//            'name' => 'day',
//            'value' => array($model, 'getFormatedDate'),
//            'type' => 'raw',
//            'filter' => false
//        ),
        'range_days',
        'days_filled',
        'dates_filled',
        'total_hours', 
    ),
));
 
echo $this->renderExportGridButton($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));
 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
Yii::app()->clientScript->registerScript('search', "
    $('#searchButton').click(function()
    { 
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var ProjectName = $('#ProjectName').val();
        if(($('#from_date').val() != '' && $('#to_date').val() != '') || (ProjectName!=''))
        {
            var farr = $('#from_date').val().split('-');
            var tarr = $('#to_date').val().split('-');
            var fromDate= new Date(farr[2] , farr[1] , farr[0]);
            var toDate = new Date(tarr[2], tarr[1], tarr[0]);
            if (fromDate > toDate) 
            {
               alert('To date should be greater than from date');
               return false;
            }       
            var from_date=$('#from_date').val();
            var to_date=$('#to_date').val(); 
                window.location.replace('" . CHelper::createUrl() . "/timeSheetNotFilled/from/'+ from_date +'/to/' + to_date +'/ProjectName/' + ProjectName); 
            $('div.custom-loader').hide();  
        } else {
            window.location.replace('" . CHelper::createUrl() . "/timeSheetNotFilled'); 
            $('div.custom-loader').hide();  
        }
    }); 
");
 
Yii::app()->clientScript->registerScript('filters', " 
     $('.datepicker').datepicker({
        dateFormat: 'd-m-yy', 
        maxDate:0, 
    }).attr('readonly','readonly');
       ", CClientScript::POS_READY);
?>   