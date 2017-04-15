<?php
Yii::app()->bootstrap->register();
Yii::app()->clientscript;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="sitelock-site-verification" content="1963" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="language" content="en" />
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- Le styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
        <!-- Le fav and touch icons -->
    </head>
    <body style="padding-top:0px;">
        <!-- main container -->
        <div class="cont">
            <div class="container-fluid">
                <?php echo $content ?>
            </div>
        </div>
        <!-- /main container -->
        <?php
//$js = Yii::app()->getClientScript();
//$js->registerScriptFile( Yii::app()->theme->baseUrl . '/js/bootstrap-hover-dropdown.min.js', CClientScript::POS_END ); 
        ?>
        <div style="text-align: center">
            <span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=7N7Lya9fwCDdaNiKf331McRJqtV0uDjWd6CSQN1spBZXqa4C97YeC1o"></script></span>
        </div>
    </body>
</html>
