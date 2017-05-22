<?php
global $current_user;
wp_get_current_user();
?>
<?php if (is_user_logged_in()): ?>
    <?php if(current_user_can('delete_post', get_the_id())): ?>
        <span class="meta-item meta-item-likes small">
        <a class="jrrny-delete-post btn btn-blue"
           data-on-post="<?php echo get_the_id(); ?>"
           data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID')); ?>"
        >
            <span>delete</span>
        </a>
        </span>
    <?php endif; ?>
<?php endif; ?>