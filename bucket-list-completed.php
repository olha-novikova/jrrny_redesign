<?php
/*
  Template Name: Bucket list - Completed
 */

get_header();
get_template_part('top');
get_template_part('title-page');
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container-primary" class="container clearfix">
        <?php
        $count_complted_bucket_list = plc_count_bucket_list($current_user->ID, 1);
        if ($count_complted_bucket_list > 0) {
            ?>
            <?php
            $completed_bucket_list = plc_get_bucket_list_array($current_user->ID, 1);
            $limit = $count_complted_bucket_list > 12 ? 12 : $count_complted_bucket_list;
            $atts = [
                'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                'post__in' => $completed_bucket_list,
                'default_query' => false,
                'orderby' => 'post__in',
                'infinite_scroll' => 'no',
                'posts_per_page' => $limit,
                'limit' => $limit,
                'show_pagination' => false
            ];

            ts_blog_loop('3columncarousel', $atts);
            ?>
        <?php }
        else {
            ?>                
            <p class="tab-text">You didn't complete any jrrnys from your bucket list</p>
<?php } ?>
    </div>    
</div>
<!-- #main-container -->

<?php get_footer(); ?>