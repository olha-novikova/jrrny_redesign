jQuery(function ($) {
    var file_frame;

    jQuery('.upload_image_button').live('click', function (event) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: true  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            $('#img-wrapper').html('');
            var files = [];
            var selection = file_frame.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                
                $('#img-wrapper').append('<span data-id="' + attachment.id + '" title="' + attachment.title + '"><img src="' + attachment.url + '" alt="" /></span>');
                
                files.push(attachment.url);
            });
            $('#img').val(files);
            $('#img_count').val(files.length);
        });

        // Finally, open the modal
        file_frame.open();
    });
    
    $(document).on('click', '.send-newsletter', function (event) {
        event.preventDefault();
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'plc_send_newsletter',
                post_id: cmb_ajax_data.post_id
            },
            beforeSend: function () {
                $(".send-newsletter .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".send-newsletter .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                alert(response.msg);
            }
        });
    });
    
    $(document).on('click', '.contribute-mail-invitation', function (event) {
        event.preventDefault();
        
        $th = $(this);
        var userID = $th.data('user'); 
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'contribute_invitation',
                user: userID,
                mail: true
            },
            beforeSend: function () {
                $(".processing-icon", this)
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".processing-icon", this)
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                $th.parent('.column-contribute').html(response.action);
            }
        });
    });
    $(document).on('click', '.contribute-invitation', function (event) {
        event.preventDefault();
        
        $th = $(this);
        var userID = $th.data('user'); 
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'contribute_invitation',
                user: userID
            },
            beforeSend: function () {
                $(".processing-icon", this)
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".processing-icon", this)
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                $th.parent('.column-contribute').html(response.action);
            }
        });
    });
    $(document).on('click', '.contribute-mark', function (event) {
        event.preventDefault();
        $th = $(this);
        var userID = $th.data('user'); 
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'contribute_mark',
                user: userID
            },
            beforeSend: function () {
                $(".processing-icon", this)
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".processing-icon", this)
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                $th.parent('.column-contribute').html(response.action);
            }
        });
    });
    
    $(document).on('click', '.add-user-to-newsletter', function (event) {
        event.preventDefault();
        
        var userID = $('#userToNewsletter').val();
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'add_user_to_newsletter',
                users: userID,
            },
            beforeSend: function () {
                $(".send-newsletter .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".send-newsletter .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                //alert(response.msg);
                user = $('#userToNewsletter option[value="' + userID + '"]').text();
                $('#list-added').append('<li><strong>' + user + '</strong> added succesfully to our subscribers list</li>');
                $('#userToNewsletter option[value="' + userID + '"]').remove();
            }
        });
    });
    
    $(document).on('click', '.remove-user-from-newsletter', function (event) {
        event.preventDefault();
        
        $th = $(this);
        var code = $th.data('code');
        var user_id = $th.data('id');
                
        var btn_id = $th.attr('id');
        btn_icon = '#' + btn_id + ' .fa';
        
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'ajax_plc_remove_subscriber',
                code: code,
            },
            beforeSend: function () {
                $(btn_icon)
                        .removeClass('fa-trash')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(btn_icon)
                        .removeClass('rotating fa-refresh')
                        .addClass('fa-check');
            },
            success: function (response) {
                //alert(response.msg);                
                $th.parent('td').parent('tr').remove();
            }
        });
    });
    
    $(document).on('click', '.add-to-cron', function (event) {
        event.preventDefault();
        
        $th = $(this);
        recurrence = $('#recurrence').val();
        datetime = $('#datetimepicker').val();
        id = $('#cron-id').val();
        post = cmb_ajax_data.post_id; 
       
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'ajax_plc_save_cron',
                recurrence: recurrence,
                post: post,
                datetime: datetime,
                id: id
            },
            beforeSend: function () {
                $(".add-to-cron .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".add-to-cron .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                alert(response.msg);
                if(response.status === 'ok'){
                    window.location.reload();
                }
            }
        });
    });
    $(document).on('click', '.remove-from-cron', function (event) {
        event.preventDefault();
        
        $th = $(this);
        id = $('#cron-id').val();
       
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'ajax_plc_remove_cron',
                id: id
            },
            beforeSend: function () {
                $(".remove-from-cron .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".remove-from-cron .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                alert(response.msg);
                if(response.status === 'ok'){
                    window.location.reload();
                }
            }
        });
    });
    if($('#datetimepicker').length){
    jQuery('#datetimepicker').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        minDate:'+1970/01/02'
      });
  }
});
   