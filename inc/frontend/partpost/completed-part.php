<?php
global $current_user;

$completed = '0';
$completed_title = 'add to completed';
$completed_icon = '<i class="fa fa-toggle-off"></i>';

if (plc_is_bucket_completed(get_the_id(), $current_user->ID)) {
    $completed = '1';
    $completed_title = 'remove from completed';
    $completed_icon = '<i class="fa fa-toggle-on"></i>';
}
?>
<span class="meta-item meta-item-likes small sp-bucket-completed-post">
    <a class="jrrny-bucket-completed-post"
       data-post="<?php echo get_the_id(); ?>"
       data-user="<?php echo $current_user->ID; ?>"
       >
        <span class="list-text" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $completed_title;?>"><?php echo $completed_icon; ?></span>
    </a>
</span>
<?php
/*
  <span class="list-text" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $bucket_list_title;?>"><?php echo $bucket_list_text; ?></span>
 */?>