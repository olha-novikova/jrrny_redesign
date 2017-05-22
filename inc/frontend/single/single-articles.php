<?php
global $smof_data, $ts_page_id, $ts_comments_top_padding, $ts_within_blog_loop, $ts_sidebar_position, $ts_show_sidebar, $ts_show_top_ticker,$current_user;
global $ts_previous_posts;

$ts_page_object = get_queried_object();
$ts_page_id = (is_single()) ? $post->ID : get_queried_object_id();
$ts_custom_css = get_post_meta($ts_page_id, '_p_css', true);

$ts_previous_posts[] = $ts_page_id;

$ts_show_top_ticker_option = (ts_option_vs_default('show_post_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_post_top_ticker', $ts_show_top_ticker_option);
$ts_show_sidebar_option = (ts_option_vs_default('show_post_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar = ts_postmeta_vs_default($post->ID, '_p_sidebar', $ts_show_sidebar_option);
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';
$ts_sidebar_position_option = ts_option_vs_default('post_sidebar_position', 'right');
$ts_sidebar_position = ts_postmeta_vs_default($post->ID, '_p_sidebar_position', $ts_sidebar_position_option);
$ts_direction_links_option = ts_option_vs_default('post_show_direction_links', 'yes');
$ts_direction_links = ts_postmeta_vs_default($post->ID, '_p_show_direction_links', $ts_direction_links_option);
$ts_sharing_position_option = ts_option_vs_default('sharing_options_position_on_post', 'top');
$ts_sharing_position = ts_postmeta_vs_default($post->ID, '_p_sharing_options_position', $ts_sharing_position_option);
$ts_show_featured_media_option = (ts_option_vs_default('show_images_on_post', 1) == 1) ? 'yes' : 'no';
$ts_show_featured_media = ts_postmeta_vs_default($post->ID, '_p_show_featured_image_on_single', $ts_show_featured_media_option);
$crop_images_option = (ts_option_vs_default('crop_images_on_post', 1)) ? 'yes' : 'no';
$crop_images = ts_postmeta_vs_default($post->ID, '_p_crop_images', $crop_images_option);

$ad_area_field = get_field('ad_area', $post->ID, false);
$ad_option = get_option('ad_single_page');

$ad_area = $ad_area_field ? $ad_area_field : $ad_option;

if ($ts_show_sidebar == 'yes' && (in_array($ts_sidebar_position, array('left', 'right')))) :
    if (in_array($crop_images, array('1', 'yes', 'true'))) :
        $crop_width = ts_option_vs_default('cropped_featured_image_width', 740, true);
        $crop_height = ts_option_vs_default('cropped_featured_image_height', 480, true);
    else :
        $crop_width = ts_option_vs_default('cropped_featured_image_width', 740, true);
        $crop_height = 0;
    endif;
else :
    if (in_array($crop_images, array('1', 'yes', 'true'))) :
        $crop_width = ts_option_vs_default('cropped_featured_image_width_full', 1100, true);
        $crop_height = ts_option_vs_default('cropped_featured_image_height_full', 540, true);
    else :
        $crop_width = ts_option_vs_default('cropped_featured_image_width_full', 1100, true);
        $crop_height = 0;
    endif;
endif;
if (has_post_thumbnail(get_the_id())) {
    $post_thumbnail_id = (int) get_post_thumbnail_id(get_the_id());
}

$hotel_images_ids = [];
for ($i = 1; $i <= 2; ++$i) {
    $id = get_post_meta(get_the_ID(), '_hotel_image_' . $i . '_id', true);
    if (!empty($id)) {
        if (wp_get_attachment_url($id)) {
            $hotel_images_ids[$i] = $id;
        }
    }
}
$postImageIds = [];
for ($i = 1; $i <= 12; ++$i) {
    $id = get_post_meta(get_the_ID(), '_p_image_' . $i . '_id', true);
    if (!empty($id)) {
        if (wp_get_attachment_url($id)) {
            $postImageIds[$i] = $id;
        }
    }
}
if(!$postImageIds){
    $postImageIds[1] = get_post_thumbnail_id($ts_page_id);
}
get_header();
get_template_part('top');
get_template_part('inc/frontend/single/header');
?>
<div id="single_wrapper">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            ?>

            <div class="container">
                <div id="ts-post-the-content" class="entry-content">
                    <?php
                    echo ts_display_link_post_format_url();
                    echo make_clickable(apply_filters('the_content', get_the_content()));
                    $insider_tip = get_post_meta(get_the_ID(), "_insider_tip", true);
                    ?>
                    <?php if (isset($insider_tip) && $insider_tip != '') : ?>
                        <h3>Insider Tip</h3>
                        <?= make_clickable(apply_filters('the_content', $insider_tip)); ?>
                    <?php endif; ?>
                    <?php
                    wp_link_pages('before=<div class="page-links">' . __('Pages: ', 'ThemeStockyard') . '<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>');
                    ?>
                    <?php
                    $tags_intro = '<span class="tags-label">' . __('Tags:', 'ThemeStockyard') . ' </span>';
                    $tags_sep = '&bull;';
                    if (has_tag()) :
                        ?>
                        <div class="post-tags mimic-small clearfix"><?php the_tags('', '', ''); ?></div>
                        <?php
                    endif;
                    ?>
                </div>  
            <div class="clearfix"></div>                      
            <div class="single-post-media-attachments">
                <ul id="single-images" class="images-<?php echo img_layout(count($postImageIds));?>">
                    <?php
                    foreach ($postImageIds as $key => $imgId) {
                        $img_small = wp_get_attachment_url($imgId);
                        $backgroundStyle = "background-image: url('" . $img_small . "')";
                        ?>
                        <li>
                            <a data-gallery="multiimages" data-toggle="lightbox" href="<?php echo $img_small;?>" style="<?php echo $backgroundStyle; ?>" class="single-image"></a>
                        </li>

                    <?php }
                    ?>
                </ul>
            </div>
            <div class="clearfix"></div>    
                <div class="about-hotel">
                    <h3><?php echo __('Where I stayed / started', 'ThemeStockyard'); ?></h3>
                    <div class="row row-eq-height">
                        <?php 
                        $hotel_image_class = '';
                        $hotel_array_first_key = key($hotel_images_ids);
                        if(isset($hotel_images_ids[$hotel_array_first_key])){ 
                            $hotel_image_class = ' col-sm-6';
                            $hotel_img_src = wp_get_attachment_url($hotel_images_ids[$hotel_array_first_key]);
                            ?>
                        <div class="col-xs-12 col-sm-6">
                            <a data-gallery="hotel_images" data-toggle="lightbox" href="<?php echo $hotel_img_src;?>"><img src="<?php echo $hotel_img_src; ?>" class="img img-responsive"/></a>
                        </div>
                        <?php 
                        unset($hotel_images_ids[$hotel_array_first_key]);
                        
                        } ?>
                        <div class="col-xs-12<?php echo $hotel_image_class;?>">
                            <div class="plc-table">
                                <div class="plc-table-cell">
                                    <?php $hotel_name = get_post_meta(get_the_ID(), "_hotel_name", true); ?>
                                    <p class="hotel-info"><?php echo $hotel_name; ?></p>
                                    <?php
                                    if ($hotel_link = get_post_meta(get_the_ID(), "_hotel_link", true)):
                                        // Fixing the url if http not provided
                                        if (empty(parse_url($hotel_link)["scheme"])):
                                            $http_hotel_link = "http://" . $hotel_link;
                                        else:
                                            $http_hotel_link = $hotel_link;
                                        endif;
                                        echo "<p class=\"hotel-info\">";
                                        echo "<a href='" . $http_hotel_link . "' target='_blank'>" . $hotel_link . "</a>";
                                        echo "</p>";
                                    endif; ?>   
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($hotel_images_ids){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach ($hotel_images_ids as $key => $hotel_image_id): 
                                $hotel_img_src = wp_get_attachment_url($hotel_image_id);?>
                                <p>
                                    <a data-gallery="hotel_images" data-toggle="lightbox" href="<?php echo $hotel_img_src;?>"><img src="<?= $hotel_img_src ?>"></a>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
                <?php if($ad_area){ ?>
                <div class="ads">
                       <?php echo $ad_area;?>
                </div>
                <?php }  ?>
            </div>
            <?php
            if (in_array($ts_direction_links, array('yes', 'yes_similar'))){
                //ts_post_direction_nav_v2($ts_direction_links);
            }?>
                <?php
                $show_related_option = ts_option_vs_default('show_related_blog_posts', 'yes');
                $show_related = ts_postmeta_vs_default($post->ID, '_p_related_posts', $show_related_option);
                if ($show_related == '1' || $show_related == 'yes') :
                    ?>
                    <div class="ts-related-posts-on-single ts-post-section-inner">
                        <div class="container"> 
                           <h4 class="h4"><?php echo ts_option_vs_default('related_blog_posts_title_text', 'Related Posts'); ?></h4>
                        </div>
                        <?php
                        $args = array('include' => 'related', 'limit' => 3, 'show_pagination' => 'no', 'media_width' => 480, 'media_height' => 360, 'title_size' => 4, 'allow_videos' => 0, 'allow_galleries' => 0);
                        echo ts_blog('3columns', $args);
                        ?>
                    </div>
                <?php endif; ?>
                <?php 
                $count_posts_user = count_user_posts($post->post_author);
                if ($count_posts_user > 2) :
                ?>
                    <div class="ts-related-posts-on-single ts-post-section-inner">
                        <div class="container"> 
                            <h4 class="h4">More Jrrnys by this Contributor</h4>
                        </div>
                        <?php
                        $args = array(
                            'author'        =>  $post->post_author,
                            'orderby'       =>  'rand',
                            'post_status'   =>  'publish',
                            'limit' => 3, 
                            'show_pagination' => 'no', 
                            'media_width' => 480, 
                            'media_height' => 360, 
                            'title_size' => 4, 
                            'allow_videos' => 0, 
                            'allow_galleries' => 0
                            );
                        echo ts_blog('3columns', $args);
                        ?>
                    </div>
                <?php endif; ?>
                    
            <div class="container"> 
                <div id="ts-comments-wrap-wrap" class="clearfix <?php echo esc_attr(ts_single_comments_wrap_wrap_class()); ?>">
                    <div id="ts-comments-wrap" class="<?php echo esc_attr(ts_single_comments_wrap_class()); ?>">
                        <?php
                        comments_template();
                        ?>
                    </div>
                    <?php ts_get_comments_sidebar(); ?>
                </div>
                <?php ts_get_content_sidebar(); ?>
            </div>
            <?php
        endwhile;
    else:
        ?>
        <div id="main-container" class="container clearfix">
            <div id="main" class="<?php echo esc_attr($ts_show_sidebar_class); ?> clearfix">
                <div class="entry single-entry clearfix">
                    <div class="post"><p><?php _e('Sorry, the post you are looking for does not exist.', 'ThemeStockyard'); ?></p></div>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>
    
</div><!-- #single_wrapper -->

<?php 
if(is_user_logged_in() && current_user_can('edit_user', $current_user->ID)){ 
    get_template_part('inc/frontend/profile/edit-form-single');     
}
?>

