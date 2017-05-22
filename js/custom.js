var $ = jQuery.noConflict();
jQuery(document).ready(function () {
    jQuery("#commentform #button").on('click', function (e) {
        if (jQuery("#comment").val().length === 0) {
            //console.log(jQuery("#userName").html());
            jQuery("#commentUserName").html(jQuery("#userName").html());
            e.preventDefault();
        }
    });
    jQuery("#place-jrrny, #activity-jrrny, #hotel-name").blur(function () {
        if (jQuery("#place-jrrny").val().length !== 0 && jQuery("#activity-jrrny").val().length !== 0 && jQuery("#hotel-name").val().length !== 0) {
            jQuery("#journey-preview").removeAttr('disabled');
            jQuery("#journey-preview").on('click', function (i) {
                i.preventDefault();
        
                jQuery("#previewTitle").html(jQuery("#place-jrrny").val());
                jQuery("#whereStay").html(jQuery("#activity-jrrny").val());
                
                if(jQuery('#wp-story-wrap').length){ 
                    wp_story_editor = tinyMCE.activeEditor.getContent();
                    jQuery('#story').val(wp_story_editor);
                }
                jQuery("#previewStory").html(jQuery("#story").val());
                jQuery("#previewStay").html(jQuery("#hotel-name").val());
                jQuery("#previewHotelLink").html(jQuery("#hotel-link").val());
                
                //All images
                var tn_array = Array();
                jQuery('#jrrny-images-dropzone img').each(function () {
                    tn_array.push(jQuery(this).attr('src'));
                });
                var data = '';
                jQuery.each(tn_array, function (index, val) {
                    data = data + '<img src="' + val + '" alt="hoteld images"> ';
                    jQuery("#imagesForJrrny").html(data);
                });
                //End
                //Hotel image
                var imageForHotel = jQuery('#jrrny-himage-dropzone img').first().attr('src');
                var data = '<img src="' + imageForHotel + '" alt="hotel images"> ';
                jQuery('#imgForHotel').html(data);
                //END
                
            });

        }
        else {
            jQuery("#journey-preview").attr('disabled', 'disabled');
        }
        
        
        

    });
    //Getting Hotel Image

    jQuery("#hotel-featured-image").change(function () {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#imgForHotel').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    jQuery(".file-input-unset").click(function () {
        jQuery("#imgForHotel").attr('src', '');
    });
    //End


    jQuery("#jrrny-submit-preview").click(function(y){
        jQuery("#journey-data-process").click();
            setTimeout(function(){ 
                console.log("innnnn");
                jQuery("#previewModal .close").click();
            }, 1000);
        
        
        
    });

    if(jQuery('#beta-now').length) jQuery('#beta-now').modal('show');
    document.cookie="beta-now=true";

});



jQuery(document).ready(function () {
    jQuery("#journey-data-process").click(function () {
        if(jQuery('#wp-story-wrap').length){ 
            wp_story_editor = tinyMCE.activeEditor.getContent();
            jQuery('#story').val(wp_story_editor);
            
        }
        if (jQuery("#place-jrrny").val().length === 0) {
            jQuery("#place-jrrny").css("border", "1px solid red");
        }
        else {
            jQuery("#place-jrrny").css("border", "none");
        }

        if (jQuery("#activity-jrrny").val().length === 0) {
            jQuery("#activity-jrrny").css("border", "1px solid red");
        }
        else {
            jQuery("#activity-jrrny").css("border", "none");
        }

        if (jQuery("#hotel-name").val().length === 0) {
            jQuery("#hotel-name").css("border", "1px solid red");
        }
        else {
            jQuery("#hotel-name").css("border", "none");
        }
        if (jQuery("#story").val().length === 0) {
            jQuery("#story").css("border", "1px solid red");
            if(jQuery('#wp-story-wrap').length){ 
                $('#wp-story-wrap').css("border", "1px solid red");
            }
        }
        else {
            jQuery("#story").css("border", "none");
            if(jQuery('#wp-story-wrap').length){ 
                $('#wp-story-wrap').css("border", "none");
            }
        }
    });
});

