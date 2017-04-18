$(document).ready(function() {
    /**
     * @author: Rishikesh Jadhav.
     * @Purpose: ajax call to add/remove employees from partner
     * @param {type} $left
     * @param {type} $right
     * @param {type} option
     * @param {type} $moveFrom
     * @returns {Boolean}
     */
    getView = function($left, $right, option) {
        var selected_ids = [];
        option = $('#multiselect_to option');
        var is_stand_by_required = ($('.stand_by_selector').attr('checked') === 'checked') ? 1 : 0;
        if (option.length) {
            $(option).each(function() {
                selected_ids.push($(this).val());
            });
            $.ajax({
                url: BASE_URL + "/nexusservicefortor/getIpv4Ipv6Details",
                type: 'POST',
                async: false,
                dataType: 'json',
                data: {selected_ids: selected_ids, is_stand_by_required: is_stand_by_required},
                success: function(data) {
                    $('.cls_vlan_details_dynamic').html(data.ipv4ipv6Details);
                    $('.cls_south_bond_details_active').html(data.southBondDetailsActive);
                    $('.cls_south_bond_details_standBy').html(data.southBondDetailsStandBy);
                }
            });
        } else {
            $('.cls_vlan_details_dynamic').html('');
            $('.cls_south_bond_details_active').html('');
            $('.cls_south_bond_details_standBy').html('');
        }


    };

    
    /**
     * @author Rishikesh Jadhav.
     * @Purpose: Function to bind events for moving records
     */
    $('#multiselect').multiselect({
        afterMoveOneToRight: function($left, $right, option) {
            getView($left, $right, option);
        },
        afterMoveOneToLeft: function($left, $right, option) {
            getView($left, $right, option);
        },
    });


    /**
     * @Author: Rishikesh Jadhav.
     * @Purpose: To load records for slected partners
     * @returns {undefined}
     */
    loadVlansMapped = function() {
        var service_id = $('#NexusServiceForTor_service_id').val();
        var appendUrl = "";
        if (service_id !== '')
            appendUrl += "?service_id=" + service_id;
        document.location = BASE_URL + '/nexusservicefortor/create' + appendUrl;
    };

    getIpv4Ipv6 = function() {
        var i = $(this).attr('data-name');
        $(".ipv4_data_" + i + ", .ipv6_data_" + i).attr('disabled', true);
        $(".ipv4_data_" + i + ", .ipv6_data_" + i).val('');
        $(".ipv4_data_" + i + ", .ipv6_data_" + i).removeClass('required_custom');
        $(".ipv4_data_" + i + ", .ipv6_data_" + i).removeClass('custombackred');
        $(".chk_ipv4_ipv6_" + i + ":checked").each(function() {
            if ($(this).val() === 'IPV4') {
                $(".ipv4_data_" + i).attr('disabled', false);
                $(".ipv4_data_" + i).addClass('required_custom');
            }
            if ($(this).val() === 'IPV6') {
                $(".ipv6_data_" + i).attr('disabled', false);
                $(".ipv6_data_" + i).addClass('required_custom', false);
            }
        });
    };

    //Call to functions
    $('#NexusServiceForTor_service_id').live('change', loadVlansMapped);
    $('.chk_ipv4_ipv6').live('change', getIpv4Ipv6);
});