<?php
/**
 * Created by PhpStorm.
 * User: amichna
 * Date: 11.04.16
 * Time: 10:21
 */

function get_place_posts()
{
    global $current_user, $wpdb;
    wp_get_current_user();
    header('Content-Type: application/json');
    $ret = array('status' => 'fail');
    try {
        $placeId = intval($_POST['id']);
        $table_place_name = $wpdb->prefix . "jrrny_place";

        $sql = 'SELECT * ';
        $sql .= 'FROM ' . $table_place_name . ' ';
        $sql .= 'WHERE id = ' . $placeId . ' ';
        $sql .= 'LIMIT 1';
        $place = $wpdb->get_results($sql, ARRAY_A);
        if (empty($place)) {
            throw new Exception('Place not found!');
        }

        $table_meta_name = $wpdb->prefix . "postmeta";

        $sql = 'SELECT post_id ';
        $sql .= 'FROM ' . $table_meta_name . '  ';
        $sql .= 'WHERE meta_key like "_place_id" ';
        $sql .= 'AND meta_value = ' . $placeId . ' ';
        $sql .= 'GROUP BY post_id ';
        $postMeta = $wpdb->get_results($sql, ARRAY_A);
        $posts = [];

        $postIds = array_column($postMeta, 'post_id');
        foreach ($postIds as $postId) {
            $post = get_post($postId);
            $posts[] = [
                'id' => $post->ID,
                'url' => get_permalink($post->ID),
                'title' => $post->post_title,
                'img' => wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),
                'authorid' => $post->post_author,
                'author' => get_the_author_meta('nicename', $post->post_author),
                'authorurl' => get_author_posts_url( $post->post_author )

            ];
        }

        $ret = [
            'status' => 'ok',
            'posts' => $posts,
            'title' => $place[0]['title']
        ];
    } catch (Exception $e) {
        $ret['msg'] = $e->getMessage();
    }
    echo json_encode($ret);
    die();
}


add_action("wp_ajax_place_posts", "get_place_posts");
add_action("wp_ajax_nopriv_place_posts", "get_place_posts");

function reload_map()
{
    global $wpdb;
    
    $table_meta_name = $wpdb->prefix . "postmeta";
    
    update_metabox_place();
   
    $sql = 'SELECT post_id, meta_value ';
    $sql .= 'FROM ' . $table_meta_name . '  ';
    $sql .= 'WHERE meta_key like "_place" ';
    $sql .= 'AND meta_value is not null ';
    $posts = $wpdb->get_results($sql, ARRAY_A);
    foreach ($posts as $post) {
        $placeId = get_post_meta($post['post_id'], '_place_id', true);
        if (empty($placeId)) {
            $address = $post['meta_value'];
            if(!$address){
                $address = update_metaboxes( $post['post_id'], get_the_title( $post['post_id'] ) );
            }
            $placeArray = geocode($address);
            
            if($placeArray !== false){                
                $placeId = update_place_table($placeArray);
                
                if($placeId !== false){                    
                    update_post_meta($post['post_id'], '_place_id', $placeId);
                }
            }
        }
    }

}

add_action('render_map', 'reload_map', 10, 3);

function update_metabox_place()
{
    global $wpdb;
    
    $table_meta_name = $wpdb->prefix . "posts";
    
    $postlist = get_posts( 'post_type=post&post_status=publish' );
    
    $sql = 'SELECT ID, post_title ';
    $sql .= 'FROM ' . $table_meta_name . '  ';
    $sql .= 'WHERE post_type = "post" ';
    $sql .= 'AND post_status = "publish" ';
    
    $posts = $wpdb->get_results($sql);
    foreach ($posts as $post) {
       $id = $post->ID;
       $place = get_post_meta($id, "_place", true);
       if(!$place){
           update_metaboxes( $id, $post->post_title );
       }
    }    
}

function update_metaboxes($postId, $title)
{    
    if(!$title){
        return false;
    }
    $title = explode('for', $title);
    
    $place = $title[0];
    $activity = isset($title[1]) ? $title[1] : '';
    
    update_post_meta($postId, '_place', $place);
    update_post_meta($postId, '_activity', $activity);
    
    return $place;
}

/**
 * Geocode adress of place
 * @param $address
 * @return array|bool
 */
function geocode($address)
{
    $address = urlencode($address);
    $url = "https://maps.google.com/maps/api/geocode/json?address={$address}&key=" . MAPS_API_KEY;
    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);

    if (!($resp['status'] == 'OK')) {
        return false;
    }

    $lat = $resp['results'][0]['geometry']['location']['lat'];
    $long = $resp['results'][0]['geometry']['location']['lng'];
    $formatted_address = $resp['results'][0]['formatted_address'];

    if (!($lat && $long && $formatted_address)) {
        return false;
    }

    $data_arr = [
        'lat' => $lat,
        'lon' =>$long,
        'address' => $formatted_address
    ];
    return $data_arr;
}

/**
 * Update place table and return place id
 * @param $placeArr
 * @return int
 */
function update_place_table($placeArr){
    //dd($placeArr);

    global $wpdb;

    $table_place_name = $wpdb->prefix . "jrrny_place";

    $sql = 'SELECT id ';
    $sql .= 'FROM ' . $table_place_name . '  ';
    $sql .= 'WHERE lat = ' . $placeArr['lat'].' ';
    $sql .= 'AND lon = ' . $placeArr['lon'].' ';
    $sql .= 'LIMIT 1 ';
    $place = $wpdb->get_results($sql, ARRAY_A);
    if(empty($place)){
        $result = $wpdb->insert(
            $table_place_name,
            array(
                'title' => $placeArr['address'],
                'lat' => $placeArr['lat'],
                'lon' => $placeArr['lon']
            ),
            array(
                '%s',
                '%s',
                '%s'
            )
        );
        if($result === false){
            return false; //can not insert
        }
        $id = $wpdb->insert_id;
        return intval($id);
    }
    return intval($place[0]['id']);
}