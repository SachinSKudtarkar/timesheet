$(document).ready(function() {

    $(window).resize(function() {
        var bodyheight = $(document).height();
        $(".left-panal").height(bodyheight);
    });
    /* left - right column height */
    var leftHeight = $('.right-panal').height();
    var rightHeight = $('.left-panal').height();
    if (leftHeight > rightHeight)
    {
        $('.right-panal').css({'height': leftHeight});
    }
    else
    {
        rightHeight = rightHeight + 15;
        $('.left-panal').css({'height': rightHeight});
    }

    /* left - right column height - X */

    /*append row*/
    $('.table').on('click', '.row-click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('row-button-group'))
        {
            $(this).removeClass('row-button-group');
            $(this).next().remove();
        }
        else
        {
            $(this).parent().find("#hellohide").prev().removeClass('row-button-group');
            $(this).parent().find("#hellohide").remove();

            $(this).addClass('row-button-group');
            //sub Admin row button group
            switch ($('.table').attr('data-button-access-group'))
            {
                case 'subadmin_button_group':
                    $('<tr id="hellohide"><td colspan="6"><div class="col-lg-12 bottons-group" > <div class="pull-right"><a href="view-sub-admin.html" class="btn btn-primary btn-small">View Details</a> <a href="assign-law-firm-sub-admin.html" class="btn btn-primary btn-small">Assign Law Firm(s)</a> <a href="#" class="btn btn-primary btn-small">Send Credentials</a> <a href="#" class="btn btn-primary btn-small">Inactivate</a> <a href="#" class="btn btn-primary btn-small">Delete</a></div></div></td></tr>').insertAfter($(this).closest('tr'));
                    break;
                case 'law_button_group':
                    $('<tr id="hellohide"><td colspan="6"><div class="col-lg-12 bottons-group" > <div class="pull-right"><a href="view-details-law-firms.html" class="btn btn-primary btn-small">View Details</a> <a href="view-estates-law-firms.html" class="btn btn-primary btn-small">View Estates</a> <a href="assign-trainer-law-firms.html" class="btn btn-primary btn-small">Assign Trainer (s)</a> <a href="assign-paralegal-law-firms.html" class="btn btn-primary btn-small">Assign Paralegal (s)</a> <a href="#" class="btn btn-primary btn-small">Send Credentials</a> <a href="#" class="btn btn-primary btn-small">Inactivate</a> <a href="#" class="btn btn-primary btn-small">Delete</a></div></div></td></tr>').insertAfter($(this).closest('tr'));
                    break;
                case 'paralegal_button_group':
                    $('<tr id="hellohide"><td colspan="6"><div class="col-lg-12 bottons-group" > <div class="pull-right"><a href="view-details-paralegals.html" class="btn btn-primary btn-small">View Details</a> <a href="assign-law-firm-paralegal.html" class="btn btn-primary btn-small">Assign Law Firm(s)</a> <a href="#" class="btn btn-primary btn-small">Send Credentials</a> <a href="#" class="btn btn-primary btn-small">Inactivate</a> <a href="#" class="btn btn-primary btn-small">Delete</a></div></div></td></tr>').insertAfter($(this).closest('tr'));
                    break;
                case 'estate_button_group':
                    $('<tr id="hellohide"><td colspan="6"><div class="col-lg-12 bottons-group" > <div class="pull-right"><a href="#" class="btn btn-primary btn-small">Move</a> <a href="#" class="btn btn-primary btn-small">Duplicate</a> </div></div></td></tr>').insertAfter($(this).closest('tr'));
                    break;
            }

        }
//sub Admin row button group-X
    });
    /*append row-X*/

    /*advance search panal*/
    $('.nav-toggle-1').click(function() {
        //get collapse content selector	
        var collapse_content_selector = $(this).attr('href');
        //make the collapse content to be shown or hide
        var toggle_switch = $(this);
        $(collapse_content_selector).toggle(function() {
            if ($(this).css('display') == 'none') {
                //change the button label to be 'Show'
                toggle_switch.html('Advanced Search');
            } else {
                //change the button label to be 'Hide'
                toggle_switch.html('Basic Search');
            }
        });
    });
    /*advance search panal-X*/

});

