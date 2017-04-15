/*
 * Fetch host name for SAP ID
 * 
 */

function validateEastWestSingleAg1(){
    var var_counter = $('#rowCount').val();
    var msg = ''; 
    if(var_counter){
        for ( var i = 0; i<var_counter ;i++ ) {
            var search_host_name_eag1 = $('#NddTempInput_'+i+'_search_host_name_eag1').val() ; 
            var search_host_name_wag1 = $('#NddTempInput_'+i+'_search_host_name_wag1').val();
            if(search_host_name_eag1 == search_host_name_wag1){
               msg +='Record #'+(i+1)+': EAST and WEST AG1 Hostname is same.\n' ;  
            }
        }
    }
    if(msg!=''){
        msg +='\nKindly confirm before proceeding' ; 
        var conf = confirm(msg);
        if (conf == true) {
            $('#ndd-temp-input-form').submit();
        }
    }else{
        $('#ndd-temp-input-form').submit();  
    }    
    return false ; 
}

function fetchHNdata(sapid, fieldid, fieldname, search_hostname, type) {
//    var var_counter = fieldname.match(/\d+/)[0];
    var var_counter = fieldname.match(/\[\d+\]/)[0];

    //var var_nodetype = search_hostname.search("east");

    $("#" + fieldid + "").addClass('error');
    $.ajax({
        type: 'GET',
        url: 'index.php',
        data: {
            sapid: sapid,
            type: type,
            r: 'NddTempInput/fetchhostname',
            //           'nodetype': var_nodetype,
        },
        success: function(data) {
            $("input[name='NddTempInput" + var_counter + "[" + search_hostname + "]']").replaceWith("<select name='NddTempInput" + var_counter + "[" + search_hostname + "]'></select>");
            if (data) {
                var str = jQuery.parseJSON(data);
//              $("label['for=NddTempInput_" + search_hostname + "']").remove();                
                var selobj = "select[name='NddTempInput" + var_counter + "[" + search_hostname + "]']";
                $(selobj).empty();
                $(selobj).next('label').remove();
                $.each(str, function(ind, val) {
                    $(selobj).append("<option value='" + val['id'] + "'>" + val['value'] + "</option>");
                });
                $("#" + fieldid + "").removeClass('error');
            } else {
                var selobj = "select[name='NddTempInput" + var_counter + "[" + search_hostname + "]']";
                $(selobj).attr('style', 'display:none');
                var parentElement = $(selobj).parent();
                parentElement.find('label').remove();
                parentElement.append("<input type='hidden' name='NddTempInput" + var_counter + "[" + search_hostname + "]' value=''>");
                parentElement.append("<label class='error'>GIIS Instantiation not done for this SAPID</label>");
                $(selobj).remove();
            }
        },
        error: function(data) { // if error occured
            alert("Error occured.please try again");
        },
        dataType: 'html'
    });
}

function fetchAG1Hostnames(rowindex, east_ag1_sapid, west_ag1_sapid) {
    $.ajax({
        type: 'GET',
        url: BASE_URL + '/nddTempInput/fetchhostname',
        data: {
            east_ag1_sapid: east_ag1_sapid,
            west_ag1_sapid: west_ag1_sapid,
            type: 'AG1',
        },
        dataType: 'json',
        success: function(data) {
            var east_ag1_hostnames = data.east_ag1_hostnames;
            var west_ag1_hostnames = data.west_ag1_hostnames;            
            $("input[name='NddTempInput[" + rowindex + "][search_host_name_eag1]']").replaceWith("<select name='NddTempInput[" + rowindex + "][search_host_name_eag1]' class='east_ag1_hostname'></select>");
            $("input[name='NddTempInput[" + rowindex + "][search_host_name_wag1]']").replaceWith("<select name='NddTempInput[" + rowindex + "][search_host_name_wag1]' class='west_ag1_hostname'></select>");
            var east_ag1_hostname_element = $("select[name='NddTempInput[" + rowindex + "][search_host_name_eag1]']");                
            var west_ag1_hostname_element = $("select[name='NddTempInput[" + rowindex + "][search_host_name_wag1]']");                
            $(east_ag1_hostname_element).empty();
            $(west_ag1_hostname_element).empty();
            if (east_ag1_hostnames.length) {                                
                east_ag1_hostname_element.parent().find('label').remove();
                $.each(east_ag1_hostnames, function(key, value) {
                    $(east_ag1_hostname_element).append($('<option></option>')
                            .attr('value', value)
                            .text(value));
                });
                east_ag1_hostname_element.show();
            } else {
                east_ag1_hostname_element.parent().find('label').remove();
                east_ag1_hostname_element.hide();
                east_ag1_hostname_element.parent().append('<input type="hidden" name="NddTempInput[' + rowindex + '][search_host_name_eag1]" value="" class="east_ag1_hostname">');
                if(east_ag1_sapid.length > 0){
                    east_ag1_hostname_element.parent().append('<label class=\'error\'>GIIS Instantiation not done for this SAPID</label>');
                }
                east_ag1_hostname_element.remove();
            }
            if (west_ag1_hostnames.length) {                                
                west_ag1_hostname_element.parent().find('label').remove();
                $.each(west_ag1_hostnames, function(key, value) {
                    $(west_ag1_hostname_element).append($('<option></option>')
                            .attr('value', value)
                            .text(value));
                });
                west_ag1_hostname_element.show();
            } else {
                if(typeof west_ag1_sapid !== 'undefined'){
                west_ag1_hostname_element.parent().find('label').remove();
                west_ag1_hostname_element.hide();
                west_ag1_hostname_element.parent().append('<input type="hidden" name="NddTempInput[' + rowindex + '][search_host_name_wag1]" value="">');
                if(west_ag1_sapid.length > 0){
                    west_ag1_hostname_element.parent().append('<label class=\'error\'>GIIS Instantiation not done for this SAPID</label>');
                }
                west_ag1_hostname_element.remove();
                }
            }
        },
        error: function(data) { // if error occured
            alert("Something went wrong. Please try again.");
        },
    });
}
function fetchRcomAG1Hostnames(rowindex, east_ag1_sapid) {
    $.ajax({
        type: 'GET',
        url: BASE_URL + '/nddRcomInput/fetchhostname',
        data: {
            east_ag1_sapid: east_ag1_sapid,            
            type: 'AG1',
        },
        dataType: 'json',
        success: function(data) {
            var east_ag1_hostnames = data.east_ag1_hostnames;            
            $("input[name='NddTempInput[" + rowindex + "][search_host_name_eag1]']").replaceWith("<select name='NddTempInput[" + rowindex + "][search_host_name_eag1]' class='east_ag1_hostname'></select>");            
            var east_ag1_hostname_element = $("select[name='NddTempInput[" + rowindex + "][search_host_name_eag1]']");                            
            $(east_ag1_hostname_element).empty();            
            if (east_ag1_hostnames.length) {                                
                east_ag1_hostname_element.parent().find('label').remove();
                $.each(east_ag1_hostnames, function(key, value) {
                    $(east_ag1_hostname_element).append($('<option></option>')
                            .attr('value', value)
                            .text(value));
                });
                east_ag1_hostname_element.show();
            } else {
                east_ag1_hostname_element.parent().find('label').remove();
                east_ag1_hostname_element.hide();
                east_ag1_hostname_element.parent().append('<input type="hidden" name="NddTempInput[' + rowindex + '][search_host_name_eag1]" value="" class="east_ag1_hostname">');
                if(east_ag1_sapid.length > 0){
                    east_ag1_hostname_element.parent().append('<label class=\'error\'>GIIS Instantiation not done for this SAPID</label>');
                }
                east_ag1_hostname_element.remove();
            }            
        },
        error: function(data) { // if error occured
            alert("Something went wrong. Please try again.");
        },
    });
}
$(document).ready(function() {
    $('.east_ag1_sapid').on('change', function() {
        var parentElement = $(this).parents('tr');
        var rowindex = parentElement.data('rowindex');
        var east_ag1_sapid = $(this).val();
        var west_ag1_sapid = parentElement.find('.west_ag1_sapid').val();
//        var east_ag1_hostname_element = parentElement.find('.east_ag1_hostname');
//        var west_ag1_hostname_element = parentElement.find('.west_ag1_hostname');
        fetchAG1Hostnames(rowindex, east_ag1_sapid, west_ag1_sapid);
    });
    $('.west_ag1_sapid').on('change', function() {
        var parentElement = $(this).parents('tr');
        var rowindex = parentElement.data('rowindex');
        var east_ag1_sapid = parentElement.find('.east_ag1_sapid').val();
        var west_ag1_sapid = $(this).val();
//        var east_ag1_hostname_element = parentElement.find('.east_ag1_hostname');
//        var west_ag1_hostname_element = parentElement.find('.west_ag1_hostname');
        fetchAG1Hostnames(rowindex, east_ag1_sapid, west_ag1_sapid);
    });
    
    $('.rcom_east_ag1_sapid').on('change', function() {
        var parentElement = $(this).parents('tr');
        var rowindex = parentElement.data('rowindex');
        var east_ag1_sapid = $(this).val();        
        fetchRcomAG1Hostnames(rowindex, east_ag1_sapid);
    });
});
