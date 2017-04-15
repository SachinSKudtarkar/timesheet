$(document).ready(function(){
  
  $("#topology").change(function(){
    var topology = $(this).val();
    document.location=BASE_URL+'/parseronec?topology='+topology;
  });
  
  $("#city").change(function(){
      var topology = $('#topology').val();
      var city = $(this).val();
      document.location=BASE_URL+'/parseronec?topology='+topology+'&city='+city;
  });
  
  
});
