<?php
/*
Template Name: Advertise
*/

get_header();
get_template_part('top');

$join_image = get_post_meta($post->ID, 'join_image', true);

$subtitle = get_post_meta($post->ID, 'subheader', true);

$join_image_attr = wp_get_attachment_image_src($join_image, 'full');
$join_image_paralax = get_post_meta($post->ID, 'join_image_paralax', true);
?>
<div id="join-container" class="clearfix<?php echo $join_image_paralax ? ' parallax' : ''?>" style="background-image: url('<?php echo $join_image_attr[0];?>');">
    <div class="join-table">
        <div class="join-cell">
            <div class="join-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 title">
                            <?php  the_title(); ?>

                        </div>
                        <div class="col-xs-12 subtitle">
                            <?php echo apply_filters( 'the_content', $subtitle );?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main-container-wrap" >
    <div id="main-container" class="clearfix">
        <div class="container padding-bottom-30 padding-top-30">
            <div class="row">
                <div class="col-md-12">

                    <?php echo apply_filters( 'the_content', $post->post_content ); ?>
                </div>
            </div>
        </div>
    </div><!-- #main-container -->
</div><!-- #main-container-wrap -->
<?php if( have_rows('brands_we_work_with') ): ?>

    <div class="brand_wit_us ">
        <div class="header-section">
            <div class="container">
                <p class="module-header">Brands We Work With</p>
            </div>
        </div>
        <div class="brand_list container padding-top-30">
            <div class="row padding-bottom-30">
                <?php
                $i = 0;
                while ( have_rows('brands_we_work_with') ) : the_row();
                    $image = get_sub_field('brand_logo');
                    ?>
                    <div class="col-md-6 vcenter">
                        <?php if( $image ): ?>
                            <div class="brand "><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" /></div>
                        <?php endif; ?>
                    </div>
                    <?php
                    $i ++;
                    if ($i == 2) {echo "</div><div class=\"row padding-bottom-30\">"; $i = 0;}
                endwhile; ?>
            </div>
        </div>
    </div>

<?php endif; ?>
    <div class="pricing-table">

        <div class="header-section">
            <div class="container">
                <p class="module-header">Prices</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if( have_rows('silver_options') ):?>
                <div class="price_columns">
                    <ul class="price">
                        <li class="header">Silver</li>
                        <li class="grey"><span>$ <?php the_field('silver_price'); ?> / 6 month</span><span>Media Coverage</span></li>
                        <?php
                        while ( have_rows('silver_options') ) : the_row();

                            if( get_row_layout() == 'video' ):

                                $num = get_sub_field('vodeo_num');
                                if ($num == 0) $str = "-";
                                elseif ($num == 1) $str = "1 Video";
                                else $str = $num." Videos";

                                $coverage = get_sub_field('video_coverage');
                                if ($coverage == 0) $str_coverage = "-";
                                else $str_coverage = $coverage;
                                ?>

                                <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                            <?php

                            elseif( get_row_layout() == 'sponsored_article' ):

                                $num = get_sub_field('sponsored_article_num');
                                if ($num == 0) $str = "-";
                                elseif ($num == 1) $str = "1 Sponsored Article";
                                else $str = $num." Sponsored Articles";

                                $coverage = get_sub_field('sponsored_article_coverage');
                                if ($coverage == 0) $str_coverage = "-";
                                else $str_coverage = $coverage;
                                ?>

                                <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                            <?php


                            elseif( get_row_layout() == 'distribution' ):

                                $num = get_sub_field('distribution_num');
                                if ($num == 0) $str = "-";
                                elseif ($num == 1) $str = "1 Distribution";
                                else $str = $num." Distributions";

                                $coverage = get_sub_field('distribution_coverage');
                                if ($num == 0) $str_coverage = "-";
                                else $str_coverage = $coverage;
                                ?>

                                <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                            <?php


                            elseif( get_row_layout() == 'dedicated_newsletter' ):

                                $num = get_sub_field('dedicated_newsletter_num');
                                if ($num == 0) $str = "-";
                                elseif ($num == 1) $str = "1 Dedicated Newsletter";
                                else $str = $num." No Dedicated Newsletters";

                                $coverage = get_sub_field('dedicated_newsletter_coverage');
                                if ($coverage == 0) $str_coverage = "-";
                                else $str_coverage = $coverage;
                                ?>

                                <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                            <?php


                            elseif( get_row_layout() == 'influenced_opportunity' ):

                                $num = get_sub_field('influenced_opportunity_num');
                                if ($num == 0) $str = "-";
                                elseif ($num == 1) $str = "1 Influencer Opportunity";
                                else $str = $num." Influencer Opportunities";

                                $coverage = get_sub_field('influenced_opportunity_coverage');
                                if ($coverage == 0) $str_coverage = "-";
                                else $str_coverage = $coverage;
                                ?>

                                <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                            <?php

                            endif;
                        endwhile;
                        $link = get_field('silver_page_link'); ?>

                        <li class="grey"><span><a href="<?php if ($link!= '')echo $link; else echo '#';?>" class="btn btn-blue">Sign Up</a></span><span>Total Impressions ~ <?php the_field('silver_impression_count'); ?></span></li>
                    </ul>
                </div>
                <?php
                endif;

                if( have_rows('gold_options') ):?>
                    <div class="price_columns">
                        <ul class="price">
                            <li class="header">Gold</li>
                            <li class="grey"><span>$ <?php the_field('gold_price_copy'); ?> / 6 month</span><span>Media Coverage</span></li>
                            <?php
                            while ( have_rows('gold_options') ) : the_row();

                                if( get_row_layout() == 'video' ):

                                    $num = get_sub_field('vodeo_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Video";
                                    else $str = $num." Videos";

                                    $coverage = get_sub_field('video_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php

                                elseif( get_row_layout() == 'sponsored_article' ):

                                    $num = get_sub_field('sponsored_article_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Sponsored Article";
                                    else $str = $num." Sponsored Articles";

                                    $coverage = get_sub_field('sponsored_article_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'distribution' ):

                                    $num = get_sub_field('distribution_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Distribution";
                                    else $str = $num." Distributions";

                                    $coverage = get_sub_field('distribution_coverage');
                                    if ($num == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'dedicated_newsletter' ):

                                    $num = get_sub_field('dedicated_newsletter_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Dedicated Newsletter";
                                    else $str = $num." No Dedicated Newsletters";

                                    $coverage = get_sub_field('dedicated_newsletter_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'influenced_opportunity' ):

                                    $num = get_sub_field('influenced_opportunity_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Influencer Opportunity";
                                    else $str = $num." Influencer Opportunities";

                                    $coverage = get_sub_field('influenced_opportunity_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php

                                endif;
                            endwhile;
                            $link = get_field('gold_page_link'); ?>

                            <li class="grey"><span><a href="<?php if ($link!= '')echo $link; else echo '#';?>" class="btn btn-blue">Sign Up</a></span><span>Total Impressions ~ <?php the_field('gold_impression_count'); ?></span></li>
                        </ul>
                    </div>
                    <?php
                    endif;

                    if( have_rows('platinum_options') ):?>
                    <div class="price_columns">
                        <ul class="price">
                            <li class="header">Platinum</li>
                            <li class="grey"><span>$ <?php the_field('platinum_price_copy'); ?> / 6 month</span><span>Media Coverage</span></li>
                            <?php
                            while ( have_rows('platinum_options') ) : the_row();

                                if( get_row_layout() == 'video' ):

                                    $num = get_sub_field('vodeo_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Video";
                                    else $str = $num." Videos";

                                    $coverage = get_sub_field('video_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php

                                elseif( get_row_layout() == 'sponsored_article' ):

                                    $num = get_sub_field('sponsored_article_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Sponsored Article";
                                    else $str = $num." Sponsored Articles";

                                    $coverage = get_sub_field('sponsored_article_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'distribution' ):

                                    $num = get_sub_field('distribution_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Distribution";
                                    else $str = $num." Distributions";

                                    $coverage = get_sub_field('distribution_coverage');
                                    if ($num == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'dedicated_newsletter' ):

                                    $num = get_sub_field('dedicated_newsletter_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Dedicated Newsletter";
                                    else $str = $num." No Dedicated Newsletters";

                                    $coverage = get_sub_field('dedicated_newsletter_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php


                                elseif( get_row_layout() == 'influenced_opportunity' ):

                                    $num = get_sub_field('influenced_opportunity_num');
                                    if ($num == 0) $str = "-";
                                    elseif ($num == 1) $str = "1 Influencer Opportunity";
                                    else $str = $num." Influencer Opportunities";

                                    $coverage = get_sub_field('influenced_opportunity_coverage');
                                    if ($coverage == 0) $str_coverage = "-";
                                    else $str_coverage = $coverage;
                                    ?>

                                    <li><span><?php echo $str; ?></span><span><?php echo $str_coverage; ?></span></li>
                                <?php

                                endif;
                            endwhile;
                            $link = get_field('platinum_page_link'); ?>
                            <li class="grey"><span><a href="<?php if ($link!= '')echo $link; else echo '#';?>" class="btn btn-blue">Sign Up</a></span><span>Total Impressions ~ <?php the_field('platimum_impression_count'); ?></span></li>
                        </ul>
                    </div>
                    <?php
                endif;

                ?>
            </div>
        </div>


    </div>
<?php
get_footer();
?>