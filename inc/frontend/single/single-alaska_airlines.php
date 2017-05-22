<?php

$hero_image = get_post_meta($post->ID, 'hero_image', true);
$hero_image_attr = wp_get_attachment_image_src($hero_image, 'full');
$hero_logo = get_post_meta($post->ID, 'hero_logo', true);
$hero_logo_attr = wp_get_attachment_image_src($hero_logo, 'full');

$signup_image = get_post_meta($post->ID, 'signup_image', true);
$signup_image_attr = wp_get_attachment_image_src($signup_image, 'full');

$left_content = get_post_meta($post->ID, 'left_content', true);
$right_content = get_post_meta($post->ID, 'right_content', true);

$hero_post = get_post_meta($post->ID, 'hero_post', true);
$jrrnys = get_post_meta($post->ID, 'jrrnys', true);

$sponsor_logo = get_post_meta($post->ID, 'sponsor_logo', true);
$sponsor_logo_size = get_post_meta($post->ID, 'sponsor_logo_size', true);
if(!$sponsor_logo_size){
    $sponsor_logo_size = 'medium';    
}
$sponsor_images = get_post_meta($post->ID, 'sponsor_images', true);
$sponsor_url = get_post_meta($post->ID, 'sponsor_url', true);
$sponsor_description = get_post_meta($post->ID, 'sponsor_description', true);
if (isset($sponsor_url) && $sponsor_url != '' && empty(parse_url($sponsor_url)["scheme"])):
    $sponsor_url = "http://" . $sponsor_url;
endif;
$isAdsense = get_post_meta($post->ID, 'adsense', true);

$contest = get_post_meta($post->ID, 'contest', true);

?>
<div class="clearfix"></div>
<div id="alaska-header" style="background-image: url('<?php echo $hero_image_attr[0]; ?>');">    
    <div class="container-fluid">
        <div class="row vertical-align">
            <div class="col-xs-12 col-sm-3">
                <div class="alaska-logo">
                    <img src="<?php echo $hero_logo_attr[0]; ?>" class="img img-responsive" alt="<?php echo get_the_title() ?>"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">   
                <div class="content">
                    <?= apply_filters('the_content', $post->post_content) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="alaska-extra-content">    
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 left_content">
                <?= apply_filters('the_content', $left_content) ?>
            </div>
            <div class="col-xs-12 col-sm-6 right_content">  
                <?= apply_filters('the_content', $right_content) ?>            
            </div>
        </div>
    </div>
</div>
<div id="alaska-singup" style="background-image: url('<?php echo $signup_image_attr[0]; ?>');">    
    <div id="singup-content" class="container-fluid">
        <div class="row">
            <div class="col-md-7 border-right">   
                <input id="track_signup" name="track_signup" value="<?php echo get_the_title() ?>" type="hidden"/>
                <input id="contest" name="contest" value="<?php echo $contest;?>" type="hidden"/>
                <?php get_template_part('inc/frontend/login/form', 'newsignup'); ?>
            </div>
            <div class="col-md-5">       
                <?php get_template_part('inc/frontend/upload/upload', 'widget'); ?>         
            </div>
        </div>
    </div>
</div>
<?php 
    if($hero_post){
    //Banner HERO Tag HERO
            $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
            'post__in' => $hero_post,
            'default_query' => false,
            'infinite_scroll' => 'no',
            'posts_per_page' => 2,
            'limit' => 2,
            'show_pagination' => false,
            'orderby' => 'post__in'
        ];
        $ts_query =  new WP_Query($atts);

        ts_blog_loop('2column-banner', $atts);
    }
?>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
    <div id="main-container" class="container clearfix">
<?php
    $atts = [
        'post_type' => array('post', 'sponsored_post', 'featured_destination'),
        'post__in' => $jrrnys,
        'default_query' => false,
        'orderby' => 'post__in',
        'infinite_scroll' => 'no',
        'posts_per_page' => 12,
        'limit' => 12,
        'show_pagination' => false
    ];
    $ts_query =  new WP_Query($atts);

    ts_blog_loop('3column', $atts);
?>
	</div>
</div>
<div id="featured-sponsor-wrapper" class="container">
    <?php 
        $sponsor_logo_metadata = wp_get_attachment_metadata($sponsor_logo); 
        
        if($sponsor_logo_metadata && $sponsor_logo_metadata['width'] > $sponsor_logo_metadata['height']){
            $sponsor_logo_class = 'col-sm-6';
            $sponsor_images_class = 'col-sm-6';
        } 
        else {
            $sponsor_logo_class = 'col-sm-6 col-md-5';
            $sponsor_images_class = 'col-sm-6 col-md-7';
        }
    
    ?>
    <?php if (!$isAdsense) : ?>
    <div class="row">
        <div class="col-xs-12 col-left">
            <h3>Jrrny Recommends:</h3>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">    
        <div class="sponsor-table">            
            <div class="sponsor-table-cell col-xs-12 <?php echo $sponsor_images ? $sponsor_logo_class : '' ?> col-left">
                <?php if (!$isAdsense) : ?>
                    <?php if (isset($sponsor_url) && $sponsor_url != '') : ?>
                        <p class="text-center">
                            <a href="<?= $sponsor_url ?>" target="_blank" onclick="trackOutboundLink('<?= $sponsor_url ?>'); return false;">
                                <?php echo wp_get_attachment_image($sponsor_logo, $sponsor_logo_size); ?>
                            </a>
                        </p>
                        <p class="text-center">
                            <a href="<?= $sponsor_url ?>" target="_blank" onclick="trackOutboundLink('<?= $sponsor_url ?>'); return false;">Click here for more information</a>
                        </p>
                    <?php else : ?>
                        <?php echo wp_get_attachment_image($sponsor_logo, $sponsor_logo_size); ?>                
                    <?php endif ?>
                <?php else : ?>
                    <div id="jrrrny-logo-ads">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- responsive-promotedcontent -->
                        <ins class="adsbygoogle adslot_1"
                             style="display:block"
                             data-ad-client="ca-pub-5199865911902219"
                             data-ad-slot="3600615085"
                             data-ad-format="auto"></ins>
                        <script>setTimeout(function () {
                                        (adsbygoogle = window.adsbygoogle || []).push({})
                                    }, 1000);</script>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (isset($sponsor_images) && $sponsor_images != '') : ?>
            <div class="sponsor-table-cell col-xs-12 <?php echo $sponsor_images_class;?> col-right">
                <?php foreach ($sponsor_images as $ima_id) : ?>
                    <?php
                    $imgPost = get_post($ima_id);
                    $imgAttr = wp_get_attachment_image_src($ima_id);

                    if (isset($sponsor_url) && $sponsor_url != '') {
                        echo '<a href="' . $sponsor_url . '" target="_blank"  onclick="trackOutboundLink(\'' . $sponsor_url .'\'); return false;">';
                    }
                    else {
                        echo '<a data-title="' . $imgPost->post_title . '" data-gallery="multiimages" data-toggle="lightbox" href="' . $imgPost->guid . '">';
                    }
                    ?>

                    <img width="150" height="150" alt="<?= $imgPost->post_title ?>" class="attachment-thumbnail" src="<?= $imgAttr[0] ?>">
                    </a>
                <?php endforeach; ?>
                <?php if (!$isAdsense) : ?>
                    <span>sponsored</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 featured-sponsor">
            <?php echo apply_filters('the_content', $sponsor_description) ?>
        </div>
    </div>
</div>
<div class="clear"></div>
