<?php
$hero_post = get_post_meta($post->ID, 'hero_post', true);
$top_tagline = get_post_meta($post->ID, 'top_tagline', true);
$join_image = get_post_meta($post->ID, 'join_image', true);
$join_image_attr = wp_get_attachment_image_src($join_image, 'full');
$join_image_paralax = get_post_meta($post->ID, 'join_image_paralax', true);
$collections = get_field('collections', $post->ID);
$top_contributors = get_field('top_contributors', $post->ID, true);

$ad1 = get_option('ad_1');

?>
<?php if ($ad1) { ?>
    <div class="container">
        <?php echo $ad1; ?>
    </div>
<?php } ?>

<div id="homepage-notlogged">

<div  class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">

<?php echo do_shortcode('[show_video]'); ?>

<div id="futured-section" class="clearfix">
    <div class="header-section">
        <div class="container">
            <p class="module-header">FEATURED ARTICLES AND JRRNYS</p>
        </div>
    </div>
    <?php

    $primaryPost = plc_get_primary_posts();

    $atts = [
        'post_type'         => array('post', 'sponsored_post', 'featured_destination', 'contest'),
        'post__in'          => $primaryPost,
        'default_query'     => false,
        'orderby'           => 'post__in',
        'infinite_scroll'   => 'no',
        'posts_per_page'    => 6,
        'limit'             => 6,
        'show_pagination'   => false,
        'image_size'        => 'small',
        'fullwidth'         => 1,
        'show_excerpt'      => 1,
        'excerpt_length'    => '250',
        'show_sharing_options'=> false,
        'show_title'        => 1,
        'show_meta'         => 1,
        'show_category'     => 1
    ];

    echo ts_blog('slider', $atts);

    ?>
</div>

<div id="main-container" class="clearfix">
    <div class="header-section">
        <div class="container">
            <p class="module-header">Most Recent <a href="/all-jrrnys"> view more</a></p>
        </div>
    </div>

    <div id="recent" class="clearfix">
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
    //$ts_query =  new WP_Query($atts);

    ts_blog_loop('2column-banner', $atts);
}

if($collections){

    ?>
    <div id="futured-collection" class="clearfix">
        <div class="header-section">
            <div class="container">
                <p class="module-header">Featured Collections <a href="/collection"> view more</a></p>
            </div>
        </div>

        <div class="ts-slider-wrap loop-slider-wrap flexslider ts-edge-to-edge ts-color-section-fullwidth">
            <ul class="slides" data-slide-width="720" data-desired-slide-width="720>">
            <?php

            $collections = plc_get_collections($collections);
            //echo '<pre>'.print_r($collections, true).'</pre>';//
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
                $backgroundStyle ="background-image: url('". $image_data[0]  ."'); height:500px";
                ?>

                <li class="flex-item ts-slider-item" data-width="720" data-height="480" style="<?php echo esc_attr($backgroundStyle);?>">
                    <div class="ts-item-details">
                        <div class="ts-item-details-inner container">
                            <h2 class="blog-title title title-h color-white"><?php the_title();?></h2>
                            <div class="blog-descr">
                                <div class="read-more-wrap">
                                    <div class="read-more mimic-smaller uppercase">
                                        <a class ="btn btn-blue" href="<?php the_permalink();?>" rel="bookmark"><?php echo __('View Collection', 'ThemeStockyard');?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php }
            wp_reset_postdata();
            ?>
            </ul>
        </div>
    </div>
<?php
}
$rand_collections = plc_get_random_collections($collections, 3);
if ( $rand_collections ):
?>
<div id="rand_collections" class="clearfix">
    <div class="featured_destination_wrapper clearfix">
        <ul id="featured_destination_list" class="no-padding custom-collections">
            <?php
            foreach( $rand_collections as $collection ){
                $image_data = wp_get_attachment_image_src( get_post_thumbnail_id($collection->ID), 'large');

                if(!$image_data){
                    $hero_image = get_post_meta($collection->ID, 'hero_image', true);
                    $hero_image_attr = wp_get_attachment_image_src( $hero_image, 'large');
                    $image_data = $hero_image_attr;
                }
                $backgroundStyle ="background-image: url('". $image_data[0]  ."')";

                ?>
                <li>
                    <div style="<?php echo $backgroundStyle;?>" class="fetured-content">
                        <a href="<?php echo  get_the_permalink($collection->ID)?>" class="fetured-content-link"></a>
                        <div class="plc-table">
                            <div class="plc-cell">
                                <?php echo $collection->post_title; ?>
                            </div>
                        </div>
                    </div>
                </li>

            <?php }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</div>
<!-- #main-container -->
<?php
endif;
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