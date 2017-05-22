<?php
if($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
else :
    $entries_class = 'no-sidebar';
endif;
global $current_user;
wp_get_current_user();

$ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
$atts = (isset($atts)) ? $atts : array();
?>

                <div class="loop-wrap loop-single loop-2-column-wrap <?php echo esc_attr(ts_loop_wrap_class($atts));?>">
                    <div class="hfeed entries blog-entries loop loop-2-column <?php echo esc_attr($entries_class);?> clearfix">
                        <?php
                        $exc_lnth = ts_option_vs_default('excerpt_length_2col_medium', 320);
                        $excerpt_length = (isset($atts['excerpt_length']) && $atts['excerpt_length'] != '') ? $atts['excerpt_length'] : $exc_lnth;

                        $ts_show = ts_maybe_show_blog_elements($atts);

                        $show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;

                        $title_size = ts_get_blog_loop_title_size($atts, 2);

                        $text_align = ts_get_blog_loop_text_align($atts);

                        $allow_vids = (isset($atts['allow_videos'])) ? $atts['allow_videos'] : 'yes';
                        $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : 'yes';

                        if($ts_query->have_posts()) :
                            $i = 1;
                            while($ts_query->have_posts()) :
                                $ts_query->the_post();
                                $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
                                if(!ts_attr_is_false($atts['exclude_these_later'])) $ts_previous_posts[] = $ts_query->post->ID;
                                $post_type = get_post_type();

                                $category   = ts_get_the_category(ts_get_the_display_taxonomy($atts), 'big_array:1', '', $ts_query->post->ID);

                                $media = ts_get_featured_media(array('allow_videos'=>$allow_vids,'allow_galleries'=>$allow_gals));
                        ?>

                        <div id="post-<?php echo esc_attr($ts_query->post->ID);?>" class="hentry entry">
                            <div class="post-content">
                                <div class="post-category post-category-heading mimic-small uppercase <?php echo ts_loop_post_category_class($atts, $media);?>">
                                    <a href="<?php echo ts_get_term_link($category[0]);?>"><strong><?php echo esc_html($category[0]['name']);?></strong></a>
                                </div>
                                <div class="ts-meta-wrap <?php echo ($ts_show->media && trim($media)) ? 'media-meta-wrap' : 'meta-wrap';?>">
                                    <?php
                                    if($ts_show->media && trim($media)) :
                                        $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';
                                        echo ts_escape($media);

                                    endif; ?>
                                    
                                    
                                            <div class="entry-info post-entry-info">
                                            <p class="small-size clearfix <?php echo esc_attr($text_align);?>">   
                                                    <?php get_template_part('inc/frontend/partpost/like-part'); ?>
                                                    <?php get_template_part('inc/frontend/partpost/bucketlist-part'); ?>
                                                    <?php get_template_part('inc/frontend/partpost/delete-part'); ?>
                                                    <?php get_template_part('inc/frontend/partpost/edit-part'); ?>
                                                </p></div>
                                    
                                    <?php
                                    if($ts_show->meta) :
                                        if($post_type == 'portfolio') :
                                    ?>
                                    <div class="entry-info portfolio-entry-info"><p class="small-size <?php echo esc_attr($text_align);?>"><?php echo ts_get_the_category('portfolio-category','text');?></p></div>
                                    <?php
                                        elseif($post_type == 'page') :
                                    ?>
                                    <div class="entry-info page-entry-info"><p class="small-size <?php echo esc_attr($text_align);?>"><?php _e('Page last modified:', 'ThemeStockyard');?> <?php the_modified_date();?></p></div>
                                    <?php
                                        elseif($post_type == 'post') :
                                    ?>
                                    <div class="entry-info post-entry-info"><p class="small-size <?php echo esc_attr($text_align);?> clearfix"><?php
                                            // author link
                                            echo '<span class="meta-item meta-item-author subtle-text-color stylized-meta">';
                                            echo __('By', 'ThemeStockyard').' ';
                                            echo '<span class="author vcard">';
                                            echo '<a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" class="fn">';
                                            echo get_the_author_meta('display_name').'</a></span>';
                                            echo '</span>';


                                            echo '<span class="meta-item meta-item-date published small uppercase" title="'.get_the_date('Y-m-d\TH:i:s').'">'.get_the_date('M j').'</span>';
                                            echo '<span class="updated hidden" title="'.get_the_modified_date('Y-m-d\TH:i:s').'">'.get_the_modified_date('M j').'</span>';
                                            if(comments_open()) :
                                                $comment_number = get_comments_number();
                                                echo '<span class="meta-item meta-item-comments small uppercase">';
                                                echo '<a href="'.ts_link2comments(get_permalink()).'">';
                                                echo '<i class="fa fa-comments"></i>';
                                                echo '<span class="disqus-comment-count" data-disqus-url="'.get_permalink().'">';
                                                echo esc_html($comment_number);
                                                echo '</span>';
                                                echo '</a>';
                                                echo '</span>';
                                            endif;
                                            
                                            get_template_part('inc/frontend/partpost/like-part');
                                            get_template_part('inc/frontend/partpost/following_span-part');
                                            get_template_part('inc/frontend/partpost/delete-part'); 
                                            get_template_part('inc/frontend/partpost/edit-part'); 
                                            /*
                                            echo '<span class="meta-item meta-item-likes small">';
                                            ?> <a class="meta-item-like <?php if(is_user_logged_in()): is_liked(); endif;?>" data-on-post="<?php echo get_the_id();?>" data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID'));?>"> <?php
                                            echo '<span>Add to My Jrrnys</span> ';
                                            ?> <?php $likes = get_post_meta( get_the_id(), "likes", true );?>
			                                <span class="likes-quant "><?php echo ($likes ? strval($likes["quantity"]) : "0"); ?></span> <?php
                                            echo '</a>';
                                            echo '</span>';
                                             * 
                                             */
                                    ?></p></div>
                                    <?php
                                        endif;
                                    endif;
                                    ?>
                                </div>
                                <?php if(is_page_template('my-jrrnys.php') && (int)get_the_author_meta('ID') == $current_user->ID):?>
                                <div class="post-edit-delete">
                                    <button data-href="<?php echo get_the_permalink().'?action=edit&user='.$current_user->user_login;?>" class="btn btn-warning action-post action-edit">Edit <i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger action-post action-delete" data-toggle="popover">Delete <i class="fa fa-trash-o trash-icon"></i></button>
                                    <input type="hidden" name="post-id" value="<?php echo get_the_id();?>" data-for="post-delete" />
                                </div>
                                <?php endif;?>
                                <div class="title-date clearfix">
                                    <div class="title-info">
                                        <h<?php echo absint($title_size->h);?> class="title-h entry-title <?php echo esc_attr($text_align);?>"><?php echo ts_sticky_badge();?><a href="<?php the_permalink();?>" ><?php the_title();?></a></h<?php echo absint($title_size->h);?>>
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

                                    /*if($ts_show->read_more) :
                                    ?>
                                    <div class="read-more-wrap <?php echo esc_attr($text_align);?>">
                                        <div class="read-more mimic-smaller uppercase"><a href="<?php the_permalink();?>" rel="bookmark"><strong><?php echo __('View jrrny', 'ThemeStockyard');?></strong></a></div>
                                    </div>

                                    <?php
                                    endif; */
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
                            //echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';
                            if(is_search ()){
                                echo '<p class="no-results">'.__("Sorry, there's no posts for that yet -- Why don't you add the first one?", "ThemeStockyard").'</p>';
                                echo '<a href="'.home_url().'/upload'.'" class="btn btn-primary search-create-jrrny">Create Jrrny</a> - Or check out these posts'.'<br><br>';
                                echo do_shortcode ('[blog layout="3-column-grid" limit="12" infinite_scroll="no" infinite_scroll_limit="0" infinite_scroll_button_text="Load more posts" cat="7" include="" exclude="" exclude_previous_posts="yes" exclude_these_later="yes" excerpt_length="" title_size="" text_align="" show_pagination="no" show_category="yes" show_media="yes" show_excerpt="yes" show_meta="yes" show_read_more="yes" image_orientation="" allow_videos="yes" allow_galleries="yes" ignore_sticky_posts="0"][/blog]');
                            }
                            else{echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';}
                        endif;
                        ?>

                    </div>
                    <?php echo ($pagination) ? ts_paginator($atts) : ''; ?>
                </div>
