<?php $this->beginContent('//layouts/main'); ?>
      <div class="row-fluid">
        <div class="span2 left-panal">
         <?php   
            $this->beginWidget('zii.widgets.CPortlet', array(
                'title'=>'Operations',
            ));
            $this->widget('zii.widgets.CMenu', array(
                'items'=>$this->menu,
                'htmlOptions'=>array('class'=>'sidebar'),
            ));
            $this->endWidget();
            if( Yii::app()->controller->id == 'gcttemplatemgmtmaster' && Yii::app()->controller->action->id == 'index' ){
            ?>
            <span class='notify'  style='display: none; position:fixed;  padding:5px 10px; background:#F6FF00; z-index:99999999;margin-top: -25px'>Copied to clipboard</span>
            <div class="nip_variables" id="nip_variables_id"></div>
                <?php
            }
            $device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : 0;
			if( Yii::app()->controller->action->id == 'index' && $device_id && Yii::app()->controller->id == 'unprocessedplans'){
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type'=>'',
                    'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(               
                        array(
                           'label'=>'Upload Zip File', 
                           'url'=>array('#'),                    
                           'htmlOptions'=>array('class'=>'form-group','data-toggle' => 'modal', 'data-target' => '#upload_zip'), 
                        ),
                    ),
                ));
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type'=>'',
                    'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(               
                        array(
                           'label'=>'Upload Nip File (zip only)', 
                           'url'=>array('#'),                    
                           'htmlOptions'=>array('class'=>'form-group','data-toggle' => 'modal', 'data-target' => '#upload_nip'), 
                        ),
                    ),
                ));
                 $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type'=>'',
                    'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(
                        array(
                           'label'=>'Fetch Command file', 
                           'htmlOptions'=>array('class'=>'form-group fetch_cmd_file ', 'style'=>'display:none', 'id'=>'fetchCmdBtn','data-toggle' => 'modal', 'data-target' => '#fetch_commands'), 
                        ),
                    ),
                ));
                // Code for Fetch Commands
                $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'fetch_commands'));
                $this->renderPartial('//unp_plan/fetch_commands');
                $this->endWidget();
                
                // Code for Upload ZIP
                $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'upload_zip'));
                $this->renderPartial('//unp_plan/upload_zip');
                $this->endWidget();
                
                // Code for Upload NIP
                $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'upload_nip'));
                $this->renderPartial('//unp_plan/upload_nip');
                $this->endWidget();
            }

        if (Yii::app()->controller->action->id == 'index' && $device_id && Yii::app()->controller->id == 'atp1cunprocessedplans') {
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                'buttons' => array(
                    array(
                        'label' => 'Upload Zip File',
                        'url' => array('#'),
                        'htmlOptions' => array('class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_zip'),
                    ),
                ),
            ));
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                'buttons' => array(
                    array(
                        'label' => 'Upload Nip File (zip only)',
                        'url' => array('#'),
                        'htmlOptions' => array('class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_nip'),
                    ),
                ),
            ));
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                'buttons' => array(
                    array(
                        'label' => 'Fetch Command file',
                        'htmlOptions' => array('class' => 'form-group fetch_cmd_file ', 'style' => 'display:none', 'id' => 'fetchCmdBtn', 'data-toggle' => 'modal', 'data-target' => '#fetch_commands'),
                    ),
                ),
            ));
            // Code for Fetch Commands
            $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'fetch_commands'));
            $this->renderPartial('//unp_plan/fetch_commands');
            $this->endWidget();

            // Code for Upload ZIP
            $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'upload_zip'));
            $this->renderPartial('//atp1cunp_plan/upload_zip');
            $this->endWidget();
        }
			// 1A - Side Bar Menu
            if( Yii::app()->controller->action->id == 'index' && $device_id && Yii::app()->controller->id == 'unprocessedplansonea'){
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type'=>'',
                    'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(
                        array(
                           'label'=>'Upload Zip File', 
                           'url'=>array('#'),                    
                           'htmlOptions'=>array('class'=>'form-group','data-toggle' => 'modal', 'data-target' => '#upload_zip'), 
                        ),
                    ),
                ));    
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type'=>'',
                    'htmlOptions'=>array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(               
                        array(
                           'label'=>'Upload Ndd File (zip only)', 
                           'url'=>array('#'),                    
                           'htmlOptions'=>array('class'=>'form-group','data-toggle' => 'modal', 'data-target' => '#upload_ndd'), 
                        ),
                    ),
                ));
                // Code for Upload ZIP
                $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'upload_zip'));
                $this->renderPartial('//unp_plan_one_a/upload_zip');
                $this->endWidget();
                
                // Code for Upload NIP
                $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'upload_ndd'));
                $this->renderPartial('//unp_plan_one_a/upload_ndd');
                $this->endWidget();
            }
            if( (Yii::app()->controller->action->id == 'csvcupload' || Yii::app()->controller->action->id == 'addnewcspcdevice') && Yii::app()->controller->id == 'unprocessedplans') {
                // Code for Upload ZIP
            //================Added by aashay to disable button for read only user on 10 may 2015 ==========================
            if ((CHelper::isAccess("CSPC_COLLECT", "unprocessedplans/csvcupload")==0 && Yii::app()->controller->action->id == 'csvcupload')) {  
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => '',
                    'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                    'buttons' => array(
                        array(
                            'label' => 'Upload TXT File',
                            'url' => array('#'),
                            'htmlOptions' => array('data-keyboard' => "false", 'data-backdrop' => 'static', 'class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_zip' ,'disabled'=>'disabled'),
                        ),
                    ),
                ));
            }else{
                    if(CHelper::isAccess("CSPC_DAV", "unprocessedplans/addnewcspcdevice")==0 && Yii::app()->controller->action->id == 'addnewcspcdevice')
                    {
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => '',
                    'htmlOptions' => array('class'=>'span12 btn-group-vertical'),
                    'buttons'=>array(               
                        array(
                           'label'=>'Upload TXT File', 
                           'url'=>array('#'),                    
                                'htmlOptions'=>array('data-keyboard' => "false", 'data-backdrop' => 'static', 'class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_zip' ,'disabled'=>'disabled'),
                        ),
                    ),
                ));
                    
                    }else
                    {
                    //======================End============================
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                        'type' => '',
                        'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                        'buttons' => array(
                            array(
                                'label' => 'Upload TXT File',
                                'url' => array('#'),
                                'htmlOptions' => array('data-keyboard' => "false", 'data-backdrop' => 'static', 'class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_zip'),
                            ),
                        ),
                    ));
                    
                  
                    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'upload_zip'));
                    $this->renderPartial('//unp_plan/upload_txt_json');
                    $this->endWidget();
                }
            }
        }
        
            if (Yii::app()->controller->action->id == 'uploadips' && Yii::app()->controller->id == 'autoloopback0cspccollection') 
                    {
                        $this->widget('bootstrap.widgets.TbButtonGroup', array(
                            'type' => '',
                            'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                            'buttons' => array(
                                array(
                                    'label' => 'Upload Automation TXT File',
                                    'url' => array('#'),
                                    'htmlOptions' => array('data-keyboard' => "false", 'data-backdrop' => 'static', 'class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_text_file'),
                                ),
                            ),
                        )); 

                        $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'upload_text_file'));
                $this->renderPartial('//autoLoopback0CspcCollection/upload_auto_txt_json');
                        $this->endWidget();
                    }
             if (Yii::app()->controller->action->id == 'uploadips' && Yii::app()->controller->id == 'syslogserveripschange') 
                    {
                        $device_id_xxxx = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : 0; 
                        if ($device_id_xxxx != "PAR" && $device_id_xxxx != "ESR") {
                        $this->widget('bootstrap.widgets.TbButtonGroup', array(
                            'type' => '',
                            'htmlOptions' => array('class' => 'span12 btn-group-vertical'),
                            'buttons' => array(
                                array(
                                    'label' => 'Upload CSV File',
                                    'url' => array('#'),
                                    'htmlOptions' => array('data-keyboard' => "false", 'data-backdrop' => 'static', 'class' => 'form-group', 'data-toggle' => 'modal', 'data-target' => '#upload_text_file'),
                                ),
                            ),
                        )); 
        
                        $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'upload_text_file'));
                        $this->renderPartial('//syslogserveripschange/upload_auto_txt_json');
                        $this->endWidget();
                        }         
                    }
                    
	?>
	</div>
        <!-- sidebar span2 -->

	<div class="span10 right-panal">
		<div class="main">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
</div>
<?php $this->endContent(); ?>
<?php
    $jsUrl = CHelper::projectUrl();
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($jsUrl.'/themes/cisco/js/fetchCommandFiles.js',CClientScript::POS_END);
?>
