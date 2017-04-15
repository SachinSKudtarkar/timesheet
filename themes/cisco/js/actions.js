/** ************************************************************
 *  File Name		 : actions.php
 *  File Description     : Ajax user actions.
 *  Author		 : Benchmark, 
 *  Created Date	 : 04th March 2014 06:07:00 PM IST
 *  Develop By		 : Anand Rathi
 * ************************************************************* */
$(document).ready(function() {
    /* set ajax call to send-credentials*/
    $('.action-controls .send-credentials').live('click', function() {
        // Current element	
        var current_tag = $(this);
        // confirm from user to do this action
        if (!current_tag.hasClass('confirmed'))
            return false;

        current_tag.animate({'opacity': '0.5'}, 800, function() {
            current_tag.html('loading...').addClass('disabled')
        });
        var current = $(this).parents('tr');
        var parent = current.prev('tr');
        // show loading
        // current.find('td').eq(0).addClass('grid-view-loading');

        // Ajax call to send credentials
        $.ajax({
            'url': $(this).attr('href'),
            'type': 'post',
            'data': {ajax: $(this).attr('request-type')},
            success: function(data)
            {
                current_tag.removeClass('disabled');
                current.remove();
                parent.attr('class', 'row-click');
                $('.breadcrumb').after('<div class="alert alert-success" style="opacity: 1;"></div>');
                $('.alert-success').html(data).animate({opacity: 0, display: 'none'}, 10000, function() {
                    $('.alert-success').remove();
                });
            }
        });
        return false;
    });

    /* Action of active & inactivate*/
    $("#inactivate-user textarea").live('mouseleave', function() {
        $(this).parents('form').find('button[type=submit]').focus();
    });

    $('.action-controls .inactivate, .action-controls .inactivation_cancel').live('click', function() {
        return false;
    });
    $('.modal-body #inactivate_call, .action-controls .activate').live('click', function() {
        // Current tr element
        var current = $(this).parents('tr');
        // before tr element ( user detail column )
        var parent = current.prev('tr');

        var request_type = $(this).attr('request-type');
        // Current element	
        var current_tag = $(this);

        if (request_type === 'inactivate')
        {
            if ($('#inactivate-user textarea').val() == '')
                $('#inactivate-user textarea').next('.error').html('Reason for Deactivation cannot be blank.').show();
            if ($('.error').css('display') != 'none' || $('#inactivate-user textarea').val() == '')
                return false;
        } else {
            if (!current_tag.hasClass('confirmed'))
                return false;
        }
        current_tag.animate({'opacity': '0.5'}, 800, function() {
            current_tag.html('Processing...');
        });

        // Get id data from tag
        var id_data = parent.attr('id').split('#');
        var currentId = id_data[0];
        // variable to set new status & id
        var new_id, status;
        // get respective data
        // Finally call to url for changing status & set new status
        var action_url = (typeof $(this).attr('href') != 'undefined') ? $(this).attr('href') : $(this).parents('form').attr('action');
        $.ajax({
            'url': action_url,
            'type': 'post',
            'data': {ajax: $(this).attr('request-type'), inactivate_reason: $('#inactivate-user textarea').val()},
            'success': function(data)
            {
                $('a[data-dismiss]').trigger('click');
                if (data) {
                    switch (request_type) {
                        case 'inactivate':
                            // On Inactivate
                            $(this).attr('request-type', 'activate');
                            new_id = currentId + '#2';
                            status = 'Inactive';
                            break;
                        case 'activate':
                            // On Activate
                            $(this).attr('request-type', 'inactivate');
                            new_id = currentId + '#1';
                            status = 'Active';
                            break;
                    }
                    // replace with new status
                    current_tag.removeClass('disabled').removeClass('active');
                    current.remove();
                    parent.find('.status').html(status);
                    parent.attr('class', 'row-click');
                    parent.attr('id', new_id);
                    $('.alert.alert-success').remove();
                    $('.main').before('<div class="alert alert-success" style="opacity: 1;"></div>');
                    $('.alert-success').html(data).animate({opacity: 0, display: 'none'}, 10000, function() {
                        $('.alert-success').remove();
                    });
                }
            }
        });
        return false;
    });
});