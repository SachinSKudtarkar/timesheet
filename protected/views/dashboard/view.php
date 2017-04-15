<?php
/* @var $this NddSapidManagerController */
/* @var $model NddSapidManager */

$this->breadcrumbs=array(
	'Dashboard',
	
);

?>

<?php
echo CHtml::dropDownList('type','Please select the value',Yii::app()->params['dashboard_options'],array(
'class' =>'type_filter',
   
));
?><div id="dashboard_content">
<?php $this->renderPartial('//dashboard/_dashboard',array('model'=>$model));?>
    </div>
<?php
    
Yii::app()->clientScript->registerScript('search', "
$('.type_filter').on('change',function(){
   $.ajax({
                            url: '" . CommonUtility::createUrl("site/dashboard") . "',
                            type: 'POST',
                            data: {type : $(this).val()},
                            success: function(data) {
                                //$.fn.yiiGridView.update('dashboard-grid');
                                $('#dashboard_content').html(data);
                              
                            },
                        });


    
});

");
?>
