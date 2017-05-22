<?php
/*
  Template Name: Contribute
 */
/* $current_user = (is_user_logged_in()) ? wp_get_current_user() : null;
  $user_invitation = get_user_meta($current_user->ID, '_user_invitation');
  if(empty($user_invitation)){
  wp_redirect('/');
  }
 */
get_header();
get_template_part('top');
if (post_password_required()) :
    get_the_password_form();
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php echo the_content(); ?>
        </div>
    </div>
</div>
<?php
else :
    $top_title = get_field('top_title', $post->ID, true);
    $top_left_content = get_field('top_left_content', $post->ID, true);
    $top_right_content = get_field('top_right_content', $post->ID, true);
    $left_title = get_field('left_title', $post->ID, true);
    $left_content = get_field('left_content', $post->ID, true);
    $right_title = get_field('right_title', $post->ID, true);
    $right_content = get_field('right_content', $post->ID, true);
    $bottom_title = get_field('bottom_title', $post->ID, true);
    $bottom_left_iconnumber = get_field('bottom_left_iconnumber', $post->ID, true);
    $bottom_left_content = get_field('bottom_left_content', $post->ID, true);
    $bottom_middle_iconnumber = get_field('bottom_middle_iconnumber', $post->ID, true);
    $bottom_middle_content = get_field('bottom_middle_content', $post->ID, true);
    $bottom_right_iconnumber = get_field('bottom_right_iconnumber', $post->ID, true);
    $bottom_right_content = get_field('bottom_right_content', $post->ID, true);

    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    $image_url = isset($image[0]) ? $image[0] : '';
    ?>
    <div id="contribute-wrapper">
        <div id="top-wrap" style="background-image: url('<?php echo $image_url; ?>');">   
            <div class="header-table">
                <div class="header-cell">
                    <div class="hompage-header-wrapper">    
                        <div class="container">
                            <div class="row">   
                                <div class="col-xs-12 title">
                                    You are invited
                                </div>
                                <div class="col-xs-12 subtitle">
                                    Join our travel contributor program
                                </div>
                                <div class="col-xs-12 description">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-blue<?php echo (is_user_logged_in()) ? ' ' : ' login_modal';?>" href="<?php echo home_url('/contribute/apply'); ?>">Apply Now</a>
                                    </div>
                                    <p><a href="<?php echo home_url(); ?>/contribute#why-apply">Learn more</a></p>
                                </div>
                            </div>
                        </div>                                            
                    </div>
                </div>
            </div>  
        </div> 
        <div class="contribute-gray">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <a name="why-apply"></a>
                        <p class="contribute-title text-center"><?php echo $top_title; ?></p>              
                    </div>
                    <div class="col-xs-12 col-sm-6">       
                        <?php echo $top_left_content; ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">       
                        <?php echo $top_right_content; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contribute-white">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7"> 
                        <p class="contribute-title"><?php echo $left_title; ?></p>         
                        <?php echo $left_content; ?>
                    </div>
                    <div class="col-xs-12 col-sm-5">  
                        <p class="contribute-title text-center"><?php echo $right_title; ?></p>        
                        <?php echo $right_content; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contribute-gray">
            <div class="container">
                <div class="row">          
                    <div class="col-xs-12">                
                        <p class="contribute-title text-center"><?php echo $bottom_title; ?></p>   
                    </div>
                    <div class="col-xs-12 col-sm-4"> 
                        <p><span class="contribute-number"><?php echo $bottom_left_iconnumber; ?></span></p>
                        <?php echo $bottom_left_content; ?>
                    </div>
                    <div class="col-xs-12 col-sm-4">       
                        <p><span class="contribute-number"><?php echo $bottom_middle_iconnumber; ?></span></p>
                        <?php echo $bottom_middle_content; ?>
                    </div>
                    <div class="col-xs-12 col-sm-4">   
                        <p><span class="contribute-number"><?php echo $bottom_right_iconnumber; ?></span></p>    
                        <?php echo $bottom_right_content; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contribute-join">
            <div class="container">
                <div class="row">            
                    <div class="col-xs-12"> 
                        <p>Contribute to Jrrny</p>
                        <a class="btn btn-blue<?php echo (is_user_logged_in()) ? ' ' : ' login_modal';?>" href="<?php echo home_url('/contribute/apply'); ?>">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade plc-new-modal" tabindex="-1" role="dialog" id="contribute-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-wrapper">
                        <p class="title">Review your profile</p>
                        <form method="post" id="review_profile">
                            <p id="form-validation">Are you sure you would like to send your profile for review?</p>
                            <div class="form-group">                            
                                <div class="btn-group" role="group">
                                    <button type="button" data-dismiss="modal" class="btn btn-blue-border">NO</button>
                                    <button type="submit" id="submit_review" class="btn btn-blue">YES&nbsp;<i class="fa processing-icon hide"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
get_footer();
