<!DOCTYPE html>
<!--<html class="no-js"> <![endif]-->
    <head>
<!--        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TimeSheet Calculator</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">-->
        <?php
        /* @var $this DayCommentController */
        /* @var $dataProvider CActiveDataProvider */

        $this->breadcrumbs = array(
            'Day Comments',
        );

        $this->menu = array(
            array('label' => 'DayComment', 'url' => array('index')),
            array('label' => 'View My Status', 'url' => array('admin')),
        );

        Yii::app()->clientScript->registerCoreScript('jquery.ui');

        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
        $cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
//        $cs->registerCssFile(Yii::app()->baseUrl . "/css/style.css");

        Yii::app()->clientScript->registerCssFile(
                Yii::app()->clientScript->getCoreScriptUrl() .
                '/jui/css/base/jquery-ui.css'
        );
        
        $emp_id = Yii::app()->session['login']['user_id'];
        
        
        
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'day-comment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
//    'clientOptions'=>array(
//        'validateOnSubmit'=>true,
//        'afterValidate'=>'js:yiiFix.ajaxSubmit.afterValidate'
//    )
)); 
//        CHelper::debug($allProjects);
        ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link  rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">


    </head>
    <body>


<!--        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                     <a class="brand" href="#">Log Yo Times, Foo!</a> 
                    <div class="nav-collapse collapse">

                    </div>/.nav-collapse 
                </div>
            </div>
        </div>-->

        <div class="info well well-large">
            <h1>Time Sheet Calculator</h1>
            <p>Fill in the table on the left with the start and end times of a task, and the task being completed. Then click <code>submit</code>, which will sum the hours and place them in the proceeding table.</p>
        </div>


        <div id="centerContainer">
            <div class="info well-large cloneclass">
                <!-- Example row of columns -->
                <div class="row">
                    <div class="block-left ">
                       <form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addcomment" method="post" id="addcommentfrm">
    <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />
    
            <?php $this->widget('ext.yiicalendar.YiiCalendar', array
        (
            'dataProvider'=>array
            (
                'pagination'=>array
                (
                    'pageSize'=>'week',
                    'isMondayFirst'=>TRUE
                )
              )
        )); 
            ?>
  

    


</p>
    
                            <table  class="table table-hover small-text" id="tb">
                                
                                <thead>
                                    <tr class="tr-header">
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Program</th>
                                        <th>Project</th>
                                        <th>Task</th>
                                        <th>Additional Notes</th>
                                        <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" title="Add More Person"><span class="glyphicon glyphicon-plus"></span></a></th>
                                    </tr>
                                </thead>
                                <tbody id="hoursBody">
                                    <tr>
                                        <td><input type="text" placeholder="HH:MM" name ="start[]" class="form-control"></td>
                                        <td><input type="text" placeholder="HH:MM" name ="end[]" class="form-control"></td>
                                        <td class="Program">
                                            <?php 
                                            
                                            echo CHTML::dropDownList('ProjectName[]','pid', CommonUtility::getProject_byEmp_id($emp_id), array('style' => 'width:150px;', 'prompt' => 'Please select Program', 'class' => 'form-control')); ?>
                                            <!--<input  type="text" placeholder="Select Program" name ="Program[]" class="form-control">-->
                                        
                                        </td>
                                        
								
								
							
                                        <td class="Project"><input type="text" placeholder="Select Project" name ="Project[]" class="form-control"></td>
                                        <td class="Task"><input  type="text" placeholder="Select Task" name ="Task[]"class="form-control"</td>
                                        <td class="Comment"><input  type="text" placeholder="Please right comment here" name ="Comment[]"class="form-control"></td>
                                        <td><a href='javascript:void(0);'  class='remove'><span class='glyphicon glyphicon-remove'></span></a></td>
                                    </tr>

                                   
                                </tbody>
                            </table>
                            <br />
                             <div class="row">
		<?php
			
				echo CHtml::submitButton('Save', array('id' => 'addC', 'style' => 'margin-top:10px;'));
			
			//if(!$is_submitted && ( strtotime(date('Y-m-d')) >= strtotime("saturday this week") && strtotime(date('Y-m-d')) <= strtotime("monday next week 1 pm") ) ) {
				//echo CHtml::submitButton('Submit', array('id' => 'btnSubmit', 'style' => 'margin-top:10px;', 'value'=>'Submit'));
			
		?>
    </div>
                            <!--<button type="button" class="btn span3" id="hrSubmit">Submit</button>-->
                        </form>
                        <!-- <div id="error">This is a test error message.</div> -->
                    </div>


                </div>
            </div> <!-- /container -->
        </div>

<?php $this->endWidget(); ?>
<?php
Yii::app()->clientScript->registerScript('filters', "
  
    $('.datepicker').datepicker({
     dateFormat: 'yy-m-d',    
     onSelect: function(dateText) {
        var type = $(this).attr('id');
        var date = $(this).val();         
      },
    }).attr('readonly','readonly');
  
    
", CClientScript::POS_READY);
?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
<script>
$(function(){
    $('#addMore').on('click', function() {
              var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
              data.find("input").val('');
     });
     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>0) {
             $(this).closest("tr").remove();
           } else {
             alert("Sorry!! Can't remove first row!");
           }
      });
});  

  
//  $(document).ready(function(){
// $('.datepicker').datepicker({
//     dateFormat: 'yy-m-d',    
//     onSelect: function(dateText) {
//        var type = $(this).attr('id');
//        var date = $(this).val();         
//      },
//    }).attr('readonly','readonly');
//  
//  });
</script>



    </body>
<!--</html>-->


