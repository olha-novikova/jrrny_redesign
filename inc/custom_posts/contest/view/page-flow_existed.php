<?php
$post_id = $post->ID;

$logo = get_post_meta($post_id, 'logo', true);
$logo_attr = wp_get_attachment_image_src($logo, 'full');
$step_1_left_content = get_post_meta($post_id, 'step_1_left_content', true);
$step_1_right_content = get_post_meta($post_id, 'step_1_right_content', true);
$step_2_share = get_post_meta($post_id, 'step_2_share', true);
$step_3_final_page_header = get_post_meta($post_id, 'step_3_final_page_header', true);
$step_3_final_page_left_content = get_post_meta($post_id, 'step_3_final_page_left_content', true);

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
    <input id="contest" name="contest" value="<?php echo $post_id; ?>" type="hidden"/>
    <input id="referral_user_id" name="referral_user_id" value="<?php echo $referral_user_id; ?>" type="hidden"/>
    <input id="referral_url" name="referral_url" value="<?php echo $referral_url; ?>" type="hidden"/>
    <div class="content-cell">
        <div class="content-container">
            <div class="logo">
                <a href="<?php echo site_url(); ?>"><img src="<?php echo $logo_attr[0]; ?>" alt="logo"/></a>
            </div>
            <div class="content" id="wizzard">
                <div id="step_1" class="row">
                    <div class="col-md-6">
                        <?php echo apply_filters('the_content', $step_1_left_content); ?>                        
                        <div class="form-group info hidden-xs hidden-sm">
                            <p class="help-block">By joining jrrny, you agree to our <a href="<?php echo home_url(); ?>/terms.pdf"><strong>TERMS OF SERVICE</strong></a> and <strong><a href="<?php echo home_url(); ?>/privacy.pdf">PRIVACY POLICY</strong></a></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php echo apply_filters('the_content', $step_1_right_content); ?> 
                        <?php
                        if (is_user_logged_in()) {
                            ?>
                            <div class="wizzar-contest-user-logged">
                                <p>You're just about to enter this contest, if you like to continue click enter button</p>
                                <button id="add-to-contest" class="btn btn-blue">ENTER&nbsp;<i class="fa processing-icon hide"></i></button>
                            </div>
                        <?php
                        }
                        else {
                            get_template_part('inc/frontend/login/form', 'newsignup-noinfo');
                        }
                        ?>   
                        <div class="form-group info visible-xs visible-sm">
                            <p class="help-block">By joining jrrny, you agree to our <a href="<?php echo home_url(); ?>/terms.pdf"><strong>TERMS OF SERVICE</strong></a> and <strong><a href="<?php echo home_url(); ?>/privacy.pdf">PRIVACY POLICY</strong></a></p>
                        </div>
                    </div>
                </div>
                <div id="step_2" class="hidden">
                    <?php echo apply_filters('the_content', $step_2_share); ?>   
                    
                </div>
                <div id="step_3" class="row hidden">
                    <div class="col-xs-12">
                        <?php echo apply_filters('the_content', $step_3_final_page_header); ?>
                    </div>
                    <div class="col-md-5">
                        <?php echo apply_filters('the_content', $step_3_final_page_left_content); ?>
                    </div>
                    <div class="col-md-7">   
                        <?php get_template_part('inc/frontend/upload/upload', 'small-widget'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
