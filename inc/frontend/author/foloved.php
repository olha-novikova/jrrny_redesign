<?php
global $current_user;
//wp_get_current_user();

if (isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
}
else {
    $curauth = get_userdata(intval($author));
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$followUsersIds = getFollowedUsersIds($curauth->ID);

$location = get_user_meta($curauth->ID, "_user_location", true);
$likesCount = get_post_meta($curauth->ID, "likes_count");
if (!$location) {
    $location = "";
}
else {
    $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"] . ", " . country_full_name($location["country"]) );
}
$display_name = ($curauth->user_firstname && $curauth->user_lastname ? $curauth->user_firstname . " " . $curauth->user_lastname : $curauth->user_login);
$home_img = get_option('plc_header_images');
$home_img = explode(',', $home_img);
$headImgId = isset($home_img[0]) ? $home_img[0] : '';

$class = "no-image";
if ($headImgId) {
    $backgroundStyle = 'url(' . $headImgId . ')';
    $class = "image";
}
else {
    $backgroundStyle = '#fff';
}
?>

    <div id="main-container-wrap"
         class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
        <div id="profile-header" class="<?php echo $class; ?> blured" style="background: <?= $backgroundStyle ?>; background-size: cover;">
            <div class="plc-table">
                <div class="plc-table-cell">
                    <div class="profile-header-container">
                        <div class="container-fluid">
                            <div id="profile-data-bar" class="row row-eq-height">
                                <div class="col-xs-12 col-sm-3 no-padding-right">
                                    <?php if ($curauth->user_url): ?>
                                        <a href="<?php echo $curauth->user_url; ?>">
                                            <?php echo get_avatar($curauth->ID, 128, '', $curauth->user_login); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo get_avatar($curauth->ID, 128, '', $curauth->user_login); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-9 no-padding-left">
                                    <div id="profile-data-content">
                                        <div class="name">
                                            Hi, I'm <?php echo $display_name; ?>
                                        </div>
                                        <div class="location">
                                            <?php echo (isset($location_full) ? $location_full : ""); ?> <br />
                                            <?php echo "<a href='{$curauth->user_url}'>{$curauth->user_url}</a>"; ?>
                                        </div>
                                        <div class="description">
                                            <?php echo $curauth->description; ?>
                                        </div>
                                        <?php if (is_user_logged_in()) : ?>
                                            <div id="edit-profile_badge">
                                                <?php if (current_user_can('edit_user', $curauth->ID)) : ?>
                                                    <a href="" id="jrrny-author-edit-btn" class="btn btn-white-border" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</a>
                                                <?php endif;?>
                                                <?php if ($current_user->ID != $curauth->ID) : ?>
                                                    <a href="" id="jrrny-author-follow-btn" class="btn btn-white-border
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
        </div>

        <div id="following-section-2" class="clearfix">
            <div class="header-section">
                <div class="container">
                    <p class="module-header">Followed</p>
                </div>
            </div>
            <div class="loop-wrap loop-3-column-wrap ">
                <div class="row">
                <?php
                $i=0;
                foreach ($followUsersIds as $followUserId) :
                    $followUser = get_userdata(intval($followUserId));
                    $firstName = get_user_meta($followUserId, 'first_name', true);
                    $lastName = get_user_meta($followUserId, 'last_name', true);
                    $name = ($firstName && $lastName ? $firstName . '&nbsp;' . $lastName : $followUser->user_nicename);
                    $img = get_user_meta($followUserId, 'wsl_current_user_image', true);

                    if ($img == '') $img = get_stylesheet_directory_uri()."/images/jrrny_join_our_community.jpeg";

                    ?>

                    <div id="post-<?php echo $followUserId;?>" class="hentry entry col-xs-12 col-sm-6 col-md-4">
                        <div class="friend fetured-content" style="background-image: url(<?php echo $img;?>)">
                            <a class="fetured-content-link" href="<?php echo get_author_posts_url($followUserId); ?>"></a>
                            <div class="plc-table">
                                <div class="plc-cell">
                                    <h1 class="title-h entry-title">
                                        <a class="fetured-content-link" href="<?php echo get_author_posts_url($followUserId); ?>"><?= $name; ?> </a>
                                    </h1>
                                    <?php
                                    if ( get_the_author_meta('ID')!= $current_user->ID){
                                        $following = '0';
                                        $following_text = 'follow';

                                        if (is_follow($followUserId)) {
                                            $following = '1';
                                            $following_text = 'unfollow';
                                        }?>

                                        <a href="#" data-author="<?php echo $followUserId; ?>" data-following="<?php echo $following; ?>" class="btn btn-follow-loop meta-item-follow"><?php echo$following_text ?></a>
                                    <?php }?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                $i++;
                if ( $i == 3 ) {echo "</div> <div class=\"row\">";$i=0;}

                endforeach; ?>
                </div>
        </div>

    </div>

<?php get_template_part('inc/frontend/profile/edit-form'); ?>