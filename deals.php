<?php
/*
Template Name: Deals
*/
get_header();
get_template_part('top'); ?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
    <?php if (have_posts()) :
            while (have_posts()) : the_post(); ?>
                <div id="main-container" class="container clearfix">
                    <div id="main" class="<?php echo esc_attr(ts_main_div_class());?> clearfix">
                        <div class="entry single-entry clearfix">
                            <div id="ts-post-content-sidebar-wrap" class="clearfix">
                                <div id="ts-post-wrap">
                                    <div id="ts-post" <?php post_class('post clearfix'); ?>>
                                        <div id="ts-post-the-content-wrap">
                                            <div id="ts-post-the-content">
                                                <h3><?php the_content(); ?></h3><?php
                                                wp_link_pages('before=<div class="page-links">'.__('Pages: ', 'ThemeStockyard').'<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <form method="post" action="" id="subscribe-form">
                                <div class="input-group">
                                    <span class="input-group-addon" id="email-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" placeholder="Email" name="email" id="email" class="form-control" aria-describedby="email-addon" />
                                </div>
                                <div class="input-group">
                                    <a class="btn button" id="subscribe-btn">Subscribe</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- #main-container -->
        <?php endwhile;
    endif; ?>
</div>
<?php get_footer(); ?>