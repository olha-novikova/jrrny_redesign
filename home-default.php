<?php
/*
Template Name: Home - Default
*/

$ts_page_object = get_queried_object();
$ts_page_id     = (is_single()) ? $post->ID : get_queried_object_id();

global $ts_template_content;

$ts_template_content = '[blog layout="3-column-grid" limit="6" infinite_scroll="no" infinite_scroll_button_text="" category_name="" include="" exclude="" exclude_previous_posts="yes" exclude_these_later="yes" excerpt_length="115" title_size="" text_align="" show_pagination="no" show_excerpt="yes" show_meta="yes" show_read_more="no" allow_videos="no" allow_galleries="yes"][/blog]

[blog_banner columns="2" limit="2" fullwidth="yes" infinite_scroll="no" infinite_scroll_button_text="" category_name="" include="" exclude="" exclude_previous_posts="yes" exclude_these_later="yes" excerpt_length="" show_pagination="no" text_position="" ignore_sticky_posts="0"][/blog_banner]

[blog layout="3-column-grid" limit="3" infinite_scroll="no" infinite_scroll_button_text="" category_name="" include="" exclude="" exclude_previous_posts="yes" exclude_these_later="yes" excerpt_length="115" title_size="" text_align="" show_pagination="no" show_excerpt="yes" show_meta="yes" show_read_more="no" allow_videos="no" allow_galleries="yes"][/blog]';

get_template_part('page');
