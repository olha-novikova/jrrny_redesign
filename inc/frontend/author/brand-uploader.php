<?php
global $current_user;
wp_get_current_user();


$location = get_user_meta($current_user->ID, "_user_location", true);
$likesCount = get_post_meta($current_user->ID, "likes_count");
if (!$location) {
    $location = "";
}
else {
    $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"] . ", " . country_full_name($location["country"]) );
}
?>

<div class="brand">
    <!-- <div class="row"> -->
    <?php
    $headImgId = get_user_meta($current_user->ID, 'wsl_current_user_head_image', true);
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
        <div class="plc-table">
            <div class="plc-table-cell">
                <div class="profile-header-container">
                    <div class="container-fluid">
                        <div id="profile-data-bar" class="row row-eq-height">
                            <div class="col-xs-12 col-sm-4 no-padding-right">
                                <?php if ($current_user->user_url): ?>
                                    <a href="<?php echo $current_user->user_url; ?>">
                                    <?php echo get_avatar($current_user->ID, 256, '', $current_user->user_login); ?>
                                    </a>
<?php else: ?>
    <?php echo get_avatar($current_user->ID, 256, '', $current_user->user_login); ?>                        
<?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-sm-8 no-padding-left">
                                <div id="profile-data-content">
                                    <div class="name">
                                        <?php echo ($current_user->user_firstname && $current_user->user_lastname ? $current_user->user_firstname . " " . $current_user->user_lastname : $current_user->user_login); ?>
                                    </div>
                                    <div class="link">
                                        <?php echo "<a href='{$current_user->user_url}'>{$current_user->user_url}</a>"; ?>
                                    </div>                               
                                    <div class="social_link">
                                        <?php echo plc_brand_social_links($current_user->ID); ?>
                                    </div>
                                        <?php if (is_user_logged_in()) : ?>
                                        <div id="edit-profile_badge">
                                            <?php if (current_user_can('edit_user', $current_user->ID)) : ?>
                                                <a href="" id="jrrny-author-edit-btn" class="btn btn-red" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</a>
                                            <?php endif; ?>
    <?php if ($current_user->ID != $current_user->ID) : ?>
                                                <a href="" id="jrrny-author-follow-btn" class="btn btn-black-material 
                                                    <?= (is_follow($current_user->ID)) ? 'followed' : '' ?>" 
                                                   data-user-id="<?= $current_user->ID ?>">

                                            <?= (is_follow($current_user->ID)) ? '<i class="fa fa-check"></i>&nbsp;following' : 'follow' ?>
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

<?php if (is_user_logged_in() && current_user_can('edit_user', $current_user->ID)) : ?>
            <form id="jrrny-edit-header-form" action="<?= admin_url() . 'admin-ajax.php' ?>" method="post" enctype="multipart/form-data">
                <p id="jrrny-edit-header-form-error" class="hidden"></p>
                <input type="hidden" name="user-id" value="<?= $current_user->ID ?>">
                <input type="hidden" name="action" value="edit_head_img">
                <div class="fileUpload btn btn-danger">
                    <span><?php if ($class === 'image') { ?>Edit image<?php }
    else { ?>Add Photo<?php } ?></span>
                    <input type="file" class="upload" name="head-img" id="jrrny-edit-header-file" autocomplete="off">
                </div>

            </form>
<?php endif; ?>
    </div>
    </div>
<?php get_template_part('inc/frontend/profile/edit-brand-form-single'); ?>