<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<!--        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/cisco/css/gridview.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/cisco/themes/cisco/css/style.css" />-->

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page">

            <div id="header">
                <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
            </div><!-- header -->

            <div id="mainmenu">
                <?php
                if (Yii::app()->controller->id != 'nddhostname')
                {
                $this->widget('zii.widgets.CMenu', array(
                     'items' => array(
                        array('label' => 'Dashboard', 'url' => array('/site/dashboard',)),
                        array('label' => 'Hostname', 'url' => array('/nddHostName/create',)),
                        array('label' => 'Microwave Addr', 'url' => array('/nddMicrowaveAddr/create',)),
                        array('label' => 'eNB Addr', 'url' => array('/nddEnbAddr/create',)),
                        array('label' => 'Utility Addr', 'url' => array('/nddUtilityAddr/create',)),
                        //array('label' => 'AG2 Region Mapping', 'url' => array('/nddIpRegion/create',)),
                        array('label' => 'AG1X2', 'url' => array('/nddAg1x2/create',)),
                        array('label' => 'AG2 Region Mapping', 'url' => array('/NddAg2RegionMapping/create')),
                        array('label' => 'AG2 X2 Mapping', 'url' => array('/NddAg2X2Mapping/create')),
                        array('label' => 'Sapid Manager', 'url' => array('/nddSapidManager/create',)),
                        array('label' => 'RAN LB', 'url' => array('/nddRanLb/create',)),
                        array('label' => 'RAN WAN', 'url' => array('/nddRanWan/create',)),
                        array('label' => 'PTP', 'url' => array('/nddPtp/create',)),
                        array('label' => 'PM', 'url' => array('/nddPmList/create',)),
                        array('label' => 'Upload Input File', 'url' => array('/nddTempInput/upload')),
                        array('label' => 'Download NDD', 'url' => array('/nddOutputMaster/admin')),
                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                    ),                    
                ));
                }
                else
                {
                     $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Generate Hostname', 'url' => array('/nddhostname/generatehostname',)),
                        )));
                        
                }
                ?>
            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>
            <?php $flashMessages = Yii::app()->user->getFlashes(); ?>
            <?php if (!empty($flashMessages)): ?>
                <div style="margin:auto;">
                    <strong>
                        <?php
                        foreach ($flashMessages as $key => $message) {
                            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
                        }
                        ?>
                    </strong>
                </div>
            <?php endif; ?>
            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by Company.<br/>
                All Rights Reserved.<br/>
                <?php // echo Yii::powered();  ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>