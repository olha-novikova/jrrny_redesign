<?php
global $current_user;
wp_get_current_user();

if (isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
}
else {
    $curauth = get_userdata(intval($author));
}
$location = get_user_meta($curauth->ID, "_user_location", true);
$likesCount = get_post_meta($curauth->ID, "likes_count");
if (!$location) {
    $location = "";
}
else {
    $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"] . ", " . country_full_name($location["country"]) );
}
?>

<div id="main-container-wrap" 
     class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?> brand">
    <!-- <div class="row"> -->
    <?php
    $headImgId = get_user_meta($curauth->ID, 'wsl_current_user_head_image', true);
    $headImgSrc = wp_get_attachment_image_src($headImgId, ['1920', '360'])[0];
    $blur = '';
    if (!$headImgSrc) {
        $home_img = get_option('plc_header_images');
        $home_img = explode(',', $home_img);
        $headImgSrc = isset($home_img[0]) ? $home_img[0] : '';
        $blur = ' blured';
    }

    $class = "no-image";
    if ($headImgSrc) {
        //$headImgSrc = wp_get_attachment_image_src($headImgId, ['1920','360'])[0];
        $backgroundStyle = 'url(' . $headImgSrc . ')';
        $class = "image";
    }
    else {
        $backgroundStyle = '#fff';
    }
    ?>
    <div id="profile-header" class="<?php echo $class . $blur; ?>" style="background: <?= $backgroundStyle ?>; background-size: cover;">
        <div id="user-brand" class="plc-table">
            <div class="plc-table-cell">
                <div class="profile-header-container">
                    <div class="container-fluid">
                        <div id="profile-data-bar" class="row row-eq-height">
                            <div class="col-xs-12 col-sm-4 no-padding-right">
                                <?php if ($curauth->user_url): ?>
                                    <a href="<?php echo $curauth->user_url; ?>">
                                    <?php echo get_avatar($curauth->ID, 256, '', $curauth->user_login); ?>
                                    </a>
<?php else: ?>
    <?php echo get_avatar($curauth->ID, 256, '', $curauth->user_login); ?>                        
<?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-sm-8 no-padding-left">
                                <div id="profile-data-content">
                                    <div class="name">
                                        <?php echo ($curauth->user_firstname && $curauth->user_lastname ? $curauth->user_firstname . " " . $curauth->user_lastname : $curauth->user_login); ?>
                                    </div>
                                    <div class="link">
                                        <?php echo "<a href='{$curauth->user_url}'>{$curauth->user_url}</a>"; ?>
                                    </div>                               
                                    <div class="social_link">
                                        <?php echo plc_brand_social_links($curauth->ID); ?>
                                    </div>
                                        <?php if (is_user_logged_in()) : ?>
                                        <div id="edit-profile_badge">
                                            <?php if (current_user_can('edit_user', $curauth->ID)) : ?>
                                                <a href="" id="jrrny-author-edit-btn" class="btn btn-red" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</a>
                                            <?php endif; ?>
    <?php if ($current_user->ID != $curauth->ID) : ?>
                                                <a href="" id="jrrny-author-follow-btn" class="btn btn-black-material 
                                                    <?= (is_follow($curauth->ID)) ? 'followed' : '' ?>" 
                                                   data-user-id="<?= $curauth->ID ?>">

                                            <?= (is_follow($curauth->ID)) ? '<i class="fa fa-check"></i>&nbsp;following' : 'follow' ?>
                                                </a>
    <?php endif; ?>
                                        </div>
<?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php if (is_user_logged_in() && current_user_can('edit_user', $curauth->ID)) : ?>
            <form id="jrrny-edit-header-form" action="<?= admin_url() . 'admin-ajax.php' ?>" method="post" enctype="multipart/form-data">
                <p id="jrrny-edit-header-form-error" class="hidden"></p>
                <input type="hidden" name="user-id" value="<?= $curauth->ID ?>">
                <input type="hidden" name="action" value="edit_head_img">
                <div class="fileUpload btn btn-danger">
                    <span><?php if ($class === 'image') { ?>Edit image<?php }
    else { ?>Add Photo<?php } ?></span>
                    <input type="file" class="upload" name="head-img" id="jrrny-edit-header-file" autocomplete="off">
                </div>

            </form>
<?php endif; ?>
    </div>

    <div id="brand-description" class="container clearfix">
        <div class="col-sm-12">
            <div class="brand-description">
            <?php echo get_option('ad_1'); ?>
                <div class="fd"><?php echo nl2br(get_user_meta($curauth->ID, 'full_description', true)); ?></div>
            </div>
            <p class="module-header">Trending</p>
            <?php
            $trendingIds = getTrendingPostsIds();
            $atts = [
                'post__in' => $trendingIds,
                'author' => $curauth->ID,
                'default_query' => false,
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'show_pagination' => false,
                'orderby' => 'rand',
                'infinite_scroll' => 'yes',
                'branded' => true
            ];
            //$ts_query =  new WP_Query($atts);

            ts_blog_loop('3column', $atts);
           
            ?>
            <p class="module-header">Most Recent</p>
            <div class="posts personal-posts">
                <?php
                $atts_author = array(
                    'author' => $curauth->ID,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'posts_per_page' => 3,
                    'show_pagination' => false,
                    'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                    'default_query' => false,
                    'custom_pagination' => false,
                    'infinite_scroll' => 'no'
                );
                //$ts_query =  new WP_Query($atts_author);
                ts_blog_loop('3column', $atts_author);
                ?>
            </div>

<?php if ($current_user->ID === $curauth->ID || is_follow($curauth->ID)) { ?>
                <p class="module-header">Specials & Offers</p>
                <div class="so"><?php echo nl2br(get_user_meta($curauth->ID, 'specials_offers', true)); ?></div>
<?php } ?>
        </div>
    </div>
</div> 
<?php get_template_part('inc/frontend/profile/edit-brand-form'); ?>