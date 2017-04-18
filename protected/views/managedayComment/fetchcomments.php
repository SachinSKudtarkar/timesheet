

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'day-comment-grid1',
	'dataProvider'=>$model->searchAll(false),
        //'filter'=>$model,
	'columns'=>array(
	
//                array(
//            'name'=>'unlock',
//            'type'=>'raw',
//            'value' => 'CHtml::checkBox("is_submitted[]", ($data->is_submitted)? true : false , array("class"=>"unlock_submit","value"=>$data->is_submitted,"id"=>$data->id ,"multiple" =>"multiple"),array("style"=> "align:center"))',
//            'filter'=> false,
//        ),
                array(
                'header' => 'submitted',
                'value' => '($data->is_submitted == 1 ? "Lock" :"")',
                'type' => 'raw',
        ),
            array(
    'name'=>'day',
    'htmlOptions'=>array('width'=>'140px'),
  ),
		//'day',
		//'comment',
            array(
    'name'=>'hours',
    'htmlOptions'=>array('width'=>'60px'),
  ),
	   
		//'hours',
              
              //  'is_submitted',
//		array(
//			'class'=>'CButtonColumn',
//		),
	),
)); ?>

<script>
     $('.unlock_submit').change(function(){  
     var is_sub = $('.unlock_submit').val();
     var crrent_obj = $(this);
     var to_be_checked = '';
     var msg = '';
     if( !crrent_obj.attr('checked') )
     {
        to_be_checked = true;
        msg = 'Are you sure? you want to change daycomments status';
     }else{
         to_be_checked = false;
     msg = 'Are you sure? you want to change daycomments status';
     }
     bootbox.confirm(msg,function(result){
   // alert(result);
     if( result == true )
     {
      $.ajax({
        
        url:"<?php echo Yii::app()->getBaseUrl(true); ?>/managedaycomment/updateStatus",
        type: 'POST',
        data: {id:crrent_obj.attr('id'),chk:crrent_obj.is(':checked')}, 
        success: function(data) {
        },
        });
         
     }else{
      crrent_obj.attr('checked',to_be_checked);
     }
        
     });
});
</script>