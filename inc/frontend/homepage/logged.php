<?php
global $current_user;

$collections = get_field('collections', $post->ID);
$ad1 = get_option('ad_1');

$show_home_video = get_option('home_video_show_on_page');
if ( $show_home_video == 1){
    $video_id1 = get_option('home_video_link_1');
    $video_id2 = get_option('home_video_link_2');
    $video_id3 = get_option('home_video_link_3');

    $video_img1 = get_option('video_'.$video_id1);
    $video_img2 = get_option('video_'.$video_id2);
    $video_img3 = get_option('video_'.$video_id3);

    $video_text_1 = get_option('home_video_title_1');
    $video_text_2 = get_option('home_video_title_2');
    $video_text_3 = get_option('home_video_title_3');
}

?>
<?php if ($ad1) { ?>
    <div class="container">
        <?php echo $ad1; ?>
    </div>
<?php } ?>
<div id="homepage-logged">
    <!-- Nav tabs -->
    <div class="tabs">
        <div class="container">
            <ul class="nav nav-tabs nav-justified nav-tab-blue" role="tablist">
                <li role="presentation" class="active"><a href="#feed" aria-controls="feed" role="tab" data-toggle="tab">Feed</a></li>
                <li role="presentation"><a href="#bucket_list" aria-controls="bucket_list" role="tab" data-toggle="tab">Bucket List</a></li>
                <li role="presentation"><a href="#my_jrrnys" aria-controls="my_jrrnys" role="tab" data-toggle="tab">My Jrrnys</a></li>
            </ul>
        </div>
    </div>
<?php if ($show_home_video == 1){?>
    <div id="main-container" class="container clearfix produced_by_jrrny">
        <p class="module-header">FEATURED VIDEOS produced by JRRNY</p>

        <div class="loop-wrap loop-3-column-wrap ">
            <div class="hfeed entries blog-entries loop loop-3-column no-sidebar clearfix">
                <?php if ( $video_id1 ){?>
                <div id="home-video-1" class="hentry entry span4">
                    <div class="post-content">
                        <div class="ts-meta-wrap media-meta-wrap">
                            <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                <div class="featured-photo ">
                                    <a class="featured-photo-link" href="<?php echo 'https://www.youtube.com/watch?v='.$video_id1; ?>">
                                        <img src="<?php echo esc_url($video_img1); ?>" alt="" width="480">
                                    </a>
                                </div>
                            </div>
                            <?php if ( $video_text_1 ){?>
                            <div class="title-date clearfix">
                                <div class="title-info">
                                    <h4 class="title-h entry-title ">
                                        <a href="<?php echo 'https://www.youtube.com/watch?v='.$video_id1; ?>"><?php echo $video_text_1; ?></a>
                                    </h4>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }?>
                <?php if ( $video_id2 ){?>
                <div id="home-video-2" class="hentry entry span4">
                    <div class="post-content">
                        <div class="ts-meta-wrap media-meta-wrap">
                            <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                <div class="featured-photo ">
                                    <a class="featured-photo-link" href="<?php echo 'https://www.youtube.com/watch?v='.$video_id2; ?>">
                                        <img src="<?php echo esc_url($video_img2); ?>" alt="" width="480">
                                    </a>
                                </div>
                            </div>
                            <?php if ( $video_text_1 ){?>
                            <div class="title-date clearfix">
                                <div class="title-info">
                                    <h4 class="title-h entry-title ">
                                        <a href="<?php echo 'https://www.youtube.com/watch?v='.$video_id2; ?>"><?php echo $video_text_2; ?></a>
                                    </h4>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }?>
                <?php if ( $video_id3 ){?>
                <div id="home-video-3" class="hentry entry span4">
                    <div class="post-content">
                        <div class="ts-meta-wrap media-meta-wrap">
                            <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                <div class="featured-photo ">
                                    <a class="featured-photo-link" href="<?php echo 'https://www.youtube.com/watch?v='.$video_id3; ?>">
                                        <img src="<?php echo esc_url($video_img3); ?>" alt="" width="480">
                                    </a>
                                </div>
                            </div>
                            <?php if ( $video_text_1 ){?>
                            <div class="title-date clearfix">
                                <div class="title-info">
                                    <h4 class="title-h entry-title ">
                                        <a href="<?php echo 'https://www.youtube.com/watch?v='.$video_id3; ?>"><?php echo $video_text_3; ?></a>
                                    </h4>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
<?php }?>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="feed">
            <div class="container tab-content">
                <p class="module-header">Featured <a href="/featured">view more</a></p>
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
                ?>
            </div>
            <div class="container tab-content">
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
            <div class="container tab-content">
                <p class="module-header">Following <a href="/following">view more</a></p>
                <?php
                $is_following = getFollowedUsersIds($current_user->ID);

                if($is_following){ ?>
                <?php
                    $followingUsersPosts = plc_get_following_user_posts($is_following);
                    $atts = [
                        'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                        'post__in' => $followingUsersPosts,
                        'default_query' => false,
                        'orderby' => 'rand',
                        'infinite_scroll' => 'no',
                        'posts_per_page' => 12,
                        'limit' => 12,
                        'show_pagination' => false
                    ];

                    ts_blog_loop('3columncarousel', $atts);
                ?>
                <?php } else { ?>
                    <p>You are not following any users, here is a suggestion of the user's Jrrnys you can follow</p>
                    <?php
                    $followingUsersPosts = plc_get_following_user_posts(null, true);
                    $atts = [
                        'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                        'post__in' => $followingUsersPosts,
                        'default_query' => false,
                        'orderby' => 'rand',
                        'infinite_scroll' => 'no',
                        'posts_per_page' => 12,
                        'limit' => 12,
                        'show_pagination' => false
                    ];

                    ts_blog_loop('3columncarousel', $atts);
                    ?>
                <?php } ?>
            </div>
            <?php
            $count_recently_viewed = plc_count_recently_viewed($current_user->ID);
            if($count_recently_viewed > 2){ ?>
            <div class="container tab-content">
                <p class="module-header">Recently viewed</p>
                <?php
                $recently_viewed = plc_get_recently_viewed($current_user->ID);
                $limit = $count_recently_viewed > 12 ? 12 : $count_recently_viewed;
                $atts = [
                    'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                    'post__in' => $recently_viewed,
                    'default_query' => false,
                    'orderby' => 'post__in',
                    'infinite_scroll' => 'no',
                    'posts_per_page' => $limit,
                    'limit' => $limit,
                    'show_pagination' => false
                ];

                ts_blog_loop('3columncarousel', $atts);
                ?>
            </div>
            <?php } ?>

            <div class="tab-content">
                <div class="container">
                    <p class="module-header">Featured collections <a href="/collection">view more</a></p>
                </div>
                <div class="featured_destination_wrapper clearfix">
                    <ul id="featured_destination_list" class="no-padding custom-collections">
                        <?php
                        $collections = plc_get_collections($collections);

                        foreach ($collections as $collection) {
                            $post = $collection['collections_jrrnys'];
                            setup_postdata($post);
                            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
                            if (!$image_data) {
                                $hero_image = get_post_meta($post->ID, 'hero_image', true);
                                $hero_image_attr = wp_get_attachment_image_src($hero_image, 'large');
                                $image_data = $hero_image_attr;
                            }
                            $backgroundStyle = "background-image: url('" . $image_data[0] . "')";
                            ?>
                            <li>
                                <div style="<?php echo $backgroundStyle; ?>" class="fetured-content">
                                    <a href="<?php echo the_permalink(); ?>" class="fetured-content-link"></a>
                                    <div class="plc-table">
                                        <div class="plc-cell">
                                            <?php echo the_title(); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="bucket_list">
            <div class="container tab-content">
                <p class="module-header">Bucket list <a href="/bucket-list">view more</a></p>
                <div id="bucket_list_wrapper">
                <?php
                $count_bucket_list = plc_count_bucket_list($current_user->ID);
                if($count_bucket_list > 2){ ?>
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

                    ts_blog_loop('3columncarousel', $atts);
                    ?>
                 <?php }
                 else { ?>
                <p class="tab-text">Your bucket list is empty or less than 3</p>
                 <?php } ?>
                </div>
            </div>
            <div class="container tab-content">
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
            <?php if($count_recently_viewed > 2){ ?>
            <div class="container tab-content">
                <p class="module-header">Recently viewed</p>
                <?php
                $recently_viewed = plc_get_recently_viewed($current_user->ID);
                $limit = $count_recently_viewed > 12 ? 12 : $count_recently_viewed;
                $atts = [
                    'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
                    'post__in' => $recently_viewed,
                    'default_query' => false,
                    'orderby' => 'post__in',
                    'infinite_scroll' => 'no',
                    'posts_per_page' => $limit,
                    'limit' => $limit,
                    'show_pagination' => false
                ];

                ts_blog_loop('3columncarousel', $atts);
                ?>
            </div>
            <?php } ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="my_jrrnys">
            <div class="container tab-content">
                <p class="module-header">My Jrrnys <a href="<?php echo home_url('author/' . $current_user->user_login);?>">view more</a></p>
                <?php
                $user_post_count = count_user_posts( $current_user->ID , 'post' );
                if($user_post_count > 2){
                    $atts = array(
                        'author'        =>  $current_user->ID,
                        'orderby'       =>  'post_date',
                        'order'         =>  'DESC',
                        'post_status'   =>  'publish',
                        'posts_per_page' => 12,
                        'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                        'default_query' => false,
                        'custom_pagination' => false,
                        'infinite_scroll' => 'no',
                        'show_pagination' => false
                    );

                    ts_blog_loop('3columncarousel', $atts);
                }
                else{ ?>
                <p class="tab-text">You need to create at least 3 jrrnys</p>
                <?php }
                ?>

            </div>
            <div class="container tab-content">
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

</div>