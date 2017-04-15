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
    var assigned_for = $("#assigned_for").val();
    
    addOrRemoveRecordsFromPartner = function($left, $right, option, $moveFrom) {
        var addRemoveflag = ($moveFrom === 'beforeMoveOneToRight' || $moveFrom === 'beforeMoveAllToRight') ? 'move' : 'remove';
        var selected_circle_ids = [];
        var flag = false;
        $(option).each(function() {
            selected_circle_ids.push($(this).val());
        });
        
        $.ajax({
            url: BASE_URL + "/solutionpartner/AssignedCircleToSolpar",
            type: 'POST',
            async: false,
            dataType: 'JSON',
            data: {selected_circle_ids: selected_circle_ids, sol_par_id: $('#SolutionPartner_company_name').val(), addRemoveflag: addRemoveflag, assigned_for:assigned_for},
            success: function(data) {
                if (data) {
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
                            var appendUrl  = "";
                            
                            switch(assigned_for){
                                    case 'installation':
                                                        appendUrl = 'AssignedCircleToSolparForInst';
                                                        break;
                                    case 'atp1a':
                                                appendUrl = 'AssignedCircleToSolparForAtp1a';
                                                break;
                                    case 'integration':
                                                appendUrl = 'AssignedCircleToSolparForInt';
                                                break;            
                                }
                            
                            if (partner_id !== '')
                                appendUrl += "?partner_id=" + partner_id;
                            
                            document.location = BASE_URL + '/solutionpartner/' + appendUrl;
                        };

    //Call to functions
    $('#SolutionPartner_company_name').live('change', loadEmployeeDataForPartner);

});