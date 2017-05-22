jQuery(document).ready(function($) {
	$("body").on("click", "#subscribe-btn", function(event) {
		event.preventDefault();
		$(this).closest("#subscribe-form").submit();
	});

	$("body").on("submit", "#subscribe-form", function(event) {
		event.preventDefault();
		$(this).find(".user-message").remove();
		var validate = new Validate("#subscribe-form", ["#email"], ".input-group");
		validate.messages.email = "<span class=\"warning-message user-message\"><i class=\"fa fa-warning\"></i> (Provided email is incorrect!)</span>";
		validate.messages.empty ="<span class=\"err-message user-message\"><i class=\"fa fa-remove\"></i> (This field can't be empty!)</span>";
		var that = $(this);
		if(validate.validated()) {
			$.ajax({
				url: defineURL('ajaxurl'),
				type: 'post',
				data: {
					action:"subscribe",
					"event":"subscribe",
					email: validate.data()
				},
				success: function(response) {
					if(response && typeof $.parseJSON(response) == 'object') {
						var result = $.parseJSON(response);
						if(!result.errors) {
							if(result.subsciber && result.subsciber == "in_list") {
								var success_message = "<div class=\"alert alert-success\" role=\"alert\">";
  								success_message += "<p><span class=\"glyphicon glyphicon-ok\"></span> You have been successfully added to our subscribers list and will be ";
  								success_message += "<a href="+defineURL('home')+" class=\"alert-link\">redirected</a>";
  								success_message += " to home page in <span class=\"timer\"></span> seconds.</p>";
								success_message += "</div>";
								that.fadeOut(function() {
									$(this).html(success_message);
									$(this).fadeIn(function() {
										Timer($(this).find('.timer'), {starttime: 3, endtime: 0});
									});
								});
							}
						}
						else {
							if(result.subscriber && result.subscriber == "in_list") {
								var success_message = "<div class=\"alert alert-success\" role=\"alert\">";
  								success_message += "<p><span class=\"glyphicon glyphicon-ok\"></span> "+result.errors+" You will be ";
  								success_message += "<a href="+defineURL('home')+" class=\"alert-link\">redirected</a>";
  								success_message += " to home page in <span class=\"timer\"></span> seconds.</p>";
								success_message += "</div>";
								that.fadeOut(function() {
									$(this).html(success_message);
									$(this).fadeIn(function() {
										Timer($(this).find('.timer'), {starttime: 5, endtime: 0});
									});
								});
							}
							else {
								that.find("#email").closest(".input-group").after("<span class=\"warning-message user-message\"><i class=\"fa fa-warning\"></i> "+result.errors+"</span>");
							}
						}
					}
				}
			});
		}
	});
});