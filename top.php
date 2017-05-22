<?php
global $smof_data, $ts_top_ad, $woocommerce, $ts_page_id, $current_user;

$logo = ts_option_vs_default('logo_upload', '');
$site_name = esc_attr(get_bloginfo('description')) . ' - ' . ts_option_vs_default('logo_text', get_bloginfo('name'));
?>
<nav class="navbar navbar-jrrny" role="navigation">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="<?php echo home_url(); ?>" title="<?php echo $site_name; ?>">
                <?php if ($logo) { ?>
                    <img src="<?php echo $logo; ?>" alt="<?php echo $site_name; ?>" class="img img-responsive" <?php echo $logo_xs ? 'srcset="' . $logo_xs . ' 640w"' : ''; ?>>                    
                    <?php
                }
                else {
                    echo $site_name;
                }
                ?>
            </a>
        </div>
        <div id="main_menu">

                <ul id="menu-left" class="nav navbar-nav">
                    <?php echo plc_nav('left'); ?>
                </ul>
                <ul id="menu-right" class="nav navbar-nav navbar-right">
                    <?php echo plc_nav('right'); ?>
                </ul>

        </div>
    </div>
</nav>
<?php //if ( !is_page(533) && !is_page('map') && !is_post_type_archive('featured_destination') && !is_singular('featured_destination') && !is_singular('post') && !is_singular('sponsored_post') && !is_singular('page') && !is_author() && !(isset($_GET["action"]) && $_GET["action"] == "edit") ): ?>
<!--    <div id="header-search-bar">-->
<!--        <div class="container">-->
<!--            <form action="--><?php //echo esc_url(home_url('/')); ?><!--" class="col-xs-12 col-sm-8 col-sm-push-2" method="get" role="search">-->
<!--                <div class="input-group">-->
<!--                    <input class="form-control" type="text" name="s" value="--><?php //echo esc_attr(get_search_query()); ?><!--" placeholder="Search for...">-->
<!--                    <span class="input-group-btn">-->
<!--                        <button type="submit" class="btn btn-blue">Location</button>-->
<!--                        <button type="submit" value="u" name="t" class="btn btn-blue-border">User</button>-->
<!--                    </span>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
<?php //endif; ?>
<?php if (is_page(533)): ?> 
    <div id="top-wrap" class="<?php echo ts_top_class(); ?>">   
        <div class="header-table">
            <div class="header-cell">
                <div class="hompage-header-wrapper">    
                    <div class="container">
                            <?php
                            if (is_user_logged_in()) {?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="btn-groups title" role="group">
                                            <span>I WANT TO</span> <button id="full_search_btn" class="btn btn-blue-border">Discover</button><span> OR </span><a href="<?php echo home_url(); ?>/upload" class="btn btn-blue">Share</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                //get_template_part("inc/frontend/profile/user-widget", 'homepage');
                            }
                            else {
                                ?>
                                <div class="row">
                                    <div class="col-xs-12 title">
                                        Travel Everywhere, Share Your Story Here
                                    </div>
                                    <div class="col-xs-12 subtitle">
                                        Discover, Share and More
                                    </div>
                                    <div class="col-xs-12 description">
                                        <div class="btn-groups" role="group">
                                            <button id="full_search_btn" class="btn btn-blue-border">Discover</button>
                                            <a href="<?php echo home_url(); ?>/upload" class="btn btn-blue login_modal">Share</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                    </div>                                            
                </div>
            </div>
        </div>  
    </div>
    <div id="full_search">
        <button type="button" class="close">×</button>
        <div class="full-search-wrap">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" value="" name="s" placeholder="Search for..." />
                <div class="center">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-blue">Location</button>
                        <button type="submit" value="u" name="t" class="btn btn-blue-border">User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>
<?php

if (is_user_logged_in() && current_user_can('edit_user', $current_user->ID)) {
    get_template_part('inc/frontend/profile/edit-form', 'homepage');
}

if ( is_front_page() && !is_user_logged_in() &&  !is_page_template('advertise.php') ){

}else
    get_template_part('inc/frontend/profile/user-bar');
?>