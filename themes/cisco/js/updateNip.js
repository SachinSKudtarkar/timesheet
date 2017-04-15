$(document).ready(function(){
    
    
    $('#Atp1bActionMaster_action_tag').change(function(){
        
        var action = $(this).val();
        if(action != 'add_to_nip'){ return false; }
        
    });
    
    $('.nipdata').live('click',function(){
        $('#nipcontent a').removeClass('selected');
		var key = $(this).data("val");
		
		$('#position_value').val(key);
		
        $(this).closest('#nipcontent a').addClass('selected');
    });
    
    $('#moveBtn').live('click',function(){
        if(!$('#nipcontent a').hasClass('selected')){ alert("Please select position to move config."); return false; }
        var content=$("#diffcontent a").html();
        var pos = $("#pos").val();
        if(content==""){alert("No diff config available."); return false; }
       
        $('#additional_content').val(content);
        
        content.replace('<br>',"");
        content="<a href='javascript:void(0)' class='nipdata'>"+content+"</a>";
        
        if(pos=="before")
            $(content).insertBefore(".selected");
        if(pos=="after")
            $(content).insertAfter(".selected");
        
        var newContent=$("#nipcontent").html();
         $('#new_nip').val(newContent);
        
        $("#diffcontent a").html("");
        $('#nipcontent a').removeClass('selected');
        
    });
    
    
});