$(document).ready(function()
{
    
    $('#fetchCmdBtn').live('click',function()
    { 
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

//----- Fetching commands from router
        for(var i =0; i < device_count; i++)
        {            
            var currDevice = ntw_device[i];
            var divClass = "deviceboxes";
            boxesScroll++;
            
             $('#right_'+currDevice).addClass("processing");
             $.ajax({
                   url: BASE_URL+"/AjaxCustom/FetchCommandsFiles",
                   type: 'POST',
                   dataType: 'json',
                   async:false,
                   data:{device_type:device_type,ntw_device:currDevice, action:""},
                   beforeSend:function(){
                            if(boxesScroll>3)
                            {
                                $('#rightsection').animate({
                                 scrollTop: $('#right_'+currDevice).offset().top
                                }, 500);
                                boxesScroll = 0;
                            }

                    },
                   success: function(data)
                   {
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
                            
                            if(cmdCount>6){
                                $('#cmdsbox_'+currDevice).css("overflow-y","scroll");
                            }
                            
                            //----- Appending commands to device boxes
                            for(var j=0; j < cmdCount; j++)
                            {
//                                $('#boxlabel_'+currDevice).after('<div class="cmds" id="'+currDevice+'_'+j+'">'+commands[j]+'</div>');
                                $('#cmdsbox_'+currDevice).append('<div class="cmds" id="'+currDevice+'_'+j+'">'+commands[j]+'</div>');
                            }
                            
                            var processedcmd = 0;
                            
                            //----- Fetching command files
                            for(var j=0; j < cmdCount; j++)
                            {
                                var currCommand = commands[j];
                                $('#'+currDevice+'_'+j).addClass('processing');
                                
                                processedcmd++;
                                
                                
                                $.ajax({
                                       url: BASE_URL+"/AjaxCustom/FetchCommandsFiles",
                                       type: 'POST',
                                       dataType: 'json',
                                       async:false,
                                       data:{ device_type:device_type, ntw_device:currDevice, action:"fetchfiles", currCommand:currCommand, loopback:data.loopback},
                                    beforeSend:function(){
                                             if(processedcmd>3)
                                             {
                                                 processedcmd = 0;
                                                 $('#cmdsbox_'+currDevice).animate({
                                                  scrollTop: $('#'+currDevice+'_'+j).offset().top
                                                 }, 100);
                                             }
                                            
                                            if(deviceCount>6)
                                            {
                                                 $('#leftsection').animate({
                                                  scrollTop: $('#boxlabel_'+currDevice).offset().top
                                                 }, 500);
                                            }

                                        },
                                       success: function(data)
                                       {
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
                                           
//                                            $('#leftsection').animate({
//                                             scrollTop: $('#'+currDevice+'_'+j).offset().top
//                                            }, 100);
                                           
                                       },
                                       error: function(XMLHttpRequest, data, errorThrown)
                                       {
                                            $('#'+currDevice+'_'+j).attr("title",data.description);
                                            $('#'+currDevice+'_'+j).removeClass("processing");
                                            $('#'+currDevice+'_'+j).addClass("failed");
                                       },  
                                });
                                
                            }
                            
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
                       
                            $('#right_'+currDevice).attr("title","Error 500 Internal network problem");
                            $('#right_'+currDevice).removeClass("processing");
                            $('#right_'+currDevice).addClass("failed");
                   },  
            });
       }
    });
    
    $('#Folder_folder').change(function(){
        var netDevices = $('#Folder_folder').val();
        if(netDevices)
            $('#fetchCmdBtn').show();
        else
            $('#fetchCmdBtn').hide();
    });

});