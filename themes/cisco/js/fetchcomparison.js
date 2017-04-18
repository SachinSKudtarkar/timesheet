$(document).ready(function(){
	
	$('.chkOutput').live('click',function(){
		
		$('#content').html('');
		var id = $(this).data('val');
		
     
            $.ajax({
                      url: BASE_URL+"/compareFiles/fetchOutput",
                      type: 'POST',
                      dataType: 'html',
                      data:{id:id},
                      beforeSend:function(){
//                          $('#CompareBtn').val("Comparing please wait...");
                       },
                       complete:function(){

                       },
                      success: function(data)
                      {// 
                       if(data.status=="ERROR")
                           {
                              $('#content').html('Could not Fetch Output');        
                           }
                      if(data.status=="SUCCESS")
                           {
                              var con=data.filecontent;
                              cony="<a href='"+con+'>View Output</a>';
                              $('#content').html(cony);
                          
                      },
                      error: function(XMLHttpRequest, data, errorThrown){
                      },
               });
		
	
	});
	
});