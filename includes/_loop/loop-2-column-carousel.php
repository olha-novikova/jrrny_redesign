<?php

$id = uniqid();

if ($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
else :
    $entries_class = 'no-sidebar';
endif;
if (is_user_logged_in()):
    global $current_user;
    wp_get_current_user();
endif;
$ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;

$branded = (isset($atts['branded'])) ? true : false;
if ($branded && !$ts_query->have_posts()) {
    if (isset($atts)) {
        unset($atts['author']);
    }
    $ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
}

$atts = (isset($atts)) ? $atts : array();
?>
<div class="loop-wrap loop-3-column-wrap <?php echo esc_attr(ts_loop_wrap_class($atts)); ?>">
    <div id="carousel-<?php echo $id;?>" class="hfeed entries blog-entries loop loop-3-column <?php echo esc_attr($entries_class); ?> clearfix">
<?php
$exc_lnth = ts_option_vs_default('excerpt_length_2col_medium', 100);
$excerpt_length = (isset($atts['excerpt_length']) && $atts['excerpt_length'] != '') ? $atts['excerpt_length'] : $exc_lnth;

$ts_show = ts_maybe_show_blog_elements($atts);

$show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;

$title_size = ts_get_blog_loop_title_size($atts, 4);

$text_align = ts_get_blog_loop_text_align($atts);

$allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';

$mw = 480;
$mh = ts_loop_media_height('3column', $atts, $mw);

$target = is_page('all-jrrnys') ? ' target="_blank"' : '';

if ($ts_query->have_posts()) :
    $i = 1;
    while ($ts_query->have_posts()) :
        $ts_query->the_post();
        $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
        if (!ts_attr_is_false($atts['exclude_these_later']))
            $ts_previous_posts[] = $ts_query->post->ID;
        $post_type = get_post_type();

        $category = ts_get_the_category(ts_get_the_display_taxonomy($atts), 'big_array:1', '', $ts_query->post->ID);


        $media = ts_get_featured_media(array('media_width' => $mw, 'media_height' => $mh, 'allow_videos' => 'no', 'allow_galleries' => $allow_gals, 'target' => $target));
        $active = $i == 1 ? ' active' : '';
        ?>
                <div id="post-<?php echo esc_attr($ts_query->post->ID); ?>" class="hentry entry col-xs-12 col-sm-6 col-md-4">
                    <div class="post-content <?php plc_category_unlinked(); ?>">
                        <div class="post-category post-category-heading mimic-small uppercase <?php echo ts_loop_post_category_class($atts, $media); ?>">
                            <a href="<?php echo ts_get_term_link($category[0]); ?>">
                                <strong>
                                    <?= '*'.$category[0]['name'] ?>
                                </strong>
                            </a>
                        </div>

                        <div class="title-date clearfix">
                            <div class="title-info">
                                <h<?php echo absint($title_size->h); ?> class="title-h entry-title <?php echo esc_attr($text_align); ?>"><?php echo ts_sticky_badge(); ?><a <?php echo $target; ?> href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h<?php echo absint($title_size->h); ?>>
                                <?php if ( (!isset($atts['show_sharing_options'])) || (isset($atts['show_sharing_options'])&& $atts['show_sharing_options'] != false )) get_template_part('inc/frontend/partpost/like-part'); ?>
                                <?php
                                if ( is_user_logged_in() && get_the_author_meta('ID')== $current_user->ID ):
                                    get_template_part('inc/frontend/partpost/edit-part');
                                    get_template_part('inc/frontend/partpost/delete-part');
                                endif;
                                ?>
                            </div>
                        </div>

                        <div class="ts-meta-wrap <?php echo ($ts_show->media && trim($media)) ? 'media-meta-wrap' : 'meta-wrap'; ?>">
                            <?php if ($ts_show->media && trim($media)) : ?>
                                <?php
                                $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';
                                echo ts_escape($media);

                                ?>
                                <a href="<?php the_permalink();?>"  class="overlay-carousel-link"></a>
                            <?php endif ?>
                            <?php if (!plc_is_category(13) ) { ?>
                                <div class="entry-info post-entry-info author-name">
                                    <span class="meta-item meta-item-author subtle-text-color stylized-meta">
                                    <?php if ( (!isset($atts['show_author'])) || (isset($atts['show_author'])&& $atts['show_author'] != false )) { echo __('by:', 'ThemeStockyard') ?><span class="author vcard"> <a href="<?= esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>" class="fn"><?= get_the_author_meta('display_name') ?> </a></span><?php }?>
                                    <?php
                                    if ( get_the_author_meta('ID')!= $current_user->ID && ( (!isset($atts['show_sharing_options'])) || (isset($atts['show_sharing_options'])&& $atts['show_sharing_options'] != false)) )
                                        get_template_part('inc/frontend/partpost/following-part');
                                    ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>


        <?php
        if ($show_excerpt || $ts_show->read_more):
            ?>
            <div class="post">
                <?php
                if ($show_excerpt) :
                    ?>
                        <p class="entry-summary <?php echo esc_attr($text_align); ?>"><?php
                            $content = (has_excerpt()) ? get_the_excerpt() : apply_filters('the_content', $ts_query->post->post_content);
                            echo ts_truncate_trim($content, $excerpt_length);
                    ?>  </p>

                    <?php
                endif;

//                 if($ts_show->read_more) :
//                 ?>
<!--                      <div class="read-more-wrap --><?php //echo esc_attr($text_align);?><!--">-->
<!--                         <div class="read-more mimic-smaller uppercase"><a href="--><?php //the_permalink();?><!--" rel="bookmark"><strong>--><?php //echo __('View jrrny', 'ThemeStockyard');?><!--</strong></a></div>-->
<!--                      </div>-->
<!--                      --><?php
//                 endif;
                ?>
            </div>
        <?php
        endif;
        ?>

        </div>
    </div>

        <?php
        // echo ($i == 3) ? '<div class="clear"></div>' : '';
        $i++;
    //$i = ($i == 4) ? 1 : $i;
    endwhile;
    ?>

            <?php
            $pagination = (isset($atts['show_pagination']) && $atts['show_pagination'] === false) ? false : true;
        else :
            $pagination = false;

            echo '<p class="no-results">' . __('Sorry, nothing here!', 'ThemeStockyard') . '</p>';
        endif;
        ?>

    </div>
<?php echo ($pagination) ? ts_paginator($atts) : ''; ?>
</div>
<script>$(document).ready(function () {
        $('#carousel-<?php echo $id;?>').slick({
            dots: true,
            arrows: false,
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            adaptiveHeight: false,
            responsive: [
                {
                    breakpoint: 968,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 620,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });</script>
