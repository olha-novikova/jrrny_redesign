jQuery(document).ready(function($) {

	$('body').on('click', '#lostpassword_btn', function(event) {
		event.preventDefault();
		$('#lostpassword_form').submit();
	});

	$('body').on('submit', '#lostpassword_form', function(event) {
		event.preventDefault();
		var validate = new Validate('#lostpassword_form', ["#user_login"], ".input-group");
		validate.messages.email="<i class=\"err-message user-message\">(Provided email is incorrect!)</i>";
		var that = $(this);
		if(validate.validated()) {
			var input_data = jQuery("#lostpassword_form").serialize();
			$.ajax({
				type: 'post',
			 	url: defineURL('ajaxurl'),
			 	data: input_data,
			 	beforeSend: function () {
                    //Turn on processing
                    $("#lostpassword_btn .processing-icon")
	                    .removeClass('hide fa-check')
	                    .addClass('rotating fa-refresh');
                    $('#lostpassword_btn').prop('disabled', true);
                },
			 	success: function(response) {
			 		if(response.status === 'ok'){
			 			$("#lostpassword_btn .processing-icon")
				 			.removeClass('rotating fa-refresh')
				 			.addClass('hide fa-check');
				 		$('#lostpassword_btn').prop('disabled', false);
			 			$('#lostpassword_form #user_login').val("");
			 			$('#jrrny_lostpass_modal').modal();
			 		}else {
			 			$('#lostpassword_form #form-error').append(response.msg);
			 		}
			 	}
			 });
		}
	});
});