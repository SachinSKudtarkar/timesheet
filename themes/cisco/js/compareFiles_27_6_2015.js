$(document).ready(function(){
  
  $("input[type='radio'][name='compareType']").live('click',function(){
      
    var compareType = $(this).val();
    $('#submitBtn').show();
    if(compareType=="auto")
    {
        $('#frmAuto').show();
        $('#frmManual').hide();
    }
    if(compareType=="manual")
    {
        $('#frmAuto').hide();
        $('#frmManual').show();
    }
    
  });
  
  $('#CompareBtn').live('click',function(){
        
        if($(this).val()!="Compare"){ return false; };
        
                
        var CompareType = $("input[type='radio'][name='compareType']:checked").val();
        
        if(CompareType=="auto")
        {
            var loopback0 = $('#loopback0').val();
            var hostname = $('#hostname').val();

            var ipv4 = /^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/;

            if(loopback0=="")
            {
                alert("Please enter loopback0");
                return false;
            }
            else if(!loopback0.match(ipv4))
            {
                alert("Please enter valid loopback0 (ipv4)");
                return false;
            }
            else if(hostname=="")
            {
                alert("Please enter hostname.");
            }
     
            $.ajax({
                      url: BASE_URL+"/CompareFiles/CompareNipShowrunAuto",
                      type: 'POST',
                      dataType: 'json',
                      data:{hostname:hostname, loopback0:loopback0},
                      beforeSend:function(){
                          $('#CompareBtn').val("Comparing please wait...");
                       },
                       complete:function(){

                       },
                      success: function(data)
                      {
                          if(data.status=="ERROR")
                          {
                              $('#errorMsg').html(data.error);
                              $('#errorMsg').show();
                              $('#errorMsg').fadeOut(10000);
                              $('#CompareBtn').val("Compare");
                              $('input[type="text"]').val("");
                          }
                          if(data.status=="SUCCESS")
                          {
                              $('input[type="text"]').val("");
                              $('#CompareBtn').val("Compare");
                              $('#compareContent').html(data.filecontent);
                              $('#compareContent').slideDown('slow');
                          }
                      },
                      error: function(XMLHttpRequest, data, errorThrown){
                      },
               });
        }
        
        if(CompareType=="manual")
        {
            var nipfile = $('#nip').val(); 
            var showrun = $('#showrun').val();

            if(nipfile==""){
                alert("Please upload nip file"); return false
            }
            if(showrun==""){
                alert("Please upload show run"); return false
            }
     
            $.ajax({
                      url: BASE_URL+"/CompareFiles/CompareNipShowrun",
                      type: 'POST',
                      dataType: 'json',
                      data:{showrun:showrun, nipfile:nipfile},
                      beforeSend:function(){
                          $('#CompareBtn').val("Comparing please wait...");
                       },
                       complete:function(){

                       },
                      success: function(data)
                      {
                          if(data.status=="SUCCESS")
                          {
                              $('#compareContent').html(data.filecontent);
                              $('#compareContent').slideDown('slow');
                          }
                          
//                          $('input[type="radio"]').prop('checked', false);
//                          $('ul').hide();
                          $('input[type="hidden"]').val('');
                          
                          $('#CompareBtn').val("Compare");
                      },
                      error: function(XMLHttpRequest, data, errorThrown){
                      },
               });
        }
    });
});
