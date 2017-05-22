<?php
global $current_user;
wp_get_current_user();
get_header(); 
get_template_part('top');


if(isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
} else {
    $curauth = get_userdata(intval($author));
}

if (is_user_in_role($curauth->ID, 'brand')){

     get_template_part("inc/frontend/author/brand");
}
else{
    if (isset($_GET['all'])){
        get_template_part("inc/frontend/author/author", 'all');
    }else{
        get_template_part("inc/frontend/author/author", 'new');
    }
}
get_footer();
