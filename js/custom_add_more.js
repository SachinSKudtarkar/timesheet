/**
 * 
 * @param {type} param
 */
$(document).ready(function () {
    window.count = 0;
    makeCloneLan = function (event) {
        window.count++;
        $($('.row_copy_lan:first')).clone().insertAfter('.row_copy_lan:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function () {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRow" href="javascript:void(0);"></a>').insertAfter('.lan_pool:last');

    };

    RemoveRowLan = function (event) {
        $(this).closest('.row_copy_lan').remove();
    }
    
    makeCloneVoip = function (event) {
        window.count++;
        $($('.row_copy_voip:first')).clone().insertAfter('.row_copy_voip:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function () {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowVoip" href="javascript:void(0);"></a>').insertAfter('.voip_pool:last');

    };

    RemoveRowVoip = function (event) {
        $(this).closest('.row_copy_voip').remove();
    }

    $('.customAddMore').live('click', makeCloneLan);
    $('.customRemoveRow').live('click', RemoveRowLan);
    $('.customAddMoreVoip').live('click', makeCloneVoip);
    $('.customRemoveRowVoip').live('click', RemoveRowVoip);
});

