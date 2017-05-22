<?php
add_action('wp_ajax_nopriv_edit_profile', 'edit_profile');
add_action('wp_ajax_edit_profile', 'edit_profile');

function edit_profile()
{
    header('Content-Type: application/json');
    $ret = ['status' => 'fail'];
    try {
        global $current_user;
        wp_get_current_user();
        if (!is_user_logged_in()) {
            throw new Exception("Please login!");
        }
        if (!isset($_POST['user-id']) || $_POST['user-id'] == '') {
            throw new Exception("User not set!");
        }
        $userId = intval($_POST['user-id']);
        $userArr = ['ID' => intval($_POST['user-id'])];
        if (!current_user_can('edit_user', $userId)) {
            throw new Exception("Do not have permissin!");
        }
        //Require check
        if (!isset($_POST['email']) || $_POST['email'] == '') {
            $ret['type'] = 'email';
            throw new Exception("Email can't be empty!");
        }
        if (!isset($_POST['country']) || $_POST['country'] == '') {
            $ret['type'] = 'country';
            throw new Exception("Country can't be empty!");
        }
        $userLocation = ['country' => $_POST['country']];
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $ret['type'] = 'email';
            throw new Exception("Invalid email format!");
        }
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ret['type'] = 'email';
            throw new Exception("Invalid Email!");
        }
        $userArr['user_email'] = $email;
        $url = trim($_POST['url']);
        if ($url != '' && !filter_var($url, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'url';
            throw new Exception("Invalid URL!");
        }
        $facebook = trim($_POST['facebook']);
        if ($facebook != '' && !filter_var($facebook, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'facebook';
            throw new Exception("Invalid URL!");
        }
        $twitter = trim($_POST['twitter']);
        if ($twitter != '' && !filter_var($twitter, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'twitter';
            throw new Exception("Invalid URL!");
        }
        $google = trim($_POST['google']);
        if ($google != '' && !filter_var($google, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'google';
            throw new Exception("Invalid URL!");
        }
        $tumblr = trim($_POST['tumblr']);
        if ($tumblr != '' && !filter_var($tumblr, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'tumblr';
            throw new Exception("Invalid URL!");
        }
        $linkedin = trim($_POST['linkedin']);
        if ($linkedin != '' && !filter_var($linkedin, FILTER_VALIDATE_URL)) {
            $ret['type'] = 'linkedin';
            throw new Exception("Invalid URL!");
        }
        
        $userArr['user_url'] = $_POST['url'];
        //Update user
        $userRet = wp_update_user($userArr);
        if (is_wp_error($userRet)) {
            throw new Exception("Can not update user!");
        }
        //Update user meta
        if (isset($_POST['first-name']) && $_POST['first-name'] != '') {
            if (strlen($_POST['first-name']) < 2) {
                $ret['type'] = 'first-name';
                throw new Exception("2 characters mininum!");
            }
            update_user_meta($userId, 'first_name', $_POST['first-name']);
        }
        if (isset($_POST['last-name']) && $_POST['last-name'] != '') {
            if (strlen($_POST['last-name']) < 2) {
                $ret['type'] = 'last-name';
                throw new Exception("2 characters mininum!");
            }
            update_user_meta($userId, 'last_name', $_POST['last-name']);
        }
        if (isset($_POST['city']) && $_POST['city'] != '') {
            $userLocation['city'] = $_POST['city'];
        }
        update_user_meta($userId, '_user_location', $userLocation);
        if (isset($_POST['description']) && $_POST['description'] != '') {
            if (strlen($_POST['description']) > 120) {
                $ret['type'] = 'description';
                throw new Exception("120 characters limit!");
            }
            update_user_meta($userId, 'description', $_POST['description']);
        }
        else{            
            update_user_meta($userId, 'description', '');
        }
        if (isset($_POST['quota']) && $_POST['quota'] != '') {
            if (strlen($_POST['quota']) > 100) {
                $ret['type'] = 'quota';
                throw new Exception("100 characters limit!");
            }
            update_user_meta($userId, 'quota', $_POST['quota']);
        }
        update_user_meta($userId, '_notification', (isset($_POST['notification'])) ? 1 : 0);
        
        update_user_meta($userId, 'full_description', $_POST['full_description']);
        update_user_meta($userId, 'specials_offers', $_POST['specials_offers']);
        
        update_user_meta($userId, '_facebook', $facebook);
        update_user_meta($userId, '_twitter', $twitter);
        update_user_meta($userId, '_google', $google);
        update_user_meta($userId, '_tumblr', $tumblr);
        update_user_meta($userId, '_linkedin', $linkedin);
        //set password
        $social_link = plc_brand_social_links($userId);
        if (isset($_POST['password']) && $_POST['password'] != '') {
            if (strlen($_POST['password']) < 6 || !preg_match("/^[a-zA-Z0-9]+$/i", $_POST['password'])) {
                $ret['type'] = 'password';
                throw new Exception("Your password must contain only letters and numbers and be at least 6 characters in length!");
            }
            wp_set_password($_POST['password'], $userId);
            $ret = [
                'status' => 'ok',
                'change-pass' => true,
                'social' => $social_link
            ];
        } else {
            $ret = [
                'status' => 'ok',
                'social' => $social_link
            ];
        }
    } catch (Exception $e) {
        $ret['msg'] = $e->getMessage();
    }
    echo json_encode($ret);
    die();
}

//Save avatar from form to wordpress
function upload_avatar()
{
    header('Content-Type: application/json');
    $ret = ['status' => 'fail'];
    try {
        global $current_user;
        wp_get_current_user();
        if (!is_user_logged_in()) {
            throw new Exception("Please login!");
        }
        if (!isset($_POST['user-id']) || $_POST['user-id'] == '') {
            throw new Exception("User not set!");
        }
        $user_id = intval($_POST['user-id']);
        if (!isset($_FILES["avatar"])
            || $_FILES["avatar"]["size"] <= 0
        ) {
            throw new Exception("Wrong file!");
        }
        $user_login = $_POST['user-login'];
        $file = $_FILES["avatar"];
        $wp_upload_dir = wp_upload_dir();
        $target_dir = $wp_upload_dir['basedir'] . "/journeys/" . trim($user_login) . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
            if (!file_exists($target_dir)) {
                throw new Exception("Can not create dir " . $target_dir . "!");
            }
        }

        $filename_arr = explode('.', $file["name"]);
        $file_type = end($filename_arr);
        $file_name = 'avatar_' . md5(microtime()) . '.' . $file_type;
        $target_file = $target_dir . $file_name;
        $file_tmp_name = $file['tmp_name'];
        $check = getimagesize($file_tmp_name);
        if ($check === false) {
            throw new Exception("Wrong file!");
        }
        if (!in_array(strtolower($file_type), ["jpg", "png", "jpeg", "gif"])) {
            throw new Exception("Wrong file type: " . $file_type);
        }

        $fileUrl = $wp_upload_dir['baseurl'] . "/journeys/" . trim($user_login) . "/" . $file_name;
        if (!move_uploaded_file($file_tmp_name, $target_file)) {
            throw new Exception("Can not copy file!");
        }
        $attachment = array(
            'post_mime_type' => 'image/' . $file_type,
            'post_title' => 'AVATAR ' . date('j F Y h:i') . ' ' . $user_id,
            'post_content' => '',
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'guid' => $fileUrl
        );
        $attach_id = wp_insert_attachment($attachment, $target_file, $user_id);
        //Generate matadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        $avatarUrl = wp_get_attachment_image_src($attach_id, ['240', '240'])[0];
        //Upload avater
        update_user_meta($user_id, 'wsl_current_user_image', $avatarUrl);
        $ret = [
            'status' => 'ok',
            'avatar' => $avatarUrl
        ];
    }catch (Exception $e) {
        $ret['msg'] = $e->getMessage();
    }
    echo json_encode($ret);
    die();
}
add_action('wp_ajax_nopriv_edit_avatar', 'upload_avatar');
add_action('wp_ajax_edit_avatar', 'upload_avatar');

//Ajax action to cage head autho photo
function edit_head_img()
{
    header('Content-Type: application/json');
    $ret = ['status' => 'fail'];
    try {
        global $current_user;
        wp_get_current_user();
        if (!is_user_logged_in()) {
            throw new Exception("Please login!");
        }
        if (!isset($_POST['user-id']) || $_POST['user-id'] == '') {
            throw new Exception("User not set!");
        }
        $userId = intval($_POST['user-id']);
        $userArr = ['ID' => intval($_POST['user-id'])];
        if (!current_user_can('edit_user', $userId)) {
            throw new Exception("Do not have permissin!");
        }
        //Check file
        if (!isset($_FILES["head-img"])
        ) {
            throw new Exception("File not set!");
        }
        if ($_FILES['head-img']['error'] !== UPLOAD_ERR_OK) {
            $code = $_FILES['head-img']['error'];
            if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE) {
                throw new Exception("The uploaded file exceeds the upload max filesize!");
            }
            throw new Exception("Upload failed with error code " . $code);
        }

        $file = $_FILES["head-img"];
        list($width, $height, $type, $attr) = array_values(getimagesize($file['tmp_name']));
        if ($height < 240) {
            throw new Exception("Min height 240px!");
        }
        if ($width < 500) {
            throw new Exception("Min width 500px!");
        }


        $wp_upload_dir = wp_upload_dir();
        $target_dir = $wp_upload_dir['basedir'] . "/journeys/" . trim($current_user->user_login) . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
            if (!file_exists($target_dir)) {
                throw new Exception("Can not create dir " . $checkdir . "!");
            }
        }

        $filename_arr = explode('.', $file["name"]);
        $file_type = end($filename_arr);
        $file_name = 'head_' . md5(microtime()) . '.' . $file_type;
        $target_file = $target_dir . $file_name;
        $file_tmp_name = $file['tmp_name'];
        if ($file['size'] > 2097152) {
            throw new Exception("Max image size 2MB!");
        }
        $check = getimagesize($file_tmp_name);
        if ($check === false) {
            throw new Exception("Wrong file size!");
        }
        //Check file type
        if (strtolower($file_type) != "jpg"
            && strtolower($file_type) != "png"
            && strtolower($file_type) != "jpeg"
            && strtolower($file_type) != "gif"
        ) {
            throw new Exception("Wrong file type: " . $file_type);
        }
        $fileUrl = $wp_upload_dir['baseurl'] . "/journeys/" . trim($current_user->user_login) . "/" . $file_name;
        if (!move_uploaded_file($file_tmp_name, $target_file)) {
            throw new Exception("Can not copy file!");
        }

        //Save file on database
        $attachment = array(
            'post_mime_type' => 'image/' . $file_type,
            'post_title' => 'HEAD ' . date('j F Y h:i') . ' ' . $userId,
            'post_content' => '',
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'guid' => $fileUrl
        );
        $attach_id = wp_insert_attachment($attachment, $target_file, $userId);
        //Generate matadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attach_data);

        //Set image o user
        $head_img_id = get_user_meta($userId, 'wsl_current_user_head_image', true);
        if ($head_img_id) {
            //delete is exist
            wp_delete_attachment(intval($head_img_id));
        }
        update_user_meta($userId, 'wsl_current_user_head_image', $attach_id);

        $ret = [
            'status' => 'ok',
            'url' => wp_get_attachment_image_src($attach_id, ['1920', '360'])[0]
        ];

    } catch (Exception $ex) {
        $ret['msg'] = $ex->getMessage();
    }
    echo json_encode($ret);
    die();
}

add_action('wp_ajax_nopriv_edit_head_img', 'edit_head_img');
add_action('wp_ajax_edit_head_img', 'edit_head_img');