//
//	jQuery Validate example script
//
//	Prepared by David Cochran
//
//	Free for your use -- No warranties, no guarantees!
//
$(document).ready(function(){
	// Validate
	// http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	// http://docs.jquery.com/Plugins/Validation/
	// http://docs.jquery.com/Plugins/Validation/validate#toptions

	  
	  $('#loginPage').validate({
	    rules: {
	      usreid: {minlength: 6,required: true,}, 		  		  
		  password: {required: true, minlength: 6}, 
          confirmPassword: {required: true, equalTo: "#password", minlength: 5},   
	    },
		highlight: function(element) {
			$(element).closest('.control-group').removeClass('success').addClass('error');
		},
			success: function(element) {
				element
				.html('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });


}); // end document.ready