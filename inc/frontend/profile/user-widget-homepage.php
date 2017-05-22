<?php
global $current_user, $post;

$author = get_userdata(intval($current_user->ID));

$location = get_user_meta($current_user->ID, "_user_location", true);
if (!$location) {
    $location = "";
}
else {
    $location_full = (empty($location["city"]) ? country_full_name($location["country"]) : $location["city"] . ", " . country_full_name($location["country"]) );
}
$author_url = '/author/' . $author->user_login;
?>
<div class="col-xs-12 col-sm-10 col-sm-push-1">
<div class="user-widget">
    <div class="left">
            <a href="<?php echo $author_url; ?>">
                <?php echo get_avatar($author->ID, 256, '', $author->user_login); ?>
            </a>
    </div>
    <div class="right">
        <div class="name">
            <a href="<?php echo $author_url; ?>"><?php echo ($author->user_firstname && $author->user_lastname ? $author->user_firstname . " " . $author->user_lastname : $author->user_login); ?></a>
        </div>
        <div class="location">
             <span id="user-location">
                <?php
                if(isset($location_full) && $location_full){
                    echo $location_full;
                }
                elseif(is_user_logged_in() && current_user_can( 'edit_user', $author->ID )){ ?>
                    <button class="btn btn-link author-edit-btn" data-toggle="modal" data-target="#jrrny-auhor-edit">Add your location</button>
                <?php }
                ?>
            </span><br />
            <div class="user-link"><?php echo "<a id='user-link' href='{$author->user_url}' title='{$author->user_url}'>{$author->user_url}</a>"; ?></div>
        </div>                               
        <div class="description">
            <span id="user-description">
            <?php
            if(isset($author->description) && $author->description){
                echo nl2br($author->description);
            }
            elseif(is_user_logged_in() && current_user_can( 'edit_user', $author->ID )){ ?>
                <button class="btn btn-link author-edit-btn" data-toggle="modal" data-target="#jrrny-auhor-edit">Click here to add your description</button>
           <?php }
            ?>
            </span>
        </div>
        <?php if (is_user_logged_in()) : ?>
            <div id="edit-profile_badge">
                <?php if (current_user_can('edit_user', $author->ID)) : ?>
                    <button class="btn btn-red" data-toggle="modal" data-target="#jrrny-auhor-edit">edit profile</button>
                <?php endif; ?>
                <?php if ($current_user->ID != $author->ID) : ?>
                    <?php get_template_part('inc/frontend/partpost/following-part' ,'new'); ?>  
                <?php endif; ?>
            </div>
        <?php endif ?>
    </div>
</div>
</div>
