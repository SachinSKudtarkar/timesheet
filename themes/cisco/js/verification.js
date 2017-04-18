$(document).ready(function(){

$('.autoInterfaceConigT').live("click",(function(container){
    return function(){
   
        var thisBtn = this;
        var interval;
        var processing = 0;
        var cnt = 0;
        var isConnected = 0;
		
        var cmdsArray = new Array("Configuring Interface Description");
        
        $('#rightsection').html("");
        $('#leftsection').html("");
        $('#StatusLabel').html("");
        
        for(var i =0; i<cmdsArray.length; i++)
        {
            var  currCmd = cmdsArray[i];
            var id = currCmd.replace(/\s+/g, '');
            var str='<div title="Commands fetch successfully" id="'+id+'" class="netDevices">'+cmdsArray[i]+'</div>';
            $('#rightsection').append(str);
        }
    
        interval = setInterval(function(){
            
            //if($(thisBtn).val()!="Verification"){ return false ;}
            
            if(processing==0)
            {   
                var currCmd = cmdsArray[cnt];
                var id = currCmd.replace(/\s+/g, '');
                var self_loopback0 = $(thisBtn).closest('tr').find('td:nth-child(3)').html();
                var sap_id = $(thisBtn).closest('tr').find('td:first').html();
                var hostname = $(thisBtn).closest('tr').find('td:nth-child(2)').html();
                
                $.ajax({
                    url: BASE_URL+"/AutomationIntegration/automationInterfaceConfigT",
                    type: 'post',
                    dataType: 'json',
                    data: {sap_id : sap_id, hostname : hostname, action:cmdsArray[cnt] },
                    beforeSend:function(){
                        $('#'+id).addClass("processing");
                        processing = 1;
                     },
                     complete:function(){
                        processing = 0;
                        $('#'+id).removeClass("processing");
                        cnt++;
                     },
                    success: function(data){
                        if(data.result=="SUCCESS")
                        {
                            $('#leftsection').append("<pre>"+data.content+"</pre>");
                            $('#'+id).addClass("success");
                        }
                        else
                        {
                            if(data.connection=="break")
                            {
                                $('#StatusLabel').html(data.description+". Process stopped");
                                isConnected = 0;
                                clearInterval(interval);
                                $('#'+id).removeClass("processing");
                            }
                            else
                            {
                                $('#'+id).removeClass("processing");
                                $('#'+id).addClass("failed");
                                $('#'+id).attr("title",data.description);
                            }
                        }
                    },
                    error: function(XMLHttpRequest, data, errorThrown){
                                $('#'+id).removeClass("processing");
                                $('#'+id).addClass("failed");
                    },
                });
                
                if(cnt==(cmdsArray.length-1))
                {
                    isConnected = 0;
                    clearInterval(interval);
                }
            }
            
        },1000);
        
    };
    
})(""));


$('.netDevices').live('click',function(){
    
   var target = $(this).attr("id");
   
    $('#leftsection').animate({
        scrollTop: $('#output_'+target).offset().top
    }, 2000);
    console.log('#output_'+target);
});

});