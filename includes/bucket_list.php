<?php

function plc_change_completed_bucket_list()
{
    header('Content-Type: application/json');

    global $wpdb;

    $userId = $_POST['user'];
    $postId = $_POST['post'];
    $txt = isset($_POST['txt']) ? $_POST['txt'] : '';

    $response = array();

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';
    if (!empty($userId)) {
        $sql = "SELECT id, completed FROM ";
        $sql .= $table_name . ' ';
        $sql .= "WHERE post_id= ";
        $sql .= $postId . ' ';
        $sql .= "AND user_id= ";
        $sql .= "'" . $userId . "'";
        $sql .= ';';
        $result = $wpdb->get_row($sql);
        if ($result->completed > 0) {
            $sql = "UPDATE " . $table_name . " SET ";
            $sql .= "completed = '0' ";
            $sql .= "WHERE id='" . $result->id . "'";
            $ret = $wpdb->get_results($sql);  
            
            $response['msg'] = 'add to completed';
            $response['addIcon'] = 'fa-toggle-off';
            $response['removeIcon'] = 'fa-toggle-on';
            
            if($txt){
                $response['msg'] = 'Completed';
                $response['addIcon'] = 'fa-square-o';
                $response['removeIcon'] = 'fa-check-square-o';
            }
        }
        else {
            $sql = "UPDATE " . $table_name . " SET ";
            $sql .= "completed = '1' ";
            $sql .= "WHERE id='" . $result->id . "'";
            $ret = $wpdb->get_results($sql);  

            $response['msg'] = 'remove from completed';
            $response['addIcon'] = 'fa-toggle-on';
            $response['removeIcon'] = 'fa-toggle-off';
            if($txt){
                $response['msg'] = 'Completed';
                $response['addIcon'] = 'fa-check-square-o';
                $response['removeIcon'] = 'fa-square-o';
            }
        }
        $response['list'] = plc_get_completed_bucket_list();
    }

    echo json_encode($response);
    die();
}

add_action('wp_ajax_change_completed_bucket_list', 'plc_change_completed_bucket_list');
add_action('wp_ajax_nopriv_change_completed_bucket_list', 'plc_change_completed_bucket_list');

function plc_change_bucket_list()
{
    header('Content-Type: application/json');

    global $wpdb;

    $userId = $_POST['user'];
    $postId = $_POST['post'];
    $txt = isset($_POST['txt']) ? $_POST['txt'] : '';

    $response = array();

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';
    if (!empty($userId)) {
        $sql = "SELECT id FROM ";
        $sql .= $table_name . ' ';
        $sql .= "WHERE post_id= ";
        $sql .= $postId . ' ';
        $sql .= "AND user_id= ";
        $sql .= "'" . $userId . "'";
        $sql .= ';';
        $result = $wpdb->get_var($sql);
        if ($result) {
            $sql = "DELETE FROM " . $table_name . " ";
            $sql .= "WHERE id='" . $result . "'";
            $ret = $wpdb->get_results($sql);

            $response['msg'] = 'add to bucket';
            $response['addIcon'] = 'fa-calendar-plus-o';
            $response['removeIcon'] = 'fa-calendar-minus-o';
            
            if($txt){
                $response['msg'] = 'Bucket List';
                $response['addIcon'] = 'fa-square-o';
                $response['removeIcon'] = 'fa-check-square-o';
            }
            $response['action'] = 'remove';
        }
        else {
            $sql = "INSERT INTO " . $table_name . " ";
            $sql .= "(post_id, user_id) ";
            $sql .= "VALUES ( ";
            $sql .= $postId . ", ";
            $sql .= "'" . $userId . "' ";
            $sql .= ");";
            $ret = $wpdb->get_results($sql);

            $response['msg'] = 'remove from bucket';
            $response['addIcon'] = 'fa-calendar-minus-o';
            $response['removeIcon'] = 'fa-calendar-plus-o';
            
            $completed = '<span class="meta-item meta-item-likes small sp-bucket-completed-post">
                            <a class="jrrny-bucket-completed-post" data-post="' . $postId . '" data-user="' . $userId . '">
                                <span class="list-text" data-toggle="tooltip" data-container="body" data-placement="bottom" title="add to completed"><i class="fa fa-toggle-off"></i></span>
                            </a>
                        </span>';
            
            if($txt){
                $response['msg'] = 'Bucket List';
                $response['addIcon'] = 'fa-check-square-o';
                $response['removeIcon'] = 'fa-square-o';
                $completed = '<span class="meta-item meta-item-likes small sp-bucket-completed-post">
                            <a class="jrrny-bucket-completed-post btn btn-transparent-white" data-post="' . $postId . '" data-user="' . $userId . '" data-txt="true">
                                <span class="list-text">Completed <i class="fa fa-square-o"></i></span>
                            </a>
                        </span>';
            }
            $response['completed'] = $completed;
            $response['action'] = 'add';
            
            
            $plcNotifications = PlcNotifications::get_instance();
            $mail = $plcNotifications->plc_send_notification('add_bucket', $postId, $userId);
        }
        if(!$txt){
            $response['list'] = plc_get_bucket_list();
        }
    }

    echo json_encode($response);
    die();
}

add_action('wp_ajax_change_bucket_list', 'plc_change_bucket_list');
add_action('wp_ajax_nopriv_change_bucket_list', 'plc_change_bucket_list');

function plc_is_on_bucket($postId, $userId)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';
    if (!empty($userId)) {
        $sql = "SELECT id FROM ";
        $sql .= $table_name . ' ';
        $sql .= "WHERE post_id = ";
        $sql .= "'" . $postId . "' ";
        $sql .= "AND user_id = ";
        $sql .= "'" . $userId . "'";
        $sql .= ';';
        $result = $wpdb->get_var($sql);
        
        if ($result) {
            return true;
        }
    }
    return false;
}

function plc_is_bucket_completed($postId, $userId)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';
    if (!empty($userId)) {
        $sql = "SELECT id FROM ";
        $sql .= $table_name . ' ';
        $sql .= "WHERE post_id = ";
        $sql .= "'" . $postId . "' ";
        $sql .= "AND user_id = ";
        $sql .= "'" . $userId . "' ";
        $sql .= "AND completed = '1' ";
        $sql .= ';';
        $result = $wpdb->get_var($sql);
        
        if ($result) {
            return true;
        }
    }
    return false;
    
}

function plc_count_bucket_list($user_id, $completed = false)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';

    $sql = "SELECT COUNT(*) ";
    $sql .= "FROM ";
    $sql .= '`' . $table_name . '` ';
    $sql .= "WHERE ";
    $sql .= "user_id = '" . $user_id . "' ";
    if ($completed) {
        $sql .= "AND completed = '" . $completed . "' ";
    }
    $sql .= "GROUP BY ";
    $sql .= "user_id ";
    $sql .= "LIMIT 1";

    $result = $wpdb->get_var($sql);
    
    return $result;
}

function plc_get_bucket_list_array($user_id, $completed = false)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_bucket_list';

    $sql = "SELECT post_id ";
    $sql .= "FROM " . $table_name . " ";
    $sql .= "WHERE ";
    $sql .= "user_id = '" . $user_id . "' ";
    if ($completed) {
        $sql .= "AND completed = '" . $completed . "' ";
    }
    $sql .= "ORDER BY timestamp DESC ";
    $sql .= "LIMIT 12 ";

    $result = $wpdb->get_results($sql);
    
    $array = array();
    foreach ($result as $value) {
        $array[] = intval($value->post_id);
    }
    return $array;
}

function bucket_list_create_db()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $jrrny_bucket_list = $wpdb->prefix . 'jrrny_bucket_list';
    $users_table = $wpdb->prefix . 'users';
    $posts_table = $wpdb->prefix . 'posts';

    $sql = "CREATE TABLE IF NOT EXISTS $jrrny_bucket_list ( ";
    $sql .= "id bigint(20) unsigned AUTO_INCREMENT, ";
    $sql .= "post_id bigint(20) unsigned NOT NULL, ";
    $sql .= "user_id bigint(20) unsigned NOT NULL, ";
    $sql .= "completed tinyint(1) unsigned NULL DEFAULT 0, ";
    $sql .= "timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, ";
    $sql .= "PRIMARY KEY (id), ";
    $sql .= "KEY fk_user (user_id), ";
    $sql .= "KEY fk_post (post_id), ";

    $sql .= "CONSTRAINT " . $jrrny_bucket_list . "_ibfk_1  FOREIGN KEY (user_id) REFERENCES $users_table(ID) ";
    $sql .= "ON DELETE CASCADE ";
    $sql .= "ON UPDATE CASCADE, ";

    $sql .= "CONSTRAINT " . $jrrny_bucket_list . "_ibfk_2  FOREIGN KEY (post_id) REFERENCES $posts_table(ID) ";
    $sql .= "ON DELETE CASCADE ";
    $sql .= "ON UPDATE CASCADE ";
    $sql .= ") $charset_collate;";

    $wpdb->get_results($sql);
}

add_action('init', 'bucket_list_create_db');

function plc_get_bucket_list()
{
    global $current_user;
    
    $count_bucket_list = plc_count_bucket_list($current_user->ID);
    if($count_bucket_list > 2){ 
        
        $bucket_list = plc_get_bucket_list_array($current_user->ID);

        $limit = $count_bucket_list > 12 ? 12 : $count_bucket_list;
        $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
            'post__in' => $bucket_list,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => $limit,
            'limit' => $limit,
            'show_pagination' => false
        ];
        ob_start();
        ts_blog_loop('3columncarousel', $atts);
        return ob_get_clean();
    
    }
    else {               
     $return = '<p class="tab-text">Your bucket list is empty or less then 3</p>';
    } 
    return $return;
}

function plc_get_completed_bucket_list()
{
    global $current_user;
    
    $count_complted_bucket_list = plc_count_bucket_list($current_user->ID, 1);
    if($count_complted_bucket_list > 2){ 
        $completed_bucket_list = plc_get_bucket_list_array($current_user->ID, 1);
        $limit = $count_complted_bucket_list > 12 ? 12 : $count_complted_bucket_list;
        $atts = [
            'post_type' => array('post', 'sponsored_post', 'featured_destination', 'contest'),
            'post__in' => $completed_bucket_list,
            'default_query' => false,
            'orderby' => 'post__in',
            'infinite_scroll' => 'no',
            'posts_per_page' => $limit,
            'limit' => $limit,
            'show_pagination' => false
        ];
        ob_start();
        ts_blog_loop('3columncarousel', $atts);
        return ob_get_clean();    
    }
    else {               
     $return = '<p class="tab-text">You didn\'t completed any Jrrnys from your bucket list or this list is less then 3</p>';
    } 
    return $return;
}