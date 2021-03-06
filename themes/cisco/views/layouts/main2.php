<?php
Yii::app()->bootstrap->register();
Yii::app()->clientscript;
$baseUrl = Yii::app()->getBaseUrl(true);
//Yii::app()->clientScript->scriptMap['bootstrap.css'] = false;
$user_email = Yii::app()->session["login"]["email"];
$user_email = base64_encode($user_email);
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
            <?php
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse', // null or 'inverse'
                'brand' => "", //'<img src="' . Yii::app()->theme->baseUrl . '/img/cis_logo.jpg" />',
                'brandOptions' => array('class' => 'no-link-cur span2'),
                'brandUrl' => CHelper::baseUrl(true) . '/',
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'nav nav-tabs'),
                        'activeCssClass' => 'active',
                        'items' => array(
                            array(
                                'label' => 'Dashboard',
                                'url' => array('//daycomment/adminAll'),
                                'visible' => 1,
                                'active' => (Yii::app()->controller->id == 'dashboard' && Yii::app()->controller->action->id == 'index'),
                            ),
                            array(
                                'label' => 'task',
                                'url' => '#',
                                'visible' => 1,
                                'active' => 0,
                                'items' => array(
                                    array('label' => 'Add Comment',
                                        'url' => array('//daycomment'),
                                        'encodeLabel' => false,
                                        'visible' => 1,
                                    ),
                                    array('label' => 'Allocate Resource',
                                        'url' => array('//ResourceAllocationProjectWork/admin'),
                                        'visible' => 1,
                                    )
                                ),
                            ),
                            array(
                                'label' => 'Setting',
                                'url' => '#',
                                'visible' => 1,
                                'active' => (Yii::app()->controller->id == 'profile' || Yii::app()->controller->id == 'changepassword' || Yii::app()->controller->id == 'logout'),
                                'items' => array(
                                    array('label' => 'Portal Survey',
                                        'url' => array('//PortalSurvey/admin'),
                                        'encodeLabel' => false,
                                        'visible' => CHelper::isAccess("ADMIN"),
                                    ),
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
