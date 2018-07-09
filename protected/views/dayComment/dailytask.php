<!DOCTYPE html>
 <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TimeSheet Calculator</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
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
// $cs->registerScriptFile(Yii::app()->baseUrl . "js/prototype.js");
// $cs->registerScriptFile(Yii::app()->baseUrl . "js/timekeep-proto.js");
// $cs->registerScriptFile(Yii::app()->baseUrl . "//www.google.com/jsapi");
// $cs->registerScriptFile(Yii::app()->baseUrl . "js/chartkick.js");
// $cs->registerScriptFile(Yii::app()->baseUrl . "js/vendor/modernizr-2.6.2-respond-1.1.0.min.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
// $cs->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-responsive.min.css");
// $cs->registerCssFile(Yii::app()->baseUrl . "/css/main.css");
// $cs->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap.min.css");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/style.css");

// $cs->registerScriptFile(Yii::app()->baseUrl . "js/vendor/bootstrap.min.js");

// $cs->registerScriptFile(Yii::app()->baseUrl . "js/main.js");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);

?>

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        
    </head>
    <body>


        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <!-- <a class="brand" href="#">Log Yo Times, Foo!</a> -->
                    <div class="nav-collapse collapse">

                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="info well well-large">
            <h1>Time Sheet Calculator</h1>
            <p>Fill in the table on the left with the start and end times of a task, and the task being completed. Then click <code>submit</code>, which will sum the hours and place them in the proceeding table.</p>
        </div>


        <div id="centerContainer">
        <div class="info well-large cloneclass">
            <!-- Example row of columns -->
            <div class="row">
                <div class="block-left ">
                    <form id="timeTable">
                        <table class="span3 table table-striped">
                            <caption>Insert Times Below:</caption>
                            <thead>
                                <tr>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Program</th>
                                    <th>Project</th>
                                    <th>Task</th>
                                    <th>Additional Notes</th>
                                </tr>
                            </thead>
                            <tbody id="hoursBody">
                                <tr>
                                    <td><input class="start" type="text" placeholder="HH:MM"></td>
                                    <td><input class="end" type="text" placeholder="HH:MM"></td>
                                    <td class="Program"><input class="ProgramInput" type="text" placeholder="Select Program"></td>
                                    <td class="Project"><input class="ProjectInput" type="text" placeholder="Select Project"></td>
                                    <td class="Task"><input class="TaskInput" type="text" placeholder="Select Project"></td>
                                    <td class="Comment"><input class="CommentInput" type="text" placeholder="Select Project"></td>
                                </tr>
                               
                                <tr id="addjob">
                                    <td><button type="button" id="clearInputBTN" class="btn btn-primary">Clear</button></td>
                                    <td></td>
                                    <td></td>
                                    <td id=""><button type="button" class="btn btn-add" id="addjob">Add Another Row +</button></td>
                                    <td id=""><button type="button" class="btn btn-remove" id="addjob">Remove Row -</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <br />
                    <button type="button" class="btn span3" id="hrSubmit">Submit</button>
                    </form>
                    <!-- <div id="error">This is a test error message.</div> -->
                </div>

              
            </div>
            </div> <!-- /container -->
            </div>

           <script>
               document.ready(function(){

               });
               $(".addjob").click(function () {
                   alert("hi");
   var $clone = $('table.tbll tr.cloneme:first').clone();
   console.log($clone);
   $clone.append("<td><div class='rmv' >Remove</div></td>");
   $('table.tbll').append($clone);
});

$('.tbll').on('click', '.rmv', function () {
   alert("wee");
   $(this).closest('tr').remove();
});
               </script>



    </body>
</html>