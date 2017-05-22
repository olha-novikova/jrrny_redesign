<?php
if($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
    $imagew = 510;
    $imageh = 360;
else :
    $entries_class = 'no-sidebar';
    $imagew = 720;
    $imageh = 420;
endif;

$ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
$atts = (isset($atts)) ? $atts : array();
$ts_query->set('post_type', 'any');
?>
                <div class="loop-wrap loop-banner-wrap <?php echo esc_attr(ts_loop_wrap_class($atts));?>">
                    <div class="hfeed entries blog-entries loop loop-banner clearfix <?php echo esc_attr($entries_class);?>">
                        <?php             
                        $exc_lnth = ts_option_vs_default('excerpt_length_banner', 60);
                        $excerpt_length = (isset($atts['excerpt_length']) && $atts['excerpt_length'] != '') ? $atts['excerpt_length'] : $exc_lnth;
                        
                        $ts_show = ts_maybe_show_blog_elements($atts);
                        
                        $show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;
                        
                        $title_size = ts_get_blog_loop_title_size($atts, 3);
                        
                        $text_align = ts_get_blog_loop_text_align($atts);

                        if($ts_query->have_posts()) : 
                            $i = 1;
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $limit = (isset($atts['limit'])) ? $atts['limit'] : get_option('posts_per_page');

                            while($ts_query->have_posts()) :
                                $ts_query->the_post();
                                $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
                                if(!ts_attr_is_false($atts['exclude_these_later'])) $ts_previous_posts[] = $ts_query->post->ID;
                                $post_type = get_post_type();
                                $media = ts_get_featured_media(array('allow_videos'=>'no','allow_galleries'=>'no', 'media_width'=>$imagew, 'media_height'=>$imageh));
                                $media_class = (trim($media)) ? 'has-media' : 'has-no-media';
                                
                                $category   = ts_get_the_category(ts_get_the_display_taxonomy($atts), 'big_array:1', '', $ts_query->post->ID);
                        ?>                        
                        <div id="post-<?php the_ID();?>" class="hentry entry clearfix span6">
                            <div class="post-content">
                                <?php
                                if($ts_show->media) :
                                    echo balanceTags($media); // sanitized more extensively in /includes/theme-functions.php
                                endif;
                                ?>
                                <div class="all-info">

                                    <h<?php echo absint($title_size->h).' '.$title_size->style;?> class="title-h entry-title <?php echo esc_attr($text_align);?>"><?php echo ts_sticky_badge();?><a href="<?php the_permalink();?>"  class="color-white"><?php the_title();?></a></h<?php echo absint($title_size->h);?>>
                                    <p class="category mimic-smaller uppercase <?php echo esc_attr($text_align);?> meta-item meta-item-category">
                                        <a href="<?php echo ts_get_term_link($category[0]);?>" class="color-white"><strong>*<?php echo esc_html($category[0]['name']);?></strong></a>
                                    </p>
                                    <?php
                                    $content = (has_excerpt()) ? get_the_excerpt() : apply_filters('the_content', $ts_query->post->post_content);
                                    $caption = get_post_meta($ts_query->post->ID, '_p_titlebar_caption', true);
                                    $content = (trim($caption)) ? $caption : $content;
                                    ?>
                                    <?php
                                    if ( $show_excerpt ):
                                        if ($show_excerpt) :
                                            ?>
                                            <p class="entry-summary color-white <?php echo esc_attr($text_align);?>"><?php echo ts_truncate_trim($content, $excerpt_length);?></p>
                                        <?php
                                        endif;
                                    endif;
                                    ?>



                                    <p class="time color-white hidden"><span class="meta-item meta-item-date published" title="<?php echo get_the_date('Y-m-d\TH:i:s');?>"><?php echo get_the_date('F j');?></span></p>
                                    <p class="hidden"><span class="meta-item meta-item-date updated" title="<?php echo get_the_modified_date('Y-m-d\TH:i:s');?>"><?php the_modified_date('F j, Y');?></span></p>
                                    <span class="meta-item meta-item-author subtle-text-color stylized-meta">
                                    <?php if ( (!isset($atts['show_author'])) || (isset($atts['show_author'])&& $atts['show_author'] != false )) { echo __('by:', 'ThemeStockyard') ?><span class="author vcard"> <a href="<?= esc_url(get_author_posts_url(get_the_author_meta('ID'))) ?>" class="fn"><?= get_the_author_meta('display_name') ?> </a></span><?php }?>
                                        <?php
                                        if ( get_the_author_meta('ID')!= $current_user->ID && ( (!isset($atts['show_sharing_options'])) || (isset($atts['show_sharing_options'])&& $atts['show_sharing_options'] != false)) )
                                            get_template_part('inc/frontend/partpost/following-part');
                                        ?>
                                    </span>
                                    <p class="upvote">
                                        <?php get_template_part('inc/frontend/partpost/like-part'); ?>

                                        <?php
                                        if ( is_user_logged_in() && get_the_author_meta('ID')== $current_user->ID ):
                                            get_template_part('inc/frontend/partpost/edit-part');
                                            get_template_part('inc/frontend/partpost/delete-part');
                                        endif;
                                        ?>
                                    </p>
                                    <div><a href="<?php the_permalink();?>"  class="overlay-link"></a></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                                echo ($i == 2) ? '<div class="clear"></div>' : '';
                                $i++;
                                $i = ($i == 3) ? 1 : 2;
                            endwhile;
                            
                            $pagination = (isset($atts['show_pagination']) && $atts['show_pagination'] === false) ? false : true;
                        else :
                            $pagination = false;
                            echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';
                        endif;
                        ?>                        
                    </div>
                    <?php echo ($pagination) ? ts_paginator($atts) : '';?>
                </div>
