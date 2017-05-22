<?php
define("MAPS_API_KEY", "AIzaSyB210-NxqFkimU6VKJLCVV-j8hBcMxJ8kE");

function plc_category_unlinked($separator = ' ')
{
    $categories = (array) get_the_category();
    //var_dump($categories);
    $thelist = '';
    foreach ($categories as $category) {    // concate
        $thelist .= $separator . 'category_' . $category->cat_ID;
    }

    echo $thelist;
}

/* ------------------------------------------------------------
  Add your child theme custom functions below.
  This file does not replace the parent theme's function file.
  It is loaded in addition to the parent theme's functions file.
  ------------------------------------------------------------ */

function enqueue_theme_styles_scripts()
{
    global $current_user;
    wp_get_current_user();

    $parent_style = 'matador-style';
    wp_register_style($parent_style, get_template_directory_uri() . '/style.css', null, '1.0.0');
    wp_enqueue_style($parent_style);

    wp_register_script('jquery-ui-js', get_stylesheet_directory_uri() . '/inc/jquery-ui/jquery-ui.min.js', array('jquery'), '1.11.4');
    wp_enqueue_script('jquery-ui-js');

    wp_register_style('jquery-ui-css', get_stylesheet_directory_uri() . '/inc/jquery-ui/jquery-ui.min.css', array('jquery'));
    wp_enqueue_script('jquery-ui-css');

    wp_register_script('main-js', get_stylesheet_directory_uri() . '/inc/frontend/main.js', array('jquery'), '1.0.2');
    wp_enqueue_script('main-js');
        
    wp_register_script('timer-js', get_stylesheet_directory_uri() . '/inc/timer/timer.js', array('jquery'), '1.0.0');
    wp_enqueue_script('timer-js');

    wp_register_style('bootstrap-css', get_stylesheet_directory_uri() . '/inc/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap-css');

    wp_register_script('bootstrap-js', get_stylesheet_directory_uri() . '/inc/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.5');
    wp_enqueue_script('bootstrap-js');

    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0.4');
    wp_enqueue_script('custom-js');

    wp_register_style('custom-css', get_stylesheet_directory_uri() . '/custom.css', null, '1.1.0');
    wp_enqueue_style('custom-css');

    wp_register_style('custom-responsive', get_stylesheet_directory_uri() . '/custom-responsive.css', null, '1.0.2');
    wp_enqueue_style('custom-responsive');

    wp_register_style('font-awesome-css', get_stylesheet_directory_uri() . '/inc/font-awesome/css/font-awesome.min.css');
    wp_enqueue_style('font-awesome-css');

    wp_register_style('flaticon-css', get_stylesheet_directory_uri() . '/inc/flaticon/flaticon.css');
    wp_enqueue_style('flaticon-css');
    
    wp_register_style('slick-css', get_stylesheet_directory_uri() . '/inc/slick/slick.css', null, '1.6.0');
    wp_enqueue_style('slick-css');
    wp_register_style('slick-theme-css', get_stylesheet_directory_uri() . '/inc/slick/slick-theme.css', null, '1.6.0');
    wp_enqueue_style('slick-theme-css');
    
    wp_register_script('slick-js', get_stylesheet_directory_uri() . '/inc/slick/slick.min.js', array('jquery'), '1.6.0');
    wp_enqueue_script('slick-js');
    
    wp_register_script('validate-js', get_stylesheet_directory_uri() . '/inc/validate/validate.js', array('jquery'), '1.0.2');
    wp_enqueue_script('validate-js');
    
    wp_register_script('login-js', get_stylesheet_directory_uri() . '/inc/frontend/login/js/login.js', array('jquery'), '1.1.2');
    wp_enqueue_script('login-js');
    
    wp_register_script('author-js', get_stylesheet_directory_uri() . '/inc/frontend/profile/js/author.js', array('jquery'), '1.0.4');
    wp_enqueue_script('author-js');
    
    wp_register_script('stampready-js', 'http://www.stampready.net/api2/api.js', array('jquery'), '2.0.0');
    wp_enqueue_script('stampready-js');

    if (is_page_template('lostpassword.php')):
        wp_register_style('lostpassword-css', get_stylesheet_directory_uri() . '/inc/frontend/lostpassword/css/lostpassword.css');
        wp_enqueue_style('lostpassword-css');

        wp_register_script('lostpassword-js', get_stylesheet_directory_uri() . '/inc/frontend/lostpassword/js/lostpassword.js', array('jquery'), '1.0.0');
        wp_enqueue_script('lostpassword-js');

    endif;

    if (is_page_template('resetpassword.php')):
        wp_register_style('resetpassword-css', get_stylesheet_directory_uri() . '/inc/frontend/resetpassword/css/resetpassword.css');
        wp_enqueue_style('resetpassword-css');

        wp_register_script('resetpassword-js', get_stylesheet_directory_uri() . '/inc/frontend/resetpassword/js/resetpassword.js', array('jquery'), '1.0.0');
        wp_enqueue_script('resetpassword-js');

    endif;

    if (is_page_template('contribute.php')):

        wp_register_script('contribute-js', get_stylesheet_directory_uri() . '/inc/frontend/contribute/js/contribute.js', array('jquery'), '1.0.0');
        wp_enqueue_script('contribute-js');

    endif;
    
    if (is_page_template('upload.php') || is_post_flow('flow_creation')):
        wp_register_style('dropzone-css', get_stylesheet_directory_uri() . '/inc/dropzone/dropzone.css');
        wp_enqueue_style('dropzone-css');

        wp_register_script('dropzone-js', get_stylesheet_directory_uri() . '/inc/dropzone/dropzone.js');
        wp_enqueue_script('dropzone-js');

        wp_register_style('alertify-css', get_stylesheet_directory_uri() . '/inc/alertifyjs/css/alertify.min.css', array('theme-css', 'theme-options-css', 'upload-css', 'font-awesome'));
        wp_enqueue_style('alertify-css');

        wp_register_style('alertify-theme-css', get_stylesheet_directory_uri() . '/inc/alertifyjs/css/themes/default.css', array('alertify-css', 'theme-css', 'theme-options-css', 'upload-css', 'font-awesome'));
        wp_enqueue_style('alertify-theme-css');

        wp_register_style('upload-css', get_stylesheet_directory_uri() . '/inc/frontend/upload/upload-jrrny.css');
        wp_enqueue_style('upload-css');

        wp_register_script('upload-js', get_stylesheet_directory_uri() . '/inc/frontend/upload/upload-jrrny.js', array('jquery'), '1.0.6');
        wp_enqueue_script('upload-js');

        //wp_register_script( 'default-js', get_stylesheet_directory_uri().'/js/default.js', array('dropzone-js') );
        //wp_enqueue_script( 'default-js' );

        wp_register_script('alertify-js', get_stylesheet_directory_uri() . '/inc/alertifyjs/alertify.js', array('jquery'), '1.6.0');
        wp_enqueue_script('alertify-js');
                
        if (!is_post_flow('flow_creation')) {
            wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en', array('jquery'), '', true);
        }
    endif;
    if (is_front_page()):
        wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en', array('jquery'), '', true);
    endif;

    if (is_page_template('deals.php')):
        wp_register_script('deals-js', get_stylesheet_directory_uri() . '/inc/frontend/deals/js/deals.js');
        wp_enqueue_script('deals-js');

        wp_register_style('deals-css', get_stylesheet_directory_uri() . '/inc/frontend/deals/css/deals.css');
        wp_enqueue_style('deals-css');
    endif;

    if (is_page_template('profile.php')):
        wp_register_script('profile-js', get_stylesheet_directory_uri() . '/inc/frontend/profile/js/profile.js', array('jquery'), '1.0.0');
        wp_enqueue_script('profile-js');

        wp_register_style('profile-css', get_stylesheet_directory_uri() . '/inc/frontend/profile/css/profile.css');
        wp_enqueue_style('profile-css');
    endif;

    if (is_page_template('my-jrrnys.php')):
        wp_register_script('my-jrrnys-js', get_stylesheet_directory_uri() . '/inc/frontend/profile/js/my-jrrnys.js', array('jquery'), '1.0.0');
        wp_enqueue_script('my-jrrnys-js');

        wp_register_style('my-jrrnys-css', get_stylesheet_directory_uri() . '/inc/frontend/profile/css/my-jrrnys.css');
        wp_enqueue_style('my-jrrnys-css');
    endif;

    if (is_page_template('trending.php')):
        wp_register_script('trending-js', get_stylesheet_directory_uri() . '/inc/frontend/trending/js/trending.js', array('jquery'), '1.0.0');
        wp_enqueue_script('trending-js');

        wp_register_style('trending-css', get_stylesheet_directory_uri() . '/inc/frontend/trending/css/trending.css');
        wp_enqueue_style('trending-css');
    endif;

    if (is_author()):

        wp_register_style('morris-css', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css');
        wp_enqueue_style('morris-css');

        wp_register_script('raphael-js', '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', array('jquery'), '2.1.0');
        wp_enqueue_script('raphael-js');
        wp_register_script('morris-js', '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js', array('jquery'), '0.5.1');
        wp_enqueue_script('morris-js');
    endif;

    if (is_single()):
        if (isset($_GET["action"]) && $_GET["action"] == "edit"):
            wp_register_style('edit-single-css', get_stylesheet_directory_uri() . '/inc/frontend/single/css/edit-single.css');
            wp_enqueue_style('edit-single-css');

            wp_register_script('edit-single-js', get_stylesheet_directory_uri() . '/inc/frontend/single/js/edit-single.js', array('jquery'), '1.0.1');
            wp_enqueue_script('edit-single-js');

            wp_register_script('validate-js', get_stylesheet_directory_uri() . '/inc/validate/validate.js', array('jquery'), '1.0.2');
            wp_enqueue_script('validate-js');

            wp_register_script('mosaic-js', get_stylesheet_directory_uri() . '/inc/mosaic/mosaic.js');
            wp_enqueue_script('mosaic-js');

            wp_register_style('dropzone-css', get_stylesheet_directory_uri() . '/inc/dropzone/dropzone.css');
            wp_enqueue_style('dropzone-css');

            wp_register_script('dropzone-js', get_stylesheet_directory_uri() . '/inc/dropzone/dropzone.js');
            wp_enqueue_script('dropzone-js');

            wp_register_style('upload-css', get_stylesheet_directory_uri() . '/inc/frontend/upload/upload-jrrny.css');
            wp_enqueue_style('upload-css');

        endif;

        if(!is_user_logged_in()):                             
            wp_register_script( 'single-login-js', get_stylesheet_directory_uri().'/inc/frontend/login/js/single.js', array('jquery'), '1.0.0' );
            wp_enqueue_script('single-login-js' );
        endif;
                          
        wp_register_script('lightbox-js', get_stylesheet_directory_uri() . '/inc/frontend/lightbox/ekko-lightbox.min.js', array('jquery'), '1.0.0');
        wp_enqueue_script('lightbox-js');

        wp_register_style('lightbox-css', get_stylesheet_directory_uri() . '/inc/frontend/lightbox/ekko-lightbox.min.css');
        wp_enqueue_style('lightbox-css');

        wp_register_style('lightbox-custom', get_stylesheet_directory_uri() . '/inc/frontend/lightbox/lighbox-customization.css');
        wp_enqueue_style('lightbox-custom');

        wp_register_style('singleImages-css', get_stylesheet_directory_uri() . '/assets/css/singleImages.css');
        wp_enqueue_style('singleImages-css');
        
    endif;
    if (is_page('map') || is_single()):
        $parent_style = 'matador-style';
        wp_register_style($parent_style, get_template_directory_uri() . '/style.css');
        wp_enqueue_style($parent_style);


        wp_register_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . MAPS_API_KEY . '&sensor=false&libraries=places&language=en');
        wp_enqueue_script('gmaps');

        wp_register_script('gmap3-js', get_stylesheet_directory_uri() . '/js/gmap3.min.js', array('jquery'), '7.0');
        wp_enqueue_script('gmap3-js');

        wp_register_script('map-js', get_stylesheet_directory_uri() . '/inc/frontend/map/js/map.js', array('jquery'), '1.0.0');
        wp_enqueue_script('map-js');

        wp_register_style('map-css', get_stylesheet_directory_uri() . '/inc/frontend/map/css/map.css');
        wp_enqueue_style('map-css');
    endif;
    if(is_page('apply')){        
        $current_user = (is_user_logged_in()) ? wp_get_current_user() : null;
        if(!$current_user){
            wp_redirect('/contribute');
        }
        wp_register_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . MAPS_API_KEY . '&sensor=false&libraries=places&language=en');
        wp_enqueue_script('gmaps');
    }
}

add_action('wp_enqueue_scripts', 'enqueue_theme_styles_scripts');

/* ---------------------------------------------------------------------------- */
/* Functions - You probably don't want to edit this top bit */
/* ---------------------------------------------------------------------------- */
define('TS_PATH', get_stylesheet_directory_uri());
define('TS_SERVER_PATH', get_stylesheet_directory());

$includes_path = TS_SERVER_PATH . '/includes/';

// Theme specific functionality
require_once ($includes_path . 'theme-functions.php');   // Custom theme functions
require_once ($includes_path . 'theme-dyn-css.php');   // Dynamic CSS
require_once ($includes_path . 'theme-scripts.php');  // Load css & javascript
require_once ($includes_path . 'theme-sidebars.php');  // Initialize widgetized areas
require_once ($includes_path . 'theme-plugins.php');  // Initialize widgetized areas
require_once ($includes_path . 'google-api-php-client-master/vendor/autoload.php'); // Google api client

/* ----------------------------------------------------------------------------------- */
/* End Functions - Feel free to add custom functions below */
/* ----------------------------------------------------------------------------------- */

require_once ($includes_path . 'like-jrrny.php'); // Jrrny "Like"
require_once ($includes_path . 'follow-author.php'); // Jrrny "Follow"
require_once ($includes_path . 'edit-profile.php'); // Edit profile
require_once ($includes_path . 'login.php'); // Login singup lospass logout
require_once ($includes_path . 'edit-post.php'); // Edit post
require_once ($includes_path . 'bucket_list.php'); // Bucket list
require_once ($includes_path . 'report.php'); // Report
require_once ($includes_path . 'map.php'); // Map
// Custom Post Types

require_once ($includes_path . 'admin-functions.php');   // Custom admin theme functions

function custom_post_types()
{
    $args = array(
        'labels' => array(
            'name' => 'subscribers',
            'singular_name' => 'subscriber',
            'menu_name' => 'Subscribers'
        ),
        'public' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'supports' => array('title')
    );
    //register_post_type('subscribers', $args);
}

add_action('init', 'custom_post_types');

function get_trending()
{
    global $wpdb;
    $maxPostsCount = 99;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'jrrny_trending';
    $posts_table = $wpdb->prefix . 'posts';
    $likes_table = $wpdb->prefix . 'jrrny_likes';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    //START TRANACTION
    $sql = "start transaction;";
    $wpdb->get_results($sql);

    //Create table
    $sql = "DROP TABLE IF EXISTS " . $table_name . ";";
    $ret = $wpdb->get_results($sql);

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		post_id bigint(20) unsigned NOT NULL,
		weekly_likes bigint(20) unsigned NOT NULL,
		FOREIGN KEY (post_id) REFERENCES $posts_table(ID)
			ON DELETE CASCADE
       		ON UPDATE CASCADE
	) $charset_collate;";
    $ret = $wpdb->get_results($sql);

    //Get latest like
    $sql = "SELECT post_id, count(`user_id`) as count FROM ";
    $sql .= '`' . $likes_table . '` ';
    $sql .= "WHERE `timestamp`>=date_sub(now(),INTERVAL 1 WEEK) ";
    $sql .= "group by post_id ";
    $sql .= "order by count DESC;";
    $ret = $wpdb->get_results($sql);

    $postIds = [];
    //Insert o trending
    foreach ($ret as $key => $post) {
        $sql = "INSERT INTO ";
        $sql .= "`" . $table_name . "` ";
        $sql .= "(`post_id`, `weekly_likes`) VALUES (";
        $sql .= "'" . $post->post_id . "', '" . $post->count . "');";
        $ret = $wpdb->get_results($sql);
        $postIds[] = $post->post_id;
    }

/*    $limit = $maxPostsCount - count($postIds);
    //Inser other
    $sql = "SELECT ID FROM ";
    $sql .= '`' . $posts_table . '` ';
    $sql .= "WHERE ID NOT IN  (" . implode(', ', $postIds) . ') ';
    $sql .= "AND post_type='post' ";
    $sql .= "AND (post_status = 'publish' OR post_status = 'acf-disabled') ";
    $sql .= "LIMIT " . $limit . ' ';
    $ret = $wpdb->get_results($sql);
    foreach ($ret as $key => $post) {
        $sql = "INSERT INTO ";
        $sql .= "`" . $table_name . "` ";
        $sql .= "(`post_id`, `weekly_likes`) VALUES (";
        $sql .= "'" . $post->ID . "', '0');";
        $ret = $wpdb->get_results($sql);
    }*/
}

add_action('get_trending_event', 'get_trending');

function register_daily_trending_event()
{
    if (!wp_next_scheduled('get_trending_event')) {
        wp_schedule_event(time(), 'daily', 'get_trending_event');
    }
}

add_action('init', 'register_daily_trending_event');

function create_place_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'jrrny_place';

    //Create table
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(255) NOT NULL,
		lat DECIMAL(10, 8) NOT NULL,
		lon DECIMAL(11, 8) NOT NULL
	) $charset_collate;";
    $ret = $wpdb->get_results($sql);
}

add_action('init', 'create_place_table');

function send_notification()
{
    global $wpdb;
    $users_table_name = $wpdb->prefix . 'users';
    $posts_table_name = $wpdb->prefix . 'posts';

    $sql = "SELECT u.ID FROM ";
    $sql .= '`' . $users_table_name . '` as u ';
    $sql .= 'LEFT JOIN ' . $posts_table_name . ' as p ON u.ID=p.post_author ';
    $sql .= "WHERE `user_registered` <= date_sub(now(),INTERVAL 2 DAY) ";
    $sql .= "AND `user_registered` > date_sub(now(),INTERVAL 3 DAY) ";
    $sql .= "group by u.ID ";
    $sql .= "HAVING count(p.ID) = 0 ";
    //$sql .= "order by count DESC;";
    $ret = $wpdb->get_results($sql);

    foreach ($ret as $obj) {
        $userId = $obj->ID;
        
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('no_jrrny_posted', null, $userId);
        /*$user = get_user_by('id', $userId);
        $email = $user->user_email;
        $upload_dir = wp_upload_dir();

        // Email
        $content = "<div>";
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= "<h3>Hey " . $user->user_login . ", </h3>";
        $content .= "<p>";
        $content .= "You haven't posted a jrrny yet! Log in and tell the world about your last great trip.";
        $content .= "</p>";
        $content .= "</div>";
        
        $from = "Jrrny.com <contact@jrrny.com>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
        $headers .= "From: $from\r\n";

        $mail = wp_mail($email, "Message", $content, $headers, array());
        */
        if (!$mail) {
            throw new \Exception("Can not send mail!");
        }
    }
}

add_action('send_notification_event', 'send_notification');

function register_daily_notification_event()
{
    if (!wp_next_scheduled('send_notification')) {
        wp_schedule_event(strtotime('01-01-2016 04:00'), 'daily', 'send_notification_event');
    }
}

add_action('init', 'register_daily_notification_event');

// Define urls for seperate script files
function define_urls()
{
    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));
    ?>
    <script type="text/javascript">

        var defineURL = function (item) {
            switch (item) {
                case 'ajaxurl':
                    return "<?php echo admin_url() . 'admin-ajax.php' ?>";
                case 'stylesheet_dir':
                    return "<?php echo get_stylesheet_directory_uri(); ?>";
                case 'home':
                    return "<?php echo home_url(); ?>";
                case 'current_url':
                    return "<?php echo $current_url; ?>";
                case 'is_frontpage':
                    return "<?php echo is_front_page(); ?>";
            }
        };

        var jrrnyCheckLikeArr = [
        <?php foreach (getLikedArr() as $postId) : ?>
                "<?= $postId ?>",
        <?php endforeach ?>
        ];
        var jrrnyCheckFollowedArr = [
        <?php foreach (getFollowedArr() as $authorId) : ?>
                "<?= $authorId ?>",
        <?php endforeach ?>
        ];

    </script>
<?php
}

add_action('wp_head', 'define_urls');

/* Taboola Tracking */

function taboola_tracking()
{
    ?>
    <script type="text/javascript">
        window._tfa = window._tfa || [];
        _tfa.push({notify: "action", name: 'page_view'});
    </script>
    <script src="//cdn.taboola.com/libtrc/jrrny-sc/tfa.js"></script>        
<?php
}

add_action('wp_head', 'taboola_tracking');

function country_full_name($threecode)
{
    $iso_array = array(
        'ABW' => 'Aruba',
        'AFG' => 'Afghanistan',
        'AGO' => 'Angola',
        'AIA' => 'Anguilla',
        'ALA' => 'Åland Islands',
        'ALB' => 'Albania',
        'AND' => 'Andorra',
        'ARE' => 'United Arab Emirates',
        'ARG' => 'Argentina',
        'ARM' => 'Armenia',
        'ASM' => 'American Samoa',
        'ATA' => 'Antarctica',
        'ATF' => 'French Southern Territories',
        'ATG' => 'Antigua and Barbuda',
        'AUS' => 'Australia',
        'AUT' => 'Austria',
        'AZE' => 'Azerbaijan',
        'BDI' => 'Burundi',
        'BEL' => 'Belgium',
        'BEN' => 'Benin',
        'BES' => 'Bonaire, Sint Eustatius and Saba',
        'BFA' => 'Burkina Faso',
        'BGD' => 'Bangladesh',
        'BGR' => 'Bulgaria',
        'BHR' => 'Bahrain',
        'BHS' => 'Bahamas',
        'BIH' => 'Bosnia and Herzegovina',
        'BLM' => 'Saint Barthélemy',
        'BLR' => 'Belarus',
        'BLZ' => 'Belize',
        'BMU' => 'Bermuda',
        'BOL' => 'Bolivia, Plurinational State of',
        'BRA' => 'Brazil',
        'BRB' => 'Barbados',
        'BRN' => 'Brunei Darussalam',
        'BTN' => 'Bhutan',
        'BVT' => 'Bouvet Island',
        'BWA' => 'Botswana',
        'CAF' => 'Central African Republic',
        'CAN' => 'Canada',
        'CCK' => 'Cocos (Keeling) Islands',
        'CHE' => 'Switzerland',
        'CHL' => 'Chile',
        'CHN' => 'China',
        'CIV' => 'Côte d\'Ivoire',
        'CMR' => 'Cameroon',
        'COD' => 'Congo, the Democratic Republic of the',
        'COG' => 'Congo',
        'COK' => 'Cook Islands',
        'COL' => 'Colombia',
        'COM' => 'Comoros',
        'CPV' => 'Cape Verde',
        'CRI' => 'Costa Rica',
        'CUB' => 'Cuba',
        'CUW' => 'Curaçao',
        'CXR' => 'Christmas Island',
        'CYM' => 'Cayman Islands',
        'CYP' => 'Cyprus',
        'CZE' => 'Czech Republic',
        'DEU' => 'Germany',
        'DJI' => 'Djibouti',
        'DMA' => 'Dominica',
        'DNK' => 'Denmark',
        'DOM' => 'Dominican Republic',
        'DZA' => 'Algeria',
        'ECU' => 'Ecuador',
        'EGY' => 'Egypt',
        'ERI' => 'Eritrea',
        'ESH' => 'Western Sahara',
        'ESP' => 'Spain',
        'EST' => 'Estonia',
        'ETH' => 'Ethiopia',
        'FIN' => 'Finland',
        'FJI' => 'Fiji',
        'FLK' => 'Falkland Islands (Malvinas)',
        'FRA' => 'France',
        'FRO' => 'Faroe Islands',
        'FSM' => 'Micronesia, Federated States of',
        'GAB' => 'Gabon',
        'GBR' => 'United Kingdom',
        'GEO' => 'Georgia',
        'GGY' => 'Guernsey',
        'GHA' => 'Ghana',
        'GIB' => 'Gibraltar',
        'GIN' => 'Guinea',
        'GLP' => 'Guadeloupe',
        'GMB' => 'Gambia',
        'GNB' => 'Guinea-Bissau',
        'GNQ' => 'Equatorial Guinea',
        'GRC' => 'Greece',
        'GRD' => 'Grenada',
        'GRL' => 'Greenland',
        'GTM' => 'Guatemala',
        'GUF' => 'French Guiana',
        'GUM' => 'Guam',
        'GUY' => 'Guyana',
        'HKG' => 'Hong Kong',
        'HMD' => 'Heard Island and McDonald Islands',
        'HND' => 'Honduras',
        'HRV' => 'Croatia',
        'HTI' => 'Haiti',
        'HUN' => 'Hungary',
        'IDN' => 'Indonesia',
        'IMN' => 'Isle of Man',
        'IND' => 'India',
        'IOT' => 'British Indian Ocean Territory',
        'IRL' => 'Ireland',
        'IRN' => 'Iran, Islamic Republic of',
        'IRQ' => 'Iraq',
        'ISL' => 'Iceland',
        'ISR' => 'Israel',
        'ITA' => 'Italy',
        'JAM' => 'Jamaica',
        'JEY' => 'Jersey',
        'JOR' => 'Jordan',
        'JPN' => 'Japan',
        'KAZ' => 'Kazakhstan',
        'KEN' => 'Kenya',
        'KGZ' => 'Kyrgyzstan',
        'KHM' => 'Cambodia',
        'KIR' => 'Kiribati',
        'KNA' => 'Saint Kitts and Nevis',
        'KOR' => 'Korea, Republic of',
        'KWT' => 'Kuwait',
        'LAO' => 'Lao People\'s Democratic Republic',
        'LBN' => 'Lebanon',
        'LBR' => 'Liberia',
        'LBY' => 'Libya',
        'LCA' => 'Saint Lucia',
        'LIE' => 'Liechtenstein',
        'LKA' => 'Sri Lanka',
        'LSO' => 'Lesotho',
        'LTU' => 'Lithuania',
        'LUX' => 'Luxembourg',
        'LVA' => 'Latvia',
        'MAC' => 'Macao',
        'MAF' => 'Saint Martin (French part)',
        'MAR' => 'Morocco',
        'MCO' => 'Monaco',
        'MDA' => 'Moldova, Republic of',
        'MDG' => 'Madagascar',
        'MDV' => 'Maldives',
        'MEX' => 'Mexico',
        'MHL' => 'Marshall Islands',
        'MKD' => 'Macedonia, the former Yugoslav Republic of',
        'MLI' => 'Mali',
        'MLT' => 'Malta',
        'MMR' => 'Myanmar',
        'MNE' => 'Montenegro',
        'MNG' => 'Mongolia',
        'MNP' => 'Northern Mariana Islands',
        'MOZ' => 'Mozambique',
        'MRT' => 'Mauritania',
        'MSR' => 'Montserrat',
        'MTQ' => 'Martinique',
        'MUS' => 'Mauritius',
        'MWI' => 'Malawi',
        'MYS' => 'Malaysia',
        'MYT' => 'Mayotte',
        'NAM' => 'Namibia',
        'NCL' => 'New Caledonia',
        'NER' => 'Niger',
        'NFK' => 'Norfolk Island',
        'NGA' => 'Nigeria',
        'NIC' => 'Nicaragua',
        'NIU' => 'Niue',
        'NLD' => 'Netherlands',
        'NOR' => 'Norway',
        'NPL' => 'Nepal',
        'NRU' => 'Nauru',
        'NZL' => 'New Zealand',
        'OMN' => 'Oman',
        'PAK' => 'Pakistan',
        'PAN' => 'Panama',
        'PCN' => 'Pitcairn',
        'PER' => 'Peru',
        'PHL' => 'Philippines',
        'PLW' => 'Palau',
        'PNG' => 'Papua New Guinea',
        'POL' => 'Poland',
        'PRI' => 'Puerto Rico',
        'PRK' => 'Korea, Democratic People\'s Republic of',
        'PRT' => 'Portugal',
        'PRY' => 'Paraguay',
        'PSE' => 'Palestinian Territory, Occupied',
        'PYF' => 'French Polynesia',
        'QAT' => 'Qatar',
        'REU' => 'Réunion',
        'ROU' => 'Romania',
        'RUS' => 'Russian Federation',
        'RWA' => 'Rwanda',
        'SAU' => 'Saudi Arabia',
        'SDN' => 'Sudan',
        'SEN' => 'Senegal',
        'SGP' => 'Singapore',
        'SGS' => 'South Georgia and the South Sandwich Islands',
        'SHN' => 'Saint Helena, Ascension and Tristan da Cunha',
        'SJM' => 'Svalbard and Jan Mayen',
        'SLB' => 'Solomon Islands',
        'SLE' => 'Sierra Leone',
        'SLV' => 'El Salvador',
        'SMR' => 'San Marino',
        'SOM' => 'Somalia',
        'SPM' => 'Saint Pierre and Miquelon',
        'SRB' => 'Serbia',
        'SSD' => 'South Sudan',
        'STP' => 'Sao Tome and Principe',
        'SUR' => 'Suriname',
        'SVK' => 'Slovakia',
        'SVN' => 'Slovenia',
        'SWE' => 'Sweden',
        'SWZ' => 'Swaziland',
        'SXM' => 'Sint Maarten (Dutch part)',
        'SYC' => 'Seychelles',
        'SYR' => 'Syrian Arab Republic',
        'TCA' => 'Turks and Caicos Islands',
        'TCD' => 'Chad',
        'TGO' => 'Togo',
        'THA' => 'Thailand',
        'TJK' => 'Tajikistan',
        'TKL' => 'Tokelau',
        'TKM' => 'Turkmenistan',
        'TLS' => 'Timor-Leste',
        'TON' => 'Tonga',
        'TTO' => 'Trinidad and Tobago',
        'TUN' => 'Tunisia',
        'TUR' => 'Turkey',
        'TUV' => 'Tuvalu',
        'TWN' => 'Taiwan, Province of China',
        'TZA' => 'Tanzania, United Republic of',
        'UGA' => 'Uganda',
        'UKR' => 'Ukraine',
        'UMI' => 'United States Minor Outlying Islands',
        'URY' => 'Uruguay',
        'USA' => 'United States',
        'UZB' => 'Uzbekistan',
        'VAT' => 'Holy See (Vatican City State)',
        'VCT' => 'Saint Vincent and the Grenadines',
        'VEN' => 'Venezuela, Bolivarian Republic of',
        'VGB' => 'Virgin Islands, British',
        'VIR' => 'Virgin Islands, U.S.',
        'VNM' => 'Viet Nam',
        'VUT' => 'Vanuatu',
        'WLF' => 'Wallis and Futuna',
        'WSM' => 'Samoa',
        'YEM' => 'Yemen',
        'ZAF' => 'South Africa',
        'ZMB' => 'Zambia',
        'ZWE' => 'Zimbabwe'
    );
    return $iso_array[$threecode];
}

function subscribe()
{
    if (isset($_POST["event"]) && $_POST["event"] == "subscribe"):
        if (isset($_POST["email"]) && $_POST["email"] !== ""):
            global $current_user;
            wp_get_current_user();
            $upload_dir = wp_upload_dir();

            $subscribers = get_posts(array(
                "post_type" => "subscribers",
                "posts_per_page" => -1,
                "post_status" => "publish"
            ));
            // Email
            $content = "<div>";
            $content .= $upload_dir['baseurl'] . "/2015/11/jrrnyLogoNEW1-300x81.png\" />";
            $content .= "<h3>Hey" . (is_user_logged_in() ? ' ' . $current_user->user_firstname : '') . ", Thanks for signing up!</h3>";
            $content .= "<p>You're now on the list to receive the weeks best jrrnys and a great deal now and then.</p>";
            $content .= "</div>";
            $from = "Jrrny.com <contact@jrrny.com>";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
            $headers .= "From: $from\n";
            //

            $regex = "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";
            $email = array();
            parse_str($_POST["email"], $email);
            $email = strtolower(trim($email["email"]));

            //Subscriber Post Type
            $args = array(
                "post_type" => "subscribers",
                "post_title" => $email,
                "post_status" => "publish"
            );

            if (preg_match($regex, $email)):
                $errors = 0;
                if (is_user_logged_in()):
                    if (strtolower(trim($current_user->user_email)) !== $email):
                        $errors = $errors + 1;
                        echo json_encode(array("errors" => "This doesn't seem the email you are using in our site!"));
                    endif;
                endif;
                if (!$errors):
                    if (!$subscribers):
                        $new_subsciber = wp_insert_post($args, false);
                        if ($new_subsciber):
                            $mail = wp_mail($email, "Message", $content, $headers);
                            echo json_encode(array("subsciber" => "in_list"));
                        else:
                            echo json_encode(array("errors" => "Oops. Something went wrong, please try again later!"));
                        endif;
                    else:
                        foreach ($subscribers as $subsciber):
                            if (strtolower(trim($subsciber->post_title)) == $email):
                                $errors = $errors + 1;
                                echo json_encode(array("errors" => "Your email already exists in our list! We'll be in touch!", "subscriber" => "in_list"));
                            endif;
                        endforeach;
                        if (!$errors):
                            $new_subsciber = wp_insert_post($args, false);
                            $mail = wp_mail($email, "Message", $content, $headers);
                            echo json_encode(array("subsciber" => "in_list"));
                        endif;
                    endif;
                endif;
            else:
                echo json_encode(array("errors" => "Provided email is incorrect!"));
            endif;
        endif;
    endif;
    die();
}

add_action('wp_ajax_subscribe', 'subscribe');
add_action('wp_ajax_nopriv_subscribe', 'subscribe');

// Hide admin bar for non-admin users
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

function wpse_58613_comment_redirect($location)
{
    if (isset($_POST['my_redirect_to'])) // Don't use "redirect_to", internal WP var
        $location = $_POST['my_redirect_to'];

    return $location;
}

add_filter('comment_post_redirect', 'wpse_58613_comment_redirect');

//Custow views of lost password
function jrrny_lost_password_redirect()
{
    if (is_user_logged_in()) {
        wp_redirect(home_url(), '301');
        exit;
    }
    wp_redirect(home_url() . '/lostpassword');
}

add_action('login_form_lostpassword', 'jrrny_lost_password_redirect');

//Custom views of rset password link
function jrrny_reset_password()
{
    if ('GET' == strtoupper($_SERVER['REQUEST_METHOD'])) {
        // Verify key / login combo
        $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(home_url('resetpassword?login=expiredkey'));
            }
            else {
                wp_redirect(home_url('resetpassword?login=invalidkey'));
            }
            exit;
        }

        $redirect_url = home_url('resetpassword');
        $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
        $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);

        wp_redirect($redirect_url, '301');
        exit;
    }
}

add_action('login_form_rp', 'jrrny_reset_password');
add_action('login_form_resetpass', 'jrrny_reset_password');

/**
 * Resets the user's password if the password reset form was submitted.
 */
function jrrny_do_password_reset()
{
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $rp_key = $_REQUEST['rp_key'];
        $rp_login = $_REQUEST['rp_login'];

        $user = check_password_reset_key($rp_key, $rp_login);

        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(home_url('resetpassword?login=expiredkey'));
            }
            else {
                wp_redirect(home_url('resetpassword?login=invalidkey'));
            }
            exit;
        }

        if (isset($_POST['pass1'])) {
            $pass1 = trim($_POST['pass1']);
            $pass2 = trim($_POST['pass2']);
            if ($pass1 != $pass2) {
                // Passwords don't match
                $redirect_url = home_url('resetpassword');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('errors', 'password_reset_mismatch', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }

            if (empty($pass1)) {
                // Password is empty
                $redirect_url = home_url('resetpassword');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('errors', 'password_reset_empty', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }

            if (strlen($pass1) < 6) {
                // Password is empty
                $redirect_url = home_url('resetpassword');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('errors', 'password_reset_wrong', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }

            if (!preg_match('/^[a-zA-Z0-9]+$/', $pass1)) {
                // Password is empty
                $redirect_url = home_url('resetpassword');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('errors', 'password_reset_wrong', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }
            // Parameter checks OK, reset password
            reset_password($user, $_POST['pass1']);
            wp_redirect(home_url('resetpassword?password=changed'));
        }
        else {
            echo "Invalid request.";
        }
        exit;
    }
    else {
        wp_redirect(home_url('lostpassword'));
    }
}

add_action('login_form_rp', 'jrrny_do_password_reset');
add_action('login_form_resetpass', 'jrrny_do_password_reset');

function show_beta_popup()
{
    //return (!isset($_COOKIE['beta-now']));
    return false;
}

// create custom plugin settings menu
add_action('admin_menu', 'primary_post_option');

function primary_post_option()
{

    //create new top-level menu
    add_menu_page('Primary post settings', 'Primary post settings', 'administrator', 'primary_post_option', 'primary_post_option_page');

    //call register settings function
    add_action('admin_init', 'register_primary_post_option');
}

function is_primary($id)
{
    /*
      $primary = array(

      get_option('primary_1'),
      get_option('primary_2'),
      get_option('primary_3'),
      get_option('primary_4'),
      get_option('primary_5'),
      get_option('primary_6')
      );

      if(in_array($id, $primary) || is_singular( array('sponsored_post', 'featured_destination') )){
      return TRUE;
      }
      return FALSE;
     * 
     */
    return TRUE;
}

function plc_is_category($id)
{
    $categories = (array) get_the_category();
    foreach ($categories as $category) {    // concate
        if ($category->cat_ID == $id) {
            return TRUE;
        }
    }
    return FALSE;
}

function register_primary_post_option()
{
    //register our settings
    register_setting('jrny_primary_post_option', 'primary_1');
    register_setting('jrny_primary_post_option', 'primary_2');
    register_setting('jrny_primary_post_option', 'primary_3');
    register_setting('jrny_primary_post_option', 'primary_4');
    register_setting('jrny_primary_post_option', 'primary_5');
    register_setting('jrny_primary_post_option', 'primary_6');
}

function primary_post_option_page()
{
    $jrrnyPostsSql = get_custom_posts();
    ?>
    <div class="wrap">
        <h2>Primary post</h2>

        <form method="post" action="options.php">
    <?php settings_fields('jrny_primary_post_option'); ?>
    <?php do_settings_sections('jrny_primary_post_option'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Post 1</th>
                    <td>
                        <select name="primary_1" >
                            <option value="">none</option>
                            <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post 2</th>
                    <td>
                        <select name="primary_2" >
                            <option value="">none</option>
                            <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post 3</th>
                    <td>
                        <select name="primary_3" >
                            <option value="">none</option>
                            <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_3')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post 4</th>
                    <td>
                        <select name="primary_4" >
                            <option value="">none</option>
    <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_4')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post 5</th>
                    <td>
                        <select name="primary_5" >
                            <option value="">none</option>
    <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_5')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post 6</th>
                    <td>
                        <select name="primary_6" >
                            <option value="">none</option>
    <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('primary_6')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                        </select>
                    </td>
                </tr>
            </table>

    <?php submit_button(); ?>

        </form>
    </div>
<?php
}

add_action('admin_menu', 'static_ad_option');

function static_ad_option()
{

    //create new top-level menu
    add_menu_page('Static AD settings', 'Static AD settings', 'administrator', 'static_ad_option', 'static_ad_option_page');

    //call register settings function
    add_action('admin_init', 'register_static_ad_option');
}

function register_static_ad_option()
{
    //register our settings
    register_setting('static_ad', 'ad_1');
    register_setting('static_ad', 'ad_2');
}

function static_ad_option_page()
{
    ?>
    <div class="wrap">
        <h2>Static AD</h2>

        <form method="post" action="options.php">
    <?php settings_fields('static_ad'); ?>
    <?php do_settings_sections('static_ad'); ?>
           <table class="form-table">
                <tr valign="top">
                    <th scope="row">AD below header(homepage, branded user, collection post, uploader tool for branded user)</th>
                    <td>
                        <textarea class="form-control" name="ad_1"><?php echo esc_html(get_option('ad_1'));?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">AD between top and most recent posts(collection post)</th>
                    <td>
                        <textarea class="form-control" name="ad_2"><?php echo esc_html(get_option('ad_2'));?></textarea>
                    </td>
                </tr>
           </table>
    <?php submit_button(); ?>

        </form>
    </div>
<?php
}


function get_custom_posts($post_type = null)
{
    global $wpdb;

    $posts_table = $wpdb->prefix . 'posts';
    $sql = "SELECT ID,  post_title ";
    $sql .= "from " . $posts_table . ' ';
    if ($post_type) {
        $sql .= "WHERE post_type='" . $post_type . "' ";
    }
    else {
        $sql .= "WHERE post_type IN ('post', 'sponsored_post', 'featured_destination', 'contest') ";
    }
    //$sql .= "WHERE post_type='post' ";
    //$sql .= "OR post_type='sponsored_post' ";
    //$sql .= "OR post_type='featured_destination'";
    $sql .= "AND post_status='publish' ";
    $sql .= "ORDER BY post_title ";

    return $wpdb->get_results($sql);
}

// create custom plugin settings menu for rss feed
add_action('admin_menu', 'rss_feed_option');

function rss_feed_option()
{

    //create new top-level menu
    add_menu_page('RSS feed option', 'RSS feed option', 'administrator', 'feed_option', 'rss_feed_option_page', 'dashicons-rss');

    //call register settings function
    add_action('admin_init', 'register_rss_feed_option');
}

function register_rss_feed_option()
{
    //register our settings
    register_setting('jrny_rss_feed_option', 'main_rss');
}

function rss_feed_option_page()
{
    $jrrnyPostsSql = get_custom_posts('post');
    $jrrnySposoredPostsSql = get_custom_posts('sponsored_post');
    $jrrnyCollectionPostsSql = get_custom_posts('featured_destination');
    $jrrnyContestPostsSql = get_custom_posts('contest');
    ?>
    <div class="wrap">
        <h2>Main RSS post feed</h2>

        <form method="post" action="options.php">
    <?php settings_fields('jrny_rss_feed_option'); ?>
    <?php do_settings_sections('jrny_rss_feed_option'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Main RSS feed</th>
                    <td>
                        <select name="main_rss" >
                            <option value="">none</option>
                            <optgroup label="Contest">
    <?php foreach ($jrrnyContestPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('main_rss')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Collections">
            <?php foreach ($jrrnyCollectionPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('main_rss')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Sponsored post">
    <?php foreach ($jrrnySposoredPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('main_rss')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Jrrnys">
    <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('main_rss')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>       
            </table>

    <?php submit_button(); ?>

        </form>
    </div>
                            <?php
                            }

// create custom plugin settings menu for email
                            add_action('admin_menu', 'plc_email_option');

                            function plc_email_option()
                            {

                                //create new top-level menu
                                add_menu_page('Email option', 'Email option', 'administrator', 'plc_email_option', 'plc_email_option_page', 'dashicons-email-alt');

                                //call register settings function
                                add_action('admin_init', 'register_plc_email_option');
                            }

                            function register_plc_email_option()
                            {
                                //register our settings
                                register_setting('jrny_plc_email_option', 'link_text_1');
                                register_setting('jrny_plc_email_option', 'link_text_2');
                                register_setting('jrny_plc_email_option', 'link_1');
                                register_setting('jrny_plc_email_option', 'link_2');
                                register_setting('jrny_plc_email_option', 'text_1');
                                register_setting('jrny_plc_email_option', 'text_2');
                            }

                            function plc_email_option_page()
                            {
                                $jrrnyPostsSql = get_custom_posts('post');
                                $jrrnySposoredPostsSql = get_custom_posts('sponsored_post');
                                $jrrnyCollectionPostsSql = get_custom_posts('featured_destination');
                                $jrrnyContestPostsSql = get_custom_posts('contest');
                                $jrrnyPageSql = get_custom_posts('page');
                                ?>
    <div class="wrap">
        <h2>Email options</h2>

        <form method="post" action="options.php">
                                <?php settings_fields('jrny_plc_email_option'); ?>
                                <?php do_settings_sections('jrny_plc_email_option'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">First text</th>
                    <td>
                        <input type="text" name="text_1" class="form-control" value="<?= esc_attr(get_option('text_1')); ?>"/>                
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">First custom link</th>
                    <td>
                        <select name="link_1" class="form-control">
                            <option value="">none</option>
                            <optgroup label="Contest">
                                <?php foreach ($jrrnyContestPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Collections">
                                <?php foreach ($jrrnyCollectionPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Sponsored post">
    <?php foreach ($jrrnySposoredPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Jrrnys">
    <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Page">
    <?php foreach ($jrrnyPageSql as $key => $post): ?>
                                    <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_1')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
    <?php endforeach ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>   
                <tr valign="top">
                    <th scope="row">First custom link text</th>
                    <td>
                        <input type="text" name="link_text_1" class="form-control" value="<?= esc_attr(get_option('link_text_1')); ?>" placeholder="if empty show link title"/>                
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Second text</th>
                    <td>
                        <input type="text" name="text_2" class="form-control" value="<?= esc_attr(get_option('text_2')); ?>"/>                
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Second custom link</th>
                    <td>
                        <select name="link_2" class="form-control">
                            <option value="">none</option>
                            <optgroup label="Contest">
                            <?php foreach ($jrrnyContestPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Collections">
                            <?php foreach ($jrrnyCollectionPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Sponsored post">
                            <?php foreach ($jrrnySposoredPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Jrrnys">
                            <?php foreach ($jrrnyPostsSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                            </optgroup>
                            <optgroup label="Page">
                            <?php foreach ($jrrnyPageSql as $key => $post): ?>
                                <option value="<?= $post->ID ?>" <?= (esc_attr(get_option('link_2')) == $post->ID) ? 'selected="selected"' : '' ?>><?= $post->post_title ?></option>
                            <?php endforeach ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>  
                <tr valign="top">
                    <th scope="row">Second custom link text</th>
                    <td>
                        <input type="text" name="link_text_2" class="form-control" value="<?= esc_attr(get_option('link_text_2')); ?>" placeholder="if empty show link title"/>                
                    </td>
                </tr>   
            </table>
        <?php submit_button(); ?>
        </form>
    </div>
<?php
}

add_action('admin_enqueue_scripts', 'plc_h_register_admin_scripts');
add_action('admin_enqueue_scripts', 'plc_h_register_admin_styles');

add_action('wp_enqueue_scripts', 'plc_h_register_scripts');

add_action('wp_ajax_nopriv_upd_next', 'plc_h_upd_next');
add_action('wp_ajax_upd_next', 'plc_h_upd_next');

add_action('admin_menu', 'plc_h_admin_menu');

function plc_h_admin_menu()
{
    add_options_page(
            'PLC - Header Settings', 'Header Settings', 'manage_options', 'plc_header', 'plc_header_settings'
    );
}

function plc_h_upd_next()
{
    $count = get_option('plc_header_images_count');
    $next = (int) get_option('plc_header_images_next');
    $next++;
    if ($next >= $count) {
        $next = 0;
    }

    update_option('plc_header_images_next', $next);
    update_option('plc_header_last_change', date('m/d/Y'));

    wp_send_json($next);
}

function plc_h_register_admin_scripts()
{
    wp_enqueue_script('plc_js', get_stylesheet_directory_uri() . '/assets/js/backend.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('xdan_datetimepicker_js', get_stylesheet_directory_uri() . '/inc/xdan_datetimepicker/jquery.datetimepicker.full.min.js', array('jquery'), '2.5.4', true);
}

function plc_h_register_admin_styles()
{
    wp_register_style('plc_style', get_stylesheet_directory_uri() . '/assets/css/backend.css', false, '1.0.0', 'all');
    wp_enqueue_style('plc_style');
    
    wp_register_style('xdan_datetimepicker_css', get_stylesheet_directory_uri() . '/inc/xdan_datetimepicker/jquery.datetimepicker.min.css', false, '2.5.4', 'all');
    wp_enqueue_style('xdan_datetimepicker_css');
}

function plc_h_register_scripts()
{
    wp_enqueue_script('plc_js', get_stylesheet_directory_uri() . '/assets/js/frontend.js', array('jquery'), '1.0.8', true);

    wp_localize_script('plc_js', 'header', array(
        'plc_header_start_date' => get_option('plc_header_start_date'),
        'plc_header_last_change' => get_option('plc_header_last_change'),
        'plc_header_rotating' => get_option('plc_header_rotating'),
        'plc_header_images' => get_option('plc_header_images'),
        'plc_header_images_count' => get_option('plc_header_images_count'),
        'plc_header_images_next' => get_option('plc_header_images_next'),
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}

function plc_set_options()
{
    if (!get_option('plc_header_start_date')) {
        add_option('plc_header_start_date', date('m/d/Y'));
    }
    if (!get_option('plc_header_last_change')) {
        add_option('plc_header_last_change', date('m/d/Y'));
    }
    if (!get_option('plc_header_rotating')) {
        add_option('plc_header_rotating', '1');
    }
    if (!get_option('plc_header_images')) {
        add_option('plc_header_images', '');
    }
    if (!get_option('plc_header_images')) {
        add_option('plc_header_images_next', '0');
    }
    if (!get_option('plc_header_images_count')) {
        add_option('plc_header_images_count', '0');
    }
    if (!get_option('plc_header_images_next')) {
        add_option('plc_header_images_next', '0');
    }
}

function plc_header_settings()
{
    plc_set_options();
    wp_enqueue_media();

    if ($_POST && $_POST['action'] === 'update') {
        update_option('plc_header_start_date', $_POST['plc_header_start_date']);
        update_option('plc_header_last_change', $_POST['plc_header_start_date']);
        update_option('plc_header_rotating', $_POST['plc_header_rotating']);
        update_option('plc_header_images', $_POST['plc_header_images']);
        update_option('plc_header_images_count', $_POST['plc_header_images_count']);
        update_option('plc_header_images_next', '0');
    }
    ?>
    <div class="wrap">
        <h2>Header settings</h2>
        <form method="post">
    <?php
    $plc_header_start_date = get_option('plc_header_start_date');
    $plc_header_rotating = get_option('plc_header_rotating');
    $plc_header_images = get_option('plc_header_images');
    $plc_header_images_count = get_option('plc_header_images_count');
    ?>
            <p><strong>Starting date:</strong><br />
                <input type="date" name="plc_header_start_date" placeholder="mm/dd/YYYY" value="<?php echo $plc_header_start_date; ?>" required="required"/>
            </p>
            <p><strong>Change after (day):</strong><br />
                <input type="number" name="plc_header_rotating"  value="<?php echo $plc_header_rotating; ?>" required="required"/>
            </p>
            <hr/>
            <p><strong>Images:</strong><br />
            <div id="img-wrapper">
            <?php
            if ($plc_header_images) {
                $images = explode(',', $plc_header_images);
                foreach ($images as $img) {
                    echo '<span><img src="' . $img . '" /></span>';
                }
            }
            else {
                echo 'None';
            }
            ?>
            </div>
            <p><a href="#" class="upload_image_button button button-secondary button-large">Select Image</a></p>
            <p><input type="submit" name="Submit" value="Update" class="button button-primary button-large"/></p>
            <input type="hidden" name="plc_header_images" id="img" value="<?php echo $plc_header_images; ?>"/>
            <input type="hidden" name="plc_header_images_count" id="img_count" value="<?php echo $plc_header_images_count; ?>"/>
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
    <?php
}


//Search
function rc_filter_where($where = '')
{
    if (isset($_GET['s']) && $_GET['s'] != '') {
        $user = get_user_by('login', trim($_GET['s']));
        if ($user === false) {
            return $where;
        }
        $userId = $user->ID;
        $where .= " OR ( ";
        $where .= "wp_n13mz40_posts.post_author = " . $userId . " ";
        $where .= "AND (wp_n13mz40_posts.post_password = '') ";
        $where .= "AND wp_n13mz40_posts.post_type IN ('post', 'page', 'attachment', 'featured_destination', 'options')  ";
        $where .= "AND (wp_n13mz40_posts.post_status = 'publish' OR wp_n13mz40_posts.post_status = 'acf-disabled') ";
        $where .= ") ";
    }
    return $where;
}

// Modify the current query
function rc_modify_query_get_posts_by_date($query)
{
    // Check if on frontend and main query is modified
    if (!is_admin() && $query->is_main_query()) {
        add_filter('posts_where', 'rc_filter_where');
    }
    return $query;
}

add_action('pre_get_posts', 'rc_modify_query_get_posts_by_date');

// ensure all tags are included in queries
function tags_support_query($wp_query)
{
    if ( $wp_query->is_search ) {

        $wp_query->set( 'post_type', array( 'post', 'articles', 'sponsored_post' ) );
    }
}

// tag hooks
add_action('pre_get_posts', 'tags_support_query');


if (function_exists('wsl_get_wsl_users_count')) {

    function plc_modify_user_columns($column_headers)
    {
        $column_headers['social_login'] = 'Social login';

        return $column_headers;
    }

    add_action('manage_users_columns', 'plc_modify_user_columns');

    function plc_social_login_column_content($value, $column_name, $user_id)
    {
        global $wpdb;

        if ('social_login' == $column_name) {

            $sql = "SELECT provider FROM `{$wpdb->prefix}wslusersprofiles` WHERE user_id='" . $user_id . "'";
            $provider = $wpdb->get_var($sql);

            return $provider;
        }

        return $value;
    }

    add_action('manage_users_custom_column', 'plc_social_login_column_content', 10, 3);

    function plc_custom_admin_css()
    {
        echo '<style> 
        .column-social_login,
        .column-provider {width: 8%} 
        .column-displayname img,
        .column-referral_user_login img  {
            float: left;
            margin-right: 10px;
            margin-top: 1px;
        }
        </style>';
    }

    add_action('admin_head', 'plc_custom_admin_css');

    function plc_users_menu()
    {
        add_users_page('All Social Users', 'All Social Users', 'read', 'social-users', 'social_users_page');
        add_users_page('All Special Signups', 'All Special Signups', 'read', 'singups-users', 'social_signup_users_page');
    }

    add_action('admin_menu', 'plc_users_menu');

    function social_users_page()
    {
        require_once "inc/admin/model/social_users_m.php";

        $option = 'per_page';
        $args = [
            'label' => __('Licenses', 'plc-plugin'),
            'default' => 20,
            'option' => 'licenses_per_page'
        ];

        add_screen_option($option, $args);

        $social_users_obj = new Social_users_m();

        include "inc/admin/views/social_users.php";
    }

    function social_signup_users_page()
    {
        require_once "inc/admin/model/signup_users_m.php";

        $option = 'per_page';
        $args = [
            'label' => __('Signup', 'plc-plugin'),
            'default' => 20,
            'option' => 'signup_per_page'
        ];

        add_screen_option($option, $args);

        $signup_users_obj = new Signup_users_m();

        include "inc/admin/views/signup_users.php";
    }

}
if (function_exists('lal_actually_log')) {
    add_action('admin_menu', 'adjust_the_wp_menu', 999);

    function adjust_the_wp_menu()
    {
        remove_submenu_page('lal-settings', 'lal_log_show');
        add_submenu_page(
                "lal-settings", "Log", "Log", "manage_options", "plc_lal_log_show", "plc_lal_log_show"
        );
    }

    function plc_custom_lal_admin_css()
    {
        echo '<style> #lal-lt-message {width: 58%} </style>';
    }

    add_action('admin_head', 'plc_custom_lal_admin_css');

    function plc_lal_log_show()
    {
        lal_assets();

        $log = lal_get_log();
        $istop = false;

        if (isset($_GET['topwhich']) && ($_GET['topwhich'] == 'recent') && isset($_GET['topnum'])) {
            $log = lal_get_log($_GET['topnum']);
        }
        else if (isset($_GET['topnum']) && isset($_GET['topwhich'])) {
            $top_which = $_GET['topwhich'] == 'message' ? 'password' : $_GET['topwhich'];
            $log = lal_get_log_top($_GET['topnum'], $top_which);
            $istop = true;
        }
        else {
            $log = lal_get_log();
        }

        include "inc/admin/views/lal-log.tpl.php";
    }
}

function jrrny_post_thumbnails_in_feeds($content)
{
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $output = '<p>' . get_the_post_thumbnail($post->ID, 'medium') . '</p>';
        $content = $output . $content;
    }
    $link = '<p><a href="' . get_the_permalink($post->ID) . '">View on Jrrny</a></p>';
    $content = $content . $link;

    return $content;
}

add_filter('the_excerpt_rss', 'jrrny_post_thumbnails_in_feeds');
add_filter('the_content_feed', 'jrrny_post_thumbnails_in_feeds');

remove_action('do_feed_rss2', 'do_feed_rss2', 10, 1);

// define the do_feed_rss2 callback 
function action_do_feed_rss2($for_comments)
{
    if ($for_comments){
        load_template(ABSPATH . WPINC . '/feed-rss2-comments.php');
    } else{
        load_template(TS_SERVER_PATH . '/inc/rss/feed-rss2.php');
    }
}

;

// add the action 

add_action('do_feed_rss2', 'action_do_feed_rss2', 10, 3);

/* rss enclosure image */

function wp_rss_img_url($size = 'full')
{
    global $post;
    if (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        if (!empty($thumbnail_id))
            $img = wp_get_attachment_image_src($thumbnail_id, $size);
    } else {
        $attachments = get_children(array(
            'post_parent' => $post->ID,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'numberposts' => 1));
        if ($attachments == true) {
            foreach ($attachments as $id => $attachment) :
                $img = wp_get_attachment_image_src($id, $size);
            endforeach;
        }
    }
    if (isset($img))
        return $img[0];
}

function jrrny_post_thumbnails_image_tag_feeds()
{
    $image_url = wp_rss_img_url();
    if ($image_url) {
        $uploads = wp_upload_dir();
        $url = parse_url($image_url);
        $path = $uploads['basedir'] . preg_replace('/.*uploads(.*)/', '${1}', $url['path']);

        if (file_exists($path)) {
            $filesize = filesize($path);
            $url = $path;
        }
        else {
            $ary_header = get_headers($image_url, 1);
            $filesize = $ary_header['Content-Length'];
            $url = $image_url;
        }

        echo '<enclosure url="' . $image_url . '" length="' . $filesize . '" type="image/jpg" />' . PHP_EOL;
    }
}

add_action('rss2_item', 'jrrny_post_thumbnails_image_tag_feeds');

$result = add_role('blogger', __('Blogger'), array(
    'read' => true,
    'publish_posts' => true,
    'edit_posts' => true,
    'edit_published_posts' => true,
    'delete_published_posts' => true,
    'delete_posts' => true,
    'upload_files' => true
        )
);
$result = add_role('celebrity', __('Celebrity'), array(
    'read' => true,
    'publish_posts' => true,
    'edit_posts' => true,
    'edit_published_posts' => true,
    'delete_published_posts' => true,
    'delete_posts' => true,
    'upload_files' => true
        )
);
$result = add_role('brand', __('Brand'), array(
    'read' => true,
    'publish_posts' => true,
    'edit_posts' => true,
    'edit_published_posts' => true,
    'delete_published_posts' => true,
    'delete_posts' => true,
    'upload_files' => true
        )
);

function get_user_roles_by_user_id($user_id)
{
    $user = get_userdata($user_id);
    return empty($user) ? array() : $user->roles;
}

function is_user_in_role($user_id, $role)
{
    return in_array($role, get_user_roles_by_user_id($user_id));
}

$wyswig_settings = array(
    'media_buttons' => false,
    'teeny' => true,
    'textarea_name' => 'story',
    'textarea_rows' => 12,
    'tinymce' => array(
        'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,formatselect,fontsizeselect'
    ),
    'quicktags' => false
);

function is_post_flow($type)
{
    global $wp_query;

    $page_flow = get_post_meta($wp_query->post->ID, 'page_flow', true);
    if ($page_flow === $type){
        return true;
    }
    return false;
}


include "inc/custom_posts/articles/articles.php";
include "inc/custom_posts/cities/cities.php";
include "inc/custom_posts/contest/contest.php";
include "inc/custom_posts/collections/collections.php";
include "inc/custom_posts/sponsored_post/sponsored_post.php";

include "inc/frontend/contribute/contribute.php";
include "inc/frontend/homepage/homepage.php";
include "inc/frontend/homepage/homepage_videos.php";
include "inc/plugins/cron/cron.php";
include "inc/plugins/newsletter/newsletter.php";
include "inc/plugins/notifications/notifications.php";
include "inc/plugins/stampready/stampready.php";


/* optymalize */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');


add_filter( 'get_avatar' , 'plc_avatar' , 1 , 5 );

function plc_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }

    if ( $user && is_object( $user ) ) {
        $img = get_user_meta($user->data->ID, 'wsl_current_user_image', true);
        if ( $img ) {
            $avatar = "<img alt='{$alt}' src='{$img}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }

    }

    return $avatar;
}