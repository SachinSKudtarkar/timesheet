<?php
/* @var $this AdminController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Admin Login',
);
?>

<div class="row-fluid">
<div class="login-box clearfix">
<div class="well span6 offset3">
<h2  class="text-info">SUPER ADMIN LOGIN</h2>
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
		<?php echo $form->labelEx($model,'User ID',array('class'=>'control-label') ); ?>
		<div class="controls"> <?php echo $form->textField($model,'email',array('class'=>'span8')); ?> </div>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'Password',array('class'=>'control-label')); ?>
		<div class="controls"><?php echo $form->passwordField($model,'password',array('class'=>'span8')); ?></div>
		<?php echo $form->error($model,'password'); ?>
		
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'Remember me',array('class'=>'checkbox')); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		<div class="clear"></div>
			<?php echo CHtml::button('Sign in',array('class'=>'btn')); ?>
		</div>
	</div>  

<?php $this->endWidget(); ?>
</div>


</div>
</div>   
</div>