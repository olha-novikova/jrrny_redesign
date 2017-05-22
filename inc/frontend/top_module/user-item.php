<?php
$user_quota = get_user_meta($user->ID, 'quota', true);
if ($user_quota && !$quota) {
    $quota = $user_quota . '|' . $user->display_name;
}
?>
<div id="top-user-<?php echo $user->ID; ?>" class="col-xs-12 col-sm-6 col-md-4 col-lg-2 user-item">
    <div class="user-image">
        <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>"><?php echo get_avatar($user->ID, 250, '', $user->user_login); ?></a>
    </div>
    <p class="user-title" data-toggle="tooltip" data-placement="bottom" title="<?php echo $user->display_name; ?>"><?php echo $user->display_name; ?></p>
    <a class="user-website" <?php echo ($user->user_url ? 'href="' . $user->user_url.'" title="' . $user->user_url.'" target="_blank"' : ''); ?>><?php echo ($user->user_url ? $user->user_url : '&nbsp;'); ?></a>
    <p class="user-statistic">&nbsp;</p>
    <p class="user-btn"><a class="btn btn-blue btn-block btn-no-radius" href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>">view jrrnys</a></p>
</div>
