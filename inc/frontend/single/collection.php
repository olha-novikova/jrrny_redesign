<?php
    
get_header();
get_template_part('top');

$sponsor_description = get_post_meta($post->ID, 'sponsor_description', true);
$jrrnys = get_post_meta($post->ID, 'jrrnys', true);

$disable_hero = get_post_meta($post->ID, 'disable_hero', true);
$hero_image = get_post_meta($post->ID, 'hero_image', true);
$hero_image_attr = wp_get_attachment_image_src($hero_image, 'full');
$hero_post = get_post_meta($post->ID, 'hero_post', true);


$sponsor_logo = get_post_meta($post->ID, 'sponsor_logo', true);
$sponsor_logo_size = get_post_meta($post->ID, 'sponsor_logo_size', true);
if(!$sponsor_logo_size){
    $sponsor_logo_size = 'medium';    
}
$sponsor_images = get_post_meta($post->ID, 'sponsor_images', true);
$sponsor_url = get_post_meta($post->ID, 'sponsor_url', true);
if(isset($sponsor_url) &&  $sponsor_url != '' && empty(parse_url($sponsor_url)["scheme"])):
    $sponsor_url = "http://".$sponsor_url;
endif;

$isAdsense = get_post_meta($post->ID, 'adsense', true);

$show_sign_up_in = get_post_meta($post->ID, 'show_sign_up_in', true);
$signup_image = get_post_meta($post->ID, 'signup_image', true);
$signup_image_attr = wp_get_attachment_image_src($signup_image, 'full');

$category = ts_get_the_category('category', 'big_array:1', '', $post->ID);

$video_section_options = get_post_meta($post->ID,'video_section_option',true);

?>
<?php if(!$disable_hero){ ?>
<div id="featured-header">
    <img src="<?php echo $hero_image_attr[0]; ?>" alt="<?php echo get_the_title() ?>" />
    <div class="table">
        <div class="table-cell">
            <h1 class="top-tagline"><?php echo get_the_title() ?></h1>
            <span class="cat-name"> <?= $category[0]['name'] ?></span>
        </div>
    </div>
</div>
<?php } else{ ?>
<div id="featured-no-hero">
    <h1 class="top-tagline"><?php echo get_the_title() ?></h1>
    <span class="cat-name"> <?= $category[0]['name'] ?></span>
</div>
<?php } ?>
<div id="featured-content" class="container">
    <?php echo apply_filters( 'the_content', $post->post_content ); ?>
</div>
<div class="clear"></div>

<?php if( $video_section_options != '' && $video_section_options != 'disabled' ){ ?>

    <?php if( $video_section_options == 'home_videos'){ ?>

        <?php echo do_shortcode('[show_video]'); ?>

    <?php }elseif($video_section_options == 'own'){?>

        <?php get_template_part('inc/frontend/single/videocollection'); ?>


    <?php } ?>

<?php } ?>


<?php if($show_sign_up_in){ ?>
<div id="alaska-singup" style="background-image: url('<?php echo $signup_image_attr[0]; ?>');">    
    <div id="singup-content" class="container-fluid">
        <div class="row">
            <div class="col-md-7 border-right">
                <input id="track_signup" name="track_signup" value="<?php echo get_the_title() ?>" type="hidden"/>
                <?php get_template_part('inc/frontend/login/form', 'newsignup'); ?>
            </div>
            <div class="col-md-5">       
                <?php get_template_part('inc/frontend/upload/upload', 'widget'); ?>         
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="clear"></div>

<?php 
if (isset($hero_post) && !empty($hero_post)){
    $atts = [
        'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
        'post__in'          => $hero_post,
        'default_query'     => false,
        'orderby'           => 'post__in',
        'infinite_scroll'   => 'no',
        'posts_per_page'    => 6,
        'limit'             => 6,
        'show_pagination'   => 'false',
        'image_size'        => 'small',
        'fullwidth'         => 1,
        'show_excerpt'      => 1,
        'excerpt_length'    => '250',
        'show_sharing_options'=> 'false',
        'show_title'        => 1,
        'show_category'     => 'true'
    ];

    $ts_query_futured =  new WP_Query($atts);

    if ($ts_query_futured ->have_posts()):
        ?>
        <div>
            <div class="header-section">
                <div class="container">
                    <p class="module-header">FEATURED ARTICLES AND JRRNYS</p>
                </div>
            </div>
            <?php echo ts_blog('slider', $atts); ?>
        </div>
        <?php
    endif;
    unset($ts_query_futured);
}
    $atts = [
        'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
        'post__in'          => $jrrnys,
        'default_query'     => false,
        'orderby'           => 'post__in',
        'infinite_scroll'   => 'no',
        'posts_per_page'    => 6,
        'limit'             => 6,
        'show_pagination'   => false,
        'show_category'     => false,
        'show_excerpt'      => false,
    ];
    $ts_query =  new WP_Query($atts);
    if ($ts_query ->have_posts()):
    ?>
    <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
        <div class="header-section">
            <div class="container">
                <p class="module-header">community experiences</p>
            </div>
        </div>
        <div id="main-container" class="clearfix">
        <?php
           ts_blog('3columncarousel', $atts);
        ?>
        </div>
    </div>
    <?php
    endif;
    unset($ts_query);

    $atts = [
        'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
        'post__in'          => $jrrnys,
        'default_query'     => false,
        'orderby'           => 'post__in',
        'infinite_scroll'   => 'no',
        'offset'            => 6,
        'posts_per_page'    => 6,
        'limit'             => 6,
        'show_pagination'   => false,
        'show_category'     => false,
        'show_excerpt'      => false,
    ];
    $ts_query =  new WP_Query($atts);
    if ($ts_query ->have_posts()):
        ?>
        <div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class);?>">
            <div id="main-container" class="clearfix">
                <?php
                ts_blog('3columncarousel', $atts);
                ?>
            </div>
        </div>
    <?php
    endif;
    unset($ts_query);
    ?>
    <?php if ( $isAdsense || (isset($sponsor_url) && $sponsor_url != '') ) { ?>
    <div id="featured-sponsor-wrapper" >
        <div class="header-section">
            <div class="container">
                <p class="module-header">Good to Know</p>
            </div>
        </div>
            <?php
                $sponsor_logo_metadata = wp_get_attachment_metadata($sponsor_logo);

                if($sponsor_logo_metadata && $sponsor_logo_metadata['width'] > $sponsor_logo_metadata['height']){
                    $sponsor_logo_class = 'col-sm-6 col-md-7';
                    $sponsor_images_class = 'col-sm-6 col-md-5';
                }
                else {
                    $sponsor_logo_class = 'col-sm-6 col-md-5';
                    $sponsor_images_class = 'col-sm-6 col-md-7';
                }

    ?>

        <div class="row padding-top-30 padding-bottom-30 padding-left-50 padding-right-50">
            <div class="col-md-2 col-sm-6 col-xs-12" >
                <?php if ($isAdsense) { ?>
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

                <?php }else{
                 if (isset($sponsor_url) && $sponsor_url != '') : ?>
                    <p  class="text-center">
                        <a href="<?= $sponsor_url ?>" target="_blank" onclick="trackOutboundLink('<?= $sponsor_url ?>'); return false;">
                            <?php echo wp_get_attachment_image($sponsor_logo, $sponsor_logo_size); ?>
                        </a>
                    </p>
                    <p class="text-center">
                        <a href="<?= $sponsor_url ?>" target="_blank" onclick="trackOutboundLink('<?= $sponsor_url ?>'); return false;">Click here for more information</a>
                    </p>
                <?php else : ?>
                    <?php if( $sponsor_logo ) echo wp_get_attachment_image($sponsor_logo, $sponsor_logo_size); ?>
                <?php endif;
                }?>
            </div>
            <div class="col-md-10 col-sm-6 col-xs-12">
                <div id="sonsored-content">
                <?php if ($sponsor_description!= '') echo apply_filters( 'the_content', $sponsor_description );?>
                </div>
            </div>

        </div>
<!--        --><?php //if ( isset($sponsor_description) && $sponsor_description!= '' ){?>
<!--            <div class="row">-->
<!--                <div class="col-xs-12 featured-sponsor">-->
<!--                    --><?php //echo apply_filters( 'the_content', $sponsor_description) ?>
<!--                </div>-->
<!--            </div>-->
<!--        --><?php //} ?>
    </div>
    <?php
    }
if( have_rows('local_deal') ):?>
    <div id="local_deal-wrapper" >
        <div class="header-section">
            <div class="container">
                <p class="module-header">Local Deals</p>
            </div>
        </div>
        <div class="local-deals">
        <?php while ( have_rows('local_deal') ) : the_row();

            $image = get_sub_field('deal_image');
            $content = get_sub_field('deal_text');
            $title = get_sub_field('deal_title');
        ?>
        <div class="row">
        <?php if( $title ): ?>
            <div class="col-xs-12 "><h2><?php echo $title; ?></h2> </div>
        <?php endif; ?>
            <div class="col-xs-12 ">
                <?php if( $image ): ?>
                <div class="im"><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" /></div>
                <?php endif; ?>
            <?php if( $content ): ?>
                <div class="text-left"><?php echo apply_filters( 'the_content', $content); ?></div>
            <?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php endwhile;
        ?>
        </div>
    </div>
 <?php
endif;
?>

<?php 
get_footer();
?>

