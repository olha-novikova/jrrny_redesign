<?php
global $smof_data, $ts_page_id, $ts_page_title;

$ts_page_id        = get_option('page_for_posts');

$ts_show_sidebar = (ts_option_vs_default('show_archive_sidebar', 1) != 1) ? 'no' : 'yes';
$ts_show_sidebar_class = ($ts_show_sidebar == 'yes') ? 'has-sidebar' : 'no-sidebar';

$ts_show_top_ticker_option = (ts_option_vs_default('show_page_top_ticker', 0) == 1) ? 'yes' : 'no';
$ts_show_top_ticker = ts_postmeta_vs_default($ts_page_id, '_page_top_ticker', $ts_show_top_ticker_option);

$ts_sidebar_position = ts_option_vs_default('page_sidebar_position', 'right');

$ts_infinite_scroll = ts_option_vs_default('infinite_scroll_on_blog_template', 0);

$post = $posts[0];
$ts_queried_object = get_query_var('author');
$ts_caption = (trim($ts_caption)) ? $ts_caption : '';

get_header();
get_template_part('top');

global $post;
$post = get_page_by_path( 'featured_destination' );
if($post): setup_postdata( $post );
    $content = get_the_content();    
?>
<div id="title-bar-wrap" class="title-bar-layout-normal title-bar-page " style="">
    <div id="title-bar" class="container">
        <div id="title-bar-text" class="container">
            <div class="row">
                <div class="span12">
                    <h1> <?php echo $content; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main-container-wrap" class="<?php echo esc_attr(ts_main_container_wrap_class('page')).' '.esc_attr($main_container_wrap_class) . ($content ? '' : ' no-margin no-padding' ) ;?>">
        <?php    
                
            $rows = get_field('featured_destination', get_the_ID());

            $count_rows = count($rows);
            $num_sliders = 1;
            if ( $count_rows > 15 ) $num_sliders = 4; elseif ( $count_rows > 8 ) $num_sliders = 3; elseif ( $count_rows > 5 ) $num_sliders = 2;

            $sliders = array();
            $i = 0;
            $current = 0;

            foreach ($rows as $key=>$row ){
                if ($i == 0)
                    $sliders[$i][] = $row;
                else{
                    $post = $row['featured_destination_jrrnys'];
                    setup_postdata( $post );
                    $sliders[$i][] = $post->ID;
                    wp_reset_postdata();
                }

                if ( $current == 2) { $i++; $current = 0;}
                $current ++;
                if ( $i == $num_sliders ) $i = 1;
            }
       // echo "<pre>"; print_r($sliders); echo "<pre>";
        //ts_blog_loop('3columncarousel', $atts_liked);
            if($sliders){
            ?>
                <div id="futured-collection" class="clearfix">
                    <?php
                    foreach($sliders as $key => $slider) {
                        if ( $key == 0 ){
                            $rows = $slider;
                            ?>
                            <div class="ts-slider-wrap loop-slider-wrap flexslider ts-edge-to-edge ts-color-section-fullwidth">
                                <ul class="slides" data-slide-width="720" data-desired-slide-width="720>">
                                <?php
                                foreach ( $rows as $row ){
                                    $post = $row['featured_destination_jrrnys'];
                                    setup_postdata( $post );
                                    $image_data = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                                    if(!$image_data){
                                        $hero_image = get_post_meta($post->ID, 'hero_image', true);
                                        $hero_image_attr = wp_get_attachment_image_src( $hero_image);
                                        $image_data = $hero_image_attr[0];
                                    }
                                    $backgroundStyle ="background-image: url('". $image_data  ."');height:500px";
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
                        <?php } else{
                        $slider_atts =  array(
                            'post_type'         => array('post', 'sponsored_post', 'featured_destination'),
                            'post__in'          => $slider,
                            'default_query'     => false,
                            'orderby'           => 'post__in',
                            'infinite_scroll'   => 'no',
                            'show_excerpt'      => 0,
                            'show_sharing_options'=> false,
                            'show_author'       => false,
                            'show_title'        => 1,
                            'text_align'        => 'center',
                            'title_size'        => '2',
                            'show_meta'         => 0,
                            'show_category'     => false);

                        ts_blog_loop('3columncarousel', $slider_atts);
                        }
                    ?>
                </div>
            <?php  }

//


                /* $rand_collections = plc_get_random_collections($rows, 3);
               if ( $rand_collections ):
                    ?>
                    <div id="rand_collections" class="clearfix">
                        <div class="featured_destination_wrapper clearfix">
                            <ul id="featured_destination_list" class="no-padding custom-collections">
                                <?php
                                foreach( $rand_collections as $collection ){
                                    $image_data = wp_get_attachment_image_src( get_post_thumbnail_id($collection->ID), 'large');

                                    if(!$image_data){
                                        $hero_image = get_post_meta($collection->ID, 'hero_image', true);
                                        $hero_image_attr = wp_get_attachment_image_src( $hero_image, 'large');
                                        $image_data = $hero_image_attr;
                                    }
                                    $backgroundStyle ="background-image: url('". $image_data[0]  ."')";

                                    ?>
                                    <li>
                                        <div style="<?php echo $backgroundStyle;?>" class="fetured-content">
                                            <a href="<?php echo  get_the_permalink($collection->ID)?>" class="fetured-content-link"></a>
                                            <div class="plc-table">
                                                <div class="plc-cell">
                                                    <?php echo $collection->post_title; ?>
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
                <?php
                endif;*/
            } else { ?>
    
                <div id="main-container" class="container clearfix">
                    <div id="main" class="clearfix <?php echo esc_attr($ts_show_sidebar_class);?>">
                        <div class="entry single-entry clearfix">
                            <div class="post">
                                <?php
                                /*
                                 * Run the loop to output the posts.
                                 */
                                $ts_loop = (isset($smof_data['archive_layout'])) ? $smof_data['archive_layout'] : '';
                                $atts = array('infinite_scroll' => $ts_infinite_scroll, 'layout' => $ts_loop, 'default_query' => true, 'pid' => $ts_page_id);
                                ts_blog($atts, $ts_loop);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php ts_get_sidebar(); ?>
                </div>
            <?php  } ?>   
</div><!-- #main-container-wrap -->         
                 
<?php endif;
wp_reset_postdata();?>
<?php get_footer(); ?>
