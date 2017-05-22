<?php
global $current_user;
wp_get_current_user();
?>
<?php if (is_user_logged_in()): ?>
    <?php if(current_user_can('delete_post', get_the_id())): ?>
        <span class="meta-item meta-item-likes small">
        <a class="jrrny-delete-post"
           data-on-post="<?php echo get_the_id(); ?>"
           data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID')); ?>"
        >
            <span data-toggle="tooltip" data-container="body" data-placement="bottom" title="delete"><i class="fa fa-trash"></i></span>
        </a>
        </span>
    <?php endif; ?>
<?php endif; ?>