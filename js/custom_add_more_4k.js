/**
 * 
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
        });
        $('#row_copy_id_' + window.count + ' select[multiple="multiple"]').each(function() {
            $(this).val('');
            $(this).attr('name', 'data[' + window.count + '][' + $(this).attr('data-name') + '][]');
            $(this).attr('id', 'data_' + window.count + '_' + $(this).attr('data-name'));
        })
        
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRow" href="javascript:void(0);"></a>').insertAfter('.icon-plus-sign:last');

    };

    RemoveRow = function(event) {
        $(this).closest('.row_copy').remove();
    };

    $(document).on('click','.customAddMore', makeClone);
    $(document).on('click','.customRemoveRow', RemoveRow);
    
    
    makeCloneAccessPoint = function(event) {
        window.count++;
        $($('.row_copy_ap_port:first')).clone().insertAfter('.row_copy_ap_port:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'apdata[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'apdata_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowAccessPoint" href="javascript:void(0);"></a>').insertAfter('.ap_port:last');

    };

    RemoveRowAccessPoint = function(event) {
        $(this).closest('.row_copy_ap_port').remove();
    };

    $(document).on('click','.customAddMoreAccessPoint', makeCloneAccessPoint);
    $(document).on('click','.customRemoveRowAccessPoint', RemoveRowAccessPoint);



    makeCloneL2Ring = function(event) {
        window.count++;
         var  len = $('.validateL2seq').length + $('.validatel2device_seq_no').length;
        if(len < 8){
		
        $($('.row_copy_l2_ring:first')).clone(false, false).find('input[id="est_hrs"]').val('').end().find('input[id="stask_id"]').val('').end().insertAfter('.row_copy_l2_ring:last').attr('id', 'row_copy_id_' + window.count);
        /* $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'l2_ring[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'l2_ring_' + window.count + '_' + $(this).attr('data-name'));
        }) */
        $('.row_copy_l2_ring:last').find('.datepicker').val('').removeAttr('id').removeClass('hasDatepicker');
        addDatePicker($('.row_copy_l2_ring:last').find('.datepicker'));
        if($('#PidApproval_pid_id').length == 0) {
            $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowl2_ring" href="javascript:void(0);"></a>').insertAfter('.l2_ring:last');
        }
        }else{
            alert("Limit exceeded");
        }
    };

    RemoveRowL2Ring = function(event) {
        $(this).closest('.row_copy_l2_ring').remove();
    };

    $(document).on('click','.customAddMorel2_ring', makeCloneL2Ring);
    $(document).on('click','.customRemoveRowl2_ring', RemoveRowL2Ring);
    
    makeCloneL2SpurRing = function(event) {
        window.count++;
        var  len = $('.validateL2seq').length + $('.validatel2device_seq_no').length;
        if(len < 8){
        $($('.row_copy_l2spur_ring:first')).clone().insertAfter('.row_copy_l2spur_ring:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'l2spur_ring[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'l2spur_ring_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowl2spur_ring" href="javascript:void(0);"></a>').insertAfter('.l2spur_ring:last');
        }else{
            alert("Limit exceeded");
        }
    };

    RemoveRowL2SpurRing = function(event) {
        $(this).closest('.row_copy_l2spur_ring').remove();
    };

    $(document).on('click','.customAddMorel2spur_ring', makeCloneL2SpurRing);
    $(document).on('click','.customRemoveRowl2spur_ring', RemoveRowL2SpurRing);
    
    
    makeCloneAPRing = function(event) {
        window.count++;
        $($('.row_copy_ap_ring:first')).clone().insertAfter('.row_copy_ap_ring:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'ap_ring[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'ap_ring_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowap_ring" href="javascript:void(0);"></a>').insertAfter('.ap_ring:last');

    };

    RemoveRowAPRing = function(event) {
        $(this).closest('.row_copy_ap_ring').remove();
    };
   
    makeCloneSpurAPRing = function(event) {
        window.count++;
        $($('.row_copy_spur_ap_ring:first')).clone().insertAfter('.row_copy_spur_ap_ring:last').attr('id', 'spur_row_copy_id_' + window.count);
        $('#spur_row_copy_id_' + window.count + ' input, #spur_row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'spur_ap_ring[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'spur_ap_ring_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowSpurap_ring" href="javascript:void(0);"></a>').insertAfter('.ap_ring:last');

    };

    RemoveRowSpurAPRing = function(event) {
        $(this).closest('.row_copy_spur_ap_ring').remove();
    };

    $(document).on('click','.customAddMoreap_ring', makeCloneAPRing);
    $(document).on('click','.customRemoveRowap_ring', RemoveRowAPRing);
    
    $(document).on('click','.customAddMoreSpurap_ring', makeCloneSpurAPRing);
    $(document).on('click','.customRemoveRowSpurap_ring', RemoveRowSpurAPRing);
    
    
    makeClone2960Ring = function(event) {
        window.count++;
        $($('.row_copy_2960_ring:first')).clone().insertAfter('.row_copy_2960_ring:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'ring2960[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'ring2960_' + window.count + '_' + $(this).attr('data-name'));
        });
        $('#row_copy_id_' + window.count + ' select[multiple="multiple"]').each(function() {
            $(this).val('');
            $(this).attr('name', 'ring2960[' + window.count + '][' + $(this).attr('data-name') + '][]');
            $(this).attr('id', 'ring2960_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRow2960_ring" href="javascript:void(0);"></a>').insertAfter('.ring_2960:last');

    };

    RemoveRow2960Ring = function(event) {
       $(this).closest('.row_copy_2960_ring').remove(); 
       };
    $(document).on('click','.customAddMore2960_ring', makeClone2960Ring);
    $(document).on('click','.customRemoveRow2960_ring', RemoveRow2960Ring);
    
    makeCloneSpur2960Ring = function(event) {
        window.count++;
        $($('.row_copy_spur_2960_ring:first')).clone().insertAfter('.row_copy_spur_2960_ring:last').attr('id', 'row_copy_id_' + window.count);
        $('#row_copy_id_' + window.count + ' input, #row_copy_id_' + window.count + ' select').each(function() {
            $(this).val('');
            $(this).attr('name', 'spurring2960[' + window.count + '][' + $(this).attr('data-name') + ']');
            $(this).attr('id', 'spurring2960_' + window.count + '_' + $(this).attr('data-name'));
        });
        $('#row_copy_id_' + window.count + ' select[multiple="multiple"]').each(function() {
            $(this).val('');
            $(this).attr('name', 'spurring2960[' + window.count + '][' + $(this).attr('data-name') + '][]');
            $(this).attr('id', 'spurring2960_' + window.count + '_' + $(this).attr('data-name'));
        })
        $('&nbsp; $nbsp; <a class="icon-remove-sign customRemoveRowSpur_2960_ring" href="javascript:void(0);"></a>').insertAfter('.spur_ring_2960:last');

    };

    RemoveRowSpur2960Ring = function(event) {
       $(this).closest('.row_copy_spur_2960_ring').remove(); 
       };
    $(document).on('click','.customAddMoreSpur_2960_ring', makeCloneSpur2960Ring);
    $(document).on('click','.customRemoveRowSpur_2960_ring', RemoveRowSpur2960Ring);

});

