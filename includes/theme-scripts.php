<?php

// Note: TS_THEMENAME and TS_THEME_VERSION are defined in "functions.php"

function ts_theme_styles() 
{
    wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css', '', '4.3.0');
    
    wp_dequeue_style('rs-plugin-static');
    
    wp_enqueue_style( 'theme-css', get_stylesheet_uri(), '', TS_THEME_VERSION);
    
    if(ts_enable_style_selector()) :
        wp_enqueue_style( 'style-selector', get_template_directory_uri().'/css/style_selector.css', '', TS_THEME_VERSION);
    endif;
    
    $enable_inline_css = ts_option_vs_default('enable_inline_css', 0);
    if($enable_inline_css < 1) :
        $ts_php_css_ver = get_option(ts_slugify(TS_THEMENAME).'_activation_time');
        $ts_php_css_ver = ($ts_php_css_ver) ? '-'.$ts_php_css_ver : '';
        wp_enqueue_style( 'theme-options-css', esc_url(home_url().'?theme-options=css'), '', TS_THEME_VERSION.$ts_php_css_ver);
    endif;
    
    if(ts_option_vs_default('rtl', 0) == 1) :
        wp_enqueue_style( 'rtl', get_template_directory_uri().'/rtl.css', '', TS_THEME_VERSION);
    endif;
}


function ts_dequeue_woocommerce_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
    return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'ts_dequeue_woocommerce_styles' );

function ts_theme_scripts() 
{
    global $smof_data;
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'device', get_template_directory_uri().'/js/device.js', array('jquery'), '', true);
	wp_enqueue_script( 'superfish', get_template_directory_uri().'/js/superfish.js', array('jquery'), '', true);
	wp_enqueue_script( 'ts-plugins', get_template_directory_uri().'/js/plugins.js', array('jquery'), TS_THEME_VERSION, true);
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri().'/js/magnific-popup.js', array('jquery'), '', true);
	wp_enqueue_script( 'ts-init', get_stylesheet_directory_uri().'/js/init.js', array('jquery','ts-plugins'), TS_THEME_VERSION, true);
    wp_enqueue_script( 'wp-mediaelement', '', array('jquery'), '', true);
    if(ts_enable_style_selector()) :
        wp_enqueue_script( 'style-selector', get_template_directory_uri().'/js/style-selector.js', array('jquery','ts-plugins','ts-init'), TS_THEME_VERSION, true);
    endif;
    
    $params = array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('ts-ajax-nonce'),
    );
    
    wp_localize_script( 'jquery', 'ts_ajax_vars', $params );
}

if(!is_admin()) 
{
    add_action('wp_enqueue_scripts', 'ts_theme_styles');
    add_action('wp_enqueue_scripts', 'ts_theme_scripts');
}

if(is_admin()) 
{        
    add_action( 'admin_print_scripts-widgets.php', 'ts_enqueue_color_picker' );
    function ts_enqueue_color_picker( ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'ts-admin-scripts', get_template_directory_uri().'/js/admin-scripts.js', array( 'wp-color-picker' ), TS_THEME_VERSION, true );
    }
    
    add_action('init', 'ts_admin_css');
    function ts_admin_css() {
        $ts_php_css_ver = get_option(ts_slugify(TS_THEMENAME).'_activation_time');
        $ts_php_css_ver = ($ts_php_css_ver) ? '-'.$ts_php_css_ver : '';
        wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css', '', '4.3.0');
        wp_enqueue_style('ts_admin_css', get_template_directory_uri().'/css/admin-css.css', '', TS_THEME_VERSION.$ts_php_css_ver);
    }
    
    add_action('init', 'ts_admin_scripts');
    function ts_admin_scripts() {
        wp_enqueue_script( 'ts-admin-scripts', get_template_directory_uri().'/js/admin-scripts.js', array( 'jquery' ), TS_THEME_VERSION, true );
    }
}