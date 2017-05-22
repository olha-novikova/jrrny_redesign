<?php
$entries_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';

$ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
$atts = (isset($atts)) ? $atts : array();

$entry_class = 'span4';
?>
                <div class="loop-wrap loop-masonry-wrap <?php echo esc_attr(ts_loop_wrap_class($atts));?>">
                    <div class="hfeed entries blog-entries loop loop-masonry <?php echo esc_attr($entries_class);?> clearfix">
                        <?php
                        $exc_lnth = ts_option_vs_default('excerpt_length_minimum', 100);
                        $excerpt_length = (isset($atts['excerpt_length']) && $atts['excerpt_length'] != '') ? $atts['excerpt_length'] : $exc_lnth;

                        $ts_show = ts_maybe_show_blog_elements($atts);

                        $show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;

                        $title_size = ts_get_blog_loop_title_size($atts, 4);

                        $text_align = ts_get_blog_loop_text_align($atts);
                        $allow_vids = (isset($atts['allow_videos'])) ? $atts['allow_videos'] : '';
                        $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';

                        if($ts_query->have_posts()) :
                            while($ts_query->have_posts()) :
                                $ts_query->the_post();
                                $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
                                if(!ts_attr_is_false($atts['exclude_these_later'])) $ts_previous_posts[] = $ts_query->post->ID;
                                $post_type = get_post_type();

                                $category   = ts_get_the_category(ts_get_the_display_taxonomy($atts), 'big_array:1', '', $ts_query->post->ID);

                                $media = ts_get_featured_media(array('media_width'=>480,'media_height'=>0,'gallery_width'=>480,'gallery_height'=>320,'allow_videos'=>$allow_vids,'allow_galleries'=>$allow_gals));
                        ?>

                        <div id="post-<?php echo esc_attr($ts_query->post->ID);?>" class="hentry entry masonry-entry <?php echo esc_attr($entry_class);?>">
                            <div class="post-content">
                                <div class="post-category post-category-heading mimic-small uppercase <?php echo ts_loop_post_category_class($atts, $media);?>">
                                    <a href="<?php echo ts_get_term_link($category[0]);?>"><strong><?php echo esc_html($category[0]['name']);?></strong></a>
                                </div>
                                <div class="ts-meta-wrap <?php echo ($ts_show->media && trim($media)) ? 'media-meta-wrap' : 'meta-wrap';?>">
                                    <?php
                                    if($ts_show->media && trim($media)) :
                                        $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';
                                        echo ts_escape($media);

                                    endif;

                                    if($ts_show->meta) :
                                        if($post_type == 'post') :
                                    ?>
                                    <div class="entry-info entry-info-post"><p class="small-size clearfix <?php echo esc_attr($text_align);?>"><?php
                                            // author link
                                            echo '<span class="meta-item meta-item-author author vcard stylized-meta">';
                                            echo '<a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" class="fn">';
                                            echo get_the_author_meta('display_name').'</a></span>';


                                            echo '<span class="meta-item meta-item-date published uppercase" title="'.get_the_date('Y-m-d\TH:i:s').'">'.get_the_date('M j').'</span>';
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
                                    ?></p></div>
                                    <?php
                                        endif;
                                    endif;
                                    ?>
                                </div>
                                <div class="title-date clearfix">
                                    <div class="title-info">
                                        <h<?php echo absint($title_size->h).' '.$title_size->style;?> class="title-h entry-title <?php echo esc_attr($text_align);?>"><?php echo ts_sticky_badge();?><a href="<?php the_permalink();?>" onclick="trackOutboundLink('<?php the_permalink();?>'); return false;"><?php the_title();?></a></h<?php echo esc_attr($title_size->h);?>>
                                    </div>
                                </div>

                                <?php
                                if($show_excerpt || $ts_show->read_more) :
                                ?>
                                <div class="post">
                                    <?php
                                    if($show_excerpt) :
                                    ?>

                                    <p class="entry-summary <?php echo esc_attr($text_align);?>"><?php
                                    $content = (has_excerpt()) ? get_the_excerpt() : apply_filters('the_content', $ts_query->post->post_content);
                                    echo ts_truncate_trim($content, $excerpt_length);
                                    ?></p>

                                    <?php
                                    endif;

                                    if($ts_show->read_more) :
                                    ?>
                                    <div class="read-more-wrap <?php echo esc_attr($text_align);?>">
                                        <div class="read-more mimic-smaller uppercase"><a href="<?php the_permalink();?>" onclick="trackOutboundLink('<?php the_permalink();?>'); return false;" rel="bookmark"><strong><?php echo __('Read more', 'ThemeStockyard');?></strong></a></div>
                                    </div>

                                    <?php
                                    endif;
                                    ?>
                                </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        </div>

                        <?php
                            endwhile;

                            $pagination = (isset($atts['show_pagination']) && $atts['show_pagination'] === false) ? false : true;
                        else :
                            $pagination = false;
                            echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';
                        endif;
                        ?>

                    </div>
                    <?php
                    echo ($pagination) ? ts_paginator($atts) : '';
                    ?>
                </div>
