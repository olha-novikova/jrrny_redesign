<?php global $post_id;?>
<div id="main-container" class="container-fluid clearfix">
    <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
        <div class="uploader-header">
            <h2><i class="flaticon flaticon-checkbox-pen-outline"></i> Create your jrrny</h2>
            <?php
            echo apply_filters('the_content', get_post_field('post_content', 12));
            ?>
        </div>
    </div>
</div><!-- #main-container -->
<div class="container-fluid">
    <form id="form-journey" class="form-horizontal" method="post" action="<?php echo home_url(); ?>/upload">
        <input type="hidden" name="contest_id" value="<?php echo $post_id;?>" />
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <div class="input-group">
                    <span class="input-group-addon"><i class="flaticon flaticon-map-pin-marked"></i></span>
                    <input type="text" name="place" id="place-jrrny" class="form-control"
                           placeholder="Where you went - City & State"
                           value="<?php echo((isset($_GET['place']) && $_GET['place'] !== '') ? $_GET['place'] : ''); ?>">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="input-group flaticon-absolute">
                    <span class="input-group-addon">FOR <i
                            class="flaticon flaticon-directions-signs-outlines"></i></span>
                    <input type="text" name="activity" id="activity-jrrny" class="form-control"
                           placeholder="What did you do?"
                           value="<?php echo((isset($_GET['activity']) && $_GET['activity'] !== '') ? $_GET['activity'] : ''); ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <label for="dropzone"><i class="flaticon flaticon-photo-camera-outline"></i> Add Images:
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="jrrny-dropzones-container upload-page">
                <div class="col-md-6 col-lg-5 sm-upload">
                    <div id="jrrny-himages-dropzone" class="form-group image-upload-dropzone dropzone">
                        <div class="dz-message" data-dz-message>
                            <p><i class="flaticon flaticon-uploading-archive"></i></p>

                            <p>Where did you stay/start?</p>
                            <span class="visible-xs">tap to add up to (2) photos of your jrrny</span>
                            <span class="hidden-xs">drag and drop up to (2) photos or click to browse</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7">
                    <div id="jrrny-images-dropzone" class="form-group image-upload-dropzone dropzone ">
                        <div class="dz-message" data-dz-message>
                            <p><i class="flaticon flaticon-uploading-archive"></i></p>

                            <p>What did you do?</p>
                            <span class="visible-xs">tap to add up to (12) photos </span>
                            <span class="hidden-xs">drag and drop up to (12) photos or click to browse</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <div class="input-group">
                    <span class="input-group-addon"><i class="flaticon flaticon-house-outline"></i></span>
                    <input type="text" name="hotel-name" id="hotel-name" class="form-control"
                           placeholder="Hotel Name or Starting Point">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="flaticon flaticon-globe-outline"></i></span>
                    <input type="text" name="hotel-link" id="hotel-link" class="form-control"
                           placeholder="Link to where you stayed / started">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <label for="story">
                    <i class="flaticon flaticon-folded-map"></i> What happened on your jrrny? Describe where you
                    ate, where you went, what was fun, and what wasn't!
                </label>
                <?php
                $user_id = get_current_user_id();
                if (is_user_in_role($user_id, 'blogger') || is_user_in_role($user_id, 'celebrity') || is_user_in_role($user_id, 'administrator')) {
                    global $wyswig_settings;
                    wp_editor('', 'story', $wyswig_settings);
                } else { ?>
                    <textarea id="story" class="form-control" name="story"><?= $story ?></textarea>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="flaticon flaticon-lighting-button"></i></span>
                    <input type="text" name="insider-tip" id="insider-tip" class="form-control"
                           placeholder="Insider tip? - Tell us the secrets only the locals would know">
                </div>
            </div>
        </div>
        <input type="hidden" name="_token" value="<?php echo $current_user->user_login; ?>"/>
        <input type="hidden" id="jrrny-main-image-id" name="main-image-id" value=""/>

        <div class="form-group">
            <div class="col-xs-12">
                <button id="journey-data-process" class="btn btn-blue btn-lg">
                    Upload
                    <i class="fa processing-icon hide"></i>
                </button>
            </div>
        </div>
    </form>
</div>