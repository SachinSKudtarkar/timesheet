<?php
/* @var $this ManagedayCommentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Day Comments',
);

//$this->menu=array(
//	array('label'=>'Create DayComment', 'url'=>array('create')),
//	array('label'=>'Manage DayComment', 'url'=>array('admin')),
//);
?>

<!--<h1>Day Comments</h1>-->

<?php 
//$this->widget('zii.widgets.CListView', array(
//	'dataProvider'=>$dataProvider,
//	'itemView'=>'_view',
//)); 
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
?>

<form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addcomment" method="post" id="addcommentfrm">
    <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

    <h1>Manage Day Comments</h1>
    <div class="row" >
        <div class="row" >
                                    <div class="span5"> 
                                    <?php echo CHTML::label('Employee', ''); ?>
                                    <?php
                                    $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1'));
                                    $emp_list = array();
                                    foreach ($employeeData as $key => $value) {
                                        $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
                                    }
                                   
                                    echo CHTML::dropDownList('employee', $employee, $emp_list, array('class' => 'employee','prompt' => 'Please select Emlployee')); 
                                    ?>		 
                                    </div>
                                     <div class="span5">
				    <?php
					$selecting_date = "";
					if (isset($_GET['selecting_date'])) {
						$selecting_date = $_GET['selecting_date'];
						$selecting_date = date('Y-m-d', strtotime($selecting_date));
					} else {
						$selecting_date = date('Y-m-d', strtotime("monday this week"));
					}
					echo CHTML::label('Select week :', '', array('style' => 'width:80px; font-weight:bold;'));
				
					for ($mondayCounter = 3; $mondayCounter >= 0; $mondayCounter--) {
						$da = date('d-m-Y', strtotime(" -" . $mondayCounter . "monday this week"));
						$mondayList[$da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));
					}
					
					$da = date('d-m-Y', strtotime("monday next week"));
					$mondayList[$da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));
                                        
                                       // print_r(array_flip($mondayList));exit;
					echo CHtml::dropDownList('selecting_weeks', $mid, $mondayList, array('class' => 'selecting_weeks', 'empty' => 'Select Value', 'options' => array($selecting_date => array('selected' => true))));

					
				    ?>
			             </div>
                <div class="span5">
		
    </div>
        </div>
        
       
        <hr style=" margin-top: 0px; margin-bottom: 3px;"/>
    </div>
  
			<div class="main_daycomments">
			
			</div>
    <div class="row buttons">
       <?php
       echo CHtml::Button('Unlock', array('id' => 'btnS', 'style' => 'margin-top:10px;', 'value'=>'Unlock'));
       ?> 
   </div>
 
   
    <?php
    Yii::app()->clientScript->registerScript('comment', "
        var day = '';
        var empid = '';


    $('.employee').change(function(){  
   var day = $('.selecting_weeks').val();
    var empid = $('.employee').val();
   
    
     $.ajax({
               url: BASE_URL+'/managedaycomment/fetchDaycomments',
               type: 'POST',
               dataType: 'html',
               data:{ empid:empid,day:day},
               beforeSend:function(){
                    $('.custom-loader').show();
                },
                complete:function(){
                   $('.custom-loader').hide();
                },
               success: function(result)
               {
                  $('.main_daycomments').html(result);
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });
	

});
$('.selecting_weeks').change(function(){  
   var day = $('.selecting_weeks').val();
    var empid = $('.employee').val();
   
    
     $.ajax({
               url: BASE_URL+'/managedaycomment/fetchDaycomments',
               type: 'POST',
               dataType: 'html',
               data:{ empid:empid,day:day},
               beforeSend:function(){
                    $('.custom-loader').show();
                },
                complete:function(){
                   $('.custom-loader').hide();
                },
               success: function(result)
               {
                  $('.main_daycomments').html(result);
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });
	

});

$(document).on('click','#btnS', function(){

			 var day = $('.selecting_weeks').val();
                          var empid = $('.employee').val();
$.ajax({
       url: BASE_URL+'/managedaycomment/updateStatus',
                type: 'POST',
                data:{empid:empid,day:day},
                success:function(result){
                console.log(result); 
                
                   $('.main_daycomments').html(result);
                },
                error: function(XMLHttpRequest, data, errorThrown){
               },

 });
 
        });

       ", CClientScript::POS_READY);
    ?>
</form>


