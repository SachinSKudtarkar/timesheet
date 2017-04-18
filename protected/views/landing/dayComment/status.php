<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs=array(
	'Day Comments'=>array('index'),
	'Manage',
);
$this->menu=array( 
    
    array('label'=>'DayComment', 'url'=>array('index')),
     array('label'=>'View My Status', 'url'=>array('admin')), 
); 
?> 
<h1>Daily Status Not Submitted</h1>  
<form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
    <div style="position: relative; ">    
        <table>
            <tr>
                <td><label><b>From</b></label></td>
                <td><label><b>To</b></label></td>
                <td width="40px;"></td>
                <td><label><b>Employee</b></label></td> 
                <td></td>
            </tr>    
            <tr>
                <td>
                    <?php
                    $fromForTextbox = "";
                    if (!empty($data['from'])) {
                        $fromForTextbox = date('d-m-Y', strtotime($data['from']));
                    }
                    echo CHtml::textField('from_date', $fromForTextbox, array('id' => 'from_date', 'class' => 'datepicker', 'placeholder' => 'From Date', 'style' => 'width:100px;'));
                    ?>
                </td>
                <td>
                    <?php
                    $toForTextbox = "";
                    if (!empty($data['to'])) {
                        $toForTextbox = date('d-m-Y', strtotime($data['to']));
                    }
                    echo CHtml::textField('to_date', $toForTextbox, array('id' => 'to_date', 'class' => 'datepicker', 'placeholder' => 'To Date', 'style' => 'width:100px;'));
                    ?>
                </td>   
                <td width="40px;"></td>
                <td>
                    <?php 
                    $emp_list = Employee::model()->getEmloyeeList();
                    echo CHTML::dropDownList('employee', $data['employee'], $emp_list, array('prompt' => 'Please select Emlployee'));
                    ?>	
                </td> 
                <td align="left"><?php
                    echo CHtml::button('Submit', array('name' => "searchButton", 'id' => "searchButton", 'class' => 'btn-primary', 'style' => 'margin-left:10px; margin-bottom:8px;'));
                    //echo CHtml::hiddenField("ajax", '1');
                    ?></td>
            </tr>
        </table>
    </div>    
</form>  
<?php 
$ex = $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'day-comment-grid',
	'dataProvider'=>$dataProvider, 
	'columns'=>array( 
               'name',  
            'date'
	),
));
 $this->renderExportGridButton($ex, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));  
?>
<?php
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
            var employee = $('#employee').val(); 
            if(($('#from_date').val() != '' && $('#to_date').val() != '') || (employee != ''))
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
                    window.location.replace('" . CHelper::createUrl() . "/StatusReport/from/'+ from_date +'/to/' + to_date +'/employee/' + employee); 
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