<?php
/* * **********************************************************
*  File Name : Change_email.php
*  File Description: Used for the confirm the password of admin before change the email id
*  Author: Benchmark, 
*  Created Date: 4-3-14
*  Created By: Shailesh Giri
* *********************************************************** */


?>
<div class="white-bg bordered-box clearfix">
    <div class="row-fluid" style="min-height:555px;">
        <div class="login-box clearfix" style="min-height:555px;">
             <br><br><br>
            <div class="well span6 offset3">
               
                <center><h6  class=" text-info">User Change Email-Id</h6></center>
                <div class="form">
                            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                             'id'=>'login-form',
                             'enableClientValidation'=>true,
                             'clientOptions'=>array(
                              'validateOnSubmit'=>true,
                             ),
                              'htmlOptions'=>array(
                                    'class'=>'form-horizontal',
                                ),
                            )); ?>

                            <div class="control-group">
                                <?php echo $form->labelEx($model,'Password',array('class'=>'control-label', 'style'=>'color:black;')); ?>
                                <div class="controls"><?php echo $form->passwordField($model,'password',array('class'=>'span8','placeholder'=>'Password', 'value'=>'')); ?></div>
                                <?php echo $form->error($model,'password'); ?>
                            </div>

                            <div class="control-group">
                                <div class="controls">  
                                <div class="clear"></div>
                                <?php echo CHtml::htmlButton('Verify Password',array('class'=>'btn','type'=>'submit')); ?>
                                </div>
                            </div>  

                            <?php $this->endWidget(); ?>
                </div>


            </div>
        </div>   
    </div>
</div>