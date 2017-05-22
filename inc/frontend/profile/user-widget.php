<?php
global $current_user, $post;

$author = get_userdata(intval($post->post_author));

$location = get_user_meta($author->ID, "_user_location", true);
if (!$location) {
    $location = "";
}
else {
    $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"] . ", " . country_full_name($location["country"]) );
    
}
$author_url = '/author/' . $author->user_login;
?>
<div class="user-widget">
    <div class="left">
        <div class="inner-div">
            <a href="<?php echo $author_url; ?>">
                <?php echo get_avatar($author->ID, 256, '', $author->user_login); ?>
            </a>
            <?php if (is_user_logged_in()) : ?>
                <div id="edit-profile_badge">
                    <?php if ($current_user->ID != $author->ID) : ?>
                        <?php get_template_part('inc/frontend/partpost/following-part' ,'new'); ?>
                    <?php endif; ?>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="right">
        <div class="name">
            <a href="<?php echo $author_url; ?>"><?php echo ($author->user_firstname && $author->user_lastname ? $author->user_firstname . " " . $author->user_lastname : $author->user_login); ?></a>
        </div>
        <div class="location">
            <?php echo $author->user_url;; echo "<a class=\"btn btn-transparent-white\" id='user-link' href='".$author_url."'>view profile</a>"; ?>
            <?php if (current_user_can('edit_user', $author->ID)) : ?>
                <button class="btn btn-transparent-white" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</button>
            <?php endif; ?>
        </div>
    </div>
</div>
