<?php
global $current_user;

$collections = get_field('collections', get_option('page_on_front'));

?>

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

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="feed">
            <div class="container homepage-tab-content">
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
            <div class="container homepage-tab-content">
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
            <div class="container homepage-tab-content">
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
            <div class="homepage-tab-content">
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
            <div class="container homepage-tab-content">
                <p class="module-header">Bucket list</p>
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
            <div class="container homepage-tab-content">
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
            <div class="container homepage-tab-content">
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
            <div class="container homepage-tab-content">
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
            <div class="container homepage-tab-content">
                <p class="tab-text">Leave Comments about your experiences:</p>
                <p class="module-header">Completed</p>  
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