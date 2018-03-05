$(document).ready(function(){
  
});


function applyFolderList(jsondata)
{
    var dropDown = '';
    $.each(jsondata.folderlist, function(key, val) {
        dropDown+='<option value="'+key+'">'+val+'</option>';
    });
    
    $('#folderPath').val(jsondata.folder); 
    $('#folder').append(dropDown);
    $('#folder').removeAttr('disabled');
}
