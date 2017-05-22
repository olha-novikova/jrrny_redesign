<?php
if($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
    $entry_class = 'span6';
else :
    $entries_class = 'no-sidebar';
    $entry_class = 'span4';
endif;
?>
                <div class="portfolio-entries-wrap masonry-entries-wrap">
                    <?php echo ts_portfolio_filter($atts);?>
                    <div class="portfolio-loop-masonry-cards hfeed entries loop loop-masonry masonry-cards <?php echo esc_attr($entries_class);?> clearfix">
                        <?php
                        $ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
                        $atts['title_align'] = ($atts['title_align']) ? $atts['title_align'] : 'text-center';
                        $atts['meta_align'] = ($atts['meta_align']) ? $atts['meta_align'] : 'text-center';                        
                        $atts['read_more_align'] = ($atts['read_more_align']) ? $atts['read_more_align'] : 'text-center';
                        if($ts_query->have_posts()) : 
                            while($ts_query->have_posts()) : $ts_query->the_post();
                        ?>
                        
                        <div id="post-<?php echo esc_attr($ts_query->post->ID);?>" class="hentry entry masonry-entry <?php echo esc_attr($entry_class.' '.ts_get_the_category('portfolio-category', 'filter-slugs', ' '));?>">
                            <div class="post-content">
                                <?php
                                $featured_media_args = array('media_width'=>480,'media_height'=>0,'gallery_width'=>480,'gallery_height'=>320,'allow_videos'=>'no');
                                echo ts_get_featured_media($featured_media_args);
                                ?> 
                                <div class="post-content-inner">
                                    <div class="title-date">
                                        <div class="title-info">                                    
                                            <h4 class="<?php echo esc_attr($atts['title_align']);?>"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                                            <p class="<?php echo esc_attr($atts['meta_align']);?> small other"><?php echo ts_get_the_category('portfolio-category', 'list', ' &bull; ');?></p>
                                        </div>
                                    </div>
                                    <div class="read-more"><p class="<?php echo esc_attr($atts['read_more_align']);?> uppercase mimic-small more-details"><a href="<?php the_permalink();?>"><?php _e('More Details', 'ThemeStockyard');?></a></p></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                            endwhile;
                        else :
                        
                            echo '<p class="no-results">'.__('Sorry, nothing here!', 'ThemeStockyard').'</p>';
                        endif;
                        ?>
                        
                    </div>
                    <?php
                    echo (isset($atts['show_pagination']) && $atts['show_pagination'] === false) ? '' : ts_paginator();
                    ?>
                </div>
