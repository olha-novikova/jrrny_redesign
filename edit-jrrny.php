<?php
header('Content-Type: application/json');
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');
$ret = array();

try {
    if (!is_user_logged_in()) {
        throw new Exception("You must be logged in!");
    }
    global $current_user;
    wp_get_current_user();
    if(current_user_can( 'edit_post', get_the_ID() )){
        throw new Exception("Do not have permission!");
    }
    if (!isset($_POST["post_id"])
        || $_POST["post_id"] == ""
        || !isset($_POST["place"])
        || $_POST["place"] == ""
        || !isset($_POST["activity"])
        || $_POST["activity"] == ""
        || !isset($_POST["hotel-name"])
        || $_POST["hotel-name"] == ""
    ) {
        throw new Exception("Wrong post data!");
    }
    $postId = intval($_POST["post_id"]);
    $place = trim($_POST["place"]);
    $activity = trim($_POST["activity"]);
    $title = $place . " " . "for" . " " . $activity;
    $jrrny = array(
        "ID" => $postId,
        "post_type" => "post",
        "post_status" => "publish",
        "post_title" => $title,
        "post_author" => $current_user->ID,
        "post_name" => sanitize_title($title)
    );
    if (isset($_POST["story"]) && $_POST["story"] !== "") {
        $jrrny["post_content"] = trim($_POST["story"]);
    }
    wp_update_post($jrrny);
    //Update meta
    update_post_meta($postId, '_place', $place);
    update_post_meta($postId, '_activity', $activity);
    
    $placeArray = geocode($place);
    if($placeArray !== false){                
        $placeId = update_place_table($placeArray);

        if($placeId !== false){                    
            update_post_meta($postId, '_place_id', $placeId);
        }
    }
            
    //Add hotel image
    update_post_meta($postId, "_hotel_name", trim($_POST['hotel-name']));
    update_post_meta($postId, '_hotel_link', trim($_POST['hotel-link']));
    //Edit other image
    if (isset($_POST['images']) && is_array($_POST['images'])) {
        foreach ($_POST['images'] as $key => $image_id) {
            $meta_id_name = '_p_image_' . ++$key;
            update_post_meta($postId, $meta_id_name, wp_get_attachment_url($image_id));
            update_post_meta($postId, $meta_id_name . '_id', $image_id);
            wp_update_post(
                array(
                    'ID' => $image_id,
                    'post_parent' => $postId
                )
            );
        }

        //Add thumbnails
        $mainImageId = reset($_POST['images']); //default first main
        if (isset($_POST['main-image-id']) && $_POST['main-image-id'] !== "") {
            $mainImageId = intval($_POST['main-image-id']);
            if(wp_get_attachment_url(intval($_POST['main-image-id']))){
                $mainImageId = intval($_POST['main-image-id']);
            }
        }
        set_post_thumbnail($postId, $mainImageId);
    }else{
        delete_post_thumbnail($postId);
    }
    if (isset($_POST['imagesh']) && is_array($_POST['imagesh'])) {
        foreach ($_POST['imagesh'] as $key => $image_id) {
            $meta_imageh_id_name = '_hotel_image_' . ++$key;
            update_post_meta($postId, $meta_imageh_id_name, wp_get_attachment_url($image_id));
            update_post_meta($postId, $meta_imageh_id_name . '_id', $image_id);
            wp_update_post(
                array(
                    'ID' => $image_id,
                    'post_parent' => $postId
                )
            );
        }

    }
    update_post_meta($postId, '_insider_tip', trim($_POST['insider-tip']));

    $title = get_the_title($postId);
    $link = get_permalink($postId);
    $ret = array(
        'status' => 'ok',
        'post_id' => $postId,
        'permalink' => $link,
        'title' => $title
    );
} catch (Exception $e) {
    $ret['status'] = "fail";
    $ret['msg'] = $e->getMessage();
}
echo json_encode($ret);
die();