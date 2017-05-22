jQuery(document).ready(function($){
    jQuery('.color-picker-field').wpColorPicker();
    
    jQuery('#page_template').on('change', function() {
        if(jQuery(this).val() == 'home-default.php') {
            jQuery('#_page_slider_type').val('flex');
            jQuery('#_page_slider_source').val('blog');
            jQuery('#_page_slider_text_align').val('center');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('no');
        }
        else if(jQuery(this).val() == 'home-alt-1.php') {
            jQuery('#_page_slider_type').val('flex');
            jQuery('#_page_slider_source').val('blog');
            jQuery('#_page_slider_text_align').val('center');
            jQuery('#_page_slider_width').val('content');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('no');
        }
        else if(jQuery(this).val() == 'home-alt-2.php') {
            jQuery('#_page_slider_type').val('');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('no');
        }
        else if(jQuery(this).val() == 'home-alt-3.php') {
            jQuery('#_page_slider_type').val('');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('no');
        }
        else if(jQuery(this).val() == 'home-alt-4.php') {
            jQuery('#_page_slider_type').val('');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('no');
        }
        else if(jQuery(this).val() == 'home-alt-5.php') {
            jQuery('#_page_slider_type').val('');
            jQuery('#_page_titlebar').val('no');
            jQuery('#_page_sidebar').val('yes');
        }
    });
});