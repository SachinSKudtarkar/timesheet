$(document).ready(function(){
  
  $("#topology").change(function(){
    var topology = $(this).val();
    
    if(topology=='')
    { 
        $('#sub_topology').val('');
        $('#sub_topology').attr('disabled','disabled'); 
        return false; 
    }
    
    $.ajax({
               url: BASE_URL+"/ParserOnec/fetchSubTopologies",
               type: 'POST',
               dataType: 'json',
               data:{ topology:topology},
               beforeSend:function(){
                    $('#subTopoLoader').show();
                },
                complete:function(){
                   $('#subTopoLoader').hide();
                },
               success: function(data)
               {
                   if(data.status=='SUCCESS')
                   {
                        var dropDown = '<option value="">Please Select Sub Topology</option>';
                        $.each(data.result, function(key, val) {
                            dropDown+='<option value="'+key+'">'+val+'</option>';
                        });
                        
                        $('#sub_topology').html(dropDown);
                        $('#sub_topology').removeAttr('disabled');
                   }
                   else
                   {
                        $('#sub_topology').addAttr('disabled');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                       
               },
        });
    
  });
  
  $("#region").change(function(){
      
      
    var region = $(this).val();
    
    if(region=='')
    { 
        $('#city').val('');
        $('#city').attr('disabled','disabled'); return false; 
    }
    
    $.ajax({
               url: BASE_URL+"/ParserOnec/fetchCities",
               type: 'POST',
               dataType: 'json',
               data:{ region:region},
               beforeSend:function(){
                    $('#cityLoader').show();
                },
                complete:function(){
                   $('#cityLoader').hide();
                },
               success: function(data)
               {
                   if(data.status=='SUCCESS')
                   {
                        var dropDown = '<option value="">Please Select City</option>';
                        $.each(data.result, function(key, val) {
                            dropDown+='<option value="'+key+'">'+val+'</option>';
                        });
                        
                        $('#city').html(dropDown);
                        $('#city').removeAttr('disabled');
                   }
                   else
                   {
                        $('#city').addAttr('disabled');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                       
               },
        });
      
  });
  
  $('#folder').change(function(){
        var netDevices = $('#folder').val();
        if(netDevices)
            $('#FetchCommands').show();
        else
            $('#FetchCommands').hide();
    });
  
});


function applyFolderList(jsondata)
{
    var dropDown = '';
    $.each(jsondata.folderlist, function(key, val) {
        dropDown+='<option value="'+key+'">'+val+'</option>';
    });
    
    $('#folderPath').val(jsondata.folder); 
    $('#folder').html(dropDown);
    $('#folder').removeAttr('disabled');
}