<?php
/*
Template Name: Login
*/
if(is_user_logged_in()){
	global $current_user;
	wp_get_current_user();
	$redirectUrl = home_url() . '/author/' . $current_user->user_login;
	wp_redirect($redirectUrl, '301');
	exit;
}
get_header();
get_template_part('top');
?>

<div class="search-jrrny-holder">
    <div class="container no-padding">
        <div class="row">
            <div id="singup-form-wrapper">
                <?php get_template_part('inc/frontend/login/form', 'newsignup'); ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>