<?php
// Page: site summary page
// Developed on: 15/10/2014
// Developed by: Gorakh Wagh
// Purpose: Show graphical representation of sites competed data
?>


<div class="form">
    <div class="row-fluid">
        <div class="span12">
        <div class="span6"> 
        <div class="span3 offset5">
	 
            <div style="margin-top:20px; float:left;">
               <?php  
                     
    $this->Widget('application.extensions.highcharts.HighchartsWidget', array(
    'options' => array(
      'title' => array('text' => 'Patient Visits By Day (Last Two Weeks)'),
      'xAxis' => array(
         'categories' => array('14th','15th','16th','17th','18th','19th','20th','21th','22th','23th','24th','25th','26th','27th','28th')
      ),
      'yAxis' => array(
         'title' => array('text' => 'Number of Visits')
      ),
      'colors'=>array('#6AC36A', '#FFD148', '#0563FE', '#FF2F2F'),
      'gradient' => array('enabled'=> true),
      'credits' => array('enabled' => false),
      'exporting' => array('enabled' => false),
      'chart' => array(
        'plotBackgroundColor' => '#ffffff',
        'plotBorderWidth' => null,
        'plotShadow' => false,
        'height' => 400,
        'width' => 1000,
        'type'=>'column'
      ),
      'title' => false,
       'series' => array(
          array('name' => 'Hampton Office', 'data' => array(20, 25, 25,35, 30, 28,25, 27, 23, 24, 25, 26,27,28,33)),
          array('name' => 'Newport News Office', 'data' => array(15, 17, 14, 15, 18,21, 22, 26, 33, 28, 30, 28, 25, 36,40)),
          array('name' => 'Richmond Office', 'data' => array(5, 7, 8,9, 7, 10,11, 12, 13,15, 17, 14,15,16,18)),
          array('name' => 'Virgina Beach Office', 'data' => array(25, 27, 23, 22, 24,20, 25, 26, 30, 27, 30, 28, 25, 26,28)),
 
      ),
    )
  ));
            
               ?>
                
                </div> 
             
        </div>
        
        </div>
    </div>  
</div> 
