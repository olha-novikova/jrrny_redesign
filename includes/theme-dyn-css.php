<?php
function ts_dynamic_css($color = '', $layout = '', $bg_image = '') 
{
    if(function_exists('ts_option_vs_default'))
    {
?> 
    /******************** ***********************/
    

    /* SECTION 3: TYPOGRAPHY

        Within this section....
        - Section 3a: font-famiy
        - Section 3b: font-size
        - Section 3c: font-style
        - Section 3d: line-height
    ================================================== */
    <?php
    $logo_font_family = ts_option_vs_default_font('logo_font_family', 'Montserrat');    
    $body_font_family = ts_option_vs_default_font('body_font_family', 'Open Sans');    
    $h1_font_family = ts_option_vs_default_font('h1_font_family', 'Montserrat');
    $h2_font_family = ts_option_vs_default_font('h2_font_family', 'Montserrat');
    $h3_font_family = ts_option_vs_default_font('h3_font_family', 'Montserrat');
    $h4_font_family = ts_option_vs_default_font('h4_font_family', 'Montserrat');
    $h5_font_family = ts_option_vs_default_font('h5_font_family', 'Open Sans');
    $h6_font_family = ts_option_vs_default_font('h6_font_family', 'Open Sans'); 
    $stylized_meta_font_family = ts_option_vs_default_font('stylized_meta_font_family', 'Libre Baskerville'); 
    $stylized_meta_font_style  = ts_option_vs_default('stylized_meta_font_style::style', 'italic');
    $form_font_family = ts_option_vs_default_font('form_font_family', 'Open Sans');
    $button_font_family = ts_option_vs_default_font('button_font_family', 'Open Sans');   
    $small_font_family = ts_option_vs_default_font('small_font_family', 'Open Sans');    
    $main_nav_font_family = ts_option_vs_default_font('main_nav_font_family', 'Open Sans');
    $main_nav_submenu_font = ts_option_vs_default_font('main_nav_submenu_font', 'Open Sans');
    ?>

    /* Section 3a: font-family */
    #logo .logo-text { font-family: "<?php echo esc_attr($logo_font_family);?>"; }
    body { font-family: "<?php echo esc_attr($body_font_family);?>"; }
    h1 { font-family: "<?php echo esc_attr($h1_font_family);?>"; }
    h2 { font-family: "<?php echo esc_attr($h2_font_family);?>"; }
    h3 { font-family: "<?php echo esc_attr($h3_font_family);?>"; }
    h4 { font-family: "<?php echo esc_attr($h4_font_family);?>"; }
    h5,
    .ts-tabs-widget .tab-header li { font-family: "<?php echo esc_attr($h5_font_family);?>"; }
    h6 { font-family: "<?php echo esc_attr($h6_font_family);?>"; }
    .stylized-meta { font-family: "<?php echo esc_attr($stylized_meta_font_family);?>"; <?php echo aq_font_style($stylized_meta_font_style);?> }
    .main-nav { font-family: "<?php echo esc_attr($main_nav_font_family);?>"; }
    .main-nav ul ul { font-family: "<?php echo esc_attr($main_nav_submenu_font);?>"; }
    #top-bar > .right-side input,
    small,
    .small,
    .smaller,
    .mimic-small,
    .mimic-smaller,
    #header-social,
    .dem-tags a, 
    .post-tags a,
    #copyright-nav,
    .widget .tagcloud a,
    .post .wp-caption-text,
    .mimic-post .wp-caption-text,
    ol.commentlist .comment-head,
    .post-single-prev-next strong { font-family: "<?php echo esc_attr($small_font_family);?>"; }    
    select,
    textarea, 
    input[type="tel"], 
    input[type="url"],
    input[type="text"], 
    input[type="email"],  
    input[type="search"],
    input[type="submit"],
    input[type="password"],
    .woocommerce-page .select2-drop-active,
    .woocommerce .select2-container .select2-choice { font-family: "<?php echo esc_attr($form_font_family);?>"; } 
    button,
    .button,
    .wpcf7-submit,
    #button,
    input[type="submit"],
    .woocommerce input[type="submit"], 
    .woocommerce input[type="button"], 
    .woocommerce .product-remove a.remove { font-family: "<?php echo esc_attr($button_font_family);?>"; }

    
    <?php    
    $body_font_size  = ts_option_vs_default('body_font_style::size', '14px');
    ?>
    /* Section 3b: font-size */
    body,
    blockquote p { font-size: <?php echo esc_attr($body_font_size);?>; }
    
    <?php
    $form_font_size  = ts_option_vs_default('form_font_style::size', '14px');
    $form_font_style  = ts_option_vs_default('form_font_style::style', 'normal');
    ?>
    select,
    textarea, 
    input[type="tel"], 
    input[type="url"],
    input[type="text"], 
    input[type="email"],  
    input[type="search"],
    input[type="number"],
    input[type="password"],
    .woocommerce-page .select2-drop-active,
    .woocommerce .select2-container .select2-choice { font-size: <?php echo esc_attr($form_font_size);?>; <?php echo aq_font_style($form_font_style);?> }
    
    <?php
    $h1_font_size  = ts_option_vs_default('h1_font_style::size', '36px');
    $h1_font_style  = ts_option_vs_default('h1_font_style::style', 'normal');
    $h2_font_size  = ts_option_vs_default('h2_font_style::size', '26px');
    $h2_font_style  = ts_option_vs_default('h2_font_style::style', 'normal');
    $h3_font_size  = ts_option_vs_default('h3_font_style::size', '20px');
    $h3_font_style  = ts_option_vs_default('h3_font_style::style', 'normal');
    $h4_font_size  = ts_option_vs_default('h4_font_style::size', '15px');
    $h4_font_style  = ts_option_vs_default('h4_font_style::style', 'normal');
    $h5_font_size  = ts_option_vs_default('h5_font_style::size', '14px');
    $h5_font_style  = ts_option_vs_default('h5_font_style::style', 'normal');
    $h6_font_size  = ts_option_vs_default('h6_font_style::size', '12px');
    $h6_font_style  = ts_option_vs_default('h6_font_style::style', 'normal');
    ?>
    h1,
    #title-bar h2,
    #main-slider-wrap.flexslider-wrap h2,
    .loop-slider-wrap.ts-edge-to-edge h2 { font-size: <?php echo esc_attr($h1_font_size);?>; <?php echo aq_font_style($h1_font_style);?> }
    h2 { font-size: <?php echo esc_attr($h2_font_size);?>; <?php echo aq_font_style($h2_font_style);?> }
    h3 { font-size: <?php echo esc_attr($h3_font_size);?>; <?php echo aq_font_style($h3_font_style);?> }
    h4 { font-size: <?php echo esc_attr($h4_font_size);?>; <?php echo aq_font_style($h4_font_style);?> }
    h5,
    .ts-tabs-widget .tab-header li { font-size: <?php echo esc_attr($h5_font_size);?>; <?php echo aq_font_style($h5_font_style);?> }
    h6 { font-size: <?php echo esc_attr($h6_font_size);?>; <?php echo aq_font_style($h6_font_style);?> }
    
    <?php
    $main_nav_font_size  = ts_option_vs_default('main_nav_font_style::size', '13px');
    ?>
    .main-nav > ul > li,
    .main-nav > div > ul > li,
    #header-social .social .icon-style,
    .social-icons-widget-style .social .icon-style { font-size: <?php echo esc_attr($main_nav_font_size);?>; }
    
    <?php
    $logo_font_size  = ts_option_vs_default('logo_font_style::size', '30px');
    $logo_font_style  = ts_option_vs_default('logo_font_style::style', 'normal');
    ?>
    #logo .logo-text { font-size: <?php echo esc_attr($logo_font_size);?>; <?php echo aq_font_style($logo_font_style);?> }



    /* SECTION 4: BACKGROUNDS
    ================================================== */
    
    <?php
    $primary_color = ts_option_vs_default('primary_color', '#EE4643');
    $primary_color_rgb = (isset($primary_color)) ? ts_hex2rgb($primary_color, 'string') : ts_hex2rgb('#EE4643', 'string');
    $standard_border_color = ts_option_vs_default('standard_border_color', '#eee');
    $subtle_bg_color = ts_option_vs_default('subtle_bg_color', '#f9f9f9');
    ?>

    /* primary/highlight color */
    #top-bar .ts-searchform.form-in-use input,
    .widget_calendar table td#today,
    #ts-news-ticker-nav .flex-direction-nav a,
    .vertical-tabs ul.tab-header li.active:before,
    .horizontal-tabs ul.tab-header li.active:before,
    button,
    .button,
    .wpcf7-submit,
    #button,
    input[type="submit"],
    .spinner > div,
    .woocommerce input[type="submit"], 
    .woocommerce input[type="button"], 
    .woocommerce .product-remove a.remove,
    .loop .ts-meta-wrap .meta-item-date { background-color: <?php echo esc_attr($primary_color);?>; }

    <?php
    $use_custom_background_image = ts_option_vs_default('use_custom_background_image', 0);
    $custom_background_image = ts_option_vs_default('custom_background_image', null);
    $custom_background_position = ts_option_vs_default('custom_background_positioning::position', 'top left');
    $custom_background_repeat = ts_option_vs_default('custom_background_positioning::repeat', 'repeat');
    $custom_background_attachment = ts_option_vs_default('custom_background_positioning::attachment', 'fixed');

    $background_color  = ts_option_vs_default('background_color', '#fff');
    $content_background_color  = ts_option_vs_default('content_background_color', '#fff');
    
    if($content_background_color && ts_option_vs_default('layout', 1) != 1) 
    {
        $content_bg = $content_background_color;
        $content_bg_rgb = ts_hex2rgb($content_background_color, 'string');
    }
    else
    {
        $content_bg = $background_color;
        $content_bg_rgb = ts_hex2rgb($background_color, 'string');
    }
    ?>
    /* body background color */
    body,
    #wrap,
    #ts-style-selector-wrap,
    #ts-style-selector-toggle { background-color: <?php echo esc_attr($background_color);?>; }
    #ts-main-nav-inner-wrap.stickied,
    body.not-wall-to-wall #wrap,
    .woocommerce #payment, 
    .woocommerce-page #payment,
    .loop .ts-meta-wrap .meta-item-author,
    .traditional-tabs.horizontal-tabs .tab-header li,
    .vertical-tabs ul.tab-header li.active:before { background-color: <?php echo esc_attr($content_bg);?>; }
    #ts-moon-comment-bubble:before { border-top-color: <?php echo esc_attr($content_bg);?>; }
    
    <?php 
    if($use_custom_background_image && $custom_background_image) : 
    ?>
    body.has-custom-bg-image { 
        background: url(<?php echo esc_url($custom_background_image);?>) 
            <?php echo esc_attr($custom_background_position.' '.$custom_background_repeat.' '.$custom_background_attachment);?> !important; 
    }
    <?php 
    endif; 
    ?>

    /* top container */
    #top-wrap,
    #top-bar-wrap,
    #ts-style-selector-wrap,
    #ts-style-selector-toggle,
    #ts-main-nav-inner-wrap.stickied,
    #top-bar > .right-side input,
    #top-bar > .middle-area .menu li ul li,
    .top-stuff-link-item .search-pocket-wrap .search-pocket { background-color: <?php echo esc_attr($content_bg);?>; }

    
    /* main nav > sub-menu */
    .main-nav ul ul.children,
    .main-nav ul ul.sub-menu,
    .main-nav ul .main-nav-search-sub-menu,
    .main-nav ul .main-nav-shop-sub-menu { background-color: <?php echo esc_attr($content_bg);?>; }

    /* subtle background color */
    .subtle-bg-color,
    code.ts-inline-code,
    .pagination>a:hover, 
    .pagination>a:focus, 
    .pagination>a.active, 
    .pagination>span.active,
    #ts-news-ticker-wrap .flex-direction-nav .flex-next,
    .page-links .wp-link-pages > span,
    form#commentform .form-allowed-tags code,
    .woocommerce table.shop_table tfoot th, 
    .woocommerce-page table.shop_table tfoot th,
    .woocommerce #payment div.payment_box, 
    .woocommerce-page #payment div.payment_box,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, 
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle { background-color: <?php echo esc_attr($subtle_bg_color);?>; }
    
    /* post section background */
    .masonry-cards .card-butt p,
    .tagline-shortcode .tagline,
    .masonry-cards .post-content .post-content-inner,
    .ts-pricing-table .ts-pricing-column { background-color: <?php echo esc_attr($content_bg);?>; }
    
    <?php
    $footer_bg_color = ts_option_vs_default('footer_bg_color', '#f5f5f5');
    $copyright_bg_color = ts_option_vs_default('copyright_bg_color', '#EE4643');
    ?>
    /* footer background color */
    #footer-copyright-wrap { background-color: <?php echo esc_attr($footer_bg_color);?>; }
    #copyright-nav-wrap { background-color: <?php echo esc_attr($copyright_bg_color);?>; }




    /* TYPOGRAPHY COLORS (and other relevant items)
    ================================================== */
    
    <?php
    $body_font_color  = ts_option_vs_default('body_font_color', '#555');
    ?>
    /* body: plain text */
    body,
    .mobile-menu,
    code.ts-inline-code, 
    form#commentform .form-allowed-tags code,
    .woocommerce-info, 
    .woocommerce-error,
    .woocommerce-message,
    .woocommerce #payment div.payment_box, 
    .woocommerce-page #payment div.payment_box { color: <?php echo esc_attr($body_font_color);?>; }
    
    /* mobile nav */
    .mobile-menu-icon { background-color: <?php echo esc_attr($body_font_color);?>; }
    .mobile-menu-icon:before { border-color: <?php echo esc_attr($body_font_color);?>; }
    
    <?php
    $heading_font_color  = ts_option_vs_default('heading_font_color', '#444');
    ?>
    /* body: h1-6 headings */
    h1, h2, h3, h4, h5, h6 { color: <?php echo esc_attr($heading_font_color);?>; }
    
    <?php
    $body_link_color  = ts_option_vs_default('body_link_color', '#EE4643');
    $body_title_link_color  = ts_option_vs_default('body_title_link_color', '#212121');
    ?>
    /* body: link color */
    a, 
    a:hover,
    a:visited, 
    a:active,
    a:focus { color: <?php echo esc_attr($body_link_color);?>; } 
    .tp-caption a,
    .tab-head.active,
    #title-bar h1 a,
    #title-bar h2 a,
    h1 a,
    h2 a, 
    h3 a, 
    h4 a,
    h5 a,
    h6 a,
    .post h1 a,
    .post h2 a, 
    .post h3 a, 
    .post h4 a,
    .post h5 a,
    .post h6 a,
    #sidebar h1 a,
    #sidebar h2 a, 
    #sidebar h3 a, 
    #sidebar h4 a,
    #sidebar h5 a,
    #sidebar h6 a,
    #page-share a,
    #ts-post-author a,
    #ts-news-ticker h4 a,
    .post .dem-tags.smaller a,
    .pagination a.active,
    #title-bar .to-comments-link,
    .ts-tabs-widget .tab-header li.active { color: <?php echo esc_attr($body_title_link_color);?>; }

    <?php
    $subtle_text_color = ts_option_vs_default('subtle_text_color', '#999');
    ?>
    /* subtle text color */
    del,
    small,
    .small,
    .smaller,
    small a,
    .small a,
    .smaller a,
    .post small a,
    .post .small a,
    .post .smaller a,
    #sidebar small a,
    #sidebar .small a,
    #sidebar .smaller a,
    strike,
    #header-social,
    #header-social ul li a,
    .subtle-text-color,
    .title-bar-caption, 
    .loop .entry .title-info p,
    .widget_rss li .rssSummary,
    ol.commentlist .comment-head,
    .post-single-prev-next a strong,
    .widget_calendar table caption,
    .ts-tabs-widget .tab-header li,
    .ts-tabs-widget .tab-header li:before,
    .ts-searchform button,
    .widget_search button,
    .ts-searchform input[type="submit"],
    .widget_search input[type="submit"],
    #header-social .social .icon-style,
    .social-icons-widget-style .social .icon-style,
    .woocommerce p.stars span a, 
    .woocommerce-page p.stars span a,
    .woocommerce .shop_table .product-name dt,
    #ts-news-ticker-wrap .flex-direction-nav i { color: <?php echo esc_attr($subtle_text_color);?>; }
    
    
    /* primary color */
    .woocommerce p.stars span a:hover, 
    .woocommerce-page p.stars span a:hover,
    .woocommerce p.stars span a.active, 
    .woocommerce-page p.stars span a.active,
    .ts-tabs-widget .tab-header li.active { color: <?php echo esc_attr($primary_color);?>; }
    .highlight { background-color: rgba(<?php echo esc_attr($primary_color_rgb);?>, .1); color: <?php echo esc_attr($primary_color);?>; }

    <?php
    $top_container_font_color = ts_option_vs_default('top_container_font_color', '#c3c3c3');
    $top_container_link_color = ts_option_vs_default('top_container_link_color', '#aaa');
    $top_container_link_hover_color = ts_option_vs_default('top_container_link_hover_color', '#EE4643');
    ?>
    /* top container */
    .top-stuff-side {  }
    .top-stuff-side a {  }
    .top-stuff-side a:active,
    .top-stuff-side a:focus,
    .top-stuff-side a:hover {  }
    
    
    
    
    <?php
    $logo_font_color = ts_option_vs_default('logo_font_color', '#EE4643');
    $logo_tagline_color = ts_option_vs_default('logo_tagline_color', '#808080');
    $top_bar_text_color = ts_option_vs_default('top_bar_text_color', '#aaa');
    $top_bar_link_color = ts_option_vs_default('top_bar_link_color', '#808080');
    ?>
    /* logo tagline */
    #logo a { color: <?php echo esc_attr($logo_font_color);?>; }
    #logo-tagline { color: <?php echo esc_attr($logo_tagline_color);?>; }
    #top-bar,
    #top-bar > .right-side input { color: <?php echo esc_attr($top_bar_text_color);?>; }
    #top-bar a { color: <?php echo esc_attr($top_bar_link_color);?>; }
    #top-bar bold,
    #top-bar strong { color: <?php echo esc_attr($top_bar_link_color);?>; }
    #top-bar .side a:active,
    #top-bar .side a:focus,
    #top-bar .side a:hover,
    #top-bar .middle-area a { color: <?php echo esc_attr($primary_color);?>; }
    

    <?php
    $main_nav_link_color = ts_option_vs_default('main_nav_link_color', '#444');
    ?>
    /* main nav */
    .main-nav > ul > li > a, 
    .main-nav > div > ul > li > a { color: <?php echo esc_attr($main_nav_link_color);?>; }
    <?php
    $main_nav_hover_color = ts_option_vs_default('main_nav_hover_color', '#EE4643');
    ?>
    .main-nav-wrap #header-social ul li:before,
    .main-nav-wrap #header-social ul li a:hover,
    .main-nav ul > li.menu-item > a:hover,
    .main-nav ul > li.page_item > a:hover,
    .main-nav > ul > li > a:hover, 
    .main-nav > div > ul > li > a:hover, 
    .main-nav > ul > li.current_page_item > a, 
    .main-nav > ul > li.current-menu-item > a,
    .main-nav > div > ul > li.current_page_item > a, 
    .main-nav > div > ul > li.current-menu-item > a,
    .main-nav > ul > li.inuse > a,
    .main-nav > div > ul > li.inuse > a,
    .main-nav > ul > li.current_page_parent > a,
    .main-nav > ul > li.current_page_ancestor > a,
    .main-nav > div > ul > li.current_page_parent > a,
    .main-nav > div > ul > li.current_page_ancestor > a,
    .main-nav > ul > li.current-post-parent > a,
    .main-nav > ul > li.current-post-ancestor > a,
    .main-nav > ul > li.current-category-ancestor > a,
    .main-nav > div > ul > li.current-post-parent > a,
    .main-nav > div > ul > li.current-post-ancestor > a,
    .main-nav > ul > li.current_page_parent > a > .sf-sub-indicator,
    .main-nav > div > ul > li.current_page_ancestor > a > .sf-sub-indicator,
    .main-nav > ul > li.current-post-parent > a > .sf-sub-indicator,
    .main-nav > div > ul > li.current-post-ancestor > a > .sf-sub-indicator,
    .main-nav > div > ul > li.current-category-ancestor > a > .sf-sub-indicator { color: <?php echo esc_attr($main_nav_hover_color);?>; }
    .main-nav > ul > li.menu-item-has-children:hover > a:after, 
    .main-nav > div > ul > li.menu-item-has-children:hover > a:after,
    .main-nav > ul > li.menu-item-has-children.sfHover > a:after, 
    .main-nav > div > ul > li.menu-item-has-children.sfHover > a:after,
    .main-nav.normal > ul > li.current_page_item > a:after, 
    .main-nav.normal > ul > li.current-menu-item > a:after,
    .main-nav.normal > div > ul > li.current_page_item > a:after, 
    .main-nav.normal > div > ul > li.current-menu-item > a:after,
    .main-nav > ul > li.inuse > a:after,
    .main-nav > div > ul > li.inuse > a:after,
    .main-nav > ul > li.current_page_parent > a:after,
    .main-nav > div > ul > li.current_page_ancestor > a:after,
    .main-nav > ul > li.current-post-parent > a:after,
    .main-nav > div > ul > li.current-post-ancestor > a:after,
    .main-nav > ul > li.current-category-ancestor > a:after { background-color:<?php echo esc_attr($main_nav_hover_color);?>; }


    <?php
    $main_nav_submenu_text_color = ts_option_vs_default('main_nav_submenu_text_color', '#808080');
    $main_nav_submenu_link_color = ts_option_vs_default('main_nav_submenu_link_color', '#575757');
    $main_nav_submenu_hover_color = ts_option_vs_default('main_nav_submenu_hover_color', '#EE4643');
    ?>
    /* main nav: sub-menu */
    .main-nav ul ul.children,
    .main-nav ul ul.sub-menu,
    .main-nav ul .main-nav-search-sub-menu,
    .main-nav ul .main-nav-shop-sub-menu { color: <?php echo esc_attr($main_nav_submenu_text_color);?>; }
    .main-nav ul ul.children a,
    .main-nav ul ul.sub-menu a,
    .main-nav ul .main-nav-search-sub-menu a,
    .main-nav ul .main-nav-shop-sub-menu a { color: <?php echo esc_attr($main_nav_submenu_link_color);?>; }    
    .main-nav ul ul li.menu-item-has-children > a:after { border-color: transparent transparent transparent <?php echo esc_attr($main_nav_submenu_link_color);?>; }
    .main-nav ul ul li.menu-item > a:hover,
    .main-nav ul ul li.page_item > a:hover,
    .main-nav ul ul li.current_page_item > a, 
    .main-nav ul ul li.current-menu-item > a { color: <?php echo esc_attr($main_nav_submenu_hover_color);?>; }
    .main-nav ul ul li.menu-item-has-children > a:hover:after { border-color: transparent transparent transparent <?php echo esc_attr($main_nav_submenu_hover_color);?>; }
    .main-nav ul ul.sub-menu del,
    .main-nav ul ul.sub-menu small,
    .main-nav ul ul.sub-menu .small,
    .main-nav ul ul.sub-menu .smaller,
    .main-nav ul ul.sub-menu small a,
    .main-nav ul ul.sub-menu .small a,
    .main-nav ul ul.sub-menu .smaller a,
    .main-nav ul ul.sub-menu .post small a,
    .main-nav ul ul.sub-menu .post .small a,
    .main-nav ul ul.sub-menu .post .smaller a,
    .main-nav ul ul.sub-menu #sidebar small a,
    .main-nav ul ul.sub-menu #sidebar .small a,
    .main-nav ul ul.sub-menu #sidebar .smaller a,
    .main-nav ul ul.sub-menu strike,
    .main-nav ul ul.sub-menu #header-social,
    .main-nav ul ul.sub-menu #header-social ul li a,
    .main-nav ul ul.sub-menu .subtle-text-color,
    .main-nav ul ul.sub-menu .title-bar-caption, 
    .main-nav ul ul.sub-menu .loop .entry .title-info p,
    .main-nav ul ul.sub-menu .widget_rss li .rssSummary,
    .main-nav ul ul.sub-menu ol.commentlist .comment-head,
    .main-nav ul ul.sub-menu .post-single-prev-next a strong,
    .main-nav ul ul.sub-menu .widget_calendar table caption,
    .main-nav ul ul.sub-menu .ts-tabs-widget .tab-header li,
    .main-nav ul ul.sub-menu .ts-tabs-widget .tab-header li:before,
    .main-nav ul ul.sub-menu .ts-searchform button,
    .main-nav ul ul.sub-menu .widget_search button,
    .main-nav ul ul.sub-menu .ts-searchform input[type="submit"],
    .main-nav ul ul.sub-menu .widget_search input[type="submit"],
    .main-nav ul ul.sub-menu #header-social .social .icon-style,
    .main-nav ul ul.sub-menu .social-icons-widget-style .social .icon-style,
    .main-nav ul ul.sub-menu .woocommerce p.stars span a, 
    .main-nav ul ul.sub-menu .woocommerce-page p.stars span a,
    .main-nav ul ul.sub-menu .woocommerce .shop_table .product-name dt { color: <?php echo esc_attr($subtle_text_color);?>; }

    <?php
    $footer_widget_font_color = ts_option_vs_default('footer_widget_font_color', '#808080');
    $footer_widget_headings_color = ts_option_vs_default('footer_widget_headings_color', '#808080');
    $footer_widgets_link_color = ts_option_vs_default('footer_widgets_link_color', '#EE4643');
    $copyright_link_color = ts_option_vs_default('copyright_link_color', '#fff');
    $copyright_text_color = ts_option_vs_default('copyright_text_color', '#fff');
    ?>
    /* footer colors */
    #footer-copyright-wrap { color: <?php echo esc_attr($footer_widget_font_color);?>; }
    #footer a,
    #footer a:active,
    #footer a:focus,
    #footer a:hover,
    #footer .ts-tabs-widget .tab-header li.active { color: <?php echo esc_attr($footer_widgets_link_color);?>; }
    #footer h1,
    #footer h2,
    #footer h3,
    #footer h4,
    #footer h5,
    #footer h6,
    #footer .ts-tabs-widget .tab-header li { color: <?php echo esc_attr($footer_widget_headings_color);?>; }
    #copyright-nav { color: <?php echo esc_attr($copyright_text_color);?>; }
    #copyright-nav a,
    #copyright-nav a:active,
    #copyright-nav a:focus,
    #copyright-nav a:hover { color: <?php echo esc_attr($copyright_link_color);?>; }
    #copyright-nav ul li { border-color: <?php echo esc_attr($copyright_text_color);?>; }

    <?php
    $woocommerce_price_color = ts_option_vs_default('woocommerce_price_color', '#7ac142');
    ?>
    /* woocommerce price */
    .woocommerce .price, 
    .woocommerce-page .price,
    .woocommerce div.product span.price, 
    .woocommerce-page div.product span.price, 
    .woocommerce #content div.product span.price, 
    .woocommerce-page #content div.product span.price, 
    .woocommerce div.product p.price, 
    .woocommerce-page div.product p.price, 
    .woocommerce #content div.product p.price, 
    .woocommerce-page #content div.product p.price,
    .woocommerce ul.products li.product .price, 
    .woocommerce-page ul.products li.product .price,
    .product.woocommerce span.amount,
    .woocommerce div.product .stock, 
    .woocommerce-page div.product .stock, 
    .woocommerce #content div.product .stock, 
    .woocommerce-page #content div.product .stock { color: <?php echo esc_attr($woocommerce_price_color);?>; }


    /* BORDERS / BORDER COLORS (and other relevant items)
    ================================================== */

    /* primary color */
    .ts-pricing-column.featured,
    button.outline,
    .button.outline,
    #button.outline,
    .loop .post-category-heading a,
    #ts-moon-comment-bubble:hover { border-color: <?php echo esc_attr($primary_color);?>; }
    .loop-slider-wrap .ts-item-details .comment-bubble:after,
    #ts-moon-comment-bubble:hover:after { border-top-color: <?php echo esc_attr($primary_color);?>; }
    

    /* standard border color */
    .ts-top,
    #top-bar-wrap,
    #top-bar > .right-side,
    #top-bar > .middle-area { border-color: <?php echo esc_attr($standard_border_color);?>; }
    #top-bar > .middle-area .menu > li.menu-item-has-children:after { border-top-color: <?php echo esc_attr($standard_border_color);?>; }
    #top-bar > .middle-area .menu > li.menu-item-has-children:hover:after { border-top-color: <?php echo esc_attr($primary_color);?>; }
    #top-bar > .middle-area .menu li ul li { border-color: <?php echo esc_attr($standard_border_color);?>; }
    #top-bar > .middle-area .menu li ul li:first-child { border-top-color: <?php echo esc_attr($standard_border_color);?>; }
    
    
    #ts-moon-comment-bubble { border-color: <?php echo esc_attr($standard_border_color);?>; }
    #ts-moon-comment-bubble:after { border-top-color: <?php echo esc_attr($standard_border_color);?>; }
    
    
    
    @media only screen and (max-width: 920px) {
        #main-nav,
        .main-nav > ul > li,
        .main-nav > div > ul > li { border-color: <?php echo esc_attr($standard_border_color);?>; }
        .main-nav ul ul.sub-menu,
        .main-nav ul ul.children { border-top-color: <?php echo esc_attr($standard_border_color);?>; }
    }
    
    .ts-progress-bar-wrap ,
    body.single-post #ts-post-wrap .dem-tags .sep { background-color: <?php echo esc_attr($standard_border_color);?>; }
    
    
    #ts-top-mobile-menu,
    .main-nav > ul > li > a,
    .main-nav > div > ul > li > a, 
    .main-nav > ul > li > .sub-menu,
    .main-nav > div > ul > li > .sub-menu,
    .main-nav > ul > li > .children,
    .main-nav > div > ul > li > .children,
    .main-nav ul ul > li.menu-item,
    .main-nav > div ul ul > li.menu-item,
    .main-nav ul ul > li.page_item,
    .main-nav > div ul ul > li.page_item,
    #ts-main-nav-inner-wrap.stickied { background-color: <?php echo esc_attr($content_bg);?>; }
    
    .ts-progress-bar-wrap,
    body.single-post #ts-post-wrap .dem-tags .sep { background-color: <?php echo esc_attr($standard_border_color);?>; }
    
    hr,
    abbr,
    acronym { border-bottom-color: <?php echo($standard_border_color);?>; }
    
    .main-nav-shop-link a,
    
    #header-social .social .icon-style,
    .social-icons-widget-style .social .icon-style,
    body.has-no-footer-widgets #copyright-nav,

    #top-bar-wrap,
    #top-bar-wrap:before,
    #top-bar-wrap.three-part:after,

    #main-slider-wrap.full,

    #ts-news-ticker-wrap,
    #title-bar-wrap,
    body.single-post #ts-post-the-content .ts-gallery-wrapper,
    
    ol.commentlist .comment-avatar img,
    #main .single-entry .ts-about-author .avatar-img-link img,
    .post-widget-comment .widget-thumbnail .thumb-link img,
    
    .has-sidebar-comments-left .single-entry #ts-comments-wrap-wrap,
    .has-sidebar-comments-right .single-entry #ts-comments-wrap-wrap,
    
    #ts-post-comments-share-wrap,
    .search-result-caption,

    .loop .entry .read-more-wrap,
    .loop-default .featured-media-wrap,
    .loop-widget .entry,
    .single-portfolio .post-single-prev-next,

    .ts-post-section-inner,
    #ts-post-featured-media-wrap,

    .pagination a,
    .pagination span,
    .page-links .wp-link-pages > a,
    .page-links .wp-link-pages > span,
    .masonry-cards .post-content .read-more,

    .wp-caption,
    .gallery .gallery-item .gallery-icon img,
    .traditional-tabs.horizontal-tabs .tab-header li.active,
    .widget li,
    .wpmega-widgetarea .widget li,
    .ubermenu-widget-area .widget li,
    .widget .tagcloud a, 
    .post-tags a,
    .widget .tab-header,
    .widget_calendar table td, 
    .widget_calendar table th,
    .widget .tab-header,
    .divider-shortcode.line .divider,
    .divider-shortcode.dotted .divider,
    .divider-shortcode.dashed .divider,
    .divider-shortcode.double-line .divider,
    .divider-shortcode.double-dotted .divider,
    .divider-shortcode.double-dashed .divider,
    .divider-shortcode .divider-sep,
    .divider-shortcode .shapes .ts-circle,
    .divider-shortcode .shapes .ts-square,
    .title-shortcode .title-sep,
    .title-shortcode.dashed .title-sep,
    .title-shortcode.dotted .title-sep,
    .title-shortcode.double-line .title-sep,
    .title-shortcode.double-dashed .title-sep,
    .title-shortcode.double-dotted .title-sep,
    .title-shortcode.underline-full .title-shortcode-htag,
    .title-shortcode.underline-text .title-shortcode-htag span,

    .vertical-tabs ul.tab-header li,
    .vertical-tabs ul.tab-header li:first-child,
    .traditional-tabs.vertical-tabs .tab-contents,
    .traditional-tabs.vertical-tabs ul.tab-header li,
    .simple-tabs.vertical-tabs-left .tab-contents,
    .simple-tabs.vertical-tabs-right .tab-contents,

    .toggle-block,
    .accordion-block
    .toggle-block .tab-body,
    .accordion-block .tab-body,
    .toggles-wrapper .accordion-block,
    .accordion-wrapper .accordion-block,
    .tagline-shortcode,
    .ts-pricing-column,
    .ts-pricing-column ul li,

    .ts-blockquote-shortcode.pull-left,
    .ts-blockquote-shortcode.pull-right,

    .ts-loop-product-title,
    .woocommerce-page div.product #reviews .comment img,
    .woocommerce #content div.product #reviews .comment, 
    .woocommerce div.product #reviews .comment, 
    .woocommerce-page #content div.product #reviews .comment, 
    .woocommerce-page div.product #reviews .comment,
    .woocommerce-info,
    .woocommerce-message,
    .woocommerce table.shop_table tr.cart_item, 
    .woocommerce-page table.shop_table tr.cart_item,
    .woocommerce .tab-context .shop_attributes tr,
    .woocommerce .tab-context .shop_attributes th,
    #sidebar .widget > h5,
    #sidebar .widget .page-title h5,
    #sidebar .widget > h5 > span,
    #sidebar .widget .page-title h5 > span,
    #footer .widget > h5 > span,
    #footer .widget .page-title h5 > span { border-color: <?php echo esc_attr($standard_border_color);?>; }
    
    <?php
    $footer_widget_border_color = ts_option_vs_default('footer_widget_border_color', '#e5e5e5');
    ?>
    /* footer widget border color */
    #footer-copyright-wrap,
    #footer .widget *,
    #bottom-ad-inner,
    #copyright-nav,
    #footer-copyright-wrap * { border-color: <?php echo esc_attr($footer_widget_border_color);?>; }
    




    /* FORM ELEMENT COLORS 
    ================================================== */
    
    <?php
    $form_font_color = ts_option_vs_default('form_font_color', '#808080');
    $form_background_color = ts_option_vs_default('form_background_color', '#eee');
    ?>
    .woocommerce .select2-container .select2-choice,
    .woocommerce-page .select2-drop-active,
    .wp-editor-container,
    input.input-text, 
    input[type="text"], 
    input[type="search"], 
    input[type="email"], 
    input[type="password"],
    input[type="number"],
    input[type="tel"], 
    input[type="url"], 
    textarea, 
    select { 
        background-color: <?php echo esc_attr($form_background_color);?>;
        color: <?php echo esc_attr($form_font_color);?>;
    }
    .ts-searchform button { color: <?php echo esc_attr($form_font_color);?> !important; }

    <?php
    $footer_form_font_color = ts_option_vs_default('footer_form_font_color', '#808080');
    $footer_form_background_color = ts_option_vs_default('footer_form_background_color', '#eee');
    ?>
    #footer input[type="text"], 
    #footer input[type="search"], 
    #footer input[type="email"], 
    #footer input[type="password"],
    #footer input[type="number"],
    #footer input[type="tel"], 
    #footer input[type="url"], 
    #footer textarea, 
    #footer select { 
        background-color: <?php echo esc_attr($footer_form_background_color);?>;
        color: <?php echo esc_attr($footer_form_font_color);?>;
    }
    #footer .ts-searchform button { color: <?php echo esc_attr($footer_form_font_color);?> !important; }
    
    
    /*======================================================================== 
                                #STANDARD COLORS
                                - borders
                                - backgrounds
                                - text
                                - buttons
    =========================================================================*/
    .border-standard { border-color: <?php echo esc_attr($standard_border_color);?> !important; }
    .border-primary { border-color: <?php echo esc_attr($primary_color);?> !important; }

    /* Begin Background Colors */
    .bg-primary { background-color: <?php echo esc_attr($primary_color);?> !important; }

    /* Begin Text Colors */
    .primary-color, 
    .color-shortcode.primary,
    .color-primary { color: <?php echo esc_attr($primary_color);?> !important; }

    /* Begin Button Colors */
    .button.default,
    .button.primary {
        background-color: <?php echo esc_attr($primary_color);?> !important;
    }

<?php
    }
}