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

if (isset($_GET['all'])){
    switch ($_GET['all']){
        case 'jrrnys':
            $page_title =  (get_current_user_id() == $curauth->ID) ? "My Jrrnys": "Jrrnys";
            $atts_loop = array(
                'author'            => $curauth->ID,
                'orderby'           => 'post_date',
                'order'             => 'DESC',
                'post_status'       => 'publish',
                'posts_per_page'    => 24,
                'post_type'         => array('post'),
                'default_query'     => true,
                'show_pagination'   => true,
                'infinite_scroll'   => 'no',
                'show_excerpt'      => 'false',
                'show_category'     => 'false'
            );
            break;
        case 'liked':
            $page_title =  (get_current_user_id() == $curauth->ID) ? "I've Liked" : "Liked";

            $likedPostsId = getLikedPostsIds($curauth->ID);

            if (count($likedPostsId) > 0) {

                $atts_loop = array(
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'posts_per_page'    => 24,
                    'offset'            => ($paged-1)*24,
                    'post__in'          => $likedPostsId,
                    'default_query'     => false,
                    'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
                    'show_pagination'   => true,
                    'post_status'       => 'publish',
                    'show_excerpt'      => 'false',
                    'show_category'     => 'false'
                );
            }
            else {
                $atts_loop = array(
                    'post_type'         => 'none',
                    'default_query'     => false,
                    'show_excerpt'      => 'false',
                    'show_category'     => 'false'
                );
            }
            break;
        case 'follow':
            $page_title =  (get_current_user_id() == $curauth->ID) ? "I'm following" : "Following";

            $followUsersIds = getFollowedUsersIds($curauth->ID);

            $atts_followed_user = array(
                'orderby'           => 'post_date',
                'author__in'        => $followUsersIds,
                'order'             => 'DESC',
                'posts_per_page'    => -1,
                'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
            );

            $query = new WP_Query;

            $my_posts = $query->query( $atts_followed_user );

            $followUsersPostsIds = array();

            foreach( $my_posts as $my_post ){
                if ( !array_key_exists($my_post->post_author, $followUsersPostsIds) )
                    $followUsersPostsIds[$my_post->post_author] = $my_post->ID;
                else continue;
            }

            if ( is_array($followUsersPostsIds) && count($followUsersPostsIds)>0 ){
                $atts_loop = array(
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'posts_per_page'    => 24,
                    'offset'            => ($paged-1)*24,
                    'post__in'          => $followUsersPostsIds,
                    'default_query'     => false,
                    'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
                    'infinite_scroll'   => 'no',
                    'show_pagination'   => true,
                    'post_status'       => 'publish',
                    'show_excerpt'      => 'false',
                    'show_category'     => 'false'
                );
            }else{
                $atts_loop = array(
                    'post_type'         => 'none',
                    'default_query'     => false,
                    'show_excerpt'      => 'false',
                    'show_category'     => 'false'
                );
            }
            break;
        case 'followed':


            break;
    }
}

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
if ( $_GET['all'] == 'followed'){
    get_template_part("inc/frontend/author/foloved");
}else{
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

    <div id="jrrnys-section" class="clearfix">
        <div class="header-section">
            <div class="container">
                <p class="module-header"><?php  echo $page_title; ?></p>
            </div>
        </div>
        <?php
        ts_blog_loop('3column', $atts_loop);
        ?>
    </div>

</div>
<?php }?>

<?php get_template_part('inc/frontend/profile/edit-form'); ?>