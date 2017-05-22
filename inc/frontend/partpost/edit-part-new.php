<?php
global $current_user;
wp_get_current_user();
?>
<?php if (is_user_logged_in()): ?>
    <?php if(current_user_can('edit_post', get_the_ID() )): ?>
        <span class="meta-item meta-item-likes small">
        <a class="jrrny-edit-post btn btn-blue"
            href="<?= get_permalink(get_the_ID()) ."?action=edit"?>" 
        >
            <span>edit</span>
        </a>
        </span>
    <?php endif; ?>
<?php endif; ?>