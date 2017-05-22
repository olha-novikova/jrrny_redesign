<?php
/*-----------------------------------------------------------------------------------*/
/* Register the theme's widget areas
/*-----------------------------------------------------------------------------------*/
function ts_register_sidebars()
{
    if (function_exists('register_sidebar')) {
        register_sidebar(array(
            'name'          => __('Primary Sidebar', 'ThemeStockyard'),
            'description'   => __('Default content sidebar on pages/posts.', 'ThemeStockyard'),
            'id'            => 'ts-primary-sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
            'after_title'   => '</span></h5></div>'
        ));


        
        $footer_sidebar_layout = ts_option_vs_default('footer_layout', 'footer2');
        switch ($footer_sidebar_layout) {
            case "footer1":
                register_sidebar(array(
                    'name'          => __('Footer Area 1', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-1',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 2', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-2',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 3', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-3',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 4', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-4',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                break;

            case "footer2":
            case "footer5":
            case "footer6":
                register_sidebar(array(
                    'name'          => __('Footer Area 1', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-1',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 2', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-2',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 3', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-3',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                break;

            case "footer3":
            case "footer7":
            case "footer8":
                register_sidebar(array(
                    'name'          => __('Footer Area 1', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-1',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                
                register_sidebar(array(
                    'name'          => __('Footer Area 2', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-2',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                break;

            default:
            case "footer4":
                register_sidebar(array(
                    'name'          => __('Footer Area 1', 'ThemeStockyard'),
                    'id'            => 'ts-footer-sidebar-1',
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<div class="page-title clearfix"><h5 class="mimic-small uppercase subtle-text-color"><span>',
                    'after_title'   => '</span></h5></div>'
                ));
                break;

       }
    }
}