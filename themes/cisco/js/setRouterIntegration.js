$(document).ready(function(){
    
    $('#intType').on('change',function(){
        var intType = $(this).val()
        document.location=BASE_URL+'/SetRouterIntegration/index?type='+intType;
    });
    
    $('#btnPreview').click(function(){
        
        var content = $("#configFileContent").val();
//        var content =tinyMCE.activeEditor.getContent();
        
        var id = $("#RecordId").val();
        var type = $('#intType').val();
        
        $.ajax({
               url: BASE_URL+"/SetRouterIntegration/previewIntegrationFile",
               type: 'POST',
               dataType: 'json',
               data:{id:id, content:content, type:type},
               beforeSend:function(){
                    $('#previewLoader').show();
                    $('.compare_content').html("");
                },
                complete:function(){
                    $('#previewLoader').hide();
                },
               success: function(data)
               {
                   
                   if(data.result=="SUCCESS")
                   {
                        $('.compare_content').html("<pre>"+data.filecontent);
                        $(".routerstatus").html("This router is ready to be integrate");
                        $(".routerstatus").removeClass("bgred");
                   }
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });
        
        
    });
    
    $('#btnSave').click(function(){
        
        if($('#btnSave').val()!="Save"){ return false; }
        var content = $("#configFileContent").val();
//        var content =tinyMCE.activeEditor.getContent();

        var type = $('#intType').val();
        
        $.ajax({
               url: BASE_URL+"/SetRouterIntegration/saveIntegrationFile",
               type: 'POST',
               dataType: 'json',
               data:{content:content, type:type},
               beforeSend:function(){
                    $('#btnSave').val("Saving...");
                },
                complete:function(){
                    $('#btnSave').val("Save");
                },
               success: function(data)
               {
                   alert(data.description);
               },
               error: function(XMLHttpRequest, data, errorThrown){
               },
        });
        
        
    });
    
    var posX = "";
    var posY = "";
    
     $(document).mousemove(function(event){
         posX = event.pageX;
         posY = event.pageY;
     });
     
    ZeroClipboard.setDefaults({moviePath:BASE_URL+'/themes/cisco/js/zeroclipboard/ZeroClipboard.swf'});
    var clip = new ZeroClipboard($('#varList li a'));
     
    clip.on('complete',function(client,args, event){
        posX+=30;
        posY-=20;
        var spn = "<span class='notify' style='position:absolute; top:"+posY+"px; left:"+posX+"px; padding:5px 10px; background:#F6FF00; z-index:99999999;'>Copied to clipboard</span>";
        $('body').append(spn);
        $(".notify").fadeOut(2500,function() { $(this).remove(); });
    });  
    
    
});