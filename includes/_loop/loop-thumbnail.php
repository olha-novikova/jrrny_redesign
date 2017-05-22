<?php
global $excerpt_length;
if($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
else :
    $entries_class = 'no-sidebar';
endif;
if(isset($atts) && isset($atts['custom_pagination']) && $atts['custom_pagination'] === true){            
    $atts['paged'] = 1;
}
if(isset($atts) && isset($atts['custom_query']) && $atts['custom_query'] === true){
    $ts_query ='';
}
else{
    $ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
}
$count = $ts_query->found_posts;
$atts = (isset($atts)) ? $atts : array();
$uid = 'pagination'.uniqid();
?>
                <div class="loop-wrap loop-legacy-wrap loop-thumbnail <?php echo esc_attr(ts_loop_wrap_class($atts));?>">
                    <div class="hfeed entries blog-entries loop-legacy loop <?php echo esc_attr($entries_class);?> <?php echo $uid;?>">
                        <?php
                        $ts_show = ts_maybe_show_blog_elements($atts);

                        $show_excerpt = ($excerpt_length == '0' || !$ts_show->excerpt) ? false : true;

                        $title_size = ts_get_blog_loop_title_size($atts, 2);

                        $text_align = ts_get_blog_loop_text_align($atts);

                        if($ts_query->have_posts()) :
                            while($ts_query->have_posts()) :
                                $ts_query->the_post();
                                $atts['exclude_these_later'] = (isset($atts['exclude_these_later'])) ? $atts['exclude_these_later'] : '';
                                if(!ts_attr_is_false($atts['exclude_these_later'])) $ts_previous_posts[] = $ts_query->post->ID;
                                $post_type = get_post_type();
                        ?>
                        <div id="post-<?php echo esc_attr($ts_query->post->ID);?>" class="hentry entry clearfix">
                            <div class="post-content">
	                            <h<?php echo absint($title_size->h).' '.$title_size->style;?> class="title-h entry-title <?php echo esc_attr($text_align);?>"><?php echo ts_sticky_badge();?><a href="<?php the_permalink();?>" ><?php the_title();?></a></h<?php echo absint($title_size->h);?>>
                                <div class="row">
                                <?php
                                $m_class = '';
                                if($ts_show->media) :
                                    $m_class = ' col-sm-7';
                                    $allow_vids = (isset($atts['allow_videos'])) ? $atts['allow_videos'] : '';
                                    $allow_gals = (isset($atts['allow_galleries'])) ? $atts['allow_galleries'] : '';
                                    echo '<div class="col-xs-12 col-sm-5">';
                                    echo ts_get_featured_media(array('allow_videos'=>$allow_vids,'allow_galleries'=>$allow_gals));
                                    echo '</div>';
                                endif;
                                ?>
                                <div class="col-xs-12 <?php echo $m_class;?>">
                                <div class="title-date clearfix">
                                    <div class="title-info">

                                        <?php
                                        if($ts_show->meta) :
                                            if($post_type == 'portfolio') :

                                        ?>
                                        <div class="entry-info entry-info-portfolio"><p class="smaller uppercase <?php echo esc_attr($text_align);?>"><?php echo ts_get_the_category('portfolio-category','text');?></p></div>
                                        <?php
                                            elseif($post_type == 'page') :
                                        ?>
                                        <div class="entry-info entry-info-page"><p class="smaller uppercase <?php echo esc_attr($text_align);?>"><?php _e('Page last modified:', 'ThemeStockyard');?> <?php the_modified_date();?></p></div>
                                        <?php
                                            elseif($post_type == 'post' || $post_type == 'sponsored_post' || $post_type == 'featured_destination') :
                                        ?>
                                        <div class="entry-info entry-info-post"><p class="smaller uppercase <?php echo esc_attr($text_align);?>"><?php
                                                // author link
                                                echo '<span class="meta-item meta-item-author author vcard">';
                                                echo '<a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" class="fn">';
                                                echo get_the_author_meta('display_name').'</a></span>';
		                                        echo " ";


                                                echo '<span class="meta-item meta-item-date published" title="'.get_the_date('Y-m-d\TH:i:s').'">'.get_the_date('F j').'</span>';
		                                        echo " ";
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
                                                ?>
                                                <?php get_template_part('inc/frontend/partpost/like-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/bucketlist-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/delete-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/edit-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/following_span-part'); ?>
                                                <br/>

                                        <span class="meta-item meta-item-category"><?php the_category(', ');?></span></p></div>
                                        <?php
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                </div>

                                <?php
                                if($show_excerpt || $ts_show->read_more) :
                                ?>
                                <div class="post">
                                    <?php
                                    if($show_excerpt) :
                                        echo '<div class="entry-content">';
                                        the_excerpt();
                                        echo '</div>';
                                    endif;

                                    if($ts_show->read_more) :
                                    ?>
                                    <div class="read-more-wrap <?php echo esc_attr($text_align);?>">
                                        <div class="read-more mimic-smaller uppercase"><div class="wpautop-fix"><a href="<?php the_permalink();?>"  rel="bookmark"><?php echo __('Read more', 'ThemeStockyard');?></a></div></div>
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
                            </div>
                        </div>

                        <?php
                            endwhile;
/*
                            if(isset($atts['show_pagination']) && $atts['show_pagination'] === false) :
                                echo '<div class="divider-shortcode divider"></div>';
                                echo '<div class="pagination-wrap"><p class="pagination">';
                                next_posts_link( __('Older Entries', 'ThemeStockyard'), $ts_query->max_num_pages );
                                previous_posts_link( __('Newer Entries', 'ThemeStockyard') );
                                echo '</p></div>';
                                echo '</div>';
                            endif;
                            */
                            $pagination = (isset($atts['show_pagination']) && $atts['show_pagination'] === false) ? false : true;
                        else :
                            $pagination = false;
                            echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';
                        endif;
                        ?>
                    </div>
                    
                    <?php 
 
                        if(isset($atts) && $atts['custom_pagination'] === true && $atts['posts_per_page'] < $count ){                            
                            $txt = base64_encode(serialize($atts));
                            $info['paged'] = isset($atts['paged']) ? $atts['paged'] : 1;
                            $info['loop'] = 'loop-thumbnail';
                            $info['count'] = $count;
                            $info['uid'] = $uid;
                            $infos = base64_encode(serialize($info));
                            $button = '<div class="alt-loader text-center"><button id="button_'. $uid . '"  class="btn btn-red plc-pagination" data-paged="' . $info['paged'] . '" data-container=".' .$uid . '" data-info=\'' . $infos . '\' data-atts=\''. $txt. '\'>Load More&nbsp;<i class="fa processing-icon hide"></i></button></div>';
                            
                            echo $button;
                        }
                        else{
                            echo ($pagination) ? ts_paginator($atts) : '';
                        }
                    ?>
                </div>
