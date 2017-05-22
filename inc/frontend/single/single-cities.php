<?php
get_header();
get_template_part('top');


$hero_image = get_post_meta($post->ID, 'hero_image', true);
$hero_image_attr = wp_get_attachment_image_src($hero_image, 'full');
$disable_hero = get_post_meta($post->ID, 'disable_hero', true);

$article = get_post_meta($post->ID, 'article', true);
$articles = get_post_meta($post->ID, 'articles', true);
$collections = get_post_meta($post->ID, 'collections', true);
$hero_post = get_post_meta($post->ID, 'hero_post', true);
$jrrnys = get_post_meta($post->ID, 'jrrnys', true);

$sponsor_logo = get_post_meta($post->ID, 'sponsor_logo', true);
$sponsor_logo_size = get_post_meta($post->ID, 'sponsor_logo_size', true);
if (!$sponsor_logo_size) {
    $sponsor_logo_size = 'medium';
}
$sponsor_url = get_post_meta($post->ID, 'sponsor_url', true);
if (isset($sponsor_url) && $sponsor_url != '' && empty(parse_url($sponsor_url)["scheme"])):
    $sponsor_url = "http://" . $sponsor_url;
endif;
$sponsor_images = get_post_meta($post->ID, 'sponsor_images', true);
$sponsor_description = get_post_meta($post->ID, 'sponsor_description', true);

$isAdsense = get_post_meta($post->ID, 'adsense', true);

$post_title = get_the_title();
?>
<?php if (!$disable_hero) { ?>
    <div id="featured-header">
        <img src="<?php echo $hero_image_attr[0]; ?>" alt="<?php echo $post_title ?>" />
        <div class="table">
            <div class="table-cell">
                <h1 class="top-tagline"><?php echo $post_title ?></h1>
            </div>
        </div>
    </div>
<?php }
else {?>
    <div id="featured-no-hero">
        <h1 class="top-tagline"><?php echo $post_title ?></h1>
    </div>
<?php } ?>
<?php if ( $post->post_content!='' ){?>
    <div id="featured-content" class="container">
    <?= apply_filters('the_content', $post->post_content) ?>
    </div>
<?php }?>
<div class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container" class="clearfix">

        <div class="header-section">
            <div class="container">
                <p class="module-header">Featured Collection: <a href="<?php echo home_url();?>/articles"> more articles</a></p>
            </div>
        </div>

        <?php
        $atts = [
            'post_type'         => array('articles'),
            'post__in'          => array($article),
            'default_query'     => false,
            'image_size'        => 'small',
            'fullwidth'         => 1,
            'orderby'           => 'post__in',
            'infinite_scroll'   => 'no',
            'posts_per_page'    => 1,
            'limit'             => 1,
            'show_pagination'   => false,
            'show_category'     => true

        ];

        $ts_query = new WP_Query($atts);

        ts_blog_loop('slider', $atts);
        ?>
        <?php
        $atts = [
            'post_type'         => array('articles'),
            'post__in'          => $articles,
            'default_query'     => false,
            'orderby'           => 'post__in',
            'infinite_scroll'   => 'no',
            'posts_per_page'    => 12,
            'limit'             => 12,
            'show_pagination'   => false,
            'show_category'     => false,
            'show_excerpt'      => 0,
        ];
        $ts_query = new WP_Query($atts);

        ts_blog_loop('3column', $atts);
        ?>
    </div>
</div>
<div class="<?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">
    <div id="main-container" class="clearfix">
        <div class="header-section">
            <div class="container">
                <p class="module-header"><?php echo $post_title ?> Collections  <a href="<?php echo home_url();?>/collection"> more collections</a></p>
            </div>
        </div>
    </div>
</div>
    <div class="featured_destination_wrapper clearfix">
        <div class="ts-slider-wrap loop-slider-wrap flexslider ts-edge-to-edge ts-color-section-fullwidth">
            <ul class="slides" data-slide-width="720" data-desired-slide-width="720>">
                <?php

                $collections = plc_get_collections($collections);
                //echo '<pre>'.print_r($collections, true).'</pre>';//
                foreach($collections as $collection)
                {
                    $post = $collection;
                    setup_postdata( $post );
                    $image_data = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
                    if(!$image_data){
                        $hero_image = get_post_meta($post->ID, 'hero_image', true);
                        $hero_image_attr = wp_get_attachment_image_src( $hero_image, 'large');
                        $image_data = $hero_image_attr;
                    }
                    $backgroundStyle ="background-image: url('". $image_data[0]  ."'); height:500px";
                    ?>

                    <li class="flex-item ts-slider-item" data-width="720" data-height="480" style="<?php echo esc_attr($backgroundStyle);?>">
                        <div class="ts-item-details">
                            <div class="ts-item-details-inner container">
                                <h2 class="blog-title title title-h color-white"><?php the_title();?></h2>
                                <div class="blog-descr">
                                    <div class="read-more-wrap">
                                        <div class="read-more mimic-smaller uppercase">
                                            <a class ="btn btn-blue" href="<?php the_permalink();?>" rel="bookmark"><?php echo __('View Collection', 'ThemeStockyard');?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php }
                wp_reset_postdata();
                ?>
            </ul>
        </div>

    </div>

<div>
    <div id="main-container" class="clearfix">
        <div class="header-section">
            <div class="container">
                <p class="module-header">Community Experiences</p>
            </div>
        </div>

    </div>
</div>
<?php
if ($hero_post) {
    //Banner HERO Tag HERO
    $atts = [
        'post_type' => array('post', 'sponsored_post', 'featured_destination'),
        'post__in' => $hero_post,
        'default_query' => false,
        'infinite_scroll' => 'no',
        'posts_per_page' => 2,
        'limit' => 2,
        'show_pagination' => false,
        'orderby' => 'post__in',
        'show_excerpt'      => 0,
    ];
    $ts_query = new WP_Query($atts);

    ts_blog_loop('2column-banner', $atts);
}
?>
<div>
    <div id="main-container" class="clearfix">
        <div class="header-section">
            <div class="container">
                <p class="module-header">jrrnys<a href="<?php echo home_url();?>/all-jrrnys">More jrrnys</a></p>
            </div>
        </div>
        <?php
        $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination'),
            'post__in' => $jrrnys,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => 12,
            'limit' => 12,
            'show_pagination' => false,
            'show_category'     => false,
            'show_excerpt'      => 0,
        ];
        $ts_query = new WP_Query($atts);

        ts_blog_loop('3column', $atts);
        ?>
    </div>
</div>
<div id="featured-sponsor-wrapper">
    <?php
    $sponsor_logo_metadata = wp_get_attachment_metadata($sponsor_logo);

    if ($sponsor_logo_metadata && $sponsor_logo_metadata['width'] > $sponsor_logo_metadata['height']) {
        $sponsor_logo_class = 'col-sm-6 col-md-7';
        $sponsor_images_class = 'col-sm-6 col-md-5';
    }
    else {
        $sponsor_logo_class = 'col-sm-6 col-md-5';
        $sponsor_images_class = 'col-sm-6 col-md-7';
    }
    ?>
    <?php if (!$isAdsense) : ?>
        <div class="header-section">
            <div class="container">
                <p class="module-header">Jrrny Recommends:</p>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
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
                    <div class="sponsor-table-cell col-xs-12 <?php echo $sponsor_images_class; ?> col-right">
                        <?php foreach ($sponsor_images as $ima_id) : ?>
                            <?php
                            $imgPost = get_post($ima_id);
                            $imgAttr = wp_get_attachment_image_src($ima_id);

                            if (isset($sponsor_url) && $sponsor_url != '') {
                                echo '<a href="' . $sponsor_url . '" target="_blank"  onclick="trackOutboundLink(\'' . $sponsor_url . '\'); return false;">';
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
</div>
<?php
get_footer();
?>

