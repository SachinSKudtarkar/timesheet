<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

//$this->breadcrumbs = array(
//    'Day Comments' => array('index'),
//    'Manage',
//);
//$this->menu = array(
//    array('label' => 'DayComment', 'url' => array('index')),
//    array('label' => 'View My Status', 'url' => array('admin')),
//);
?>

<h1>Manage Day Comments</h1> 
<!--<form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
    <div style="position: relative; ">    
        <table>
            <tr>
                <th><label><b>From</b></label></th>
                <th><label><b>To</b></label></th>
                <th width="40px;"></th>
                <th><label><b>Program</b></label></th> 
                <th><label><b>Employee</b></label></th>
                <th><label><b>Project</b></label></th>
                <th></th>
            </tr>    
            <tr>
                <td>
                    <?php
//                    $fromForTextbox = "";
//                    if (!empty($model->from)) {
//                        $fromForTextbox = date('d-m-Y', strtotime($model->from));
//                    }
//                    echo CHtml::textField('from_date', $fromForTextbox, array('id' => 'from_date', 'class' => 'datepicker', 'placeholder' => 'From Date', 'style' => 'width:100px;'));
                    ?>
                </td>
                <td>
                    <?php
//                    $toForTextbox = "";
//                    if (!empty($model->to)) {
//                        $toForTextbox = date('d-m-Y', strtotime($model->to));
//                    }
//                    echo CHtml::textField('to_date', $toForTextbox, array('id' => 'to_date', 'class' => 'datepicker', 'placeholder' => 'To Date', 'style' => 'width:100px;'));
                    ?>
                </td>   
                <td width="40px;"></td>
                <td id="project_name">
                    <?php // echo CHTML::dropDownList('ProjectName', $model->pid, CHtml::listData(ProjectManagement::model()->findAll(array('order' => 'project_name', 'condition' => 'is_deleted=0')), 'pid', 'project_name'), array('prompt' => 'Please select Project'));
                    ?>  
                </td>
                <td id="employee">
                     <?php
//                     $emp_list = Employee::model()->getEmloyeeList();
//                     echo CHTML::dropDownList('employee', $model->emp_id, $emp_list, array('prompt' => 'Please select Emlployee'));
                    ?>	
                </td> 
                
                <td>
                    <?php // echo CHTML::dropDownList('Task_Name', $model->pid, CHtml::listData(SubProject::model()->findAll(array('order' => 'spid', 'condition' => 'is_deleted=0')), 'sub_project_name', 'sub_project_name'), array('prompt' => 'Please select Task'));
                    ?>	
                </td>

                <td align="left"><?php
//                    echo CHtml::button('Submit', array('name' => "searchButton", 'id' => "searchButton", 'class' => 'btn-primary', 'style' => 'margin-left:10px; margin-bottom:8px;'));
                    
                    ?></td>
            </tr>
        </table>
            <script type="text/javascript">
        // $('#project_name').change(function(){
        $(document).on('change','#project_name',function(){
         var project_id =   $(this).find(":selected").val()

        $.ajax({
                type: 'GET',
                url: BASE_URL + '/DayComment/getEmployeeList',
                dataType: 'json',
                data: {
                    project_id: project_id,
                },
                async: false,
                success: function (data) {
                    if (data) {
                      $('#employee').html(data);
                    }
                },
                error: function (data) { // if error occured
                    alert("Error occured.please try again");
                },
            });
});
    </script> 
    </div> 
  
</form>  -->

<?php 

  
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    // 'dataProvider' => $model->searchAll(),
   'dataProvider' => new CArrayDataProvider($data, array()),
    'filter' => $model,
    'columns' => array(
        //'pid',
        array(
            'name' => 'day',
            'value' => array($model, 'getFormatedDate'),
            'type' => 'raw',
        ),
        'project_name', 
        'sub_project_name',
        'task_name',
        array(
            'name' => 'name',
        ),
  
        array(
            'name' => 'comment',
            'filter' => FALSE,
        )
        ,
        array(
            'name' => 'hours',
            'filter' => FALSE,
        )
        ,
//         'comment',
//        'hours', 
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
//Yii::app()->clientScript->registerScript('search', "
//    $('#searchButton').click(function()
//    { 
//            var from_date = $('#from_date').val();
//            var to_date = $('#to_date').val();
//            var employee = $('#employee').val();
//            var ProjectName = $('#ProjectName').val();
//            var Task_Name = $('#Task_Name').val();
//            if(($('#from_date').val() != '' && $('#to_date').val() != '') || (employee!='') || (ProjectName!='')|| (Task_Name!=''))
//            {
//                var farr = $('#from_date').val().split('-');
//                var tarr = $('#to_date').val().split('-');
//                var fromDate= new Date(farr[2] , farr[1] , farr[0]);
//                var toDate = new Date(tarr[2], tarr[1], tarr[0]);
//                if (fromDate > toDate) 
//                {
//                   alert('To date should be greater than from date');
//                   return false;
//                }       
//                var from_date=$('#from_date').val();
//                var to_date=$('#to_date').val(); 
//                    window.location.replace('" . CHelper::createUrl() . "/adminAll/from/'+ from_date +'/to/' + to_date +'/employee/' + employee+'/ProjectName/' + ProjectName+'/Task_Name/' + Task_Name); 
//                $('div.custom-loader').hide();  
//            }else
//            {
//                 window.location.replace('" . CHelper::createUrl() . "/adminAll'); 
//                $('div.custom-loader').hide();  
//                }
//    }); 
//");
 
Yii::app()->clientScript->registerScript('filters', " 
     $('.datepicker').datepicker({
        dateFormat: 'd-m-yy', 
        maxDate:0, 
    }).attr('readonly','readonly');
       ", CClientScript::POS_READY);
?>   