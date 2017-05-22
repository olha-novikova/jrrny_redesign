<?php
if($ts_show_sidebar == 'yes') :
    $entries_class = 'has-sidebar';
    $entry_class = 'span6';
else :
    $entries_class = 'no-sidebar';
    $entry_class = 'span4';
endif;
$atts = (isset($atts) && is_array($atts)) ? $atts : array();
$ts_query = (isset($atts['default_query']) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
$edge_class = (isset($atts['fullwidth']) && ts_attr_is_true($atts['fullwidth'])) ? 'ts-edge-to-edge' : '';
?>
                <div class="portfolio-entries-wrap masonry-entries-wrap <?php echo esc_attr($edge_class);?>">
                    <?php echo ts_portfolio_filter($atts);?>
                    <div class="portfolio-loop-masonry hfeed portfolio-entries entries loop loop-masonry <?php echo esc_attr($entries_class);?> clearfix">
                        <?php
                        if($ts_query->have_posts()) : 
                            while($ts_query->have_posts()) : $ts_query->the_post();
                        ?>
                        <div id="post-<?php echo esc_attr($ts_query->post->ID);?>" class="hentry entry masonry-entry <?php echo esc_attr($entry_class.' '.ts_get_the_category('portfolio-category', 'filter-slugs', ' '));?>">
                            <div class="post-content">
                                <?php
                                $featured_media_args = array('media_width'=>480,'media_height'=>0,'gallery_width'=>480,'gallery_height'=>320,'allow_videos'=>'no');
                                echo ts_get_featured_media($featured_media_args);
                                ?>
                                <div class="title-info">                               
                                    <div class="full-link"><a href="<?php the_permalink();?>"></a></div>
                                    <div class="title-info-inner">
                                        <h4 class="title text-center entry-title"><?php the_title();?></h4>
                                        <p class="text-center mimic-smaller"><?php echo ts_get_the_category('portfolio-category');?></p>    
                                    </div>
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
