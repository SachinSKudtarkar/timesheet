$(document).ready(function(){
  
  $("#ApplicablePlans_topology_id").change(function(){
    var topology = $(this).val();
    
    if(topology=='')
    { 
        $('#ApplicablePlans_sub_topology_id').val('');
        $('#ApplicablePlans_sub_topology_id').attr('disabled','disabled'); return false; 
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
                        
                        $('#ApplicablePlans_sub_topology_id').html(dropDown);
                        $('#ApplicablePlans_sub_topology_id').removeAttr('disabled');
                   }
                   else
                   {
                        $('#ApplicablePlans_sub_topology_id').addAttr('disabled');
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
                       
               },
        });
    
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