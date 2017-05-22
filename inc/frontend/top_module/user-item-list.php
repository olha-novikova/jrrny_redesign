<div id="top-user-<?php echo $user->ID; ?>" class="item">   
    <div><p class="item-title" data-placement="bottom" title="<?php echo $user->display_name; ?>"><a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>"><?php echo $user->display_name; ?></a></p></div>
</div>