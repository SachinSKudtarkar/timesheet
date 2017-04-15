$(document).ready(function(){
    
//----- Search record from database.    
    
    $('#SearchBtn').click(function(){
        
        if($(this).val()!="Search"){ return false; }
        
        var chkValid = 0;
        var hostname = $('#hostname').val();
        var sapid = $('#sapid').val();
        var neid = $('#neid').val();
        var loopback0 = $('#loopback0').val();
        
        if(hostname=="")
            chkValid++;
        if(sapid=="")
            chkValid++;
        if(neid=="")
            chkValid++;
        if(loopback0=="")
            chkValid++;
            
        if(chkValid>1)
        {
            $('#processStatus').css("color","#FF0000");
            return false;
        }
        
        $('#processStatus').css("color","#000000");

        $.ajax({
               url: BASE_URL+"/RouterIntegration/SearchRecord",
               type: 'POST',
               dataType: 'json',
               data:{hostname:hostname, sapid:sapid, neid:neid, loopback0:loopback0},
               beforeSend:function(){
                    $("#SearchBtn").attr("value","Searching...");
                },
                complete:function(){
                    $("#SearchBtn").attr("value","Search");
                },
               success: function(data)
               {
                    if(data.result=="SUCCESS")
                    {
                        $("#processStatus").html("<span style='color:#009900'>"+data.description+"</span>");
                        
                        $("#resultbox").slideDown(1000);
                        $("#col_hostname").html(data.hostname);
                        $("#col_sap").html(data.sapid);
                        $("#col_ne").html(data.ne_id);
                        $("#col_loopback").html(data.loopback0);
                        $("#col_601").html(data.v1601);
                        $("#col_602").html(data.v1602);
                        $("#col_603").html(data.v1603);
                        $("#col_888").html(data.v1888);
                        
                        $(".routerstatus").removeClass("bgred");
                        
                        $('#TestBtn').attr("data-id",data.id);
                        $('#IntegrateBtn').attr("data-id",data.id);
                        $('#VerifyBtn').attr("data-id",data.id);
                        $('#RecordId').val(data.id);
                        
                        if(data.status==0)
                        {
                            $(".routerstatus").html("This router is ready to be tested");
                            $("#TestBtn").removeClass("disableBtn");
                            $("#IntegrateBtn").addClass("disableBtn");
                            $("#VerifyBtn").addClass("disableBtn");
                        }
                        
                        if(data.status==1)
                        {
                            $(".routerstatus").html("This router is ready to be integrate");
                            $("#IntegrateBtn").removeClass("disableBtn");
                            $("#VerifyBtn").addClass("disableBtn");
                        }
                        
                        if(data.status==2)
                        {
                            $(".routerstatus").html("This router has been integrated successfully");
                            $("#IntegrateBtn").removeClass("disableBtn");
                            $("#VerifyBtn").removeClass("disableBtn");
                        }
                        
                        if(data.status==3)
                        {
                            $(".routerstatus").html("This router has been integrated and verified successfully");
                            $("#TestBtn").addClass("disableBtn");
                            $("#IntegrateBtn").addClass("disableBtn");
                            $("#VerifyBtn").addClass("disableBtn");
                        }
                    }
                    else
                    {
                        $("#processStatus").html("<span style='color:#FF0000'>"+data.description+"</span>");
                    }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                    $("#processStatus").html("<span style='color:#FF0000'>"+errorThrown+"</span>");
               },
        });
        
        
    });
    
    
    
    
    $('#TestBtn').click(function(){
        
        if($(this).val()!="Test"){ return false; }
        
//        var id = $(this).data("id");
        var id = $('#RecordId').val();

        $.ajax({
               url: BASE_URL+"/RouterIntegration/checkTelnetResponse",
               type: 'POST',
               dataType: 'json',
               data:{id:id},
               beforeSend:function(){
                    $('.routerstatus').addClass('inprocess');
                    $('.routerstatus').html('Testing in process. Please wait..');
                    $("#TestBtn").attr("value","Testing...");
                },
                complete:function(){
                    $('.routerstatus').removeClass('inprocess');
                    $("#TestBtn").attr("value","Test");
                },
               success: function(data)
               {
                   if(data.result=="SUCCESS")
                   {
                        $(".routerstatus").html("This router is ready to be integrate");
                        $(".routerstatus").removeClass("bgred");
                        
                        $("#IntegrateBtn").removeClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
                   }
                   else
                   {
                        $(".routerstatus").html(data.description);
                        $(".routerstatus").addClass("bgred");
                        $("#IntegrateBtn").addClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                        $(".routerstatus").html(XMLHttpRequest.responseText);
                        $("#IntegrateBtn").addClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
               },
        });
        
        
    });
    
    
    
    
    $('#IntegrateBtn').click(function(){
        
        if($(this).val()!="Integrate"){ return false; }
        
//        var id = $(this).data("id");
          var id = 435;

        $.ajax({
               url: BASE_URL+"/RouterIntegration/integrateRouter",
               type: 'POST',
               dataType: 'json',
               data:{id:id},
               beforeSend:function(){
                    $('.routerstatus').addClass('inprocess');
                    $('.routerstatus').html('Integration in process. Please wait..');
                    $("#IntegrateBtn").attr("value","Integrating...");
                },
                complete:function(){
                    $('.routerstatus').removeClass('inprocess');
                    $("#IntegrateBtn").attr("value","Integrate");
                },
               success: function(data)
               {
                   
                   if(data.result=="SUCCESS")
                   {
                        $(".routerstatus").html(data.description);
                        $(".routerstatus").removeClass("bgred");
                        
                        $("#IntegrateBtn").removeClass("disableBtn");
                        $("#VerifyBtn").removeClass("disableBtn");
                   }
                   else
                   {
                        $(".routerstatus").html(data.description);
                        $(".routerstatus").addClass("bgred");
                        $("#IntegrateBtn").removeClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
//                        $(".routerstatus").html(XMLHttpRequest.responseText);
//                        $("#IntegrateBtn").addClass("disableBtn");
//                        $("#VerifyBtn").addClass("disableBtn");
               },
        });
        
        
    });
    
    
    
    
    $('#VerifyBtn').click(function(){
        
        if($(this).val()!="Verify"){ return false; }
        
//        var id = $(this).data("id");
        var id = $('#RecordId').val();

        $.ajax({
               url: BASE_URL+"/RouterIntegration/verifyRouterIntegration",
               type: 'POST',
               dataType: 'json',
               data:{id:id},
               beforeSend:function(){
                    $('#compareLoader').show();
                    $('.compare_content').html("");
                    $("#VerifyBtn").attr("value","Verifying...");
                },
                complete:function(){
					$('#compareLoader').hide();
                    $("#VerifyBtn").attr("value","Verify");
                },
               success: function(data)
               {
                   
                   if(data.result=="SUCCESS")
                   {
                        $('.compare_content').html(data.filecontent);
//                        $(".routerstatus").html("This router is ready to be integrate");
//                        $(".routerstatus").removeClass("bgred");
                   }
                   else
                   {
                        $(".routerstatus").html(data.description);
                        $(".routerstatus").addClass("bgred");

                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                        $(".routerstatus").html(XMLHttpRequest.responseText);
               },
        });
        
        
    });
    
    
});