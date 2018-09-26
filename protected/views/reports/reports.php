<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('reports'),
);

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

?>

<h1>Reports</h1> 

<?php $this->renderPartial('_count', array('model'=>$model,'allcount'=>$allcount)); ?>


<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>

<script type="text/javascript">
    $(document).ready(function() {
        

    });
</script>