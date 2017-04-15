$(document).ready(function(){
    
$('#fetchCmdBtn').click((function(container){

var interval;
var cmdInterval;
return function(){

    $('#rightsection').html('');
    $('#leftsection').html('');

    var deviceCount = 0;
    var rowCount = 0;
    var colCount = 0;


//----- Initiating variables
    var device_type = $('#device_id').val();
    var device_type = $('#device_id').val();
    var ntw_device= $('#Folder_folder').val();
    var device_count = $(ntw_device).size();

//----- Append NetworkingDevices to #rightpanel
    for(var i =0; i < device_count; i++)
    {
        var currDevice = ntw_device[i];
        var appendstr = '<div class="netDevices"title="Fetching command files" id="right_'+currDevice+'">'+currDevice+'</div>';
        $('#rightsection').append(appendstr);
    }

    var currCol = 0;
    var boxesScroll = 0;
        
    var currDeviceNum = 0;
    var processing = 0;
    var cmdStatus = 0;
    var devicesScrollLength = 0;
    var cmdBoxScrollLength = 0;
    var cmdScrollLength = 0;
    
    
    $('.close').hide();
    $('div.modal-backdrop').hide();
    
    interval = setInterval(function(){
        var i = currDeviceNum;
        var currDevice = ntw_device[i];
        var divClass = "deviceboxes";
        
        
            if(processing==0 && cmdStatus == 0)
            {
                
            $('#right_'+currDevice).addClass("processing");
            
            boxesScroll++;
            
             $.ajax({
                   url: BASE_URL+"/AjaxCustom/FetchCommandsFiles",
                   type: 'POST',
                   dataType: 'json',
                   async:true,
                   data:{device_type:device_type,ntw_device:currDevice, action:""},
                   beforeSend:function(){
                            processing = 1;
                            if(boxesScroll==5)
                            {
                                devicesScrollLength+=130;
                                $('#rightsection').animate({
                                 scrollTop: $('#right_'+currDevice).scrollTop() + devicesScrollLength
                                }, 500);
                                boxesScroll = 0;
                            }

                            if(deviceCount>6)
                            {
                                 cmdBoxScrollLength+=130;
                                 $('#leftsection').animate({
                                  scrollTop: $('#boxlabel_'+currDevice).scrollTop() + cmdBoxScrollLength
                                 }, 500);
                            }
                    },
                   success: function(data)
                   {
                       processing = 0;
                       currDeviceNum++;
                       if(data.result=="SUCCESS")
                       {
                            deviceCount ++;
                            rowCount++;

                            if(rowCount>1)
                                divClass += " mrgnleft";

                            if(colCount>0 && currCol==colCount)
                            {
                                 currCol = colCount;
                                 divClass += " mrgntop";
                            }

                            if((deviceCount) % 3 === 0)
                            {
                                colCount++;
                                currCol = colCount;
                            }


                            $('#right_'+currDevice).removeClass("processing");
                            $('#right_'+currDevice).addClass("success");
                            $('#right_'+currDevice).attr("title",data.description);
                            
                            //----- Append NetworkingDevices to #leftpanel
                            var devicebox = "<div class='"+divClass+"' id='box_"+currDevice+"'><div class='title' id='boxlabel_"+currDevice+"'>"+currDevice+"</div><div class='cmdsboxes' id='cmdsbox_"+currDevice+"'></div></div>";
                            $('#leftsection').append(devicebox);
                           
                            var commands = data.commands;
                            var cmdCount = $(commands).size();
                            
                            if(cmdCount>0)
                                cmdStatus = 1;
                            
                            if(cmdCount>6){
                                $('#cmdsbox_'+currDevice).css("overflow-y","scroll");
                            }
                            
                            //----- Appending commands to device boxes
                            for(var j=0; j < cmdCount; j++)
                            {
//                                $('#boxlabel_'+currDevice).after('<div class="cmds" id="'+currDevice+'_'+j+'">'+commands[j]+'</div>');
                                $('#cmdsbox_'+currDevice).append('<div class="cmds" id="'+currDevice+'_'+j+'">'+commands[j]+'</div>');
                            }
                            
                            var isCmdProcessing = 0;
                            var processedcmd = 0;
                            var j = 0;
                            
                        //----- Processing Command Files
                        cmdInterval = setInterval(function(){
                            
                            if(isCmdProcessing==0)
                            {
                                isCmdProcessing=1;
                                var currCommand = commands[j];
                                $('#'+currDevice+'_'+j).addClass('processing');

                                $.ajax({
                                       url: BASE_URL+"/AjaxCustom/FetchCommandsFiles",
                                       type: 'POST',
                                       dataType: 'json',
                                       async:true,
                                       data:{ device_type:device_type, ntw_device:currDevice, action:"fetchfiles", currCommand:currCommand, loopback:data.loopback},
                                    beforeSend:function(){
                                            processing = 1;
                                            
                                            if(processedcmd>=7)
                                            {
                                                processedcmd = 0;
                                                cmdScrollLength+=40;
                                                $('#cmdsbox_'+currDevice).animate({
//                                                $('#leftsection').animate({
                                                 scrollTop: $('#'+currDevice+'_'+j).scrollTop() + cmdScrollLength
                                                }, 100);
                                            }

                                        },
                                       success: function(data)
                                       {
                                           processing = 0;
                                           processedcmd++;
                                           isCmdProcessing=0;
                                           
                                           if(data.status=="SUCCESS")
                                           {
                                                $('#'+currDevice+'_'+j).addClass('success');
                                                $('#'+currDevice+'_'+j).removeClass('processing');
                                                $('#'+currDevice+'_'+j).attr("title",data.description); 
                                           }
                                           else
                                           {
                                                $('#'+currDevice+'_'+j).addClass('failed');
                                                $('#'+currDevice+'_'+j).removeClass('processing');
                                                $('#'+currDevice+'_'+j).attr("title",data.description); 
                                           }
                                           j++;

                                       },
                                       error: function(XMLHttpRequest, data, errorThrown)
                                       {
                                            processedcmd++;
                                            processing = 0;
                                            isCmdProcessing=0;
                                            
                                            $('#'+currDevice+'_'+j).attr("title",data.description);
                                            $('#'+currDevice+'_'+j).removeClass("processing");
                                            $('#'+currDevice+'_'+j).addClass("failed");
                                            j++;
                                       },
                                       
                                });
                                
                                if(cmdCount==(j+1)){clearInterval(cmdInterval); cmdStatus = 0; };
                            
                            }    
                            
                        },3000);
                            
                       }
                       else
                       {
                            $('#right_'+currDevice).attr("title",data.description);
                            $('#right_'+currDevice).removeClass("processing");
                            $('#right_'+currDevice).addClass("failed");
                       }
                      
                       if(rowCount==3)
                           rowCount = 0;
                   },
                   error: function(XMLHttpRequest, data, errorThrown){
                            processing = 0;
                            currDeviceNum++;
                            $('#right_'+currDevice).attr("title","Error 500 Internal network problem");
                            $('#right_'+currDevice).removeClass("processing");
                            $('#right_'+currDevice).addClass("failed");
                   },
            });
        
        if(device_count==(currDeviceNum+1))
        { 
            $('.close').show();
            $('.modal-backdrop').removeClass('hideoverlay');
            clearInterval(interval)
        };
    }

    }, 2000);
            
};
        
})(""));
    
    
    
    
    
    $('#Folder_folder').change(function(){
        var netDevices = $('#Folder_folder').val();
        if(netDevices)
            $('#fetchCmdBtn').show();
        else
            $('#fetchCmdBtn').hide();
    });
    
});