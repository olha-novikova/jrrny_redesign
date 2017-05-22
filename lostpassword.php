<?php
/*
Template Name: Lost Password
*/
if(is_user_logged_in()):
	wp_redirect(home_url(), '301');
	exit;
endif;
get_header();
get_template_part('top');
get_template_part('inc/frontend/lostpassword/form-lostpassword');
get_footer();
?>