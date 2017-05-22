<?php
get_header();
get_template_part('top');

the_post();
?>
<?php
$post_all_images = get_attached_media( "image", get_the_id() );
//$hotelImageId = get_post_meta(get_the_ID(), "_hotel_image_id", true);

$hotelImagesId = [];
for ($i = 1; $i <= 2 ; ++$i){
    $id = get_post_meta(get_the_ID(), '_hotel_image_' . $i . '_id', true);
    if(!empty($id)){
        $hotelImagesId[$i] = $id;
        
    }
}

$post_images = [];
for ($i = 1; $i <= 10 ; ++$i){
    $id = get_post_meta(get_the_ID(), '_p_image_' . $i . '_id', true);
    if(!empty($id)){
        $post_images[$i] = $id;
    }
}
?>

<?php
if($http_hotel_link = get_post_meta(get_the_ID(), "_hotel_link", true)){
    if(empty(parse_url($http_hotel_link)["scheme"])){
        $http_hotel_link = "http://".$http_hotel_link;
    }
}
?>

<div id="main-container-wrap" class="edit">
    <div class="container">
        <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
            <div class="uploader-header">
                <h2> Edit  jrrny</h2>
            </div>
        </div>
        <!-- form container -->

        <form id="form-journey" class="form-horizontal" method="post" action="<?php echo home_url(); ?>/upload">
            <input type="hidden" name="_token" value="<?php echo $current_user->user_login;?>" />
            <input type="hidden" name="post_id" value="<?=get_the_ID()?>" />
            <?php $thumbId = get_post_thumbnail_id(get_the_id()); ?>
            <input type="hidden" id="jrrny-main-image-id" name="main-image-id" value="<?= $thumbId ?>" data-url="<?= wp_get_attachment_url($thumbId) ?>"/>
            <?php
            if ($hotelImagesId) :
                foreach ($hotelImagesId as $hotelImageId) :
                    $imageInfo = wp_get_attachment_image_src($hotelImageId, 'thumbnail');
                    if (is_array($imageInfo)) :
                        $url = $imageInfo[0];
                        ?>
                        <input id="jrrny-himage-<?=$hotelImageId?>" data-url="<?=$url?>" type="hidden" value="<?=$hotelImageId?>" name="imagesh[]">
                    <?php endif;
                endforeach;

            endif;

            foreach ($post_images as $imageId) :
                $imageInfo = wp_get_attachment_image_src($imageId, 'thumbnail');
                if (is_array($imageInfo)) :
                    $url = $imageInfo[0];
                    ?>
                    <input id="jrrny-image-<?=$imageId?>" data-url="<?=$url?>" type="hidden" value="<?=$imageId?>" name="images[]">
                <?php endif;
            endforeach; ?>

            <?php get_contest_input_uploader_tool($current_user->ID);?>
            <div class="row">
                <div class="col-md-8">
                    <?php $place = get_post_meta(get_the_ID(), "_place", true); ?>
                    <input type="text" name="place" id="place-jrrny" class="form-control"
                           placeholder="Where you went - City & State"
                           value="<?php echo $place; ?>">
                    <?php $activity = get_post_meta(get_the_ID(), "_activity", true); ?>
                    <input type="text" name="activity" id="activity-jrrny" class="form-control"
                           placeholder="What did you do?"
                           value="<?php echo $activity; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <?php $hotel_name = get_post_meta(get_the_ID(), "_hotel_name", true); ?>
                            <input type="text" name="hotel-name" id="hotel-name" class="form-control"
                                   placeholder="Hotel Name or Starting Point" value="<?php echo $hotel_name; ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="hotel-link" id="hotel-link" class="form-control"
                                   placeholder="Link to where you stayed / started" value="<?php echo $http_hotel_link; ?>">
                        </div>
                    </div>
                    <?php
                    $story = get_the_content();

                    global $wyswig_settings;
                    wp_editor( $story, 'story', $wyswig_settings );?>

                    <?php $insiderTip = get_post_meta(get_the_ID(), "_insider_tip", true);?>
                    <input type="text" name="insider-tip" id="insider-tip" class="form-control"
                           placeholder="Insider tip? - Tell us the secrets only the locals would know" value="<?php echo $insiderTip; ?>">
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
                    <button id="journey-data-process" class="btn btn-blue btn-lg">
                        Save
                        <i class="fa processing-icon hide"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>