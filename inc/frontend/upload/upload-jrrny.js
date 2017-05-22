jQuery(document).ready(function ($) {
    var start_image_upload, responded_jrrny_id;
    var processing_spinner = " " + "<img src='" + defineURL("stylesheet_dir") + '/inc/images/processing-spinner.gif' + "'/>";

    Dropzone.autoDiscover = false;
    var jrrny_images_dropzone = new Dropzone("#jrrny-images-dropzone", {
        init: function () {
            $('body').append("<input type=\"hidden\" value=\"\" id=\"dz-addit-params\" />");
        },
        uploadMultiple: false,
        url: defineURL("stylesheet_dir") + "/jrrny-images-upload.php",
        dictDefaultMessage: "Drag and Drop Images of what you did",
        autoProcessQueue: true,
        parallelUploads: 1,
        acceptedFiles: "image/jpeg,image/jpg,image/png,image/gif",
        maxFilesize: 8,
        maxFiles: 12
    });

    var jrrny_himages_dropzone = new Dropzone("#jrrny-himages-dropzone", {
        url: defineURL("stylesheet_dir") + "/jrrny-himages-upload.php",
        uploadMultiple: false,
        dictDefaultMessage: "Drag and drop images of where you stayed / started",
        autoProcessQueue: true,
        parallelUploads: 1,
        acceptedFiles: "image/jpeg,image/jpg,image/png,image/gif",
        maxFilesize: 8,
        maxFiles: 2
    });

    jrrny_images_dropzone.on("error", function (file, errorMessage, xhr) {
        if (!xhr) {
            alertify.error(errorMessage, 5);
            var remove_file = setTimeout(function () {
                jrrny_images_dropzone.removeFile(file);
                clearTimeout(remove_file);
            }, 5000);
        }
    });

    jrrny_himages_dropzone.on("error", function (file, errorMessage, xhr) {
        if (!xhr) {
            alertify.error(errorMessage, 5);
            var remove_file = setTimeout(function () {
                jrrny_himages_dropzone.removeFile(file);
                clearTimeout(remove_file);
            }, 5000);
        }
    });
 /*
    jrrny_images_dropzone.on("queuecomplete", function (file) {
        
        var files = jrrny_images_dropzone.files;
        if(files.length > 0){
            files.each(function(e){
                var imageId = $(files[e]).data("image_id");
            alert(imageId);
            });
        }
        if($('#jrrny-images-dropzone > .dz-preview:first')){
           
           $('#jrrny-images-dropzone > .dz-preview:first').addClass('thumb-selected');
            $("#jrrny-images-dropzone").find('.dz-set-thumb').not($(this)).removeClass('thumb-selected');
            $('input#dz-addit-params').val(file.name);
        }
    });*/
    jrrny_images_dropzone.on("addedfile", function (file) {
        $("#jrrny-images-dropzone").sortable({
            items: "div.dz-image-preview",
            opacity: 0.7,
            revert: true,
            cursor: "move",
            tolerance: "pointer",
            distance: -120,
            delay: 10,
            zIndex: 9999,
            appendTo: document.body

        });
        $("#jrrny-images-dropzone").disableSelection();
        var dz_additional = "<div class=\"dz-additional\">";
        //dz_additional += "<i title=\"Set as thumbnail\" class=\"dz-set-thumb thumb-native dz-addit-elem\">Main Image</i>";
        dz_additional += "<i title=\"Remove\" class=\"fa fa-remove dz-remove-image dz-addit-elem\"></i>";
        dz_additional += "</div>";
        if (file.type.match(/image\/+(jpeg|jpg|png|gif)/ig)) {
            $(file.previewElement).append(dz_additional);
        }
        if ($(file.previewElement).find('.dz-additional').length) {
            var _this_dz_additional = $(file.previewElement).find('.dz-additional');
            _this_dz_additional.find('.dz-remove-image').click(function () {
                jrrny_images_dropzone.removeFile(file);
            });
            /*_this_dz_additional.find('.dz-set-thumb').click(function () {
                $(this).addClass('thumb-selected');
                $("#jrrny-images-dropzone").find('.dz-set-thumb').not($(this)).removeClass('thumb-selected');
                $('input#dz-addit-params').val(file.name);
            });*/
        }
        //Blocked sending form
        $('#journey-data-process').prop('disabled', true);
    });

    jrrny_himages_dropzone.on("addedfile", function (file) {
        $("#jrrny_himages_dropzone").disableSelection();
        var dz_additional = "<div class=\"dz-additional\">";
        dz_additional += "<i title=\"Remove\" class=\"fa fa-remove dz-remove-image dz-addit-elem\"></i>";
        dz_additional += "</div>";
        if (file.type.match(/image\/+(jpeg|jpg|png|gif)/ig)) {
            $(file.previewElement).append(dz_additional);
        }
        if ($(file.previewElement).find('.dz-additional').length) {
            var _this_dz_additional = $(file.previewElement).find('.dz-additional');
            _this_dz_additional.find('.dz-remove-image').click(function () {
                jrrny_himages_dropzone.removeFile(file);
            });
        }
        //Blocked sendig form
        $('#journey-data-process').prop('disabled', true);
    });

    var dzSetThumbClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        $('#jrrny-main-image-id').val(imageId);
    };
    var dzRemoveClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-remove-image.php",
            type: 'POST',
            data: {
                'image-id': imageId
            },
            success: function (response) {
                $('input#jrrny-image-' + imageId).remove();
            }
        });
    };
    jrrny_images_dropzone.on("success", function (file, responseText) {
        if (responseText.status === 'ok') {
            var imgId = responseText.img_id;
            $('#form-journey').append('<input id="jrrny-image-' + imgId + '" type="hidden" class="jrrny-upl-img" name="images[]" value="' + imgId + '" />');
            $(file.previewElement).data('image_id', imgId);
            //$(file.previewElement).find('.dz-set-thumb').click(dzSetThumbClick);
            $(file.previewElement).find('.dz-remove-image').click(dzRemoveClick);
            //Change src
            $(file.previewElement).find('img').attr('src', responseText.url);
            //Add first image as main
            if ($('#jrrny-main-image-id').val() == "") {
                $('#jrrny-main-image-id').val(imgId);
            }
        } else if (responseText.status === 'fail') {
            $(file.previewElement).find('.dz-additional .dz-set-thumb').html(responseText.msg);
        }
        //Unblock sending form
        $('#journey-data-process').prop('disabled', false);
    });
    jrrny_himages_dropzone.on("success", function (file, responseText) {
        if (responseText.status === 'ok') {
            var imageId = responseText.img_id;
            $('#form-journey').append('<input id="jrrny-himages-' + imageId + '" type="hidden" class="jrrny-upl-himg" name="imagesh[]" value="' + imageId + '" />');
            $(file.previewElement).data('image_id', imageId);
            $(file.previewElement).find('.dz-remove-image').click(dzRemoveClick);
            //Change src
            $(file.previewElement).find('img').attr('src', responseText.url);
        }else if (responseText.status === 'fail') {
            var html = '<i class="dz-set-thumb">' + responseText.msg + '</p>';
            $(file.previewElement).find('.dz-additional').prepend(html);
        }
        //Unblock sending form
        $('#journey-data-process').prop('disabled', false);
    });

    $("body").on("submit", "#form-journey", function (event) {
        event.preventDefault();
        
        if($('#wp-story-wrap').length){ 
            wp_story_editor = tinyMCE.activeEditor.getContent();
            $('#form-journey #story').val(wp_story_editor);
        }
        var validate = new Validate("#form-journey", ["#place-jrrny", "#activity-jrrny", "#hotel-name", "#story", '#jrrny-images-dropzone'], "div.form-group");
        var that = $(this);
        //Sort file on input
        var i = 0;
        var files = $(jrrny_images_dropzone.element).find('.dz-preview');
        if(files.length > 0){
            files.each(function(e){
                var imageId = $(files[e]).data("image_id");
                $('input#jrrny-image-' + imageId).attr('name', 'images[' + e + ']');
                if(i === 0 ){
                    $('#jrrny-main-image-id').val(imageId);
                }
                i++;
            });
        }
        var hfiles = $(jrrny_himages_dropzone.element).find('.dz-preview');
        if(hfiles.length > 0){
            hfiles.each(function(e){
                var himagesId = $(hfiles[e]).data("image_id");
                $('input#jrrny-himages-' + himagesId).attr('name', 'imagesh[' + e + ']');
            });
        }
        if (validate.validated()) {
            $.ajax({
                url: defineURL("stylesheet_dir") + "/create-jrrny.php",
                type: 'POST',
                data: $('#form-journey').serialize(),
                beforeSend: function () {
                    //Turn on processing
                    $("#journey-data-process .processing-icon").removeClass('hide fa-check').addClass('rotating fa-refresh');
                },
                success: function (response) {
                    $("#journey-data-process .processing-icon").removeClass('rotating fa-refresh').addClass('fa-check');
                    //Clear form
                    $("#form-journey").find("input[type=text], textarea").val("");
                    $("#form-journey").find('input[name="main-image-id"]').val("");
                    $("#form-journey").find('input.jrrny-upl-img').remove();
                    $("#form-journey").find('input.jrrny-upl-himg').remove();
                    jrrny_himages_dropzone.removeAllFiles();
                    jrrny_images_dropzone.removeAllFiles();
                    /*
                    ga('send', {
                        'hitType' : 'pageview',
                        'page' : '/new-jrrny-success',
                        'hitCallback': function() {
                            console.log('ga new-jrrny success');
                         }
                    });*/
                    
                    ga('send', 'event', 'Posts', 'Create new post', response.permalink);
                    ga('send', 'event', 'User', 'new jrrny', response.permalink);
                    ga('send', 'pageview', {
                        'page': '/new-jrrny-success',
                        'hitCallback': function() {
                            console.log('ga new-jrrny success');
                        }
                    });

                    var social = response.social;
                    var modal_body = $("#after-upload-modal").find(".modal-content .modal-body");
                    var modal_social = modal_body.find(".modal-social");
                    modal_body.find("#link-to-jrrny").attr("href", response.permalink);
                    for (var key in social) {
                        if (social.hasOwnProperty(key)) {
                            modal_social.find("." + key).find("a").attr("href", social[key]);
                        }
                    }
                    $("#after-upload-modal").modal();
                    
                    if ($('#wizzard').length) {
                        show_step_4();
                    }
                }
            });
        }
        else{
            jQuery('html, body').animate({
                scrollTop: jQuery(".err-message:first").offset().top-80
            }, 2000);
        }
    });


    $("body").on("click", "#journey-data-process", function (event) {
        event.preventDefault();
        $("#form-journey").submit();
    });

});