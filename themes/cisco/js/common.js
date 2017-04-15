$(document).ready(function() {
    $('ul.nav')
    $(document).on('click', '.confirm-action', function() {
        var confirm_message = $(this).attr('data-confirm') ? $(this).attr('data-confirm') : 'Are you sure?';
        var current_tag = $(this);
        if (current_tag.hasClass('confirmed')) {
            return true
        }
        ;
        bootbox.confirm(confirm_message, function(result) {
            if (result) {
                current_tag.addClass('confirmed').removeClass('confirm-action');
                $('button[data-dismiss]').trigger('click');
                var clicking = current_tag[0];
                // check if is saffari
                if ($.browser.webkit)
                {
                    var evObj = document.createEvent('MouseEvents');
                    evObj.initMouseEvent('click', true, true, window);
                    clicking.dispatchEvent(evObj);
                } else {
                    clicking.click();
                }
            }
        });  
        return false;
    });

    $('.view_image[has-image=1]').each(function() {
        $(this).wrapAll('<span class="img-outer"/>').after('<i class="icon-remove remove-image confirm-action" data-confirm="Are you sure to remove this image?"></i>');
    });
    $(document).on('click', '.remove-image', function() {
        var current_tag = $(this);
        if (!current_tag.hasClass('confirmed'))
            return false;
        $.ajax({
            'url': remove_url,
            'type': 'post',
            'data': {
                ajax: true, 
                data_image: current_tag.prev('.view_image').attr('data-image')
                },
            'success': function(removed) {
                if (removed) {
                    current_tag.prev('.view_image').attr('src', no_preview);
                    current_tag.css('visibility', 'hidden');
                }
            },
            'error': function() {
                alert("Getting Error");
            }
        });
        return false;
    });
    
                                 /// Timeout popup code starts                  
    //added by Rakesh 
    //if user is idle for 5 minutes the he will be shown the popup that do u want to continue?
        
//                        idleTime = 0;
//            
//                        $(this).mousemove(function (e) {
//                            idleTime = 0;
//                        });
//                        $(this).keypress(function (e) {
//                            idleTime = 0;
//                        });
//    
//                        test1 = setInterval(function(){
//                            idleTime++;
//                            if( idleTime> 4 )
//                            {
//                                $.ajax({
//                                    type: 'POST',
//                                    url: base_url + '/ajax/showtimeoutpopup',
//                                    data:{ajax:"show_timeout_popup"},
//                                    beforeSend:function(){
//                                    // this is where we append a loading image
//
//                                    },
//                                    success:function(data){
//                                        $('.mod').remove();
//                                        $('body').before('<div class=\'mod\'></div>');
//                                        $('.mod').html(data);
//                                        idleTime = 0;
//                                    },
//                                    error:function(){
//                                    // failed request; give feedback to user
//                                    //$('#ajax-panel').html('<p class="error"><strong>Oops!</strong> Try that again in a few moments.</p>');
//                                    }
//                                });
//                            }
//
//              
//                        },10000);
                                /// Timeout popup code ends  
  //For show /hide of access right table                            
   var access_type = $('#Employee_access_type').val();
   if(access_type == 'A')
   {
        $('.access_rights').hide();
   }
   else
   {
        $('.access_rights').show();
   }
});
