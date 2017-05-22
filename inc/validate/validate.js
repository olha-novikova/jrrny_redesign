// Making jQuery Form Validator

var Validate = function(form, items, parents) {
	this.messages = {
		all: "<i class=\"err-message all-message\">Please fill out all fields!</i>",
		empty: "<i class=\"err-message user-message\">( This field is required )</i>",
		email:"<i class=\"warning-message user-message\">(Provided email is incorrect!)</i>",
		name:"<i class=\"warning-message user-message\">(Your name must contain only letters and be a real name!)</i>",
		uname:"<i class=\"warning-message user-message\">(Your username must contain only letters and be from 4 to 18 characters in length!)</i>",
		pass:"<i class=\"err-message user-message\">(Your password must contain only letters and numbers and be at least 6 characters in length!)</i>"
	};

	if (typeof jQuery !== "undefined" && jQuery !== null) {
		$ = jQuery.noConflict();
	}
	this.email_regex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
	this.name_regex = /^[a-zA-Z]{2,30}$/;
	this.username_regex = /^[a-zA-Z0-9]{4,18}$/;
	this.password_regex = /^([a-zA-Z0-9]{6,})$/;
	email_regex = this.email_regex;
	name_regex = this.name_regex;
	username_regex = this.username_regex;
	password_regex = this.password_regex;
	form = $(form);
	if(form.length > 0) {
		this.validated = function() {
			var i, success, errors;
			errors = 0;
			form.find(".user-message").remove();
			if(items && Array.isArray(items) && items.length > 0) {
				for(var i = 0; i < items.length; i++) {
                                    
					if($(items[i]).is('input') === false){
						if($(items[i]).hasClass("dropzone") 
							&& $(items[i]).find('.dz-complete').length <= 0
						){
							errors = errors + 1;
							$(items[i]).after(this.messages.empty);                                                                                                    
                                                        $(items[i]).css("border-color", "red");
						}
                                                if($('#wp-story-wrap').length && $(items[i]).attr('id') == 'story' && $(items[i]).val() == ""){ 
                                                    errors = errors + 1;
                                                    $('#wp-story-wrap').after(this.messages.empty);   
                                                    $('#wp-story-wrap').css("border", "1px solid red");
                                                }
					}else if($(items[i]).val() == "") {
						errors = errors + 1;
				 		$(items[i]).after(this.messages.empty);                                                  
                                                $(items[i]).css("border", "1px solid red");
                                                
				 	}
				 	else {
				 		if($(items[i]).attr("type") == "email") {
				 			if(!email_regex.test($(items[i]).val())) {
				 				errors = errors + 1;
				 				$(items[i]).after(this.messages.email);                                                             
                                                                $(items[i]).css("border", "1px solid red");
				 			}
				 		}
				 		if($(items[i]).attr("type") == "text" && $(items[i]).attr("id") == "name") {
				 			if(!name_regex.test($(items[i]).val())) {
				 				errors = errors + 1;
				 				$(items[i]).after(this.messages.name);                                                           
                                                                $(items[i]).css("border", "1px solid red");
				 			}
				 		}
				 		if($(items[i]).attr("type") == "text" && $(items[i]).attr("id") == "username") {
				 			if(!username_regex.test($(items[i]).val())) {
				 				errors = errors + 1;
				 				$(items[i]).after(this.messages.uname);                                                           
                                                                $(items[i]).css("border", "1px solid red");
				 			}
				 		}
				 		if($(items[i]).attr("type") == "password" && $(items[i]).attr("id") == "password") {
				 			if(!password_regex.test($(items[i]).val())) {
				 				errors = errors + 1;
				 				$(items[i]).after(this.messages.pass);                                                           
                                                                $(items[i]).css("border", "1px solid red");
                                                               
				 			}
				 		}
				 	}
				}
				if(errors == 0) {
					success = true;
				}
				else {
                                        form.find(".all-message").remove();
                                        form.append(this.messages.all);
					success = false;
				}
			}
			return success;
		}
		this.data = function() {
			return form.serialize();
		}
	}
}
