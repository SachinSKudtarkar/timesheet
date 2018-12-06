<?php
/* @var $this NddAg1InputController */
/* @var $model NddAg1Input */
/* @var $form CActiveForm */

$user_id = Yii::app()->session["login"]["user_id"];
//$user_id = base64_encode($user_id);
?>

<style>
    .radioLabel{
        text-align: left;
    }
    .alert_button{
    margin-left: 110px !important;
    background-color: #222;
    padding: 5px 14px 5px 15px;
    border-radius: 5px;
    color: #fff  !important;
    }
</style>
<?php
if ($model->id) {
    $formAction = "nddAg1Input/updateSite/id/" . $model->id . "?request_id=" . $model->request_id;
} else {
    $formAction = "nddAg1Input/updateSite?request_id=" . $model->request_id;
}
$formAction = Yii::app()->createUrl($formAction);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'ndd-ag1-input-site-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'onsubmit' => "js:return false;",),
    'method' => 'post',
    'action' => $formAction,
        ));
		
	//CHelper::debug($model);	
?>

<div class="row">
    <div class="SECT1">
        <p>Please first fill the timesheet before you logout</p>
     
    </div>
</div>
 
<div class="row buttons" style='float:left;'>
    <?php /*echo CHtml::link('OK', array('label' => 'Issue Tracker Login',
                                            'url' => Yii::app()->createUrl('/issuetracker/login.php?email=' . $user_email),
                                            'visible' => !Yii::app()->user->isGuest,
                                            'active' => (Yii::app()->controller->id == 'site'),
                                            'linkOptions' => array('target' => '_blank'),
                                            'id'=>"form_submit",'style' =>'margin-left: 110px !important;')); */?>
<?php echo  CHtml::link("OK", Yii::app()->createUrl('/daycomment'), array('class' => 'alert_button'));?>
</div>

<?php $this->endWidget(); ?>
