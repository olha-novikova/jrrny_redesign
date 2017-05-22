<?php
/*
Template Name: Upload Jrrny
*/
if (!is_user_logged_in()):
    wp_redirect(home_url() . '#login-form', '301');
    exit;
endif;
get_header();
?>
    <script>
        jQuery(document).ready(function () {
            jQuery('html, body').animate({
                scrollTop: jQuery("#main-container-wrap").offset().top
            }, 2000);
        });
    </script>
    <div id="after-upload-modal" class="modal fade plc-modal in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div>
                        <h3 class="title">Dont forget to share!</h3>
                    </div>
                    <div class="modal-social">
                        <div class="social facebook">
                            <a href="" class="share-pop">
                                <span class="flaticon flaticon-facebook-logo-button"></span>
                                    Share on Facebook
                            </a>
                        </div>
                        <div class="social twitter">
                            <a href="" class="share-pop">
                                <span class="flaticon flaticon-twitter-logo-button"></span>
                                    Share on Twitter
                            </a>
                        </div>
                    </div>
                    <div class="link-to-jrrny">
                        <a id="link-to-jrrny" href="">No Thanks!</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <?php
global $current_user;

wp_get_current_user();
get_template_part('top');

$extra_class = '';
        if (is_user_in_role($current_user->ID, 'brand')){
                $extra_class = ' branded';
                get_template_part("inc/frontend/author/brand-top");
        }
?>
    
    <div id="main-container-wrap"
         class="upload <?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class). $extra_class; ?>">
        <?php if (is_user_in_role($current_user->ID, 'brand')){ ?>
            <div id="main-container" class="container clearfix">
                <div class="brand-description">
                <?php echo get_option('ad_1'); ?>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse viverra id magna in consectetur. Fusce eu aliquet ante. Fusce vel dictum nulla, eget malesuada leo. Nullam elit ex, auctor eu faucibus eget, pellentesque vitae libero. Nulla ac facilisis turpis. In in fermentum mauris, sit amet varius nibh. Ut nec pretium quam. Proin fringilla aliquam diam, id mattis arcu malesuada at. Phasellus ultricies lectus sed leo ultricies malesuada. 
                    </p>
                </div>
            <p class="module-header">EXAMPLES</p>
            <?php 
            $atts = [
                'post_type' => array('post'),
                'default_query' => false,
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'limit' => 3,
                'show_pagination' => false,
                'orderby' => 'rand',
                'infinite_scroll' => 'no'
            ];
            //$ts_query =  new WP_Query($atts);

            ts_blog_loop('3column', $atts);
            ?>
            <p class="module-header">SHARE YOUR EXPERIENCE</p>
            </div>
        <?php } else { ?>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <div id="main-container" class="container clearfix">
                    
       
                    <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
                        <div class="uploader-header">
                            <h2> Create your jrrny</h2>
                            <?php
                            the_content();
                            ?>
                        </div>
                    </div>
                </div><!-- #main-container -->
                <?php
            endwhile;
        else :
            ?>
            <div id="main-container" class="container clearfix">
                <div id="main" class="<?php echo esc_attr($ts_show_sidebar_class); ?> clearfix">
                    <div class="entry single-entry clearfix">
                        <div class="post">
                            <p><?php _e('Sorry, the post you are looking for does not exist.', 'ThemeStockyard'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endif;
        }
        ?>
        <!-- form container -->
        <div class="container">
            <form id="form-journey" class="form-horizontal" method="post" action="<?php echo home_url(); ?>/upload">
                <?php get_contest_input_uploader_tool($current_user->ID);?>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="place" id="place-jrrny" class="form-control"
                               placeholder="Where you went - City & State"
                               value="<?php echo((isset($_GET['place']) && $_GET['place'] !== '') ? $_GET['place'] : ''); ?>">
                        <input type="text" name="activity" id="activity-jrrny" class="form-control"
                               placeholder="What did you do?"
                               value="<?php echo((isset($_GET['activity']) && $_GET['activity'] !== '') ? $_GET['activity'] : ''); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="hotel-name" id="hotel-name" class="form-control"
                                                         placeholder="Hotel Name or Starting Point">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="hotel-link" id="hotel-link" class="form-control"
                                       placeholder="Link to where you stayed / started">
                            </div>
                        </div>
                        <?php
                        global $wyswig_settings;
                        ?>
                        <label>What happened on your jrrny? Describe where you ate, where you went, what was fun, and what wasn't!</label><?php
                        wp_editor("", 'story', $wyswig_settings);
                       ?>
                        <input type="text" name="insider-tip" id="insider-tip" class="form-control"
                               placeholder="Insider tip? - Tell us the secrets only the locals would know">
                    </div>

                    <div class="col-md-4">
                        <div id="jrrny-himages-dropzone" class="form-group image-upload-dropzone dropzone">
                            <div class="dz-message" data-dz-message>
                                <p>Where did you stay/start?</p>
                                <p><i class="drop-here"></i></p>
                                <span class="visible-xs">tap to add up to (2) photos of your jrrny</span>
                                <span class="hidden-xs">drag and drop up to (2) photos or click to browse</span>
                            </div>
                        </div>

                        <div id="jrrny-images-dropzone" class="form-group image-upload-dropzone dropzone ">
                            <div class="dz-message" data-dz-message>
                                <p>What did you do?</p>
                                <p><i class="drop-here"></i></p>
                                <span class="visible-xs">tap to add up to (12) photos </span>
                                <span class="hidden-xs">drag and drop up to (12) photos or click to browse</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="_token" value="<?php echo $current_user->user_login; ?>"/>
                        <input type="hidden" id="jrrny-main-image-id" name="main-image-id" value=""/>
                        <button id="journey-data-process" class="btn btn-blue btn-lg">
                            Upload
                            <i class="fa processing-icon hide"></i>
                        </button>
                        <button id="journey-preview" class="btn btn-blue btn-lg" data-toggle="modal" disabled
                                data-target="#previewModal">
                            Preview
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div><!-- #main-container-wrap -->

    <!-- Modal -->
    <div class="modal fade" id="previewModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h1>
                        <center>Jrrny Preview</center>
                        <h1>
                </div>
                <div class="modal-body">
                    <div class="preview-holder preview_title">
                        <h1 class="entry-title"><span id="previewTitle">Kashmir</span> for <span id="whereStay">Hiking, Drinking</span>
                        </h1>
                        <span class="preview_cat">Traveler Post / </span><span
                            class="preview-date"><?php echo date("F j, Y"); ?> / </span><span
                            class="preview_author"><?php echo $current_user->user_login; ?></span>
                    </div>
                    <div class="preview-holder jrrny-all-images">
                        <h3>Jrrny Images:</h3>

                        <div id="imagesForJrrny">
                        </div>
                    </div>
                    <div class="preview-story">
                        <h3>Story:</h3>

                        <p id="previewStory">I want to enjoy holidays with my friends</p>
                    </div>
                    <div class="preview-stay">
                        <h3>Where I stayed / started:</h3>

                        <p id="previewStay">I want to enjoy holidays with my friends</p>

                        <p><strong>Link: </strong><span id="previewHotelLink">https://www.google.com</span></p>
                    </div>
                    <div class="preview-holder">
                        <h3>Hotel Image:</h3>

                        <div id="imgForHotel" class="preview-img-holder">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-success" id="jrrny-submit-preview">Submit</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- END Modal -->
<?php get_footer();