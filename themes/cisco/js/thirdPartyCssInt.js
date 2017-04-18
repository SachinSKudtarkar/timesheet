$(document).ready(function(){
    
    var ConfirmCount = 0;
    var parentResult = "";
    $('.ConfirmBtn').live('click',function(){
        
        if($(this).val()=='Confirmed'){ return false; }
        
        var id = parseInt($(this).data("id"));
        
        $(this).val('Confirmed');
        ConfirmCount++;
        
        if(ConfirmCount==2)
        {
            ConfirmCount = 0;
            var TotalCnt = parseInt($('#TotalCnt').val());
            var nxtSearch = TotalCnt+1;
            $('#TotalCnt').val(nxtSearch);

                $.ajax({
                       url: BASE_URL+"/ThirdPartyCssIntegration/getSearchForm",
                       type: 'POST',
                       dataType: 'html',
                       data:{ nxtSearch:nxtSearch},
					   beforeSend:function(){
                        },
                       success: function(data)
                       {
                          var scrollPx = 500*parseInt(id);
                          $("#searchbox_"+TotalCnt).after(data);
                          $('body, html').animate({
                                scrollTop: $('#SearchBtn_'+nxtSearch).scrollTop() + scrollPx
                           }, 500);
                       },
                });
        }
    });
    
    
    
    $('.SearchBtn').live('click',function(){
        
        var currBtn = this;
        var id = parseInt($(this).data("id"));
        var scrollPx = 500*id;
       
        var chkValid = 0;
        var hostname = $('#hostname_'+id).val();
        var sapid = $('#sapid_'+id).val();
        var neid = $('#neid_'+id).val();
        var loopback0 = $('#loopback0_'+id).val();
        var eastwest = "";
        
        
        
        if($("#east_"+id).is(':checked'))
            eastwest = "e";
        if($("#west_"+id).is(':checked'))
            eastwest = "w";
        if($("#east_"+id).is(':checked') && $("#west_"+id).is(':checked'))
            eastwest = "ew";
        
        if(hostname=="")
            chkValid++;
        if(sapid=="")
            chkValid++;
        if(neid=="")
            chkValid++;
        if(loopback0=="")
            chkValid++;
            
        if(chkValid>1)
        {
            
            $('#processStatus_'+id).css("color","#FF0000");
            $(currBtn).attr("data-isvalid","0");
            return false;
        }
        
        $('#processStatus_'+id).css("color","#000000");
        
        
        $.ajax({
                   url: BASE_URL+"/ThirdPartyCssIntegration/CssInfo",
                   type: 'POST',
                   dataType: 'JSON',
                   data:{ hostname:hostname, sapid:sapid, neid:neid, loopabck0:loopback0, eastwest:eastwest},
                   beforeSend:function(){
                    },
                   success: function(data)
                   {
                        var diff = 0;
                        if(data.result=="SUCCESS")
                        {
                            
                            
                            
                            if(parentResult!="")
                            {
                                for (var prop in data) 
                                {
                                    if(prop!="eastwest" && prop!="neid" && prop!="loopback0" && prop!="hostname" && prop!="sapid" && prop!="e_neig_int_ip" && prop !="w_neig_int_ip" && prop!="e_neig_loopback0" && prop!="w_neig_loopback0" && prop!="input_type" && prop!="fiber_microwave"){
                                     if(data[prop] != parentResult[prop]){
                                         diff++;
                                     }
                                    }
                                }
                            }
                               
                            if(diff>0)
                            {
                                $('#processStatus_'+id).html("Search result is not matching with parent AG1");
                                $('#processStatus_'+id).css("color","#FF0000");
                                $('#ConfirmBtnNew_'+id).addClass("Disabled");
                                $(currBtn).attr("data-isvalid","0");
                                return false;
                            }
                            
                            if(eastwest!=data.eastwest)
                            {
                                $("#processStatus_"+id).html("Given East/West neighbour information is not matching with result. Please verify and seatch again.");
                                $('#processStatus_'+id).css("color","#FF0000");
                                $(currBtn).attr("data-isvalid","0");
                                $(currBtn).attr("data-isvalid","0");
                                return false;
                            }
                            
                            
                            if(hostname=="")
                                $('#hostname_'+id).val(data.hostname);
                            if(sapid=="")
                                $('#sapid_'+id).val(data.sapid);
                            if(neid=="")
                                $('#neid_'+id).val(data.neid);
                            if(loopback0=="")
                                $('#loopback0_'+id).val(data.loopback0);
                            
                            $('#e_neig_int_ip_'+id).val(data.e_neig_int_ip);
                            $('#w_neig_int_ip_'+id).val(data.w_neig_int_ip);
                            $('#e_neig_loopback0_'+id).val(data.e_neig_loopback0);
                            $('#w_neig_loopback0_'+id).val(data.w_neig_loopback0);
                            
                            $('#input_type_'+id).val(data.input_type);
                            $('#fiber_microwave_'+id).val(data.fiber_microwave);
                            
                            if(parentResult=="")
                            {
                                
                                $("#SubmitBtn").show();
                                parentResult = data;
                                var isEastNeigh = "-";
                                var isWestNeigh = "-";
                                var EastWest = data.eastwest;

                                if(EastWest=="e")
                                    var isEastNeigh = "East Neighbour";

                                if(EastWest=="w")
                                    var isWestNeigh = "West Neighbour";

                                if(EastWest=="ew")
                                {
                                    var isEastNeigh = "East Neighbour";
                                    var isWestNeigh = "West Neighbour";
                                }

                                $('#e_neigh_'+id).html(isEastNeigh);
                                $('#w_neigh_'+id).html(isWestNeigh);

                                $('#e_hostname_'+id).html(data.east_ag1_hostname);
                                $('#e_loopback0_'+id).html(data.e_ag1_loopback0);
                                $('#e_loopback100_'+id).html(data.e_ag1_l100);
                                $('#e_sapid_'+id).html(data.east_ag1_sapid);
                                $('#e_ring_'+id).html(data.css_ring);

                                $('#w_hostname_'+id).html(data.west_ag1_hostname);
                                $('#w_loopback0_'+id).html(data.w_ag1_loopback0);
                                $('#w_loopback100_'+id).html(data.w_ag1_l100);
                                $('#w_sapid_'+id).html(data.west_ag1_sapid);
                                $('#w_ring_'+id).html(data.css_ring);

                                $('#routerstatus_'+id).html("East/West Ag1 found");

                                $('#resultbox_'+id).slideDown(500,function(){
                                    $('body, html').animate({
                                        scrollTop: $('#NextBtn_'+id).scrollTop() + scrollPx
                                    }, 500);
                                });
                                $("#SubmitBtn").removeClass("Disabled");
                            }
                            else
                            {
                                $('#processStatus_'+id).html("Given CSS information is of same ring");
                                $('#processStatus_'+id).css("color","#009900");
                                $('#ConfirmBtnNew_'+id).show();
                                $("#SubmitBtn").removeClass("Disabled");
                                $('#ConfirmBtnNew_'+id).removeClass("Disabled");
                            }
                            
                            $(currBtn).attr("data-isvalid","1");
                            
                        }
                        else
                        {
                            $('#processStatus_'+id).html(data.description);
                            $('#processStatus_'+id).css("color","#FF0000");
                        }
       
                   },
            });
    });
    
    
    $('.ConfirmBtnNew').live('click',function(){
        
        if($(this).val()=='Confirmed' || $(this).hasClass("Disabled")){ return false; }
        var id = parseInt($(this).data("id"));
        $(this).val("Confirmed");
        $('#NextBtn_'+id).show();
        
    });
    
    
    $('.NextBtn').live('click',function(){
        $(this).removeClass("NextBtn");
        $(this).css("background","#bdbdbd");
        var id = parseInt($(this).data("id"));
        ConfirmCount = 0;
        var TotalCnt = parseInt($('#TotalCnt').val());
        var nxtSearch = TotalCnt+1;
        $('#TotalCnt').val(nxtSearch);

            $.ajax({
                   url: BASE_URL+"/ThirdPartyCssIntegration/getSearchForm",
                   type: 'POST',
                   dataType: 'html',
                   data:{ nxtSearch:nxtSearch},
                beforeSend:function(){
                    },
                   success: function(data)
                   {
                      var scrollPx = 500*parseInt(id);
                      $("#searchbox_"+TotalCnt).after(data);
                      $('body, html').animate({
                            scrollTop: $('#SearchBtn_'+nxtSearch).scrollTop() + scrollPx
                       }, 500);
                   },
            }); 
    });
    
    $("#SubmitBtn").live('click',function(){
        if($(this).hasClass("Disabled"))
        {
            alert("Please make sure you have entered correct information");
            return false;
        }
        
        var cnt = $("#TotalCnt").val();
        var data = [];
        
        var e_ag1_hostname = $('#e_hostname_1').html();
        var e_ag1_loopback0 = $('#e_loopback0_1').html();
        var e_ag1_loopback100 = $('#e_loopback100_1').html();
        var e_ag1_sapid = $('#e_sapid_1').html();
        var ring_num = $('#e_ring_1').html();
        var interface_no = "";
        var bdi = "BDI 35"+ring_num;
        
        if(ring_num == 1){ interface_no = "7"; }
        if(ring_num == 2){ interface_no = "6"; }
        if(ring_num == 3){ interface_no = "5"; }
        if(ring_num == 4){ interface_no = "4"; }
        if(ring_num == 5){ interface_no = "3"; }
        
        var w_ag1_hostname = $('#w_hostname_1').html();
        var w_ag1_loopback0 = $('#w_loopback0_1').html();
        var w_ag1_loopback100 = $('#w_loopback100_1').html();
        var w_ag1_sapid = $('#w_sapid_1').html();
        
        for(var i=1; i<=cnt; i++)
        {
           var isValid = $('#SearchBtn_'+i).data("isvalid");
           var hostname = $('#hostname_'+i).val();
           var loopback0 = $('#loopback0_'+i).val();
          
           var e_neig_int_ip = $('#e_neig_int_ip_'+i).val();
           var w_neig_int_ip = $('#w_neig_int_ip_'+i).val();
           
           var e_neig_loopback0 = $('#e_neig_loopback0_'+i).val();
           var w_neig_loopback0 = $('#w_neig_loopback0_'+i).val();
           
           var input_type = $('#input_type_'+i).val();
           var fiber_microwave = $('#fiber_microwave_'+i).val();
           
           var sapid = $('#sapid_'+i).val();
           var neid = $('#neid_'+i).val();
           var is_east_neigh = 0;
           var is_west_neigh = 0;
           
           if($("#east_"+i).is(':checked'))
               is_east_neigh = 1;
           if($("#west_"+i).is(':checked'))
               is_west_neigh = 1;
           
           
           var notValid = 0;
           
           if(hostname=="")
                notValid++;
            if(sapid=="")
                notValid++;
            if(neid=="")
                notValid++;
            if(loopback0=="")
                notValid++;
            
            
            if(notValid==0 && isValid==1)
            {
                data[i] = {};
                data[i]["e_ag1_hostname"] = e_ag1_hostname;
                data[i]["e_ag1_loopback0"] = e_ag1_loopback0;
                data[i]["e_ag1_loopback100"] = e_ag1_loopback100;
                data[i]["e_ag1_sapid"] = e_ag1_sapid;
                data[i]["is_east_neigh"] = is_east_neigh;
                data[i]["e_neig_int_ip"] = e_neig_int_ip;
                data[i]["e_neig_loopback0"] = e_neig_loopback0;
                data[i]["w_neig_loopback0"] = w_neig_loopback0;
                data[i]["w_neig_int_ip"] = w_neig_int_ip;
                
                data[i]["input_type"] = input_type;
                data[i]["fiber_microwave"] = fiber_microwave;
                
                data[i]["w_ag1_hostname"] = w_ag1_hostname;
                data[i]["w_ag1_loopback0"] = w_ag1_loopback0;
                data[i]["w_ag1_loopback100"] = w_ag1_loopback100;
                data[i]["w_ag1_sapid"] = w_ag1_sapid;
                data[i]["is_west_neigh"] = is_west_neigh;
                
                data[i]["hostname"] = $('#hostname_'+i).val();
                data[i]["loopback0"] = $('#loopback0_'+i).val();
                data[i]["sapid"] = $('#sapid_'+i).val();
                data[i]["neid"] = $('#neid_'+i).val();
                data[i]["bdi"] = bdi;
                data[i]["ring_num"] = ring_num;
                data[i]["interface_no"] = interface_no;
                data[i]["is_east_neigh"] = is_east_neigh;
                data[i]["is_west_neigh"] = is_west_neigh;
            }
        }
//        console.log(data); return false;
        $.ajax({
               url: BASE_URL+"/ThirdPartyCssIntegration/integrateThirdPartyCss",
               type: 'POST',
               dataType: 'JSON',
               data:{ data:data},
            beforeSend:function(){
                    $("#SubmitBtn").val("Processing...");
                },
                complete:function(){
                    $("#SubmitBtn").val("Submit");   
                },
               success: function(data)
               {
//                   console.log(data);
                  alert("Your Request ID: "+data);
                  location.reload();
               },
        });
        
    });
    
});   