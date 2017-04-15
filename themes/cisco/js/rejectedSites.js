$(document).ready(function(){
    $('.btnAction').live('click',function(){
        
        var btnVal = $(this).val();
        
        if(btnVal!='Same Sample' && btnVal!='Different sample'){
            return false;
        }
        $('.custom-loader').show();
        var data_val = this.id;
        var data_batchName = $('#area_code').val();
        
        $.ajax({
                    url: BASE_URL+"/OnebBatchStatus/ResubmitBatchForReliance",
                    type: 'post',
                    dataType: 'json',
                    data: {data_batchName : data_batchName, data_val : data_val},
                    beforeSend:function(){
                        $('#sameSample').val('Processing..');
                        $('#diffSample').val('Processing..');
                     },
                     complete:function(){
                     },
                    success: function(data){
                        $('.custom-loader').hide();
                        if(data.status=="SUCCESS")
                        {
                            $('#actionButtons').html("<b>Batch Submited</b>");
                             window.location.reload( 'CHelper::createUrl("OnebBatchStatus/viewRejetcedSites")');
                        }
                        else
                        {
                            $('#sameSample').val('Same Sample');
                            $('#diffSample').val('Different sample');
                        }
                    },
                    error: function(XMLHttpRequest, data, errorThrown){
                        alert("Failed");
                    },
                });
    })
});