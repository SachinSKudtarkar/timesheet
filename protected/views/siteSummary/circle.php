<?php
// Page: site summary page
// Developed on: 15/10/2014
// Developed by: Gorakh Wagh
// Purpose: Show graphical representation of sites competed data
?>
 
        <div class="form-group clearfix test-panal" style="margin-top: 20px;">
             <h4>Circle-Wise Performance Chart</h4>    
            <form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
        
        <div class="span2 offset1" >
            <label><b>Circle</b></label>    
            <?php
            
             // Get data for Employee's Names list   
             $eid = (isset($_GET['e'])&& $_GET['e']!= '')?$_GET['e']:0; 
             $wid = (isset($_GET['w'])&& $_GET['w']!= '')?$_GET['w']:0;
             $did = (isset($_GET['d'])&& $_GET['d']!= '')?$_GET['d']:0;
             $mid = (isset($_GET['m'])&& $_GET['m']!= '')?$_GET['m']:0;
            
             $datesArrF = $data; // getting array of all keys. this $data array.. came from SiteSummary Model -> siteSummaryController ->individual.php [this page]s
                
             
             $start_week = strtotime("last monday midnight"); 
             $from_week = strtotime("-1 week",$start_week);
             $to_week = strtotime("-2 week",$start_week);
             $empData = !empty($empData)?$empData:array();
             $datesArrFinal = array();
 
             if(!empty($data))
             $datesArrFinal = array_keys($data);  
         
                  
            $circle_id = isset($filterOptions['e']) ? $filterOptions['e'] : 0;
            echo CHtml::dropDownList("empNames", $eid, array("0"=>"All")+CHtml::listData(CircleMaster::model()->findAll(), 'id', 'circle_name'), array('class' => 'span12', 'prompt' => 'Please select'));
          
            ?>
        </div>
        <div class="span1 offset1">
            <label><b>Daily</b></label>    
            <?php
            //$circle_id = isset($filterOptions['circle_id']) ? $filterOptions['circle_id'] : 0;
           // echo CHtml::dropDownList("drp_daily", $circle_id, CHtml::listData(CircleMaster::model()->findAll(), 'id', 'circle_name'), array('class' => 'span12', 'prompt' => 'Select'));
            $tdarray = array();          
            for($i = 1; $i < 7; $i++)
            {                
                    $tdarray[$i] = 'Last '.(5*$i).' days';                          
            }               
            echo CHtml::dropDownList('drp_daily',$did,$tdarray,array('empty'=>'Select Value')); 
            ?>
        </div>
                 <div class="span1 offset1">
                     <label><b>Weekly</b></label>    
            <?php
            $tdarray = array();            
            for($i = 1; $i < 7; $i++)
            {                
                $tdarray[$i] = 'Last '.(5*$i).' Weeks';              
            }
           echo CHtml::dropDownList('drp_weekly',$wid,$tdarray,array('empty'=>'Select Value')); 
            ?>
        </div>
                 <div class="span1 offset1">
                     <label><b>Monthly</b></label>    
            <?php             
            $tdarray = array();
            $sarray = array();
            for($i = 1; $i < 7; $i++)
            {
                $tdarray[$i] = 'Last '.(5*$i).' Months';              
            }
            echo CHtml::dropDownList('drp_monthly',$mid,$tdarray,array('empty'=>'Select Value')); 
            ?>
        </div>
            </form>
              <div class="hr-line"> <span class=""></span></div>
	 
            <div style="margin-top:30px; float:left; margin-left:90px;" id="dynGraphs">
               <?php  
               
               
               // Write your logic here
               
                $dateToday = date('Y-m-d h:i:s');
                $dayToday = date('d');
                $daysCount = (isset($_GET['d'])&& $_GET['d']!= '')?$_GET['d']:0; 
                $weeksCount = (isset($_GET['w'])&& $_GET['w']!= '')?$_GET['w']:0;
                $monthsCount = (isset($_GET['m'])&& $_GET['m']!= '')?$_GET['m']:0;
                $catCondArray = array();
                $seriesValuesArray = array();
          
                if($daysCount)
                {    
                    for ($i = 0; $i < ($daysCount*5); $i++) {
                        $catCondArray[] = date('jS', strtotime("-$i days"));
                        $currdate = date('Y-m-d',strtotime("-$i days"));
                        if(in_array($currdate,$datesArrFinal))
                        {
                            $seriesValuesArray[] = $datesArrF[$currdate]; 
                        }
                        else
                        {
                            $seriesValuesArray[] = 0;
                        }
                    }
                }
                else
                if($weeksCount)
                {
                                    
                    $start_week = strtotime("this monday midnight");                    
                    
                     for ($i = 0; $i < ($weeksCount*5); $i++) {
                        
                         $t = $i+1;
                         $from_week = strtotime("-$i week",$start_week);
                         $to_week = strtotime("-$t week",$start_week);
                         $frmDate = date('Y/m/d',strtotime("-1 days",$from_week));
                         $frmToCalstr = strtotime($frmDate);
                         $total = 0;
                         foreach($datesArrFinal as $f)
                         {
                             $strtime = strtotime($f);
                             
                             if($strtime <= $frmToCalstr && $strtime >= $to_week)
                             {
                                $total += $datesArrF[$f];
                             }                             
                         }
                         
                          $seriesValuesArray[] = $total;
                          
                          $fromw =  date('j-M',strtotime("-1 days",$from_week)); 
                          $tow = date('j-M',$to_week);                         
                                                   
                          $catCondArray[] = $fromw.'<br>'.$tow;                                    
                         
                    }
                }
                else
                if($monthsCount)
                {
                     for ($i = 0; $i < ($monthsCount*5); $i++) {
                        
                        $shwstring = date('M/y', strtotime("-$i months"));
                        $catCondArray[] = $shwstring;
                        $addCount = 0;
                        foreach($datesArrFinal as $f)
                         {
                                $dtstr =  date('M/y',strtotime($f));
                                if($shwstring == $dtstr)
                                {
                                    $addCount += $datesArrF[$f];
                                }
                                
                        }
                        $seriesValuesArray[] = $addCount;
                    }
                }
                else if(!$monthsCount && !$weeksCount && !$daysCount)
                {
                    ?>
                <div style="margin-left:40px;font-size: 16px; color:navy;">NOTE: Please select circle to view performance chart against daily, weekly or monthly.</div>
                  <?php   
                }
               
                 if($monthsCount || $weeksCount || $daysCount)
                 {
                  $this->Widget('application.extensions.highcharts.HighchartsWidget',
                          array(
                                'scripts' => array(
                                                    'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                                  //  'modules/exporting', // adds Exporting button/menu to chart
                                                    'themes/grid-light'        // applies global 'grid' theme to all charts
                                                  ),
                                'options' => array(
                                                    'title' => array('text' => 'Sites Completed By Day (Last Two Weeks)'),
                                                    'xAxis' => array(
                                                    'categories' => $catCondArray
                                                   ),
                                'yAxis' => array(
                                                    'title' => array('text' => 'Number of Sites Completed')
                                                ),
                                                    'colors'=>array('#0563FE', '#6AC36A', '#FFD148', '#FF2F2F'),
                                                    'gradient' => array('enabled'=> false),
                                                    'credits' => array('enabled' => false),
                                                     /*'exporting' => array('enabled' => false),*/ //to turn off exporting uncomment
                                'chart' => array(
                                                    'plotBackgroundColor' => '#ffffff',
                                                    'plotBorderWidth' => null,
                                                    'plotShadow' => true,
                                                    'height' => 500,
                                                    'width' => 1200,
                                                 ),
                                'title' => false,
                                'series' => array(
                                                    array('type'=>'column','name' => 'Performance', 'data' => $seriesValuesArray),
                                                /*  array('type'=>'spline','name' => '', 'data' => array(20, 25, 25,35, 30, 28,25, 27)),
                                                    array('type'=>'spline','name' => '', 'data' => array(5, 7, 8,9, 7, 10,11, 12)), 
                                            array(
                                              'type'=>'pie',
                                              'name' => 'Richmond Office',
                                              'data' => array(5, 7, 8),
                                              'dataLabels' => array(
                                                'enabled' => false,
                                              ),
                                'showInLegend'=>false,
                                'size'=>'10',
                                'center'=>[20, 20],
          ), */
      ),
    )
  ));
                 }
               
                ?>
                
                </div> 
             
        </div>

 
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#empNames").change(function(){
            
            $e = $(this).val();
            $d = $("#drp_daily").val();
            $w = $('#drp_weekly').val();
            $m = $('#drp_monthly').val();
            if($e != '' && ($d != '' || $w != '' || $m != ''))
            {
                $parameters = '&e='+$e+'&d='+$d+'&w='+$w+'&m='+$m;            
                window.location = "?"+$parameters;
            }
            
        });
        
        $("#drp_daily").change(function(){             
            $("#drp_weekly").val('');
            $("#drp_monthly").val('');
            $e = $("#empNames").val();
            $d = $(this).val();
            if($e != '' && $d != '')
            {
                $parameters = '&e='+$e+'&d='+$d;            
                window.location = "?"+$parameters;
            }
        });
        
        $("#drp_weekly").change(function(){             
            $("#drp_daily").val('');
            $("#drp_monthly").val('');
            $e = $("#empNames").val();
            $w = $(this).val();
            if($e != '' && $w != '')
            {
                $parameters = '&e='+$e+'&w='+$w;            
                window.location = "?"+$parameters;
            }
        });
        
        $("#drp_monthly").change(function(){            
            $("#drp_weekly").val('');
            $("#drp_daily").val('');
            $e = $("#empNames").val();
            $m = $(this).val();
            if($e != '' && $m != '')
            {
                $parameters = '&e='+$e+'&m='+$m;            
                window.location = "?"+$parameters;
            }
        });
    });
</script>
        
    
