<?php
global $ts_page_title, $ts_show_title_bar_override, $ts_caption, $ts_page_id, $smof_data, $post;

if(is_page()) :
    $ts_show_title_bar_on_pages = (ts_option_vs_default('show_titlebar_on_pages', 1) != 1) ? 'no' : 'yes';
    $ts_show_title_bar = ts_postmeta_vs_default($ts_page_id, '_page_titlebar', $ts_show_title_bar_on_pages);
else :
    $ts_show_title_bar = 'yes';
endif;
if(is_archive()){
    $ts_page_title = post_type_archive_title('', false);
}
$_ts_caption = (is_page()) ? get_post_meta($ts_page_id, '_page_titlebar_caption', true) : get_post_meta($ts_page_id, '_p_titlebar_caption', true);
$ts_caption = (trim($_ts_caption)) ? $_ts_caption : $ts_caption;

$ts_caption_class = (trim($_ts_caption)) ? 'has-title-caption' : '';
$page_photo_class = $page_photo_css = '';

if($ts_show_title_bar == 'yes' || $ts_show_title_bar == 1 || $ts_show_title_bar_override == 'yes') :
    if(is_page()) :
        $page_photo_id = get_post_thumbnail_id($ts_page_id);
        $page_photo   = wp_get_attachment_image_src($page_photo_id, 'full');
        $page_photo   = (isset($page_photo[0])) ? $page_photo[0] : '';
        $page_photo   = ($page_photo) ? aq_resize($page_photo, 1360, 0, true, true, true, $page_photo_id) : '';
        $page_photo_css = ($page_photo) ? 'background-image:url('.$page_photo.');' : '';
        $page_photo_class = ($page_photo) ? 'title-banner' : '';
    endif;
    
    $title_tag = (is_front_page()) ? 'h2' : 'h1';
?>
            <div id="title-bar-wrap" class="title-bar-layout-normal title-bar-page <?php echo esc_attr($page_photo_class.' '.$ts_caption_class);?>" style="<?php echo esc_attr($page_photo_css);?>">
                <div id="title-bar" class="container">                    
                    <div id="title-bar-text" class="container">
                        <div class="row">
                            <div class="span12">
                                <?php
                                if(function_exists('is_bbpress') && is_bbpress()) :
                                ?>
                                <<?php echo tag_escape($title_tag);?>><?php
                                        echo '<a href="'.esc_url(get_permalink()).'">';
                                        echo (trim($ts_page_title)) ? esc_html($ts_page_title) : get_the_title();
                                        echo '</a>';
                                ?></<?php echo tag_escape($title_tag);?>>
                                <?php
                                elseif(is_single()) :
                                ?>
                                <<?php echo tag_escape($title_tag);?> class="entry-title"><?php 
                                        if(function_exists('is_woocommerce') && is_woocommerce()) :
                                            $shop_page = get_post(woocommerce_get_page_id('shop'));
                                            echo '<a href="'.esc_url(get_permalink($shop_page->ID)).'">';
                                            echo get_post(woocommerce_get_page_id('shop'))->post_title;
                                            echo '</a>';
                                        else :
                                            echo (ts_option_vs_default('link_title_on_post', 1) == 1) ? '<a href="'.esc_url(get_permalink()).'">' : '';
                                            echo (trim($ts_page_title)) ? esc_attr($ts_page_title) : get_the_title();
                                            echo (ts_option_vs_default('link_title_on_post', 1) == 1) ? '</a>' : '';
                                        endif;
                                ?></<?php echo tag_escape($title_tag);?>>
                                <?php echo (trim($ts_caption)) ? '<p class="title-bar-caption entry-summary">'.wp_kses_post($ts_caption).'</p>' : ''; ?>
                                <div class="meta small-size subtle-text-color"><?php 
                                    if(function_exists('is_woocommerce') && is_woocommerce()) : 
                                        global $product;
                                        echo '&#8220;'.get_the_title().'&#8221;';
                                        
                                        $show_shop_prices_on_single = ts_option_vs_default('show_shop_prices_on_single', 1);
                                        if($show_shop_prices_on_single == 1) :
                                            $sale  = get_post_meta( get_the_ID(), '_sale_price', true);
                                            $price = get_post_meta( get_the_ID(), '_regular_price', true);
                                            $price = (trim($sale)) ? $sale : $price;
                                            echo (preg_replace("/[^0-9]/", "", $price) > 0) ? ' &nbsp;/&nbsp; '.woocommerce_price( $price ) : '';
                                        endif;
                                    else :
                                        if(!is_attachment()) :
                                            echo '<span class="title-bar-cats uppercase">';
                                            the_category(', ');
                                            echo '</span>';
                                            echo ' <span class="sep sep-0">&nbsp;/&nbsp;</span> ';
                                        endif;
                                        echo '<time class="published" datetime="'.esc_attr(get_the_time('Y-m-d H:i:s')).'">';
                                        echo get_the_date();
                                        echo '</time>';
                                    endif; 
                                    
                                    if(comments_open()) :
                                        $comment_number = get_comments_number();
                                        if(function_exists('is_woocommerce') && is_woocommerce()) :
                                            $show_shop_reviews_on_single = ts_option_vs_default('show_shop_reviews_on_single', 1);
                                            if($show_shop_reviews_on_single == 1) :
                                                echo ' <span class="sep sep-1">&nbsp;/&nbsp;</span> ';
                                                echo '<a href="'.esc_url(get_permalink()).'#reviews" class="to-comments-link reviews-smoothscroll">'.$comment_number.' ';
                                                echo ($comment_number == 1) ? __('Review', 'ThemeStockyard') : __('Reviews', 'ThemeStockyard');
                                                echo '</a>'."\n";
                                            endif;
                                        endif;
                                    else :
                                        echo '<!-- comments closed -->'."\n";
                                    endif;
                                    
                                    if(!plc_is_category(13)) {
                                        echo ' <span class="sep sep-2">&nbsp;/&nbsp;</span> ';
                                        echo '<span class="mimic-small">';
                                        echo __('By', 'ThemeStockyard').' ';
                                        echo '<span class="author vcard">';
                                        echo '<a href="'.get_author_posts_url( $post->post_author ).'">'.get_the_author_meta( 'display_name', $post->post_author ).'</a>';
                                        echo '</span>';
                                        echo '</span>';
                                        if(ts_option_vs_default('show_titlebar_post_view_count', 0) == 1) :
                                            $ts_postview_nonce = wp_create_nonce('ts_update_postviews_nonce');
                                            $post_view_count = ts_postmeta_vs_default($ts_page_id, '_p_ts_postviews', 0);
                                            $post_view_count = (!$post_view_count) ? '0' : $post_view_count;
                                            $post_view_count_text = sprintf(_n( '1 view', '%s views', $post_view_count, 'ThemeStockyard'), $post_view_count);
                                            echo ' <span class="sep sep-3">&nbsp;/&nbsp;</span> ';
                                            echo '<span id="ts-postviews" data-pid="'.esc_attr($ts_page_id).'" data-nonce="'.esc_attr($ts_postview_nonce).'">';
                                            echo '<i class="fa fa-eye"></i>'.esc_html($post_view_count_text);
                                            echo '</span>';
                                        endif;
                                        echo '<span class="sep sep-2">&nbsp;/&nbsp;</span> ';
                                            echo '<span class="mimic-small">';
                                                    get_template_part('inc/frontend/partpost/following_title-part'); 
                                            echo '</span>';
                                        echo '</span>';
                                    }
                                    ?>
                                
                                </div>
                                <?php
                                else :
                                ?>
                                <<?php echo tag_escape($title_tag);?>><?php 
                                    if(function_exists('is_woocommerce') && is_woocommerce()) :
                                        echo get_post(woocommerce_get_page_id('shop'))->post_title;
                                        if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) :
                                            $term = get_queried_object();
                                            echo (is_object($term) && isset($term->name)) ? ' &rsaquo; '.esc_html($term->name) : '';
                                        endif;
                                    else :
                                        echo (trim($ts_page_title)) ? esc_attr($ts_page_title) : get_the_title();
                                    endif;
                                ?></<?php echo tag_escape($title_tag);?>>
                                <?php
                                    echo (trim($ts_caption)) ? '<p class="title-bar-caption">'.wp_kses_post($ts_caption).'</p>' : '';
                                endif;
                                ?>
                            </div>
<!--                            <div class="span4 breadcrumbs-wrap">-->
<!--                                --><?php
//                                $has_yoast_breadcrumbs = false;
//                                if(function_exists('yoast_breadcrumb')) :
//                                    $yoast_breadcrumbs = yoast_breadcrumb('<div class="breadcrumbs small" data-breadcrumbs-by="yoast">', '</div>', false);
//
//                                    $has_yoast_breadcrumbs = (trim($yoast_breadcrumbs)) ? true : false;
//                                endif;
//
//                                if(!$has_yoast_breadcrumbs) :
//                                    echo dimox_breadcrumbs();
//                                else :
//                                    echo ts_escape($yoast_breadcrumbs);
//                                endif;
//                                ?>
<!--                            </div>-->
                        </div>
                    </div>
                </div>                
            </div>
<?php
endif;
?>