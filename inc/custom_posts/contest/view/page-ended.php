<?php
$post_id = $post->ID;

$page_flow = get_post_meta($post_id, 'page_flow', true);

$logo = get_post_meta($post_id, 'logo', true);
$logo_attr = wp_get_attachment_image_src($logo, 'full');
$step_1_left_content = get_post_meta($post_id, 'step_1_left_content', true);
$step_1_right_content = get_post_meta($post_id, 'step_1_right_content', true);
$step_2 = get_post_meta($post_id, 'step_2', true);
$step_3 = get_post_meta($post_id, 'step_3', true);
$step_4 = get_post_meta($post_id, 'step_4', true);

$fb_title = get_post_meta($post_id, 'fb_title', true);
$fb_description = get_post_meta($post_id, 'fb_description', true);
$fb_image = get_post_meta($post_id, '_webdados_fb_open_graph_specific_image', true);

$tw_description = get_post_meta($post_id, 'tw_description', true);


$email_content = get_post_meta($post_id, 'email_content', true);
$email_content_autoreply = get_post_meta($post_id, 'email_content_autoreply', true);

$referral_user_id = 0;
$ref = isset($_GET['ref']) ? $_GET['ref'] : '';

if ($ref) {
    $ref = explode('xyz', $ref);
    $referral_user_id = isset($ref[1]) ? $ref[1] : 0;
}

$ref = $_SERVER['HTTP_REFERER'];
$referral_url = $ref ? $ref : '';

$bg_image = '';
if (has_post_thumbnail()) {
    $bg_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
}
?>

<div<?php echo isset($bg_image[0]) ? " style=\"background-image: url('" . $bg_image[0] . "');\"" : ''; ?> class="contest-wrapper">     
    <div class="content-cell <?php echo $page_flow;?>">
        <div class="content-container">
            <div class="logo">
                <a href="<?php echo site_url(); ?>"><img src="<?php echo $logo_attr[0]; ?>" alt="logo"/></a>
            </div>
            <div class="content" id="wizzard">
                <div id="step_1" class="row">
                    <div class="col-xs-12 text-center">
                        <?php echo apply_filters('the_content', $step_1_right_content); ?> 
                        <h3>This contest is ended</h3><br/>
                        <p><a href="/" class="btn btn-lg btn-blue"><i class="fa fa-home"></i> HOME</a></p>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
