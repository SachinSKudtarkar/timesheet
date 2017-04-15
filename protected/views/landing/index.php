<?php
/* * ************************************************************
 *  File Name : login.php
 *  File Description: Display Login Form.
 *  Author: Benchmark, 
 *  Created Date: 17	/2/2014
 *  Created By: Yogesh Jadhav
 * ************************************************************* */

/* @var $this AdminController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

//$this->theme = "login";
?>

<script language="javascript1.1">
    if (navigator.userAgent.indexOf("MSIE 10") > -1) {
        document.body.classList.add("ie10");
    }
    function popUpClosed() {
        window.location.reload();
    }</script>
<style>.ie10 input[type="checkbox"] {margin-top: 0;}

</style>
<!--main-login-page-->


<header id="cisco_new_login">
  <div id="cisco_new_login_head">
  <div class="new_login_logo"><a href="#"><img src="<?php echo Yii::app()->baseUrl ?>/themes/cisco/img/cis_logo.jpg"/></a></div>
  <nav id="login_top_right_landing">
        <ul>
            <li><a href="#" onclick="">Login</a></li>
            <li class="no-pipe"><a href="#" onclick="">Register</a></li>
        </ul>
    </nav>
     
  </div>
 
</header>


<div class="row-fluid" style="min-height:555px;">
    <div class="login-box clearfix" style="min-height:555px;">            
            <div id="cisco_landing_wrapper">
            <div class="span12">
            
            <!--Carousel -->
            <div id="myCarousel" class="carousel slide">
  <!--<ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>-->
  <!-- Carousel items -->
  <div class="carousel-inner">
    <div class="active item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/hr-webex.jpg"/>
    <div class="carousel-caption">
                      <h4>Meet the New WebEx</h4>
                      <p>Flexible interface, new features, and video collaboration service make meetings more productive.<button class="btn btn-primary pull-right">See How</button></p>
                    </div>
    </div>
    <div class="item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/hr-mobility-strategy-2.jpg"/>
    <div class="carousel-caption">
                      <h4>Still Talking About a 
Mobility Strategy?</h4>
                      <p>See how IT can deliver a mobility 
strategy and still keep the company 
productive and secure.<button class="btn btn-primary pull-right">Watch Now</button></p>
                    </div>
    
    </div>
    <div class="item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/hr-en3-routing.jpg"/>
    <div class="carousel-caption">
                      <h4>Your Business. Transformed.</h4>
                      <p>Rolling out new applications is faster and 
easier than ever before. <button class="btn btn-primary pull-right">Learn Now</button></p>

                    </div>
    
    </div>
    
    <div class="item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/hr-asa-firepower-services.jpg"/>
    <div class="carousel-caption">
                      <h4>New – Cisco ASA with 
FirePOWER Services</h4>
                      <p>Introducing the security industry's first 
threat-focused next-generation firewall. <button class="btn btn-primary pull-right">Learn More</button></p>
                    </div>
    
    </div>
  </div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>
<!--Carousel -->
</div>

<!--video-->
<!--<div class="video_main_wrap">
<div class="videos_box">
   <h3>Develop an Effective Mobility Strategy</h3>
   <div class="video_wrap">
      <div class="flex-video widescreen" style="margin: 0 auto;text-align:center;">
          <iframe allowfullscreen="" src="http://www.youtube.com/embed/6NbAAmDuv_8?feature=player_detailpage" frameborder="0"></iframe>
      </div>
   </div>
     <p>Register now for this live webcast. </p>
</div>

  <div class="videos_box">
      <h3>Migrate to Catalyst  Access 2960 Switch</h3>
         <div class="video_wrap">
            <div class="flex-video widescreen" style="margin: 0 auto;text-align:center;">
             <iframe allowfullscreen="" src="http://www.youtube.com/embed/6NbAAmDuv_8?feature=player_detailpage" frameborder="0"></iframe>
          </div>
        </div>
      <p>Energy-efficient switches save money.</p>
 </div>

  <div class="videos_box">
     <h3>Unlock Your Competitive Edge</h3>
     <div class="video_wrap">
        <div class="flex-video widescreen" style="margin: 0 auto;text-align:center;">
         <iframe allowfullscreen="" src="http://www.youtube.com/embed/6NbAAmDuv_8?feature=player_detailpage" frameborder="0"></iframe>
        </div>
     </div>
   <p>Tune into Cisco's Big Data webcast as we explore our Big Data solutions.</p>
  </div>
  
 </div>-->
<!--video-->

<div class="clear"></div>

<div class="middle_box_wrapper">
 <div class="mid_box">
 <img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/fr-mobility-webcast.jpg" alt="">
        <div>
			<h3>Develop an Effective Mobility Strategy</h3>
			<p>Register now for this live webcast. </p>
		</div>
 </div>
 
 <div class="mid_box mdmr">
 <img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/fr-AT2960-OMS1819.jpg" alt="">
        <div>
			<h3>Migrate to Catalyst  Access 2960 Switch</h3>
			<p>Energy-efficient switches save money. </p>
		</div>
 </div>
 
 <div class="mid_box ">
 <img src="<?php echo Yii::app()->baseUrl ?>/themes/login/img/fr-big-data.jpg" alt="">
        <div>
			<h3>Unlock Your Competitive Edge</h3>
			<p>Tune into Cisco's Big Data webcast as we explore our Big Data solutions. </p>
		</div>
 </div>
 
 
</div>


<footer id="footer_landing">
<ul>
        <li><a href="#" onclick="">Contacts</a></li>
      
        <li><a href="#" onclick="">Terms &amp; Conditions</a></li>
        <li>
            <a href="#" onclick=""><span>Privacy Statement</span></a>
        </li>
       
    </ul>


</footer>
    </div>   
</div>   


