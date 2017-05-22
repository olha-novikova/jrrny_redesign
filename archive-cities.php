<?php
global $smof_data, $ts_page_id, $ts_page_title;

$ts_page_id        = get_option('page_for_posts');

$ts_show_sidebar = (ts_option_vs_default('show_archive_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';

$ts_show_top_ticker_option = (ts_option_vs_default('show_page_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_page_top_ticker', $ts_show_top_ticker_option);

$ts_sidebar_position = ts_option_vs_default('page_sidebar_position', 'right');

$ts_infinite_scroll = ts_option_vs_default('infinite_scroll_on_blog_template', 0);

$post = $posts[0];
$ts_queried_object = get_query_var('author');

if (is_category()) { 
    $ts_page_title = single_cat_title('', false);
    $ts_caption = strip_tags(category_description());
} elseif( is_tag() ) {
    $ts_page_title = __('Posts Tagged', 'ThemeStockyard').' &#8216;'.single_tag_title('', false).'&#8217;';
} elseif (is_day()) { 
    $ts_page_title = __('Archive for:', 'ThemeStockyard').' '.get_the_time('F jS, Y');
} elseif (is_month()) { 
    $ts_page_title = __('Archive for:', 'ThemeStockyard').' '.get_the_time('F, Y');
} elseif (is_year()) {
    $ts_page_title = __('Archive for:', 'ThemeStockyard').' '.get_the_time('Y');
} elseif (is_author()) { 
    $ts_page_title = __('Posts by:', 'ThemeStockyard').' '.get_the_author_meta('display_name', get_query_var('author'));
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
    $ts_page_title = __('Archives', 'ThemeStockyard');
} elseif(is_tax()) {
    $ts_page_title = get_the_archive_title();
    $ts_caption = get_the_archive_description();
}
$ts_caption = (trim($ts_caption)) ? $ts_caption : '';

get_header();
get_template_part('top');

global $post;
$post = get_page_by_path( 'cities' );
if($post): setup_postdata( $post );
    $content = get_the_content();    
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class) . ($content ? '' : ' no-margin no-padding' ) ;?>">
        <?php    
                
            $rows = get_field('cities', get_the_ID());
            if($rows){
            ?>
            <div class="featured_destination_wrapper">
                <?php echo $content; ?>
                <ul id="featured_destination_list" <?php echo $content ? '' : ' class="no-margin no-padding"' ;?>>
                <?php
                    
                    foreach($rows as $row)
                    {
                        $post = $row['cities_jrrnys'];
                        setup_postdata( $post );
                        $image_data = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                        if(!$image_data){                    
                            $hero_image = get_post_meta($post->ID, 'hero_image', true);
                            $hero_image_attr = wp_get_attachment_image_src( $hero_image);
                            $image_data = $hero_image_attr[0];
                        }
                        $backgroundStyle ="background-image: url('". $image_data  ."')";
                    ?>
                    <li>
                        <div style="<?php echo $backgroundStyle;?>" class="fetured-content">
                            <a href="<?php echo the_permalink(); ?>" class="fetured-content-link"></a>
                            <div class="plc-table">
                                <div class="plc-cell">
                                    <?php echo the_title(); ?>
                                </div>
                            </div>
                        </div>
                    </li>

                <?php }    
                    wp_reset_postdata();
                ?>
                </ul>
            </div>
            <?php } else { ?>
    
            <div id="main-container" class="container clearfix">
                <div id="main" class="clearfix <?php echo esc_attr($ts_show_sidebar_class);?>">
                    <div class="entry single-entry clearfix">
                        <div class="post">
                            <?php
                            /* 
                             * Run the loop to output the posts.
                             */
                            $ts_loop = (isset($smof_data['archive_layout'])) ? $smof_data['archive_layout'] : '';
                            $atts = array('infinite_scroll' => $ts_infinite_scroll, 'layout' => $ts_loop, 'default_query' => true, 'pid' => $ts_page_id);
                            ts_blog($atts, $ts_loop);
                            ?>
                        </div>
                    </div>
                </div>
                <?php ts_get_sidebar(); ?>
            </div>                
            <?php  } ?>   
</div><!-- #main-container-wrap -->         
                 
<?php endif; ?>
<?php get_footer(); ?>
