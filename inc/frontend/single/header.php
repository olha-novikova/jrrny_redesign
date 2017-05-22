<?php
global $ts_page_title, $ts_show_title_bar_override, $ts_caption, $ts_page_id, $smof_data, $post;

$header_photo_id = get_post_thumbnail_id($ts_page_id);
$header_photo_src = wp_get_attachment_image_src($header_photo_id, 'medium');
$header_photo = (isset($header_photo_src[0])) ? $header_photo_src[0] : '';
$header_photo_css = ($header_photo) ? ' style="background-image:url(' . $header_photo . ');"' : '';
$header_photo_class = ($header_photo) ? '' : 'class="title-banner"';
?>
<div class="clearfix"></div>
<div id="single-header" <?php echo $header_photo_class;?>>   
    <div id="single-header-image" <?php echo $header_photo_css;?>></div>
    <div class="plc-table">
        <div class="plc-table-row">
            <div>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <?php echo plc_social_sharing(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="plc-table-row">
            <div class="plc-table-cell">
                <h1><?php echo (trim($ts_page_title)) ? esc_html($ts_page_title) : get_the_title(); ?></h1>
                <div class="container">
                    <div class="row">
                        <?php get_template_part('inc/frontend/partpost/like-part' ,'new'); ?>
                        <?php get_template_part('inc/frontend/partpost/delete-part' ,'new'); ?>
                        <?php get_template_part('inc/frontend/partpost/edit-part' ,'new'); ?>
                        <?php get_template_part('inc/frontend/partpost/comments-part' ,'new'); ?>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>