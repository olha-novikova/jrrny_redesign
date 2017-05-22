var $ = jQuery.noConflict();
function clean_wizzard() {  

    if ($('#wizzard').length) {
        var contest = $('#contest').val();
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'get_wizzard',
                step: 'wizzard_step_2',
                contest: contest
            },
            success: function (response) {
                if ($('.wizzard-step-2').length){
                    $('.wizzard-step-2').remove();
                }
                if ($('#step_2').length) {
                    $('#step_2').append(response.content);
                }                            
            }
        });
        $("#step_1").fadeOut('slow', function(){
            $("#step_2").fadeIn('slow').removeClass('hidden');
        });
    }      
};
jQuery(document).ready(function ($) {
    function show_step_3() {
        $("#step_2").fadeOut('slow', function(){
            $("#step_3").fadeIn('slow').removeClass('hidden');
        });
    }    
    $(document).on('click', '.next-step', function () {
        show_step_3();
    });
    $(document).on('click', '#add-to-contest', function (event) {
        event.preventDefault();
        
        var contest = $('#contest').val();
        var referral_url = $('#referral_url').val();
        var referral_user_id = $('#referral_user_id').val();
            
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'add_to_contest',
                contest: contest,
                referral_url: referral_url,
                referral_user_id: referral_user_id
            },
            beforeSend: function () {
                $("#contestSendEmails .processing-icon")
                    .removeClass('hide fa-check')
                    .addClass('rotating fa-refresh');
            },
            complete: function () {
                $("#contestSendEmails .processing-icon")
                    .removeClass('rotating fa-refresh')
                    .addClass('hide fa-check');
            },
            success: function (response) {                
                clean_site_after_login();
            }
        });
    });
    
    $(document).on('click', '.send-contest-modal-mail', function (event) {
        event.preventDefault();        
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'get_modal',
                modal: 'contest_modal_mail'
            },
            success: function (response) {
                if ($('#contest_mail_modal').length) {
                    $('#contest_mail_modal').remove();
                }
                $('body').append(response.msg);
                $('#contest_mail_modal').modal('show');
            }
        });
    });
    $(document).on('click', '#contestSendEmails', function (event) {
        event.preventDefault();
        $('#contestSendEmails_form').submit();        
    });
    $(document).on('submit', '#contestSendEmails_form', function (event) {
        event.preventDefault();
        
        var contest = $('#contest').val();
        var contestSendEmails = $('#contestSendEmails_form #contestEmails').val();
        
        var validate = new Validate('#contestSendEmails_form', ["#contestSendEmails_form #contestEmails"], ".form-group");
        validate.messages.email = "<i class=\"err-message user-message\">(Provided email is incorrect!)</i>";
        
        if (validate.validated()) {
            $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'send_share_emails',
                    contest: contest,
                    contestSendEmails: contestSendEmails
                },
                beforeSend: function () {
                    $("#contestSendEmails .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
                },
                complete: function () {
                    $("#contestSendEmails .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
                },
                success: function (response) {
                    if (response.status == 'ok') {            
                        $('#contest_mail_modal').modal('hide');
                    }
                    else {
                        $('.err-message').remove();
                        if (response.status == 'fail') {
                            $('#contestSendEmails_form #contestEmails').after("<i class=\"err-message user-message\">" + response.msg + "</i>");
                        } else {
                            $('#contestSendEmails_form #contestEmails').after("<i class=\"err-message user-message\">Undefined eror.!</i>");
                        }
                    }
                }
            });
        }
    });
    $(document).on('hidden.bs.modal', '#contest_mail_modal', function () {
        show_step_3();
    });
    
});