<?php
/* @var $this LevelResourceAllocationController */
/* @var $model LevelResourceAllocation */
/* @var $form CActiveForm */
?>

<div class="form-group clearfix test-panal" style="margin-top: 20px;">

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

    <?php //echo $form->errorSummary($model); ?>
    <form class="form no-mr clearfix" action="<?php echo Yii::app()->baseUrl; ?>/levelresourceallocation/allocate" method="post" id="allocaterform">
        <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

        <div class="row">
            <h3>Resource Allocation</h3>
            <?php echo CHtml::label('Level Name', ''); ?>
            <?php echo CHtml::dropDownList('LevelName', $model->level_id, CHtml::listData(LevelMaster::model()->findAll(array('order' => 'level_name ASC')), 'level_id', 'level_name'), array('prompt' => 'Please select Level'));?>
        </div>

        <div class="row">
            <?php echo CHtml::label('Available Resource', ''); ?>
            <?php
            $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1 and is_password_changed="yes"'));
            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
            }
            echo CHtml::dropDownList("txtarea1", '', $emp_list, array('multiple' => 'multiple', 'style' => 'height:200px;margin-top:20px;'));
            echo CHtml::link('Allocate', array(''), array('style' => 'margin-left:20px;margin-top:50px; float:left', 'class' => 'btn allcateresource'));
            echo CHtml::link('De-Allocate', array(''), array('style' => 'margin-left:20px;margin-top:50px; float:left', 'class' => 'btn dallocate'));
            ?>

            <?php
                //$allocatedResourcesIdsArr = explode(',', $model->allocated_resource);
                //foreach($allocatedResourcesIdsArr as $k=>$id){
                  //  $allocatedResourcesArr[$id] = $emp_list[$id];
                //}
                echo CHtml::dropDownList("txtarea2", '', $allocatedResourcesArr, array('multiple' => 'multiple', 'style' => 'height:200px;margin-left:20px;margin-top:20px;'));
            ?>
        </div>

<?php echo CHtml::submitButton('Done', array('id' => 'allocateR', 'style' => 'margin-top:20px;')); ?>

    </form>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('MoveElements', "

$('#LevelName').change(function(){
var level_id = $(this).val();

 $('#txtarea2 option').each(function(index, option) {
    $(option).remove();
});
var tval = $(this).val();

 if(tval != '')
{
$('.custom-loader').show();
$.ajax({
				url: '" . CHelper::createUrl("levelresourceallocation/fetchAllocatedResource") . "',
                type:'POST',
			    data: {level_id : level_id},
                success:function(data)
                {
                $('.custom-loader').hide();

                 if(data != 0)
                 {

                    var allocation = data.split(',');
                    var length = allocation.length;
                    for(var i = 0; i < length; i++)
                    {
                        var nextsplit = allocation[i].split('==');
                        $('#txtarea2').append('<option value = '+nextsplit[0]+' >'+nextsplit[1]+'</option>');
                        //$('#txtarea1 option[value='+nextsplit[0]+']').remove();
                    }

                    }
                }

            });
            }
});

    $('.allcateresource').click(function(){
    return !$('#txtarea1 option:selected').remove().appendTo('#txtarea2');
    });

$('.dallocate').click(function(){
    return !$('#txtarea2 option:selected').remove().appendTo('#txtarea1');
    });

//$('#txtarea2').click(function(){
//$('.allcateresource').hide();
//$('.dallocate').show();
//});
//
//$('#txtarea1').click(function(){
//$('.allcateresource').show();
//$('.dallocate').hide();
//});

$('#allocateR').click(function(){
$('#txtarea2 option').prop('selected', true);
if($('#LevelName').val() == '')
{
    alert('Please select level.');
    return false;
}

});





    ", CClientScript::POS_READY);
?>

<?php
Yii::app()->clientScript->registerScript('ShowResource', "

  $('#ProjectName').change(function(){


});

    ", CClientScript::POS_READY);
?>

