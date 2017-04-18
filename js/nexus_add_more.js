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
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRow" href="javascript:void(0);"></a>').insertAfter('.icon-plus-sign:last');

    };

    RemoveRow = function(event) {
        $(this).closest('.row_copy').remove();
    }

    RemoveAlreadyAdded = function(event) {
        var that = this;
        bootbox.confirm('Are you sure, you want to delete this VLAN from service?', function(result) {
            if (result) {
                $.ajax({
                    url: BASE_URL + '/nexusservicemstr/removeAlreadyMappedVlansFromService',
                    type: 'POST',
                    dataType: 'json',
                    data: {vlan_map_id: $(that).attr('data-name')},
                    success: function(data) {
                        $(that).closest('.row_copy_added').remove();
                    },
                });
            }
        });


    }

    chkIsNotBlank = function() {
        var is_valid = true;
        $('.required').each(function() {
            if (this.value == '') {
                $(this).addClass('custombackred');
                is_valid = false;
            }
        });
        if (is_valid)
            return true;
        return false;
    };

    chkForValidVlanRange = function() {
        var is_valid = true;
        var vlan_array = Array();
        $('.cls_vlan_temp').each(function() {
            vlan_array.push($(this).val());
//            if (!($.isNumeric($(this).val()) && (parseInt($(this).val()) >= '1260' && parseInt($(this).val()) <= '1640'))) {
            if (!($.isNumeric($(this).val()))) {
                is_valid = false;
            }
        });
        if (is_valid) {
            return vlan_array;
        }
        bootbox.alert('VLAN should be number');
//        bootbox.alert('VLAN should be within Range: 1260 - 1640');
        return false;
    };

    chkForDuplicatesVlan = function(vlan_array) {
        var originalLength = vlan_array.length;
        vlan_array = $.unique(vlan_array);
        var lengthAfterFilter = vlan_array.length;
        if (originalLength === lengthAfterFilter)
            return true;
        else {
            bootbox.alert('VLAN should be unique');
            return false;
        }
    };

    chkForValidDescription = function() {
        var returnFlag = true
        $('.cls_description').each(function() {
            if (!/^[a-z0-9-]+$/i.test($(this).val())) {
                returnFlag = false;
            }
        });
        if (!returnFlag) {
            bootbox.alert('Description should contain only alphanumeric and hyphens');
        }
        return returnFlag;
    };

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
        if (chkIsNotBlank()) {
            var vlan_array = chkForValidVlanRange();
            if ($.isArray(vlan_array)) {
                if (chkForDuplicatesVlan(vlan_array)) {
                    if (chkForValidDescription()) {
                        if (chkForDuplicatesVlanInDb(vlan_array)) {
                            //Logic to be implemented for already exists VLAN for services;
                            return true;
                        }
                        return false;
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
        return false;
    };

    $('.required').live('blur', function() {
        if ($(this).val()) {
            $(this).removeClass('custombackred');
        }
    });

    $('.submit-btn-primary').live('click', validateForm);
    $('.customAddMore').live('click', makeClone);
    $('.customRemoveRow').live('click', RemoveRow);
    $('.removeAlreadyAdded').on('click', RemoveAlreadyAdded);

});

