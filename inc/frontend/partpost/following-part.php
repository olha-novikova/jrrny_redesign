<?php

$following = '0';
$following_class = '';
$following_text = 'follow';

if (is_follow(get_the_author_meta('ID'))) {
    $following = '1';
    $following_text = 'unfollow';
}
$following_class .= (is_user_logged_in()) ? ' ' : ' login_modal';

?>

<a href="#"  data-author="<?php echo get_the_author_meta('ID'); ?>" data-following="<?php echo $following; ?>"  class="btn btn-follow-loop meta-item-follow">
    <?php echo $following_text; ?>
</a>

