$(document).ready(function(){
	
	$('.chkOutput').live('click',function(){
		
		$('#content').html('');
		var id = $(this).data('val');
		
     
            $.ajax({
                      url: BASE_URL+"/serviceInstanceCorrections/fetchOutput",
                      type: 'POST',
                      dataType: 'json',
                      data:{id:id},
                      beforeSend:function(){
//                          $('#CompareBtn').val("Comparing please wait...");
                       },
                       complete:function(){

                       },
                      success: function(data)
                      {
                          if(data.status=="ERROR")
                          {
                              
                          }
                          if(data.status=="SUCCESS")
                          {
                              $('#content').html(data.output);
                          }
                      },
                      error: function(XMLHttpRequest, data, errorThrown){
                      },
               });
		
	
	});
	
});