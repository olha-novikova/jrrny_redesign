<?php
/*
Template Name: Trending
*/

$jrrnys = get_post_meta($post->ID, 'jrrnys', true);

get_header();
get_template_part('top');
get_template_part('title-page');
////////////////
?>

<?php
/*if (have_posts()) :
    while (have_posts()) : the_post(); ?>
    <div class="tag-heading">
        <div class="container">
            <div class="top-tagline">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
<?php endif; */?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
    <div id="main-container" class="clearfix">
        <?php
        if($jrrnys){
            $atts = [
                'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                'post__in' => $jrrnys,
                'default_query' => false,
                'orderby' => 'post__in',
                'infinite_scroll' => 'no',
                'posts_per_page' => 12,
                'limit' => 12,
                'show_pagination' => false,
                'show_category'=>false,
                'show_excerpt' => false
            ];
            //$ts_query =  new WP_Query($atts);

            ts_blog_loop('3column', $atts);
        }
        ?>
        <?php
            $trendingIds = getTrendingPostsIds();

            if ( count($trendingIds) < 12 ) {
                $randomPostsCount = 12 - count($trendingIds);

                $post_ids = get_posts(array(
                    'numberposts'   => $randomPostsCount, // get all posts.
                    'exclude'       => $trendingIds,
                    'orderby'       => 'rand',
                    'fields'        => 'ids' // Only get post IDs
                ));

                if ( count($post_ids)>0 ) $trendingIds = array_merge ($trendingIds, $post_ids);

            }

            $atts = [
                'post__in' => $trendingIds,
                'default_query' => false,
                'orderby' => 'post__in',
                'posts_per_page'=> -1,
                'infinite_scroll' => 'yes',
                'show_category'=>false,
                'show_excerpt' => false
            ];

            //$ts_query =  new WP_Query($atts);

            ts_blog_loop('3column', $atts);
        ?>
	</div>
</div>

<?php get_footer(); ?>