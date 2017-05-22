<?php
global $smof_data, $ts_options;

/* Add filters, actions, and theme-supported features. */
add_action( 'after_setup_theme', 'ts_theme_setup' );
function ts_theme_setup()
{
    global $smof_data, $content_width;

    /* Content Width */
    if(!isset($content_width) || (!$content_width)) $content_width = 1000;

    /* Add support for 'ThemeStockyard Essentials' */
    add_theme_support('ts-essentials');
    define('TS_ESSENTIALS_SUPPORTED_POSTTYPES', 'megamenu'); // separate with commas

    /* Thumbnails */
    if(function_exists('add_image_size'))
    {
        add_theme_support('post-thumbnails');
        add_image_size('thumb_1200', 1200, 0, false);
        add_image_size('thumb_1200x600', 1200, 600, true);
        add_image_size('thumb_740', 740, 0, false);
        add_image_size('thumb_740x480', 740, 480, true);
        add_image_size('thumb_510x340', 510, 340, true);
        add_image_size('thumb_480', 480, 0, false);
        add_image_size('thumb_480x320', 480, 320, true);
    }

    /* When an image is deleted, remove any additional thumbnails that were created by aq_resize function */
    add_filter('delete_attachment', 'ts_aq_resized_images_removal', 900);

    /* Increase JPG quality for resized images */
    if(ts_option_vs_default('use_custom_jpeg_compression', 1) == 1) {
        add_filter('jpeg_quality', 'ts_image_full_quality');
        add_filter('wp_editor_set_quality', 'ts_image_full_quality');
    }

    /* Sharpen resized images */
    if(ts_option_vs_default('sharpen_resized_images', 1) == 1) {
        add_filter('image_make_intermediate_size', 'ts_sharpen_resized_files',900);
    }

    /* Better SEO: page titles */
    add_filter( 'wp_title', 'ts_filter_wp_title', 10, 2 );

    /* Sidebars */
    add_action( 'widgets_init', 'ts_register_sidebars', 999 );

    /* Post formats */
    add_theme_support('post-formats', array('gallery', 'video', 'audio', 'link')); // need to support more here

    /* Add support for nav menus */
    add_theme_support( 'nav-menus' );
    register_nav_menus( array(
            'top_bar' => __( 'Top Bar: Small Navigation', 'ThemeStockyard'),
            'main_nav' => __( 'Main Navigation', 'ThemeStockyard'),
            'footer_nav' => __( 'Footer Navigation', 'ThemeStockyard'),
    ));

    /* Add custom classes to wp_nav_menu */
    add_filter('nav_menu_css_class', 'ts_add_class_to_wp_nav_menu');

    /* Enable shortcodes in excerpts and widgets */
    add_filter('the_excerpt', 'do_shortcode');
    add_filter('widget_text', 'do_shortcode');

    /* Properly format shortcodes (particularly block elements) */
    add_filter('the_content', 'ts_shortcodes_formatter');
    add_filter('widget_text', 'ts_shortcodes_formatter', 9);

    /* End truncated excerpts with "..." */
    add_filter('excerpt_more', 'ts_new_excerpt_more');

    /* Localization */
    if (function_exists('load_theme_textdomain')) {
        load_theme_textdomain( 'ThemeStockyard', get_stylesheet_directory() .'/languages' );
    }

    /* Add support for woocommerce */
    add_theme_support( 'woocommerce' );
    //define('WOOCOMMERCE_USE_CSS', false);
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 );

    /* Add custom CSS & JS (from Theme Options) to wp_head */
    add_action('wp_head', 'ts_wp_head_addons');

    /* Add custom JS & HTML (from Theme Options) to wp_footer */
    add_action('wp_footer', 'ts_wp_footer_addons', 999);

    /* Custom theme activation function */
    add_action('after_switch_theme', 'ts_activation_function');

    /* RSS feed link */
    add_theme_support( 'automatic-feed-links' );
    add_action( 'feed_link', 'ts_custom_rss_feed', 99, 2 );

    /* Wrap oEmbeds with responsive div */
    add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);

    /* Modify the search form */
    add_filter( 'get_search_form', 'ts_html5_search_form' );

    /* adding support for WP Reviews plugin */
    if ( function_exists( 'wp_review_theme_defaults' )) {
        $color_options = array(
            'colors' => array(
                'color'         => ts_option_vs_default('primary_color', '#ee4643'),
                'fontcolor'     => ts_option_vs_default('body_font_color', '#555'),
                'bgcolor1'      => ts_option_vs_default('subtle_bg_color', '#f5f5f5'),
                'bgcolor2'      => ts_option_vs_default('content_background_color', '#fff'),
                'bordercolor'   => ts_option_vs_default('standard_border_color', '#eee')
            )
        );
        wp_review_theme_defaults( $color_options );
    }

    /* ajax load mini woocommerce cart */
    add_action("wp_ajax_ts_reload_mini_cart", "ts_reload_mini_cart");
    add_action("wp_ajax_nopriv_ts_reload_mini_cart", "ts_reload_mini_cart");

    /* ajax load infinite blog */
    add_action("wp_ajax_ts_load_infinite_blog", "ts_load_infinite_blog");
    add_action("wp_ajax_nopriv_ts_load_infinite_blog", "ts_load_infinite_blog");

    /* ajax postview counter */
    add_action("wp_ajax_ts_update_postviews", "ts_update_postviews");
    add_action("wp_ajax_nopriv_ts_update_postviews", "ts_update_postviews");

    /* add post id column to wp-admin/posts.php */
    add_filter('manage_posts_columns', 'ts_posts_columns_id', 5);
    add_action('manage_posts_custom_column', 'ts_posts_custom_id_columns', 5, 2);

    /* add custom query vars */
    add_filter( 'query_vars', 'ts_add_query_vars_filter' );

    /* dynamic CSS from theme options */
    if( !function_exists( 'ts_style_options' )) {
        function ts_style_options($wp) {
            if (!empty($_GET['theme-options']) && $_GET['theme-options'] == 'css') {
                # get theme options
                header("Content-type: text/css; charset: UTF-8");
                header("Cache-Control: must-revalidate");
                $offset = 72000;
                $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
                header($ExpStr);
                echo ts_dynamic_css();
                exit;
            }
        }
        add_action('parse_request', 'ts_style_options');
    }

    /* smart date/time conversion (for localizing dates/times for posts) */
    add_filter('get_the_date', 'ts_smart_date', 10, 3);
    add_filter('get_the_time', 'ts_smart_time', 10, 3);

    /* smart permalink conversion (for "link" post formats - initialized via theme options) */
    add_filter('post_link', 'ts_smart_permalink', 10, 3);

    /* smart author display name conversion (for "link" post formats - initialized via theme options) */
    add_filter('get_the_author_display_name', 'ts_smart_author_display_name', 10, 2);

    /* smart author posts url conversion (for "link" post formats - initialized via theme options) */
    add_filter('author_link', 'ts_smart_author_posts_url', 10, 3);

    /* smart comments_open (for "link" post formats - initialized via theme options) */
    add_filter('comments_open', 'ts_smart_comments_open', 10, 2);

    /* smart title (for using an alternate title - via post meta options) */
    add_filter('the_title', 'ts_smart_title', 10, 2);

    /* setup global $ts_previous_posts var */
    add_action('wp_head', 'ts_setup_previous_post_cache', 99999);
}



// --------------  variables --------------
// Variables begin...
// --------------  variables --------------

global $ts_previous_posts;
global $ts_page_title, $ts_featured_post_ids, $ts_slider_post_ids, $ts_show_title_bar_override, $ts_caption, $ts_page_id;
global $ts_grab_home_sidebar, $ts_within_blog_loop, $ts_comments_top_padding, $ts_sidebar_position;

$ts_previous_posts = array();
$ts_page_title = '';
$ts_featured_post_ids = array();
$ts_slider_post_ids = array();
$ts_show_title_bar_override = '';
$ts_caption = '';
$ts_page_id = '';
$ts_grab_home_sidebar = false;
$ts_within_blog_loop = false;
$ts_comments_top_padding = true;
$ts_sidebar_position = 'right';

// --------------  functions --------------
// Functions begin...
// --------------  functions --------------

function ts_setup_previous_post_cache() {
    global $ts_previous_posts;

    $ts_previous_posts = array(); // yep, that's all folks
}

function ts_smart_title($title, $post_id = null)
{
    global $ts_page_id, $post;

    $post_id = (is_null($post_id)) ? ((is_object($post) && isset($post->ID)) ? $post->ID : $ts_page_id) : $post_id;

    if($ts_page_id != $post_id) :
        $alt_title = trim(ts_postmeta_vs_default($post_id, '_p_alt_title', ''));

        if($alt_title) :
            $title = $alt_title;
        endif;
    endif;

    return $title;
}

function ts_smart_comments_open($open, $post_id)
{
    global $post;

    if($open) :
        if(ts_option_vs_default('redirect_link_post_format', 1) == 1 && get_post_format($post_id) == 'link') :
            $open = false;
        endif;
    endif;

    return $open;
}

function ts_smart_permalink($permalink, $post, $leavename)
{
    global $post;

    if(ts_option_vs_default('redirect_link_post_format', 1) == 1) :
        if(is_object($post) && isset($post->ID)) :
            if(get_post_format($post->ID) == 'link') :
                $url = trim(ts_postmeta_vs_default($post->ID, '_p_link', ''));
                $permalink = ($url != '') ? esc_url($url) : $permalink;
            endif;
        endif;
    endif;
    return $permalink;
}

function ts_smart_author_display_name($value, $user_id)
{
    global $post;

    if(ts_option_vs_default('redirect_link_post_format', 1) == 1) :
        if(is_object($post) && isset($post->ID)) :
            if(get_post_format($post->ID) == 'link') :
                $url = trim(ts_postmeta_vs_default($post->ID, '_p_link', ''));
                $author = trim(ts_postmeta_vs_default($post->ID, '_p_link_author', ''));
                if($url && $author) $value = $author;
            endif;
        endif;
    endif;

    return $value;
}

function ts_smart_author_posts_url($link, $author_id, $author_nicename)
{
    global $post;

    if(ts_option_vs_default('redirect_link_post_format', 1) == 1) :
        if(is_object($post) && isset($post->ID)) :
            if(get_post_format($post->ID) == 'link') :
                $url = trim(ts_postmeta_vs_default($post->ID, '_p_link', ''));
                $author = trim(ts_postmeta_vs_default($post->ID, '_p_link_author', ''));
                if($url && $author) $link = $url;
            endif;
        endif;
    endif;

    return $link;
}

function ts_smart_date($date, $format = '', $post = null) {
    $date_parts = ($date) ? explode(' ', $date) : '';
    if(ts_option_vs_default('smart_datetime', 0) == 1) {
        $date_pref = ts_option_vs_default('smart_date_format', 'american');

        if($date_pref != 'american' && is_array($date_parts))
        {
            if($format == 'M j' || $format == 'F j') :
                $date = (is_numeric($date_parts[1])) ? $date_parts[1].' '.$date_parts[0] : $date;
            elseif($format == 'M j Y' || $format == 'M j, Y' || $format == 'F j Y' || $format == 'F j, Y') :
                $date = (is_numeric(trim($date_parts[1], ','))) ? trim($date_parts[1], ',').' '.$date_parts[0].' '.$date_parts[2] : $date;
            elseif($format == 'mm/dd/yyyy' || $format == 'm/d/yyyy') :
                $date_parts = explode('/', $date);
                $date = $date_parts[1].'/'.$date_parts[0].'/'.$date_parts[2];
            elseif($format == 'mm-dd-yyyy') :
                $date_parts = explode('-', $date);
                $date = $date_parts[1].'-'.$date_parts[0].'-'.$date_parts[2];
            endif;

            return $date;
        }
    }

    return $date;
}

function ts_smart_time($time, $format = '', $post = null) {
    if(ts_option_vs_default('smart_datetime', 0) == 1) {
        $time_pref = ts_option_vs_default('smart_time_format', '12hour');

        if($format == 'g:ia' || $format == 'g:i a' || $format == 'g:iA' || $format == 'g:i A' || $format == 'h:iA' || $format == 'h:i A')
        {
            $time = ($time_pref != '12hour') ? get_post_time('H:i', false, $post, true) : $time;
        }
        else // probably a "date"...
        {
            $time = (trim($format)) ? ts_smart_date($time, $format) : $time;
        }
    }

    return $time;
}

function ts_metaboxes_post_types($post_type = 'post')
{
    $the_post_types = ts_option_vs_default($post_type.'_metaboxes_on_these_post_types', '');
    $the_post_types = (is_array($the_post_types) && !empty($the_post_types)) ? $the_post_types : array();
    $the_post_types[] = $post_type;

    return $the_post_types;
}

function ts_wp_head_addons()
{
    global $smof_data;

    ts_custom_css();

    // load woo cart(s) via ajax (useful when pages have been cached)
    if(isset($smof_data['disable_woocommerce_checkout']) && $smof_data['disable_woocommerce_checkout'] == 1) {
        echo '<script type="text/javascript">';
        echo "jQuery(document).ready(function($){";
        echo "jQuery('form.checkout').attr('action','javascript:void(0)')";
        echo ".attr('name','checkout-disabled').attr('class','checkout-disabled')";
        echo ".on('submit', function() {";
        echo "alert('".__('Sorry, checkout is currently disabled.', 'ThemeStockyard')."');";
        echo "return false;";
        echo "});";
        echo "});";
        echo '</script>';
        echo "\n";
    }
}

function ts_wp_footer_addons()
{
    global $smof_data;

    // load woo cart(s) via ajax (useful when pages have been cached)
    if(isset($smof_data['enable_cart_ajax_loading']) && $smof_data['enable_cart_ajax_loading'] == 1) {
        echo '<script type="text/javascript">';
        echo "jQuery(window).load(function(){if(typeof ts_update_mini_cart=='function'){ts_update_mini_cart();}});";
        echo '</script>';
        echo "\n";
    }
}

/**
 * returns theme data in array or string
 */
function ts_theme_data($var = '')
{
    global $theme_data;

    if((is_array($theme_data) && count($theme_data) > 1) || (is_string($theme_data) && count($theme_data) > 1)) {
        return ($var && isset($theme_data[$var])) ? $theme_data[$var] : $theme_data;
    } else {
        $theme_data = array();
    }

    if( is_child_theme() ) {
        $temp_obj = wp_get_theme();
        $theme_obj = wp_get_theme( $temp_obj->get('Template') );
    } else {
        $theme_obj = wp_get_theme();
    }

    $theme_data['version'] = $theme_obj->get('Version');
    $theme_data['name'] = $theme_obj->get('Name');
    $theme_data['uri'] = $theme_obj->get('ThemeURI');
    $theme_data['author_uri'] = $theme_obj->get('AuthorURI');

    return ($var) ? $theme_data[$var] : $theme_data;
}
define('TS_THEME_VERSION', ts_theme_data('version'));
define('TS_THEMENAME', ts_theme_data('name'));
define('TS_THEMEURI', ts_theme_data('uri'));
define('TS_AUTHOR_URI', ts_theme_data('author_uri'));

// Wrap oEmbeds with responsive div
function my_embed_oembed_html($html, $url, $attr, $post_id)
{
    return '<p class="ts-wp-oembed fluid-width-video-wrapper">' . $html . '</p>';
}

// quick wrapper around balanceTags function
function ts_print($str = '', $balanceTags = false)
{
	return ($balanceTags) ? balanceTags($str) : $str;
}

// wrapper around escape functions
function ts_escape($str = '', $type = '', $context = '')
{
    if(trim($str))
    {
        if($type == 'strip')
        {
            if($context == 'widget_before_after')
            {
                return strip_tags($str, '<div><ul><li>');
            }
            elseif($context == 'widget_title_before_after')
            {
                return strip_tags($str, '<div><ul><li><h1><h2><h3><h4><h5><h6><strong><em><i><b><span>');
            }
            elseif(substr($context, 0, 1) == '<')
            {
                return strip_tags($str, $context);
            }
            else
            {
                return strip_tags($str);
            }
        }
        elseif($type == 'bal' || $type == 'balance')
        {
            return balanceTags($str);
        }
        elseif($type == 'attr')
        {
            return esc_attr($str);
        }
        elseif($type == 'html')
        {
            return esc_html($str);
        }
        elseif($type == 'url')
        {
            return esc_url($str);
        }
        elseif($type == 'js')
        {
            return esc_js($str);
        }
        elseif($type == 'textarea')
        {
            return esc_textarea($str);
        }
        elseif($type == 'sql')
        {
            return esc_sql($str);
        }
        elseif($type == 'post')
        {
            return wp_kses_post($str);
        }
    }

    return $str;
}

// Add post id column to wp-admin/posts.php (part 1)
function ts_posts_columns_id($defaults){
    $defaults['ts_post_id'] = 'ID';
    return $defaults;
}

// Add post id column to wp-admin/posts.php (part 2)
function ts_posts_custom_id_columns($column_name, $id){
    if($column_name === 'ts_post_id') {
        echo esc_attr($id);
    }
}

//
function ts_template_content()
{
    global $ts_template_content;

    if(trim($ts_template_content)) :
        $content = apply_filters('the_content', $ts_template_content);
        if(trim($content)) :
            echo ts_escape($content);
        endif;
    endif;
}

// Increase jpeg quality to 100 percent
function ts_image_full_quality($arg = '')
{
    $quality = ts_option_vs_default('jpeg_compression', 95);
    return $quality;
}

// replace the default posts feed with feedburner
function ts_custom_rss_feed( $output, $feed ) {
    global $smof_data;
    if ( strpos( $output, 'comments' ) )
        return $output;

    return (isset($smof_data['rss_url']) && trim($smof_data['rss_url'])) ? esc_url( $smof_data['rss_url'] ) : $output;
}

function ts_activation_function()
{
    update_option(ts_slugify(TS_THEMENAME).'_activation_time', time());
}

function generated_dynamic_sidebar($name = '0')
{
    if(class_exists('sidebar_generator'))
    {
        sidebar_generator::get_sidebar($name);
        return true;
	}
}

function ts_get_sidebar()
{
    global $ts_sidebar_position, $ts_page_id;
    if($ts_sidebar_position == 'left' || $ts_sidebar_position == 'right')
    {
        get_sidebar();
    }
}

function ts_get_content_sidebar()
{
    global $ts_sidebar_position;
    if($ts_sidebar_position == 'content-left' || $ts_sidebar_position == 'content-right')
    {
        get_sidebar();
    }
}

function ts_get_comments_sidebar()
{
    global $ts_sidebar_position;
    if($ts_sidebar_position == 'comments-left' || $ts_sidebar_position == 'comments-right')
    {
        get_sidebar();
    }
}

function ts_custom_css() {
    global $ts_custom_css, $ts_css_addon, $smof_data;
    $css  = '';
    $css .= (isset($smof_data['enable_inline_css']) && $smof_data['enable_inline_css'] == 1) ? ts_get_dynamic_css() : '';
    $css .= (isset($smof_data['custom_css'])) ? $smof_data['custom_css'] : '';
    $css .= (isset($ts_custom_css)) ? $ts_custom_css : '';
    $css .= (isset($ts_css_addon)) ? $ts_css_addon : '';
    echo (trim($css)) ? '<style type="text/css">'."\n".'/***********CUSTOM CSS************/'."\n".strip_tags($css)."\n".'</style>'."\n" : '';
}
function ts_get_dynamic_css() {
    ob_start();
    ts_dynamic_css();
    $css = ob_get_contents();
    ob_end_clean();
    return $css;
}
function ts_css_num($num, $allow_percent = false, $default = '')
{
    $num = trim(str_replace(' ', '', $num));
    if($allow_percent && strpos($num, '%') !== false) :
        $num = preg_replace("/[^0-9]*/", "", $num);
        $num = ($num == '') ? '' : $num.'%';
    else :
        $num = preg_replace("/[^0-9]*/", "", $num);
        $num = ($num == '') ? '' : $num.'px';
    endif;

    return ($num == '') ? $default : $num;
}

function ts_logo_alignment_class($default = '')
{
    $alignment = ts_option_vs_default('logo_alignment', $default);

    $alignment = (in_array($alignment, array('left','center','right'))) ? $alignment : (trim($default) ? $default : 'left');

    return esc_attr('text-'.$alignment);
}

function ts_theme_logo() {
    global $smof_data;

    $html = '';

    $logo_tag = (is_front_page()) ? 'h1' : 'div';

    $logo = ts_option_vs_default('logo_upload', '');
    $logo_text = ts_option_vs_default('logo_text', get_bloginfo('name'));

    if(!trim($logo)) :
        $html .= '<'.tag_escape($logo_tag).' class="logo-text"><a href="'.esc_url(home_url()).'" title="'.esc_attr(get_bloginfo('description')).'" class="text-logo">'.$logo_text.'</a></'.tag_escape($logo_tag).'>';
    else :
        $retina_logo = (isset($smof_data['retina_logo'])) ? $smof_data['retina_logo'] : '';

        $width = preg_replace("/[^0-9]*/", "", ts_css_num(ts_option_vs_default('retina_logo_width','')));
        $height = preg_replace("/[^0-9]*/", "", ts_css_num(ts_option_vs_default('retina_logo_height','')));

        $logo_dims = $retina_dims = '';

        $retina_dims .= ($width) ? 'width="'.esc_attr($width).'"' : '';
        $retina_dims .= ($height) ? 'height="'.esc_attr($height).'"' : '';

        $retina_class = ($height && $height > 30) ? 'retina-logo resize-sticky-retina-logo' : 'retina-logo';

        if(!trim($logo) && trim($retina_logo)) :
            $logo = $retina_logo;
            $retina_logo = '';
            $logo_dims = $retina_dims;
        endif;

        $html .= '<'.tag_escape($logo_tag).' class="logo-image"><a href="'.esc_url(home_url()).'" title="'.esc_attr(get_bloginfo('description')).'">';
        $html .= '<img src="'.esc_url($logo).'" alt="'.esc_attr(get_bloginfo('name')).'" class="low-res-logo" '.$logo_dims.'/>';
        $html .= ($retina_logo) ? '<img src="'.esc_url($retina_logo).'" alt="'.esc_attr(get_bloginfo('name')).'" class="'.esc_attr($retina_class).'" '.$retina_dims.'/>' : '';
        $html .= '</a></'.tag_escape($logo_tag).'>';
    endif;

    return $html;
}

function ts_theme_logo_tagline()
{
    $html = '';

    $tagline_alt = ts_option_vs_default('logo_tagline_text', '');

    if(trim($tagline_alt))
    {
        $html .= '<p id="logo-tagline" class="mimic-smaller uppercase">';
        $html .= do_shortcode($tagline_alt);
        $html .= '</p>';
    }

    return $html;
}

function ts_theme_top_stuff()
{
    $return = array();

    $left_side_of_header_content = ts_option_vs_default('left_side_of_header_content', 'social_icons');
    $right_side_of_header_content = ts_option_vs_default('right_side_of_header_content', 'shop_search');

    $left_html = $right_html = $html = $logo_class = '';

    // left side...
    if($left_side_of_header_content != 'nothing') :
        if($left_side_of_header_content == 'social_icons') :
            $html .= '<div class="header-social-icons">'.ts_small_nav_social().'</div>';
        elseif($left_side_of_header_content == 'shop_search') :
            $html .= '<div class="header-social-icons">'.ts_small_nav_shop_search().'</div>';
        elseif($left_side_of_header_content == 'left_alternative_field') :
            $html .= '<div class="header-alternative-field">';
            $html .= do_shortcode(ts_option_vs_default('alternative_to_social_icons_search', ''));
            $html .= '</div>';
        elseif($left_side_of_header_content == 'left_header_menu') :
            $left_menu = ts_option_vs_default('left_top_menu', '');
            $nav_menu_options = array(
                'menu' => $left_menu,
                'echo' => false,
                'container' => false,
                'depth' => -1,
                'items_wrap' => '%3$s'
            );

            $nav_menu = wp_nav_menu($nav_menu_options);
            $html .= '<div class="header-menu">'.$nav_menu.'</div>';
        endif;
        $left_html = $html;
    endif;

    $html = '';

    // right side
    if($right_side_of_header_content != 'nothing') :
        if($right_side_of_header_content == 'social_links') :
            $html .= '<div class="header-social-icons">'.ts_small_nav_social().'</div>';
        elseif($right_side_of_header_content == 'shop_search') :
            $html .= '<div class="header-social-icons">'.ts_small_nav_shop_search().'</div>';
        elseif($right_side_of_header_content == 'right_alternative_field') :
            $html .= '<div class="header-alternative-field">';
            $html .= do_shortcode(ts_option_vs_default('alternative_to_social_icons_search', ''));
            $html .= '</div>';
        elseif($right_side_of_header_content == 'right_header_menu') :
            $right_menu = ts_option_vs_default('right_top_menu', '');
            $nav_menu_options = array(
                'menu' => $right_menu,
                'echo' => false,
                'container' => false,
                'depth' => -1,
                'items_wrap' => '%3$s'
            );

            $nav_menu = wp_nav_menu($nav_menu_options);
            $html .= '<div class="header-menu">'.$nav_menu.'</div>';
        endif;
        $right_html = $html;
    endif;

    if($left_html || $right_html) :
        $return['left'] = '<div id="top-stuff-left" class="mimic-small uppercase top-stuff-side">'.$left_html.'</div>';
        $return['right'] = '<div id="top-stuff-right" class="mimic-small uppercase top-stuff-side">'.$right_html.'</div>';
        $return['logo_class'] = 'with-side-stuff';
    else :
        $return['left'] = '';
        $return['right'] = '';
        $return['logo_class'] = 'without-side-stuff';
    endif;

    return ts_array2object($return);
}

function ts_top_class()
{
    return 'header_holder';//(is_front_page()) ? 'ts-fade-in-from-top' : '';
}

/*
 * Outputs the Top Bar
 */
function ts_get_top_bar()
{
    global $ts_page_id;

    $show_top_bar = ts_option_vs_default('show_top_bar', 'yes');

    if(in_array($show_top_bar, array('yes','social'))) :

        $center_section = true;

        $top_bar_center_content = ts_option_vs_default('top_bar_center_content', 'alt_text');
        $top_bar_center_alt_content = do_shortcode(ts_option_vs_default('top_bar_center_alt_content', 'Lorem ipsum...'));

        if($top_bar_center_content == 'blank') :
            $center_content = '&nbsp;';
        elseif($top_bar_center_content == 'hidden') :
            $center_content = '';
            $center_section = false;
        elseif($top_bar_center_content == 'alt_text') :
            $center_content = $top_bar_center_alt_content;
        else :
            $nav_menu_options = array(
                'theme_location' => 'top_bar',
                'echo' => false,
                'container' => false,
                'menu_id' => 'top-bar-nav',
                'depth' => 3,
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>'
            );

            $center_content = wp_nav_menu($nav_menu_options);
        endif;
        global $current_user;
        wp_get_current_user();
        $center_content = (trim($center_content)) ? $center_content : '&nbsp;';

        $top_bar_wrap_class = ($center_section) ? 'three-part top-header' : 'two-part';

        $html  = '<div id="top-bar-wrap" class="'.esc_attr($top_bar_wrap_class).'">';
        $html .= '<div id="top-bar" class="mimic-small container clearfix">'."\n";
        // $html .= '<div class="text-right right-side side search-area">'.ts_top_search().'</div>'."\n";
        $html .= "<div class=\"middle-area\">";
        $html .= "<a href='".home_url()."/upload' class=\"top-bar-ma-lnk\"><i class=\"fa fa-plus-circle\"></i> Create Jrrny</a>";
        $html .= (!is_user_logged_in()) ? "" : "<a href='". get_author_posts_url($current_user->ID) ."' class=\"top-bar-ma-lnk\"><i class=\"fa fa-briefcase\"></i> My Jrrnys</a>";
        $html .= "<a class=\"top-bar-ma-lnk\" id=\"login-out-lnk\">".(is_user_logged_in() ? "<i class=\"fa fa-sign-out\"></i> Log Out" : "<i class=\"fa fa-sign-in\"></i> Login")."</a>";
        $html .= (is_user_logged_in() ? "" : "<a class=\"top-bar-ma-lnk\" href='".home_url()."/register'><i class=\"fa fa-sign-in\"></i> Join")."</a>";

        $html .= "</div>";
        $html .= '<div class="left-side side header-social-icons">'.(is_user_logged_in() ?  '<p class="hello_username">Hello, '. $current_user->user_login : "" .'</p>').ts_small_nav_social().'</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    endif;
}
function newNav(){
        $current_user = (is_user_logged_in()) ? wp_get_current_user() : null;
        $html = '';
        $html .= "<div class=\"nav-holder\">";
        $html .= "<a href='".home_url()."/upload' class=\"top-bar-ma-lnk".(is_user_logged_in() ? "" : " login_modal")."\">Create Jrrny</a>";
        $html .= (!is_user_logged_in()) ? "" : "<a href='". get_author_posts_url($current_user->ID) ."' class=\"top-bar-ma-lnk\"> My Jrrnys</a>";
        $html .= "<a href='".home_url()."/trending' class=\"top-bar-ma-lnk\"> Trending</a>";
        //$html .= "<a href='".home_url()."/about' class=\"top-bar-ma-lnk\"> About</a>";
        $html .= "<a href='".home_url()."/collection' class=\"top-bar-ma-lnk\"> Collections</a>";
        $html .= "<a class=\"top-bar-ma-lnk".(is_user_logged_in() ? "" : " login_modal")."\" id=\"login-out-lnk\">".(is_user_logged_in() ? " Log Out" : " Login")."</a>";
        $html .= (is_user_logged_in() ? "" : "<a id=\"signup-navbar-link\" class=\"top-bar-ma-lnk\" href='".home_url()."/register'> Join")."</a>";
        $html .= '</div>';
    return $html;
}
function plc_nav($side = 'left'){
        $current_user = (is_user_logged_in()) ? wp_get_current_user() : null;
        $html = '';
       
        if($side === 'left'){
            $html .= '<li><a href="' . home_url() . '/upload"' . (is_user_logged_in() ? '' : ' class="login_modal"') . '>Create Jrrny</a></li>';
            $html .= '<li><a href="' . home_url() . '/trending">Trending</a></li>';
            $html .= '<li><a href="' . home_url() . '/collection">Collections</a></li>';
           /* $user_invitation = get_user_meta($current_user->ID, '_user_invitation');
            if(!empty($user_invitation)){                
                $html .= '<li class="contribute"><a href="' . home_url() . '/contribute">Contribute</a></li>';
            }
            * 
            */
        }
        else{       
            if(is_user_logged_in()){
                $html .= '<li><a href="' . home_url() . '/register" id="login-out-lnk" >Log Out</a></li>';
            }
            else{
                $html .= '<li><a href="' . home_url() . '/register" class="login_modal">Log In</a></li>';
                $html .= '<li><a href="' . home_url() . '/register">Sign up</a></li>';
            }
        }
    return $html;
}
/*
 * Outputs the Top News Ticker
 */
function ts_get_ticker($location = 'above-footer')
{
    return; // not quite ready (performance issues).
    $location_option = ts_option_vs_default('ticker_location', 'above-footer');

    if($location == $location_option) :
        $ticker_limit = ts_option_vs_default('ticker_limit', 6);

        ob_start();
        ts_blog('ticker', array('exclude_previous_posts'=>'0','exclude_these_later'=>'0','limit'=>$ticker_limit));
        $ticker = ob_get_contents();
        ob_end_clean();

        $html = '<div id="ts-news-ticker-wrap"><div id="ts-news-ticker-inner" class="flexslider container">'.$ticker.'</div></div>';

        return $html;
    endif;

    return;
}

function ts_top_ticker_label()
{
    $label = ts_option_vs_default('top_ticker_label_text', 'Trending:');
    return (trim($label)) ? '<strong class="uppercase primary-color">'.$label.'</strong> ' : '';
}
function ts_top_search( $form = '' ) {
    global $smof_data;
    $html = '';
    
    if(is_page(533)){
        $html .= '<p class="title">Discover new experiences</p>';
        $html .= '<form role="search" method="get" class="new-search-form" action="' . esc_url(home_url( '/' )) . '" >';
            $html .= '<div class="input-group input-group-lg">';
                $html .= '<span class="input-group-addon"><i class="fa fa-search"></i></span>';
                $html .= '<input type="text" class="form-control" placeholder="Where are you going / have you been?" value="' . esc_attr(get_search_query()) . '" name="s">';
                $html .= '<span class="input-group-btn"><button type="submit" class="btn btn-red" type="button">Search</button></span>';
            $html .= '</div>';
        $html .= '</form>';
        $html .= '<ul class="nav-search">';
            $html .= '<li role="presentation"><a href="#" class="takeTour">Take a quick Tour</a></li>';
            $html .= '<li role="presentation"><a href="' . home_url() . '/upload">Add yout first post</a></li>';
            $html .= '<li role="presentation"><a href="' . home_url() . '/trending">View what\'s trending</a></li>';
        $html .= '</ul>'; 
    }
    else{
        $html .= '<div class="top-stuff-link-item ts-hover-menu-wrap">';
        $html .= '<div class="search-pocket">'.ts_html5_search_form().'</div>';
        $html .= '</div>';        
    }

    return $html;
}
function ts_top_search_old()
{
    $html = '';
    /*
    $search_link  = '<div class="top-stuff-link-item ts-hover-menu-wrap">';
    $search_link .= '<a href="javascript:void(0)" class="top-stuff-search-link">';
    $search_link .= '<i class="fa fa-search"></i> ';
    $search_link .= '<span class="top-stuff-search-link-text">'.__('Search', 'ThemeStockyard').'</span>';
    $search_link .= '</a>';
    $search_link .= '<div class="search-pocket-wrap ts-hover-menu"><div class="search-pocket">'.ts_html5_search_form().'</div></div>';
    $search_link .= '</div>';
    $html .= $search_link;
    */

    $html .= '<div class="top-stuff-link-item ts-hover-menu-wrap">';
    $html .= '<div class="search-pocket">'.ts_html5_search_form().'</div>';
    $html .= '</div>';

    return $html;
}

function ts_sidebar_width($sidebar_width = '310px', $return = 'sidebar', $percent_sign = true)
{
    global $content_width;

    if(!isset($ts_sidebar_width) || !isset($ts_main_width) || !isset($ts_sidebar_margin_width))
    {
        global $ts_sidebar_width, $ts_main_width, $ts_sidebar_margin_width;

        $sidebar_width = preg_replace("/[^0-9]*/", "", ts_css_num($sidebar_width));
        $sidebar_width = (ts_number_within_range($sidebar_width, 100, 600)) ? $sidebar_width : 310;
        $sidebar_width_percent = $sidebar_width / $content_width;

        $ts_sidebar_width = round($sidebar_width_percent, 6) * 100;

        $ts_sidebar_margin_width = 3.1915; // always stays the same (for now at least)

        $ts_main_width = 100 - $ts_sidebar_width - $ts_sidebar_margin_width;
    }

    $percent = ($percent_sign) ? '%' : '';

    if($return == 'sidebar')
        return $ts_sidebar_width . $percent;
    elseif($return == 'main')
        return $ts_main_width . $percent;
    elseif($return == 'margin')
        return $ts_sidebar_margin_width . $percent;
    else
        return array('sidebar' => $ts_sidebar_width.$percent, 'margin' => $ts_sidebar_margin_width.$percent, 'main' => $ts_main_width . $percent);
}

function ts_html_class() {
    $class = array();
    if(ts_option_vs_default('use_smooth_scroll', 0) == 1) {
        $class[] = 'nicescroll';
    }
    return implode(' ', $class);
}

function ts_fade_in_class($arg = '')
{
    $arg = (in_array($arg, array('top','right','bottom','left','above','below'))) ? $arg : '';
    $arg = ($arg == 'below') ? 'bottom' : $arg;
    $arg = ($arg == 'above') ? 'top' : $arg;
    return ($arg) ? 'ts-fade-in-from-'.$arg : 'ts-fade-in';
}

function ts_disqus_active() {
    global $smof_data;
    return (isset($smof_data['use_disqus']) && $smof_data['use_disqus'] && trim($smof_data['disqus_shortname'])) ? true : false;
}

function ts_link2comments($permalink = '') {
    global $smof_data;
    if(isset($smof_data['use_disqus']) && $smof_data['use_disqus'] && trim($smof_data['disqus_shortname'])) :
        return $permalink . '#comments';//$permalink . '#disqus_thread';
    elseif(function_exists('is_woocommerce') && is_woocommerce()) :
        return $permalink . '#tab-reviews';
    else :
        return $permalink . '#comments';
    endif;
}

function ts_new_excerpt_more( $more ) {
	return '...';
}

// grab google fonts (updated)
function ts_grab_google_fonts($echo = false)
{
    global $ts_standard_fonts;

    // build global var (array) to only be used and taking up memory when needed
    ts_standard_fonts();

    $fonts = array();
    $output = '';


    $theme_options_fonts = array();
    $theme_options_fonts[] = ts_option_vs_default_font('logo_font_family', 'Montserrat');
    $theme_options_fonts[] = ts_option_vs_default_font('body_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('h1_font_family', 'Montserrat');
    $theme_options_fonts[] = ts_option_vs_default_font('h2_font_family', 'Montserrat');
    $theme_options_fonts[] = ts_option_vs_default_font('h3_font_family', 'Montserrat');
    $theme_options_fonts[] = ts_option_vs_default_font('h4_font_family', 'Montserrat');
    $theme_options_fonts[] = ts_option_vs_default_font('h5_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('h6_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('stylized_meta_font_family', 'Libre Baskerville');
    $theme_options_fonts[] = ts_option_vs_default_font('form_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('button_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('small_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('main_nav_font_family', 'Open Sans');
    $theme_options_fonts[] = ts_option_vs_default_font('main_nav_submenu_font', 'Open Sans');

    if(is_array($theme_options_fonts))
    {
        foreach($theme_options_fonts AS $item)
        {
            if(trim($item) && !in_array($item, $ts_standard_fonts))
                $fonts[] = $item;
        }

        $fonts = array_filter(array_unique($fonts));

        foreach($fonts AS $font)
        {
            $url  = '';
            $url .= '//fonts.googleapis.com/css?family='.urlencode($font).':400,400italic,700,700italic&amp;';
            $url .= 'subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese';

            if($echo)
            {
                echo '<link href="'.esc_url($url).'" rel="stylesheet" type="text/css" />'."\n";
            }
            else
            {
                $output .= '<link href="'.esc_url($url).'" rel="stylesheet" type="text/css" />'."\n";
            }
        }

        if(!$echo) return $output;
    }
}

// Return the font-style(s) from the theme admin options
function aq_font_style($arg = null, $important = false)
{
    $important = ($important) ? '!important' : '';

    if(!is_null($arg))
    {
        $arg = strtolower($arg);
        $args = explode(" ", $arg);
        if(count($args) == 1)
        {
            if($arg == 'normal') return 'font-style: normal '.esc_attr($important).'; font-weight: normal '.esc_attr($important).';';
            if($arg == 'italic') return 'font-style: italic '.esc_attr($important).'; font-weight: normal '.esc_attr($important).';';
            if($arg == 'bold') return 'font-style: normal '.esc_attr($important).'; font-weight: bold '.esc_attr($important).';';
        }
        else
            return 'font-style: italic '.esc_attr($important).'; font-weight: bold '.esc_attr($important).';';
    }
}

function ts_blog_loop($layout = '', $atts = array())
{
    global $paged, $ts_query, $smof_data, $ts_show_sidebar, $wp_query, $ts_previous_posts;

    $default_layout = ts_option_vs_default('blog_layout', '1column');

    $orig_layout = trim(strtolower($layout));
    $layout = ($layout) ? preg_replace("/[^0-9a-z]*/", "", strtolower($layout)) : $default_layout;
    $test_layout = ($orig_layout) ? '-'.$orig_layout : '';

    if($layout == 'banner' || $layout == '2columnbanner') :
        $atts['meta_query'] = array(
            'relation'=>'OR',
            array('key'=>'_thumbnail_id'),
            array('key'=>'_p_preview_image_id')
        );
    endif;

    if($layout == 'banner' && isset($atts['columns']) && intval($atts['columns']) > 1) :
        $layout = '2columnbanner';
        $test_layout = '2-column-banner';
    endif;
    
    if(trim($test_layout) && file_exists(TS_SERVER_PATH . '/includes/_loop/loop'.$test_layout.'.php')) :
        include(locate_template('includes/_loop/loop'.$test_layout.'.php'));
    elseif(in_array($layout, array('masonry', 'grid', 'masonrygrid', 'masonry2columns', 'masonry3columns'))) :
        include(locate_template('includes/_loop/loop-masonry.php'));
    elseif(in_array($layout, array('cards', 'gridcards', 'cardgrid', 'masonrycards', 'masonrycards2columns', 'masonrycards3columns'))) :
        include(locate_template('includes/_loop/loop-masonry-cards.php'));
    elseif(in_array($layout, array('1column'))) :
        include(locate_template('includes/_loop/loop-1-column.php'));
    elseif(in_array($layout, array('2column', '2columns', '2columngrid'))) :
        include(locate_template('includes/_loop/loop-2-column.php'));
    elseif(in_array($layout, array('3column', '3columns', '3columngrid'))) :
        include(locate_template('includes/_loop/loop-3-column.php'));
    elseif(in_array($layout, array('3columncarousel'))) :
        include(locate_template('includes/_loop/loop-3-column-carousel.php'));
    elseif(in_array($layout, array('4column', '4columns', '4columngrid'))) :
        include(locate_template('includes/_loop/loop-4-column.php'));
    elseif(in_array($layout, array('mediumimage', 'medium', 'mediumimages', 'horizontal', 'horizontalrows', 'list'))) :
        include(locate_template('includes/_loop/loop-medium-image.php'));
    elseif(in_array($layout, array('thumbnail'))) :
        include(locate_template('includes/_loop/loop-thumbnail.php'));
    elseif(in_array($layout, array('thumbnailAjax', 'thumbnailajax'))) :
        include(locate_template('includes/_loop/loop-thumbnailAjax.php'));
    elseif(in_array($layout, array('legacy', 'traditional'))) :
        include(locate_template('includes/_loop/loop-thumbnail.php'));
    elseif(in_array($layout, array('2columnbanner', '2columnbanners'))) :
        include(locate_template('includes/_loop/loop-2-column-banner.php'));
    else :
        include(locate_template('includes/_loop/loop.php'));
    endif;
}

function ts_portfolio_loop($layout = '', $atts = '')
{
    global $paged, $ts_query, $smof_data, $ts_show_sidebar, $wp_query;

    $default_layout = ts_option_vs_default('portfolio_loop_layout', '3column');

    $orig_layout = trim(strtolower($layout));
    $layout = ($layout) ? preg_replace("/[^0-9a-z]*/", "", strtolower($layout)) : $default_layout;
    $test_layout = ($orig_layout) ? '-'.$orig_layout : '';

    if(trim($test_layout) && file_exists(TS_SERVER_PATH . '/includes/_loop/loop'.$test_layout.'.php')) :
        include(locate_template('includes/_loop/loop'.$test_layout.'.php'));
    elseif(in_array($layout, array('masonry', 'grid', 'masonrygrid'))) :
        include(locate_template('includes/_loop-portfolio/loop-masonry.php'));
    elseif(in_array($layout, array('cards', 'gridcards', 'cardgrid', 'masonrycards'))) :
        include(locate_template('includes/_loop-portfolio/loop-masonry-cards.php'));
    elseif(in_array($layout, array('4column', '4columns'))) :
        include(locate_template('includes/_loop-portfolio/loop-4-column.php'));
    elseif(in_array($layout, array('2column', '2columns'))) :
        include(locate_template('includes/_loop-portfolio/loop-2-column.php'));
    elseif(in_array($layout, array('mini', 'widget'))) :
        include(locate_template('includes/_loop-portfolio/loop-mini.php'));
    else :
        include(locate_template('includes/_loop-portfolio/loop-3-column.php'));
    endif;
}

function ts_portfolio()
{
    $args = func_get_args();

    if(func_num_args() == 2)
    {
        $atts = (is_array($args[0])) ? $args[0] : $args[1];
        $layout = (is_string($args[0])) ? $args[0] : $args[1];
    }
    else
    {
        $atts = (isset($args[0]) && is_array($args[0])) ? $args[0] : array();
        $layout = (isset($args[0]) && is_string($args[0])) ? $args[0] : '';
    }

    $atts['post_type'] = 'portfolio';

    ts_blog($layout, $atts);
}

function ts_blog()
{
    $args = func_get_args();

    if(func_num_args() == 2)
    {
        $atts = (is_array($args[0])) ? $args[0] : $args[1];
        $layout = (is_string($args[0])) ? $args[0] : $args[1];
    }
    else
    {
        $atts = (isset($args[0]) && is_array($args[0])) ? $args[0] : array();
        $layout = (isset($args[0]) && is_string($args[0])) ? $args[0] : '';
    }

    global $ts_previous_posts;
    global $post, $paged, $ts_query_original_atts, $ts_query, $smof_data, $ts_show_sidebar, $wp_query, $ts_slider_post_ids, $ts_within_blog_loop;

    $original_atts = $atts;
    $original_atts['pid'] = $atts_pid = (isset($atts['pid'])) ? $atts['pid'] : ((is_object($post) && isset($post)) ? $post->ID : '');

    $ts_within_blog_loop = 'yes';
    $post_type = (isset($atts['post_type'])) ? $atts['post_type'] : 'post';
    $sharing_options_var = ($post_type == 'portfolio') ? 'show_sharing_options_on_portfolio' : 'show_sharing_options_on_blog';

    if((is_front_page() || is_home())) :
		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	else :
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	endif;

	$paged = (isset($atts['paged'])) ? $atts['paged'] : $paged;

	$show_sharing_options = (ts_option_vs_default($sharing_options_var, 1) == 1) ? 'true' : 'false';
	if(isset($atts['show_sharing_options'])) :
        $show_sharing_options = (in_array($atts['show_sharing_options'], array('false','no','0'))) ? 'false' : 'true';
	endif;

    $title_align = (isset($atts['title_align']) && in_array($atts['title_align'], array('right','center','left'))) ? 'text-'.$atts['title_align'] : '';
    $meta_align = (isset($atts['meta_align']) && in_array($atts['meta_align'], array('right','center','left'))) ? 'text-'.$atts['meta_align'] : '';
    $excerpt_align = (isset($atts['excerpt_align']) && in_array($atts['excerpt_align'], array('right','center','left'))) ? 'text-'.$atts['excerpt_align'] : '';
    $read_more_align = (isset($atts['read_more_align']) && in_array($atts['read_more_align'], array('right','center','left'))) ? 'text-'.$atts['read_more_align'] : '';
    $posts_per_page = (isset($atts['posts_per_page'])) ? $atts['posts_per_page'] : ((isset($atts['limit']) ? $atts['limit'] : get_option('posts_per_page')));

	if($post_type == 'portfolio') :
        $nopaging = (isset($atts['nopaging']) && in_array($atts['nopaging'], array('false','no','0'))) ? false : true;
        $show_pagination = (isset($atts['show_pagination']) && in_array($atts['show_pagination'], array('true','yes','1'))) ? true : false;
        $posts_per_page = (isset($atts['posts_per_page'])) ? $atts['posts_per_page'] : (isset($atts['limit']) ? $atts['limit'] : -1);
        $posts_per_page = (is_numeric($posts_per_page) && $posts_per_page > 0) ? $posts_per_page : -1;
        $nopaging = (is_numeric($posts_per_page) && $posts_per_page > 0) ? false : $nopaging;
    else :
        $nopaging = (isset($atts['nopaging']) && in_array($atts['nopaging'], array('true','yes','1'))) ? true : false;
        $show_pagination = (isset($atts['show_pagination']) && in_array($atts['show_pagination'], array('false','no','0'))) ? false : true;
        $show_pagination = (isset($atts['infinite_scroll']) && in_array($atts['infinite_scroll'], array('true','yes','1','yes_button'))) ? true : $show_pagination;
        $posts_per_page = (isset($atts['posts_per_page'])) ? $atts['posts_per_page'] : ((isset($atts['limit']) ? $atts['limit'] : get_option('posts_per_page')));
        $posts_per_page = (in_array($posts_per_page, array('unlimited','no-limit', 'no limit', 'nolimit','none'))) ? -1 : $posts_per_page;
    endif;


    $atts = shortcode_atts( array(
            'meta_query'          => '',
            'author'              => '',
            'author_name'		  => '',
            'category_name'       => '',
            'cat'                 => '',
            'category'      	  => '',
            'category__in'        => '',
            'category__not_in'    => '',
            'exclude_categories'  => '',
            'id'                  => false,
            'p'					  => false,
            'post__in'			  => false,
            'post__not_in'		  => false,
            'show_filter'         => 'true',
            'align_filter'        => 'center',
            'include'             => '',
            'exclude'             => '',
            'nopaging'			  => false,
            'no_results'          => '',
            'hide_enlarge'        => (ts_option_vs_default('show_image_enlarge_button_on_blog', 1) == 1) ? 'false' : 'true',
            'is_search'           => false,
            'show_pagination'     => ($post_type == 'portfolio') ? false : $show_pagination,
            'posts_per_page'      => get_option('posts_per_page'),
            'offset'              => '',
            'order'               => 'DESC',
            'orderby'             => 'date',
            'post_status'         => 'publish',
            'post_type'           => 'post',
            'paged'				  => $paged,
            'tag'                 => '',
            'tag__in'             => '',
            'tax_operator'        => 'IN',
            'tax_term'            => false,
            'tax_query'           => '',
            'taxonomy'            => 'category',
            'display_taxonomy'    => '',
            'term'                => '',
            'title_size'          => '',
            'title_align'         => '',
            'meta_align'          => '',
            'excerpt_align'       => '',
            'read_more_align'     => '',
            'text_align'          => '',
            'text_position'       => '',
            'show_media'          => '',
            'show_category'       => '',
            'show_meta'           => '',
            'show_excerpt'        => '',
            'show_first_excerpt'  => '',
            'show_read_more'      => '',
            'show_sharing_options'=> true,
            'show_title'          => '',
            'show_author_avatar'  => (ts_option_vs_default('show_author_avatar', 0) == 1) ? 'true' : 'false',
            'excerpt_length'      => '',
            'html_in_excerpt'     => 'none',
            'allow_videos'        => true,
            'allow_galleries'     => true,
            'default_query'       => false,
            'fade_in_from'        => '',
            'media_width'         => false,
            'media_height'        => false,
            'must_have_media'     => false,
            'slider_type'         => 'flexslider',
            'exclude_previous_posts' => 1,
            'exclude_these_later' => 1,
            'within_mega_menu'    => 0,
            'layout'              => $layout,
            'image_size'          => '',
            'image_orientation'   => '',
            'widget_heading'      => '',
            'widget_heading_size' => '',
            'widget_layout'       => '',
            'override_widget_heading' => '',
            'infinite_scroll'     => '',
            'infinite_scroll_limit' => '',
            'infinite_scroll_button_text' => ts_option_vs_default('infinite_scroll_button_text', 'Load more posts'),
            'infinite_scroll_button_color' => '',
            'called_via'          => '',
            'columns'             => '',
            'fullwidth'           => '',
            'ignore_sticky_posts' => '',
            'lang' => (function_exists('pll_current_language')) ? pll_current_language() : '',
        ), $atts);

    $post__in = (trim($atts['include'])) ? $atts['include'] : $atts['post__in'];
    $atts['post__in'] = (is_array($post__in)) ? $post__in : ((is_string($post__in)) ? explode(',', $post__in) : false);

    $post__not_in = (trim($atts['exclude'])) ? $atts['exclude'] : $atts['post__not_in'];
    $atts['post__not_in'] = (is_array($post__not_in)) ? $post__not_in : ((is_string($post__not_in)) ? explode(',', $post__not_in) : false);
    $atts['post__not_in'] = (is_array($atts['post__not_in'])) ? $atts['post__not_in'] : array();
    if(is_array($atts['post__not_in']) && in_array('slider', $atts['post__not_in'])) :
        $exclude_slider = array('slider');
        $atts['post__not_in'] = array_diff($atts['post__not_in'], $exclude_slider);
        if(is_array($ts_slider_post_ids))
            $atts['post__not_in'] = array_merge($atts['post__not_in'], $ts_slider_post_ids);
    endif;

    $excluded_cats = ts_option_vs_default('excluded_blog_loop_categories');
    if($atts['exclude_categories'] != '0') :
        $excluded_cats = (trim($atts['exclude_categories'])) ? $atts['exclude_categories'] : $excluded_cats;
        $excluded_cats = (is_string($excluded_cats) && trim($excluded_cats)) ? explode(',', $excluded_cats) : $excluded_cats;
        $atts['category__not_in'] = (is_array($excluded_cats) && count($excluded_cats) > 0) ? $excluded_cats : $atts['category__not_in'];
        $atts['category__not_in'] = (is_array($atts['category__not_in']) && $atts['is_search'] === false) ? $atts['category__not_in'] : array();

        $cats = (is_array($atts['cat'])) ? $atts['cat'] : (trim($atts['cat']) ? explode(',', $atts['cat']) : array());

        $atts['category__not_in'] = array_diff($atts['category__not_in'], $cats);
    endif;

    if(!in_array($atts['exclude_previous_posts'], array('0','false','no')) && !$atts['default_query']) :
        $atts['post__not_in'] = array_merge($atts['post__not_in'], $ts_previous_posts);
    else :
        $atts['exclude'] = $atts['post__not_in'] = '';
    endif;

    if($atts['include'] == 'related') :
        if($post_type == 'portfolio') :
            $atts['post__not_in'] = (isset($post->ID)) ? array($post->ID) : $atts['post__not_in'];
            $item_cats = (isset($post->ID)) ? get_the_terms($post->ID, 'portfolio-category') : '';
            $item_array = array();
            if(isset($item_cats) && is_array($item_cats)):
                foreach($item_cats as $item_cat) {
                    $item_array[] = $item_cat->term_id;
                }
            endif;
            $atts['tax_query'] = array(
                array(
                    'taxonomy' => 'portfolio-category',
                    'field' => 'id',
                    'terms' => $item_array
                )
            );
        else :
            $atts['post__not_in'] = (isset($post->ID)) ? array($post->ID) : $atts['post__not_in'];
            $atts['category__in'] = (isset($post->ID)) ? wp_get_post_categories($post->ID) : '';

            if(isset($post->ID)) :
                $related_filter = ts_related_post_filter($post->ID);
                $atts['category__in'] = (is_array($related_filter) && $related_filter['type'] == 'category') ? $related_filter['arg'] : '';
                $atts['tag__in'] = (is_array($related_filter) && $related_filter['type'] == 'tag') ? $related_filter['arg'] : '';
            endif;
        endif;
        $atts['include'] = '';
        $atts['post__in'] = '';
    endif;

    if($post_type == 'portfolio' && trim($atts['category_name']) && $atts['include'] != 'related') :
        $portfolio_cats = (is_array($atts['category_name'])) ? $atts['category_name'] : explode(',', $atts['category_name']);
        $atts['tax_query'] = array(
            array(
                'taxonomy' => 'portfolio-category',
                'field' => 'slug',
                'terms' => $portfolio_cats
            )
        );
        $atts['category_name'] = '';
    endif;

    if($post_type == 'portfolio' && trim($atts['cat']) && $atts['include'] != 'related') :
        $portfolio_cats = (is_array($atts['cat'])) ? $atts['cat'] : explode(',', $atts['cat']);
        $atts['tax_query'] = array(
            array(
                'taxonomy' => 'portfolio-category',
                'field' => 'id',
                'terms' => $portfolio_cats
            )
        );
        $atts['category_name'] = '';
        $atts['cat'] = '';
    endif;


    if($layout == 'slider') :
        $atts['meta_query'] = array(
            'relation'=>'OR',
            array('key'=>'_thumbnail_id'),
            array('key'=>'_p_preview_image_id'),
            array('key'=>'_slider_vimeo_id'),
            array('key'=>'_slider_youtube_id')
        );
    endif;

    // if is not infinite scroll, and we're on page 2+, and there are previous posts to exclude...
    // that means we need to use an offset.
    $ts_offset = get_query_var('ts-offset');
    if($ts_offset > 0) :
        //detect and handle pagination...
        if ( $atts['paged'] > 2 ) :

            //Manually determine page query offset (offset + current page (minus one) x posts per page)
            $ts_offset = $ts_offset + ( ($atts['paged'] - 1) * $posts_per_page );

            //Apply adjust page offset
            $atts['offset'] = $ts_offset;

        else :
            //This is the first page. Just use the offset...
            $atts['offset'] = $ts_offset;

        endif;
    endif;

    $infinite_scroll_limit_option = ts_option_vs_default('infinite_scroll_limit', 0);
    if($atts['called_via'] != 'shortcode' && $infinite_scroll_limit_option >= 1) :
        $atts['infinite_scroll_limit'] = $original_atts['infinite_scroll_limit'] = $infinite_scroll_limit_option;
    endif;


    $atts['hide_enlarge'] = (in_array($atts['hide_enlarge'], array('yes','true','1','hide','hidden'))) ? true : false;
    $atts['show_author_avatar'] = (trim($atts['show_author_avatar'])) ? $atts['show_author_avatar'] : ts_option_vs_default('show_author_avatar', 0);
    $atts['show_author_avatar'] = (in_array($atts['show_author_avatar'], array('yes','true','1'))) ? true : false;

    $atts['post_type'] = $post_type;
    $atts['posts_per_page'] = $posts_per_page;
    $atts['nopaging'] = ($posts_per_page == -1) ? true : $nopaging;
    $atts['show_pagination'] = $show_pagination;
    $atts['show_sharing_options'] = $show_sharing_options;
    $atts['title_align'] = $title_align;
    $atts['meta_align'] = $meta_align;
    $atts['excerpt_align'] = $excerpt_align;
    $atts['read_more_align'] = $read_more_align;

    $atts['columns'] = ($layout == 'banner') ? $atts['columns'] : ((strpos($layout, '3columns') === false) ? 2 : 3);

    $original_atts['paged'] = $paged;
    $original_atts['infinite_scroll_button_text'] = $atts['infinite_scroll_button_text'];

    $atts['original_atts'] = $original_atts;

    if($post_type == 'portfolio') :
        ts_portfolio_loop($layout, $atts);
    else :
        ts_blog_loop($layout, $atts);
    endif;

    unset($ts_query);
    wp_reset_postdata();
    $ts_within_blog_loop = false;
}

function ts_related_post_filter($postid)
{
    $filter = array('type' => '', 'arg' => '');

    if($postid)
    {
        $related_option = ts_option_vs_default('show_related_blog_posts', 'yes');
        $related_option = ts_postmeta_vs_default($postid, '_p_related_posts', $related_option);

        if($related_option != 'no' && $related_option != '0')
        {
            if($related_option == 'yes' || $related_option == '1') // filter by category
            {
                $filter['type'] = 'category';
                $filter['arg'] = wp_get_post_categories($postid);
            }
            elseif($related_option == 'yes_specific_cat') // filter by specific category
            {
                $filter['type'] = 'category';
                $term = get_post_meta($postid, '_p_specific_related_category', true);
                $term = trim($term);
                $term = get_term_by('name', $term, 'category');
                if(is_object($term) && isset($term->term_id))
                    $filter['arg'] = array($term->term_id);
                else
                    $filter['arg'] = '';
            }
            elseif($related_option == 'yes_tag') // filter by tag
            {
                $filter['type'] = 'tag';
                $filter['arg'] = wp_get_post_tags($postid, array( 'fields' => 'ids' ));
            }
            elseif($related_option == 'yes_specific_tag') // filter by specific tag
            {
                $filter['type'] = 'tag';
                $term = get_post_meta($postid, '_p_specific_related_tag', true);
                $term = trim($term);
                $term = get_term_by('name', $term, 'post_tag');
                if(is_object($term) && isset($term->term_id))
                    $filter['arg'] = array($term->term_id);
                else
                    $filter['arg'] = '';
            }
        }
    }

    return (is_array($filter)) ? $filter : '';
}

function ts_add_query_vars_filter( $vars ){
    $vars[] = "ts-offset";
    return $vars;
}

function ts_attr_is_true($arg)
{
    return ($arg === true || $arg === 1 || $arg == "1" || strtolower(trim($arg)) == 'true' || strtolower(trim($arg)) == 'yes') ? true : false;
}

function ts_attr_is_false($arg)
{
    return ($arg === false || $arg === 0 || $arg == "0" || strtolower(trim($arg)) == 'false' || strtolower(trim($arg)) == 'no') ? true : false;
}

function ts_maybe_show_blog_elements($atts = '')
{
    $atts = (is_array($atts)) ? $atts : array();

    $true_options = array('true','1','yes');
    $false_options = array('false','0','no');

    $return = array();

    $return['media'] = (isset($atts['show_media']) && in_array($atts['show_media'], $false_options)) ? false : true;
    $return['title'] = (isset($atts['show_title']) && in_array($atts['show_title'], $false_options)) ? false : true;
    $return['category'] = (isset($atts['show_category']) && in_array($atts['show_category'], $false_options)) ? false : true;
    $return['meta'] = (isset($atts['show_meta']) && in_array($atts['show_meta'], $false_options)) ? false : true;
    $return['title_info'] = ($return['title'] || $return['meta']) ? true : false;
    $return['excerpt'] = (isset($atts['show_excerpt']) && in_array($atts['show_excerpt'], $false_options)) ? false : true;
    $return['read_more'] = (isset($atts['show_read_more']) && in_array($atts['show_read_more'], $false_options)) ? false : true;
    $return['sharing_options'] = (isset($atts['show_sharing_options']) && in_array($atts['show_sharing_options'], $false_options)) ? false : true;
    $return['sharing_options'] = (isset($atts['show_sharing_options']) && $atts['show_sharing_options'] === false) ? false : $return['sharing_options'];

    return ts_array2object($return);
}

function ts_maybe_show_blog_widget_elements($atts = '')
{
    $atts = (is_array($atts)) ? $atts : array();

    $true_options = array('true','1','yes');
    $false_options = array('false','0','no');

    $return = array();

    $return['media'] = (isset($atts['hide_media']) && in_array($atts['hide_media'], $true_options)) ? false : true;
    $return['media'] = (isset($atts['show_media']) && in_array($atts['show_media'], $false_options)) ? false : $return['media'];
    $return['featured_first'] = (isset($atts['show_media']) && $atts['show_media'] == 'first') ? true : false;
    $return['meta'] = (isset($atts['show_meta']) && in_array($atts['show_meta'], $false_options)) ? false : true;
    $return['excerpt'] = (isset($atts['show_excerpt']) && in_array($atts['show_excerpt'], $true_options)) ? true : false;
    $return['first_excerpt'] = (isset($atts['show_first_excerpt']) && in_array($atts['show_first_excerpt'], $true_options)) ? true : false;

    return ts_array2object($return);
}

/* wrapper for ts_figure_h_size */
function ts_get_blog_loop_title_size($atts = '', $default = 4)
{
    return ts_figure_h_size($atts, $default);
}
function ts_figure_h_size($atts = '', $default = 4)
{
    $atts = (is_array($atts)) ? $atts : ((is_numeric($atts) || is_string($atts)) ? array('h_size'=>$atts) : array());

    $h_size = (isset($atts['h_size'])) ? $atts['h_size'] : ((isset($atts['title_size'])) ? $atts['title_size'] : $default);

    $h_size = $h_font_size = preg_replace("/[^0-9]*/","", $h_size);
    $h_size = (ts_number_within_range($h_size, 1, 6)) ? $h_size : ((ts_number_within_range($default, 1, 6)) ? $default : '4');
    $h_style = ($h_font_size > 6) ? 'style="font-size:'.esc_attr($h_font_size).'px;"' : '';

    $return['h'] = $h_size;
    $return['style'] = $h_style;
    $return['h_style'] = $h_size.' '.$h_style;

    return ts_array2object($return);
}

/* wrapper for ts_get_h_size */
function ts_get_blog_loop_text_align($atts = '', $default = '')
{
    return ts_figure_text_align($atts, $default);
}
function ts_figure_text_align($atts = '', $default = '')
{
    $atts = (is_array($atts)) ? $atts : array();

    $text_align = (isset($atts['text_align'])) ? $atts['text_align'] : $default;

    return (in_array($text_align, array('left','center','right'))) ? 'text-'.$text_align : '';
}

function ts_portfolio_filter($atts = '')
{
    return; // not used right now...

    global $wp_query;

    $atts = (is_array($atts)) ? $atts : array();
    $show_filter = (isset($atts['show_filter'])) ? $atts['show_filter'] : 'true';
    $show_filter = (in_array($show_filter, array('false','0','no')) || $show_filter === false) ? 0 : 1;

    $align_filter = (isset($atts['align_filter'])) ? $atts['align_filter'] : (isset($atts['align_category_filter']) ? $atts['align_category_filter'] : 'center');
    //$align_filter = (in_array($align_filter, array('left','center','right'))) ? $align_filter : 'center';

    $output = '';

    if(isset($atts['is_search']) && $atts['is_search'] && isset($atts['default_query']) && $atts['default_query'] && !$wp_query->have_posts())
        return $output;

    if($show_filter)
    {
        $categories = get_categories(array('taxonomy' => 'portfolio-category', 'hide_empty' => 1));
        if(count($categories) && !isset($categories['errors'])) :
            $output .= '<ul class="portfolio-filter text-'.esc_attr($align_filter).'" data-show-filter="'.esc_attr($show_filter).'">';
            $output .= '<li><a data-filter="" class="active">'.__('All', 'ThemeStockyard').'</a></li>';

            foreach($categories AS $cat) :
                $output .= '<li><a data-filter="ts-folio-'.esc_attr($cat->slug).'">'.$cat->name.'</a></li>';
            endforeach;

            $output .= '</ul>';
        endif;
    }

    return $output;
}

function ts_portfolio_media_wrap_class($layout = '')
{
    $layout_options = array(
        '2-3-media_1-3-text',
        '2-3-text_1-3-media',
        '1-3-media_2-3-text',
        '1-3-text_2-3-media',
        '1-2-media_1-2-text',
        '1-2-text_1-2-media',
        'fullwidth',
    );

    $layout = (in_array($layout, $layout_options)) ? $layout : $layout_options[0];

    if($layout == '2-3-media_1-3-text') :
        $output = 'span8 span-pull-left';
    elseif($layout == '2-3-text_1-3-media') :
        $output = 'span4 span-pull-right';
    elseif($layout == '1-3-media_2-3-text') :
        $output = 'span4 span-pull-left';
    elseif($layout == '1-3-text_2-3-media') :
        $output = 'span8 span-pull-right';
    elseif($layout == '1-2-media_1-2-text') :
        $output = 'span6 span-pull-left';
    elseif($layout == '1-2-text_1-2-media') :
        $output = 'span6 span-pull-right';
    else :
        $output = 'span12';
    endif;

    return $output;
}

function ts_portfolio_post_wrap_class($layout = '')
{
    $layout_options = array(
        '2-3-media_1-3-text',
        '2-3-text_1-3-media',
        '1-3-media_2-3-text',
        '1-3-text_2-3-media',
        '1-2-media_1-2-text',
        '1-2-text_1-2-media',
        'fullwidth',
    );

    $layout = (in_array($layout, $layout_options)) ? $layout : $layout_options[0];

    if($layout == '2-3-media_1-3-text') :
        $output = 'span4 span-pull-right';
    elseif($layout == '2-3-text_1-3-media') :
        $output = 'span8 span-pull-left';
    elseif($layout == '1-3-media_2-3-text') :
        $output = 'span8 span-pull-right';
    elseif($layout == '1-3-text_2-3-media') :
        $output = 'span4 span-pull-left';
    elseif($layout == '1-2-media_1-2-text') :
        $output = 'span6 span-pull-right';
    elseif($layout == '1-2-text_1-2-media') :
        $output = 'span6 span-pull-left';
    else :
        $output = 'span12';
    endif;

    return $output;
}

function ts_portfolio_direction_nav($reverse = false, $previous_text = '', $next_text = '')
{
    return ts_post_direction_nav($reverse, $previous_text, $next_text);
}

function ts_post_direction_nav_v2($setting = 'yes')
{
    $similar = ($setting == 'yes_similar') ? true : false;
    return ts_post_direction_nav(false, '', '', $similar);
}

function ts_post_direction_nav($reverse = false, $previous_text = '', $next_text = '', $similar = false)
{
    $prev_function = ($reverse) ? 'next_post_link' : 'previous_post_link';
    $next_function = ($reverse) ? 'previous_post_link' : 'next_post_link';

    $previous_text = (trim($previous_text)) ? $previous_text : __('Older', 'ThemeStockyard');
    $next_text = (trim($next_text)) ? $next_text : __('Newer', 'ThemeStockyard');

    echo '<div class="post-single-prev-next ts-post-section-inner clearfix">';
    echo '<div class="post-single-next">';
    $next_function('%link','<span class="dir smaller uppercase"><i class="fa fa-chevron-left"></i>'.$next_text.'</span><span class="title">%title</span>', $similar);
    echo '</div>';
    echo '<div class="post-single-prev">';
    $prev_function('%link', '<span class="dir smaller uppercase">'.$previous_text.'<i class="fa fa-chevron-right"></i></span><span class="title">%title</span>', $similar);
    echo '</div>';
    echo '</div>';
}


function ts_get_term_link($var = '')
{
    //debugging...
    //print_r($var);
    //die();
    if(is_object($var) || (is_array($var) && isset($var['taxonomy'])))
    {
        $var = (object) $var;
        $term_link = get_term_link( $var );
    } else {
        $var = (is_array($var) && isset($var['term_id'])) ? $var['term_id'] : $var;
        $term_link = get_category_link($var);
    }

    // If there was an error, continue to the next term.
    return ( is_wp_error( $term_link ) ) ? '' : $term_link;
}


function ts_get_the_display_taxonomy($atts = '')
{
    $tax = 'category';

    $atts = (is_array($atts)) ? $atts : array();

    if(isset($atts['display_taxonomy']) && trim($atts['display_taxonomy'])) :
        $tax = $atts['display_taxonomy'];
    elseif(is_archive() && get_post_type() != 'post' && get_query_var('taxonomy')) :
        $tax = get_query_var('taxonomy');
    endif;

    return $tax;
}

function ts_substr_trim($str, $trim = '')
{
    $str = trim($str);
    $trim = trim($trim);
    $trim_count = mb_strlen($trim);

    if(substr($str, 0, $trim_count) == $trim) :
        $str = trim(substr($str, $trim_count));
    endif;

    if(substr($str, -$trim_count) == $trim) :
        $str = trim(substr($str, 0, -$trim_count));
    endif;

    return $str;
}


// get categories for a post
// returns: array() or comma-separated list
function ts_get_the_category($taxonomy = 'category', $return = 'list', $separator = ', ', $postid = '')
{
    global $post;
    $display = '';
    $output = ($return == 'array') ? array() : '';
    $limit = explode(':', $return);
    $limit = end($limit);
    $postid = ($postid) ? $postid : ((is_object($post) && isset($post->ID)) ? $post->ID : '');
    if($postid) :
        $categories = get_the_terms($postid, $taxonomy);
        if($return == 'array' || ts_starts_with($return, 'array')) :
            $output = array();
            if($categories) :
                $i = 1;
                foreach($categories as $category) :
                    $output[$i-1] = $category->name;

                    if($limit && is_numeric($limit) && $i == $limit) break;

                    $i++;
                endforeach;
            else :
                $output[0] = __('Uncategorized', 'ThemeStockyard');
            endif;
        elseif($return == 'big_array' || ts_starts_with($return, 'big_array')) :
            $output = array();
            if(is_category() && $limit == 1) :
                $category = get_category( get_query_var( 'cat' ) );
                //$output[0] = array('term_id'=>$category->cat_ID, 'name'=>$category->name, 'og_object'=>$category);
                $output[0] = (array) $category;
            elseif($categories) :
                $i = 1;
                foreach($categories as $category) :
                    $category = (array) $category;
                    $category['color'] = ts_option_vs_default('taxonomy_'.$category['term_id'].'_color', '');
                    $output[$i-1] = $category;

                    if($limit && is_numeric($limit) && $i == $limit) break;

                    $i++;
                endforeach;
            else :
                $output[0] = array('term_id'=>1, 'name'=>__('Uncategorized', 'ThemeStockyard'));
            endif;
        elseif(in_array($return, array('link', 'links')) || ts_starts_with($return, 'link')) :
            //$separator = ', ';
            $output = '';
            if(is_array($categories)) :
                $i = 1;
                foreach($categories as $category) :
                    $output .= '<a href="'.esc_url($category->rewrite).'">'.$category->name.'</a>'.$separator;

                    if($limit && is_numeric($limit) && $i == $limit) break;

                    $i++;
                endforeach;
                $output = ts_substr_trim($output, $separator);
            endif;
        else :
            //$separator = ', ';
            $output = '';
            if(is_array($categories)) :
                $i = 1;
                foreach($categories as $category) :
                    $display = ($return == 'slugs') ? $category->slug : (($return == 'filter-slugs') ? 'ts-folio-'.$category->slug : $category->name);
                    $output .= $display.$separator;

                    if($limit && is_numeric($limit) && $i == $limit) break;

                    $i++;
                endforeach;
                $output = ts_substr_trim($output, $separator);
            endif;
        endif;
    endif;

    return $output;
}

// get list of all available entries for a specific taxonomy
function ts_get_categories($category_name = 'category', $hide_empty = '1', $return = 'array')
{
    $hide_empty  = ($hide_empty == 1) ? '1' : '0';
    $categories = get_categories(array('taxonomy' => $category_name, 'hide_empty' => $hide_empty));
    $categories = (isset($categories['errors'])) ? array() : $categories;
    //$categories = get_terms($category_name, array('hide_empty' => $hide_empty));
    //$categories = $categories[0];

    if($return == 'array')
        return $categories;
    elseif($return == 'cb_metabox_array')
    {
        $category_list = array();
        foreach ($categories as $category)
        {
            $category_list[] = array('name'=>$category->cat_name, 'value'=>$category->term_id);
        }

        return $category_list;
    }
    else
    {
        $all = ($parent) ? $parent : 'All';
        $category_list = array('0' => $all);

        foreach ($categories as $category)
        {
            $category_list[] = $category['name'];
        }

        return $category_list;
    }
}

// WP Loop Pagination
function ts_paginator($pages = '', $range = 2)
{
    global $ts_previous_posts;

    $output = '';
    $append = '';
    $showitems = ($range * 2)+1;
    $atts = (is_array($pages)) ? $pages : array();
    $atts = (isset($atts['original_atts']) && is_array($atts['original_atts'])) ? $atts['original_atts'] : $atts;
    $infinite_scroll = false;
    $pages = (is_array($pages)) ? '' : $pages;
    $limit = (isset($atts['limit'])) ? $atts['limit'] : ((isset($atts['posts_per_page'])) ? $atts['posts_per_page'] : get_option('posts_per_page'));

    $next_icon_direction = (ts_option_vs_default('rtl', 0) == 1) ? 'left' : 'right';
    $prev_icon_direction = (ts_option_vs_default('rtl', 0) == 1) ? 'right' : 'left';

    global $paged;
    if (empty($paged)) $paged = 1;

    $paged = (isset($atts['paged']) && isset($atts['via_ajax'])) ? $atts['paged'] : $paged;


    if ($pages == '')
    {
        global $wp_query, $ts_query;
        $pages = (isset($ts_query) && !is_search()) ? $ts_query->max_num_pages : $wp_query->max_num_pages;
        if (!$pages) { $pages = 1; }
    }

    $use_offset = false; //($paged >= 1 && is_array($ts_previous_posts) && count($ts_previous_posts) > $limit) ? true : false;

    // if "use offset", we need to override "max_num_pages"
    if($use_offset)
    {
        $found_posts = (isset($ts_query) && !is_search()) ? $ts_query->found_posts : $wp_query->found_posts;
        $pages = ceil($found_posts / count($ts_previous_posts));
    }

    if (1 != $pages)
    {
        $output .= '<div class="divider-shortcode divider"></div>';

        if(isset($atts['infinite_scroll']) && in_array($atts['infinite_scroll'], array('yes','true','1','yes_button')))
        {
            global $ts_infinite_scroller;

            // if an infinite scroller is already active on current page,
            // or if max_page has been reached, do nothing.
            if($ts_infinite_scroller == true || ($paged > $pages))
            {
                return '';
            }
            else
            {
                $layout = (isset($atts['layout'])) ? preg_replace("/[^0-9a-z]*/", "", strtolower($atts['layout'])) : '';

                if(in_array($layout, array('masonry', 'grid', 'cards', 'gridcards', 'cardgrid', 'masonrycards')))
                    $threshold_class = 'threshold-pending';
                else
                    $threshold_class = 'threshold';

                $atts['paged'] = $paged + 1; // set up next query
                $atts['is_final'] = ($paged == $pages) ? 1 : 0;
                if(isset($atts['excluded_posts_preserved'])) :
                    $exclude = $atts['excluded_posts_preserved'];
                else :
                    if(is_array($ts_previous_posts) && isset($atts['limit'])) :
                        $pp_count  = count($ts_previous_posts);
                        $ex_count = (($pp_count - $atts['limit']) >= 0) ? $pp_count - $atts['limit'] : $pp_count;
                        $exclude = array_slice($ts_previous_posts, 0, $ex_count);
                        $atts['excluded_posts_preserved'] = $exclude;
                    else :
                        $exclude = $ts_previous_posts;
                    endif;
                endif;
                $atts['exclude'] = (is_array($exclude)) ? implode(',', $exclude) : $exclude;
                $exclude_array = (is_array($exclude)) ? $exclude : explode(',', $exclude);
                if(isset($atts['via_ajax'])) $atts['test'] = 'success';
                $use_button = ($atts['infinite_scroll'] == 'yes_button') ? 'ts-use-infinite-scroll-button' : '';
                $hide_spinner = ($atts['infinite_scroll'] == 'yes_button') ? 'display:none;' : '';
                $load_button_text = (isset($atts['infinite_scroll_button_text'])) ? $atts['infinite_scroll_button_text'] : ts_option_vs_default('infinite_scroll_button_text', 'Load more posts');
                $load_button_text = (trim($load_button_text)) ? $load_button_text : __('Load more posts','ThemeStockyard');

                $load_button_color = (isset($atts['infinite_scroll_button_color'])) ? esc_attr($atts['infinite_scroll_button_color']) : '';
                $load_button_color = ($load_button_color == '#fff' || $load_button_color == '#ffffff') ? 'white' : $load_button_color;
                if(substr($load_button_color, 0, 1) == '#') :
                    $load_button_color_css = 'background-color:'.$load_button_color.' !important';
                    $load_button_color = $load_spinner_color = '';
                else :
                    $load_button_color_css = '';
                    $load_button_color = $load_button_color.' color-shortcode';
                    $load_spinner_color = 'bg-'.$load_button_color;
                endif;

                $infinite_scroll_limit = (isset($atts['infinite_scroll_limit'])) ? intval($atts['infinite_scroll_limit']) : 0;
                $has_limit = ($infinite_scroll_limit > 0) ? 'has-infinite-scroll-limit' : '';

                $output .= '<div class="infinite-scroller ready '.esc_attr($use_button).' '.esc_attr($has_limit).'">';
                $output .= '<div class="'.esc_attr($threshold_class).'"></div>';
                $output .= '<div class="spinner" style="'.esc_attr($hide_spinner).'">';
                $output .= '<div class="bounce1 '.esc_attr($load_spinner_color).'" style="'.esc_attr($load_button_color_css).'"></div>';
                $output .= '<div class="bounce2 '.esc_attr($load_spinner_color).'" style="'.esc_attr($load_button_color_css).'"></div>';
                $output .= '<div class="bounce3 '.esc_attr($load_spinner_color).'" style="'.esc_attr($load_button_color_css).'"></div>';
                $output .= '</div>';
                $output .= '<div class="alt-loader text-center">';
                $output .= '<a class="button mimic-smaller uppercase '.esc_attr($load_button_color).'" style="'.esc_attr($load_button_color_css).'">'.$load_button_text.'</a>';
                $output .= '</div>';

                if(isset($atts['default_query']) && $atts['default_query'] === true && isset($wp_query->query_vars)) :
                    $_atts = $wp_query->query_vars;
                    $_atts['paged'] = $atts['paged'];
                    $_atts['is_final'] = $atts['is_final'];
                    $_atts['layout'] = $atts['layout'];
                    $_atts['pid'] = (isset($atts['pid'])) ? $atts['pid'] : '';
                    $_atts['infinite_scroll'] = 'yes';
                    $_atts['infinite_scroll_limit'] = $infinite_scroll_limit;
                    $_atts['_dq'] = true;
                    $_atts['exclude'] = '';
                    $atts = $_atts;
                elseif(isset($atts['_dq']) && ($atts['_dq'] === true || $atts['_dq'] == 'true')) :
                    $atts['exclude'] = '';
                    $atts['action'] = '';
                endif;

                $output .= '<div class="infinite-scroller-atts hidden">'.json_encode($atts).'</div>';
                $output .= '</div>';

                $ts_infinite_scroller = true;
            }
        }
        else
        {
            if($use_offset) :
                $prev_count = count($ts_previous_posts);
                $q_offset_key = 'ts-offset';
                $q_offset_var = $prev_count;
            else :
                $q_offset_key = '';
                $q_offset_var = '';
            endif;

            $output .= '<div class="pagination-wrap"><p class="pagination clearfix">';

            $link = remove_query_arg('ts-offset', get_pagenum_link(1));
            $link_or_span_open = ($paged > 1) ? '<a href="' . esc_url($link) . '">' : '<span class="subtle-text-color">';
            $link_or_span_close = ($paged > 1) ? '</a>' : '</span>';
            $output .= $link_or_span_open.'<i class="fa fa-angle-double-'.esc_attr($prev_icon_direction).'"></i>'.$link_or_span_close;


            $link = esc_url(add_query_arg($q_offset_key, $q_offset_var, get_pagenum_link($paged - 1)));
            if($paged == 2) :
                $link = esc_url(remove_query_arg('ts-offset', get_pagenum_link($paged - 1)));
            else :
                $link = esc_url(add_query_arg($q_offset_key, $q_offset_var, get_pagenum_link($paged - 1)));
            endif;

            $link_or_span_open = ($paged > 1) ? '<a href="' . esc_url($link) . '">' : '<span class="subtle-text-color">';
            $link_or_span_close = ($paged > 1) ? '</a>' : '</span>';
            $output .= $link_or_span_open.'<i class="fa fa-angle-'.esc_attr($prev_icon_direction).'"></i>'.$link_or_span_close;

            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    $link = esc_url(add_query_arg($q_offset_key, $q_offset_var, get_pagenum_link($i)));
                    $link = ($i == 1) ? remove_query_arg('ts-offset', get_pagenum_link($i)) : $link;
                    if($paged == $i) :
                        $output .= '<a href="' . esc_url($link) . '" class="active"><strong>' . $i . '</strong></a>';
                    else :
                        $output .= '<a href="' . esc_url($link) . '" class="">' . $i . '</a>';
                    endif;
                }
            }

            $link = esc_url(add_query_arg($q_offset_key, $q_offset_var, get_pagenum_link($paged + 1)));
            $link_or_span_open = ($paged < $pages) ? '<a href="' . esc_url($link) . '" >' : '<span class="subtle-text-color">';
            $link_or_span_close = ($paged < $pages) ? '</a>' : '</span>';
            $output .= $link_or_span_open.'<i class="fa fa-angle-'.esc_attr($next_icon_direction).'"></i>'.$link_or_span_close;

            $link = esc_url(add_query_arg($q_offset_key, $q_offset_var, get_pagenum_link($pages)));
            $link_or_span_open = ($paged < $pages) ? '<a href="' . esc_url($link) . '">' : '<span class="subtle-text-color">';
            $link_or_span_close = ($paged < $pages) ? '</a>' : '</span>';
            $output .= $link_or_span_open.'<i class="fa fa-angle-double-'.esc_attr($next_icon_direction).'"></i>'.$link_or_span_close;

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $output .= '</p></div>'."\n";
        }

        return str_replace("\n", '', $output);
    }
    else
    {
        return '<!--notsearch-->';
    }
}


function ts_load_infinite_blog()
{
    $post = get_post($_POST['pid']);

    if(is_object($post) && isset($post->ID))
    {
        global $ts_show_sidebar;

        $ts_show_sidebar_option = (ts_option_vs_default('show_page_sidebar', 1) < 1) ? 'no' : 'yes';
        $ts_show_sidebar = ts_postmeta_vs_default($post->ID, '_page_sidebar', $ts_show_sidebar_option);

        $post_data = $_POST;
        $post_data['via_ajax'] = 1;

        echo ts_blog($post_data, $post_data['layout']);
    }

    exit;
}


function dimox_breadcrumbs() {

	/* === OPTIONS === */
	$text['home']     = ts_option_vs_default('breadcrumbs_home_link_text', '');
	$text['home']     = (trim($text['home'])) ? $text['home'] : __('Home', 'ThemeStockyard'); // text for the 'Home' link
	$text['portfolio']     = ts_option_vs_default('breadcrumbs_portfolio_link_text', ''); // text for the 'Portfolio' link
	$text['category'] = __('Category Archives', 'ThemeStockyard'); // text for a category page
	$text['search']   = __('Search Results', 'ThemeStockyard'); // text for a search results page
	$text['tag']      = __('Tagged Posts', 'ThemeStockyard'); // text for a tag page
	$text['author']   = __('By %s', 'ThemeStockyard'); // text for an author page
	$text['404']      = __('Error 404', 'ThemeStockyard'); // text for the 404 page

	$show_current   = (is_single()) ? 0 : 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
	$current_limit  = 20; // set unusually high (like 1000) to not trim text at all
	$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
	$show_title     = 1; // 1 - show the title for the links, 0 - don't show
	$delimiter      = ' <span class="delimiter">/</span> '; // delimiter between crumbs
	$before         = '<span class="current">'; // tag before the current crumb
	$after          = '</span>'; // tag after the current crumb
	/* === END OF OPTIONS === */

	global $post;
	$output = '';
	$home_link    = home_url('/');
	$link_before  = '<span typeof="v:Breadcrumb">';
	$link_after   = '</span>';
	$link_attr    = ' rel="v:url" property="v:title"';
	$link         = $link_before . '<a rel="v:url" property="v:title" href="%1$s">%2$s</a>' . $link_after;
	if(is_object($post) && isset($post->post_parent)) {
        $parent_id = $parent_id_2 = $post->post_parent;
	} else {
        $parent_id = $parent_id_2 = null;
	}
	$frontpage_id = get_option('page_on_front');

	if (is_home() || is_front_page()) {
        if( is_home() && get_option('page_for_posts') )
        {
            $ts_blog_page_id = get_option('page_for_posts');
            $ts_page_title = get_page($ts_blog_page_id)->post_title;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $paged = ($paged == 1 || ts_contains_word($ts_page_title, 'Page', true)) ? '' : ' (Page '.$paged.')';
            $output .= '<div class="breadcrumbs small"><a href="' . esc_url($home_link) . '">' . $text['home'] . '</a> '.$delimiter.$ts_page_title.$paged.'</div>';
        }
        else
        {
            if ($show_on_home == 1) $output .= '<div class="breadcrumbs small"><a href="' . esc_url($home_link) . '">' . $text['home'] . '</a></div>';
        }
	} else {

		$output .= '<div class="breadcrumbs small">';
		if ($show_home_link == 1) {
			$output .= sprintf($link, $home_link, $text['home']);
			if ($frontpage_id == 0 || $parent_id != $frontpage_id) $output .= $delimiter;
		}

		if ( is_category() ) {
			$this_cat = get_category(get_query_var('cat'), false);
			if ($this_cat->parent != 0) {
				$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
				if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
				$cats = str_replace('<a', $link_before . '<a rel="v:url" property="v:title"', $cats);
				$cats = str_replace('</a>', '</a>' . $link_after, $cats);
				if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
				$output .= $cats;
			}
			if ($show_current == 1) $output .= $before . ts_trim_text(sprintf($text['category'], single_cat_title('', false)), $current_limit) . $after;

		} elseif ( is_search() ) {
			$output .= $before . ts_trim_text(sprintf($text['search'], get_search_query()), $current_limit) . $after;

		} elseif ( is_day() ) {
			$output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			$output .= sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
			$output .= $before . get_the_time('d') . $after;

		} elseif ( is_month() ) {
			$output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			$output .= $before . get_the_time('F') . $after;

		} elseif ( is_year() ) {
			$output .= $before . get_the_time('Y') . $after;
        } elseif (function_exists('is_woocommerce') && is_woocommerce()) {
            $shop_page = get_post(woocommerce_get_page_id('shop'));
            if(is_shop()) {
                if ($show_current == 1) $output .= $before . ts_trim_text($shop_page->post_title, $current_limit) . $after;
            } else {
                $output .= sprintf($link, get_permalink($shop_page->ID), $shop_page->post_title);
            }
		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$home_link = (ts_ends_with($home_link, '/')) ? $home_link : $home_link . '/';

				$label = (trim($text['portfolio']) && get_post_type() == 'portfolio') ? $text['portfolio'] : $post_type->labels->name;

				sprintf($link, $home_link . $slug['slug'] . '/', $label);
				if ($show_current == 1) $output .= $delimiter . $before . ts_trim_text(get_the_title(), $current_limit) . $after;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, $delimiter);
				if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
				$cats = str_replace('<a', $link_before . '<a rel="v:url" property="v:title"', $cats);
				$cats = str_replace('</a>', '</a>' . $link_after, $cats);
				if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', esc_attr($cats));
				$output .= $cats;
				if ($show_current == 1) $output .= $before . ts_trim_text(get_the_title(), $current_limit) . $after;
			}

		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			$label = (trim($text['portfolio']) && get_post_type() == 'portfolio') ? $text['portfolio'] : $post_type->labels->name;
			$output .= $before . ts_trim_text($label, $current_limit) . $after;

		} elseif ( is_attachment() ) {
			$parent = get_post($parent_id);
			$cat = get_the_category($parent->ID);
			$cat = (isset($cat[0])) ? $cat[0] : '';
			if($cat)
			{
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $link_before . '<a rel="v:url" property="v:title"', $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', esc_attr($cats));
                $output .= $cats;
			}
			$output .= sprintf($link, get_permalink($parent), $parent->post_title);
			//if ($cat && $show_current == 1) $output .= $delimiter;
			if ($show_current == 1) $output .= $delimiter . $before . ts_trim_text(get_the_title(), $current_limit) . $after;

		} elseif ( is_page() && !$parent_id ) {
			if ($show_current == 1) $output .= $before . ts_trim_text(get_the_title(), $current_limit) . $after;

		} elseif ( is_page() && $parent_id ) {
			if ($parent_id != $frontpage_id) {
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					if ($parent_id != $frontpage_id) {
						$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
					}
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					$output .= $breadcrumbs[$i];
					if ($i != count($breadcrumbs)-1) $output .= $delimiter;
				}
			}
			if ($show_current == 1) {
				if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) $output .= $delimiter;
				$output .= $before . ts_trim_text(get_the_title(), $current_limit) . $after;
			}

		} elseif ( is_tag() ) {
			$output .= $before . ts_trim_text(sprintf($text['tag'], single_tag_title('', false)), $current_limit) . $after;

		} elseif ( is_author() ) {
	 		global $author;
			$userdata = get_userdata($author);
			$output .= $before . ts_trim_text(sprintf($text['author'], $userdata->display_name), $current_limit) . $after;

		} elseif ( is_404() ) {
			$output .= $before . $text['404'] . $after;
		}

		if ( get_query_var('paged') ) {
			$output .= ' ('.__('Page') . ' ' . get_query_var('paged').')';
		}

		$output .= '</div><!-- .breadcrumbs -->';

	}

	return $output;
} // end dimox_breadcrumbs(

function ts_breadcrumbs()
{
    global $smof_data, $post;

    $breadcrumb_prefix = ts_option_vs_default('breadcrumb_prefix', '');
    $output         = '';
    $output        .= '<ul class="breadcrumbs small">';

    if(!is_front_page())
    {
        $output    .= '<li>'.$breadcrumb_prefix.' <a href="'.esc_url(home_url()).'">'.__('Home', 'ThemeStockyard')."</a></li>";
    }

    $params = array();
    $params['link_none'] = '';
    $separator = '';

    if(is_category() && !is_singular('ts_portfolio'))
    {
        $category   = get_the_category();
        $cat_id     = $category[0]->cat_ID;
        $output    .= is_wp_error( $cat_parents = get_category_parents($cat_id, true, '', false)) ? '' : '<li>'.$cat_parents.'</li>';
    }

    if(is_singular('ts_portfolio'))
    {
        $output    .= get_the_term_list($post->ID, 'portfolio_category', '<li>', '&nbsp;/&nbsp;', '</li>');
        $output    .= '<li>'.get_the_title().'</li>';
    }

    if(is_tax())
    {
        $term       = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $output    .= '<li>'.$term->name.'</li>';
    }

    if(is_home())
    {
        $blog_title = ts_option_vs_default('blog_title', __('Blog', 'ThemeStockyard'));
        $output    .= '<li>'.$blog_title.'</li>';
    }

    if(is_page() && !is_front_page())
    {
        $parents    = array();
        $parent_id  = $post->post_parent;
        while($parent_id)
        {
            $page = get_page( $parent_id );
            if ($params["link_none"])
                $parents[]  = get_the_title( $page->ID );
            else
                $parents[]  = '<li><a href="' . esc_url(get_permalink( $page->ID )) . '" title="' . esc_attr(get_the_title( $page->ID )) . '">' . get_the_title( $page->ID ) . '</a></li>' . $separator;
            $parent_id  = $page->post_parent;
        }
        $parents    = array_reverse( $parents );
        $output    .= join( ' ', $parents );
        $output    .= '<li>'.get_the_title().'</li>';
    }
    if(is_single() && !is_singular('ts_portfolio'))
    {
        $categories_1 = get_the_category($post->ID);
        if($categories_1) :
            foreach($categories_1 as $cat_1) :
                $cat_1_ids[] = $cat_1->term_id;
            endforeach;
            $cat_1_line = implode(',', $cat_1_ids);
        endif;
        $categories = get_categories(array(
            'include' => $cat_1_line,
            'orderby' => 'id'
        ));
        if ( $categories ) :
            foreach ( $categories as $cat ) :
                $cats[] = '<li><a href="' . esc_url(get_category_link( $cat->term_id )) . '" title="' . esc_attr($cat->name) . '">' . $cat->name . '</a></li>';
            endforeach;
            $output .= join( ' ', $cats );
        endif;
        $output     .= '<li>'.get_the_title().'</li>';
    }
    if(is_tag()) { $output .= '<li>'.__("Tag:", 'ThemeStockyard').' '.single_tag_title('',FALSE).'</li>'; }
    if(is_404()) { $output .= '<li>'.__("404 - Page not Found", 'ThemeStockyard').'</li>'; }
    if(is_search()) { $output .= '<li>'.__("Search", 'ThemeStockyard').'</li>'; }
    if(is_year()) { $output .= '<li>'.get_the_time('Y').'</li>'; }

    $output .= "</ul>";

    return $output;
}

//
function ts_get_cat_IDs($args = '')
{
    if(ctype_digit($args) || is_int($args))
        return $args;
    elseif(is_array($args))
    {
        $ids = array();
        foreach($args AS $arg)
        {
            $id = (ctype_digit($arg) || is_int($arg)) ? $arg : get_cat_ID($arg);
            if($id) $ids[] = $id;
        }

        return implode(',', $ids);
    }
    else
    {
        $id = get_cat_ID($args);
        return ($id) ? $id : '';
    }

}

function ts_wp_remote_get($url = '')
{
    $data = wp_remote_get($url);
    $data = (isset($data['body'])) ? $data['body'] : array();
    return $data;
}

function ts_get_proportional_size($orig_w, $orig_h, $dest_w = null, $dest_h = null)
{
    if($dest_w < 1 && $dest_h < 1) :
        return array('width'=>$orig_w, 'height'=>$orig_h);
    elseif($dest_w < 1 && $dest_h > 0) :
        $dest_w = round(($orig_w * $dest_h) / $orig_h);
        return array('width'=>$dest_w, 'height'=>$dest_h);
    elseif($dest_w > 0 && $dest_h < 1) :
        $dest_h = round(($orig_h * $dest_w) / $orig_w);
        return array('width'=>$dest_w, 'height'=>$dest_h);
    endif;
}

function ts_get_and_save_video_data($id = '', $service = '', $default = '')
{
    $default = (is_array($default)) ? $default : array('width'=>640,'height'=>360);
    return ts_save_video_data($id, $service, $default);
}

function ts_save_video_data($id = '', $service = '', $default = '')
{
    global $post;
    if(is_object($post) && isset($post->ID))
    {
        $data = ts_get_video_data($id, $service, $default);
        update_post_meta($post->ID, '_ts_video_data', $data);
    }

    return $data;
}

function ts_get_saved_video_data($id = '', $service = '')
{
    global $post;
    if(is_object($post) && isset($post->ID))
    {
        $data = get_post_meta($post->ID, '_ts_video_data', true);
        if($data)
        {
            $data = maybe_unserialize($data);
            if(is_array($data) && isset($data['id']) && $data['id'] == $id)
            {
                return $data;
            }
        }
    }

    return null;
}

function ts_get_video_data($id = '', $service = '', $default = '')
{
    $id = trim($id);
    $service = trim($service);

    $id = (preg_match("/^[a-z0-9\-_]+$/i", $id) || ctype_digit($id)) ? $id : ts_get_video_id($id);

    // first, see if there is any saved video data
    $data = ts_get_saved_video_data($id, $service);

    if(is_array($data))
        return $data;

    if($id)
    {
        if($service == 'youtube')
        {
            $data = ts_wp_remote_get('https://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3D'.$id.'&format=json');
            $data = json_decode($data);
            $data = (is_array($data)) ? $data[0] : $data;

            if(isset($data->type) && $data->type == 'video')
            {
                return array(
                    'id'            => $id,
                    'width'         => $data->width,
                    'height'        => $data->height,
                    'title'         => $data->title,
                    'description'   => '',
                    'url'           => 'https://www.youtube.com/watch?v='.$id,
                    'thumbnail'     => $data->thumbnail_url,
                    'service'       => 'youtube'
                );
            }
        }
        elseif($service == 'vimeo')
        {
            $grab = ts_wp_remote_get('https://vimeo.com/api/v2/video/'.$id.'.json');
            $data = json_decode($grab);
            $data = (is_array($data)) ? $data[0] : $data;

            $dims = ts_wp_remote_get('https://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/'.$id);
            $dims = json_decode($dims);
            $dims = (is_array($dims)) ? $dims[0] : $dims;

            if($data->url)
            {
                return array(
                    'id'            => $id,
                    'width'         => $dims->width,
                    'height'        => $dims->height,
                    'title'         => $data->title,
                    'description'   => ts_trim_text(strip_tags($data->description), 100),
                    'url'           => $data->url,
                    'thumbnail'     => $data->thumbnail_medium,
                    'service'       => 'vimeo'
                );
            }
        }
    }

    return $default;
}

function ts_get_video_qs_addons($url, $sep = '')
{
    if(preg_match("/^[a-z0-9\-_]+$/i", $url) || ctype_digit($url) || !trim($url))
        return '';

    $url = str_replace('&amp;', '&', $url);
    $url_parts  = parse_url($url);
    $url_part   = (is_array($url_parts)) ? ts_array2object($url_parts) : $url_parts;

    if(is_object($url_part) && isset($url_part->query))
    {
        parse_str($url_part->query, $qvars);

        if(isset($qvars['id'])) unset($qvars['id']);
        if(isset($qvars['v'])) unset($qvars['v']);
        if(isset($qvars['list']) && strtolower($qvars['list']) == 'wl') unset($qvars['list']);

        return $sep . http_build_query($qvars);
    }

    return '';
}

// Parse a Youtube, Vine, or Vimeo URL and return the ID
function ts_get_video_id($url)
{
    $url        = trim($url);

    if(preg_match("/^[a-z0-9\-_]+$/i", $url) || ctype_digit($url))
        return $url;

    $url_parts  = parse_url($url);
    $url_part   = (is_array($url_parts)) ? ts_array2object($url_parts) : $url_parts;

    if(is_object($url_part) && isset($url_part->host))
    {
        $host_names     = explode(".", $url_part->host);
        $url_part->host = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];

        if(($url_part->host == 'youtube.com') || ($url_part->host == 'youtu.be'))
        {
            if($url_part->host == 'youtube.com')
            {
                if($url_part->query)
                {
                    parse_str($url_part->query);
                }

                if(isset($v)) :
                    $id = $v;
                elseif(strpos($url_part->path, '/v/') !== false) :
                    $id_parts = explode('/v/', $url_part->path, 2);
                    $id = end($id_parts);
                elseif(strpos($url_part->path, '/embed/') !== false) :
                    $id_parts = explode('/embed/', $url_part->path, 2);
                    $id = end($id_parts);
                else :
                    $id = '';
                endif;
            }
            else
            {
                $id = substr($url_part->path, 1);
            }

            return $id;
        }
        elseif($url_part->host == 'vimeo.com')
        {
            if($url_part->path == '/channels/hd') :
                $id = $url_part->fragment;
            elseif(substr($url_part->path, 0, 7) == '/video/' || substr($url_part->path, 0, 8) == '/groups/' || substr($url_part->path, 0, 10) == '/channels/') :
                $id_parts = explode('/', $url_part->path);
                $id = end($id_parts);
            else :
                $id = substr($url_part->path, 1);
            endif;

            if(ctype_digit($id))
                return $id;
        }
        elseif($url_part->host == 'vine.co')
        {
            if(substr($url_part->path, 0, 3) == '/v/')
                return current(explode('/', substr($url_part->path, 3)));
        }
    }

    return null;
}

// Parse a SoundCloud or Spotify URL and return the embed URL
function ts_get_audio_embed_url($url)
{
    $url  = trim($url);
    $embed_url = '';

    $url_parts  = parse_url($url);
    $url_part   = (is_array($url_parts)) ? ts_array2object($url_parts) : $url_parts;

    if(is_object($url_part) && isset($url_part->host)) :
        $host_names     = explode(".", $url_part->host);
        $url_host = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
    else :
        $url_host = '';
    endif;


    if($url_host == 'spotify.com' || substr($url, 0, 14) == 'spotify:track:')
    {
        if(substr($url, 0, 14) == 'spotify:track:')
        {
            $embed_url = 'https://embed.spotify.com/?uri='.$url;
        }
        else
        {
            $id = explode('/', $url);
            $id = end($id);
            $embed_url = 'https://embed.spotify.com/?uri=spotify:track:'.$id;
        }

        return $embed_url;
    }
    elseif($url_host == 'soundcloud.com')
    {
        $embed_url ='https://w.soundcloud.com/player/?url='.urlencode($url);

        return $embed_url;
    }

    return null;
}

// Outputs a number in short, human readable format.
// Eg. 1421056 becomes 1.4M
function ts_num2str($val)
{
    $unit = array('','K','M','B');
    while($val >= 1000)
    {
        $val /= 1000;
        array_shift($unit);
    }
    $unit = array_shift($unit);
    return (round($val, 0) > 99) ? round($val, 0).$unit : round($val, 1).$unit;
}

//
function ts_option_vs_default($option = '', $default = '', $avoid_empty_values = false)
{
    global $smof_data;

    if(!is_array($smof_data)) {
        unset($smof_data);
        $smof_data = get_option(TS_THEMENAME . '_options');
    }

    if(trim($option)) {
        $parts = explode('::', $option);
        if(count($parts) == 2)
        {
            if(isset($smof_data[$parts[0]][$parts[1]]))
                return ($avoid_empty_values && $smof_data[$parts[0]][$parts[1]] == '') ? $default : $smof_data[$parts[0]][$parts[1]];
        }
        else
        {
            if(isset($smof_data[$option]))
                return ($avoid_empty_values && $smof_data[$option] == '') ? $default : $smof_data[$option];
        }
    }
    return ($default) ? $default : '';
}

//
function ts_postmeta_vs_default($id, $key = '', $default = '')
{
    if(trim($key)) {
        $postmeta = get_post_meta( $id, $key, true );
        if(trim($postmeta) !== '' && $postmeta != 'default')
            return $postmeta;
    }
    return ($default) ? $default : '';
}

// Check a user's preferences (theme option vs post meta)
// Post meta is higher priority.
function ts_postmeta_vs_option($id = '', $postmeta = '', $option = '', $default = '')
{
    global $smof_data;
    if(trim($postmeta) && $postmeta != 'default' && $id) {
        $postmeta = get_post_meta( $id, $postmeta, true );
        if($postmeta !== '')
            return $postmeta;
    }
    return ts_option_vs_default($option, $default);
}

// test to see if a number is within a range of numbers...
function ts_number_within_range($num, $min = 1, $max = 100)
{
    // make sure max & min are placed correctly
    if($min > $max) list($min, $max) = array($max, $min);
    return ($num >= $min && $num <= $max) ? true : false;
}

/**
 * Replacing the default WordPress search form with an HTML5 version
 * http://bavotasan.com/2011/html5-search-form-in-wordpress/
 */
function ts_html5_search_form( $form = '' ) {
    global $smof_data;
    $class = is_page(533) ? ' se-home' : '';
    $placeholder_text = ts_option_vs_default('search_placeholder_text', 'Search...');
    $form = '<form role="search" method="get" class="ts-searchform' . $class . '" action="' . esc_url(home_url( '/' )) . '" >
    <div class="form-group">
    <label class="assistive-text">' . __('Search for:', 'ThemeStockyard') . '</label>
    <input type="text" placeholder="Write Location Name...'.esc_attr($placeholder_text).'" value="' . esc_attr(get_search_query()) . '" name="s" />
    <button type="submit" class="fa fa-search"></button></div>';
    
    if(is_page(533)){
        $form .= '<div class="header-search">
        <a href="' . home_url() . '/upload" class="btn btn-blue"><i class="fa fa-plus-circle"></i>add your own jrrny</a>
        <button type="submit" class="btn btn-red"><i class="fa fa-search"></i>Search</button>
        </div>';        
    }
    $form .= '</form>';

    return $form;
}


/*-----------------------------------------------------------------------------------*/
/* Get coordinates and save as transient */
/* http://pippinsplugins.com/simple-google-maps-short-code */
/*-----------------------------------------------------------------------------------*/
function aq_get_map_coordinates($address, $force_refresh = false ) {

    $address_hash = md5( $address );
    $coordinates = get_transient( $address_hash );

    if ($force_refresh || $coordinates === false) {

        $args       = array('address' => urlencode( $address ), 'sensor' => 'false');
        $url        = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
        $response   = wp_remote_get( esc_url($url) );

        if( is_wp_error( $response ) )
            return;

        $data = wp_remote_retrieve_body( $response );

        if( is_wp_error( $data ) )
            return;

        if ( $response['response']['code'] == 200 ) {

            $data = json_decode( $data );

            if ( $data->status === 'OK' ) {

                $coordinates = $data->results[0]->geometry->location;

                $cache_value['lat']     = $coordinates->lat;
                $cache_value['lng']     = $coordinates->lng;
                $cache_value['address'] = (string) $data->results[0]->formatted_address;

                // cache coordinates for 3 months
                set_transient($address_hash, $cache_value, 3600*24*30*3);
                $data = $cache_value;

            } elseif ( $data->status === 'ZERO_RESULTS' ) {
                return __( 'No location found for the entered address.', 'ThemeStockyard' );
            } elseif( $data->status === 'INVALID_REQUEST' ) {
                return __( 'Invalid request. Did you enter an address?', 'ThemeStockyard' );
            } else {
                return __( 'Something went wrong while retrieving your map, please ensure you have entered the shortcode correctly.', 'ThemeStockyard' );
            }

        } else {
            return __( 'Unable to contact Google API service.', 'ThemeStockyard' );
        }

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
}

/*
 * Converts multiple line breaks to '<p>[blah,blah]</p>'
 */
function ts_nl2p($str, $open_and_close = true, $class = false)
{
    $class = ($class) ? $class : '';
    $new_str = preg_replace('/<br \\/>\s*<br \\/>/', '</p>\n<p class="'.esc_attr($class).'">', nl2br($str));
    return ($open_and_close) ? '<p class="'.esc_attr($class).'">' . $new_str . '</p>' . "\n" : $new_str;
}

/*
 * Converts multiple "br" tags to "\n"
 */
function ts_br2nl($str)
{
    return preg_replace('/<br(.+?)>/', "", preg_replace('/<br(.+?)>\s*<br(.+?)>/', "\n\n", $str));
}

/*
 * Converts multiple line breaks to '<li>[blah,blah]</li>'
 */
function ts_nl2li($str, $open_and_close = true, $class = false)
{
    $class = ($class) ? ' class="'.esc_attr($class).'"' : '';
    $str_array = explode('*', $str);
    $list = '';
    foreach($str_array AS $str)
    {
        $new_str = trim(preg_replace('/<br \\/>\s*/', "", nl2br($str)));
        $list .= ($new_str) ? '<li'.$class.'>'.$new_str.'</li>'."\n" : '';
    }
    return ($open_and_close) ? '<li'.$class.'>' . $list . '</li>' . "\n" : $list;
}

/*
 * Tests for a valid email address and optionally tests for valid MX records, too.
 */
function ts_valid_email($email, $test_mx = false)
{
    if(eregi("^([_a-z0-9+-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
    {
        if($test_mx)
        {
            list($username, $domain) = split("@", $email);
            getmxrr($domain, $mxrecords);
            return (count($mxrecords) > 0) ? true : false;
        }
        else
            return true;
    }
    else
        return false;
}

function ts_slugify($str = '')
{
    $str = preg_replace('/[^a-zA-Z0-9 -_]/', '', trim($str));
    $str = strtolower(str_replace(' ', '-', trim($str)));
    $str = preg_replace('/-+/', '-', $str);
    return $str;
}

/*
 * Takes a (usually user-defined) fontawesome name and converts it to its proper fontawesome class
 */
function ts_fontawesome_class($icon, $default = '')
{
    $icon = (trim($icon)) ? trim($icon) : $default;

    if(ts_starts_with($icon, 'fa-'))
        $icon = substr($icon, 3);
    elseif(ts_starts_with($icon, 'icon-'))
        $icon = substr($icon, 5);

    return (trim($icon)) ? 'fa fa-'.$icon : '';
}

function ts_divide_list_into_columns($content = '', $columns = 1, $arrange_columns = 'vertical', $ul_class = 'list-shortcode')
{
    $output = '';
    $columns = ($columns > 4) ? 4 : $columns;
    $ul_class = esc_attr($ul_class);

    if(is_array($content))
    {
        if($columns < 2)
        {
            $output .= "\n".'<ul class="'.esc_attr($ul_class).'">'.implode('', $content).'</ul>';
        }
        else
        {
            $count = count($content);
            $base_count_per_column = floor($count / $columns);
            $high_count_per_column = ceil($count / $columns);
            $remainder = $og_remainder = $count - ($base_count_per_column * $columns);

            $column_class = 'ts-1-of-'.$columns;

            if($arrange_columns == 'vertical')
            {
                $columns_array = array();
                for($i = 0; $i < $columns; $i++)
                {
                    $columns_array[$i] = '';
                }

                $j = 0;
                $k = 0;
                foreach($content AS $item)
                {
                    if(!trim($item)) continue;

                    $count_per_column = ($remainder) ? $high_count_per_column : $base_count_per_column;
                    $columns_array[$k] .= "\t".$item."\n";
                    if($j == ($count_per_column - 1))
                    {
                        $j = 0;
                        $k++;
                        $remainder = ($remainder) ? $remainder - 1 : 0;
                    }
                    else
                    {
                        $j++;
                    }
                }

                $l = 1;
                foreach($columns_array AS $column)
                {
                    $column_class = ($l == $columns) ? $column_class.' ts-column-last' : $column_class;
                    $output .= "\n".'<ul class="'.esc_attr($ul_class).' '.esc_attr($column_class).'">'."\n".$column.'</ul>';
                    $l++;
                }

            }
            else
            {
                $columns_array = array();
                for($i = 0; $i < $columns; $i++)
                {
                    $columns_array[$i] = '';
                }

                $j = 0;
                foreach($content AS $item)
                {
                    if(!trim($item)) continue;

                    $count_per_column = $high_count_per_column;
                    $columns_array[$j] .= "\t".$item."\n";
                    if($j == ($columns - 1))
                    {
                        $j = 0;
                    }
                    else
                    {
                        $j++;
                    }
                }

                $l = 1;
                foreach($columns_array AS $column)
                {
                    $column_class = ($l == $columns) ? $column_class.' ts-column-last' : $column_class;
                    $output .= "\n".'<ul class="'.esc_attr($ul_class).' '.esc_attr($column_class).'">'."\n".$column.'</ul>';
                    $l++;
                }
            }
            $output .= "\n".'<div class="clear"></div>'."\n";
        }
    }
    else
    {
        //$output .= $content;
        $output .= "\n".'<ul class="'.esc_attr($ul_class).'">'.$content.'</ul>';
    }

    return $output;
}

/**
 * this is mainly used for when users upgrade the theme by changing the theme directory
 * to something like [themename]-[version#]. theme files that were saved into the theme options
 * may still have the original/outdated uri.
 */
function ts_correct_image_uri($uri = '')
{
    if(preg_match("/(wp\-content\/themes\/).*(".TS_THEMENAME.").*(\/images\/).*(gif|jpg|png)/i", $uri))
    {
        $filename = pathinfo($uri, PATHINFO_BASENAME);
        return get_template_directory_uri().'/images/'.$filename;
    }
    elseif(ts_starts_with($uri, '[theme_directory]'))
    {
        return strip_tags(do_shortcode($uri));
    }
}

function ts_contains_word($haystack, $needle, $ignore_case = false)
{
    if($ignore_case) :
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
    endif;

    return (strpos($haystack, $needle) !== false) ? true : false;
}

function ts_starts_with($haystack, $needle)
{
    if(is_array($needle))
    {
        foreach($needle AS $need)
        {
            $length = strlen($need);
            $result = (substr($haystack, 0, $length) === $need);
            if($result === true)
            {
                return true;
                break;
            }
        }
    }
    else
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

function ts_ends_with($haystack, $needle)
{
    if(is_array($needle))
    {
        foreach($needle AS $need)
        {
            $length = strlen($needle);
            $start  = $length * -1; //negative
            $result = (substr($haystack, $start) === $needle);
            if($result === true)
            {
                return true;
                break;
            }
        }
    }
    else
    {
        $length = strlen($needle);
        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }
}


function ts_strip_shortcode_tags($content = '')
{
    return preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);
}

/*
 * truncate text/html.
 * keeps html formatting in place, accounts for html/utf8 entities, closes unfinsished html.
 *
 * $s => string to be cut
 * $l => desired length
 * $e => ending/suffix
 * $allow_html => boolean (self explanatory)
 * $allowed_tags => eg. '<strong><a><em>'
 * $allow_shortcodes => boolean (self explanatory)
 */
function ts_truncate($s, $l, $e = '&hellip;', $allow_html = false, $allowed_tags = 'simple', $allow_shortcodes = true)
{
    // account for WordPress shortcodes...
    $s = ($allow_shortcodes && function_exists('do_shortcode')) ? do_shortcode($s) : $s;
    // shortcut to only output a few inline tags
    $allowed_tags = ($allowed_tags == 'simple') ? '<strong><b><i><br><em><a><u><strike><del><acronym><abbr><sup><sub>' : $allowed_tags;
    // allow html? if so, anything specific?
    $s = ($allow_html) ? (($allowed_tags) ? strip_tags($s, $allowed_tags) : $s) : strip_tags($s);
    // remove line breaks, and merge multiple spaces into one (so that we don't count them)...
    $s = trim(preg_replace('/\s{2,}/', ' ', preg_replace('/\r?\n|\r/', ' ', $s)));
    $e = (mb_strlen(strip_tags($s)) > $l) ? $e : '';
    $i = 0;
    $tags = array();

    // account for html/utf8 entities
    $temp_s = strip_tags($s);
    preg_match_all("/&#?[a-zA-Z0-9]{1,7};|[\x80-\xFF][\x80-\xBF]*/", $temp_s, $entities, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    if(is_array($entities) && count($entities))
    {
        foreach($entities AS $entity)
        {
            if($entity[0][1] - $i >= $l)
            {
                break;
            }
            $i = $i + mb_strlen($entity[0][0]) - 1;
        }
    }

    if($allow_html)
    {
        preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach($m as $o)
        {
            if($o[0][1] - $i >= $l)
            {
                break;
            }
            $t = mb_substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
            // don't add the following to tags that will need to be closed:
            // already closed tags, self-closing tags (with ending slash), comment tags
            if($t[0] != '/' && mb_substr($t, -1) != '/' && mb_substr($t, 0, 3) != '!--')
            {
                // and then for self-closing tags *without* ending slashes...
                $self_closing = array('area','base','br','col','embed','hr','img','input','keygen','link','menuitem','meta','param','source','track','wbr');
                if(!in_array($t, $self_closing))
                    $tags[] = $t;
            }
            elseif(end($tags) == mb_substr($t, 1))
            {
                array_pop($tags);
            }
            $i += $o[1][1] - $o[0][1];
        }
    }

    $output = trim(mb_substr($s, 0, $l = min(mb_strlen($s), $l + $i))) . (count($tags = array_reverse($tags)) ? $e . '</' . implode('></', $tags) . '>' : $e);

    //uncomment next line to debug
    //$output .= "\n". (isset($m) ? '<pre>'.print_r($m, true).'</pre><br/>'."\n" : '').'HTML/Entity characters: '.$i."<br/>\n".'Total characters kept: '.$l;

    return $output;
}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string
 */
function ts_trim_text($input, $length, $ellipses = true, $strip_html = true)
{
    $length = (is_numeric($length)) ? $length : 100;

    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags(do_shortcode($input));
    }

    //no need to trim, already shorter than trim length
    if (mb_strlen($input) <= $length)
        return $input;

    // first we see if there are any encoded characters
    $num = 0;
    $t = mb_substr($input, 0, $length);
    preg_match_all("/&#?[a-zA-Z0-9]{1,7};/", $t, $specials);
    if(is_array($specials) && count($specials[0])) {
        $specials = $specials[0];
        foreach($specials AS $special) {
            $length = $length + strlen($special);
        }
    }

    // we do this again since we have now allowed for special chars
    if (mb_strlen($input) <= $length)
        return $input;

    //find last space within length
    $last_space = mb_strrpos(mb_substr($input, 0, $length), ' ');
    $trimmed_text = mb_substr($input, 0, $last_space);
    $trimmed_text = ($trimmed_text) ? $trimmed_text : mb_substr($input, 0, $length);

    return ($ellipses) ? $trimmed_text . '...' : $trimmed_text;
}

/*
 * Wrapper for ts_truncate() and ts_trim_text()
 * Takes the 'allow_html_excerpts' setting from Theme Options
 * and chooses the appropriate function based on that setting
 */
function ts_truncate_trim($input, $length)
{
    $allow_html = (ts_option_vs_default('allow_html_excerpts', 0) == 1) ? true : false;

    $output = ($allow_html) ? ts_truncate($input, $length, '&hellip;', true) : ts_trim_text($input, $length);

    return $output;
}

/*
 * Similar to ts_trim_text, but will automatically fetch the excerpt of current post if none is provided.
 */
function ts_max_charlength($charlength, $text = null)
{
    $excerpt = ($text) ? $text : get_the_excerpt();
    $charlength++;
    echo (mb_strlen($excerpt) > $charlength) ? ts_trim_text($excerpt, $charlength) : $excerpt;
}

/*
 * Convert an array to object. There are easier ways to do this now... do we still need this?
 */
function ts_array2object($array)
{
	if(!is_array($array))
		return $array;

	$object = new stdClass();
	if(is_array($array) && count($array) > 0)
	{
        foreach ($array as $name=>$value)
        {
            $name = strtolower(trim($name));
            if(!empty($name))
                $object->$name = ts_array2object($value);
        }
        return $object;
	}
    else
      return false;
}

/*
 * Covert hex color code to RGB array
 * Taken from: http://css-tricks.com/snippets/php/convert-hex-to-rgb/
 */
function ts_hex2rgb($hex, $return = 'array')
{
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);

   return ($return == 'string') ? implode(", ", $rgb) : $rgb;
}

function ts_rgb2hex($rgb, $include_hex = true)
{
   $hex = ($include_hex) ? "#" : '';
   $rgb = (is_array($rgb)) ? $rgb : (($rgb && is_string($rgb)) ? explode(',', $rgb) : array());

   $red = (isset($rgb['red'])) ? $rgb['red'] : $rgb[0];
   $green = (isset($rgb['green'])) ? $rgb['green'] : $rgb[1];
   $blue = (isset($rgb['blue'])) ? $rgb['blue'] : $rgb[2];

   $hex .= str_pad(dechex(trim($red)), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex(trim($green)), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex(trim($blue)), 2, "0", STR_PAD_LEFT);

   return $hex;
}

function ts_small_nav_social()
{

    $output  = '<p class="social social-fa-icons">';
    $output .= ts_output_social_icon('facebook');
    $output .= ts_output_social_icon('twitter');
    $output .= ts_output_social_icon('pinterest');
    $output .= ts_output_social_icon('google_plus');
    $output .= ts_output_social_icon('github');
    $output .= ts_output_social_icon('linkedin');
    $output .= ts_output_social_icon('instagram');
    $output .= ts_output_social_icon('flickr');
    $output .= ts_output_social_icon('youtube');
    $output .= ts_output_social_icon('vimeo');
    $output .= ts_output_social_icon('vk');
    $output .= ts_output_social_icon('tumblr');
    $output .= ts_output_social_icon('behance');
    $output .= ts_output_social_icon('dribbble');
    $output .= ts_output_social_icon('soundcloud');
    $output .= ts_output_social_icon('rss');
    $output .= '</p>';

    return $output;
}


function ts_output_social_header_pocket()
{
    $output  = '<p class="social social-fa-icons">';
    $output .= ts_output_social_icon('facebook');
    $output .= ts_output_social_icon('twitter');
    $output .= ts_output_social_icon('pinterest');
    $output .= ts_output_social_icon('google_plus');
    $output .= ts_output_social_icon('github');
    $output .= ts_output_social_icon('linkedin');
    $output .= ts_output_social_icon('instagram');
    $output .= ts_output_social_icon('flickr');
    $output .= ts_output_social_icon('youtube');
    $output .= ts_output_social_icon('vimeo');
    $output .= ts_output_social_icon('vk');
    $output .= ts_output_social_icon('tumblr');
    $output .= ts_output_social_icon('behance');
    $output .= ts_output_social_icon('dribbble');
    $output .= ts_output_social_icon('soundcloud');
    $output .= ts_output_social_icon('rss');
    $output .= '</p>';

    return $output;
}

function ts_output_social_icon($arg, $preset = '', $override = false)
{
    $orig_arg = $arg;
    $social_icon_style = ts_option_vs_default('social_icon_style', 'fontawesome');
    $arg = 'social_url_'.$arg;
    $val = ($override !== false) ? $override : ts_option_vs_default($arg, $preset);
    if(trim($val)) :
        $url = $val;
        if($orig_arg == 'rss' && ($preset == '[rss_url]' || $url == '[rss_url]')) :
            $override = '[rss_url]';
            $url = ts_get_feed_url($override);
        endif;

        $orig_arg = ($orig_arg == 'google_plus') ? 'google-plus' : $orig_arg;
        $orig_arg = ($orig_arg == 'vimeo') ? 'vimeo-square' : $orig_arg;

        return '<a href="'.esc_url($url).'" class="icon-style" target="_blank"><i class="fa fa-'.esc_attr($orig_arg).'"></i></a>';
    else :
        return '';
    endif;
}

function ts_get_feed_url($override = '')
{
    if(trim($override)) {
        if(in_array($override, array('[rss]', '[rss_url]')))
            return get_bloginfo('rss2_url');
        else
            return $override;
    }

    return get_bloginfo('rss2_url');
}

function ts_main_container_wrap_class($prefix = '')
{
    global $ts_page_id;

    $prefix_options = array(
        'p',
        'page',
        'portfolio',
    );

    $prefix = (in_array($prefix, $prefix_options)) ? $prefix : $prefix_options[0];

    $content_padding_options = array(
        'no_padding' => 'no-top-padding no-bottom-padding',
        'no_top_padding' => 'no-top-padding',
        'no_bottom_padding' => 'no-bottom-padding',
    );

    $content_padding = ts_postmeta_vs_default($ts_page_id, '_'.$prefix.'_content_padding', '');
    $content_padding = (isset($content_padding_options[$content_padding])) ? $content_padding_options[$content_padding] : '';

    return $content_padding;
}

function ts_main_div_class($section = '')
{
    global $ts_show_sidebar, $ts_sidebar_position, $ts_is_woocommerce, $ts_is_woocommcerce_page;

    $classes  = array();

    $classes[] = ($ts_show_sidebar == 'yes' && in_array($ts_sidebar_position, array('left','right'))) ? 'has-sidebar' : 'no-sidebar';
    $classes[] = ($ts_show_sidebar == 'yes') ? 'has-sidebar-'.$ts_sidebar_position : 'has-no-sidebar '.$ts_show_sidebar;

    // woocommerce class adjustment...
    if(isset($ts_is_woocommerce) && $ts_is_woocommerce == true)
        $classes[] = 'woocommerce';

    if(isset($ts_is_woocommerce_page) && $ts_is_woocommerce_page == true)
        $classes[] = 'woocommerce-page';

    $classes = implode(' ', $classes);

    return $classes;
}

function ts_single_post_wrap_class($sharing_position = 'top', $media = '')
{
    return (!trim($media) && $sharing_position != 'top') ? 'ts-post-wrap-collapse-whitespace' : '';
}

function ts_single_comments_wrap_wrap_class()
{
    global $ts_show_sidebar, $ts_sidebar_position;

    return ($ts_show_sidebar == 'no' || strpos($ts_sidebar_position, 'comments') === false) ? 'ts-post-section' : '';
}

function ts_single_comments_wrap_class()
{
    global $ts_show_sidebar, $ts_sidebar_position;

    return ($ts_show_sidebar == 'no' || strpos($ts_sidebar_position, 'comments') === false) ? 'ts-post-section-inner' : 'ts-post-section';
}

function ts_post_sharing_class()
{
    global $post;

    $ts_sharing_position_option = ts_option_vs_default('sharing_options_position_on_post', 'top');
    $ts_sharing_position = ts_postmeta_vs_default($post->ID, '_p_sharing_options_position', $ts_sharing_position_option);

    return 'sharing-pos-'.$ts_sharing_position;
}

/*
 * Outputs the footer widgets (if option is checked in your settings)
 */
function ts_footer_widgets()
{
    if(ts_option_vs_default('show_footer_widgets', 1) == 1 && ts_option_vs_default('footer_layout', 'footer4')) :
        echo '<div id="footer-wrap" class="footer-jrrny">'."\n";
        echo '<div id="footer" class="footer-widgets no-padding footer-custom">'."\n";
        echo '<div class="row">'."\n";

        switch (ts_option_vs_default('footer_layout', 'footer4')) {

            case "footer1":
                get_template_part('includes/_footer/footer1');
                break;

            case "footer2":
                get_template_part('includes/_footer/footer2');
                break;

            case "footer3":
                get_template_part('includes/_footer/footer3');
                break;

            case "footer4":
                get_template_part('includes/_footer/footer4');
                break;

            case "footer5":
                get_template_part('includes/_footer/footer5');
                break;

            case "footer6":
                get_template_part('includes/_footer/footer6');
                break;

            case "footer7":
                get_template_part('includes/_footer/footer7');
                break;

            case "footer8":
                get_template_part('includes/_footer/footer8');
                break;

        }

        echo '</div>'."\n";
        echo '</div>'."\n";
        echo '</div>'."\n";
        echo ts_get_bottom_ad();
    endif;
}


/*
 * Outputs the Bottom Ad
 */
function ts_get_bottom_ad()
{
    global $ts_page_id;

    $prefix = (is_page($ts_page_id)) ? '_page_' : '_p_';

    $show_bottom_ad = (ts_option_vs_default('show_bottom_ad', 1) == 1) ? 'yes' : 'no';
    $show_bottom_ad = ts_postmeta_vs_default($ts_page_id, $prefix.'show_bottom_ad', $show_bottom_ad);

    if(ts_attr_is_true($show_bottom_ad)) :

        $bottom_ad = ts_option_vs_default('bottom_ad_code', '');
        $bottom_ad = do_shortcode(ts_postmeta_vs_default($ts_page_id, $prefix.'bottom_ad', $bottom_ad));

        return (trim($bottom_ad)) ? '<div id="bottom-ad"><div id="bottom-ad-inner" class="container text-center">'.$bottom_ad.'</div></div>'."\n" : '';
    endif;
}


/*
 * Outputs the Bottom Ad separator
 */
function ts_bottom_ad_widgets_sep($show = true)
{
    if($show === false) return;

    global $ts_page_id;

    $prefix = (is_page($ts_page_id)) ? '_page_' : '_p_';

    $show_bottom_ad = (ts_option_vs_default('show_bottom_ad', 1) == 1) ? 'yes' : 'no';
    $show_bottom_ad = ts_postmeta_vs_default($ts_page_id, $prefix.'show_bottom_ad', $show_bottom_ad);

    if(ts_attr_is_true($show_bottom_ad) && ts_option_vs_default('show_footer_widgets', 1) == 1) :

        $html  = '<div id="bottom-ad-widgets-sep" class="container text-center">';
        $html .= do_shortcode('[divider style="single" padding_bottom="0" padding_top="0"]');
        $html .= '</div>'."\n";

        return $html;
    endif;
}

function ts_custom_comment_list($comment, $args, $depth) {

    $GLOBALS['comment'] = $comment;

    switch ($comment->comment_type) :
        case 'pingback'  :
        case 'trackback' :
        ?>
            <li class="pingback">
                <p>
                    <?php _e('Pingback:', 'ThemeStockyard'); ?>
                    <?php comment_author_link(); ?>
                    <?php edit_comment_link(__('(Edit)', 'ThemeStockyard'), ' ' ); ?>
                </p>
            </li>
            <?php
            break;

        default :
        ?>
            <li <?php comment_class('clearfix'); ?> id="comment-<?php comment_ID(); ?>">
                <div class="comment-inner-wrap clearfix">

                    <div class="comment-avatar">
                        <?php echo get_avatar( $comment, 50 ); ?>
                    </div><!-- end div .comment-avartar -->

                    <div class="comment-content-wrapper clearfix">

                        <div class="comment-head small">
                            <span class="comment-author"><?php echo get_comment_author_link(); ?></span>

                            <span class="comment-date"><?php echo get_comment_date().', '.get_comment_time() ?></span>
                            <?php
                            $comment_reply_link_before = '<span class="sep">&bull;</span><span class="comment-reply">';
                            $comment_reply_link_after = '</span>';
                            $comment_reply_link_args = array(
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                                'before'    => $comment_reply_link_before,
                                'after'     => $comment_reply_link_after
                            );
                            comment_reply_link(array_merge($args, $comment_reply_link_args));
                            ?>
                            <?php edit_comment_link(__('Edit', 'ThemeStockyard'), ' <span class="sep">&bull;</span> <span class="comment-edit">', '</span>');?>
                        </div><!-- end div .comment-head -->
                    </div><!-- end div.comment-authors -->
                    <div class="clear"></div>
                    <div class="comment-message">
                        <?php comment_text(); ?>
                    </div><!-- end div .comment-message -->
                </div>
            <!--</li>-->
            <?php
            break;
    endswitch;

}

/**
 * Title         : Aqua Resizer
 * Description   : Resizes WordPress images on the fly
 * Version       : 1.1.7
 * Author        : Syamil MJ
 * Author URI    : http://aquagraphite.com
 * License       : WTFPL - http://sam.zoy.org/wtfpl/
 * Documentation : https://github.com/sy4mil/Aqua-Resizer/
 *
 * @param string  $url    - (required) must be uploaded using wp media uploader
 * @param int     $width  - (required)
 * @param int     $height - (optional)
 * @param bool    $crop   - (optional) default to soft crop
 * @param bool    $single - (optional) returns an array if false
 * @uses  wp_upload_dir()
 * @uses  wp_get_image_editor()
 *
 * @return str|array
 */

function aq_resize( $url = '', $width = null, $height = null, $crop = null, $single = true, $upscale = false, $id = null )
{
    $original_url = $url;

	// Validate inputs.
	if (( ! $width && ! $height ) || (!$url && !$id)) return false;

	if($id && !$url)
	{
        $image_data = wp_get_attachment_image_src($id, 'large');
        $url = $original_url = $image_data[0];

        if(!$url) return false;
	}

	// slight detour... see if Jetpack's Photon is being used...
	$photon_active = false;
	if(preg_match('#^https?://i[0-9]{1,3}\.wp\.com#', $url))
        $photon_active = true;

	if(class_exists('Jetpack') && method_exists('Jetpack', 'get_active_modules') && in_array('photon', Jetpack::get_active_modules()))
        $photon_active = true;

    if($photon_active)
    {
        // remove query string
        $newurl = current(explode('?', $url));

        // create query string
        if($width && $height)
            $qs = '?resize='.$width.','.$height;
        elseif($width && !$height)
            $qs = '?w='.$width;
        elseif(!$width && $height)
            $qs = '?h='.$height;
        else
            $newurl = 'old';

        $url = ($newurl == 'old') ? $url : $newurl . $qs;

        if($single)
            $image = $url;
        else {
            if(!$width || !$height) {
                list($width, $height) = getimagesize($url);
            }

            $image = array (
                0 => $url,
                1 => $width,
                2 => $height
            );
        }

        return $image;
    }



	// Caipt'n, ready to hook.
	if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'aq_upscale', 10, 6 ); // ignore themecheck - not a deprecated function/hook

	if(ts_option_vs_default('sharpen_resized_images', 1) == 1) {
        add_filter('image_make_intermediate_size', 'ts_sharpen_resized_files', 900);
	}

	// Define upload path & dir.
	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];

	if(substr($upload_url, 0, 5) == 'http:' && substr($url, 0, 6) == 'https:')
	{
        $url = 'http:'.substr($url, 6);
	}

	// Check if $img_url is local.
	if ( false === strpos( $url, $upload_url ) ) return false;

	// Define path of image.
	$rel_path = str_replace( $upload_url, '', $url );
	$img_path = $upload_dir . $rel_path;

	// Check if img path exists, and is an image indeed.
	if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

	// Get image info.
	$info = pathinfo( $img_path );
	$ext = $info['extension'];
	list( $orig_w, $orig_h ) = getimagesize( $img_path );

	// Get image size after cropping.
	$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
	$dst_w = $dims[4];
	$dst_h = $dims[5];

	// added by Jarrod:
	// if error occurs, return original image. This more closely resembles WordPress's default behavior when resizing.
	$error_img = ($single) ? $url : array($url, $orig_w, $orig_h);

	// Return the original image only if it exactly fits the needed measures.
	if ( ( ( ! $height && $orig_w == $width ) xor ( ! $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) {
		$img_url = $original_url;
		$dst_w = $orig_w;
		$dst_h = $orig_h;
	} else {
		// Use this to check if cropped image already exists, so we can return that instead.
		$suffix = "{$dst_w}x{$dst_h}";
		$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
		$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

		if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
			// Can't resize, so return false saying that the action to do could not be processed as planned.
			//return false;
			return $error_img;
		}
		// Else check if cache exists.
		elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
			$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
		}
		// Else, we resize the image and return the new resized image url.
		else {

            $editor = wp_get_image_editor( $img_path );

            if(is_wp_error($editor)) {
                $err_img = '';
                if($id) {
                    $seek_thumb_size_name = ts_figure_thumb_name($width, $height);
                    $err_img = wp_get_attachment_image_src($id, $seek_thumb_size_name);
                    if(is_array($err_img) && isset($err_img[0])) {
                        $err_img = ($single) ? $err_img[0] : array($err_img[0], $err_img[1], $err_img[2]);
                    } else {
                        $err_img = '';
                    }
                }
                return (is_array($err_img)) ? $err_img : $error_img;
            }
            else {
                $editor->set_quality(100);
            }

            if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) ) {
                //return false;
                return $error_img;
            }

            $resized_file = $editor->save();

            if ( ! is_wp_error( $resized_file ) ) {
                $resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
                $img_url = $upload_url . $resized_rel_path;

                // right here is where we need to add postmeta to associate any newly created images with
                // the original attachment id (if one was passed into the function)
                if($id) {
                    $resized_versions = get_post_meta($id, '_aq_resize_versions', true);
                    $resized_versions = maybe_unserialize($resized_versions);
                    if(!is_array($resized_versions)) {
                        $resized_versions = array();
                    }

                    if(!in_array($resized_rel_path, $resized_versions)) {
                        $resized_versions[] = $resized_rel_path;
                        $resized_versions['last-update'] = date('Y-m-d H:i:s');
                        $resized_versions = maybe_serialize($resized_versions);
                        delete_post_meta( $id, '_aq_resize_versions');
                        update_post_meta( $id, '_aq_resize_versions', $resized_versions );
                    }
                }
            } else {
                //return false;
                return $error_img;
            }

		}
	}

	// Okay, leave the ship.
	if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'aq_upscale' ); // ignore themecheck - not a deprecated function/hook

    // ssl cleanup
    if(is_ssl() && substr($img_url, 0, 5) == 'http:') {
        $img_url = 'https:'.substr($img_url, 5);
    }

	// Return the output.
	if ( $single ) {
		// str return.
		$image = $img_url;
	} else {
		// array return.
		$image = array (
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}

	return $image;
}

function ts_aq_resized_images_removal($id)
{
    $upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];

    $resized_versions = get_post_meta($id, '_aq_resize_versions', true);
    $resized_versions = maybe_unserialize($resized_versions);
    if(is_array($resized_versions)) {
        foreach($resized_versions AS $file) {
            $rel_path = str_replace( $upload_url, '', $file );
            $img_path = $upload_dir . $rel_path;
            unlink($img_path);
        }
    }
}


function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
	if ( ! $crop ) return null; // Let the wordpress default function handle this.

	// Here is the point we allow to use larger image size than the original one.
	$aspect_ratio = $orig_w / $orig_h;
	$new_w = $dest_w;
	$new_h = $dest_h;

	if ( ! $new_w ) {
		$new_w = intval( $new_h * $aspect_ratio );
	}

	if ( ! $new_h ) {
		$new_h = intval( $new_w / $aspect_ratio );
	}

	$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

	$crop_w = round( $new_w / $size_ratio );
	$crop_h = round( $new_h / $size_ratio );

	$s_x = floor( ( $orig_w - $crop_w ) / 2 );
	$s_y = floor( ( $orig_h - $crop_h ) / 2 );

	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

function ts_sharpen_resized_files( $resized_file )
{

	//$file_contents = wp_remote_retrieve_body(wp_remote_get($resized_file));

	$image = imagecreatefromstring( file_get_contents( $resized_file ) ); // safe!
	//$image = imagecreatefromstring( $file_contents ); // safe!

	$size = @getimagesize( $resized_file );
	if ( !$size )
		return new WP_Error('invalid_image', __('Could not read image size','ThemeStockyard'), $resized_file);
	list($orig_w, $orig_h, $orig_type) = $size;

	switch ( $orig_type ) {
		case IMAGETYPE_JPEG:
			$matrix = array(
				array(apply_filters('sharpen_resized_corner',-1), apply_filters('sharpen_resized_side',-1), apply_filters('sharpen_resized_corner',-1)),
				array(apply_filters('sharpen_resized_side',-1), apply_filters('sharpen_resized_center',24), apply_filters('sharpen_resized_side',-1)),
				array(apply_filters('sharpen_resized_corner',-1), apply_filters('sharpen_resized_side',-1), apply_filters('sharpen_resized_corner',-1)),
			);

			$divisor = array_sum(array_map('array_sum', $matrix));
			$offset = 0;
			imageconvolution($image, $matrix, $divisor, $offset);
			imagejpeg($image, $resized_file,apply_filters( 'jpeg_quality', 99, 'edit_image' ));
			break;
		case IMAGETYPE_PNG:
			return $resized_file;
		case IMAGETYPE_GIF:
			return $resized_file;
	}

	// we don't need images in memory anymore
	imagedestroy( $image );

	return $resized_file;
}

function ts_figure_thumb_name($width = null, $height = null)
{
    if($width && !$height)
        return 'thumb_'.$width;
    elseif($width && $height)
        return 'thumb_'.$width.'x'.$height;
    else
        return 'large';
}


function ts_sharing_options_on_posts($postid = null)
{
    global $post, $ts_page_id;

    $postid = ($postid) ? $postid : (isset($post->ID) ? $post->ID : $ts_page_id);

    $return = array();

    $ts_sharing_options_position_option = ts_option_vs_default('sharing_options_position_on_post', 'top');
    $ts_sharing_options_position = ts_postmeta_vs_default($postid, '_p_sharing_options_position', $ts_sharing_options_position_option);

    $return['position'] = (in_array($ts_sharing_options_position, array('left','right','hidden','top'))) ? $ts_sharing_options_position : 'top';
    $return['position_class'] = ($return['position'] == 'top') ? 'not-pulled' : 'pulled pull-'.$return['position'];
    $return['show'] = ($ts_sharing_options_position == 'hidden') ? false : true;

    return ts_array2object($return);
}


function ts_social_sharing($single = true)
{
    global $smof_data, $post;

    remove_filter('single_post_title', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('wp_title', 'wptexturize');
    
    $html = '';

    $share_text = (get_post_type() == 'portfolio') ? __('Share:', 'ThemeStockyard') : __('Share:', 'ThemeStockyard');

    if($single) :
        // set defaults...
        $title = $facebook_title = $twitter_title = $google_plus_title = get_the_title();
        $link = get_permalink();

        // accommodate for yoast
        if ( class_exists('WPSEO_Meta') && method_exists('WPSEO_Meta', 'get_value')) :
            $social_title = WPSEO_Meta::get_value('title');
            $title = (trim($social_title)) ? $social_title : $title;
            $social_link = WPSEO_Meta::get_value('canonical');
            $link = (trim($social_link)) ? $social_link : $link;

            $_facebook_title = WPSEO_Meta::get_value('opengraph-title');
            $facebook_title = (trim($_facebook_title)) ? $_facebook_title : $title;

            $_twitter_title = WPSEO_Meta::get_value('twitter-title');
            $twitter_title = (trim($_twitter_title)) ? $_twitter_title : $title;

            $_google_plus_title = WPSEO_Meta::get_value('google-plus-title');
            $google_plus_title = (trim($_google_plus_title)) ? $_google_plus_title : $title;
        endif;
    endif;

    $default_sharing_options = array( 'facebook', 'twitter', 'google-plus', 'pinterest', 'tumblr', 'linkedin', 'reddit', 'email', 'print' );

    $sharing_options = ts_option_vs_default('available_sharing_options', $default_sharing_options);

    $html .= ($single) ? '<div class="share-options">' : '';
    $quote_text = 'I just shared an amazing travel experience on jrrny…';
    $html .= (in_array('facebook', $sharing_options)) ? '<a href="'.esc_url('https://www.facebook.com/sharer.php?u='.urlencode($link).'&amp;t='.urlencode($facebook_title)).'&quote='.urlencode($quote_text).'" class="hover-facebook-color share-pop" title="Facebook" rel="nofollow"><i class="fa fa-facebook-square facebook-color"></i><span>Facebook</span></a>' : '';

    $html .= (in_array('twitter', $sharing_options)) ? '<a href="'.esc_url('https://twitter.com/home?status='.urlencode($twitter_title.' '.$link)).'" class="hover-twitter-color share-pop" title="Twitter" rel="nofollow"><i class="fa fa-twitter-square twitter-color"></i><span>Twitter</span></a>' : '';

    $html .= (in_array('google-plus', $sharing_options)) ? '<a href="'.esc_url('https://plus.google.com/share?url='.urlencode($link).'&amp;title='.urlencode($google_plus_title)).'" class="hover-gplus-color share-pop" title="Google+" rel="nofollow"><i class="fa fa-google-plus-square gplus-color"></i><span>Google+</span></a>' : '';

    if(is_object($post) && isset($post->ID) && has_post_thumbnail())
    {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'large');
        if(!empty($image[0]))
        {
            $html .= (in_array('pinterest', $sharing_options)) ? '<a href="'.esc_url('http://pinterest.com/pin/create/button/?url='.urlencode($link).'&amp;description='.urlencode($title)).'&amp;media='.urlencode($image[0]).'" class="hover-pinterest-color share-pop" title="Pinterest" rel="nofollow"><i class="fa fa-pinterest-square pinterest-color"></i><span>Pinterest</span></a>' : '';
        }
    }

    $html .= (in_array('vk', $sharing_options)) ? '<a href="'.esc_url('http://vk.com/share.php?url='.urlencode($link).'&amp;name='.urlencode($title)).'" class="hover-vk-color share-pop" title="VK" rel="nofollow"><i class="fa fa-vk vk-color"></i><span>VK</span></a>' : '';

    $html .= (in_array('tumblr', $sharing_options)) ? '<a href="'.esc_url('http://www.tumblr.com/share/link?url='.urlencode($link).'&amp;name='.urlencode($title)).'" class="hover-tumblr-color share-pop" title="Tumblr" rel="nofollow"><i class="fa fa-tumblr-square tumblr-color"></i><span>Tumblr</span></a>' : '';

    $html .= (in_array('linkedin', $sharing_options)) ? '<a href="'.esc_url('http://linkedin.com/shareArticle?mini=true&amp;url='.urlencode($link).'&amp;title='.urlencode($title)).'" class="hover-linkedin-color share-pop" title="LinkedIn" rel="nofollow"><i class="fa fa-linkedin-square linkedin-color"></i><span>LinkedIn</span></a>' : '';

    $html .= (in_array('reddit', $sharing_options)) ? '<a href="'.esc_url('http://www.reddit.com/submit?url='.urlencode($link).'&amp;title='.urlencode($title)).'" class="hover-reddit-color share-pop" title="Reddit" rel="nofollow"><i class="fa fa-reddit-square reddit-color"></i><span>Reddit</span></a>' : '';

    $html .= (in_array('digg', $sharing_options)) ? '<a href="'.esc_url('http://www.digg.com/submit?url='.urlencode($link)).'" class="hover-digg-color share-pop" title="digg" rel="nofollow"><i class="fa fa-digg digg-color"></i><span>digg</span></a>' : '';

    $html .= (in_array('stumbleupon', $sharing_options)) ? '<a href="'.esc_url('http://www.stumbleupon.com/submit?url='.urlencode($link)).'" class="hover-stumbleupon-color share-pop" title="StumbleUpon" rel="nofollow"><i class="fa fa-stumbleupon stumbleupon-color"></i><span>StumbleUpon</span></a>' : '';

    $html .= ($single && in_array('email', $sharing_options)) ? '<a href="'.esc_url('mailto:?subject='.urlencode($title).'&amp;body='.urlencode($link)).'" class="email" title="'.__('Email','ThemeStockyard').'" rel="nofollow"><i class="fa fa-envelope"></i><span>'.__('Email','ThemeStockyard').'</span></a>' : '';

    $html .= ($single && in_array('print', $sharing_options)) ? '<a href="javascript:print()" class="print" title="'.__('Print','ThemeStockyard').'"><i class="fa fa-print"></i><span>'.__('Print','ThemeStockyard').'</span></a>' : '';

    $html .= ($single) ? '</div>' : '';

    return $html;
}

function ts_get_photo_caption($id)
{
    $caption = get_post($id)->post_excerpt;
    return ($caption) ? '<div class="fp-caption-wrap"><div class="fp-caption small">'.$caption.'</div></div>' : '';
}

function ts_sticky_badge($badge = true)
{
    $html = '';

    $wrap_class = ($badge) ? 'bg-primary' : '';
    $icon_class = ($badge) ? 'color-white' : 'color-primary';

    if(is_sticky()) :
        $html .= '<span class="ts-sticky-badge '.esc_attr($wrap_class).'" title="'.esc_attr(__('Featured','ThemeStockyard')).'">';
        $html .= '<i class="fa fa-bookmark '.esc_attr($icon_class).'"></i>';
        $html .= '</span>';
    endif;

    return $html;
}

function ts_loop_media_height($layout, $atts, $width)
{
    $atts = (is_array($atts)) ? $atts : array();

    $layout = preg_replace("/[^0-9a-z]*/", "", strtolower($layout));
    $layout = (ts_starts_with($layout, '4col')) ? '4col' : 'standard';

    $orientation_options = array('3:2','2:3','16:9','9:16','16:10','10:16','1:1');
    $orientation = $orientation_default = ($layout == '4col') ? '1:1' : '3:2';
    $orientation = ts_option_vs_default('image_orientation_'.$layout, $orientation);
    $orientation = (isset($atts['image_orientation']) && $atts['image_orientation']) ? $atts['image_orientation'] : $orientation;

    $orientation = (in_array($orientation, $orientation_options)) ? $orientation : $orientation_default;
    $orientation_array = explode(':', $orientation);

    $size = ts_get_proportional_size($orientation_array[0], $orientation_array[1], $width, null);

    return $size['height'];
}

function ts_display_link_post_format_url($url = '')
{
    global $post;

    $html = '';

    $url = ($url) ? $url : ((is_object($post) && isset($post->ID)) ? ts_postmeta_vs_default($post->ID, '_p_link', '') : '');

    if(trim($url) && get_post_format() == 'link') :
        $html .= '<div class="ts-link-post-format-url-wrap">';
        $html .= '<a href="'.esc_url($url).'"><i class="fa fa-link"></i>'.esc_html($url).'</a>';
        $html .= '</div>';
    endif;

    return $html;
}

function ts_get_featured_media($arg = array())
{
    global $post, $smof_data, $ts_show_sidebar, $ts_page_id, $ts_within_blog_loop;

    $defaults = array(
        'allow_audio' => false,
        'allow_videos' => true,
        'allow_galleries' => true,
    );

    $arg = wp_parse_args($arg, $defaults);

    $html = '';
    $ready = false;
    $within_slider = (isset($arg['within_slider']) && ts_attr_is_true($arg['within_slider'])) ? true : false;
    $is_single_featured_media = (isset($arg['is_single']) && ts_attr_is_true($arg['is_single'])) ? true : false;

    $posttype = get_post_type($post->ID);
    $post_meta_prefix = ($posttype == 'portfolio') ? '_portfolio_' : '_p_';

    if($posttype == 'portfolio') :
        $crop_images = (ts_option_vs_default('crop_images_on_portfolio', 1)) ? true : false;
    else :
        $crop_images = (ts_option_vs_default('crop_images_on_blog', 1)) ? true : false;
    endif;

    $get_alt_text_in_loop = (ts_option_vs_default('featured_image_alt_text_within_loop', 0)) ? true : false;
    $get_alt_text = ($is_single_featured_media || $get_alt_text_in_loop) ? true : false;

    $video_id = $return_link = '';

    if($ts_show_sidebar == 'yes') :
        if($crop_images) :
            $crop_width = 740;
            $crop_height = 480;
        else :
            $crop_width = 740;
            $crop_height = 0;
        endif;
    else :
        if($crop_images) :
            $crop_width = 1100;
            $crop_height = 540;
        else :
            $crop_width = 1100;
            $crop_height = 0;
        endif;
    endif;

    if(isset($arg['media_height']) && $arg['media_height'] !== false && isset($arg['media_width']) && $arg['media_width'] !== false) :
        $crop_width = $arg['media_width'];
        $crop_height = $arg['media_height'];
    endif;

    $seek_thumb_size_name = ($crop_width > 1024) ? 'full' : 'large';    //ts_figure_thumb_name($crop_width, $crop_height);

    $allow_audio = (isset($arg['allow_audio']) && ts_attr_is_true($arg['allow_audio'])) ? true : false;
    $allow_videos = (isset($arg['allow_videos']) && ts_attr_is_false($arg['allow_videos'])) ? false : true;
    $allow_self_hosted_video = (isset($arg['allow_self_hosted_video']) && ts_attr_is_true($arg['allow_self_hosted_video'])) ? true : false;
    $allow_video_embed_code = (isset($arg['allow_video_embed_code']) && ts_attr_is_false($arg['allow_video_embed_code'])) ? false : true;
    $allow_galleries = (isset($arg['allow_galleries']) && ts_attr_is_false($arg['allow_galleries'])) ? false : true;

    if(isset($arg['video_height']) && isset($arg['video_width'])) :
        $video_width = 'width="'.esc_attr($arg['video_width']).'"';
        $video_height = 'height="'.esc_attr($arg['video_height']).'"';
    else :
        $video_width = '';
        $video_height = '';
    endif;

    $audio_class = (isset($arg['audio_class'])) ? $arg['audio_class'] : '';
    $media_class = (isset($arg['media_class'])) ? $arg['media_class'] : '';
    $show_caption = (isset($arg['show_caption']) && $arg['show_caption'] === true) ? true : false;
    $target = (isset($arg['target'])) ? $arg['target'] : '';
            
    $post_format = ($posttype == 'portfolio') ? get_post_meta($post->ID, $post_meta_prefix.'project_type', true) : get_post_format();

    if($allow_videos && in_array($post_format, array('video', 'youtube', 'vimeo', 'self_hosted_video'))) :
        $vine = ''; // not used for now
        $vimeo  = get_post_meta( $post->ID, $post_meta_prefix.'vimeo_id', true );
        $youtube = get_post_meta($post->ID, $post_meta_prefix.'youtube_id', true);
        $self_hosted = get_post_meta($post->ID, $post_meta_prefix.'self_hosted_video', true);
        $embed_code  = get_post_meta($post->ID, $post_meta_prefix.'video_embed_code', true);
        $post_format = ($vimeo || $youtube || $self_hosted || $embed_code) ? 'video' : 'standard';
        if($self_hosted && !$allow_self_hosted_video) :
            $post_format = 'standard';
        endif;
        if($embed_code && !$allow_video_embed_code) :
            $post_format = 'standard';
        endif;
    elseif($allow_galleries && in_array($post_format, array('gallery','slider'))) :
        $post_format = 'gallery';
    elseif($allow_audio && in_array($post_format, array('audio', 'soundcloud', 'spotify', 'self_hosted_audio'))) :
        $soundcloud = get_post_meta( $post->ID, $post_meta_prefix.'soundcloud_id', true);
        $spotify  = get_post_meta( $post->ID, $post_meta_prefix.'spotify_id', true );
        $self_hosted_audio = get_post_meta($post->ID, $post_meta_prefix.'self_hosted_audio', true);
        $post_format = ($soundcloud || $spotify || $self_hosted_audio) ? 'audio' : 'standard';
    else :
        $post_format = 'standard';
    endif;

    $preview = get_post_meta($post->ID, $post_meta_prefix.'preview_image', true);
    $preview_id = get_post_meta($post->ID, $post_meta_prefix.'preview_image_id', true);
    $post_format = ($preview && trim($preview) && $ts_page_id != $post->ID) ? 'standard' : $post_format;

    $class = (isset($arg['class'])) ? $arg['class'] : '';
    $color = str_replace('#', '', ts_option_vs_default('primary_color',''));

    if($post_format == 'video') :
        $html .= ($class) ? '<div class="'.esc_attr($class).'">' : '';
        if($vimeo) :
            $qs_addons = ts_get_video_qs_addons($vimeo, '&amp;');
            $video_id = ts_get_video_id($vimeo);
            $return_link = 'https://vimeo.com/'.$video_id;
            $iframe_id = 'vimeo-'.$video_id.'-'.mt_rand();
            $src_append = ($within_slider) ? '&amp;api=1&amp;player_id='.$iframe_id : '';
            $html .= '<div class="fluid-width-video-wrapper '.esc_attr($media_class).'"><div>
                <iframe id="'.esc_attr($iframe_id).'" src="'.esc_url('https://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color='.$color.$src_append.$qs_addons).'" class="ts-vimeo-player" frameborder="0" webkitAllowFullScreen allowFullScreen '.$video_width.' '.$video_height.'></iframe>
            </div></div>';
        elseif ($self_hosted) :
            $html .= '<div class="fluid-width-video-wrapperx '.esc_attr($media_class).'">
                '.do_shortcode('[video src="'.esc_url($self_hosted).'"]').'
            </div>';
        elseif($vine) :
            $video_id = ts_get_video_id($vine);
            $return_link = 'https://vine.com/v/'.$video_id;
            $html .= '<div class="featured-photo"><p class="fluid-width-video-wrapper '.esc_attr($media_class).'">
                <iframe class="vine-embed" src="'.esc_url('https://vine.co/v/'.ts_get_video_id($vine).'/embed/simple').'" width="600" height="600" frameborder="0"></iframe><script async src="https://platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
            </p></div>';
        elseif($embed_code) :
            $video_id = 'ts-embed-'.mt_rand();
            $return_link = '';
            $html = '<div class="fluid-width-video-wrapper '.esc_attr($media_class).'">'.$embed_code.'</div>';
        elseif($youtube) :
            $qs_addons = ts_get_video_qs_addons($youtube, '&amp;');
            $video_id = ts_get_video_id($youtube);
            $return_link = 'https://www.youtube.com/watch?v='.$video_id;
            $iframe_id = 'youtube-'.$video_id.'-'.mt_rand();
            $src_append = ($within_slider) ? '&amp;enablejsapi=1' : '';
            $html .= '<div class="fluid-width-video-wrapper '.esc_attr($media_class).'">
                <iframe id="'.esc_attr($iframe_id).'" src="'.esc_url('https://www.youtube.com/embed/'.$video_id.'?rel=0'.$src_append.$qs_addons).'" frameborder="0" class="ts-youtube-player" allowfullscreen '.$video_width.' '.$video_height.'></iframe>
            </div>';
        endif;
        $html .= ($class) ? '</div>' : '';
        $ready = true;
    endif;
    if($post_format == 'audio') :
        $html .= ($class) ? '<div class="featured-audio '.esc_attr($class).'">' : '';
        if ($soundcloud) :
            $audio_url = ts_get_audio_embed_url($soundcloud);
            $return_link = $audio_url;
            $html .= '<div class="'.esc_attr($audio_class).' '.esc_attr($media_class).'">'.do_shortcode('[soundcloud url="'.esc_url($soundcloud).'"]').'</div>';
        elseif($spotify) :
            $audio_url = ts_get_audio_embed_url($spotify);
            $return_link = $audio_url;
            $html .= '<div class="'.esc_attr($audio_class).' '.esc_attr($media_class).'">'.do_shortcode('[spotify url="'.esc_url($spotify).'"]').'</div>';
        elseif($self_hosted_audio) :
            $return_link = $audio_url = $self_hosted_audio;
            $html .= '<div class="'.esc_attr($audio_class).' '.esc_attr($media_class).'">'.do_shortcode('[audio src="'.esc_url($self_hosted_audio).'"]').'</div>';
        endif;
        $html .= ($class) ? '</div>' : '';

        $post_format = ($audio_url) ? 'audio' : 'standard';
        $ready = ($audio_url) ? true : false;
    endif;
    if($post_format == 'gallery') :
        $crop_width = (isset($arg['gallery_width'])) ? $arg['gallery_width'] : $crop_width;
        $crop_height = (isset($arg['gallery_height'])) ? $arg['gallery_height'] : $crop_height;

        $gallery_type = (isset($arg['gallery_type']) && $post->ID == $ts_page_id) ? $arg['gallery_type'] : 'slider';

        $photo1_id = get_post_meta( $post->ID, $post_meta_prefix.'image_1_id', true );
        $photo2_id = get_post_meta( $post->ID, $post_meta_prefix.'image_2_id', true );
        $photo3_id = get_post_meta( $post->ID, $post_meta_prefix.'image_3_id', true );
        $photo4_id = get_post_meta( $post->ID, $post_meta_prefix.'image_4_id', true );
        $photo5_id = get_post_meta( $post->ID, $post_meta_prefix.'image_5_id', true );
        $photo6_id = get_post_meta( $post->ID, $post_meta_prefix.'image_6_id', true );
        $photo7_id = get_post_meta( $post->ID, $post_meta_prefix.'image_7_id', true );
        $photo8_id = get_post_meta( $post->ID, $post_meta_prefix.'image_8_id', true );
        $photo9_id = get_post_meta( $post->ID, $post_meta_prefix.'image_9_id', true );
        $photo10_id = get_post_meta( $post->ID, $post_meta_prefix.'image_10_id', true );


        $photo1 = wp_get_attachment_image_src($photo1_id, $seek_thumb_size_name);
        $photo2 = wp_get_attachment_image_src($photo2_id, $seek_thumb_size_name);
        $photo3 = wp_get_attachment_image_src($photo3_id, $seek_thumb_size_name);
        $photo4 = wp_get_attachment_image_src($photo4_id, $seek_thumb_size_name);
        $photo5 = wp_get_attachment_image_src($photo5_id, $seek_thumb_size_name);
        $photo6 = wp_get_attachment_image_src($photo6_id, $seek_thumb_size_name);
        $photo7 = wp_get_attachment_image_src($photo7_id, $seek_thumb_size_name);
        $photo8 = wp_get_attachment_image_src($photo8_id, $seek_thumb_size_name);
        $photo9 = wp_get_attachment_image_src($photo9_id, $seek_thumb_size_name);
        $photo10 = wp_get_attachment_image_src($photo10_id, $seek_thumb_size_name);



        $photo1 = (isset($photo1[0])) ? aq_resize($photo1[0], $crop_width, $crop_height, true, true, true, $photo1_id) : '';
        $photo2 = (isset($photo2[0])) ? aq_resize($photo2[0], $crop_width, $crop_height, true, true, true, $photo2_id) : '';
        $photo3 = (isset($photo3[0])) ? aq_resize($photo3[0], $crop_width, $crop_height, true, true, true, $photo3_id) : '';
        $photo4 = (isset($photo4[0])) ? aq_resize($photo4[0], $crop_width, $crop_height, true, true, true, $photo4_id) : '';
        $photo5 = (isset($photo5[0])) ? aq_resize($photo5[0], $crop_width, $crop_height, true, true, true, $photo5_id) : '';
        $photo6 = (isset($photo6[0])) ? aq_resize($photo6[0], $crop_width, $crop_height, true, true, true, $photo6_id) : '';
        $photo7 = (isset($photo7[0])) ? aq_resize($photo7[0], $crop_width, $crop_height, true, true, true, $photo7_id) : '';
        $photo8 = (isset($photo8[0])) ? aq_resize($photo8[0], $crop_width, $crop_height, true, true, true, $photo8_id) : '';
        $photo9 = (isset($photo9[0])) ? aq_resize($photo9[0], $crop_width, $crop_height, true, true, true, $photo9_id) : '';
        $photo10 = (isset($photo10[0])) ? aq_resize($photo10[0], $crop_width, $crop_height, true, true, true, $photo10_id) : '';


        $all_photos =   array(
                            'id_'.$photo1_id => $photo1, 'id_'.$photo2_id => $photo2, 'id_'.$photo3_id => $photo3,
                            'id_'.$photo4_id => $photo4, 'id_'.$photo5_id => $photo5, 'id_'.$photo6_id => $photo6,
                            'id_'.$photo7_id => $photo7, 'id_'.$photo8_id => $photo8, 'id_'.$photo9_id => $photo9,
                            'id_'.$photo10_id => $photo10
                        );
        $all_photos = array_filter($all_photos);
        $return_link = current($all_photos);
        if(count($all_photos) > 0) :
            if($gallery_type == 'masonry' || $gallery_type == 'grid') :
                $html .= '<div class="ts-portfolio-masonry-gallery">';
                $link_begin = '<a ' . $target . ' href="'.esc_url(get_permalink()).'" class="featured-photo-link">';
                $link_end = '</a>';
                if($post->ID == $ts_page_id) :
                    $link_begin = '';
                    $link_end = '';
                endif;
                $i = 1;
                foreach($all_photos AS $img_id => $img) {
                    $alt = ($get_alt_text) ? ts_get_attachment_alt_text($img_id) : '';
                    $html .= '<div class="item">'.$link_begin.'<img src="'.esc_url($img).'" alt="'.esc_attr($alt).'"/>'.$link_end.'</div>';
                    $i++;
                }
                $html .= ($class) ? '</div>' : '';
            elseif($gallery_type == 'images') :
                $i = 1;
                foreach($all_photos AS $img_id => $img) {
                    $alt = ($get_alt_text) ? ts_get_attachment_alt_text($img_id) : '';
                    $html .= '<div class="single-image"><img src="'.esc_url($img).'" alt="'.esc_attr($alt).'"/></div>';
                    $i++;
                }
            else :
                $html .= ($class) ? '<div class="'.esc_attr($class).' '.esc_attr($media_class).'">' : '';
                $html .= '<div class="flexslider gallery slider"><ul class="slides">';
                $link_begin = '<a ' . $target . ' href="'.esc_url(get_permalink()).'" class="featured-photo-link" >';
                $link_end = '</a>';
                if($post->ID == $ts_page_id) :
                    $link_begin = '';
                    $link_end = '';
                endif;
                $i = 1;
                foreach($all_photos AS $img_id => $img) {
                    $alt = ($get_alt_text) ? ts_get_attachment_alt_text($img_id) : '';
                    $html .= '<li>'.$link_begin.'<img src="'.esc_url($img).'" alt="'.esc_attr($alt).'"/>'.$link_end.'</li>';
                    $i++;
                }
                $html .= '</ul></div>';
                $html .= ($class) ? '</div>' : '';
            endif;
        else :
            $post_format = 'standard';
        endif;
    endif;
    if(!in_array($post_format, array('video','gallery','slider','audio')) || (in_array($post_format, array('video','gallery','slider','audio')) && !$html)) :
        $post_format = 'standard';
        $photo_id = '';
        $thumb_photo_id = get_post_thumbnail_id($post->ID);
        $thumb_photo = wp_get_attachment_image_src($thumb_photo_id, $seek_thumb_size_name);
        $thumb_photo = (isset($thumb_photo[0])) ? $thumb_photo[0] : '';

        if((trim($preview) && !$is_single_featured_media) || ($preview && !$thumb_photo)) :
            $photo_id = $preview_id;
            $photo = $return_link = $preview;
        else :
            $photo_id = $thumb_photo_id;
            $photo = $return_link = $thumb_photo;
        endif;

        if($photo) :
            $caption = '';
            $link_class = ($within_slider) ? 'ts-item-link' : 'featured-photo-link';
            $link_begin = '<a ' . $target . ' href="'.esc_url(get_permalink()).'" class="'.esc_attr($link_class).'">';
            $link_end = '</a>';
            if($is_single_featured_media) :
                $link_begin = '';
                $link_end = '';
                $caption = ts_get_photo_caption($photo_id);
            endif;
            $photo = aq_resize($photo, $crop_width, $crop_height, true, true, true, $photo_id);
            $photo_class = ($within_slider) ? 'slider-photo' : 'featured-photo';
            $alt = ($get_alt_text) ? ts_get_attachment_alt_text($photo_id) : '';
            $media = '<div class="'.esc_attr($photo_class).' '.esc_attr($media_class).' '.esc_attr($ts_within_blog_loop).'">'.$link_begin.'<img src="'.esc_url($photo).'" alt="'.esc_attr($alt).'" width="'.esc_attr($crop_width).'"/>'.$link_end.$caption.'</div>';
            $html .= ($class) ? '<div class="'.esc_attr($class).'">'.$media.'</div>'."\n" : $media."\n";
        endif;
    endif;

    $wrap_class = array();

    $wrap_class[] = ($within_slider) ? '' : 'ts-fade-in';
    $wrap_class[] = (isset($arg['wrap_class'])) ? $arg['wrap_class'] : '';
    $wrap_class[] = 'ts-featured-media-'.$post_format;

    $wrap_class = implode(' ', $wrap_class);

    $final_html = '<div class="featured-media-wrap '.esc_attr($wrap_class).'">'.$html.'</div>';

    if(isset($arg['return']) && $arg['return'] == 'link') :
        return $return_link;
    elseif(isset($arg['return']) && $arg['return'] == 'array') :
        $mfp_class = ($post_format == 'video' && $video_id) ? 'mfp-iframe' : (isset($return_link) ? 'mfp-image' : '');
        return array('link'=>$return_link,'mfp_class'=>$mfp_class);
    elseif(isset($arg['return']) && $arg['return'] == 'big_array') :
        $photo = (isset($photo)) ? $photo : '';
        $return_array = array(
            'html' => $final_html,
            'format' => $post_format,
            'photo' => $photo
        );
        return $return_array;
    else :
        return (trim($html)) ? $final_html : '';
    endif;
}

function ts_get_attachment_alt_text($attachment_id)
{
    $attachment_id = (substr($attachment_id, 0, 3) == 'id_') ? substr($attachment_id, 3) : $attachment_id;
    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    return (is_string($alt) && trim($alt)) ? $alt : '';
}

function ts_oembed_html_api_fix($html = '')
{
    $iframe_id = 'vid-'.mt_rand();
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $tag = $dom->getElementsByTagName('iframe')->item(0);
    $src = $tag->getAttribute('src');
    $src_parts = explode('?', $src);
    $connector = (isset($src_parts[1])) ? '&amp;' : '?';

    $class = '';

    if(strpos($src_parts[0], 'vimeo') !== false) :
        $src = $src.$connector.'api=1&amp;player_id='.$iframe_id;
        $class = "ts-vimeo-player";
    elseif(strpos($src_parts[0], 'youtu') !== false) :
        $src = $src.$connector.'enablejsapi=1';
        $class = "ts-youtube-player";
    else :
        return $html;
    endif;

    $tag->setAttribute('src', $src);
    $tag->setAttribute('id', $iframe_id);
    $tag->setAttribute('class', $class);

    return $dom->saveHTML($tag);
}

function ts_full_url($include_port = false)
{
    $s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
    $protocol = substr(strtolower($_SERVER['SERVER_PROTOCOL']), 0, strpos(strtolower($_SERVER['SERVER_PROTOCOL']), '/')) . $s;
    $port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (($include_port) ? ":".$_SERVER['SERVER_PORT'] : '');
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

/*
 * tweetify.inc
 *
 * Ported from Remy Sharp's 'ify' javascript function; see:
 * http://code.google.com/p/twitterjs/source/browse/trunk/src/ify.js
 *
 * Based on revision 46:
 * http://code.google.com/p/twitterjs/source/detail?spec=svn46&r=46
 */


/*
 * Clean a tweet: translate links, usernames beginning '@', and hashtags
 */
function ts_clean_tweet($tweet, $links_in_new_tab = false)
{
    global $ts_open_tweet_links_in_new_tab;

    $ts_open_tweet_links_in_new_tab = ($links_in_new_tab) ? 1 : 0;

    $tweet = trim($tweet);

	$regexps = array
	(
		"link"  => '/[a-z]+:\/\/[a-z0-9-_]+\.[a-z0-9-_@:~%&\?\+#\/.=]+[^:\.,\)\s*$]/i',
		"at"    => '/(^|[^\w]+)\@([a-zA-Z0-9_]{1,15}(\/[a-zA-Z0-9-_]+)*)/',
		"hash"  => "/(^|[^&\w'\"]+)\#([a-zA-Z0-9_]+)/"
	);

	foreach ($regexps as $name => $re)
	{
		$tweet = preg_replace_callback($re, 'ts_parse_tweet_'.$name, $tweet);
	}

	unset($GLOBALS['ts_open_tweet_links_in_new_tab']);

	return $tweet;
}

/*
 * Wrap a link element around URLs matched via preg_replace()
 */
function ts_parse_tweet_link($m)
{
    global $ts_open_tweet_links_in_new_tab;
    $target = ($ts_open_tweet_links_in_new_tab == 1) ? '_blank' : '_self';
	//return '<a href="'.$m[0].'" '.$target.'>'.((strlen($m[0]) > 25) ? substr($m[0], 0, 24).'...' : $m[0]).'</a>';
	return '<a href="'.esc_url($m[0]).'" target="'.esc_attr($target).'">'.$m[0].'</a>';
}

/*
 * Wrap a link element around usernames matched via preg_replace()
 */
function ts_parse_tweet_at($m)
{
    global $ts_open_tweet_links_in_new_tab;
    $target = ($ts_open_tweet_links_in_new_tab == 1) ? '_blank' : '_self';
    $url = 'https://twitter.com/'.$m[2];
	return $m[1].'<a href="'.esc_url($url).'" class="at-link" target="'.esc_attr($target).'"><span class="at-sym">@</span><span class="at-text">'.$m[2].'</span></a>';
}

/*
 * Wrap a link element around hashtags matched via preg_replace()
 */
function ts_parse_tweet_hash($m)
{
    global $ts_open_tweet_links_in_new_tab;
    $target = ($ts_open_tweet_links_in_new_tab == 1) ? '_blank' : '_self';
    $url = 'https://twitter.com/search?q=%23'.$m[2];
	return $m[1].'<a href="'.esc_url($url).'" class="hash-link" target="'.esc_attr($target).'"><span class="hash-sym">#</span><span class="hash-text">'.$m[2].'</span></a>';
}

function ts_time2str($ts = null)
{
    $orig_ts = strtotime($ts);
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 5400) return '1 hour ago';
            if($diff < 7200) return '2 hours ago';
            if($diff < 86400) return round($diff / 3600) . ' hours ago';
        }
        if($day_diff < 7)
        {
            $day_diff = ceil($diff / 86400);
            return ($day_diff <= 1) ? 'Yesterday' : $day_diff . ' days ago';
        }
        if($day_diff < 31) return (ceil($day_diff / 7) == 1) ? ceil($day_diff / 7) . ' week ago' : ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 5400) return 'in an hour';
            if($diff < 7200) return 'in 2 hours';
            if($diff < 86400) return 'in ' . round($diff / 3600) . ' hours';
        }
        if($day_diff == 1)
        {
            $day_diff = abs(ceil($diff / 86400));
            return ($day_diff <= 1) ? 'Tomorrow' : 'in '.$day_diff.' days';
        }
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return (ceil($day_diff / 7) == 1) ? 'in ' . ceil($day_diff / 7) . ' week' : 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

// Stop WordPress from assuming that the "Blog" page is the parent for our custom post types of: portfolio, slider
function ts_remove_parent_classes($class)
{
  // check for current page classes, return false if they exist.
	return (in_array($class, array('current_page_item', 'current_page_parent', 'current_page_ancestor', 'current-menu-item'))) ? FALSE : TRUE;
}

function ts_add_class_to_wp_nav_menu($classes)
{
    global $ts_page_id;

    $post_type = get_post_type($ts_page_id);

    if(in_array($post_type, array('slider','portfolio')) || is_post_type_archive(array('slider','portfolio')) || is_404())
    {
        // we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
        $classes = array_filter($classes, "ts_remove_parent_classes");
    }

    switch (get_post_type($ts_page_id))
    {
        case 'slider':
            // add the current page class to a specific menu item (replace ###).
            if (in_array('menu-item-592', $classes))
            {
                //$classes[] = 'current_page_parent';
            }
            break;

        case 'portfolio':
            // add the current page class to a specific menu item (replace ###).
            if (in_array('menu-item-348', $classes))
            {
                //$classes[] = 'current_page_parent';
            }
            break;

    // add more cases if necessary and/or a default
    }
    return $classes;
}


function ts_enable_style_selector()
{
    return (ts_option_vs_default('enable_style_selector', 0) == 1) ? true : false;
}

// sets or retrieves a cookie
function ts_cookie($args)
{
    if(func_num_args() > 1)
        setcookie($args);
    else
        return (isset($_COOKIE[$args])) ? $_COOKIE[$args] : '';
}

function ts_encode_all($str = '')
{
    $str = mb_convert_encoding($str , 'UTF-32', 'UTF-8'); //big endian
    $split = str_split($str, 4);

    $res = "";
    foreach ($split as $c) {
        $cur = 0;
        for ($i = 0; $i < 4; $i++) {
            $cur |= ord($c[$i]) << (8*(3 - $i));
        }
        $res .= "&#" . $cur . ";";
    }
    return $res;
}

function ts_valid_hex_color($color = '')
{
    return (preg_match('/^#[a-f0-9]{6}$/i', $color)) ? true : false;
}

function ts_query_var($var = '', $default = '')
{
    return (isset($_GET[$var])) ? $_GET[$var] : $default;
}

/*
 * Preset background pattern logic
 */
function ts_body_bg_class()
{
    $bg_pattern = ts_option_vs_default('background_pattern', null);

    return (trim($bg_pattern)) ? 'bg_'.$bg_pattern : '';
}

function ts_body_class($classes = '')
{
    $classes = (is_array($classes)) ? $classes : array();

    /* is responsive on (default for now)*/
    $classes[] = 'ts-responsive';

    /* has at least partial top bar */
    if(in_array(ts_option_vs_default('show_top_bar', 'yes'), array('yes','social'))) :
        $classes[] = 'ts-partial-top-bar';
    endif;

    /* has full top bar */
    if(ts_option_vs_default('show_top_bar', 'yes') == 'yes') :
        $classes[] = 'ts-full-top-bar';
    endif;

    /* has only social top bar */
    if(ts_option_vs_default('show_top_bar', 'yes') == 'social') :
        $classes[] = 'ts-social-top-bar';
    endif;

    /* RTL mode */
    if(ts_option_vs_default('rtl', 0) == 1) :
        $classes[] = 'rtl';
    endif;

    /* is single post */
    $classes[] = (is_single()) ? 'ts-is-single' : 'ts-is-not-single';

    /* body has retina logo? */
    $logo = ts_option_vs_default('logo_upload', null);
    $retina_logo = ts_option_vs_default('retina_logo', null);

    if(!trim($logo) && trim($retina_logo)) $retina_logo = ''; // because $logo becomes $retina_logo in this case.

    if($retina_logo) :
        $classes[] = 'has-retina-logo';
    endif;

    /* "back to top" is in use (or not) */
    $classes[] = (ts_option_vs_default('show_back_to_top', 0) == 1) ? 'ts-back-to-top' : 'ts-no-back-to-top';

    /* "back to top" is in use (or not) on mobile devices */
    $classes[] = (ts_option_vs_default('show_back_to_top_on_mobile', 0) == 1) ? 'ts-back-to-top-mobile' : 'ts-no-back-to-top-mobile';

    /* sticky nav is in use (or not) */
    $classes[] = (ts_option_vs_default('sticky_nav', 1) == 1) ? 'ts-has-sticky-nav' : 'ts-no-sticky-nav';

    /* fullwidth layout is on (or not) */
    $classes[] = (ts_option_vs_default('layout', 1) == 1) ? 'wall-to-wall' : 'not-wall-to-wall';

    /* add shadow to page content? */
    $classes[] = (ts_option_vs_default('layout', 1) != 1 && ts_option_vs_default('layout_shadow', 1) == 1) ? 'shadow' : '';

    /* body bg pattern */
    $bg_pattern = ts_option_vs_default('background_pattern', null);
    if(trim($bg_pattern)) :
        $classes[] = 'bg_'.$bg_pattern;
    endif;

    /* sidebar placement on tablet-sized devices */
    $sidebar_placement = ts_option_vs_default('tablet_sidebar_placement', 'beside-content');
    $classes[] = 'ts-sidebar-'.$sidebar_placement;

    /* body has *custom* bg image */
    if(ts_option_vs_default('use_custom_background_image', 0) && ts_option_vs_default('custom_background_image', null)) :
        $classes[] = 'has-custom-bg-image';
    endif;

    /* body has bg image (or not) */
    if(in_array('not-wall-to-wall', $classes) && (in_array('has-custom-bg-image', $classes) || trim($bg_pattern))) :
        $classes[] = 'has-bg-image';
    else :
        $classes[] = 'no-bg-image';
    endif;

    /* smooth scroll is turned on */
    if(ts_option_vs_default('smooth_page_scroll', 1) == 1) :
        $classes[] = 'smooth-page-scroll';
    endif;

    /* body has woocommerce plus/minus buttons */
    if(!ts_woocommerce_version_check('2.3.0')) :
        $classes[] = 'woocommerce-plus-minus-buttons';
        $classes[] = 'woocommerce-less-than-2dot3';
    else :
        $classes[] = 'woocommerce-2dot3-plus';
    endif;

    /* body has centered logo */
    //if(ts_option_vs_default('logo_alignment_layout') == 'centered') :
        $classes[] = 'ts-logo-centered';
    //endif;

    /* body has centered main menu */
    $classes[] = (ts_option_vs_default('main_nav_align') == 'centered') ? 'ts-main-nav-centered' : 'ts-main-nav-standard';

    /* body has centered breadcrumbs */
    /*
    if(ts_option_vs_default('breadcrumbs_alignment_layout') == 'centered') :
        $classes[] = 'ts-breadcrumbs-centered';
    endif;
    */

    /* body has centered title bar */
    /*
    if(ts_option_vs_default('titlebar_layout') == 'centered') :
        $classes[] = 'ts-titlebar-centered';
    endif;
    */

    /* body has footer widgets */
    if(ts_option_vs_default('show_footer_widgets', 1) == 1 && ts_option_vs_default('footer_layout', 'footer-2')) :
        $classes[] = 'has-footer-widgets';
    else :
        $classes[] = 'has-no-footer-widgets';
    endif;

    /* filter out any duplicates */
    $classes = array_filter(get_body_class($classes));

    /* return it all */
    echo 'class="'.esc_attr(implode(' ', $classes)).'"';
}

function ts_loop_class()
{
    $args = func_get_args();

    if(func_num_args() == 2)
    {
        $atts = (is_array($args[0])) ? $args[0] : $args[1];
        $addons = (is_string($args[0])) ? $args[0] : $args[1];
    }
    else
    {
        $atts = null;
        $addons = (isset($args[0]) && is_string($args[0])) ? $args[0] : '';
    }

    $classes = array();

    if(!is_null($atts) && is_array($atts))
    {

        if(isset($atts['category_name']) && trim($atts['category_name'])) {
            $category_names = str_replace(' ', '', $atts['category_name']);
            $category_names = explode(',', $category_names);
            $category_names = (count($category_names) > 0) ? 'category-'.implode(' category-', $category_names) : '';
            $classes[] = $category_names;
        }

        if(isset($atts['cat']) && trim($atts['cat'])) {
            $cats = str_replace(' ', '', $atts['cat']);
            $cats = explode(',', $cats);
            $cats = (count($cats) > 0) ? 'category-'.implode(' category-', $cats) : '';
            $classes[] = $cats;
        }

        if(isset($atts['layout']) && $atts['layout'] == 'banner') {
            $text_position = 'alternate';
            if(isset($atts['text_position']) && in_array($atts['text_position'], array('left','right'))) {
                $text_position = $atts['text_position'];
            }
            $classes[] = 'loop-banner-'.$text_position.'-text-pos';
        }

        if(isset($atts['layout']) && ($atts['layout'] == 'banner' || $atts['layout'] == 'slider')) {
            if(isset($atts['fullwidth']) && ts_attr_is_true($atts['fullwidth'])) {
                $classes[] = 'ts-edge-to-edge ts-color-section-fullwidth';
            } else {
                $classes[] = 'not-fullwidth';
            }
        }

        if(isset($atts['layout']) && $atts['layout'] == 'widget') {
            if(isset($atts['widget_layout']) && $atts['widget_layout'] == 'horizontal') {
                $classes[] = 'loop-widget-horizontal-wrap';
            } else {
                $classes[] = 'loop-widget-vertical-wrap';
            }
        }
    }

    $classes[] = $addons;

    $classes = implode(' ', $classes);

    return $classes;
}

function ts_loop_post_category_class($atts = '', $media = '')
{
    $atts = (is_array($atts)) ? $atts : array();
    $show_media = (isset($atts['show_media'])) ? $atts['show_media'] : true;
    $show_category = (isset($atts['show_category'])) ? $atts['show_category'] : true;
    $show_meta = (isset($atts['show_meta'])) ? $atts['show_meta'] : true;

    $classes = array();

    $classes[] = (!trim($media)) ? 'has-no-media' : 'has-media';
    $classes[] = (ts_attr_is_false($show_media)) ? 'showing-no-media' : 'showing-media';
    $classes[] = (ts_attr_is_false($show_category)) ? 'hidden' : '';
    if((!trim($media) || ts_attr_is_false($show_media)) && ts_attr_is_false($show_meta) && ts_attr_is_true($show_category)) :
        $classes[] = 'add-bottom-margin';
    endif;

    $classes = array_filter($classes);

    return implode(' ', $classes);
}

function ts_loop_wrap_class()
{
    $args = func_get_args();

    if(func_num_args() == 2)
    {
        $atts = (is_array($args[0])) ? $args[0] : $args[1];
        $addons = (is_string($args[0])) ? $args[0] : $args[1];

        return ts_loop_class($atts, $addons);
    }
    else
    {
        $atts = (isset($args[0]) && is_array($args[0])) ? $args[0] : array();
        $addons = (isset($args[0]) && is_string($args[0])) ? $args[0] : '';

        return ts_loop_class($atts, $addons);
    }
}

function ts_get_top_layout()
{
    $layout = ts_option_vs_default('top_layout','normal');

    return $layout;
}

function ts_wide_layout()
{
    $is_wide = (ts_option_vs_default('layout', 1) == 1) ? true : false;

    return $is_wide;
}


add_filter( 'wp_nav_menu_items', 'ts_add_main_menu_links', 10, 2);
function ts_add_main_menu_links($menu, $args)
{
    $main_nav_addons = '';

    if($args->theme_location == 'main_nav') :
        /*
        if(ts_option_vs_default('include_main_nav_search_link', 0) == 1) :
            $main_nav_search_link  = '<li id="main-nav-search-link" class="main-nav-search-link menu-item ubermenu-item">';
            $main_nav_search_link .= '<a href="javascript:void(0)" class="ts-hover-menu-link">';
            $main_nav_search_link .= '<i class="fa fa-search"></i>';
            $main_nav_search_link .= '<span class="mb-only">&nbsp;'.__('Search', 'ThemeStockyard').'</span>';
            $main_nav_search_link .= '</a>';
            $main_nav_search_link .= '<div id="main-nav-search-sub-menu" class="ts-hover-menu invert main-nav-search-sub-menu">';
            $main_nav_search_link .= ts_html5_search_form().'</div>';
            $main_nav_search_link .= '</li>';
            $main_nav_addons .= $main_nav_search_link;
        endif;
        */
        global $woocommerce;
        if(ts_option_vs_default('include_main_nav_shop_link', 0) == 1 && class_exists( 'WooCommerce' ) && is_object($woocommerce)) :
            $main_nav_shop_href = (is_object($woocommerce)) ? $woocommerce->cart->get_cart_url() : '';
            $main_nav_shop_link  = '<li id="main-nav-shop-link" class="main-nav-shop-link menu-item ubermenu-item">';
            $main_nav_shop_link .= '<a href="'.esc_url($main_nav_shop_href).'" class="ts-hover-menu-link color-primary">';
            $main_nav_shop_link .= '<i class="fa fa-shopping-cart"></i> ';
            $main_nav_shop_link .= '(<span id="ts-top-nav-shop-total">'.strip_tags($woocommerce->cart->get_cart_total()).'</span>)';
            $main_nav_shop_link .= '</a>';
            $main_nav_shop_link .= '</li>';
            $main_nav_addons .= $main_nav_shop_link;
        endif;
    endif;

    return $menu . $main_nav_addons;
}

function ts_get_woo_mini_cart()
{
    /*
    if ( class_exists('woocommerce') && function_exists('woocommerce_mini_cart')) :
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_contents();
        ob_end_clean();
        return $mini_cart;
    endif;
    */
    global $woocommerce;
    if ( class_exists('woocommerce') && is_object($woocommerce)) :
        /*
        ob_start();
        wc_cart_totals_subtotal_html();
        $subtotal = ob_get_contents();
        ob_end_clean();
        return $subtotal;
        */
        return $woocommerce->cart->get_cart_total();
    endif;
}

function ts_is_woo_shop()
{
    return (function_exists('is_shop') && is_shop()) ? true : false;
}

function ts_is_woo_cart()
{
    return (function_exists('is_cart') && is_cart()) ? true : false;
}

function ts_is_woo_checkout()
{
    return (function_exists('is_checkout') && is_checkout()) ? true : false;
}

function ts_reload_mini_cart()
{
    echo ts_get_woo_mini_cart();

    exit;
}

function ts_woocommerce_version_check( $version = '2.1' ) {
    global $woocommerce;
    if ( class_exists('woocommerce') && is_object($woocommerce)) {
        if( version_compare( $woocommerce->version, $version, ">=" ) ) {
            return true;
        }
    }
    return false;
}


function ts_update_postviews()
{
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "ts_update_postviews_nonce")) {
        exit('no funny business');
    }

    if(ts_option_vs_default('show_titlebar_post_view_count', 0) == 1)
    {
        $post = get_post($_POST['pid']);

        if(is_object($post) && isset($post->ID))
        {
            $post_view_count = ts_postmeta_vs_default($post->ID, '_p_ts_postviews', 0);
            $post_view_count_text = sprintf(_n( '1 view', '%s views', $post_view_count, 'ThemeStockyard'), $post_view_count);

            $post_view_count++;

            update_post_meta($post->ID, '_p_ts_postviews', $post_view_count);

            return $post_view_count_text;
        }
    }

    exit;
}

//////////////////////////////////////////////////////////////////
// Prevent P & BR tags from wrapping around block element shortcodes
//////////////////////////////////////////////////////////////////
function ts_shortcodes_formatter($content) {
	$block = join("|",array("accordion", "alert", "animation", "blog", "blog_slider", "blog_widget", "callout", "carousel_gallery", "center", "clip", "code", "color_section", "column", "columns", "column_row", "divider", "fadein", "five_sixth", "five_sixths", "four_fifth", "four_fifths", "fullwidth", "fullwidth_section", "googlemap", "hidden", "hide", "iconbox", "iconboxes", "list", "map", "one_half", "one_third", "one_fourth", "one_fifth", "one_sixth", "parallax", "parallax_section", "person", "portfolio", "pricing_column", "pricing_footer", "pricing_row", "pricing_table", "progress", "right", "ts_gallery", "section", "slider_gallery", "slider", "soudcloud", "spotify", "table", "table_body", "table_cell", "table_heading_row", "table_heading", "table_row", "tab", "tabs", "tagline", "testimonial", "testimonials", "title", "three_fifth", "three_fifths", "three_fourth", "three_fourths", "thumb_gallery", "toggle", "toggles", "two_fifth", "two_fifths", "two_third", "two_thirds", "vimeo", "vine", "youtube"));

	// opening tag
	$content = preg_replace("/(<p[^>]*>)?\[($block)([^\]]+)?\](<\/p>|<br \/>|<br>)?/","[$2$3]",$content);

	// closing tag
	$content = preg_replace("/(<p[^>]*>)?\[\/($block)](<\/p>|<br \/>|<br>)?/","[/$2]", $content);

	return $content;
}


/**
 * Filters the page title appropriately depending on the current page
 *
 * This function is attached to the 'wp_title' fiilter hook.
 *
 * @uses	get_bloginfo()
 * @uses	is_home()
 * @uses	is_front_page()
 */
function ts_filter_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	$filtered_title = '';

    $blog_name = get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description' );
    $paged_addon = ( 2 <= $paged || 2 <= $page ) ? sprintf( __( 'Page %s', 'ThemeStockyard' ), max( $paged, $page ) ) : '';

    if(is_front_page()) :
        $filtered_title .= $blog_name;
        $filtered_title .= (trim($site_description)) ? $sep . $site_description : '';
        $filtered_title .= (trim($paged_addon)) ? $sep . $paged_addon : '';
    else :
        $filtered_title .= str_replace($blog_name, '', $title);
        $filtered_title .= (trim($paged_addon) && !is_home()) ? $sep . $paged_addon : '';
        $filtered_title .= $sep . $blog_name;
    endif;

	return ts_substr_trim(trim($filtered_title), $sep);
}

//////////////////////////////////////////////////////////////////
// WP_Query addons
// ===============
// Custom sorting functions. Allows you us to sort by...
// - most comments in last 30 days
// - most comments in last 7 days
// - most comments in last 24 hours
//
// Inspired by: https://wordpress.org/support/topic/sort-posts-in-the-loop-by-recent-comments-or-activity#post-2130345
//////////////////////////////////////////////////////////////////
add_filter('query_vars', 'ts_query_vars');
add_filter('posts_join', 'ts_join_comments_from_30_days_ago');
add_filter('posts_join', 'ts_join_comments_from_7_days_ago');
add_filter('posts_join', 'ts_join_comments_from_24_hours_ago');
add_filter('posts_orderby', 'ts_orderby_most_comments');

function ts_query_vars($vars) {
	$vars[] = 'orderby_comments_30_days';
	$vars[] = 'orderby_comments_7_days';
	$vars[] = 'orderby_comments_24_hours';
	return $vars;
}

function ts_join_comments_from_30_days_ago($sql) {
	global $wpdb;
	if(get_query_var('orderby_comments_30_days')) {
		$sql .= '
			LEFT JOIN (SELECT COUNT(comment_post_ID) AS recent_comment_count
                FROM '.esc_sql($wpdb->comments).'
                WHERE comment_approved="1" && comment_date_gmt >= "'.esc_sql(date('Y-m-d H:i:s', strtotime(current_time('mysql').' -30 days'))).'"
                GROUP BY post) AS recent_comments
            ON recent_comments.post = '.esc_sql($wpdb->posts).'.ID
		';
	}
	return $sql;
}

function ts_join_comments_from_7_days_ago($sql) {
	global $wpdb;
	if(get_query_var('orderby_comments_7_days')) {
		$sql .= '
			LEFT JOIN (SELECT COUNT(comment_post_ID) AS recent_comment_count
                FROM '.esc_sql($wpdb->comments).'
                WHERE comment_approved="1" && comment_date_gmt >= "'.esc_sql(date('Y-m-d H:i:s', strtotime(current_time('mysql').' -7 days'))).'"
                GROUP BY post) AS recent_comments
            ON recent_comments.post = '.esc_sql($wpdb->posts).'.ID
		';
	}
	return $sql;
}

function ts_join_comments_from_24_hours_ago($sql) {
	global $wpdb;
	if(get_query_var('orderby_comments_24_hours')) {
		$sql .= '
			LEFT JOIN (SELECT COUNT(comment_post_ID) AS recent_comment_count
                FROM '.esc_sql($wpdb->comments).'
                WHERE comment_approved="1" && comment_date_gmt >= "'.esc_sql(date('Y-m-d H:i:s', strtotime(current_time('mysql').' -24 hours'))).'"
                GROUP BY post) AS recent_comments
            ON recent_comments.post = '.esc_sql($wpdb->posts).'.ID
		';
	}
	return $sql;
}

function ts_orderby_most_comments($sql) {
	if(get_query_var('orderby_comments_30_days') || get_query_var('orderby_comments_7_days') || get_query_var('orderby_comments_24_hours')) {
		return 'recent_comments.recent_comment_count DESC'.($sql ? ', '.$sql : '');
	}
	return $sql;
}


/////////////////////////////////////////////////////////
// Font list. Used within the Theme Options
/////////////////////////////////////////////////////////

// based on the above function: ts_option_vs_default()
function ts_option_vs_default_font($option = '', $default = '', $avoid_empty_values = false, $alt_supported = true)
{
    $alt_font = '';
    $font = ts_option_vs_default($option, $default, true);

    $test_option = explode('::', $option);

    if(count($test_option) == 2)
    {
        return $font;
    }
    elseif($alt_supported && ts_option_vs_default('use_alt_'.$option, 0) == 1)
    {
        $alt_font = ts_option_vs_default('alt_'.$option);
    }

    return (trim($alt_font)) ? $alt_font : $font;
}

function ts_standard_fonts($return = false)
{
    global $ts_standard_fonts;

    $ts_standard_fonts = array(
                    'Select a font...' => 'Select a font...',
                    '----------Standard Fonts----------' => '----------Standard Fonts----------',
                    'Arial' => 'Arial',
                    "Arial Black" => "Arial Black",
                    "Bookman Old Stylef" => "Bookman Old Style",
                    "Comic Sans MS" => "Comic Sans MS",
                    "Courier" => "Courier",
                    "Garamond" => "Garamond",
                    "Georgia" => "Georgia",
                    "Impact" => "Impact",
                    "Lucida Console" => "Lucida Console",
                    "Lucida Sans Unicode" => "Lucida Sans Unicode",
                    "MS Sans Serif" => "MS Sans Serif",
                    "MS Serif" => "'MS Serif', 'New York', sans-serif",
                    "Palatino Linotype" => "Palatino Linotype",
                    "Tahoma" => "Tahoma",
                    "Times New Roman" => "Times New Roman",
                    "Trebuchet MS" => "Trebuchet MS",
                    "Verdana" => "Verdana",
                    //"" => "",
                );

    if($return) return $ts_standard_fonts;
}

function ts_google_fonts($return = false)
{
    global $ts_google_fonts;

    $ts_google_fonts = array(
                "----------Google Fonts----------" => "----------Google Fonts----------",
                "ABeeZee" => "ABeeZee",
                "Abel" => "Abel",
                "Abril Fatface" => "Abril Fatface",
                "Aclonica" => "Aclonica",
                "Acme" => "Acme",
                "Actor" => "Actor",
                "Adamina" => "Adamina",
                "Advent Pro" => "Advent Pro",
                "Aguafina Script" => "Aguafina Script",
                "Akronim" => "Akronim",
                "Aladin" => "Aladin",
                "Aldrich" => "Aldrich",
                "Alef" => "Alef",
                "Alegreya" => "Alegreya",
                "Alegreya SC" => "Alegreya SC",
                "Alegreya Sans" => "Alegreya Sans",
                "Alegreya Sans SC" => "Alegreya Sans SC",
                "Alex Brush" => "Alex Brush",
                "Alfa Slab One" => "Alfa Slab One",
                "Alice" => "Alice",
                "Alike" => "Alike",
                "Alike Angular" => "Alike Angular",
                "Allan" => "Allan",
                "Allerta" => "Allerta",
                "Allerta Stencil" => "Allerta Stencil",
                "Allura" => "Allura",
                "Almendra" => "Almendra",
                "Almendra Display" => "Almendra Display",
                "Almendra SC" => "Almendra SC",
                "Amarante" => "Amarante",
                "Amaranth" => "Amaranth",
                "Amatic SC" => "Amatic SC",
                "Amethysta" => "Amethysta",
                "Amiri" => "Amiri",
                "Amita" => "Amita",
                "Anaheim" => "Anaheim",
                "Andada" => "Andada",
                "Andika" => "Andika",
                "Angkor" => "Angkor",
                "Annie Use Your Telescope" => "Annie Use Your Telescope",
                "Anonymous Pro" => "Anonymous Pro",
                "Antic" => "Antic",
                "Antic Didone" => "Antic Didone",
                "Antic Slab" => "Antic Slab",
                "Anton" => "Anton",
                "Arapey" => "Arapey",
                "Arbutus" => "Arbutus",
                "Arbutus Slab" => "Arbutus Slab",
                "Architects Daughter" => "Architects Daughter",
                "Archivo Black" => "Archivo Black",
                "Archivo Narrow" => "Archivo Narrow",
                "Arimo" => "Arimo",
                "Arizonia" => "Arizonia",
                "Armata" => "Armata",
                "Artifika" => "Artifika",
                "Arvo" => "Arvo",
                "Arya" => "Arya",
                "Asap" => "Asap",
                "Asar" => "Asar",
                "Asset" => "Asset",
                "Astloch" => "Astloch",
                "Asul" => "Asul",
                "Atomic Age" => "Atomic Age",
                "Aubrey" => "Aubrey",
                "Audiowide" => "Audiowide",
                "Autour One" => "Autour One",
                "Average" => "Average",
                "Average Sans" => "Average Sans",
                "Averia Gruesa Libre" => "Averia Gruesa Libre",
                "Averia Libre" => "Averia Libre",
                "Averia Sans Libre" => "Averia Sans Libre",
                "Averia Serif Libre" => "Averia Serif Libre",
                "Bad Script" => "Bad Script",
                "Balthazar" => "Balthazar",
                "Bangers" => "Bangers",
                "Basic" => "Basic",
                "Battambang" => "Battambang",
                "Baumans" => "Baumans",
                "Bayon" => "Bayon",
                "Belgrano" => "Belgrano",
                "Belleza" => "Belleza",
                "BenchNine" => "BenchNine",
                "Bentham" => "Bentham",
                "Berkshire Swash" => "Berkshire Swash",
                "Bevan" => "Bevan",
                "Bigelow Rules" => "Bigelow Rules",
                "Bigshot One" => "Bigshot One",
                "Bilbo" => "Bilbo",
                "Bilbo Swash Caps" => "Bilbo Swash Caps",
                "Biryani" => "Biryani",
                "Bitter" => "Bitter",
                "Black Ops One" => "Black Ops One",
                "Bokor" => "Bokor",
                "Bonbon" => "Bonbon",
                "Boogaloo" => "Boogaloo",
                "Bowlby One" => "Bowlby One",
                "Bowlby One SC" => "Bowlby One SC",
                "Brawler" => "Brawler",
                "Bree Serif" => "Bree Serif",
                "Bubblegum Sans" => "Bubblegum Sans",
                "Bubbler One" => "Bubbler One",
                "Buda" => "Buda",
                "Buenard" => "Buenard",
                "Butcherman" => "Butcherman",
                "Butterfly Kids" => "Butterfly Kids",
                "Cabin" => "Cabin",
                "Cabin Condensed" => "Cabin Condensed",
                "Cabin Sketch" => "Cabin Sketch",
                "Caesar Dressing" => "Caesar Dressing",
                "Cagliostro" => "Cagliostro",
                "Calligraffitti" => "Calligraffitti",
                "Cambay" => "Cambay",
                "Cambo" => "Cambo",
                "Candal" => "Candal",
                "Cantarell" => "Cantarell",
                "Cantata One" => "Cantata One",
                "Cantora One" => "Cantora One",
                "Capriola" => "Capriola",
                "Cardo" => "Cardo",
                "Carme" => "Carme",
                "Carrois Gothic" => "Carrois Gothic",
                "Carrois Gothic SC" => "Carrois Gothic SC",
                "Carter One" => "Carter One",
                "Catamaran" => "Catamaran",
                "Caudex" => "Caudex",
                "Cedarville Cursive" => "Cedarville Cursive",
                "Ceviche One" => "Ceviche One",
                "Changa One" => "Changa One",
                "Chango" => "Chango",
                "Chau Philomene One" => "Chau Philomene One",
                "Chela One" => "Chela One",
                "Chelsea Market" => "Chelsea Market",
                "Chenla" => "Chenla",
                "Cherry Cream Soda" => "Cherry Cream Soda",
                "Cherry Swash" => "Cherry Swash",
                "Chewy" => "Chewy",
                "Chicle" => "Chicle",
                "Chivo" => "Chivo",
                "Chonburi" => "Chonburi",
                "Cinzel" => "Cinzel",
                "Cinzel Decorative" => "Cinzel Decorative",
                "Clicker Script" => "Clicker Script",
                "Coda" => "Coda",
                "Coda Caption" => "Coda Caption",
                "Codystar" => "Codystar",
                "Combo" => "Combo",
                "Comfortaa" => "Comfortaa",
                "Coming Soon" => "Coming Soon",
                "Concert One" => "Concert One",
                "Condiment" => "Condiment",
                "Content" => "Content",
                "Contrail One" => "Contrail One",
                "Convergence" => "Convergence",
                "Cookie" => "Cookie",
                "Copse" => "Copse",
                "Corben" => "Corben",
                "Courgette" => "Courgette",
                "Cousine" => "Cousine",
                "Coustard" => "Coustard",
                "Covered By Your Grace" => "Covered By Your Grace",
                "Crafty Girls" => "Crafty Girls",
                "Creepster" => "Creepster",
                "Crete Round" => "Crete Round",
                "Crimson Text" => "Crimson Text",
                "Croissant One" => "Croissant One",
                "Crushed" => "Crushed",
                "Cuprum" => "Cuprum",
                "Cutive" => "Cutive",
                "Cutive Mono" => "Cutive Mono",
                "Damion" => "Damion",
                "Dancing Script" => "Dancing Script",
                "Dangrek" => "Dangrek",
                "Dawning of a New Day" => "Dawning of a New Day",
                "Days One" => "Days One",
                "Dekko" => "Dekko",
                "Delius" => "Delius",
                "Delius Swash Caps" => "Delius Swash Caps",
                "Delius Unicase" => "Delius Unicase",
                "Della Respira" => "Della Respira",
                "Denk One" => "Denk One",
                "Devonshire" => "Devonshire",
                "Dhurjati" => "Dhurjati",
                "Didact Gothic" => "Didact Gothic",
                "Diplomata" => "Diplomata",
                "Diplomata SC" => "Diplomata SC",
                "Domine" => "Domine",
                "Donegal One" => "Donegal One",
                "Doppio One" => "Doppio One",
                "Dorsa" => "Dorsa",
                "Dosis" => "Dosis",
                "Dr Sugiyama" => "Dr Sugiyama",
                "Droid Sans" => "Droid Sans",
                "Droid Sans Mono" => "Droid Sans Mono",
                "Droid Serif" => "Droid Serif",
                "Duru Sans" => "Duru Sans",
                "Dynalight" => "Dynalight",
                "EB Garamond" => "EB Garamond",
                "Eagle Lake" => "Eagle Lake",
                "Eater" => "Eater",
                "Economica" => "Economica",
                "Eczar" => "Eczar",
                "Ek Mukta" => "Ek Mukta",
                "Electrolize" => "Electrolize",
                "Elsie" => "Elsie",
                "Elsie Swash Caps" => "Elsie Swash Caps",
                "Emblema One" => "Emblema One",
                "Emilys Candy" => "Emilys Candy",
                "Engagement" => "Engagement",
                "Englebert" => "Englebert",
                "Enriqueta" => "Enriqueta",
                "Erica One" => "Erica One",
                "Esteban" => "Esteban",
                "Euphoria Script" => "Euphoria Script",
                "Ewert" => "Ewert",
                "Exo" => "Exo",
                "Exo 2" => "Exo 2",
                "Expletus Sans" => "Expletus Sans",
                "Fanwood Text" => "Fanwood Text",
                "Fascinate" => "Fascinate",
                "Fascinate Inline" => "Fascinate Inline",
                "Faster One" => "Faster One",
                "Fasthand" => "Fasthand",
                "Fauna One" => "Fauna One",
                "Federant" => "Federant",
                "Federo" => "Federo",
                "Felipa" => "Felipa",
                "Fenix" => "Fenix",
                "Finger Paint" => "Finger Paint",
                "Fira Mono" => "Fira Mono",
                "Fira Sans" => "Fira Sans",
                "Fjalla One" => "Fjalla One",
                "Fjord One" => "Fjord One",
                "Flamenco" => "Flamenco",
                "Flavors" => "Flavors",
                "Fondamento" => "Fondamento",
                "Fontdiner Swanky" => "Fontdiner Swanky",
                "Forum" => "Forum",
                "Francois One" => "Francois One",
                "Freckle Face" => "Freckle Face",
                "Fredericka the Great" => "Fredericka the Great",
                "Fredoka One" => "Fredoka One",
                "Freehand" => "Freehand",
                "Fresca" => "Fresca",
                "Frijole" => "Frijole",
                "Fruktur" => "Fruktur",
                "Fugaz One" => "Fugaz One",
                "GFS Didot" => "GFS Didot",
                "GFS Neohellenic" => "GFS Neohellenic",
                "Gabriela" => "Gabriela",
                "Gafata" => "Gafata",
                "Galdeano" => "Galdeano",
                "Galindo" => "Galindo",
                "Gentium Basic" => "Gentium Basic",
                "Gentium Book Basic" => "Gentium Book Basic",
                "Geo" => "Geo",
                "Geostar" => "Geostar",
                "Geostar Fill" => "Geostar Fill",
                "Germania One" => "Germania One",
                "Gidugu" => "Gidugu",
                "Gilda Display" => "Gilda Display",
                "Give You Glory" => "Give You Glory",
                "Glass Antiqua" => "Glass Antiqua",
                "Glegoo" => "Glegoo",
                "Gloria Hallelujah" => "Gloria Hallelujah",
                "Goblin One" => "Goblin One",
                "Gochi Hand" => "Gochi Hand",
                "Gorditas" => "Gorditas",
                "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
                "Graduate" => "Graduate",
                "Grand Hotel" => "Grand Hotel",
                "Gravitas One" => "Gravitas One",
                "Great Vibes" => "Great Vibes",
                "Griffy" => "Griffy",
                "Gruppo" => "Gruppo",
                "Gudea" => "Gudea",
                "Gurajada" => "Gurajada",
                "Habibi" => "Habibi",
                "Halant" => "Halant",
                "Hammersmith One" => "Hammersmith One",
                "Hanalei" => "Hanalei",
                "Hanalei Fill" => "Hanalei Fill",
                "Handlee" => "Handlee",
                "Hanuman" => "Hanuman",
                "Happy Monkey" => "Happy Monkey",
                "Headland One" => "Headland One",
                "Henny Penny" => "Henny Penny",
                "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
                "Hind" => "Hind",
                "Hind Siliguri" => "Hind Siliguri",
                "Hind Vadodara" => "Hind Vadodara",
                "Holtwood One SC" => "Holtwood One SC",
                "Homemade Apple" => "Homemade Apple",
                "Homenaje" => "Homenaje",
                "IM Fell DW Pica" => "IM Fell DW Pica",
                "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
                "IM Fell Double Pica" => "IM Fell Double Pica",
                "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
                "IM Fell English" => "IM Fell English",
                "IM Fell English SC" => "IM Fell English SC",
                "IM Fell French Canon" => "IM Fell French Canon",
                "IM Fell French Canon SC" => "IM Fell French Canon SC",
                "IM Fell Great Primer" => "IM Fell Great Primer",
                "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
                "Iceberg" => "Iceberg",
                "Iceland" => "Iceland",
                "Imprima" => "Imprima",
                "Inconsolata" => "Inconsolata",
                "Inder" => "Inder",
                "Indie Flower" => "Indie Flower",
                "Inika" => "Inika",
                "Inknut Antiqua" => "Inknut Antiqua",
                "Irish Grover" => "Irish Grover",
                "Istok Web" => "Istok Web",
                "Italiana" => "Italiana",
                "Italianno" => "Italianno",
                "Itim" => "Itim",
                "Jacques Francois" => "Jacques Francois",
                "Jacques Francois Shadow" => "Jacques Francois Shadow",
                "Jaldi" => "Jaldi",
                "Jim Nightshade" => "Jim Nightshade",
                "Jockey One" => "Jockey One",
                "Jolly Lodger" => "Jolly Lodger",
                "Josefin Sans" => "Josefin Sans",
                "Josefin Slab" => "Josefin Slab",
                "Joti One" => "Joti One",
                "Judson" => "Judson",
                "Julee" => "Julee",
                "Julius Sans One" => "Julius Sans One",
                "Junge" => "Junge",
                "Jura" => "Jura",
                "Just Another Hand" => "Just Another Hand",
                "Just Me Again Down Here" => "Just Me Again Down Here",
                "Kadwa" => "Kadwa",
                "Kalam" => "Kalam",
                "Kameron" => "Kameron",
                "Kantumruy" => "Kantumruy",
                "Karla" => "Karla",
                "Karma" => "Karma",
                "Kaushan Script" => "Kaushan Script",
                "Kavoon" => "Kavoon",
                "Kdam Thmor" => "Kdam Thmor",
                "Keania One" => "Keania One",
                "Kelly Slab" => "Kelly Slab",
                "Kenia" => "Kenia",
                "Khand" => "Khand",
                "Khmer" => "Khmer",
                "Khula" => "Khula",
                "Kite One" => "Kite One",
                "Knewave" => "Knewave",
                "Kotta One" => "Kotta One",
                "Koulen" => "Koulen",
                "Kranky" => "Kranky",
                "Kreon" => "Kreon",
                "Kristi" => "Kristi",
                "Krona One" => "Krona One",
                "Kurale" => "Kurale",
                "La Belle Aurore" => "La Belle Aurore",
                "Laila" => "Laila",
                "Lakki Reddy" => "Lakki Reddy",
                "Lancelot" => "Lancelot",
                "Lateef" => "Lateef",
                "Lato" => "Lato",
                "League Script" => "League Script",
                "Leckerli One" => "Leckerli One",
                "Ledger" => "Ledger",
                "Lekton" => "Lekton",
                "Lemon" => "Lemon",
                "Libre Baskerville" => "Libre Baskerville",
                "Life Savers" => "Life Savers",
                "Lilita One" => "Lilita One",
                "Lily Script One" => "Lily Script One",
                "Limelight" => "Limelight",
                "Linden Hill" => "Linden Hill",
                "Lobster" => "Lobster",
                "Lobster Two" => "Lobster Two",
                "Londrina Outline" => "Londrina Outline",
                "Londrina Shadow" => "Londrina Shadow",
                "Londrina Sketch" => "Londrina Sketch",
                "Londrina Solid" => "Londrina Solid",
                "Lora" => "Lora",
                "Love Ya Like A Sister" => "Love Ya Like A Sister",
                "Loved by the King" => "Loved by the King",
                "Lovers Quarrel" => "Lovers Quarrel",
                "Luckiest Guy" => "Luckiest Guy",
                "Lusitana" => "Lusitana",
                "Lustria" => "Lustria",
                "Macondo" => "Macondo",
                "Macondo Swash Caps" => "Macondo Swash Caps",
                "Magra" => "Magra",
                "Maiden Orange" => "Maiden Orange",
                "Mako" => "Mako",
                "Mallanna" => "Mallanna",
                "Mandali" => "Mandali",
                "Marcellus" => "Marcellus",
                "Marcellus SC" => "Marcellus SC",
                "Marck Script" => "Marck Script",
                "Margarine" => "Margarine",
                "Marko One" => "Marko One",
                "Marmelad" => "Marmelad",
                "Martel" => "Martel",
                "Martel Sans" => "Martel Sans",
                "Marvel" => "Marvel",
                "Mate" => "Mate",
                "Mate SC" => "Mate SC",
                "Maven Pro" => "Maven Pro",
                "McLaren" => "McLaren",
                "Meddon" => "Meddon",
                "MedievalSharp" => "MedievalSharp",
                "Medula One" => "Medula One",
                "Megrim" => "Megrim",
                "Meie Script" => "Meie Script",
                "Merienda" => "Merienda",
                "Merienda One" => "Merienda One",
                "Merriweather" => "Merriweather",
                "Merriweather Sans" => "Merriweather Sans",
                "Metal" => "Metal",
                "Metal Mania" => "Metal Mania",
                "Metamorphous" => "Metamorphous",
                "Metrophobic" => "Metrophobic",
                "Michroma" => "Michroma",
                "Milonga" => "Milonga",
                "Miltonian" => "Miltonian",
                "Miltonian Tattoo" => "Miltonian Tattoo",
                "Miniver" => "Miniver",
                "Miss Fajardose" => "Miss Fajardose",
                "Modak" => "Modak",
                "Modern Antiqua" => "Modern Antiqua",
                "Molengo" => "Molengo",
                "Molle" => "Molle",
                "Monda" => "Monda",
                "Monofett" => "Monofett",
                "Monoton" => "Monoton",
                "Monsieur La Doulaise" => "Monsieur La Doulaise",
                "Montaga" => "Montaga",
                "Montez" => "Montez",
                "Montserrat" => "Montserrat",
                "Montserrat Alternates" => "Montserrat Alternates",
                "Montserrat Subrayada" => "Montserrat Subrayada",
                "Moul" => "Moul",
                "Moulpali" => "Moulpali",
                "Mountains of Christmas" => "Mountains of Christmas",
                "Mouse Memoirs" => "Mouse Memoirs",
                "Mr Bedfort" => "Mr Bedfort",
                "Mr Dafoe" => "Mr Dafoe",
                "Mr De Haviland" => "Mr De Haviland",
                "Mrs Saint Delafield" => "Mrs Saint Delafield",
                "Mrs Sheppards" => "Mrs Sheppards",
                "Muli" => "Muli",
                "Mystery Quest" => "Mystery Quest",
                "NTR" => "NTR",
                "Neucha" => "Neucha",
                "Neuton" => "Neuton",
                "New Rocker" => "New Rocker",
                "News Cycle" => "News Cycle",
                "Niconne" => "Niconne",
                "Nixie One" => "Nixie One",
                "Nobile" => "Nobile",
                "Nokora" => "Nokora",
                "Norican" => "Norican",
                "Nosifer" => "Nosifer",
                "Nothing You Could Do" => "Nothing You Could Do",
                "Noticia Text" => "Noticia Text",
                "Noto Sans" => "Noto Sans",
                "Noto Serif" => "Noto Serif",
                "Nova Cut" => "Nova Cut",
                "Nova Flat" => "Nova Flat",
                "Nova Mono" => "Nova Mono",
                "Nova Oval" => "Nova Oval",
                "Nova Round" => "Nova Round",
                "Nova Script" => "Nova Script",
                "Nova Slim" => "Nova Slim",
                "Nova Square" => "Nova Square",
                "Numans" => "Numans",
                "Nunito" => "Nunito",
                "Odor Mean Chey" => "Odor Mean Chey",
                "Offside" => "Offside",
                "Old Standard TT" => "Old Standard TT",
                "Oldenburg" => "Oldenburg",
                "Oleo Script" => "Oleo Script",
                "Oleo Script Swash Caps" => "Oleo Script Swash Caps",
                "Open Sans" => "Open Sans",
                "Open Sans Condensed" => "Open Sans Condensed",
                "Oranienbaum" => "Oranienbaum",
                "Orbitron" => "Orbitron",
                "Oregano" => "Oregano",
                "Orienta" => "Orienta",
                "Original Surfer" => "Original Surfer",
                "Oswald" => "Oswald",
                "Over the Rainbow" => "Over the Rainbow",
                "Overlock" => "Overlock",
                "Overlock SC" => "Overlock SC",
                "Ovo" => "Ovo",
                "Oxygen" => "Oxygen",
                "Oxygen Mono" => "Oxygen Mono",
                "PT Mono" => "PT Mono",
                "PT Sans" => "PT Sans",
                "PT Sans Caption" => "PT Sans Caption",
                "PT Sans Narrow" => "PT Sans Narrow",
                "PT Serif" => "PT Serif",
                "PT Serif Caption" => "PT Serif Caption",
                "Pacifico" => "Pacifico",
                "Palanquin" => "Palanquin",
                "Palanquin Dark" => "Palanquin Dark",
                "Paprika" => "Paprika",
                "Parisienne" => "Parisienne",
                "Passero One" => "Passero One",
                "Passion One" => "Passion One",
                "Pathway Gothic One" => "Pathway Gothic One",
                "Patrick Hand" => "Patrick Hand",
                "Patrick Hand SC" => "Patrick Hand SC",
                "Patua One" => "Patua One",
                "Paytone One" => "Paytone One",
                "Peddana" => "Peddana",
                "Peralta" => "Peralta",
                "Permanent Marker" => "Permanent Marker",
                "Petit Formal Script" => "Petit Formal Script",
                "Petrona" => "Petrona",
                "Philosopher" => "Philosopher",
                "Piedra" => "Piedra",
                "Pinyon Script" => "Pinyon Script",
                "Pirata One" => "Pirata One",
                "Plaster" => "Plaster",
                "Play" => "Play",
                "Playball" => "Playball",
                "Playfair Display" => "Playfair Display",
                "Playfair Display SC" => "Playfair Display SC",
                "Podkova" => "Podkova",
                "Poiret One" => "Poiret One",
                "Poller One" => "Poller One",
                "Poly" => "Poly",
                "Pompiere" => "Pompiere",
                "Pontano Sans" => "Pontano Sans",
                "Poppins" => "Poppins",
                "Port Lligat Sans" => "Port Lligat Sans",
                "Port Lligat Slab" => "Port Lligat Slab",
                "Pragati Narrow" => "Pragati Narrow",
                "Prata" => "Prata",
                "Preahvihear" => "Preahvihear",
                "Press Start 2P" => "Press Start 2P",
                "Princess Sofia" => "Princess Sofia",
                "Prociono" => "Prociono",
                "Prosto One" => "Prosto One",
                "Puritan" => "Puritan",
                "Purple Purse" => "Purple Purse",
                "Quando" => "Quando",
                "Quantico" => "Quantico",
                "Quattrocento" => "Quattrocento",
                "Quattrocento Sans" => "Quattrocento Sans",
                "Questrial" => "Questrial",
                "Quicksand" => "Quicksand",
                "Quintessential" => "Quintessential",
                "Qwigley" => "Qwigley",
                "Racing Sans One" => "Racing Sans One",
                "Radley" => "Radley",
                "Rajdhani" => "Rajdhani",
                "Raleway" => "Raleway",
                "Raleway Dots" => "Raleway Dots",
                "Ramabhadra" => "Ramabhadra",
                "Ramaraja" => "Ramaraja",
                "Rambla" => "Rambla",
                "Rammetto One" => "Rammetto One",
                "Ranchers" => "Ranchers",
                "Rancho" => "Rancho",
                "Ranga" => "Ranga",
                "Rationale" => "Rationale",
                "Ravi Prakash" => "Ravi Prakash",
                "Redressed" => "Redressed",
                "Reenie Beanie" => "Reenie Beanie",
                "Revalia" => "Revalia",
                "Rhodium Libre" => "Rhodium Libre",
                "Ribeye" => "Ribeye",
                "Ribeye Marrow" => "Ribeye Marrow",
                "Righteous" => "Righteous",
                "Risque" => "Risque",
                "Roboto" => "Roboto",
                "Roboto Condensed" => "Roboto Condensed",
                "Roboto Mono" => "Roboto Mono",
                "Roboto Slab" => "Roboto Slab",
                "Rochester" => "Rochester",
                "Rock Salt" => "Rock Salt",
                "Rokkitt" => "Rokkitt",
                "Romanesco" => "Romanesco",
                "Ropa Sans" => "Ropa Sans",
                "Rosario" => "Rosario",
                "Rosarivo" => "Rosarivo",
                "Rouge Script" => "Rouge Script",
                "Rozha One" => "Rozha One",
                "Rubik" => "Rubik",
                "Rubik Mono One" => "Rubik Mono One",
                "Rubik One" => "Rubik One",
                "Ruda" => "Ruda",
                "Rufina" => "Rufina",
                "Ruge Boogie" => "Ruge Boogie",
                "Ruluko" => "Ruluko",
                "Rum Raisin" => "Rum Raisin",
                "Ruslan Display" => "Ruslan Display",
                "Russo One" => "Russo One",
                "Ruthie" => "Ruthie",
                "Rye" => "Rye",
                "Sacramento" => "Sacramento",
                "Sahitya" => "Sahitya",
                "Sail" => "Sail",
                "Salsa" => "Salsa",
                "Sanchez" => "Sanchez",
                "Sancreek" => "Sancreek",
                "Sansita One" => "Sansita One",
                "Sarala" => "Sarala",
                "Sarina" => "Sarina",
                "Sarpanch" => "Sarpanch",
                "Satisfy" => "Satisfy",
                "Scada" => "Scada",
                "Scheherazade" => "Scheherazade",
                "Schoolbell" => "Schoolbell",
                "Seaweed Script" => "Seaweed Script",
                "Sevillana" => "Sevillana",
                "Seymour One" => "Seymour One",
                "Shadows Into Light" => "Shadows Into Light",
                "Shadows Into Light Two" => "Shadows Into Light Two",
                "Shanti" => "Shanti",
                "Share" => "Share",
                "Share Tech" => "Share Tech",
                "Share Tech Mono" => "Share Tech Mono",
                "Shojumaru" => "Shojumaru",
                "Short Stack" => "Short Stack",
                "Siemreap" => "Siemreap",
                "Sigmar One" => "Sigmar One",
                "Signika" => "Signika",
                "Signika Negative" => "Signika Negative",
                "Simonetta" => "Simonetta",
                "Sintony" => "Sintony",
                "Sirin Stencil" => "Sirin Stencil",
                "Six Caps" => "Six Caps",
                "Skranji" => "Skranji",
                "Slabo 13px" => "Slabo 13px",
                "Slabo 27px" => "Slabo 27px",
                "Slackey" => "Slackey",
                "Smokum" => "Smokum",
                "Smythe" => "Smythe",
                "Sniglet" => "Sniglet",
                "Snippet" => "Snippet",
                "Snowburst One" => "Snowburst One",
                "Sofadi One" => "Sofadi One",
                "Sofia" => "Sofia",
                "Sonsie One" => "Sonsie One",
                "Sorts Mill Goudy" => "Sorts Mill Goudy",
                "Source Code Pro" => "Source Code Pro",
                "Source Sans Pro" => "Source Sans Pro",
                "Source Serif Pro" => "Source Serif Pro",
                "Special Elite" => "Special Elite",
                "Spicy Rice" => "Spicy Rice",
                "Spinnaker" => "Spinnaker",
                "Spirax" => "Spirax",
                "Squada One" => "Squada One",
                "Sree Krushnadevaraya" => "Sree Krushnadevaraya",
                "Stalemate" => "Stalemate",
                "Stalinist One" => "Stalinist One",
                "Stardos Stencil" => "Stardos Stencil",
                "Stint Ultra Condensed" => "Stint Ultra Condensed",
                "Stint Ultra Expanded" => "Stint Ultra Expanded",
                "Stoke" => "Stoke",
                "Strait" => "Strait",
                "Sue Ellen Francisco" => "Sue Ellen Francisco",
                "Sumana" => "Sumana",
                "Sunshiney" => "Sunshiney",
                "Supermercado One" => "Supermercado One",
                "Sura" => "Sura",
                "Suranna" => "Suranna",
                "Suravaram" => "Suravaram",
                "Suwannaphum" => "Suwannaphum",
                "Swanky and Moo Moo" => "Swanky and Moo Moo",
                "Syncopate" => "Syncopate",
                "Tangerine" => "Tangerine",
                "Taprom" => "Taprom",
                "Tauri" => "Tauri",
                "Teko" => "Teko",
                "Telex" => "Telex",
                "Tenali Ramakrishna" => "Tenali Ramakrishna",
                "Tenor Sans" => "Tenor Sans",
                "Text Me One" => "Text Me One",
                "The Girl Next Door" => "The Girl Next Door",
                "Tienne" => "Tienne",
                "Tillana" => "Tillana",
                "Timmana" => "Timmana",
                "Tinos" => "Tinos",
                "Titan One" => "Titan One",
                "Titillium Web" => "Titillium Web",
                "Trade Winds" => "Trade Winds",
                "Trocchi" => "Trocchi",
                "Trochut" => "Trochut",
                "Trykker" => "Trykker",
                "Tulpen One" => "Tulpen One",
                "Ubuntu" => "Ubuntu",
                "Ubuntu Condensed" => "Ubuntu Condensed",
                "Ubuntu Mono" => "Ubuntu Mono",
                "Ultra" => "Ultra",
                "Uncial Antiqua" => "Uncial Antiqua",
                "Underdog" => "Underdog",
                "Unica One" => "Unica One",
                "UnifrakturCook" => "UnifrakturCook",
                "UnifrakturMaguntia" => "UnifrakturMaguntia",
                "Unkempt" => "Unkempt",
                "Unlock" => "Unlock",
                "Unna" => "Unna",
                "VT323" => "VT323",
                "Vampiro One" => "Vampiro One",
                "Varela" => "Varela",
                "Varela Round" => "Varela Round",
                "Vast Shadow" => "Vast Shadow",
                "Vesper Libre" => "Vesper Libre",
                "Vibur" => "Vibur",
                "Vidaloka" => "Vidaloka",
                "Viga" => "Viga",
                "Voces" => "Voces",
                "Volkhov" => "Volkhov",
                "Vollkorn" => "Vollkorn",
                "Voltaire" => "Voltaire",
                "Waiting for the Sunrise" => "Waiting for the Sunrise",
                "Wallpoet" => "Wallpoet",
                "Walter Turncoat" => "Walter Turncoat",
                "Warnes" => "Warnes",
                "Wellfleet" => "Wellfleet",
                "Wendy One" => "Wendy One",
                "Wire One" => "Wire One",
                "Work Sans" => "Work Sans",
                "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
                "Yantramanav" => "Yantramanav",
                "Yellowtail" => "Yellowtail",
                "Yeseva One" => "Yeseva One",
                "Yesteryear" => "Yesteryear",
                "Zeyada" => "Zeyada",
            );


    if($return) return $ts_google_fonts;
}

function ts_all_fonts($return = false)
{
    global $ts_all_fonts, $ts_standard_fonts, $ts_google_fonts;

    ts_standard_fonts();
    ts_google_fonts();

    $ts_all_fonts = array_merge($ts_standard_fonts, $ts_google_fonts);

    if($return) return $ts_all_fonts;
}

//Return array ids of liked post of user
function getLikedPostsIds($user_id){
    global $wpdb;
    $ret = array();

    $likes_table = $wpdb->prefix . 'jrrny_likes';
    $sql = "SELECT post_id from ";
    $sql .= $likes_table ." ";
    $sql .= "WHERE user_id='";
    $sql .= intval($user_id);
    $sql .="';";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $result = $wpdb->get_results( $sql );
    foreach ($result as $key => $value) {
        $ret[] = intval($value->post_id);
    }
    return $ret;
}

//Return count of upvotes That user has given.
function getUpvotesCount($user_id){
    global $wpdb;

    $likes_table = $wpdb->prefix . 'jrrny_likes';

    $sql = "SELECT count(*) as count from ";
    $sql .= $likes_table ." ";
    $sql .= "WHERE user_id='";
    $sql .= intval($user_id);
    $sql .="';";

    $result = $wpdb->get_results( $sql );
    if(!isset($result[0])){
        return 0;
    }
    return $result[0]->count;
}
//Return count of created jrrnys of user
function getJrrnysCount($user_id){
    global $wpdb;

    $posts_table = $wpdb->prefix . 'posts';

    $sql = "SELECT count(*) as count from ";
    $sql .= $posts_table ." ";
    $sql .= "WHERE post_author='";
    $sql .= intval($user_id);
    $sql .="' ";
    $sql .= "AND post_status='publish' " ;
    $sql .= "AND post_type='post';";

    $result = $wpdb->get_results( $sql );
    if(!isset($result[0])){
        return 0;
    }
    return $result[0]->count;
}
//Return array ids of trening post 
function getTrendingPostsIds($limit = null){
    global $wpdb;
    $ret = array();

    $table = $wpdb->prefix . 'jrrny_trending';
    $sql = "SELECT post_id from ";
    $sql .= $table ." ";
    $sql .= "WHERE weekly_likes > '0' ";
    $sql .= "ORDER BY weekly_likes DESC ";
    if($limit){        
        $sql .= "LIMIT " . $limit . " ";
    }
    $sql .=";";

    $result = $wpdb->get_results( $sql );
    foreach ($result as $key => $value) {
        $ret[] = intval($value->post_id);
    }
    return $ret;
}
function getViewsPerDay($timestamp, $userId){
    global $wpdb;
    $table_name = $wpdb->prefix . "jrrny_view_user";

    $startDay = strtotime("midnight", $timestamp);
    $endDay = strtotime('+1 day', $startDay);

    $sql = "SELECT count(*) as count FROM " . $table_name ." ";
    $sql .= "WHERE user_id=" . intval($userId) . " ";
    $sql .= "AND timestamp >= '" . date('Y-m-d',$timestamp) . " 00:00:00' ";
    $sql .= "AND timestamp < '" . date('Y-m-d',$timestamp) . " 23:59:59' ";
    $result = $wpdb->get_results($sql);
    return reset($result)->count;
}

function getMapPlaces(){
    global $wpdb;
    $table_name = $wpdb->prefix . "jrrny_place";
    $sql = 'SELECT * ';
    $sql .= 'FROM '.$table_name.' ';
    $places = $wpdb->get_results( $sql, OBJECT );
    $ret = [];
    foreach($places as $key => $place){
        $ret[$key] = [
            'id' => $place->id,
            'lat' => $place->lat,
            'lon' => $place->lon,
            'title' => $place->title
        ];
    }

    return $ret;
}
function getMapPlace($id = NULL){
    global $wpdb;
    
    $table_name = $wpdb->prefix . "jrrny_place";
    
    if(!$id){
        return false;
    }
    
    $sql = 'SELECT * ';
    $sql .= 'FROM '.$table_name.' ';
    $sql .= 'WHERE  id = '.$id.' ';
    
    $place = $wpdb->get_row( $sql, OBJECT );    

    return $place;
}

add_action( 'admin_init', 'admin_place' );
function admin_place() {
    add_meta_box( 'place_meta_box', 'Place', 'display_custom_meta_box','post', 'normal', 'high' );
}
function display_custom_meta_box( $custom ) {
    ?>
    <?php
    $places = getMapPlaces();
    $placeSelect = intval(get_post_meta( $custom->ID, '_place_id', true ));
    ?>
    <table width="100%">
        <tr>
            <td style="width: 25%">Place title</td>
            <td>
            <select name="meta[_place_id]">
                <option >Select...</option>
                <?php foreach($places as $place): ?>
                    <option <?= ($place['id'] == $placeSelect) ? 'selected="selected' : ''?>value="<?= $place['id']?>"><?= $place['title']?></option>
                <?php endforeach ?>
            </select>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'save_post', 'add_place_fields', 10, 2 );
function add_place_fields( $custom_id, $custom ) {
    if ( $custom->post_type == 'post' ) {
        if ( isset( $_POST['meta'] ) ) {
            foreach( $_POST['meta'] as $key => $value ){
                update_post_meta( $custom_id, $key, $value );
            }
        }
    }
}

function getLikedArr(){
    global $wpdb;
    $ret = [];
    $user_id = get_current_user_id();
    if($user_id != 0){
        $table_name = $wpdb->prefix . 'jrrny_likes';
        $sql = 'SELECT post_id FROM '. $table_name.' WHERE user_id = '.$user_id;
        $results = $wpdb->get_results( $sql, OBJECT );
        foreach($results as $res){
            $ret[] = $res->post_id;
        }

    }
    return $ret;
}
function getFollowedArr(){
    global $wpdb;
    $ret = [];
    $user_id = get_current_user_id();
    if($user_id != 0){
        $table_name = $wpdb->prefix . 'jrrny_follow';
        $sql = 'SELECT follow_user_id FROM '. $table_name.' WHERE user_id = '.$user_id;
        $results = $wpdb->get_results( $sql, OBJECT );
        foreach($results as $res){
            $ret[] = $res->follow_user_id;
        }

    }
    return $ret;
}
function ajax_refresh_after_login()
{
    global $current_user;
    
    header('Content-Type: application/json');
    
    $homepage = '';
    $user_widget = '';
    $user_edit_form = '';
    
    $homepage_post = $_POST['homepage'];
    if($homepage_post == 1){
        ob_start();    
        get_template_part("inc/frontend/homepage/logged_ajax");
        $homepage = ob_get_clean();
        
        ob_start();    
        get_template_part("inc/frontend/profile/user-widget-homepage");
        $user_widget = ob_get_clean();
        
        if(is_user_logged_in() && current_user_can('edit_user', $current_user->ID)){ 
            ob_start();  
            get_template_part('inc/frontend/profile/edit-form', 'homepage'); 
            $user_edit_form = ob_get_clean();
        }
    }
    $menu_left = plc_nav('left');
    $menu_right = plc_nav('right');
    $response = array(
        'menu_left' => $menu_left,
        'menu_right' => $menu_right,
        'liked' => getLikedArr(),
        'followed' => getFollowedArr(),
        'homepage' => $homepage,
        'user_widget' => $user_widget,
        'user_edit_form' => $user_edit_form
    );
    echo json_encode($response);
    die();
}

add_action('wp_ajax_refresh_after_login', 'ajax_refresh_after_login');
add_action('wp_ajax_nopriv_refresh_after_login', 'ajax_refresh_after_login');

function plc_get_loop(){
    
}

function ajax_plc_get_loop(){
    
    header('Content-Type: application/json');

    $atts = $_POST['atts'];
    $info = $_POST['info'];
    $paged = isset($_POST['paged']) ? (int) $_POST['paged'] : 1;
    $atts = unserialize(base64_decode($atts));
    $info = unserialize(base64_decode($info));
    
    $info['paged'] = $paged + 1;
    $info['is_final'] = isset($atts['is_final']) ? $atts['is_final'] : 1;
    $atts['paged'] = $paged + 1;  
    
    $already_shown = $atts['posts_per_page'] * $atts['paged'];
    $is_final = 0;
    if($already_shown >= $info['count']){
        $is_final = 1;
    }
    
    ob_start();    
    ts_blog_loop('thumbnailAjax', $atts);    
    $loop = ob_get_clean();
    
    $response = array(
        'atts' => base64_encode(serialize($atts)),
        'info' => base64_encode(serialize($info)),
        'loop' => $loop,
        'is_final' => $is_final,
        'paged' => $atts['paged']
    );
    echo json_encode($response);
    die();
}

add_action('wp_ajax_plc_get_loop', 'ajax_plc_get_loop');
add_action('wp_ajax_nopriv_plc_get_loop', 'ajax_plc_get_loop');


function plc_social_sharing($single = true)
{
    global $smof_data, $post;

    remove_filter('single_post_title', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('wp_title', 'wptexturize');
    
    $html = '';

    $share_text = (get_post_type() == 'portfolio') ? __('Share:', 'ThemeStockyard') : __('Share:', 'ThemeStockyard');

    if($single) :
        // set defaults...
        $title = $facebook_title = $twitter_title = $google_plus_title = get_the_title();
        $link = get_permalink();

        // accommodate for yoast
        if ( class_exists('WPSEO_Meta') && method_exists('WPSEO_Meta', 'get_value')) :
            $social_title = WPSEO_Meta::get_value('title');
            $title = (trim($social_title)) ? $social_title : $title;
            $social_link = WPSEO_Meta::get_value('canonical');
            $link = (trim($social_link)) ? $social_link : $link;

            $_facebook_title = WPSEO_Meta::get_value('opengraph-title');
            $facebook_title = (trim($_facebook_title)) ? $_facebook_title : $title;

            $_twitter_title = WPSEO_Meta::get_value('twitter-title');
            $twitter_title = (trim($_twitter_title)) ? $_twitter_title : $title;

            $_google_plus_title = WPSEO_Meta::get_value('google-plus-title');
            $google_plus_title = (trim($_google_plus_title)) ? $_google_plus_title : $title;
        endif;
    endif;
    $quote_text = 'I just shared an amazing travel experience on jrrny…';

    $title = str_replace('&amp;', 'AND', $title);
    $facebook_title = str_replace('&amp;', 'AND', $facebook_title);
    $twitter_title = str_replace('&amp;', 'AND', $twitter_title);
    $google_plus_title = str_replace('&amp;', 'AND', $google_plus_title);
    
    
    $html .= ($single) ? '<div class="share">' : '';
    $html .= '<span class="share-title">Share</span>';
    $fb_link = esc_url('https://www.facebook.com/sharer.php?u='.urlencode($link).'&amp;t='.urlencode($facebook_title)).'&quote='.urlencode($quote_text);
    $html .= '<a onclick="trackShareLink("' . $fb_link . '"); return false;" href="'. $fb_link.'" title="Facebook" rel="nofollow"><span class="flaticon flaticon-facebook-logo-button"></span></a>';
    //$tw_link = esc_url('https://twitter.com/home?status='.urlencode($twitter_title.' '.$link));
    $tw_link = esc_url('https://twitter.com/intent/tweet/?text=' . urlencode($twitter_title) . '&amp;url=' . urlencode($link));
    $html .= '<a onclick="trackShareLink("' . $tw_link . '"); return false;" href="' . $tw_link . '" title="Twitter" rel="nofollow"<span class="flaticon flaticon-twitter-logo-button"></span></a>';
    /*
    $html .= '<a href="'.esc_url('https://plus.google.com/share?url='.urlencode($link).'&amp;title='.urlencode($google_plus_title)).'" title="Google+" rel="nofollow"><span class="flaticon flaticon-google-logo-button"></span></a>';
  */
    $tr_link = esc_url('http://www.tumblr.com/share/link?url='.urlencode($link).'&amp;name='.urlencode($title));
    $html .= '<a onclick="trackShareLink("' . $tr_link . '"); return false;" href="' . $tr_link . '" title="Tumblr" rel="nofollow"><span class="flaticon flaticon-tumblr-logo-button"></span></a>';
    $ln_link = esc_url('http://linkedin.com/shareArticle?mini=true&amp;url='.urlencode($link).'&amp;title='.urlencode($title));
    $html .= '<a onclick="trackShareLink("' . $ln_link . '"); return false;" href="' . $ln_link . '" title="LinkedIn" rel="nofollow"><span class="flaticon flaticon-linkedin-logo-button"></span></a>';
    
    $html .= ($single) ? '</div>' : '';

    return $html;
}
function plc_get_collections($collections){
    $notIN = array();
    foreach($collections as $key => $value){
        $post = $value['collections_jrrnys'];
        setup_postdata( $post );
        if(isset($post->ID)){
            $notIN[] = $post->ID;
        }
    }
    wp_reset_postdata();
    foreach($collections as $key => $value){
         if($collections[$key]['collections_jrrnys'] == '') {
            $collections[$key]['collections_jrrnys'] = get_post(plc_get_random_post($notIN, array("'featured_destination'")));
         }
    }
    return $collections;
}

function plc_get_random_collections($collections, $limit = 3){
    $notIN = array();

    foreach($collections as $key => $value){
        $post = $value['collections_jrrnys'];
        setup_postdata( $post );
        if(isset($post->ID)){
            $notIN[] = $post->ID;
        }
    }
    wp_reset_postdata();

    $random_collections = array();
    $random_collections = plc_get_random_posts($notIN, array("featured_destination"), $limit);

    return $random_collections;
}

function plc_get_primary_posts(){
    $primaryPost = [
        get_option('primary_1'),
        get_option('primary_2'),
        get_option('primary_3'),
        get_option('primary_4'),
        get_option('primary_5'),
        get_option('primary_6')
    ];
    foreach($primaryPost as $key => $value) {  
        if($primaryPost[$key] == '') {
          $primaryPost[$key] = plc_get_random_post($primaryPost);
        }
    }
    return $primaryPost;
}
function plc_get_random_post($notIn = null, $post_type = array("'post'", "'sponsored_post'", "'featured_destination'", "'contest'"))
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'posts';

    $sql = "SELECT ID ";
    $sql .= "FROM " . $table_name . " ";
    $sql .= "WHERE post_type IN (" . implode(',', $post_type) . ") ";
    if($notIn){ 
        $sql .= "AND ID NOT IN (" . implode(',', array_filter($notIn)) . ") ";
    }
    $sql .= "AND post_status = 'publish' ";
    $sql .= "ORDER BY RAND() ";
    $sql .= "LIMIT 1";

    $result = $wpdb->get_var($sql);
    return $result;
}

function plc_get_random_posts($notIn = array(), $post_type = array("post", "sponsored_post", "featured_destination", "contest"), $limit = 1)
{
    $query = new WP_Query;

    $args=array(
        'orderby' => 'rand',
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'ignore_sticky_posts'=> 1,
        'post__not_in' => $notIn
    );

    $result = $query->query( $args );

    return $result;
}

function plc_top_jrrnys($jrrnys)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_likes';

    $sql = "SELECT ";
    $sql .= "post_id, COUNT(post_id) as count ";
    $sql .= "FROM " . $table_name . " ";
    $sql .= "WHERE post_id IN (" . implode(',', $jrrnys) . ") ";
    $sql .= "GROUP BY post_id ORDER BY count DESC LIMIT 3 ";

    $result = $wpdb->get_results($sql);

    $array = array();
    if($result){
        foreach($result as $value){
            $array[] = $value->post_id;
        }
    }
    
    return $array;
}
function plc_brand_social_links($ID)
{
    $facebook = get_user_meta($ID, '_facebook', true);
    $twitter = get_user_meta($ID, '_twitter', true);
    $google = get_user_meta($ID, '_google', true);
    $tumblr = get_user_meta($ID, '_tumblr', true);
    $linkedin = get_user_meta($ID, '_linkedin', true);
    
    $html = '';
    
    if($facebook){
        $html .= '<a href="'.esc_url( $facebook ).'" title="Facebook" rel="nofollow"><span class="flaticon flaticon-facebook-logo-button"></span></a>';
    }
    if($twitter){
        $html .= '<a href="'.esc_url($twitter).'" title="Twitter" rel="nofollow"><span class="flaticon flaticon-twitter-logo-button"></span></a>';
    }
    if($google){
        $html .= '<a href="'.esc_url($google).'" title="Google+" rel="nofollow"><span class="flaticon flaticon-google-plus-logo-button"></span></a>';
    }
    if($tumblr){
        $html .= '<a href="'.esc_url($tumblr).'" title="Tumblr" rel="nofollow"><span class="flaticon flaticon-tumblr-logo-button"></span></a>';
    }
    if($linkedin){
        $html .= '<a href="'.esc_url($linkedin).'" title="LinkedIn" rel="nofollow"><span class="flaticon flaticon-linkedin-logo-button"></span></a>';
    }
    return $html;
}
function img_layout($count)
{
    $layout = '';
    switch($count):
        case '1':
            $layout = '1';
        break;
        case '2':
            $layout = '2';
        break;
        case '3':
        case '5':
        case '6':
        case '8':
        case '9':
        case '11':
        case '12':
            $layout = '21';
        break;
        case '4':
        case '7':
        case '10':
            $layout = '12';
        break;

        default :
            $layout = '1';       
        break;
    endswitch; 
    
    return $layout;
}

function wp_bootstrap_pagination( $args = array() ) {
    
    $defaults = array(
        'range'           => 4,
        'custom_query'    => FALSE,
        'previous_string' => __( '<i class="fa fa-angle-left"></i>', 'text-domain' ),
        'next_string'     => __( '<i class="fa fa-angle-right"></i>', 'text-domain' ),
        'before_output'   => '<div class="post-nav"><ul class="pager">',
        'after_output'    => '</ul></div>'
    );
    
    $args = wp_parse_args( 
        $args, 
        apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
    );
    
    $args['range'] = (int) $args['range'] - 1;
    if ( !$args['custom_query'] )
        $args['custom_query'] = @$GLOBALS['wp_query'];
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval( get_query_var( 'paged' ) );
    $ceil  = ceil( $args['range'] / 2 );
    
    if ( $count <= 1 )
        return FALSE;
    
    if ( !$page )
        $page = 1;
    
    if ( $count > $args['range'] ) {
        if ( $page <= $args['range'] ) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ( $page >= ($count - $ceil) ) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    
    $echo = '';
    $previous = intval($page) - 1;
    $previous = esc_attr( get_pagenum_link($previous) );
    
    $firstpage = esc_attr( get_pagenum_link(1) );
    if ( $firstpage && (1 != $page) )
        $echo .= '<li class="previous"><a href="' . $firstpage . '">' . __( '<i class="fa fa-angle-double-left"></i>', 'text-domain' ) . '</a></li>';
    if ( $previous && (1 != $page) )
        $echo .= '<li><a href="' . $previous . '" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
    
    if ( !empty($min) && !empty($max) ) {
        for( $i = $min; $i <= $max; $i++ ) {
            if ($page == $i) {
                $echo .= '<li class="active"><span class="active">' . (int)$i. '</span></li>';
            } else {
                $echo .= sprintf( '<li><a href="%s">%2d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
            }
        }
    }
    
    $next = intval($page) + 1;
    $next = esc_attr( get_pagenum_link($next) );
    if ($next && ($count != $page) )
        $echo .= '<li><a href="' . $next . '" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
    
    $lastpage = esc_attr( get_pagenum_link($count) );
    if ( $lastpage ) {
        $echo .= '<li class="next"><a href="' . $lastpage . '">' . __( '<i class="fa fa-angle-double-right"></i>', 'text-domain' ) . '</a></li>';
    }
    if ( isset($echo) )
        echo $args['before_output'] . $echo . $args['after_output'];
}


function plc_form_contribute($atts)
{       
    $errors = array();
    $action = $_POST['action'] ? $_POST['action'] : '';
    $username = $_POST['username'] ? $_POST['username'] : '';
    $email = $_POST['email'] ? $_POST['email'] : '';
    $twitter = $_POST['twitter'] ? $_POST['twitter'] : '';
    $instagram = $_POST['instagram'] ? $_POST['instagram'] : '';
    $facebook = $_POST['facebook'] ? $_POST['facebook'] : '';
    $url = $_POST['url'] ? $_POST['url'] : '';
    $expertise = $_POST['expertise'] ? $_POST['expertise'] : '';
    $location = $_POST['location'] ? $_POST['location'] : '';
    
    if ($action === 'review') {
        if(!$username){
            $errors[] = 'Please fill a Name field';
        }
        if(!$email){
            $errors[] = 'Please fill a Email address field';
        }
        if(!$url){
            $errors[] = 'Please fill a Website field';
        }
        if(!$expertise){
            $errors[] = 'Please fill your Expertise field';
        }
        if(!$location){
            $errors[] = 'Please fill a Location field';
        }
        if(!$errors){
            plc_send_contribute();
            return '<div class="alert alert-success">Your request sent successfully.</div>';
        }
    }

    ob_start();
    require ( TS_SERVER_PATH . '/inc/shortcode/form_contribute.php' );
    $shortcode = ob_get_clean();
    
    return $shortcode;
}

add_shortcode('form_contribute', 'plc_form_contribute');

function plc_send_contribute()
{
    $title = 'Please review contributor request!';
    $email = 'admin@jrrny.com';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
    $headers .= "From: Jrrny.com <contact@jrrny.com>\r\n";
    //$headers .= "BCC: john@jrrny.com\r\n";
    //$headers .= "BCC: jeremy@jrrny.com\r\n";
    
    $content = '<div>';
        $content .= $_POST['username'] ? '<p><strong>Name</strong>: ' . $_POST['username'] . '</p>' : '';
        $content .= $_POST['email'] ? '<p><strong>E-mail</strong>: ' . $_POST['email'] . '</p>' : '';
        $content .= $_POST['twitter'] ? '<p><strong>Twitter</strong>: ' . $_POST['twitter'] . '</p>' : '';
        $content .= $_POST['instagram'] ? '<p><strong>Instagram</strong>: ' . $_POST['Instagram'] . '</p>' : '';
        $content .= $_POST['facebook'] ? '<p><strong>Facebook</strong>: ' . $_POST['facebook'] . '</p>' : '';
        $content .= $_POST['url'] ? '<p><strong>Website</strong>: ' . $_POST['url'] . '</p>' : '';
        $content .= $_POST['expertise'] ? '<p><strong>Expertise in</strong>: ' . nl2br($_POST['expertise']) . '</p>' : '';
        $content .= $_POST['location'] ? '<p><strong>Location</strong>: ' . $_POST['location'] . '</p>' : '';
    $content .= '</div>';

    wp_mail($email, $title, $content, $headers, array());
}

function plc_youtube_id_from_url($link)
{
    $url = urldecode(rawurldecode($link));

    $result = preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

    if ($result) {
        $yt_id = trim($matches[1]);
        return $yt_id;
    }

    return FALSE;
}