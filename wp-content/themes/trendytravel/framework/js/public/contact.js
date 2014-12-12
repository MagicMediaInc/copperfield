jQuery.noConflict();

jQuery(document).ready(function($){
	"use strict";
	
	if($(".btn-book").length) {
		$(".btn-book").colorbox({ inline:true, width:"auto" });
	}
	
	if($(".contact-frm").length) {
		$(".contact-frm").validate({
		  onfocusout: function(element){ $(element).valid(); },
			rules: { 
				cname: { required: true, minlength: 2 },
				cemail: { required: true, email: true },
				cmessage: { required: true, minlength: 10 }			
			}
		});
	}
	
	if($(".booknow-frm").length) {
		$(".booknow-frm").validate({
			onfocusout: function(element){ $(element).valid(); },
			debug: true,
			rules: {
				txtfname: { required: true, minlength: 2 },
				txtemail: { required: true, email: true },
				txtdate: { required: true }
			}
		});
	}
	
	//AJAX SUBMIT...
	$('.contact-frm, .booknow-frm').submit(function () {
      
		var This = $(this);
        var data_value = null;
		
		if($(This).valid()) {
			var action = $(This).attr('action');

			data_value = decodeURI($(This).serialize());
			$.ajax({
                 type: "POST",
                 url:action,
                 data: data_value,
                 success: function (response) {
                   $('#ajax_message').html(response);
                   $('#ajax_message').slideDown('slow');
                   if (response.match('success') !== null){ $(This).slideUp('slow'); }
                 }
            });
        }
        return false;
    });
});