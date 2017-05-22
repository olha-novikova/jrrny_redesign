    <ul id="ts-news-ticker" class="slides">
        <?php
        $ts_query = (isset($atts) && ($atts['default_query'] === false)) ? new WP_Query($atts) : $wp_query;
        $atts = (isset($atts)) ? $atts : array();
        
        if($ts_query->have_posts()) : 
            while($ts_query->have_posts()) :
                $ts_query->the_post();
                
                $category = ts_get_the_category('category', 'big_array:1', '', $ts_query->post->ID);
                
                $media = ts_get_featured_media(array('media_width'=>80,'media_height'=>80,'allow_videos'=>'no','allow_galleries'=>'no'));
                
                $item_class = (trim($media)) ? 'has-image' : 'no-image';
        ?>                        
        <li class="news-item flex-item <?php echo esc_attr($item_class);?>">
            <?php
            if(trim($media)) :
                echo ts_escape($media);
            endif;
            ?>
            <div class="post-category post-category-heading mimic-small uppercase">
                <a href="<?php echo get_category_link($category[0]['term_id']);?>"><strong><?php echo esc_html($category[0]['name']);?></strong></a>
            </div>
            <h4><a href="<?php the_permalink();?>" ><?php the_title();?></a></h4>
        </li>
        <?php
            endwhile;
        endif;
        ?>                        
    </ul>
