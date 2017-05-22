var $ = jQuery.noConflict();

jQuery(document).ready(function ($) {
    
    $(document).on('submit', '#review_profile', function (event) {
        
        event.preventDefault();
        
            $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'review_profile',
                    current_url: defineURL('current_url')
                },
                beforeSend: function () {
                    $("#review_profile .processing-icon")
                            .removeClass('hide fa-check')
                            .addClass('rotating fa-refresh');
                },
                complete: function () {
                    $("#review_profile .processing-icon")
                            .removeClass('rotating fa-refresh')
                            .addClass('hide fa-check');
                },
                success: function (response) {       
                    if(response.status === 'error'){             
                        $('#form-validation').html(response.msg);
                        $('#submit_review').addClass('disabled');
                    }
                    else{                        
                        $('#contribute-modal').modal('hide');
                    }
                }
            });
        
    });    
});