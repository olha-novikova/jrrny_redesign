jQuery(document).ready(function ($) {
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
    $(document).on('click', '.plc-pagination', function (event) {
        event.preventDefault();
        var id = $(this).attr('id');
        var button = $('#' + id);
        var atts = button.data('atts');
        var info = button.data('info');
        var paged = button.data('paged');
        var container = button.data('container');

        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'plc_get_loop',
                atts: atts,
                info: info,
                paged: paged
            },
            beforeSend: function () {
                $(".plc-pagination .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(".plc-pagination .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                if (response.loop) {
                    $(container).append(response.loop);
                    $(container + ' .ts-fade-in').each(function () {
                        $(this).removeClass('ts-fade-in');
                        $(this).css('opacity', 1);
                    });
                }
                button.data('atts', response.atts);
                button.data('info', response.info);
                button.data('paged', response.paged);
                if (response.is_final > 0) {
                    button.addClass('hidden');
                }
            }
        });
    });

    $(document).on('click', '.edit-profile', function (event) {
        event.preventDefault();
        var btn_id = $(this).attr('id');
        btn_icon = '#' + btn_id + ' .processing-icon';

        $('#jrrny-edit-profile-form').submit();
    });
    $('#jrrny-edit-profile-form').submit(function (event) {
        event.preventDefault();
        var form = $(this);

        $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(btn_icon)
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
            },
            complete: function () {
                $(btn_icon)
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
            },
            success: function (response) {
                $("#profile-updated").addClass('hidden');
                $('.error-msg').each(function () {
                    $(this).remove();
                });
                if (response.status == 'ok') {
                    var data = form.serializeArray().reduce(function (obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});


                    var country = $('select option:selected', form).text();
                    if($('.user-widget').length){
                        if (data["first-name"] !== '' && data["last-name"] !== '') {
                            $('#user-name a', $(".user-widget")).html(data["first-name"] + ' ' + data["last-name"]);
                        }
                        
                        if (data.city !== '' && country !== '') {
                            var location = data.city + ", " + country;

                            $('#user-location', $(".user-widget")).html(location);
                        }
                        else{
                            $('#user-location', $(".user-widget")).html('<button class="btn btn-link author-edit-btn" data-toggle="modal" data-target="#jrrny-auhor-edit">Add your location</button>');  
                        }
                        if(data.url){                            
                            $('#user-link', $(".user-widget")).html(data.url); 
                            $('#user-link', $(".user-widget")).attr('href', data.url);                            
                        }
                        else{
                            $('#user-link', $(".user-widget")).html('');  
                        }
                        if(data.description){                            
                            $('#user-description', $(".user-widget")).html(data.description);                            
                        }
                        else{
                            $('#user-description', $(".user-widget")).html('<button class="btn btn-link author-edit-btn" data-toggle="modal" data-target="#jrrny-auhor-edit">Click here to add your description</button>');  
                        }
                    }
                    else if($('#user-brand #profile-data-bar').length){
                        $('.fd', $('#brand-description')).html(nl2br(data.full_description));    
                        $('.so', $('#brand-description')).html(nl2br(data.specials_offers));    
                       
                        if(data.url){                            
                            $('.link a', $('#user-brand #profile-data-bar')).html(data.url); 
                            $('.link a', $('#user-brand #profile-data-bar')).attr('href', data.url);                            
                        }
                        else{
                            $('.link a', $('#user-brand #profile-data-bar')).html('');  
                        }
                        if (data["first-name"] !== '' && data["last-name"] !== '') {
                            $('.name', $('#user-brand #profile-data-bar')).html(data["first-name"] + ' ' + data["last-name"]);
                        }
                        $('.social_link', $('#user-brand #profile-data-bar')).html(response.social); 
                    }
                    else{
                        $('.location', $("#profile-data-bar")).html('');
                        if (data.city !== '' && country !== '') {
                            var location = data.city + ", " + country + "<br />" + "<a href='" + data.url + "'>" + data.url + "</a>";

                            $('.location', $("#profile-data-bar")).html(location);
                        }
                        $('.description', $("#profile-data-bar")).html(data.description);
                        if (data["first-name"] !== '' && data["last-name"] !== '') {
                            $('.name', $("#profile-data-bar")).html(data["first-name"] + ' ' + data["last-name"]);
                        }
                    }
                    $("#jrrny-auhor-edit").modal("hide");
                } else {

                    var msg = "<span class='error-msg'>" + response.msg + "</span>";
                    $(msg).insertAfter("#" + response.type);

                }
            }
        });

    });

    $('.profile-tab', $("#profile-tabs")).click(function () {
        deleteClassesAndHide();
        $(this).addClass('active');
        var tab = $(this).data('tab');
        $('#tab-' + tab).addClass('visible');
    });

    function deleteClassesAndHide() {
        $('.tab-page', $("#main-container")).each(function () {
            $(this).removeClass('visible');
        });

        $('.profile-tab', $("#profile-tabs")).each(function () {
            $(this).removeClass('active');
        });
    }
    $('#jrrny-edit-header-form').submit(function (event) {
        event.preventDefault();
        $('body').append(loader);
        $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#jrrny-edit-header-form-error').addClass('hidden');
            },
            success: function (response) {
                if (response.status == 'ok') {
                    $('#profile-header').attr('style', "background: url(" + response.url + "); background-size: cover;");
                    $('.fileUpload span').html('Edit photo');
                    $('#profile-header').removeClass('no-image');
                    $('#profile-header').addClass('image');
                } else {
                    $('#jrrny-edit-header-form-error').html(response.msg);
                    $('#jrrny-edit-header-form-error').removeClass('hidden');
                }
                $('#loader').remove();
            },
            complete: function () {
                $("#jrrny-edit-header-form #jrrny-edit-header-file").val("");
            }
        });
    });


    $("#jrrny-edit-header-file").change(function (event) {
        event.preventDefault();
        $("#jrrny-edit-header-form").submit();
    });

    $("#jrrny-auhor-edit #avatar-input").change(function (event) {
        event.preventDefault();
        $('#jrrny-edit-avatar-form').submit();
    });

    $('#jrrny-edit-avatar-form').submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.avatar != '') {
                    $('img.avatar', $("#avatar-wrapper")).attr("src", response.avatar);
                    $('img.avatar', $("#profile-data-bar")).attr("src", response.avatar);
                    $('img.avatar', $(".user-widget")).attr("src", response.avatar);
                    $('img.avatar', $("#jrrny-edit-avatar-form")).attr("src", response.avatar);
                }
            },
            complete: function () {
                $("#jrrny-auhor-edit #avatar-input").val("");
            }
        });
    });

    //Follow click
    $('#jrrny-author-follow-btn').click(function (e) {
        var action = ($(this).hasClass('followed')) ? 'unfollow' : 'follow';
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                'user-id': $(this).data('user-id'),
                'action': action
            },
            success: function (response) {
                if (response.status == 'ok') {
                    if (action === 'unfollow') {
                        $('#jrrny-author-follow-btn').removeClass('followed');
                    } else {
                        $('#jrrny-author-follow-btn').addClass('followed');
                    }
                    $('#jrrny-author-follow-btn').html('<i class="fa fa-check"></i>&nbsp;following')
                }
            },
        });
        e.preventDefault();
    });


});