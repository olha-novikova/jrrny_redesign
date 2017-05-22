<?php
global $ts_page_id;

if(function_exists('putRevSlider'))
{
    $slider_width       = ts_postmeta_vs_default($ts_page_id, '_page_slider_width', 'fullwidth');
    $slider_width_class = ($slider_width == 'content') ? 'container' : 'full';
    $rev_slider_id      = get_post_meta($ts_page_id, '_page_rev_slider', true);

    if(get_post_meta($ts_page_id, '_page_slider_type', true) == 'rev' && $rev_slider_id) 
    {
        echo '<div id="main-slider-wrap" class="ts-slider-wrap ts-rev-slider-wrap clear '.esc_attr($slider_width_class).'">'."\n";
        putRevSlider($rev_slider_id);
        echo '</div>';
    }
}