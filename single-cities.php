<?php
    global $current_user;
    wp_get_current_user();
    
    get_template_part("inc/frontend/single/single", 'cities');
    get_footer();
    get_template_part("inc/frontend/map/modal-map");
?>
<?php if(is_single()): ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(".hover-gplus-color.share-pop").attr('href',jQuery("#googleShare").attr('href'))
});
</script>
<meta name="description" property="og:description" content="<?php echo get_the_excerpt(); ?>" />
<a href="https://plus.google.com/share?url=<?php echo the_permalink(); ?>" style="display: none;" id="googleShare">share google</a>
<?php endif; ?>