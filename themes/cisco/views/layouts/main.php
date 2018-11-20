<?php
Yii::app()->bootstrap->register();
Yii::app()->clientscript;
$baseUrl = Yii::app()->getBaseUrl(true);
//Yii::app()->clientScript->scriptMap['bootstrap.css'] = false;
$user_email = Yii::app()->session["login"]["email"];
$user_email = base64_encode($user_email);

$userName = Yii::app()->session['login']['first_name'].' '.Yii::app()->session['login']['last_name'];

$emp_id = Yii::app()->session['login']['user_id'];
$access_type = AccessRoleMaster::model()->findByAttributes(array('emp_id' => $emp_id,'is_active'=>1));
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="sitelock-site-verification" content="1963" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="language" content="en" />
        <!--[if !IE]><!--><script>if (/*@cc_on!@*/false) {
                document.documentElement.className += ' ie10';
            }</script><!--<![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/custom-style.css" />
        <style>
            .top-banner{
                font-family: inherit;font-size: 18px; color: yellow; position: fixed; top: 20px; z-index: 9999;width: 100%;
            }
            a.menu:after, .dropdown-toggle:after {content: none;}
        </style>

        <?php
        $timelogid = isset(Yii::app()->session['login']) && array_key_exists('timelogid', Yii::app()->session['login']) ? Yii::app()->session['login']['timelogid'] : '';
        Yii::app()->clientScript->registerScript('common_header_script', "
                var BASE_URL = '{$baseUrl}';
            ", CClientScript::POS_HEAD);
        ?>
    </head>
    <body>
        <div class="custom-loader"></div>
        <!-- main nav -->
        <div class="header-wrap-1">

            <span class="brand-name">Timesheet</span>
            <span class="" style="font-size: 16px; color: #fff; right: 20px; position: fixed; top: 33px; z-index: 9999;">
                <small style="color: #aeaeae">Logged in as </small>&nbsp;&nbsp;
                <?php echo $userName; ?>
            </span>
            <?php
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse', // null or 'inverse'
                'brand' => '<img src="' . Yii::app()->baseUrl . '/images/infinity.jpg" />',
                'brandOptions' => array('class' => 'no-link-cur span2'),
                'brandUrl' => CHelper::baseUrl(true) . '/',
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'nav nav-tabs'),
                        'activeCssClass' => 'active',
                        'items' => array(
                            //  array(
                            //     'label' => 'Home',
                            //     'url' => array('//daycomment/home'),
                            //     'visible' => 1,
                            // ),
                            array(
                                'label' => 'Dashboard',
                                'url' => array('//pidapproval/admin'),
                                'visible' => (CHelper::isAccess("DASHBOARD", "full_access")),
                                'active' => (Yii::app()->controller->id == 'dashboard' && Yii::app()->controller->action->id == 'index'),
                            ),
                            array(
                                'label' => 'Operations',
                                'url' => '#',
                                'visible' => (isset(Yii::app()->session['login']['user_id'])),
                                'active' => 0,
                                'items' => array(
                                    array('label' => 'Add Comment',
                                        'url' => array('//daycomment'),
                                        'encodeLabel' => false,
                                        'visible' => 1,
                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                    ),
                                    array('label' => 'View Comment',
                                        'url' => array('//daycomment/admin'),
                                        'encodeLabel' => false,
                                        'visible' => 1,
                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'admin'),
                                    ),
                                      
                                    array('label' => 'Manage Program',
                                        'url' => '#',
                                        'visible' => ($access_type->access_type == 1),
                                        'active' => 0,
                                        'items' => array(

                                            array('label' => 'Create Program',
                                                'url' => array('//projectmanagement/create'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            ),
                                            array('label' => 'Allocate Resource for Program',
                                                'url' => array('//resourceallocationprojectwork/create'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            ),
//					                                     array('label' => 'View Program',
//			                                        'url' => array('//projectmanagement/admin'),
//			                                        'encodeLabel' => false,
//			                                        'visible' => 1,
//			                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
//
//					                                    ),
                                        ),
                                    ),
                                    array('label' => 'Manage Project',
                                        'url' => '#',
                                        'encodeLabel' => false,
//                                        'visible' => 1,
                                        'visible' => ($access_type->access_type == 1),
                                        'active' => 0,
                                        'items' => array(
                                            array('label' => 'Create Project',
                                                'url' => array('//subproject/create'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            ),
                                            // array('label' => 'Allocate Resource for Project',
                                            // 'url' => array('//resourceallocationprojectwork/create_task'),
                                            // 'encodeLabel' => false,
                                            // 'visible' => 1,
                                            // 'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            // ),
                                            array('label' => 'View Project',
                                                'url' => array('//subproject/admin'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            ),
//                                                    array('label' => 'Resource statistics',
//			                                        'url' => array('//resourceallocationprojectwork/resourceallocatedtask'),
//			                                        'encodeLabel' => false,
//			                                        'visible' => 1,
//			                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
//
//					                                    ),
                                        ),
                                    ),
//                                    array('label' => 'Manage Type',
//                                        'url' => array('//Task/admin'),
//                                        'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
//                                        'active' => (Yii::app()->controller->id == 'task' && Yii::app()->controller->action->id == 'task')
//                                    ),
                                    array('label' => 'Manage Tasks',
                                        'url' => '#',
                                        'encodeLabel' => false,
//                                        'visible' => 1,
                                        'visible' => ($access_type->access_type == 1),
                                        'active' => 0,
                                        'items' => array(
                                                     array('label' => 'Create Task',
                                                'url' => array('//pidapproval/create'),
                                                'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
                                                'active' => (Yii::app()->controller->id == 'pidapproval' && Yii::app()->controller->action->id == 'pidapproval')
                                            ),
                                            array('label' => 'Manage Task',
//                                                'url' => array('//pidapproval/admin'),
                                                'url' => array('//subtask/admin'),
                                                'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
                                                'active' => (Yii::app()->controller->id == 'pidapproval' && Yii::app()->controller->action->id == 'admin')
                                            ),
                                            array('label' => 'View Task(PID) Mapping',
                                                'url' => array('//pidmapping/index'),
                                                'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
                                                'active' => (Yii::app()->controller->id == 'pidmapping' && Yii::app()->controller->action->id == 'pidmapping')
                                            ),
//                                            array('label' => 'View All Projects Status',
//                                                'url' => array('//pidapproval/AllProjects'),
//                                                'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
//                                                'active' => (Yii::app()->controller->id == 'pidapproval' && Yii::app()->controller->action->id == 'pidapproval')
//                                            ),
                                            array('label' => 'View All Projects Details',
                                                'url' => array('//Project/allProject'),
                                                'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
                                                'active' => (Yii::app()->controller->id == 'Project' && Yii::app()->controller->action->id == 'Project')
                                            ),
                                        ),
                                    ),
                                     array('label' => 'Manage Project Task',
                                        'url' => array('//subtask/admin'),
                                       'visible' => ($access_type->access_type == 1),
                                        'active' => (Yii::app()->controller->id == 'subtask' && Yii::app()->controller->action->id == 'subtask')
                                    ),
//                                    array('label' => 'View Project Statistics',
//                                        'url' => array('//resourceallocationprojectwork/resourcemanagement'),
//                                        'visible' => (CHelper::isAccess("RESOURCEALLOCATION", "full_access")),
//                                        'active' => (Yii::app()->controller->id == 'resourceallocationprojectwork' && Yii::app()->controller->action->id == 'resourcemanagement')
//                                    ),
                                ),
                            ),
                            array(
                                'label' => 'Setting',
                                'url' => '#',
                                'visible' => isset(Yii::app()->session['login']['user_id']),
                                'active' => (Yii::app()->controller->id == 'profile' || Yii::app()->controller->id == 'changepassword' || Yii::app()->controller->id == 'logout'),
                                'items' => array(
//                                    array('label' => 'Status not submit',
//                                        'url' => array('//daycomment/StatusReport'),
//                                        'visible' => CHelper::isAccess("STATUS", "full_access"),
//                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'statusreport')
//                                    ),
                                    array('label' => 'Daily task submission records',
                                        'url' => array('//daycomment/adminAll'),
                                        'visible' => ($access_type->access_type == 1),
                                        'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'adminAll')
                                    ),
                                   array('label' => 'Manage Resource',
                                       'url' => array('//AccessRoleMaster/SetRoles'),
                                       'visible' => ($access_type->access_type == 1),
                                       'active' => (Yii::app()->controller->id == 'AccessRoleMaster' && Yii::app()->controller->action->id == 'SetRoles')
                                   ),
								   array('label' => 'Manage Level',
                                       'url' => array('//levelmaster/admin'),
                                       'visible' => ($access_type->access_type == 1),
                                       'active' => (Yii::app()->controller->id == 'LevelMaster' && Yii::app()->controller->action->id == 'admin'),
									   'items' => array(
											array('label' => 'Level Master',
                                                'url' => array('//levelmaster/admin'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => '',
                                            ),
                                            array('label' => 'Allocate Resource Level',
                                                'url' => array('//levelresourceallocation/create'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'daycomment' && Yii::app()->controller->action->id == 'index'),
                                            )),
                                   ),
                                   array('label' => 'Reports',
                                       'url' => array('//reports/getreports'),
                                       'visible' => ($access_type->access_type == 1),
                                       'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'getReports'),
                                       'items' => array(
                                            array('label' => 'Project Report',
                                                'url' => array('//reports/graphreports'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'graphreports'),
                                            ),
                                            array('label' => 'Resource Report',
                                                'url' => array('//reports/timesheetreports'),
                                                'encodeLabel' => false,
                                                'visible' => 1,
                                                'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'timesheetreports'),
                                            )
                                            // array('label' => 'All Reports',
                                            //     'url' => array('//reports/graphreports'),
                                            //     'encodeLabel' => false,
                                            //     'visible' => 1,
                                            //     'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'graphreports'),
                                            // ),
                                            // array('label' => 'Project Reports',
                                            //     'url' => array('//reports/getreports'),
                                            //     'encodeLabel' => false,
                                            //     'visible' => 1,
                                            //     'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'getReports'),
                                            // ),
                                            // array('label' => 'Timesheet Reports',
                                            //     'url' => array('//reports/gettimesheet'),
                                            //     'encodeLabel' => false,
                                            //     'visible' => 1,
                                            //     'active' => (Yii::app()->controller->id == 'Reports' && Yii::app()->controller->action->id == 'getTimesheet'),
                                            // )
                                        ),
                                   ),
//                                    array('label' => 'Access Roles',
//                                        'url' => array('//roles'),
//                                        'visible' => CHelper::isAccess("STATUS", "full_access"),
//                                        'active' => (Yii::app()->controller->id == 'roles' && Yii::app()->controller->action->id == 'index')
//                                    ),
                                    // array('label' => 'Unlock Daily Status',
                                    //     'url' => array('//ManagedayComment/index'),
                                    //     'visible' => CHelper::isAccess("STATUS", "full_access"),
                                    //     'active' => (Yii::app()->controller->id == 'ManagedayComment' && Yii::app()->controller->action->id == 'ManagedayComment')
                                    // ),
                                    array('label' => 'Logout',
                                        'url' => array('//logout'),
                                        'visible' => !Yii::app()->user->isGuest,
                                    )
                                ),
                            ),
                        ),),),));
            ?>
        </div>

        <div class="cont">
            <?php if (!CHelper::user()->isGuest) { ?>
                <?php if (CHelper::hasFlash('success') && CHelper::getFlash('success') != ''): ?>
                    <div class="alert alert-success">
                        <?php
                        echo CHelper::getFlash('success');
                        CHelper::setFlash('success', '');
                        ?>
                    </div>
                <?php endif ?>
                <?php if (CHelper::hasFlash('error') && CHelper::getFlash('error') != ''): ?>
                    <div class="alert alert-error">
                        <?php
                        echo CHelper::getFlash('error');
                        CHelper::setFlash('error', '');
                        ?>
                    </div>
                <?php endif ?>
                <?php if (CHelper::hasFlash('notice') && CHelper::getFlash('notice') != ''): ?>
                    <div class="alert alert-notice">
                        <?php
                        echo CHelper::getFlash('notice');
                        CHelper::setFlash('notice', '');
                        ?>
                    </div>
                <?php endif ?>
                <?php
                Yii::app()->clientScript->registerScript(
                        'myHideEffect', '$(".alert-success,.alert-notice,.alert-error").animate({opacity: 1.0}, 10000).fadeOut("slow");', CClientScript::POS_READY
                );
                ?>
            <?php } ?>
            <div class="container-fluid"> <?php echo $content ?> </div>
        </div>
        <?php
        CHelper::registerScriptFile(CHelper::getCurrentThemePath() . '/js/left_panel.js');
        CHelper::registerScriptFile(CHelper::getCurrentThemePath() . '/js/common.js', CClientScript::POS_END);
        CHelper::registerScriptFile(CHelper::getCurrentThemePath() . '/js/bootbox.js', CClientScript::POS_END);
        ?>
        <?php
        if (Yii::app()->session['login']['email'] == 'bits.qa@gmail.com') {
            if (Yii::app()->session['login']['survey_taken_count'] <= 4 && isset(Yii::app()->session['login']['user_id'])) {
                if ((Yii::app()->session['login']['survey_taken_count']) == 4) {
                    $event_on_close = 'js:function(event, ui) { $(".ui-dialog-titlebar-close").hide();  }';
                } else {
                    $event_on_close = "";
                }

                if ((Yii::app()->session['login']['survey_taken_count']) < 4) {
                    // Survey count update
                    if (Yii::app()->session['login']['survey_taken_count'] < 5) {
                        Employee::model()->updateAll(array('portal_survey' => Yii::app()->session['login']['survey_taken_count'] + 1), 'emp_id=:emp_id', array(':emp_id' => Yii::app()->session['login']['user_id']));
                    }
                    $_SESSION['login']['survey_taken_count'] = 5;
                }


                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogForm',
                    'options' => array(
                        'open' => $event_on_close,
                        'title' => 'Portal Survey',
                        'autoOpen' => true,
                        'modal' => true,
                        'width' => 1120,
                        'height' => 450,
                        'closeOnEscape' => false
                    ),
                ));
                ?>
                <div class="surveyModel">
                    <?php echo $this->renderPartial('//portalSurvey/survey', array('model' => new PortalSurvey())); ?>
                </div>
                <?php
                $this->endWidget();
            }
        }
        ?>

    </body>

</html>
