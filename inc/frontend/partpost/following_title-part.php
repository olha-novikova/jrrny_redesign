<?php
$author_id = get_the_author_meta('ID', $post->post_author);

$following = '0';
$following_class = '';
$following_text = 'follow';

if (is_follow($author_id)) {
    $following = '1';
    $following_class = 'disabled';
    $following_text = 'following';
}
$following_class .= (is_user_logged_in()) ? ' ' : ' login_modal';
?>

<a href="#"
   data-author="<?php echo $author_id; ?>"
   data-following="<?php echo $following; ?>"
   class="follow-title meta-item-follow <?php echo $following_class; ?>">
    <?php echo $following_text; ?>
</a>
