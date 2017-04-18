<?php
 


Yii::app()->clientScript->registerScript('TestSites', "   

  function validateIP(value)
        {
                          
                var ipv4 = /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/;    
                return value.match(ipv4);
           
        }

   $('.checkHost').live('click',function(){
	        var lpback0 = $('#loopback0').val();
			if(lpback0.trim() == '')
            {
                alert('Please enter comma seperated loopback0 to check.');
                return false;
            }
            var lpback0_arr =  lpback0.split(',');
			var lpbkln = lpback0_arr.length;
            
            var finalips = '';;
            
            for(var i=0; i < lpbkln; i++)
            {
                if(lpback0_arr[i].length != 0)    
                { 
                    var reslt = validateIP(lpback0_arr[i]);
                    if(reslt)
                    {
                        finalips += reslt+',';
                    }                    
                }
            } 
  
			  $('.custom-loader').show(); 
			  
				hostnames = '';		  

			    $.ajax({               
				url: '" . CHelper::createUrl("TestSites/gethostnames") . "',
                type:'POST',               
			    data: {loopback0 : finalips},
                success:function(data)
                {   				 
                   $('.custom-loader').hide();
				   var hostnames = data.split('===');
				   $('#hostnames').val(hostnames[0]);
                   $('#ip_host_name_str').val(hostnames[1]);
				   $('.hostnames').show(); 
                }
            });
     
           
    }); 

	$('.checkTest').live('click',function(){
	
		 var scrlprmtop = 300;
         var scrlinterval = 0;
         $(window.opera ? 'html' : 'html, body').animate({scrollTop: 300},1500);
         var hstnames = $('#hostnames').val();
         var mixedString = $('#ip_host_name_str').val();
         var mixArray = mixedString.split('|||');
         var looplength = mixArray.length -1;

		 var tableString = '<table';
         tableString += ' border';
		 tableString += ' = ';
		 tableString += ' 1 ';
		 tableString += ' class ';
		  tableString += ' = ';
		 tableString += ' tblformat ';
		 tableString += '>';
		 tableString += '<thead><th>Loopback0</th><th>Hostnames</th><th>Test Result (Pass/Fail)</th></thead>';
         tableString += '<tbody>';
         $('#dynTabletest').show();

		 
		 for(var i =0; i < looplength; i++)
		 {
			      var lp_hs = mixArray[i].split('=>');
                  var lp = lp_hs[0];
                  var hs = lp_hs[1];
				  var spname = lp_hs[2];
				  var spid = lp_hs[3];
				  var rslt = '';
				  scrlprmtop += 10;
                  scrlinterval += 20; 

				       
                $.ajax({ 
						    url: '" . CHelper::createUrl("TestSites/tstconnection") . "',
                            type:'POST',
                            async:false,                         
							data: {lp_hs : lp_hs},
                            beforeSend:function()
                            {
                                 // $('.custom-loader').show();
                                  $(window.opera ? 'html' : 'html, body').animate({scrollTop: scrlprmtop},scrlinterval); 
                            },
                            success:function(data)
                            {   
                                rslt = data;								 
                                $(window.opera ? 'html' : 'html, body').animate({scrollTop: scrlprmtop},scrlinterval);
                            }
                    });
                 
				 tableString += '<tr>';
				 tableString += '<td>';
				 tableString += lp;
				 tableString += '</td>';
				 tableString += '<td>';
				 tableString += hs;
				 tableString += '</td>';
				 tableString += '<td>';
				 tableString += rslt;
				 tableString += '</td>';                 
                               // $('.custom-loader').hide();
                 tableString += '</tr>';                   
                 $('#dynTabletest').html(tableString); 

		 }
		      
		 
	
	});
	
");

?>
 <style>
 .tblformat{ width:1000px; text-align:center; background-color:white;}
 </style>
        <div class="form-group clearfix test-panal" style="margin-top: 20px;">
             <h4>Test Sites</h4>    
            <form class="form-inline no-mr clearfix" action="" method="get" id="graphFiltersForm">
        
        <div class="span2 offset1" >
            <label><b>Loopback0</b></label>    
            <?php
            
            echo CHtml::textArea("loopback0", '', array('rows'=>6, 'cols'=>100));
          
                echo "<br>&nbsp";
            
          // echo CHtml::button('Check', array('submit' => array('testSites/result')));
		   echo CHtml::button('Check', array('class'=>'checkHost','style'=>'margin:0'));
            ?>
          
        </div>
       
   
                 <div class="span1 offset1 hostnames hide">
                     <label><b>Host Names</b></label>    
            <?php             
         
            echo CHtml::textArea('hostnames','',array('rows'=>12, 'cols'=>100)); 
            echo "<br>&nbsp";
           // echo CHtml::button('Go', array('submit' => array('site/checkConnection')));
		    echo CHtml::button('Go', array('class'=>'checkTest','style'=>'margin:0'));
            
            
            //if(isset(Yii::app()->session['failorpass']))
            //unset(Yii::app()->session['failorpass']);
            ?>
        </div>
                
                
            </form>
              <div class="hr-line"> <span class=""></span></div>
	 
              <div style="margin-top:30px; float:left; margin-left:110px;" id="dynTabletest" class="hide">
               <input name="ip_host_name_str" type="hidden" value="" id="ip_host_name_str"></input>
               <input name="rtrn_result" type="hidden" value="" id="rtrn_result"></input>
             <!--   <table style="width: 940px; background-color: white;" border="1">
                    <thead>
                    <th>Loopback0</th>
                    <th>Hostnames</th>
                    <th>Test Result (Pass/Fail)</th>
                    </thead>
                    <tbody style="text-align: center">
                        <tr><td>asdf</td><td>asdf</td><td>Fail</td></tr>
                        <tr><td>asdf</td><td>asdf</td><td>Fail</td></tr>
                        <tr><td>asdf</td><td>asdf</td><td>Fail</td></tr>
                        <tr><td>asdf</td><td>asdf</td><td>Fail</td></tr>
                    </tbody>
                </table> -->
                 
                
            </div> 
              
              
             
        </div>

 
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        
        function validateIP(value)
        {
                          
                var ipv4 = /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/;    
                return value.match(ipv4);
           
        }
      
      /*     $(".checkTest").click(function(){           
            $lpback0 = $("#loopback0").val();
            if($lpback0.trim() == '')
            {
                alert("Please enter comma seperated loopback0 to check.");
                return false;
            }
            $lpback0_arr =  $lpback0.split(',');
            
            $lpbkln = $lpback0_arr.length;
            
            var finalips = [];
            
            for($i=0; $i < $lpbkln; $i++)
            {
                if($lpback0_arr[$i].length != 0)    
                { 
                    $reslt = validateIP($lpback0_arr[$i]);
                    if($reslt)
                    {
                        finalips.push($reslt);
                    }                    
                }
            }             
            
            $('.custom-loader').show();	
            
            $hostnames = '';
        
            
            $.ajax({
                url:'gethostnames',
                type:'POST',
                data:'loopback0='+finalips+'',
                success:function(data)
                {     
                   $('.custom-loader').hide();  
                   if(data == '===')
                   {
                        alert("No hostname found for the given loopback0(s)."); 
                   }
                   else
                   {
                        $hostnames = data.split('===');
                        $(".hostnames").show(); 
                        $("#hostnames").val($hostnames[0]);
                        $("#ip_host_name_str").val($hostnames[1]);

                   }
                }
            });
            
            
        }); 
           $("#yt1").click(function(){
               
               $scrlprmtop = 300;
               $scrlinterval = 0;
               $(window.opera ? 'html' : 'html, body').animate({scrollTop: 300},1500);
               $hstnames = $("#hostnames").val();
               $mixedString = $("#ip_host_name_str").val();
               $mixArray = $mixedString.split("|||");
               $looplength = $mixArray.length -1;
                
               
               $tableString = '<table style="width: 940px; background-color: white;" border="1">';
               $tableString += '<thead><th>Loopback0</th><th>Hostnames</th><th>Test Result (Pass/Fail)</th></thead>';
               $tableString += '<tbody style="text-align: center">';
               $("#dynTabletest").show();
                
               
               for($i =0; $i < ($looplength); $i++)
               {
                    $lp_hs = $mixArray[$i].split('=>');
                    $lp = $lp_hs[0];
                    $hs = $lp_hs[1];
                   
                    $rslt = '';
                    
                      
                    $scrlprmtop += 10;
                    $scrlinterval += 20; 
                    
                    
                $.ajax({
                            url:'tstconnection',
                            type:'POST',
                            async:false,
                            data:'lp_hs='+$lp_hs+'',
                            beforeSend:function()
                            {
                                 // $('.custom-loader').show();
                                  $(window.opera ? 'html' : 'html, body').animate({scrollTop: $scrlprmtop},$scrlinterval); 
                            },
                            success:function(data)
                            {   
                                $rslt = data;
                                $(window.opera ? 'html' : 'html, body').animate({scrollTop: $scrlprmtop},$scrlinterval);
                            }
                    });   
                    
                    $tableString += '<tr>';
                                 $tableString += '<td>'+$lp+'</td>';
                                 $tableString += '<td>'+$hs+'</td>';
                                 $tableString += '<td><span>'+$rslt+'</span></td>';
                               // $('.custom-loader').hide();
                                $tableString += '</tr>';                   
                                $("#dynTabletest").html($tableString); 
                    
               }
               
               $tableString += '</tbody></table>';
              
               
               $("#dynTabletest").html($tableString);
         
    
           });*/
    });
</script>
        
    