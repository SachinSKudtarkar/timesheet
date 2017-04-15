jQuery(document).ready(function($) {

    /**
     * @author: Rishikesh Jadhav.
     * @Purpose: ajax call to add/remove employees from partner
     * @param {type} $left
     * @param {type} $right
     * @param {type} option
     * @param {type} $moveFrom
     * @returns {Boolean}
     */
    addOrRemoveRecordsFromPartner = function($left, $right, option, $moveFrom) {
        var addRemoveflag = ($moveFrom === 'beforeMoveOneToRight' || $moveFrom === 'beforeMoveAllToRight') ? 1 : 0;
        var selected_ids = [];
        var flag = false;
        $(option).each(function() {
            selected_ids.push($(this).val());
        });
        
        $.ajax({
            url: BASE_URL + "/LinkEnggCrMaster/AssignCategoriesToApprover",
            type: 'POST',
            async: false,
            dataType: 'JSON',
            data: {selected_ids: selected_ids, cat_id: $('#NddLinkEnggCatEmployeeMapping_cat_key_id').val(), level_id: $('#NddLinkEnggCatEmployeeMapping_level_id').val(), addRemoveflag: addRemoveflag},
            success: function(data) {
                if (data) {
                   $('#result_msg').addClass('alert-success alert');
                   $('#result_msg').html('Your request has been completed successfully');
                   $('#result_msg').css('display','block');
                   $('#result_msg').delay(5000).fadeOut('slow');
                   flag = true;
                }
            }
        });
        return flag;

    };

    /**
     * @author: Rishikesh Jadhav.
     * @Purpose: function to confirm before moving left to right
     * @param {type} $left
     * @param {type} $right
     * @param {type} option
     * @param {type} $moveFrom
     * @returns {Boolean}
     */
    confirmFromLeftToRight = function($left, $right, option, $moveFrom) {
        if (option.length) {
            if (confirm('Are you sure, Do you want to move selected employee(s) under selected partner?')) {
                if (addOrRemoveRecordsFromPartner($left, $right, option, $moveFrom)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } else {
            return false;
        }
    };

    /**
     * @author: Rishikesh Jadhav.
     * @Purpose: function to confirm before moving right to left
     * @param {type} $left
     * @param {type} $right
     * @param {type} option
     * @param {type} $moveFrom
     * @returns {Boolean}
     */
    confirmFromRighToLeft = function($left, $right, option, $moveFrom) {
        if (option.length) {
            if (confirm('Are you sure, Do you want to remove selected employee(s) under selected partner?')) {
                if (addOrRemoveRecordsFromPartner($left, $right, option, $moveFrom)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;

        } else {
            return false;
        }
    };

    /**
     * @author Rishikesh Jadhav.
     * @Purpose: Function to bind events for moving records
     */
    $('#multiselect').multiselect({
        beforeMoveOneToRight: function($left, $right, option) {
            return confirmFromLeftToRight($left, $right, option, 'beforeMoveOneToRight');
        },
        beforeMoveAllToRight: function($left, $right, option) {
            return confirmFromLeftToRight($left, $right, option, 'beforeMoveAllToRight');
        },
        beforeMoveOneToLeft: function($left, $right, option) {
            return confirmFromRighToLeft($left, $right, option, 'beforeMoveOneToLeft');
        },
        beforeMoveAllToLeft: function($left, $right, option) {
            return confirmFromRighToLeft($left, $right, option, 'beforeMoveAllToLeft');
        }
    });

    /**
     * @Author: Rishikesh Jadhav.
     * @Purpose: To load records for slected partners
     * @returns {undefined}
     */
    loadEmployeeDataForPartner = function() {
        var cat_id = $('#NddLinkEnggCatEmployeeMapping_cat_key_id').val();
        var level_id = $('#NddLinkEnggCatEmployeeMapping_level_id').val();
        if( cat_id == '' || level_id == '' )
        {
            if( $( this ).attr('id') === 'NddLinkEnggCatEmployeeMapping_cat_key_id'  )
                return false;
            bootbox.alert('Please select Category and Level');
            return false;
        }
        var appendUrl = "";
        if (cat_id !== '')
            appendUrl += "?cat_id=" + cat_id;
        if( level_id !== '' )
            appendUrl += "&level_id=" + level_id;
        document.location = BASE_URL + '/LinkEnggCrMaster/AssignCategoriesToApprover' + appendUrl;
    };

    //Call to functions
    $('#NddLinkEnggCatEmployeeMapping_level_id, #NddLinkEnggCatEmployeeMapping_cat_key_id').live('change', loadEmployeeDataForPartner);

});