<?php
if (!is_primary(get_the_ID())) {
    if (!is_user_logged_in()) {
        $redirect = get_site_url() . '?redirect=' . urldecode(get_site_url() . $_SERVER['REQUEST_URI']).'#login-form';
        wp_redirect($redirect);
        exit;
    }
}

$template = get_post_meta($post->ID, 'template', true);

get_header();
get_template_part('top');
if($template === 'alaska_airlines'){
    get_template_part("inc/frontend/single/single", $template);  
}
else{
    get_template_part("inc/frontend/single/single", 'sponsored_post');    
}
get_footer();
?>
</body>
</html>
