<?php
$post_id = $post->ID;

$page_flow = get_post_meta($post_id, 'page_flow', true);

$fb_title = get_post_meta($post_id, 'fb_title', true);
$fb_description = get_post_meta($post_id, 'fb_description', true);
$fb_image = get_post_meta($post_id, '_webdados_fb_open_graph_specific_image', true);


$contest_end_date = get_post_meta($post_id, 'contest_end_date', true);
if($contest_end_date <= date('Ymd')){
    $page_flow = 'ended';
}

remove_action('wp_head', 'webdados_fb_open_graph', 9999);
add_action('wp_head', 'fb_tags', 2);

function fb_tags()
{
    global $fb_title, $fb_description, $fb_image, $post;
    $current_url = get_permalink($post->ID);
    $html = '
        <link rel="canonical" href="' . $current_url . '" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="' . $fb_title . '" />
        <meta property="og:description" content="' . $fb_description . '"/>
        <meta property="og:url" content="' . $current_url . '" />
        <meta property="og:site_name" content="Jrrny" />
        <meta property="og:image" content="' . $fb_image . '" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="' . $fb_title . '" />
        <meta name="twitter:image" content="' . $fb_image . '" />';
    echo $html;
}

add_filter('wp_title', 'wp_head_title');

function wp_head_title($title)
{
    global $fb_title;

    return $fb_title;
}

wp_register_script($page_flow . '-js', get_stylesheet_directory_uri() . '/inc/custom_posts/contest/js/' . $page_flow . '.js', array('jquery'), '0.0.1');
wp_enqueue_script($page_flow . '-js');

get_header();
get_template_part("inc/custom_posts/contest/view/page", $page_flow);
?>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
