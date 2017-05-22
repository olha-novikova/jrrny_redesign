<?php
header('Content-Type: application/json');
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');
$ret = array();

if (is_user_logged_in()) {
    global $current_user;
    wp_get_current_user();

    if (isset($_POST["place"])
        && $_POST["place"] !== ""
        && isset($_POST["activity"])
        && $_POST["activity"] !== ""
        && isset($_POST["hotel-name"])
        && $_POST["hotel-name"] !== ""
    ) {
        $place = trim($_POST["place"]);
        $activity = trim($_POST["activity"]);
        $title = $place . " " . "for" . " " . $activity;

        $post_category =  array(11);
        if(is_user_in_role( $current_user->ID,  'blogger'  )){
            $post_category = array(16);
        }else if(is_user_in_role( $current_user->ID,  'celebrity'  )){
            $post_category = array(17);
        }
        $jrrny = array(
            "ID" => "",
            "post_type" => "post",
            "post_status" => "publish",
            "post_title" => $title,
            "post_author" => $current_user->ID,
            "post_name" => sanitize_title($title),
            "post_category" => $post_category
        );
        if (isset($_POST["story"]) && $_POST["story"] !== "") {
            $content = trim($_POST["story"]);
            $jrrny["post_content"] = $content;
        }
        if ($create_jrrny_id = wp_insert_post($jrrny, false)) {
            //Add meta
            add_post_meta($create_jrrny_id, '_place', $place);
            add_post_meta($create_jrrny_id, '_activity', $activity);
            
            $placeArray = geocode($place);
            if($placeArray !== false){                
                $placeId = update_place_table($placeArray);
                
                if($placeId !== false){                    
                    add_post_meta($create_jrrny_id, '_place_id', $placeId);
                }
            }
            //Add jrrny to contest
            if (isset($_POST["contest_id"]) && $_POST["contest_id"] > 0) {
                add_post_meta($create_jrrny_id, '_attend_to_contest', $_POST["contest_id"]);                
            }
            //Add hotel image
            if (isset($_POST['imagesh'])) {
                foreach ($_POST['imagesh'] as $key => $image_id) {
                    $meta_imageh_id_name = '_hotel_image_' . ++$key;
                    add_post_meta($create_jrrny_id, $meta_imageh_id_name, wp_get_attachment_url($image_id));
                    add_post_meta($create_jrrny_id, $meta_imageh_id_name . '_id', $image_id);
                    wp_update_post(
                        array(
                            'ID' => $image_id,
                            'post_parent' => $create_jrrny_id
                        )
                    );
                }
                
            }
            add_post_meta($create_jrrny_id, "_hotel_name", trim($_POST['hotel-name']));
            if (isset($_POST["hotel-link"]) && $_POST["hotel-link"] !== "") {
                add_post_meta($create_jrrny_id, '_hotel_link', trim($_POST['hotel-link']));
            }
            //Add othe image
            if (isset($_POST['images']) && is_array($_POST['images'])) {
                foreach ($_POST['images'] as $key => $image_id) {
                    $meta_id_name = '_p_image_' . ++$key;
                    add_post_meta($create_jrrny_id, $meta_id_name, wp_get_attachment_url($image_id));
                    add_post_meta($create_jrrny_id, $meta_id_name . '_id', $image_id);
                    wp_update_post(
                        array(
                            'ID' => $image_id,
                            'post_parent' => $create_jrrny_id
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
                set_post_thumbnail($create_jrrny_id, $mainImageId);
            }else{
                delete_post_thumbnail($create_jrrny_id);
            }
            add_post_meta($create_jrrny_id, '_insider_tip', trim($_POST['insider-tip']));

            //Create return
            $title = get_the_title($create_jrrny_id);
            $link = get_permalink($create_jrrny_id);
            $ret = array(
                'status' => 'ok',
                'post_id' => $create_jrrny_id,
                'permalink' => $link
            );

            $social_urls = array(
                'facebook' => esc_url('https://www.facebook.com/sharer.php?u=' . urlencode($link) . '&amp;t=' . urlencode($title)),
                'twitter' => esc_url('https://twitter.com/home?status=' . urlencode($title . ' ' . $link)),
                'google_plus' => esc_url('https://plus.google.com/share?url=' . urlencode($link) . '&amp;title=' . urlencode($title)),
            );
            $ret["social"] = $social_urls;

        }
    }
} else {
    $ret = array('status' => 'fail', 'msg' => "You must login!");
}

echo json_encode($ret);
die();