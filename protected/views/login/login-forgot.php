<?php
/* * ************************************************************
*  File Name : login.php
*  File Description: Display Login Form.
*  Author: Benchmark, 
*  Created Date: 17	/2/2014
*  Created By: Yogesh Jadhav
* ************************************************************* */

/* @var $this AdminController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

$this->breadcrumbs=array(
	'Admin Login',
);
?>
<div class="row-fluid">
<div class="login-box clearfix">
<div class="well span6 offset3">
<h2  class="text-info">Supper Admin Login</h2>
<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	 'htmlOptions'=>array(
        'class'=>'form-horizontal',
    ),
)); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model,'User ID',array('class'=>'control-label') ); ?>
		<div class="controls"> <?php echo $form->textField($model,'username',array('class'=>'span8','placeholder'=>'User ID')); ?> </div>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'Password',array('class'=>'control-label')); ?>
		<div class="controls"><?php echo $form->passwordField($model,'password',array('class'=>'span8','placeholder'=>'Password')); ?></div>
		<?php echo $form->error($model,'password'); ?>
		
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'Remember me',array('class'=>'checkbox')); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		<div class="clear"></div>
			<?php echo CHtml::htmlButton('Sign in',array('class'=>'btn','type'=>'submit')); ?>
		</div>
	</div>  
	
	<div class="control-group">
		<div class="controls">
			<a  href="#modal-2" role="button"  data-toggle="modal">Forgot password?</a>
			
			
		</div>
	</div>
  
<?php $this->endWidget(); ?>

<?php 
Yii::app()->clientScript->registerScript('forgotpass', "function forgotpass(){
		alert('aaa');
       $.ajax({
            url: \"//admin/login/forgotPasswprd\",
            type: \"get\",
            data: {Email:$(\"#email\")},
            success: function( strData ){
				alert('asdsa');
                //$('.modal, .modal-backdrop').hide();
                $(\"#modal-2\").modal('hide');
				$('#target-04').toggle();
            },
            error: function(){
				alert('error');
                $('#report').text('Sorry, Please try again').css('color', 'red');
            }
        });
    };");
?>
</div>

<div id="modal-2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title bold-text" id="myModalLabel">Forgot Password</h5>
      </div>
	<?php $this->renderPartial('_form', array('model'=>$model)); ?>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

		
</div>
</div>   
</div>