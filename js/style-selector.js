(function($) {
    "use strict";
    
    var primary_color = $('#dev-style-div').attr('data-color');
    
    function ts_fix_section_margins() {  
        var ww = $('.wrap-inner').width();
        var w = $('#main-container').width();
        var margin = (ww - w) / 2;
        if(!$('#main').hasClass('fullwidth') && ($('#main').hasClass('no-sidebar')||$('#main').hasClass('sidebar-comments-right'))) {
            $('.ts-color-section-fullwidth').each(function() {
                    $(this).css('margin-left', '-'+margin+'px').css('margin-right', '-'+margin+'px');
            });
        }
    }
    
    function ts_toggle_style_selector() {
        if($('#ts-style-selector-wrap').hasClass('closed')) {
            $('#ts-style-selector-wrap').animate({left:'0px'}, function() {
                $(this).removeClass('closed').addClass('open');
            });
        } else {
            $('#ts-style-selector-wrap').animate({left:'-180px'}, function() {
                $(this).removeClass('open').addClass('closed');
            });
        }
    }
    function ts_set_demo_highlight(el) {
        var el = $(el);
        var color = el.attr('data-color');
        el.addClass('active').parent().find('a').not(el).removeClass('active');
        color = (color.substr(0,1) == '#') ? color : '#'+color;
        var ts_css = $('#dev-style-div').text();
        var re = new RegExp(primary_color,"gi");
        $('#dev-style-div,#dev-style').html(ts_css.replace(re, color));
        primary_color = color;
    }
    function ts_toggle_demo_skin_logo(skin) {
        var logo_src = $('.low-res-logo').attr('src');
        if(skin == 'dark') {
            $('.low-res-logo').attr('src', logo_src.replace('maddux-logo-dark', 'maddux-logo-white'));
        } else {
            $('.low-res-logo').attr('src', logo_src.replace('maddux-logo-white', 'maddux-logo-dark'));
        }
    }
    function ts_reset_styles() {
        
        var bg_classes = $('#dev-style-div').attr('data-bg-classes');
        var orig_color = $('#dev-style-div').attr('data-orig-color');
        orig_color = (orig_color.substr(0,1) == '#') ? orig_color.substr(1) : orig_color;
        //alert(orig_color);
        $('body').removeClass('shadow not-wall-to-wall has-bg-image '+bg_classes).addClass('wall-to-wall no-bg-image');
        var el = $('.ts-demo-test-color-'+orig_color.toLowerCase());
        ts_set_demo_highlight(el);
        $('#ts-style-selector-bg-options a').removeClass('active');
        $('#ts_style_skin').val('light');
        $('#dev-style-dark-skin').html('');
        ts_toggle_demo_skin_logo('light')
        ts_fix_section_margins();
    }
    function ts_toggle_demo_skin(skin) {
        ts_toggle_demo_skin_logo(skin);
        if(skin == 'dark') {
            jQuery.post(theme_directory_uri+'/css/style_selector.dark.css', function(data) {
                $('#dev-style-dark-skin').html(data);
            });
        } else {
            $('#dev-style-dark-skin').html('');
        }
    }
    
    function ts_get_cookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }
    
    function ts_user_is_new() {
        var ts_style_switcher_seen = ts_get_cookie("ts_style_switcher_seen");
        if (ts_style_switcher_seen != "") {
            // do nothing
        } else {
            document.cookie = "ts_style_switcher_seen=1"; // don't do all of this again
            ts_toggle_style_selector(); // open to let user know it exists
            setTimeout(function() {
                ts_toggle_style_selector(); // close so as not to become a hindrance
            }, 1000);
        }
    }
    
    ts_user_is_new();
    
    $('head').append('<style id="dev-style-dark-skin"></style>');
    $('head').append('<style id="dev-style"></style>');
    $('.ts-style-color-options a').each(function() {
        var color = $(this).attr('data-color');
        $(this).css({'background-color':color});
    });
    $('.ts-style-color-options a').on('click', function() {
        ts_set_demo_highlight($(this));
    });
    $('.ts-style-bg-options a').each(function() {
        var bg = $(this).attr('data-bg');
        $(this).addClass('bg_'+bg+'_thumb');
    });
    $('.ts-style-bg-options a').on('click', function() {
        var bg = $(this).attr('data-bg');
        var bg_classes = $('#dev-style-div').attr('data-bg-classes');
        $(this).addClass('active border-primary').parent().find('a').not($(this)).removeClass('active border-primary');
        $('body').removeClass('no-bg-image '+bg_classes).addClass('bg_'+bg+' shadow not-wall-to-wall has-bg-image').find('#top-small-bar .text-right').css({'width':'48%','float':'right'});
        /* that last bit is for ie10 */
    });  
    $('#ts_style_skin').on('change', function() {
        var skin = $('#ts_style_skin').val();
        ts_toggle_demo_skin(skin);
        ts_set_demo_highlight($('.ts-demo-test-color.active'));
    });
    $('#ts-style-selector-toggle').on('click', function() {
        ts_toggle_style_selector();
    });
    $('#ts-style-selector-reset-button').on('click', function() {
        ts_reset_styles();
    });
    
})(jQuery);