/**
 * @author Rishikesh Jadhav
 * @purpose Validate Add Services Form;
 * @param {type} param
 */
$(document).ready(function() {
    window.count = 0;
    makeClone = function(event) {
        window.count++;
        $($('.row_copy:first')).clone().insertAfter('.row_copy:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select, #row_copy_id_' + window.count + ' textarea').each(function() {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('#row_copy_id_' + window.count + ' select[multiple="multiple"]').each(function() {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + '][]');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRow" href="javascript:void(0);"></a>').insertAfter('.customAddMore:last');

    };

    RemoveRow = function(event) {
        $(this).closest('.row_copy').remove();
    };
    makeCloneStandby = function(event) {
        window.count++;
        $($('.row_copy_standby:first')).clone().insertAfter('.row_copy_standby:last').attr('id', 'row_copy_standby_id_' + window.count);
        $('#row_copy_standby_id_' + window.count + ' input, #row_copy_standby_id_' + window.count + ' select, #row_copy_standby_id_' + window.count + ' textarea').each(function() {
            $(this).val('');
            $(this).attr('name', 'data_standby[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'data_standby' + window.count + '_' + $(this).attr('data-name'));
        })
        $('#row_copy_standby_id_' + window.count + ' select[multiple="multiple"]').each(function() {
            $(this).val('');
            $(this).attr('name', 'data_standby[' + window.count + '][' + $(this).attr('data-name') + '][]');
            $(this).attr('id', 'data_standby' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowStandby" href="javascript:void(0);"></a>').insertAfter('.customAddMoreStandby:last');

    };

    RemoveRowStandby = function(event) {
        $(this).closest('.row_copy_standby').remove();
    };

    chkIsNotBlank = function() {
        var is_valid = true;
        $('.required_custom').removeClass('custombackred');
        $('.required_custom').each(function() {
            if ($(this).val() == '' || this.value == '') {
                $(this).addClass('custombackred');
                is_valid = false;
            }
        });
        if (is_valid)
            return true;
        return false;
    };

    chkForValidVlanRange = function(class_check) {
        var port_array = Array();
        $(class_check).each(function() {
            port_array.push($(this).val());
        });
        return port_array;
    };

    chkForDuplicatesVlan = function(vlan_array) {
        var originalLength = vlan_array.length;
        vlan_array = $.unique(vlan_array);
        var lengthAfterFilter = vlan_array.length;
        if (originalLength === lengthAfterFilter)
            return true;
        return false;
    };

//    chkForValidDescription = function() {
//        var returnFlag = true
//        $('.cls_description').each(function() {
//            if (!/^[a-z0-9-]+$/i.test($(this).val())) {
//                returnFlag = false;
//            }
//        });
//        if (!returnFlag) {
//            bootbox.alert('Description should contain only alphanumeric and hyphens');
//        }
//        return returnFlag;
//    };

    chkForDuplicatesVlanInDb = function(vlan_array) {
        var flag = false;
        $.ajax({
            url: BASE_URL + '/nexusservicemstr/chkIsVlanAlreadyMapped',
            type: 'POST',
            dataType: 'json',
            async: false,
            data: {vlan_array: vlan_array},
            success: function(data) {
                if (data.succsess === true) {
                    flag = true;
                } else {
                    bootbox.alert('VLAN already exists: ' + data.vlan_exists);
                }
            },
        });
        return flag;
    };

    validateForm = function() {
        var flagActiveSouthBond = false;
        var flagStandBySouthBond = false;
        if (chkIsVlanSelected()) {
            if (chkIsNotBlank()) {
                var port_array_active = chkForValidVlanRange('.cls_temp_active_port_no');
                if (port_array_active.length >= 1) {
                    if (chkForDuplicatesVlan(port_array_active)) {
                        flagActiveSouthBond = true;
                    } else {
                        bootbox.alert('Active port should not be overlap.');
                        return false;
                    }
                } else {
                    bootbox.alert('Please select atleast 1 vlan.');
                    return false;
                }
                if ($('.stand_by_selector').attr('checked') === 'checked') {
                    var port_array_standby = chkForValidVlanRange('.cls_temp_standby_port_no');
                    if (port_array_standby.length >= 1) {
                        if (chkForDuplicatesVlan(port_array_standby)) {
                            flagStandBySouthBond = true;
                        } else {
                            bootbox.alert('Standby port should not be overlap.');
                            return false;
                        }
                    } else {
                        bootbox.alert('Standby select atleast 1 vlan.');
                        return false;
                    }
                } else {
                    flagStandBySouthBond = true;
                }
            }

        }
        if (flagActiveSouthBond && flagStandBySouthBond)
            return true;
        return false;

    };

    $('.submit-btn-primary').live('click', validateForm);
    $('.customAddMore').live('click', makeClone);
    $('.customRemoveRow').live('click', RemoveRow);
    $('.customAddMoreStandby').live('click', makeCloneStandby);
    $('.customRemoveRowStandby').live('click', RemoveRowStandby);

});

