<?php
/*
Template Name: Contact
*/

get_header();
get_template_part('top');
get_template_part('title-page');

$join_image = get_post_meta($post->ID, 'join_image', true);
$join_image_attr = wp_get_attachment_image_src($join_image, 'full');
$join_image_paralax = get_post_meta($post->ID, 'join_image_paralax', true);
?>
<div id="contact" class="padding-top-30 padding-bottom-30">

    <div id="main-container" class="clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php echo apply_filters( 'the_content', $post->post_content ); ?>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
get_footer();
?>