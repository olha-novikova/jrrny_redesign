<?php
$postID = get_the_ID();
$place_id = get_post_meta($postID, '_place_id', true);

if($place_id){
    $place = getMapPlace($place_id);
?>
<script>
    var smallMapCenter = [<?php echo $place->lat.','.$place->lon; ?>];
    var placeId = ' <?php echo $place->id; ?>';
    var icon = '<?php echo get_stylesheet_directory_uri();?>/images/marker20x30.png';
</script>
<div id="small-map"></div>
<?php }