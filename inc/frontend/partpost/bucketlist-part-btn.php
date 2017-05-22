<?php
global $current_user;

if (is_user_logged_in()) {
    $on_list = false;
    $bucket_list_title = 'Bucket List';
    $bucket_list_text = '<i class="fa fa-square-o"></i>';

    if (plc_is_on_bucket(get_the_id(), $current_user->ID)) {
        $on_list = true;
        $bucket_list_text = '<i class="fa fa-check-square-o"></i>';
        //$bucket_list_title = 'remove from bucket';
    }
    ?>
    <span class="meta-item meta-item-likes small sp-bucket-list">
        <a class="jrrny-bucket-post btn btn-blue"
           data-title="<?php echo get_the_title(); ?>"
           data-post="<?php echo get_the_id(); ?>"
           data-user="<?php echo $current_user->ID; ?>"
           data-txt="true"
           >    
            <span class="list-text"><?php echo $bucket_list_title; ?></span><?php echo $bucket_list_text; ?>
        </a>
    </span>
    <?php
    if ($on_list) {
        get_template_part('inc/frontend/partpost/completed-part', 'btn');
    }
}

