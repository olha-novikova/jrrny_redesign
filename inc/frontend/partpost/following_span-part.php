<span class="meta-item meta-item-likes small <?php (is_user_logged_in()) ? is_liked() : '' ?>">
<?php

$following = '0';
$following_class = '';
$following_text = 'follow';

if (is_follow(get_the_author_meta('ID'))) {
    $following = '1';
    $following_class = 'disabled';
    $following_text = 'following';
}
$following_class .= (is_user_logged_in()) ? ' ' : ' login_modal';
?>

    <a href="#"
       data-author="<?php echo get_the_author_meta('ID'); ?>"
       data-following="<?php echo $following; ?>"
       class="meta-item-follow <?php echo $following_class; ?>">
        <?php echo $following_text; ?>
    </a>
</span>

            
           