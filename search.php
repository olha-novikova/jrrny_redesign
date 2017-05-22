<?php
/**
 * The search template file.
 */
global $smof_data;

$ts_search_term = (isset($_GET['s'])) ? ts_trim_text($_GET['s'], 20) : __('undefined', 'ThemeStockyard');
$ts_page_title = __('Search results', 'ThemeStockyard');

$ts_page_id = get_option('page_for_posts');

$ts_show_sidebar = (ts_option_vs_default('show_search_sidebar', 1) != 1) ? 'no' : 'yes';

$ts_sidebar_position = ts_option_vs_default('page_sidebar_position', 'right');

$ts_caption = '';
get_header();
get_template_part('top');
get_template_part('title-page');
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')); ?>">
    <div id="main-container" class="clearfix">
        <div id="" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
            <div class="entry single-entry clearfix">
                <div class="post">
                    <?php
                    /*
                     * Run the loop to output the posts.
                     */
                    if (isset($_GET['t'])) {
                        global $wpdb;
                        $page = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

                        $post_per_page = intval(12);
                        $offset = ( $page - 1 ) * $post_per_page;

                        $search_term = esc_attr(trim(get_query_var('s')));
                        
                        $sql = "SELECT u.ID FROM wp_n13mz40_users AS u
                            JOIN wp_n13mz40_usermeta AS m1 ON (m1.user_id = u.ID AND m1.meta_key = 'first_name')
                            JOIN wp_n13mz40_usermeta AS m2 ON (m2.user_id = u.ID AND m2.meta_key = 'last_name')
                            WHERE u.user_login LIKE '" . esc_attr($search_term) . "' OR 
                            u.user_email LIKE '" . esc_attr($search_term) . "' OR 
                            u.user_url LIKE '" . esc_attr($search_term) . "' OR 
                            m1.meta_value LIKE '" . esc_attr($search_term) . "' OR 
                            m2.meta_value LIKE '" . esc_attr($search_term) . "' 
                            ORDER BY u.user_login 
                            LIMIT " . $offset . "," .$post_per_page;    
                        
                        $users_found = $wpdb->get_results($sql);
                        //$sql_posts_total = $wpdb->get_var( "SELECT FOUND_ROWS();" );
                        //$max_num_pages = ceil($sql_posts_total / $post_per_page);
                        ?>
                        <div class="posts personal-posts ts-people-wrap container padding-bottom-30 padding-top-30">
                            <div class="row">
                            <?php
                            $i = 0;
                            foreach ($users_found as $usr) :
                                $user = get_userdata(intval($usr->ID));
                                $firstName = $user->first_name;
                                $lastName = $user->last_name;
                                $name = ($firstName && $lastName ? $firstName . '&nbsp;' . $lastName : $user->user_nicename);
                                $location = get_user_meta($usr->ID, '_user_location', true);
                                $city = (isset($location['city'])) ? $location['city'] : '';
                                $country = (isset($location['country'])) ? $location['country'] : '';
                                $country_full = country_full_name($country);
                                $description = get_user_meta($usr->ID, 'description', true);
                                $address = ($city ? $city . ',&nbsp;' . $country_full : $country_full);
                                ?>
                                <div class="ts-person ts-boxed-1-of-4 text-center followed-author" id="jrrny-author-<?= $usr->ID ?>">
                                    <div class="image">
                                        <a class = "round_100pct" href="<?php echo get_author_posts_url($usr->ID); ?>">
                                            <?php echo get_avatar($usr->ID, 256, '', $user->user_login);?>
                                        </a>
                                    </div>
                                    <h3 class="name">
                                        <a href="<?php echo get_author_posts_url($usr->ID); ?>"><?= $name; ?></a>
                                    </h3>
                                    <p class="smaller uppercase subtitle"><?= $address; ?></p>
                                    <div class="description">
                                        <p><?= $description ?></p>
                                    </div>
                                    <p class="mimic-smaller uppercase">
                                        <a href="<?= get_author_posts_url($usr->ID); ?>">view profile</a>
                                        <?php
                                        $following = '0';
                                        $following_class = '';
                                        $following_text = 'follow';

                                        if (is_follow($usr->ID)) {
                                            $following = '1';
                                            $following_class = 'disabled';
                                            $following_text = 'following';
                                        }
                                        $following_class .= (is_user_logged_in()) ? ' ' : ' login_modal';
                                        ?>

                                        <a href="#"    data-author="<?php echo $usr->ID; ?>"   data-following="<?php echo $following; ?>"
                                           class="btn btn-follow-loop meta-item-follow <?php echo $following_class; ?>">
                                            <?php echo $following_text; ?>
                                        </a>
                                    </p>
                                </div>
                            <?php
                            $i++;   if ($i == 4){ echo "</div><div class=\"row\">"; $i = 0; }
                            endforeach; ?>
                        </div>
                        <?php
                        if (function_exists("wp_bootstrap_pagination") && count($users_found) > 1) {
                            //wp_bootstrap_pagination(array('total' => $max_num_pages));
                            wp_bootstrap_pagination();
                        }
                        ?>
                        <?php
                    }
                    else {
                        if (get_query_var('post_type') == 'portfolio') :
                            ts_blog_loop('3column', array('default_query' => true, 'is_search' => true, 'show_category'=>false, 'show_excerpt' => false, 'posts_per_page' => -1));
                        else :
                            ts_blog_loop('3column', array('layout' => '3column', 'default_query' => true, 'pid' => $ts_page_id, '_dq' => true, 'is_search' => true, 'show_category'=>"false", 'show_excerpt' => "false",  'infinite_scroll'=> false, 'show_pagination'=> true, 'must_have_media'=> true));
                        endif;
                    }
                    ?>
                </div>
            </div>                        
        </div>

        <?php ts_get_sidebar(); ?>

    </div><!-- #main-container -->
</div><!-- #main-container-wrap -->

<?php get_footer(); ?>
