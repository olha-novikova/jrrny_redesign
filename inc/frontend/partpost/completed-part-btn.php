<?php
global $current_user;

$completed = '0';
$completed_title = 'completed';
$completed_icon = '<i class="fa fa-square-o"></i>';

if (plc_is_bucket_completed(get_the_id(), $current_user->ID)) {
    $completed = '1';
   // $completed_title = 'remove from completed';
    $completed_icon = '<i class="fa fa-check-square-o"></i>';
}
?>
<span class="meta-item meta-item-likes small sp-bucket-completed-post">
    <a class="jrrny-bucket-completed-post btn btn-transparent-white"
       data-post="<?php echo get_the_id(); ?>"
       data-user="<?php echo $current_user->ID; ?>"
           data-txt="true"
       >
        <span class="list-text"><?php echo $completed_title;?></span><?php echo $completed_icon; ?>
    </a>
</span>