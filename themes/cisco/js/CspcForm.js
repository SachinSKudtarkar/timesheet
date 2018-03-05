function checkCSPCFormVal() {
    
    RegIP = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/g;
    RegDevice = /^[A-Za-z0-9\.]{3,40}$/;
    $(".info").html("");
    
    if($("#yt0").val() == "Submitting...") {
        return false;
    }
    
    if($("#Folder_device_id").val() == ""){ 
        $("#Folder_device_id_em_").html("Select device id").css("display","block"); 
        return false; 
    }

    if($("#Folder_host_name").val() == ""){ 
        $("#Folder_host_name_em_").html("Host name cannot be blank.").css("display","block"); 
        return false; 
    }
    if($("#Folder_host_name").val().length < 14 ){ 
        $("#Folder_host_name_em_").html("Host name is too short (minimum is 14 characters).").css("display","block"); 
        return false; 
    }
    if($("#Folder_host_name").val().match(RegDevice)){
        
    } else  {
        $("#Folder_host_name_em_").html("Host name not valid").css("display","block"); 
        return false; 
    }
    
    if($("#Folder_ip_address").val() == ""){ 
        $("#Folder_ip_address_em_").html("IP address cannot be blank.").css("display","block"); 
        return false; 
    }
    if($("#Folder_ip_address").val().length < 10){ 
        $("#Folder_ip_address_em_").html("IP address is too short (minimum is 10 characters).").css("display","block"); 
        return false; 
    }
    if($("#Folder_ip_address").val().match(RegIP)){
        
    } else  {
        $("#Folder_ip_address_em_").html("IP address not valid").css("display","block"); 
        return false; 
    }
    
    if(RegIP.test($("#Folder_ip_address").val())){
        
    } else  {
        $("#Folder_ip_address_em_").html("IP address not valid").css("display","block"); 
        return false; 
    }
    $("#ajaxLoader").addClass("loading");
    $("#yt0").attr('value', 'Submitting...');
    $('#yt0').attr('readonly', true);
}

function checkAddNewCSPCFormVal() {
    
    RegIP = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/g;
    $(".info").html("");
    
    if($("#yt0").val() == "Submitting...") {
        return false;
    }
    
    if($("#Folder_ip_address").val() == ""){ 
        $("#Folder_ip_address_em_").html("IP address cannot be blank.").css("display","block"); 
        return false; 
    }
    if($("#Folder_ip_address").val().length < 10){ 
        $("#Folder_ip_address_em_").html("IP address is too short (minimum is 10 characters).").css("display","block"); 
        return false; 
    }
    if($("#Folder_ip_address").val().match(RegIP)){
        
    } else  {
        $("#Folder_ip_address_em_").html("IP address not valid").css("display","block"); 
        return false; 
    }
    if(RegIP.test($("#Folder_ip_address").val())){
        
    } else  {
        $("#Folder_ip_address_em_").html("IP address not valid").css("display","block"); 
        return false; 
    }
    $("#ajaxLoader").addClass("loading");
    $("#yt0").attr('value', 'Submitting...');
    $('#yt0').attr('readonly', true); 
}
