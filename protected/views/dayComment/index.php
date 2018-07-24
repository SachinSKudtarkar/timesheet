<?php
/* @var $this DayCommentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Day Comments',
);

//$this->menu = array(
//    array('label' => 'DayComment', 'url' => array('index')),
//    array('label' => 'View My Status', 'url' => array('admin')),
//);
$this->widget('ext.yiicalendar.YiiCalendar', array
    (
    'dataProvider' => array
        (
        'pagination' => array
            (
            'pageSize' => 'week',
            'isMondayFirst' => TRUE
        )
    )
));
?>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
// echo '<pre>';print_r($arrData);echo '</pre>';exit;
?>

<div class="info well well-large">
    <h1>Time Sheet Notice </h1>
    <p>For multiple task add by  clicking <code>+ sign</code> ,  mention <code>shift</code> and  Then click <code>Save Button</code> to save changes, <code>You can also edit current Month task </code> 
    </p>
</div>


<form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addcomment" method="post" id="addcommentfrm">
    <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

    <h1>Day Comments</h1>
    <div class="row" >
        <div class="row" >
            <div class="span5">
				<?php
                   	echo CHTML::label('Select Day :', '', array('style' => 'width:80px; font-weight:bold;'));
					$selecting_date = "";
					if (isset($_GET['selecting_date'])) {
						$selecting_date = $_GET['selecting_date'];
						$selecting_date = date('Y-m-d', strtotime($selecting_date));
					} else {
						$selecting_date = date('Y-m-d');
					}
                                   
//                                        echo $selecting_date;
                                        
//					echo CHTML::label('Select Day :', '', array('style' => 'width:80px; font-weight:bold;'));
//				
//					for ($mondayCounter = 4; $mondayCounter >= 0; $mondayCounter--) {
//						$da = date('d-m-Y', strtotime(" -" . $mondayCounter . "monday this week"));
//						$mondayList[date('Y-m-d', strtotime(" -" . $mondayCounter . "monday this week"))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));
//					}
//					//add 1 week additional
//					$da = date('d-m-Y', strtotime("monday next week"));
//					$mondayList[date('Y-m-d', strtotime("monday next week"))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));
                                        
                                       $list=array();
                                       $date =  date('Y-m-d');
                                       $date = explode("-", $date);
                                        $month = $date[1];
                                        $year = $date[0];
                                        
                                         $number = cal_days_in_month(CAL_GREGORIAN,$month, $year);

                                        for($d=1; $d<=$number; $d++)
                                        {
                                            $time=mktime(12, 0, 0, $month, $d, $year);          
                                            if (date('m', $time)==$month)       
                                                $list[date('Y-m-d',$time)]=date('Y-m-d', $time);
                                        }
					echo CHtml::dropDownList('selecting_weeks', $date,$list, array('class' => 'selecting_weeks', 'empty' => 'Select Value', 'options' => array($selecting_date => array('selected' => true))));

					$btnShow = FALSE;
					//$is_submitted = TRUE;
//					if (strtotime($selecting_date) >= strtotime("monday this week") ) {
//						$btnShow = TRUE;
//					}else if (strtotime($selecting_date) <= strtotime("monday this week") ) {
//						$is_submitted = TRUE;
//					}
					
					$is_submitted = false;
					$btnShow = true;
                                        
                                        
				?>
                                                      
			</div>
        </div>
        <hr style=" margin-top: 0px; margin-bottom: 3px;"/>
    </div>

    <div class="row">
                            <div class="span"  style="margin-left:5px;">
							<?php 
					 $day_ne = preg_replace('/-/','_', $selecting_date);
							foreach ($arrData[$day_ne] as $key=>$eachproject) {
								$shift = $eachproject['shift'];
							}

				

								echo CHTML::label('shift :', '', array('style' => 'width:50px;font-weight:bold; '));?>

<span id="shift">
    <input value="1" id="day" type="radio" name="shift" <?php if($shift == 1) echo "checked = 'checked' " ;?> >
    <label for="day_1" style = "display:inline;margin-top: -3px;width: auto !important;">Day</label>
    <input value="2" id="night" type="radio" name="shift" <?php if($shift == 2) echo "checked = 'checked' " ;?>>
    <label for="night_1" style = "display:inline;margin-top: -3px;width: auto !important;">Night</label>
</span>

							</div>
							</div>
    <?php
    echo "<input type='hidden' name='selected_date' value='{$selecting_date}' />";
    $img_path = Yii::app()->theme->baseUrl . "/img/add_image.png";
    
//    for ($k = 0; $k <= 6; $k++) {
//        $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
//        $date = date("Y-m-d", $generte_date);
//        $date_id = date("Y_m_d", $generte_date);
//
//		if(key_exists($date,$arrSubmitted))
//			$is_submitted = $arrSubmitted[$date];
   
                $generte_date = strtotime($selecting_date);
		 $date_id = date("Y_m_d", $generte_date);
                
               
		
		if(key_exists($date_id, $arrData)) { ?>
				<div class="main_daycomments">
			<?php
			$i = 0;
		
		
			foreach ($arrData[$date_id] as $key=>$eachproject) {
				
				// CHelper::debug($eachproject);
				$tmpcls = ($i > 0) ? '' : '';
				$nxt = $i + 1;
				$day = explode(" ", $eachproject['day'])[0];
				$day = preg_replace('/-/','_', $day);
				if($date_id == $day){
					$count++;

				}
				if($count > 1){
					$pid = $eachproject['pid']+$count;

				}else{
					$pid = $eachproject['pid'];
				}
					?>
					
				<!-- 	<div class="row hdshow_<?php //echo $date_id ."_". $i . " " . $tmpcls; ?>" id="<?php //echo $date_id ."_". $i . " " . $tmpcls; ?>" > -->
				<div>
                                   
						<div class="row" >
                                                   

							<div class="span2"  style="margin-left:10px;">
								<?php echo "<b>" . date("l", $generte_date) . "</b> <br /> (" . date("d-m-Y", $generte_date) . " )"; ?>
								<?php echo CHTML::hiddenField('Date[]', $date, array('readonly' => 'readonly', 'style' => 'width:90px;')); ?>
							</div>
							<div class="span2"  style="margin-left:10px; " >
								<?php echo CHTML::label('Program :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php echo CHTML::dropDownList('ProjectName[]', $eachproject['pid'], CHtml::listData($allProjects, 'pid', 'project_name'), array('style' => 'width:150px;', 'disabled' => ($is_submitted ? 'disabled' : ''), 'prompt' => 'Please select Program', 'class' => 'proclass', 'id' => $date_id ."_". $pid, 'nxt' => $date_id ."_". $nxt)); ?>
							</div>
							<div class="span2"  style="margin-left:10px;">
								<?php echo CHTML::label('Project :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php
									//$result = array();
									echo CHTML::dropDownList('SubProjectName[]', $eachproject['spid']['selected'], $eachproject['spid']['result'], array('class'=>'sub-project','style' => 'width:150px;  ', 'prompt' => 'Please select  Project', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subproclass' . $date_id ."_". $pid ));
								?>
							</div>
							<div class="span2"  style="margin-left:10px;">
								<?php echo CHTML::label('Task :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php
									//$result = array();
									echo CHTML::dropDownList('SubTaskName[]', $eachproject['stask_id']['selected'], $eachproject['stask_id']['result'], array('class'=>'sub-task','style' => 'width:150px;  ', 'prompt' => 'Please select  Task', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subtasks' . $date_id ."_". $pid ));
								?>
							</div>
							<div class="span1" style="margin-left:10px; ">
								<?php echo CHTML::label('Hour', '', array('style' => 'width:50px;font-weight:bold; ')); ?>
								<?php
									$arrHrs = array();
									for($h=0; $h<=23; $h++) {
										$h = (strlen($h) < 2) ? "0".$h : $h;
										$arrHrs[$h] = $h;
									}
									echo CHTML::dropDownList('hrs[]', $eachproject['hrs'], $arrHrs, array('style' => 'width:50px;  ', 'class'=>'wrkhrsClass', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'wrkhrsClass' . $date_id ."_". $pid));
								?>
							</div>
							<div class="span1" style="margin-left:10px; ">
								<?php echo CHTML::label('Minutes', '', array('style' => 'width:50px;font-weight:bold; ')); ?>
								<?php
									$arrMnts = array();
									for($m=0; $m<=59; $m+=5) {
										$m = (strlen($m) < 2) ? "0".$m : $m;
										$arrMnts[$m] = $m;
									}
									echo CHTML::dropDownList('mnts[]', $eachproject['mnts'], $arrMnts, array('style' => 'width:50px;  ', 'class'=>'wrkmntClass',  'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'wrkminsClass' . $date_id ."_". $pid));
								?>
							</div>
							<div class="span2">
								<?php echo CHTML::label('Comment :', '', array('style' => 'width:130px;font-weight:bold; ')); ?>
								<?php echo CHtml::textArea('procomment[]', $eachproject['comment'], array('style' => ' height:40px; width:200px; ','class' =>'DayComment', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'comment' . $date_id ."_". $pid ,'onclick'=>'checkLength()'));
								?>
							</div>
							<div class="span2">
								<?php echo CHTML::label('&nbsp;', '', array('style' => 'width:20px;font-weight:bold; ')); ?>
								<?php
								if(!$is_submitted) {
									echo '<a href="javascript:void(0)"  class="showbox" data-nxt="' . $date_id ."_". $nxt . '" data-val="' . $date_id ."_". $pid . '" >'
										. '<img src="' . $img_path . '" style="width:20px;">' . '</a>';
								}
								?>
							</div>
						</div>	
						
						<hr style=" margin-top: 0px; margin-bottom: 3px;"/>
					</div>
				<?php
				$i++;
			}
			?>
			</div>
		<?php }else {
                    
                    
                    ?>

			<div class="main_daycomments">
			<?php
			$i = 0;


			
			foreach ($allProjects as $eachproject) {
				$tmpcls = ($i > 0) ? '' : '';
				$nxt = $i + 1;
				?>
				
					<!-- <div class="row hdshow_<?php //echo $date_id ."_". $i . " " . $tmpcls; ?>"  > -->
					<div>
						<div class="row" >
                                                    
							<div class="span2"  style="margin-left:10px;">
								<?php echo "<b>" . date("l", $generte_date) . "</b> <br /> (" . date("d-m-Y", $generte_date) . " )"; ?>
								<?php echo CHTML::hiddenField('Date[]', date("Y-m-d", $generte_date), array('readonly' => 'readonly', 'style' => 'width:90px;'));
								?>
							</div>
							<div class="span2"  style="margin-left:10px; ">
								<?php echo CHTML::label('Program :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php echo CHTML::dropDownList('ProjectName[]', 'pid', CHtml::listData($allProjects, 'pid', 'project_name'), array('style' => 'width:150px;', 'disabled' => ($is_submitted ? 'disabled' : ''), 'prompt' => 'Please select Program', 'class' => 'proclass', 'id' => $date_id ."_". $eachproject['pid'], 'nxt' => $date_id ."_". $nxt)); ?>
							</div>
							<div class="span2"  style="margin-left:10px;">
								<?php echo CHTML::label('Project :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php
									$result = array();
									echo CHTML::dropDownList('SubProjectName[]', 'spid', $result, array('class'=>'sub-project','style' => 'width:150px;  ', 'prompt' => 'Please select  Project', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subproclass' . $date_id ."_". $eachproject['pid']));
								?>
							</div>
							
							<div class="span2"  style="margin-left:10px;">
								<?php echo CHTML::label('Task :', '', array('style' => 'width:90px;font-weight:bold; ')); ?>
								<?php
									$result = array();
									echo CHTML::dropDownList('SubTaskName[]', 'stask_id', $result, array('class'=>'sub-task','style' => 'width:150px;  ', 'prompt' => 'Please select  Task', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'subtasks' . $date_id ."_". $eachproject['pid']));
								?>
							</div>
							<div class="span1" style="margin-left:10px; ">
								<?php echo CHTML::label('Hour', '', array('style' => 'width:50px;font-weight:bold; ')); ?>
								<?php
									$arrHrs = array();
									for($h=0; $h<=23; $h++) {
										$h = (strlen($h) < 2) ? "0".$h : $h;
										$arrHrs[$h] = $h;
									}
									echo CHTML::dropDownList('hrs[]', '', $arrHrs, array('style' => 'width:50px;  ', 'class'=>'wrkhrsClass', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'wrkhrsClass' . $date_id ."_". $eachproject['pid']));
								?>
							</div>
							<div class="span1" style="margin-left:10px; ">
								<?php echo CHTML::label('Minutes', '', array('style' => 'width:50px;font-weight:bold; ')); ?>
								<?php
									$arrMnts = array();
									for($m=0; $m<=59; $m+=5) {
										$m = (strlen($m) < 2) ? "0".$m : $m;
										$arrMnts[$m] = $m;
									}
									echo CHTML::dropDownList('mnts[]', '', $arrMnts, array('style' => 'width:50px;  ', 'class'=>'wrkmntClass', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'wrkminsClass' . $date_id ."_". $eachproject['pid']));
								?>
							</div>
							<div class="span2">
								<?php echo CHTML::label('Comment :', '', array('style' => 'width:130px;font-weight:bold; ')); ?>
								<?php echo CHtml::textArea('procomment[]','', array('style' => ' height:40px; width:200px; ','class' =>'DayComment', 'disabled' => ($is_submitted ? 'disabled' : ''), 'id' => 'comment' . $date_id ."_". $eachproject['pid'] ,'onclick'=>'checkLength()'));
								?>
							</div>
							<div class="span2">
								<?php echo CHTML::label('&nbsp;', '', array('style' => 'width:20px;font-weight:bold; ')); ?>
								<?php
								if(!$is_submitted) {
									echo '<a href="javascript:void(0)"  class="showbox" data-nxt="' . $date_id ."_". $nxt . '" data-val="' . $date_id ."_". $eachproject['pid'] . '" >'
										. '<img src="' . $img_path . '" style="width:20px;">' . '</a>';
								}
								?>
							</div>
						</div>
						<hr style=" margin-top: 0px; margin-bottom: 3px;"/>
					</div>
				<?php
				$i++;
				break;
			}
			?>
			</div>
		<?php } ?>
	<?php
    //}
    ?>
    <div class="row">
        <input type="hidden" name="totalPrjcts" value="<?php echo $i; ?>" />
        <input type="hidden" name="pidsid"  id="pidsid" />
        <?php echo CHTML::label('Total Hours :', '' ,array('style' => 'font-weight:bold; ')); ?>
        <?php echo CHTML::textField('tworkedHrs', '', array('class' => 'tworkedHrs', 'disabled' => 'disabled')); ?>
    </div>

    <!--        <div class="row">
    <?php// echo CHTML::label('Add Comment', ''); ?>
    <?php //echo CHtml::textArea('dayComment', '', array('style' => 'width:500px;height:150px;'));  ?>
            </div>-->

	<?php if ($btnShow) { ?>
    <div class="row">
		<?php
			if(!$is_submitted) {
				echo CHtml::submitButton('Save', array('id' => 'addC', 'style' => 'margin-top:10px;'));
			}
//			if(!$is_submitted) {
//			//if(!$is_submitted && ( strtotime(date('Y-m-d')) >= strtotime("saturday this week") && strtotime(date('Y-m-d')) <= strtotime("monday next week 1 pm") ) ) {
//				echo CHtml::submitButton('Submit', array('id' => 'btnSubmit', 'style' => 'margin-top:10px;', 'value'=>'Submit'));
//			}
		?>
    </div>
	<?php } ?>
    <?php
    Yii::app()->clientScript->registerScript('comment', "
        $(document).on('change','.wrkmntClass, .wrkhrsClass', function(){
			getWrkHoursTotal();
        });
		function getWrkHoursTotal() {

			var allhrs = 0;
			var allmnts = 0;
			$('.wrkhrsClass').each(function(){
				var thisval = $(this).val();
				if(thisval != ''){
					allhrs = parseFloat(allhrs)+parseFloat(thisval);
				}
			});
			$('.wrkmntClass').each(function(){
				var thisval2 = $(this).val();
				if(thisval2 != ''){
					allmnts = parseFloat(allmnts)+parseFloat(thisval2);
				}
			});
			var totHrs = ( parseFloat(allhrs)+parseInt( (allmnts / 60)) ) +':'+ ( parseFloat( (allmnts % 60) ) );
			$('#tworkedHrs').val(totHrs);
		}
		getWrkHoursTotal();

		$(document).on('click','#btnSubmit', function(){

			if(confirm('Are you sure want to Submit? Data for this week cant be changed after!!!')) {
				return true;
			}
			return false;
        });
        var pids = [];

	$(document).on('click','.showbox',function(){
		var _this = $(this).parent().parent().parent();

		var oldId = _this.find('select').attr('id');
		 //alert(oldId);
		var newArr = oldId.split('_');
		var newId = newArr[0]+'_'+newArr[1]+'_'+newArr[2]+'_'+(+newArr[3]+1);
		//alert(newId);
		var clonned = _this.clone();

		clonned.find('#'+oldId).each(function() {
			$(this).attr('id',$(this).attr('id').replace(''+oldId,''+newId));
		});

		clonned.find('#subproclass'+oldId).each(function() {
			$(this).attr('id',$(this).attr('id').replace('subproclass'+oldId,'subproclass'+newId));
		});
		
		clonned.find('#subtasks'+oldId).each(function() {
			$(this).attr('id',$(this).attr('id').replace('subtasks'+oldId,'subtasks'+newId));
		});

		clonned.find('#wrkhrs'+oldId).each(function() {
			$(this).attr('id',$(this).attr('id').replace('wrkhrs'+oldId,'wrkhrs'+newId));
		});

		clonned.find('#comment'+oldId).each(function() {
			$(this).attr('id',$(this).attr('id').replace('comment'+oldId,'comment'+newId));
		});

		clonned.find('input[type=text]').val('');
		clonned.find('textarea').text('');

		clonned.appendTo(_this.parent());
		//var nextcls = $(this).data('nxt');
		//$('.hdshow_'+nextcls).show();
    });

	$(document).on('change','.proclass',function(){
		if($(this).val() != '')
       	{
       		var thisId = $(this).attr('id');           
           	var sub_id=$(this).val();
           	var nextcls = $(this).attr('nxt');
           //	var this = $(this).parent('id');

           //	alert(this);
       		$(this).parents('.row').find('.wrkhrsClass').removeAttr('disabled');
       		$(this).parents('.row').find('.wrkmntClass').removeAttr('disabled');
       		$.ajax({
               url: BASE_URL+'/daycomment/fetchSubProject',
               type: 'POST',
               dataType: 'json',
               data:{ pid:sub_id},
               // beforeSend:function(){
               //      $('.custom-loader').show();
               //  },
               //  complete:function(){
               //     $('.custom-loader').hide();
               //  },
	           success: function(data)
	               {
					 
	                   	if(data.status=='SUCCESS')
	                   	{
	                        var dropDown = '<option value=>Please Select Sub Project</option>';
							//var workhours = data.workhours;
	                        $.each(data.result, function(key, val) {
	                            dropDown+='<option value='+key+'>'+val+'</option>';
								//localStorage.setItem( 'hours-'+key, workhours[key] );								

	                        });
							//console.log(dropDown);
							// console.log($(this).closest('.sub-project').length);
							// $(this).parent('div').next().html('dsds');
							//$(this).closest('select').html('test');
							//$(this).parents('.row').find('.sub-project').val(dropDown);
							//$(this).parents('.row').find('.sub-project').attr('disabled','disabled');
							//$(this).parents('.row').find('select.sub-project').removeAttr('disabled');
	                        $('#subproclass'+thisId).html(dropDown);
	                        $('#subproclass'+thisId).removeAttr('disabled');
	                   	}
	               },
	               error: function(XMLHttpRequest, data, errorThrown){
	               },
	        });
       	}
       	else{
       		$(this).parents('.row').find('.wrkhrsClass').attr('disabled','disabled');
       		$(this).parents('.row').find('.wrkmntClass').attr('disabled','disabled');
       	}
	});
    //$('.proclass').trigger('change');
    $('.proclassold').change(function(){
       if($(this).val() != '')
       {
           var thisId = $(this).attr('id');
           var nextcls = $(this).attr('nxt');
           var thisVal = $(this).val();
           $('#wrkhrs'+thisId).removeAttr('disabled');
           $('#comment'+thisId).removeAttr('disabled');
//           $('.hdshow_'+nextcls).show();
//           pids.push(thisVal);
//           $('#pidsid').val(pids);
       } else {
           var thisId = $(this).attr('id');
              var thisVal = $(this).val();
              var nextcls = $(this).attr('nxt');
              $('#wrkhrs'+thisId).attr('disabled','disabled');
              $('#comment'+thisId).attr('disabled','disabled');
//              $('.hdshow_'+nextcls).hide();
              pids.pop(thisVal);
              $('#pidsid').val(pids);
           }
    });


    $(document).on('change', '.proclassffff', function(){

       if($(this).val() != '')
       {

           var thisId = $(this).attr('id');
           
           var sub_id=$(this).val();
          //alert('subproclass'+thisId);
           $('#subproclass'+thisId).removeAttr('disabled');
           $.ajax({
               url: BASE_URL+'/daycomment/fetchSubProject',
               type: 'POST',
               dataType: 'json',
               data:{ pid:sub_id},
               // beforeSend:function(){
               //      $('.custom-loader').show();
               //  },
               //  complete:function(){
               //     $('.custom-loader').hide();
               //  },
               success: function(data)
               {
				  // alert(data);
                   if(data.status=='SUCCESS')
                   {
                        var dropDown = '<option value=>Please Select Sub Project</option>';
						var workhours = data.workhours;
                        $.each(data.result, function(key, val) {
                            dropDown+='<option value='+key+'>'+val+'</option>';
							localStorage.setItem( 'hours-'+key, workhours[key] );
							console.log('ddddddddddd'+localStorage.getItem( 'hours-'+key ) );

                        });

                        $('#subproclass'+thisId).html(dropDown);
                        $('#subproclass'+thisId).removeAttr('disabled');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });

       }
    });

	$(document).on('change', '.sub-project', function(){
       if($(this).val() != '')
       {
         	var thisId = $(this).attr('id');
           var is = thisId.substr(11, 13);
           var sub_id=$(this).val(); 
           //  alert(is);
           $('#subtasks'+is).removeAttr('disabled');
           $.ajax({
               url: BASE_URL+'/daycomment/fetchSubTask',
               type: 'POST',
               dataType: 'json',
               data:{ pid:sub_id},
               // beforeSend:function(){
               //      $('.custom-loader').show();
               //  },
               //  complete:function(){
               //     $('.custom-loader').hide();
               //  },
               success: function(data)
               {
			   console.log(data);
                   if(data.status=='SUCCESS')
                   {
                   	 
                        var dropDown = '<option value=>Please Select Sub Project</option>';
						//var workhours = data.workhours;
                        $.each(data.result, function(key, val) {
                            dropDown+='<option value='+key+' hrmin='+data.workhours[key]+'>'+val+'</option>';
							

                        });
						 // $(this).parents('.row').find('.sub-task').html(dropDown);
						 // $(this).parents('.row').find('.sub-task').removeAttr('disabled');
                        $('#subtasks'+is).html(dropDown);
                        $('#subtasks'+is).removeAttr('disabled');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });

       }
    });
	
	$(document).on('change', '.sub-task', function(){
		//console.log('here')
		/*var hrs = 0;
		var hrtime = 0;
		var hrstime = $(this).find('option:selected').attr('hrmin').split(':');
		var thisid = $(this).attr('id').split('subtasks');
		//console.log(thisid);
		if(hrstime[1]==0){
			hrs = hrstime[0];
			hrtime = 59;
		}
		else{
			hrs = hrstime[0];
			hrtime = hrstime[1];
		}
		if(hrs>24)
		hrs=23;
		
		var optstr = '';
		var optminstr = '';
		for(var h=0;h<=hrs;h++){
			optstr = optstr+'<option value='+h+'>'+h+'</option>';
		}
		for(var m=0;m<=hrtime;m++){
			optminstr = optminstr+'<option value='+m+'>'+m+'</option>';
		}
		console.log(optstr);
		$('#wrkhrsClass'+thisid[1]).html(optstr);
		$('#wrkminsClass'+thisid[1]).html(optminstr);*/
	});
	
	$(document).on('change', '.wrkhrsClass', function(){
		
		var thisattr = $(this).attr('id');
		var maxhrs = $('#'+thisattr+' option:last').val();
		var thisid = $(this).attr('id').split('wrkhrsClass');
		if($(this).val()==maxhrs && $(this).val()!=23){
			
			//console.log('asdada'+thisid);
			$('#wrkminsClass'+thisid[1]).attr('disabled',true);	
		}
		else{
			$('#wrkminsClass'+thisid[1]).removeAttr('disabled');	
		}
	});
           $('.datepicker').datepicker({
               dateFormat: 'd/m/yy',
               onSelect: function(dateText) {
                  var type = $(this).attr('id');
                  var date = $(this).val();
                },
              }).attr('readonly','readonly');



	$('.selecting_weeks').change(function()
    {
            var data = $('.selecting_weeks').val();
            if(data != '')
            {
                window.location.replace('" . CHelper::createUrl() . "/index/selecting_date/'+data);
            }
    });
	$(document).on('change','.sub-project111',function(){
		var sub_prj = $(this).val();
		var hrs;
		if(localStorage.getItem( 'hours-'+sub_prj )){
			hrs = localStorage.getItem( 'hours-'+sub_prj );
		}
		else{
			hrs=0;
		}
		console.log(hrs);
		console.log($(this).closest('.wrkhrsClass').val(hrs));
		
	});
        
function checkLength(){
    var textbox = document.getElementById('comment2018_06_25_9');
    if(textbox.value.length <= 10 && textbox.value.length >= 10){
        alert('success');
    }
    else{
        alert('make sure the input is between 10-100 characters long')
    }
}



       ", CClientScript::POS_READY);
    ?>
    
<script>
 $(document).ready(function () {
  $('#addC').click(function (e) {
            var isValid = true;
            $('.DayComment,.wrkmntClass,.sub-task,.sub-project,.proclass').each(function () {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                    $(this).css({
                        "border": "1px solid red",
                        "background": "#FFCECE"
                    });
                }
                else {
                    $(this).css({
                        "border": "",
                        "background": ""
                    });
                }
            });
            if (isValid == false)
                e.preventDefault();

        });
        
        // $(document).on('change','.proclass',function(){
//      $(function () {
//         $('.proclass').on('click', function () {
// //        var $div = $('div[id^="klon"]:last');

//             var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
//             data.find("input").val('');
//         });
//         $(document).on('click', '.remove', function () {
//             var trIndex = $(this).closest("tr").index();
//             if (trIndex > 0) {
//                 $(this).closest("tr").remove();
//             } else {
//                 alert("Sorry!! Can't remove first row!");
//             }
//         });
//     });
	
	
});
</script>
</form>

<!--  $(document).on('change', '.subclass', function(){
       if($(this).val() != '')
       {

            var spid=$(this).val();
           
            $.ajax({
               url: BASE_URL+'/daycomment/GetSubPStatus',
               type: 'POST',
               dataType: 'json',
               data:{spid:spid},
               beforeSend:function(){
                    $('.custom-loader').show();
                },
                complete:function(){
                   $('.custom-loader').hide();
                },
               success: function(data)
               {
               	    $.each(val,function(data){
          console.log(data);     
});
               	//alert(data);
                   if(data.status=='UNSUCCESS')
                   {
                       alert('your hours are exceeded');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });

       }
    });-->