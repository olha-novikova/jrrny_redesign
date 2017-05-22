<?php
global $wpdb, $smof_data, $ts_previous_posts, $ts_page_id, $ts_show_top_ticker, $ts_show_sidebar, $ts_sidebar_position;

$ts_page_object = get_queried_object();
$ts_page_id     = (is_single()) ? $post->ID : get_queried_object_id();
$ts_custom_css  = get_post_meta($ts_page_id, '_page_css', true);

$ts_show_top_ticker_option = (ts_option_vs_default('show_page_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_page_top_ticker', $ts_show_top_ticker_option);

$ts_show_sidebar_option = (ts_option_vs_default('show_page_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar = ts_postmeta_vs_default($ts_page_id, '_page_sidebar', $ts_show_sidebar_option);
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';

$ts_sidebar_position_option = ts_option_vs_default('page_sidebar_position', 'right');
$ts_sidebar_position = ts_postmeta_vs_default($ts_page_id, '_page_sidebar_position', $ts_sidebar_position_option);

$ts_page_comments = (ts_option_vs_default('page_comments',0) == 1) ? true : false;

get_header();
get_template_part('top');

$featured_media_vars = array('media_width'=>1040,'media_height'=>340,'is_single'=>1);
$featured_media = ts_get_featured_media($featured_media_vars);
$main_container_wrap_class = '';
do_action('render_map');
$places = getMapPlaces();
?>
<script>
    var jrrny_map_addresses = [
        <?php foreach($places as $key => $place): ?>
        <?= '{position:['.$place['lat'].','.$place['lon'].'], icon: "'.get_stylesheet_directory_uri().'/images/marker20x30.png", placeId:"'.$place['id'].'", title:"'.$place['title'].'"},' ?>
        <?php endforeach; ?>
    ];
</script>
    <div class="map"></div>
<?php get_footer(); ?>
    
<?php get_template_part("inc/frontend/map/modal-map");?>
