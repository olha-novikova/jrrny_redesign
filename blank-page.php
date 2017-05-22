<?php
/*
  Template Name: Blank Page
 */
get_header();
get_template_part('top');
?>
<?php if (post_password_required()) : ?>
    <?php get_the_password_form(); ?>
    <?php else : ?>
    <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
        <?php if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <div id="main-container" class="container clearfix">
                    <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
                        <div class="entry single-entry clearfix">
                            <div id="ts-post-content-sidebar-wrap" class="clearfix">
                                <div id="ts-post-wrap">
                                    <div id="ts-post" <?php post_class('post clearfix'); ?>>
                                        <div id="ts-post-the-content-wrap">
                                            <div id="ts-post-the-content">
                                                <h3><?php the_content(); ?></h3><?php
                                    wp_link_pages('before=<div class="page-links">' . __('Pages: ', 'ThemeStockyard') . '<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>');
                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- #main-container -->
        <?php endwhile;
    endif;
    ?>
    </div>
<?php endif; ?>
<?php
get_footer();
