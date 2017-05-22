<?php
/**
 * The main template file.
 */
global $smof_data;

if(isset($smof_data['blog_page_title'])) 
{
    $ts_page_title = $smof_data['blog_page_title'];
}
else 
{
    if( is_home() && get_option('page_for_posts') ) 
    {
        $ts_blog_page_id = get_option('page_for_posts');
        $ts_page_title = get_page($ts_blog_page_id)->post_title;
        $ts_caption = get_post_meta($ts_blog_page_id, '_page_titlebar_caption', true);
    }
    else
    {
        $ts_blog_page_id = $post->ID;
        $ts_page_title = __('Recent Posts', 'ThemeStockyard');
    }
}



$ts_page_object = get_queried_object();
$ts_page_id     = (is_home() && get_option('page_for_posts')) ? get_option('page_for_posts') : ((is_single()) ? $post->ID : get_queried_object_id());
$ts_custom_css  = get_post_meta($ts_page_id, '_page_css', true);

$ts_show_top_ticker_option = (ts_option_vs_default('show_page_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_page_top_ticker', $ts_show_top_ticker_option);

$ts_show_sidebar_option = (ts_option_vs_default('show_page_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar = ts_postmeta_vs_default($ts_page_id, '_page_sidebar', $ts_show_sidebar_option);

$ts_sidebar_position_option = ts_option_vs_default('page_sidebar_position', 'right');
$ts_sidebar_position = ts_postmeta_vs_default($ts_page_id, '_page_sidebar_position', $ts_sidebar_position_option);

$ts_infinite_scroll = ts_option_vs_default('infinite_scroll_on_blog_template', 0);

get_header(); 
get_template_part('top');
get_template_part('title-page');

?>
            <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page'));?>">
                <div id="main-container" class="clearfix">
                    <div class="<?php echo esc_attr(ts_main_div_class());?> clearfix">
                        <div class="entry single-entry clearfix">
                            <div class="post">

                                <?php

                                /* 
                                 * Run the loop to output the posts.
                                 */
                                $ts_loop = (isset($smof_data['blog_layout'])) ? $smof_data['blog_layout'] : '';
                                $ts_loop = (isset($_GET['layout'])) ? $_GET['layout'] : $ts_loop;
                                $atts = array('infinite_scroll' => $ts_infinite_scroll, 'layout' => $ts_loop, 'pid' => $ts_page_id, '_dq' => true,  'show_category'=>"false", 'show_excerpt'=> "false");

                                ts_blog($atts, $ts_loop);
                                ?>
                                
                            </div>
                        </div>
                    </div>

<?php 
ts_get_sidebar(); 
?>

                </div><!-- #main-container -->
            </div><!-- #main-container-wrap -->
            
<?php get_footer(); ?>