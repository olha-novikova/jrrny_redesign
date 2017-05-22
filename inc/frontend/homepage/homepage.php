<?php

function plc_get_following_user_posts($is_following = null, $rand = false, $post_type = array("'post'", "'sponsored_post'", "'featured_destination'", "'contest'")){
    
    global $wpdb;

    $table_name = $wpdb->prefix . 'posts';

    $sql = "SELECT ID ";
    $sql .= "FROM " . $table_name . " ";
    $sql .= "WHERE post_type IN (" . implode(',', $post_type) . ") ";
    if($is_following){ 
        $sql .= "AND post_author IN (" . implode(',', $is_following) . ") ";
    }
    $sql .= "AND post_status = 'publish' ";
    $sql .= "ORDER BY RAND() ";
    if($rand){
        $sql .= "GROUP BY post_author ";        
    }
    $sql .= "LIMIT 12 ";

    $result = $wpdb->get_results($sql);
    $array = array();
    foreach ($result as $key => $value) {
        $array[] = intval($value->ID);
    }
    return $array;
}
