<?php
/*
Template Name: Featured
*/

get_header();
get_template_part('top');
get_template_part('title-page');
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container-primary" class="container clearfix">
        <?php
        $primaryPost = plc_get_primary_posts();
        
        $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
            'post__in' => $primaryPost,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => 6,
            'limit' => 6,
            'show_pagination' => false
        ];
        
        ts_blog_loop('3column', $atts);
        
        $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
            'post__not_in' => $primaryPost,
            'default_query' => false,
            'orderby' => 'rand',
            'infinite_scroll' => 'no',
            'posts_per_page' => 6,
            'limit' => 6,
            'show_pagination' => false
        ];
        
        ts_blog_loop('3column', $atts);
        
        ?>
    </div>    
</div>
<!-- #main-container -->

<?php get_footer(); ?>