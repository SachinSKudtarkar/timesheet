<?php
/* @var $this ReportsController */
/* @var $model Report */
/* @var $form CActiveForm */

?>
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
