<?php
/**
 * User: polcode
 * Date: 22.01.16
 * Time: 13:50
 */
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

    if (!isset($_POST["image-id"]) || $_POST["image-id"] == null) {
        throw new Exception("image-id require!");
    }
    if(!wp_delete_attachment(intval($_POST["image-id"]))){
        throw new Exception("Can not delete image");
    }
    $ret = array('status' => 'ok');

} catch (Exception $e) {
    $ret['status'] = "fail";
    $ret['msg'] = $e->getMessage();
}
echo json_encode($ret);
die();