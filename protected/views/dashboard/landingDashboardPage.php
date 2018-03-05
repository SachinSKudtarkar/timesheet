<?php
/* * ************************************************************
 *  File Name : landingDashboardPage.php
 *  File Description: Display Landing Page Page on occasion.
 *  Author: Mahesh Jagadale<maheshjagadale@benchmarkitsolutions.com>,  
 * ************************************************************* */
?>


<?php
//echo "<pre>";print_r(Yii::app()->session['login']);
$user_name = Yii::app()->session['login']['first_name'] . " " . Yii::app()->session['login']['last_name'];
?>

<div class="form-group clearfix test-panal" style="margin-top: 20px;">
    <h3>Welcome, <?php echo ucwords($user_name); ?></h3>
</div>


<div class="row-fluid" style="min-height:555px;">
    <div class="login-box clearfix" style="min-height:555px;">            
        <div id="cisco_landing_wrapper">
            <div class="span12">

                <!--Carousel--> 
                <div id="myCarousel" class="carousel slide">

                    
                    <!--Carousel items--> 
                    <div class="carousel-inner">
                        <div class="active item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/cisco/img/Jagadish_Chandra_Bose.jpg"/>
                            <div class="carousel-caption">
                                <h4>Sir Jagadish Chandra Bose</h4>
                                <p> He pioneered the investigation of radio and microwave optics. IEEE named him one of the fathers of radio science.</p>
                            </div>
                        </div>
                        <div class="item"><img src="<?php echo Yii::app()->baseUrl ?>/themes/cisco/img/microwave_apparatus_jcb.jpg"/>
                            <div class="carousel-caption">
                                <h4>Microwave Apparatus </h4>
                                <p>Bose's 60 GHz microwave apparatus at the Bose Institute, Kolkata, India. His receiver (left) used a galena crystal detector inside a horn antenna and galvanometer to detect microwaves. Bose invented the crystal radio detector, waveguide, horn antenna, and other apparatus used at microwave frequencies.</p>
                            </div>

                        </div>



                    </div>

                    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                </div>

            </div>



            <div class="clear"></div>





        </div>   
    </div>   

    -->
