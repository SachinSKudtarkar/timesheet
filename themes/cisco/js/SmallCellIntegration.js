$(document).ready(function(){
	
	
	
    $('#device_type').change(function(){
        var device_type = $(this).val();
		if(device_type=="CSS")
		{
			$("#SelectSubType").show();
		}
		else
		{
			$("#SelectSubType").hide();
		}
    });
	
	
//----- Search Action	
	
	$('#SearchBtn').live('click',function(){
		
		if($(this).val()!="Search")
			return false;
		
		$(this).val("Searching...");
		
		var sapid = $("#sapid").val();
		var hostname = $("#hostname").val();
		var loopback0 = $("#loopback0").val();
		var neid = $("#neid").val();
		var oamid = $("#oamid").val();
		var topology = $("#topology").val();
		var topology = $("#topology").val();
		var connect_type = $("#connect_type").val();
		var device_type = $("#device_type").val();
		var sub_device_type = $("#sub_device_type").val();
		var ring = $("#ring").val();
		var company = $("#company").val();
		
		if(sapid=="")
			$('#sapid').addClass('err');
		else
			$('#sapid').removeClass('err');
		
		if(hostname=="")
			$('#hostname').addClass('err');
		else
			$('#hostname').removeClass('err');
		
		if(loopback0=="")
			$('#loopback0').addClass('err');
		else
			$('#loopback0').removeClass('err');
		
		if(neid=="")
			$('#neid').addClass('err');
		else
			$('#neid').removeClass('err');
		
		if(oamid=="")
			$('#oamid').addClass('err');
		else
			$('#oamid').removeClass('err');
		
		if(topology=="")
			$('#topology').addClass('err');
		else
			$('#topology').removeClass('err');
		
		if(connect_type=="")
			$('#connect_type').addClass('err');
		else
			$('#connect_type').removeClass('err');
		
		if(device_type=="")
			$('#device_type').addClass('err');
		else
			$('#device_type').removeClass('err');
		
		if(ring=="")
			$('#ring').addClass('err');
		else
			$('#ring').removeClass('err');
		
		if(company=="")
			$('#company').addClass('err');
		else
			$('#company').removeClass('err');
		
		
		if(device_type=="CSS")
		{
			if(sub_device_type=="")
				$('#sub_device_type').addClass('err');
			else
				$('#sub_device_type').removeClass('err');
		}
		else
		{
			$('#sub_device_type').removeClass('err');
		}
		
		var chkError = $('.err').length;
		
		if(chkError>0)
		{
			$('#SmallCellResBox').slideUp(1000);
			$(this).val("Search");
			return false;
		}
		
		$('#SmallCellResBox').slideDown(1000);
		
		var scrollPx = 500;
		
		$('body, html').animate({
			scrollTop: $('#SearchBtn').scrollTop() + scrollPx
		}, 2000);
		
		$(this).val("Search");
		
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
                    $('.statusbar').addClass('inprocess');
                    $('.statusbar').html('Testing in process. Please wait..');
                    $("#TestBtn").attr("value","Testing...");
                },
                complete:function(){
                    $('.statusbar').removeClass('inprocess');
                    $("#TestBtn").attr("value","Test");
                },
               success: function(data)
               {
                   if(data.result=="SUCCESS")
                   {
                        $(".statusbar").html("This router is ready to be integrate");
                        $(".statusbar").removeClass("bgred");
                        
                        $("#IntegrateBtn").removeClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
                   }
                   else
                   {
//                        $(".statusbar").html(data.description);
                        $(".statusbar").html("Device not reachable");
                        $(".statusbar").addClass("bgred");
                        $("#IntegrateBtn").addClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                        $(".statusbar").html(XMLHttpRequest.responseText);
                        $("#IntegrateBtn").addClass("disableBtn");
                        $("#VerifyBtn").addClass("disableBtn");
               },
        });
    });
    
    
    $('#IntegrateBtn').click(function(){
        
        if($(this).val()!="Integrate"){ return false; }
        
//        var id = $(this).data("id");
//        var id = $('#RecordId').val();
		  var id = 1;

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
	
	
});