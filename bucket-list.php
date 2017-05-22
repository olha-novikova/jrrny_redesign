<?php
/*
Template Name: Bucket list
*/

get_header();
get_template_part('top');
get_template_part('title-page');
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container-primary" class="container clearfix">
        <div id="bucket_wrapper">
       <?php
       $count_bucket_list = plc_count_bucket_list($current_user->ID);
        if($count_bucket_list > 0){ ?>
            <?php
            $bucket_list = plc_get_bucket_list_array($current_user->ID);

            $limit = $count_bucket_list > 12 ? 12 : $count_bucket_list;
            $atts = [
                'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                'post__in' => $bucket_list,
                'default_query' => false,
                'orderby' => 'post__in',
                'infinite_scroll' => 'no',
                'posts_per_page' => $limit,
                'limit' => $limit,
                'show_pagination' => false
            ];

            ts_blog_loop('3column', $atts);
            ?>
         <?php }
         else { ?>                
        <p class="tab-text">Your bucket list is empty</p>
         <?php } ?>
        </div>
            <div class="homepage-tab-content"> 
                <p class="tab-text">Other jrrnys you might like to add:</p>
                <p class="module-header">Most Recent <a href="/all-jrrnys">view more</a></p>
                <?php
                $atts = [
                    'post_type' => array('post'),
                    'default_query' => false,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'infinite_scroll' => 'no',
                    'posts_per_page' => 12,
                    'limit' => 12,
                    'show_pagination' => false
                ];

                ts_blog_loop('3columncarousel', $atts);
                ?>
            </div> 
        <div class="homepage-tab-content">
                <p class="tab-text">Leave Comments about your experiences:</p>
                <p class="module-header">Completed <a href="/bucket-list/completed-bucket-list">view more</a></p>  
                <div id="bucket_list_completed_wrapper">
                <?php
                $count_complted_bucket_list = plc_count_bucket_list($current_user->ID, 1);
                if($count_complted_bucket_list > 2){ ?>
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
                 else { ?>                
                <p class="tab-text">You didn't complete any jrrnys from your bucket list or this list is less than three</p>
                 <?php } ?>
                </div>
            </div> 
        
    </div>    
</div>
<!-- #main-container -->

<?php get_footer(); ?>