var socket;
//var socket = io.connect("http://localhost:3000");
//var socket = io.connect("http://192.168.1.56:3000");
var socket = io.connect("http://71.43.59.189:9002");
function setModel(modelName, id)
{
    socket.emit('set model', modelName, id);
}
function updateModel(msg)
{
    socket.emit('update model', msg);
}
function getUpdateModel(msg)
{

	// Define selector of that field
	var selector = ( typeof msg.selector !== undefined ) ? msg.selector : msg.model + '_' + msg.attribute;
	
        if ( typeof selector === undefined ) return false;
	// Get tag name
	var tag_name = $("#"+selector).prop('tagName').toLowerCase();
	// Cheking tag name type to update that field
	switch( tag_name )
	{
		case 'input' :
			var input_type = $("#"+selector).attr('type');
			switch( input_type )
			{
				case 'radio' :
					// Radio input updation
					$("#"+selector).prop('checked', true);
					$("#"+selector).trigger('make_changes');
					break;
				case 'checkbox' :
					// checkbox updation
					if( msg.value == 0 )
						$("#"+selector).prop('checked', false);
					else if( msg.value == 1 )					
						$("#"+selector).prop('checked', true);
					$("#"+selector).trigger('make_changes');
						
					break;
				default :
					// Text Or Other input updation
					$("#"+selector).val(msg.value).addClass('highlight').attr('disabled', 'disabled');
                                        $( '#'+selector ).trigger('make_changes');
					break;
			}
			break;
		case 'select' :
			// Select list updation
			$("#"+selector).find('option').removeAttr('selected');
			$("#"+selector).find('option[value='+msg.value+']').attr('selected', 'selected');
			$("#"+selector).addClass('highlight').attr('disabled', 'disabled');
                        $( '#'+selector ).trigger('make_changes');
			break;
		case 'textarea' :
			// Textarea updation
			$("#"+selector).val(msg.value).addClass('highlight').attr('disabled', 'disabled');
                        $( '#'+selector ).trigger('make_changes');
			break;
		default :
			// HTML Replacement
			$("#"+selector).html(msg.value).addClass('highlight').attr('disabled', 'disabled');
			break;
	}
	
	setTimeout(function(){
		$("#"+selector).removeClass('highlight');
                // Skip when its preview
                if(  $("#"+selector).parents('form').hasClass('preview') || $("#"+selector).hasClass('virtual_changes') ){
                    if( $("#"+selector).hasClass('virtual_changes') )
                        $("#"+selector).removeClass('virtual_changes');
                }else{
                    $("#"+selector).removeAttr('disabled');
                }
	}, 10000);
	
}

socket.on('update model', function(msg) {
    getUpdateModel(msg);
});