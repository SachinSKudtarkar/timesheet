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
        var addRemoveflag = ($moveFrom === 'beforeMoveOneToRight' || $moveFrom === 'beforeMoveAllToRight') ? 'moved' : 'removed';
        var selected_ids = [];
        var flag = false;
        $(option).each(function() {
            selected_ids.push($(this).val());
        });
        $.ajax({
            url: BASE_URL + "/solutionpartner/LinkPartnerToEmployeeView",
            type: 'POST',
            async: false,
            dataType: 'JSON',
            data: {selected_ids: selected_ids, sol_par_id: $('#SolutionPartner_circles').val(), addRemoveflag: addRemoveflag, map_for:'atp1a'},
            success: function(data) {
                if (data) {
                    bootbox.alert(data + ' Employees ' + addRemoveflag + ' under selected partner succsesfully.');
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
        var partner_id = $('#SolutionPartner_company_name').val();
        var circle_solpar_rel_id = $('#SolutionPartner_circles').val();
        var appendUrl = "";
        if (partner_id !== '')
            appendUrl += "?partner_id=" + partner_id;
        if (circle_solpar_rel_id !== '' && $(this).attr('id') !== 'SolutionPartner_company_name')
            appendUrl += "&circle_solpar_rel_id=" + circle_solpar_rel_id;
        document.location = BASE_URL + '/solutionpartner/MapSolEnggToSolparForAtp1a' + appendUrl;
    };

    //Call to functions
    $('#SolutionPartner_company_name, #SolutionPartner_circles').live('change', loadEmployeeDataForPartner);

});