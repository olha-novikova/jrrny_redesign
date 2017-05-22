<?php
global $current_user;
wp_get_current_user();

if(isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
} else {
    $curauth = get_userdata(intval($author));
}
$location = get_user_meta($curauth->ID, "_user_location", true);
$likesCount = get_post_meta( $curauth->ID, "likes_count");
    if(!$location){
        $location = "";
    } else{
        $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"].", ".country_full_name($location["country"]) );
    }
?>

<div id="main-container-wrap" 
class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
    <!-- <div class="row"> -->
    <?php
        //$headImgId = get_user_meta($curauth->ID, 'wsl_current_user_head_image', true );
        $home_img = get_option('plc_header_images');
        $home_img = explode(',', $home_img);
        $headImgId = isset($home_img[0]) ? $home_img[0] : '';
        
        $class = "no-image";
        if($headImgId){
            //$headImgSrc = wp_get_attachment_image_src($headImgId, ['1920','360'])[0];
            $backgroundStyle = 'url('. $headImgId  .')';
            $class = "image";
        }else {
            $backgroundStyle = '#fff';
        } 
    ?>
    <div id="profile-header" class="<?php echo $class; ?> blured" style="background: <?= $backgroundStyle ?>; background-size: cover;">
        <div class="plc-table">
            <div class="plc-table-cell">
                <div class="profile-header-container">
                    <div class="container-fluid">
                        <div id="profile-data-bar" class="row row-eq-height">
                            <div class="col-xs-12 col-sm-4 no-padding-right">
                                <?php if($curauth->user_url): ?>
                                    <a href="<?php echo $curauth->user_url; ?>">
                                        <?php echo get_avatar( $curauth->ID, 256, '', $curauth->user_login ); ?>
                                    </a>
                                <?php else:?>
                                    <?php echo get_avatar( $curauth->ID, 256, '', $curauth->user_login ); ?>                        
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-sm-8 no-padding-left">
                                <div id="profile-data-content" class="padding-left-50">
                                    <div class="name">
                                        <?php echo ($curauth->user_firstname && $curauth->user_lastname ? $curauth->user_firstname . " " . $curauth->user_lastname : $curauth->user_login);?>
                                    </div>
                                    <div class="location">
                                            <?php echo (isset($location_full) ? $location_full : "");?> <br />
                                            <?php echo "<a href='{$curauth->user_url}'>{$curauth->user_url}</a>" ;?>
                                    </div>                               
                                    <div class="description">
                                        <?php echo $curauth->description;?>
                                    </div>
                                    <?php if(is_user_logged_in()) : ?>
                                        <div id="edit-profile_badge">
                                        <?php if(current_user_can( 'edit_user', $curauth->ID )) : ?>
                                                <a href="" id="jrrny-author-edit-btn" class="btn btn-red" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</a>
                                        <?php endif; ?>
                                        <?php if($current_user->ID != $curauth->ID) : ?>
                                            <a href="" id="jrrny-author-follow-btn" class="btn btn-black-material 
                                            <?=(is_follow($curauth->ID)) ? 'followed' : '' ?>" 
                                            data-user-id="<?=$curauth->ID?>">

                                            <?=(is_follow($curauth->ID)) ? '<i class="fa fa-check"></i>&nbsp;following': 'follow'?>
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
        
        <?php /* if(is_user_logged_in() && current_user_can( 'edit_user', $curauth->ID )) : ?>
            <form id="jrrny-edit-header-form" action="<?= admin_url().'admin-ajax.php'?>" method="post" enctype="multipart/form-data">
                <p id="jrrny-edit-header-form-error" class="hidden"></p>
                <input type="hidden" name="user-id" value="<?= $curauth->ID?>">
                <input type="hidden" name="action" value="edit_head_img">
                <div class="fileUpload btn btn-danger">
                    <span><?php if($class === 'image'){ ?>Edit image<?php } else { ?>Add Photo<?php }?></span>
                    <input type="file" class="upload" name="head-img" id="jrrny-edit-header-file" autocomplete="off">
                </div>

            </form>
        <?php endif; */?>
    </div>
    <div id="profile-stats-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">             
                    <span class="stats jrrnys"><b><?php echo sprintf("%02d", getJrrnysCount($curauth->ID)); ?> </b><small>JRRNYS</small></span>
                    <span class="stats upvoted"><b><?php echo sprintf("%02d", getUpvotesCount($curauth->ID)); ?></b> <small>LIKED</small></span>
                </div>
            </div>
        </div>
    </div>
    <?php /*
    <div id="profile-data-wrapper">
        <div class="profile-clear visible-xs-block"></div>
        <div id="profile-data-bar" class="container-custom">
            <div class="row">
                <div class="col-sm-9 col-sm-push-3 name"><?php echo ($curauth->user_firstname && $curauth->user_lastname ? $curauth->user_firstname . " " . $curauth->user_lastname : $curauth->user_login);?></div>
            </div>
            <div class="row">
                <div class="col-sm-9 col-sm-push-3 location">
                    <?php echo (isset($location_full) ? $location_full : "");?> <br />
                    <?php echo "<a href='{$curauth->user_url}'>{$curauth->user_url}</a>" ;?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 col-sm-push-3 description"><?php echo $curauth->description;?></div>
            </div>
        </div>
    </div> */?>
    <div id="tabs-bar">
        <div id="profile-tabs" class="container-custom">
            <div class="row">
                <div data-tab="upvoted" class="col-xs-4 text-center profile-tab"><i class="fa fa-thumbs-o-up"></i> <span class="hidden-xs"><?php echo (get_current_user_id() == $curauth->ID) ? "i've " : "";?>liked</span></div>
                <div data-tab="my-jrrnys" class="col-xs-4 text-center profile-tab active"><i class="fa fa-map-marker"></i> <span class="hidden-xs"><?php echo (get_current_user_id() == $curauth->ID) ? "my " : "";?>jrrnys</span></div>
                <div data-tab="following" class="col-xs-4 text-center profile-tab"><i class="fa fa-heart"></i> <span class="hidden-xs">following</span></div>
            </div>
        </div>
    </div>
    <div id="main-container" class="container clearfix">
        <div class="col-sm-12">
            <div class="tab-page" id="tab-upvoted">
                <h2><i class="fa fa-thumbs-o-up"></i> Liked</h2>
                <div class="posts liked-posts">
                    <?php
                    $curauth = get_userdata(intval($author));
                    $likedPostsId = getLikedPostsIds($curauth->ID);
                    if(count($likedPostsId) > 0){
                        
                           $atts_liked = array(
                            'orderby'       =>  'post_date',
                            'order'         =>  'DESC',
                            'posts_per_page' => 10,
                            'post__in' => $likedPostsId,
                            'default_query' => false,
                            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                            'infinite_scroll' => 'yes_button',
                            'custom_pagination' => true,
                            'post_status'   =>  'publish',
                        );
                    }else {
                        $atts_liked = array(
                            'post_type'        => 'none',
                            'default_query' => false,
                        );
                    }
                   // $ts_query =  new WP_Query($atts_liked);
                    ts_blog_loop('thumbnail', $atts_liked);
                    ?>
                </div>
            </div>
            <div class="tab-page visible" id="tab-my-jrrnys">
                <h2><i class="fa fa-map-marker"></i> Jrrnys</h2>
                <?php get_template_part('inc/frontend/partauthor/report-part'); ?>


                <div class="posts personal-posts">
                    <?php
                    $curauth = get_userdata(intval($author));
                    $atts_author = array(
                        'author'        =>  $curauth->ID,
                        'orderby'       =>  'post_date',
                        'order'         =>  'DESC',
                        'post_status'   =>  'publish',
                        'posts_per_page' => 10,
                        'post_type' => array('post', 'sponsored_post', 'featured_destination'),
                        'default_query' => false,
                        'custom_pagination' => true,
                        'infinite_scroll' => 'yes_button'
                    );
                    //$ts_query =  new WP_Query($atts_author);
                    ts_blog_loop('thumbnail', $atts_author);
                    ?>
                </div>
            </div>
            <div class="tab-page" id="tab-following">
                <h2><i class="fa fa-heart"></i> following</h2>
                <div class="posts personal-posts">
                    <?php
                    $followUsersIds = getFollowedUsersIds($curauth->ID);
                    foreach ( $followUsersIds as $followUserId ) : 
                        $followUser =  get_userdata(intval($followUserId ));
                        $firstName = get_user_meta($followUserId, 'first_name', true);
                        $lastName =   get_user_meta($followUserId, 'last_name', true);
                        $location = get_user_meta($followUserId, '_user_location', true);
                        $city = (isset($location['city'])) ? $location['city'] : '';
                        $country = (isset($location['country'])) ? $location['country'] : '';
                        $country_full = country_full_name($country);
                        $description = get_user_meta($followUserId, 'description', true);
                    ?>

                    <div class="row followed-author" id="jrrny-author-<?=$followUserId?>">
                        <div class="col-sm-3">                            
                            <a href="<?php echo get_author_posts_url( $followUserId ); ?>"> 
                                <?php echo get_avatar( $followUserId, 256, '', $followUser->user_login ); ?>
                            </a>                        
                        </div>
                        <div class="col-sm-9">
                            <h1><?= $firstName?>&nbsp;<?= $lastName?></h1>
                            <h2><?= $city ?>,&nbsp;<?= $country_full ?></h2>
                            <div class="description"><?= $description ?></div>
                            <a class="btn button" href="<?= get_author_posts_url( $followUserId ); ?>">view profile</a>
                            <a href="#" data-author="<?php echo $followUserId;?>" data-following="1" class="btn btn-red btn-no-radius meta-item-follow">unfollow</a>
                        </div>
                    </div>

                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div> 
<?php get_template_part('inc/frontend/profile/edit-form'); ?>