<?php 
$hero_post = get_post_meta($post->ID, 'hero_post', true);
$top_tagline = get_post_meta($post->ID, 'top_tagline', true);
$join_image = get_post_meta($post->ID, 'join_image', true);
$join_image_attr = wp_get_attachment_image_src($join_image, 'full');
$join_image_paralax = get_post_meta($post->ID, 'join_image_paralax', true);
$collections = get_field('collections', $post->ID);
$top_contributors = get_field('top_contributors', $post->ID, true);
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
$ad1 = get_option('ad_1');
?>
<?php if ($ad1) { ?>
    <div class="container">
        <?php echo $ad1; ?>
    </div>
<?php } ?>

<div id="homepage-notlogged">
<?php if ($top_tagline): ?>
<div class="tag-heading">
    <div class="container">
            <?php /*
                We're creating the world's largest travel magazine, built by the people!
                <br>
                Check out the latest jrrnys below, or view our
                <a href="<?php echo home_url('/collection'); ?>">featured collections</a>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <a href="<?php echo home_url();?>/collection" class="btn btn-lg btn-block btn-blue btn-homepage">View Featured Collections</a>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <a href="<?php echo home_url();?>/map" class="btn btn-lg btn-block btn-link btn-homepage">Click here to visit selected jrrny by location</a>
                        </div>
                    </div>
                    <p class="top-tagline">
                        We're creating the world's largest travel magazine, built by the people!<br>
                        <a href="#" class="takeTour">Take a quick tour</a>,  <a href="<?php echo home_url();?>/upload">add your first post</a>, or <a href="<?php echo home_url()?>/trending">view what's trending</a>.
                    </p>
                    <?php */ ?>
        <div class="top-tagline">
            <?php echo apply_filters('the_content', $top_tagline);?>
        </div>
    </div>
</div>
    <?php endif ?>

<div id="main-container-wrap"
     class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">

<?php if ( $show_home_video == 1 ){?>
    <div id="main-container" class="container clearfix produced_by_jrrny not_logged">
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


    <div id="main-container" class="container clearfix">
        
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
        $ts_query = new WP_Query($atts);

        ts_blog_loop('3column', $atts);
        ?>
        <?php /*
        <div class="row">
            <div class="col-xs-12 new-search-wrapper">
                <?php echo ts_top_search(); ?>
            </div>
        </div>*/ ?>
    </div>
     
    <div id="main-container" class="container clearfix">
        <p class="module-header">Most Recent <a href="/all-jrrnys">...more</a></p>
        <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
            <div class="entry single-entry clearfix">
                <?php
                if (have_posts()) :
                while (have_posts()) : the_post();
                    ?>
                    <div id="ts-post-content-sidebar-wrap" class="clearfix">
                        <div id="ts-post-wrap">
                            <div id="ts-post" <?php post_class('post clearfix'); ?>>
                                <div id="ts-post-the-content-wrap">
                                    <div id="ts-post-the-content">

                                        <?php the_content(); ?>
                                        <?php wp_link_pages('before=<div class="page-links">' . __('Pages: ', 'ThemeStockyard') . '<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>'); ?>

                                    </div>
                                </div>
                            </div>
                            <?php if ($ts_page_comments) : ?>
                                <div id="ts-comments-wrap-wrap" class="clearfix">
                                    <?php
                                    if (get_comments_number() < 1) :
                                        echo '<div id="comments">';
                                        echo do_shortcode('[divider height="0"]');
                                        echo '</div>';
                                    endif;
                                    ?>
                                    <div id="ts-comments-wrap">
                                        <?php comments_template(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php ts_get_content_sidebar(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php 
    if($hero_post){
    //Banner HERO Tag HERO
            $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
            'post__in' => $hero_post,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => 2,
            'limit' => 2,
            'show_pagination' => false
        ];
        $ts_query =  new WP_Query($atts);

        ts_blog_loop('2column-banner', $atts);
    }/*
    else{
        //Banner HERO Tag HERO
        $atts = [
            'post_type' => 'any',
            'tag' => 'HERO',
            'default_query' => false,
            'infinite_scroll' => 'no',
            'posts_per_page' => 2,
            'limit' => 2,
            'show_pagination' => false
        ];
        $ts_query = new WP_Query($atts);

        ts_blog_loop('2column-banner', $atts);
    } */

    if($collections){
    ?>
    
    <div class="container">
        <p class="module-header">Featured Collections <a href="/collection">view all collections</a></p>
    </div>
    <div class="featured_destination_wrapper clearfix">
        <ul id="featured_destination_list" class="no-padding custom-collections">
        <?php
            
            $collections = plc_get_collections($collections);
            //echo '<pre>'.print_r($collections, true).'</pre>';
            foreach($collections as $collection)
            {
                $post = $collection['collections_jrrnys'];
                setup_postdata( $post );
                $image_data = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
                if(!$image_data){                    
                    $hero_image = get_post_meta($post->ID, 'hero_image', true);
                    $hero_image_attr = wp_get_attachment_image_src( $hero_image, 'large');
                    $image_data = $hero_image_attr;
                }
                $backgroundStyle ="background-image: url('". $image_data[0]  ."')";
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
    <?php }  ?>   
<!-- #main-container -->
    <div id="join-container" class="clearfix<?php echo $join_image_paralax ? ' parallax' : ''?>" style="background-image: url('<?php echo $join_image_attr[0];?>');">
        <div class="join-table">
            <div class="join-cell">
                <div class="join-wrapper">    
                <div class="container">
                    <div class="row">   
                        <div class="col-xs-12 title">
                            Share all of your travel stories
                        </div>
                        <div class="col-xs-12 subtitle">
                            Join the thousands of other travelers <br/>sharing their experiences on jrrny
                        </div>
                        <div class="col-xs-12 description">
                            <a href="/register" class="btn btn-blue">Join now</a>
                          <?php /* <p>Find out how to become a jrrny <a href="<?php echo home_url();?>/contribute">contributor</a></p> */?>
                        </div>
                    </div>
                </div>                                            
            </div>
            </div>
        </div>
    </div>
<?php
/*
<div id="topModule" class="container">    
    <p class="module-header">Best of the week</p>
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <div class="item-wrapper">
            <div class="top-title"><p><span class="flaticon flaticon-hearts-outline-icon"></span>Top Trending</p></div>
            <?php 
            $trendingIds = getTrendingPostsIds(5);
            $args = array(
                'include' => $trendingIds,
                'fields' => 'all',
                'orderby' => 'include',
                'order' => 'ASC'
            );
            $posts = get_posts($args); 
            if($posts){
                foreach ($posts as $post) { setup_postdata( $post );
                    include(locate_template('inc/frontend/top_module/post-item-list.php'));
                }   
                wp_reset_postdata();
            }
            ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="item-wrapper">
            <div class="top-title"><p><span class="flaticon flaticon-user-avatar-main-picture"></span>Top Contributors</p></div>
            <?php 
            $topUsersIds = getTopUsersIds(5);
            $countTopUsersIds = count($topUsersIds);
            $args = array(
                'include' => $topUsersIds,
                'fields' => 'all',
                'orderby' => 'include',
                'order' => 'ASC'
            );
            $countTopUsers = 5 - $countTopUsersIds;
            $users = '';
            if($countTopUsersIds > 0){
                $users = get_users($args); 
            }
            
            if($users){
                foreach ($users as $user) {
                    include(locate_template('inc/frontend/top_module/user-item-list.php'));
                }        
            }
            if($countTopUsers > 0){
                $topRandomUsersIds = getTopRandomUsersIds($countTopUsers, $topUsersIds);
                $args = array(
                    'include' => $topRandomUsersIds,
                    'fields' => 'all',
                    'orderby' => 'include',
                    'order' => 'ASC'
                );
                $users = get_users($args);
                foreach ($users as $user) {
                    include(locate_template('inc/frontend/top_module/user-item-list.php'));
                }
            }
            ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="item-wrapper">
            <div class="top-title"><p><span class="flaticon flaticon-map-pin-marked"></span>Top Destinations</p></div>
            <?php
            $args = array(
                'fields' => 'all',
                'orderby' => 'rand',
                'limit' => 5,
                'post_type' => 'featured_destination',
                'post_status'   =>  'publish',
            );
            $posts = get_posts($args); 
            if($posts){
                foreach ($posts as $post) { setup_postdata( $post );
                    include(locate_template('inc/frontend/top_module/post-item-list.php'));
                }   
                wp_reset_postdata();
            }
            ?>
            </div>
        </div>
    </div>
</div>


<?php
*/
if($top_contributors){    
    $topUsersIds = getTopUsersIds(5);
    $countTopUsersIds = count($topUsersIds);
    $args = array(
        'include' => $topUsersIds,
        'fields' => 'all',
        'orderby' => 'include',
        'order' => 'ASC'
    );
    $countTopUsers = 6 - $countTopUsersIds;
    $users = '';
    if($countTopUsersIds > 0){
        $users = get_users($args); 
    }?>
    <div id="users-container" class="clearfix">
        <p class="users-title">Here's this week's top contributors!</p>

        <div class="row">
            <?php
            $quota = '';
            if($users){
                foreach ($users as $user) {
                    include(locate_template('inc/frontend/top_module/user-item.php'));
                }        
            }
            if($countTopUsers > 0){
                $topRandomUsersIds = getTopRandomUsersIds($countTopUsers, $topUsersIds);
                $args = array(
                    'include' => $topRandomUsersIds,
                    'fields' => 'all',
                    'orderby' => 'include',
                    'order' => 'ASC'
                );
                $users = get_users($args);
                foreach ($users as $user) {
                    include(locate_template('inc/frontend/top_module/user-item.php'));
                }
            }
            ?>
        </div>
        <?php
        if ($quota) {
            $q = explode('|', $quota);
            echo '<div id="user-quota-container">';
            echo '<p class="user-quota">&quot;' . $q[0] . '&quot;</p>';
            echo '<p class="user-quota-author">- ' . $q[1] . '</p>';
            echo '</div>';
        }
        ?>
    </div>
<?php } ?>

<?php endif; ?>
</div><!-- #main-container-wrap -->
</div>