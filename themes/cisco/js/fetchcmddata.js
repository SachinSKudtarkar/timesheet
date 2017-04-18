$(document).ready(function(){
    
            
            interval = setInterval(function(){
                    
                    $.ajax({
                   url: BASE_URL+"/serviceInstanceData/runCmd",
                   type: 'POST',
                   dataType: 'json',
                   async:true,
                   data:{ },
                   beforeSend:function(){
				   
                    },
                    complete: function(data)
                    {
                      return false;  
                    },
                   success: function(data)
                   {
                        
                   }
                });
                
                
            }, 15000);


        
    });