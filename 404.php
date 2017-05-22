<?php
global $smof_data, $ts_page_id;

$ts_page_title = (isset($smof_data['title_404'])) ? $smof_data['title_404'] : __('Page not found', 'ThemeStockyard');
$ts_show_title_bar_override = 'no';

get_header(); 
get_template_part('top');
get_template_part('title-page');
?>
            <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page'));?>">
                <div id="main-container" class="container clearfix">
                    <div id="main" class="clearfix no-sidebar">
                        <div class="entries">
                            <div class="entry single-entry">
                                <div class="post">
                                    <div class="container">
                                        <div class="ts-row">
                                            <div class="span6">
                                                <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help...', 'ThemeStockyard' ); ?></p>
                                                <p><?php get_search_form(); ?></p>
                                            </div>
                                            <div class="span6">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo do_shortcode('[divider style="line" padding_top="30px" padding_bottom="30px"]');?>
                                </div>
                                <!----<div class="mimic-post">
                                    <div class="container">
                                        <div class="ts-row">
                                            <div class="span4">
                                                <h3><?php //_e('Pages','ThemeStockyard');?></h3>
                                                <ul><?php //wp_list_pages(array('title_li'=>''));?></ul>
                                            </div>
                                            <div class="span4">
                                                <h3><?php //_e('Categories','ThemeStockyard');?></h3>
                                                <ul><?php //wp_list_categories(array('title_li'=>''));?></ul>
                                            </div>
                                            <div class="span4">
                                                <h3><?php // _e('Authors','ThemeStockyard');?></h3>
                                                <ul><?php //wp_list_authors();?></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>--------->
                            </div>                            
                        </div>
                    </div>
                </div><!-- #main-container -->
            </div><!-- #main-container-wrap -->
            
<?php get_footer(); ?>
