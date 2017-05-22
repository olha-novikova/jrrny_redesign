<?php
global $current_user;
wp_get_current_user();
?>
<?php if (is_user_logged_in()): ?>
    <?php if(current_user_can('edit_post', get_the_ID() )): ?>

        <a class="jrrny-edit-post btn btn-blue"
            href="<?= get_permalink(get_the_ID()) ."?action=edit"?>" 
        >
            <span data-toggle="tooltip" data-container="body" data-placement="bottom" title="edit">edit</span>
        </a>

    <?php endif; ?>
<?php endif; ?>