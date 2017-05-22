<?php
global $smof_data, $ts_slider_post_ids, $ts_page_id, $ts_previous_posts;

$slider_margin_class = (ts_postmeta_vs_default($ts_page_id, '_page_slider_bottom_margin', 'yes') == 'no') ? 'has-no-bottom-margin' : 'has-bottom-margin';
$slider_width       = ts_postmeta_vs_default($ts_page_id, '_page_slider_width', 'content');
$slider_width_class = ($slider_width == 'content') ? 'container' : 'full';
$slider_height_default = 500;
$slider_height      = preg_replace("/[^0-9]*/","", ts_postmeta_vs_default($ts_page_id, '_page_slider_height', $slider_height_default));
$slider_height      = (ctype_digit($slider_height) && $slider_height > 100) ? $slider_height : $slider_height_default;
$slider_height  = ($slider_height == 'screen' && $slider_width_class == 'full') ? 'screen' : $slider_height;
if($slider_height != 'screen') :
    $slider_height  = (ts_number_within_range($slider_height, 300, 800)) ? $slider_height : $slider_height_default;
endif;
$slider_height_class = ($slider_height == 'screen') ? 'slider-screen-height' : '';
$slider_carousel_height  = preg_replace("/[^0-9]*/","", ts_postmeta_vs_default($ts_page_id, '_page_slider_carousel_height', '420'));
$slider_text_align  = ts_postmeta_vs_default($ts_page_id, '_page_slider_text_align', 'center');
$slider_text_align  = (in_array($slider_text_align, array('left','center','right'))) ? $slider_text_align : 'center';
$slider_allow_vids  = ts_postmeta_vs_default($ts_page_id, '_page_slider_allow_videos', 'no');
$slider_allow_vids  = ($slider_allow_vids == 'yes') ? true : false;

$ts_slider_type             = get_post_meta($ts_page_id, '_page_slider_type', true);
$ts_slider_parallax         = get_post_meta($ts_page_id, '_page_slider_enable_parallax', true);
$ts_slider_source           = get_post_meta($ts_page_id, '_page_slider_source', true);
$ts_slider_blog_post_ids    = get_post_meta($ts_page_id, '_page_slider_blog_post_ids', true);
$ts_slider_category         = get_post_meta($ts_page_id, '_page_slider_category', true);
$ts_slider_blog_cats        = get_post_meta($ts_page_id, '_page_slider_blog_cats'); // array
$ts_slider_portfolio_cats   = get_post_meta($ts_page_id, '_page_slider_portfolio_cats', true);
$ts_slider_slider_cats      = get_post_meta($ts_page_id, '_page_slider_slider_cats', true);
$ts_slider_count            = get_post_meta($ts_page_id, '_page_slider_count', true);
$ts_slider_count            = ($ts_slider_count) ? $ts_slider_count : 10;

$get_alt_text = (ts_option_vs_default('featured_image_alt_text_within_loop', 0)) ? true : false;

$slider_button_color = ts_option_vs_default('flexslider_button_color', 'white');

if(in_array($ts_slider_type, array('carousel','flex')) && $ts_slider_source) :

    $parallax_class = ($slider_width_class == 'full' && $ts_slider_parallax == 'yes') ? 'ts-is-parallax' : '';
    
    if($ts_slider_source == 'blog' || $ts_slider_source == 'specific_blog_posts') :
    
        $meta_query = array(
            'relation'=>'OR', 
            array('key'=>'_thumbnail_id'),
            array('key'=>'_p_preview_image_id')
        );
        
        if($slider_allow_vids) :
            $meta_query[] = array('key'=>'_p_vimeo_id');
            $meta_query[] = array('key'=>'_p_youtube_id');
        endif;
        
        $slider_query_params = array(
            'meta_query'        => $meta_query, 
            'posts_per_page'    => $ts_slider_count
        );
        
        // if user wants to show specific posts
        if($ts_slider_source == 'specific_blog_posts' && trim($ts_slider_blog_post_ids)) :
            $ts_slider_blog_post_ids = str_replace(' ', '', $ts_slider_blog_post_ids);
            $ts_slider_blog_post_ids_array = explode(',', $ts_slider_blog_post_ids);
            if(count($ts_slider_blog_post_ids_array) > 0) :
                $slider_query_params['post__in'] = $ts_slider_blog_post_ids_array;
            else :
                $slider_query_params['post__in'] = array();
            endif;
        endif;
        
        // if user wants to show specific categories
        if($ts_slider_source == 'blog' && is_array($ts_slider_blog_cats) && count($ts_slider_blog_cats) > 0 && !isset($ts_slider_blog_cats['error'])) :
            if(isset($ts_slider_blog_cats[0]) && is_array($ts_slider_blog_cats[0])) :
                // do nothing
            else :
                $slider_query_params['cat'] = implode(',', $ts_slider_blog_cats);
            endif;
        endif;
        
        // do the query
        $slider = new WP_Query($slider_query_params);
        
        // set $ts_slider_source as "blog"
        $ts_slider_source = 'blog';
        
    elseif($ts_slider_source == 'portfolio') :
    
        $meta_query = array(
            'relation'=>'OR', 
            array('key'=>'_thumbnail_id'),
            array('key'=>'_portfolio_preview_image_id')
        );
        
        if($slider_allow_vids) :
            $meta_query[] = array('key'=>'_portfolio_vimeo_id');
            $meta_query[] = array('key'=>'_portfolio_youtube_id');
        endif;
        
        $tax_query = '';
        if(is_array($ts_slider_portfolio_cats) && count($ts_slider_portfolio_cats) && !isset($ts_slider_portfolio_cats['error'])) :
            $tax_query = array(
                array(
                    'taxonomy' => 'portfolio-category',
                    'field' => 'term_id',
                    'terms' => $ts_slider_portfolio_cats
                )
            );
        endif;
        
        $slider = new WP_Query(array('post_type'=>'portfolio','meta_query' => $meta_query, 'tax_query' => $tax_query,'posts_per_page'=>$ts_slider_count));
        
    elseif($ts_slider_source == 'slider') :
    
        $meta_query = array(
            'relation'=>'OR', 
            array('key'=>'_thumbnail_id'),
            array('key'=>'_slider_preview_image_id')
        );
        
        if($slider_allow_vids) :
            $meta_query[] = array('key'=>'_slider_vimeo_id');
            $meta_query[] = array('key'=>'_slider_youtube_id');
        endif;
        
        $tax_query = '';
        if($ts_slider_category) :
            $tax_query = array(
                array(
                    'taxonomy' => 'slider-category',
                    'field' => 'term_id',
                    'terms' => $ts_slider_category
                )
            );
        endif;
        
        $slider = new WP_Query(array('post_type'=>'slider','meta_query' => $meta_query, 'tax_query' => $tax_query,'posts_per_page'=>$ts_slider_count));
        
    endif;

    if(is_object($slider) && $slider->have_posts()) : 
    
        echo '<div id="main-slider-wrap-wrap">'."\n";
        echo '<div id="main-slider-wrap" class="ts-slider-wrap loop-slider-wrap flexslider-wrap clear slider '.esc_attr($slider_height_class).' '.esc_attr($slider_margin_class).' '.esc_attr($slider_width_class).' ts-fade-in ts-item-details-'.esc_attr($slider_text_align).' ts-flex-nav-'.esc_attr($slider_text_align).' '.esc_attr($parallax_class).' playing">'."\n";
        echo '<div class="flexslider gallery" style="overflow:hidden;">'."\n";
        echo '<ul class="slides" style="overflow:hidden;">'."\n";
        
        while ($slider->have_posts()) :
        
            $slider->the_post();
            
            $ts_previous_posts[] = $slider->post->ID;
            $ts_slider_post_ids[] = $slider->post->ID;
            
            $post_type = get_post_type();
            if($post_type == 'post') :
                $prefix = 'p';
            elseif($post_type == 'portfolio') :
                $prefix = 'portfolio';
            elseif($post_type == 'slider') :
                $prefix = 'slider';
            endif;
            
            $slider_photo_id = get_post_thumbnail_id($slider->post->ID);
            $slider_preview_id = ($post_type == 'post') ? get_post_meta($slider->post->ID, '_p_preview_image_id', true) : '';
            $slider_preview_exists = ($slider_preview_id) ? true : false;
            $slider_photo_id = ($slider_preview_id) ? $slider_preview_id : $slider_photo_id;
            
            /* what's the post format? */
            if($post_type == 'post') :
                $content_type = get_post_format();
            elseif($post_type == 'portfolio') :
                $content_type = get_post_meta($slider->post->ID, '_portfolio_project_type', true);
            elseif($post_type == 'slider') :
                $content_type = get_post_meta($slider->post->ID, '_slider_slide_type', true);
            endif;
            
            $item_el = ($ts_slider_type == 'carousel') ? 'div' : 'li';
            $video = '';
            $video_src = '';
            $video_id = '';
            $vdata = '';
            $vimeo = $youtube = $video_service = '';
            $img = '';
            $img_blank = get_template_directory_uri().'/images/blank.gif';
            $w = '';
            $h = '';
            $w_mobile = '';
            $h_mobile = '';
            $hide_text = '';
            if($ts_slider_type == 'carousel') :
                $w = 630; // this is just a good starting point
                $h = 420; // this is just a good starting point
                $slider_carousel_height = ($slider_carousel_height == 'screen') ? 420 : $slider_carousel_height;
                $dims = ts_get_proportional_size($w, $h, null, $slider_carousel_height);  
                $w = $dims['width'];
                $h = $dims['height'];
            elseif($ts_slider_type == 'flex') :
                if($slider_width == 'content' || ts_option_vs_default('layout', 1) < 1) :
                    $w = 1024; // this is just a good starting point
                else :
                    $w = 1360;
                endif;
                $h = $slider_height;
            endif;
            $style = '';
            $title = '';
            $descr = '';
            $descr_limit = ($ts_slider_type == 'carousel') ? 150 : 200;
            $title_size = ($ts_slider_type == 'carousel') ? 2 : 2;
            $date = '';
            $category   = '';
            $cat_name   = '';
            $cat_color  = '';
            $author     = '';
            $comments   = '';
            $link_begin = '';
            $link_end = '';
            $url = '';
            $read_more = '';
            $readmore = '';
            $readmore_inline = '';
            $readmore_block = '';
            $meta = '';
            $categories = '';
            $dims = '';
            $info = '';
            $img_wrap_begin = '';
            $img_wrap_end = '';
            $target = '_self';
            
            if($slider_photo_id) :
                $slider_photo   = wp_get_attachment_image_src($slider_photo_id, 'full');
                $slider_photo   = (isset($slider_photo[0])) ? $slider_photo[0] : '';
                if($ts_slider_type == 'carousel') :
                    $slider_photo   = ($slider_photo) ? aq_resize($slider_photo, $w, $h, true, false, true, $slider_photo_id) : '';
                else :
                    $h = ($h == 'screen') ? $slider_height_default : $h;
                    $sh = ($w == 1360) ? 0 : $h;
                    $slider_photo   = ($slider_photo) ? aq_resize($slider_photo, $w, $sh, true, false, true, $slider_photo_id) : '';
                endif;
                $img            = (isset($slider_photo[0])) ? $slider_photo[0] : 'error';
                if($img == 'error') :
                    echo '<!-- error: aq_resize -->';
                    continue;
                endif;
                $w              = $slider_photo[1];
                $h              = ($w == 1360) ? $slider_height : $slider_photo[2];
                $style          = ($ts_slider_type == 'carousel') ? 'style="width:'.absint($w).'px;height:'.absint($h).'px;"' : '';
                $title          = get_the_title();
                
                if($ts_slider_source == 'blog') :
                    $descr      = (has_excerpt()) ? strip_tags(get_the_excerpt()) : ts_trim_text(apply_filters('the_content', get_the_content()), $descr_limit);
                    $url        = get_permalink();
                    $read_more  = __('Read More', 'ThemeStockyard');
                    $date       = get_the_date('M j, Y');
                    $category   = ts_get_the_category('category', 'big_array:1', '', $slider->post->ID);
                    
                    $cat_name   = $category[0]['name'];
                    $cat_color  = ($category[0]['color']) ? $category[0]['color'] : 'primary';
                    
                    $alt_cat_name = get_post_meta( $slider->post->ID, '_'.$prefix.'_alt_category_text', true );
                    
                    $cat_name   = (trim($alt_cat_name)) ? $alt_cat_name : $cat_name;
                    
                    $author     = get_the_author_meta('display_name');
                    if(ts_disqus_active()) :
                        $comments   = '<span class="disqus-comment-count" data-disqus-url="'.esc_url(get_permalink()).'">0</span>';
                    else :
                        $comments   = get_comments_number();
                    endif;
                    
                    /* this will change per theme */
                    //$meta .= '<span class="small-size color-white stylized-meta">'.esc_html($author).'</span>';
                    
                    $meta .= '<span class="small-size stylized-meta">'.__('By', 'ThemeStockyard').' <em class="color-white">'.esc_html($author).'</em></span> ';
                    if(comments_open()) :
                        $meta .= '<span class="comment-bubble border-primary bg-primary bg-primary-text mimic-small">'.$comments.'</span>';
                    endif;
                elseif($ts_slider_source == 'portfolio') :
                    $descr      = (has_excerpt()) ? strip_tags(get_the_excerpt()) : ts_trim_text(apply_filters('the_content', get_the_content()), $descr_limit);
                    $url        = get_permalink();
                    $read_more  = __('Read More', 'ThemeStockyard');
                    $meta       = ts_get_the_category('portfolio-category');
                elseif($ts_slider_source == 'slider') :
                    $descr      = get_post_meta( $slider->post->ID, '_'.$prefix.'_description', true );
                    $url        = get_post_meta( $slider->post->ID, '_'.$prefix.'_url', true );
                    $_target    = get_post_meta( $slider->post->ID, '_'.$prefix.'_new_tab', true );
                    $target     = ($_target) ? '_blank' : '_self';
                    $hide_text  = get_post_meta( $slider->post->ID, '_'.$prefix.'_hide_text', true );
                    $hide_text  = ($hide_text) ? 1 : 0;
                endif;
                
                $link_begin     = (trim($url)) ? '<a href="'.esc_url($url).'" class="ts-item-link" target="'.esc_attr($target).'">' : '';
                $link_begin2    = (trim($url)) ? '<a href="'.esc_url($url).'" class="ts-item-link ts-item-link-2" target="'.esc_attr($target).'">' : '';
                $link_end       = (trim($url)) ? '</a>' : '';
            endif;
            if($slider_allow_vids && (in_array($content_type, array('video','youtube','vimeo','self_hosted','self_hosted_video'))) && !$slider_preview_exists) :
                $vimeo          = get_post_meta( $slider->post->ID, '_'.$prefix.'_vimeo_id', true );
                $youtube        = get_post_meta( $slider->post->ID, '_'.$prefix.'_youtube_id', true );
                $content_type .= ($vimeo) ? $vimeo : $youtube;
                if($vimeo || $youtube) :
                    $video_service  = ($vimeo) ? 'vimeo' : (($youtube) ? 'youtube' : '');
                    $video_id       = ($vimeo) ? ts_get_video_id($vimeo) : (($youtube) ? ts_get_video_id($youtube) : '');
                    $vdata          = ts_get_and_save_video_data($video_id, $video_service);
                    //$info           = maybe_serialize(ts_get_saved_video_data($video_id, $video_service));    // for testing
                    if($ts_slider_type == 'carousel') :     
                        $style          = 'width:'.absint($w).'px;height:'.absint($h).'px;';
                    endif;
                endif;
                if($video_id) :
                    $img = '';
                else :
                    $youtube = $vimeo = '';
                endif;
            endif;
            if(!$img && !$vimeo && !$youtube) :
                continue;
            endif;
            
            if($ts_slider_type == 'carousel') :
                if($slider_height > 202) :
                    $w_mobile = $w;
                    $h_mobile = $h;
                else :
                    $w_mobile = $w;
                    $h_mobile = $h; 
                endif;
            endif;
            
            if($ts_slider_type == 'flex' && $slider_width_class == 'full' && $img) :
                $h = (!$h || $h == 'screen') ? $slider_height_default : $h;
                $style = 'background-image:url('.esc_url($img).');height:'.absint($h).'px';
                $img = $img_blank;
                $img_wrap_begin = '';
                $img_wrap_end = '';
            endif;
            
            
            // note: $style variable is escaped above
            echo '<li class="'.esc_attr($ts_slider_type).'-item ts-slider-item" style="'.esc_attr($style).'" data-width="'.absint($w).'" data-height="'.absint($h).'">'."\n";
            
            $img_height = ($ts_slider_type == 'carousel') ? 'height="'.absint($h).'"' : '';
            if($img) :
                $alt_text = ($get_alt_text) ? ts_get_attachment_alt_text($slider_photo_id) : '';
                $img_html = $img_wrap_begin.'<img src="'.esc_url($img).'" width="'.absint($w).'" '.$img_height.' alt=""/>'.$img_wrap_end."\n";
            elseif($vimeo || $youtube) :
                $rand_id = mt_rand();
                if($vimeo) :
                    $qs_addons = ts_get_video_qs_addons($vimeo, '&amp;');
                    $color = str_replace('#', '', ts_option_vs_default('primary_color',''));
                    $video_src  = 'https://player.vimeo.com/video/'.$video_id.'?api=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;color='.$color;
                    $video_src .= '&amp;player_id='.$video_service.'-'.$slider->post->ID.'-'.$rand_id.$qs_addons;
                    $iframe_class = 'ts-vimeo-player';
                else :
                    $qs_addons = ts_get_video_qs_addons($youtube, '&amp;');
                    $video_src = 'https://www.youtube.com/embed/'.$video_id.'?enablejsapi=1&amp;rel=0&amp;version=3&amp;hd=1'.$qs_addons;
                    $iframe_class = 'ts-youtube-player';
                endif;
                
                echo '<div class="fluid-width-video-wrapper"><div>';
                echo '<iframe id="'.esc_attr($video_service.'-'.$slider->post->ID.'-'.$rand_id).'" src="'.esc_url($video_src).'" width="'.absint($w).'" '.$img_height.' data-videoid="'.esc_attr($video_id).'" class="'.esc_attr($iframe_class).'" allowFullScreen webkitAllowFullScreen></iframe>';
                echo '</div></div>'."\n";
            endif;
            
            if($img) :
                echo strip_tags($link_begin, '<a>');
                echo ($ts_slider_type == 'flex' && $slider_width_class == 'full') ? '' : strip_tags($img_html, '<a><img>');
                
                if((trim($title) || trim($descr)) && $hide_text != '1') :
                    
                    echo '<div class="ts-item-details">'."\n";
                    echo ($ts_slider_type == 'flex' && $w == 1360) ? '<div class="ts-item-details-inner container">'."\n" : '<div class="ts-item-details-inner">'."\n";
                    
                    if($ts_slider_source == 'portfolio') :
                        echo '<h'.absint($title_size).' class="portfolio-title title text-'.esc_attr($slider_text_align).' color-white">'.ts_escape($title).'</h'.absint($title_size).'>'."\n";
                        echo (trim($meta)) ? '<p class="portfolio-meta color-shortcode white text-'.esc_attr($slider_text_align).'">'.strip_tags($meta, '<span>').'</p>'."\n" : '';
                    else :
                        echo '<h'.absint($title_size).' class="blog-title title text-'.esc_attr($slider_text_align).' color-white">'.ts_sticky_badge().ts_escape($title).'</h'.absint($title_size).'>'."\n";
                        echo '<p class="blog-meta text-'.esc_attr($slider_text_align).'">'.strip_tags($meta, '<span>').'</p>'."\n";
                    endif;
                    
                    
                    echo '</div></div>'."\n";
                endif;
                
                echo strip_tags($link_end, '<a>');
            endif;
            
            echo '</li>'."\n";
            
            
        endwhile;
    
        echo '</ul>'."\n";
        echo '<span class="pause-slider smaller uppercase bg-primary"><i class="fa fa-pause"></i> '.__('Pause','ThemeStockyard').'</span>'."\n";
        echo '<span class="play-slider smaller uppercase bg-primary"><i class="fa fa-play"></i> '.__('Resume','ThemeStockyard').'</span>'."\n";
        echo '</div>'."\n";
        echo '<div class="ts-main-flex-nav"></div>'."\n";            
        echo '</div>'."\n";
        echo '</div>'."\n";
    endif;
endif;