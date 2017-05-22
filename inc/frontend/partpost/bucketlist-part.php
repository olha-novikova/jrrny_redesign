<?php
global $current_user;

if (is_user_logged_in()) {
    $on_list = false;
    $bucket_list_title = 'add to bucket';
    $bucket_list_text = '<i class="fa fa-calendar-plus-o"></i>';

    if (plc_is_on_bucket(get_the_id(), $current_user->ID)) {
        $on_list = true;
        $bucket_list_text = '<i class="fa fa-calendar-minus-o"></i>';
        $bucket_list_title = 'remove from bucket';
    }
    ?>
    <span class="meta-item meta-item-likes small sp-bucket-list">
        <a class="jrrny-bucket-post"
           data-title="<?php echo get_the_title(); ?>"
           data-post="<?php echo get_the_id(); ?>"
           data-user="<?php echo $current_user->ID; ?>"
           >    
            <span class="list-text" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $bucket_list_title; ?>"><?php echo $bucket_list_text; ?></span>
        </a>
    </span>
    <?php
    if ($on_list) {
        get_template_part('inc/frontend/partpost/completed-part');
    }
}

/*
  <span class="list-text"><?php echo $bucket_list_title; ?></span>
  <span class="list-text" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $bucket_list_title;?>"><?php echo $bucket_list_text; ?></span>
 */
?>