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

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);

?>


<h1>Day Comments</h1> 
<form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
    <div style="position: relative; ">    
        <table>
            <tr>
                <td>
                    <div class="row">
		<?php echo $form->labelEx($model,'day'); ?>
		<?php // echo $form->textField($model,'inception_date'); ?>
           <?php echo $form->textField($model, 'day', array('id' => 'day', 'class' => 'datepicker', 'placeholder' => 'Select day', 'style' => 'width:200px;')); ?>
		<?php echo $form->error($model,'day'); ?>
	</div>
                </td>
            </tr>
            <tr>
                <td><label><b>From</b></label></td>
                <td><label><b>To</b></label></td>
                <td width="40px;"></td>
                <td><label><b>Program</b></label></td> 
                <td><label><b>Employee</b></label></td>
                <td><label><b>Project</b></label></td>
                <td></td>
            </tr>    
            <tr>
                <td>
                   
                </td>
                <td>
                   
                </td>   
                <td width="40px;"></td>
                <td id="project_name">
                     
                </td>
                <td id="employee">
                    
                </td> 
                
                <td>
                    
                </td>

                <td align="left">
                    
                </td>
            </tr>
        </table>
            <script type="text/javascript">
   
    </script> 
    </div> 
  
</form>  
