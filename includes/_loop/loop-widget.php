<?php
$ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
$atts = (isset($atts)) ? $atts : array();

$widget_layout = (isset($atts['widget_layout']) && in_array($atts['widget_layout'], array('vertical','horizontal'))) ? $atts['widget_layout'] : '';
if($widget_layout == 'horizontal') :
    $media_class = 'span4';
    $row_class = 'ts-row';
else :
    $media_class = '';
    $row_class = '';
endif;

/* widget heading */
$widget_heading = (isset($atts['widget_heading'])) ? $atts['widget_heading'] : '';
$widget_heading_size = (isset($atts['widget_heading_size'])) ? $atts['widget_heading_size'] : '';
$widget_heading_size = ts_figure_h_size($widget_heading_size, 5);

if(isset($atts['override_widget_heading']) && ts_attr_is_true($atts['override_widget_heading'])) :
    if(isset($atts['category_name']) && trim($atts['category_name'])) :
        $cat_name = $atts['category_name'];
        $cat_names = explode(',', $cat_name);
        if(count($cat_names) == 1) :
            $cat = get_category_by_slug($cat_name);
            if($cat !== false) :
                $widget_heading = '<a href="'.get_category_link( $cat->term_id ).'" class="color-primary">'.esc_html($cat->name).'<i class="fa fa-angle-right"></i></a>';
            endif;
        endif;
    elseif(isset($atts['cat']) && trim($atts['cat'])) :
        $cat = $atts['cat'];
        $cats = explode(',', $cat);
        if(count($cats) == 1) :
            $cat = get_term_by('id', $cat, ts_get_the_display_taxonomy($atts));
            if($cat !== false) :
                $widget_heading = '<a href="'.ts_get_term_link( $cat ).'" class="color-primary">'.esc_html($cat->name).'<i class="fa fa-angle-right"></i></a>';
            endif;
        endif;
    endif;
endif;

$text_align = ts_get_blog_loop_text_align($atts);
?>

                <div class="loop-widget-wrap loop-widget-simple-wrap <?php echo esc_attr(ts_loop_wrap_class($atts));?>">
                    <?php
                    if(trim($widget_heading)) :
                    ?>
                    <h5 class="<?php echo esc_attr($text_align);?> uppercase mimic-small bold ts-widget-heading"><?php echo strip_tags($widget_heading, '<a>');?></h5>
                    <?php echo do_shortcode('[divider style="line" padding_bottom="15" padding_top="0"]');?>
                    <?php
                    endif
                    ?>

                    <div class="loop hfeed entries blog-entries loop-widget loop-widget-simple">
                        <?php
                        $exc_lnth = 80;
                        $excerpt_length = (isset($atts['excerpt_length']) && $atts['excerpt_length'] != '') ? $atts['excerpt_length'] : $exc_lnth;

                        $ts_show = ts_maybe_show_blog_widget_elements($atts);

                        $show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;

                        $title_size = ts_get_blog_loop_title_size($atts, 4);

                        $text_align = ts_get_blog_loop_text_align($atts);
                        $allow_vids = (isset($atts['allow_videos'])) ? $atts['allow_videos'] : '';
                        $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';

                        if($ts_query->have_posts()) :
                            $i = 1;
                            while($ts_query->have_posts()) :
                                $ts_query->the_post();
                                $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
                                if(!ts_attr_is_false($atts['exclude_these_later'])) $ts_previous_posts[] = $ts_query->post->ID;
                                $post_type = get_post_type();

                                $meta_hidden = ($ts_show->meta) ? '' : 'hidden';

                                $media = ts_get_featured_media(array('media_width'=>400,'media_height'=>267,'allow_videos'=>$allow_vids,'allow_galleries'=>$allow_gals, 'wrap_class'=>$media_class));
                        ?>
                        <div id="post-<?php the_ID();?>" class="hentry entry clearfix container">
                            <div class="post-content <?php echo esc_attr($row_class);?>">
                                <?php
                                if($ts_show->media || $ts_show->meta) :
                                ?>
                                <div class="ts-meta-wrap <?php echo ($ts_show->media && trim($media) && !($ts_show->featured_first && $i > 1)) ? 'media-meta-wrap' : 'meta-wrap';?>">
                                    <?php
                                    if($ts_show->media) :
                                        if($ts_show->featured_first) :
                                            echo ($i == 1) ? balanceTags($media) : ''; // sanitized more extensively with 'ts_get_featured_media' function
                                        else :
                                            echo balanceTags($media); // sanitized more extensively with 'ts_get_featured_media' function
                                        endif;
                                    endif;
                                    ?>
                                    <div class="entry-info smaller uppercase clearfix <?php echo esc_attr($meta_hidden.' '.$text_align);?>"><?php
                                            // author link
                                            echo '<span class="hidden author vcard">';
                                            echo '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" class="color-white fn">';
                                            echo get_the_author_meta('display_name').'</a></span>';

                                            echo '<span class="meta-item meta-item-date published" title="'.get_the_date('Y-m-d\TH:i:s').'">'.get_the_date('M j').'</span>';
                                            echo '<span class="updated hidden" title="'.get_the_modified_date('Y-m-d\TH:i:s').'">'.get_the_modified_date('M j').'</span>';
                                            if(comments_open()) :
                                                $comment_number = get_comments_number();
                                                echo '<span class="meta-item meta-item-comments">';
                                                echo '<a href="'.ts_link2comments(get_permalink()).'">';
                                                echo '<i class="fa fa-comments"></i>';
                                                echo '<span class="disqus-comment-count" data-disqus-url="'.get_permalink().'">';
                                                echo esc_html($comment_number);
                                                echo '</span>';
                                                echo '</a>';
                                                echo '</span>';
                                            endif;

                                            echo '<span class="meta-item meta-item-likes small">';
                                            ?> <a class="meta-item-like <?php if(is_user_logged_in()): is_liked(); endif;?>" data-on-post="<?php echo get_the_id();?>" data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID'));?>"> <?php
                                            echo '<span>Add to My Jrrnys</span> ';
                                            ?> <?php $likes = get_post_meta( get_the_id(), "likes", true );?>
			                                <span class="likes-quant "><?php echo ($likes ? strval($likes["quantity"]) : "0"); ?></span> <?php
                                            echo '</a>';
                                            echo '</span>';
                                    ?></div>
                                </div>
                                <?php
                                endif;
                                ?>
                                <div class="title-post-wrap <?php echo ($media_class == 'span4' && trim($media)) ? 'span8' : '';?>">
                                    <div class="title-date clearfix">
                                        <div class="title-info">
                                            <h<?php echo absint($title_size->h).' '.$title_size->style;?> class="title-h entry-title <?php echo esc_attr($text_align);?>"><?php echo ts_sticky_badge();?><a href="<?php the_permalink();?>" ><?php the_title();?></a></h<?php echo absint($title_size->h);?>>
                                        </div>
                                    </div>

                                    <?php
                                    if($ts_show->first_excerpt) :
                                        $show_excerpt = ($i == 1) ? $show_excerpt : false;
                                    endif;

                                    if($show_excerpt) :
                                    ?>
                                    <div class="post">
                                        <p class="entry-summary <?php echo esc_attr($text_align);?>"><?php
                                        $content = (has_excerpt()) ? get_the_excerpt() : apply_filters('the_content', $ts_query->post->post_content);
                                        echo ts_truncate_trim($content, $excerpt_length);
                                        ?></p>
                                    </div>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                                $i++;
                            endwhile;
                        endif;
                        ?>
                    </div>
                </div>
