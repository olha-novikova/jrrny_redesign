<?php
add_action('wp_ajax_nopriv_delete_post', 'delete_post');
add_action('wp_ajax_delete_post', 'delete_post');

function delete_post()
{
    header('Content-Type: application/json');
    $ret = ['status' => 'fail'];
    try {
        global $current_user;
        wp_get_current_user();
        if(!is_user_logged_in()){
            throw new Exception("Please login!");
        }
        if(!isset($_POST['post-id']) || $_POST['post-id'] == ''){
            throw new Exception("Post not set!");
        }
        $postId = intval($_POST['post-id']);
        if(!current_user_can( 'delete_post', $postId )){
            throw new Exception("Do not have permissin!");
        }
        if(!wp_delete_post($postId)){
            throw new Exception("Can not remove post!");
        }
        $ret = ['status' => 'ok', 'post-id' => $postId];
    } catch (Exception $e) {
        $ret['msg'] = $e->getMessage();
    }
    echo json_encode($ret);
    die();
}
// Rotate img and retunr the url. 
// Orintation flase anticlockwise
function jrrny_rotate_img($imgId, $orientation) {
    require_once( ABSPATH . 'wp-includes/class-wp-image-editor.php' );
    $upload_dir = wp_upload_dir();

    $post = get_post($imgId);

    $mine_type = get_post_mime_type($imgId);
    $parsed = parse_url( wp_get_attachment_url( $imgId ) );
    $path  = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );    
    $img = wp_get_image_editor( ABSPATH.$path );
    if ( is_wp_error( $img ) ) {
        throw new Exception("Can not get image");
        
    }
    if(!$img->supports_mime_type($mine_type)){
        throw new Exception("Not suprtorted minetype!");
        
    }
    if($orientation){
        $img->rotate( 90 );
        $filename = $img->generate_filename();
    }else{
        $img->rotate( 270 );
        $filename = $img->generate_filename();
    }
    $filename = str_replace('//', '/', $filename);

    $saved = $img->save($filename, $mine_type);

    /*if (false === wp_delete_attachment($imgId) ) {
        throw new Exception("Con not delete attachment"); 
    }*/

    $url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $saved['path']);
    $url = preg_replace('/\s+/', '', $url);
    $newImg= array(
          'ID' => intval($imgId),
          'guid' =>  $url,
          'post_type' => 'attachment',
          'post_status' => 'inherit',
          'post_title' => $post->post_title,
          'post_author' => $post->post_author,
          'post_mime_type' => $mine_type,
          'post_parent' => $post->post_parent
    );
    if (wp_insert_attachment( $newImg , true) === 0) {
        throw new Exception("Can not update img!");
    }
    $attached_file = str_replace($upload_dir['basedir'], '', $saved['path']);
    if(false === update_post_meta($imgId, '_wp_attached_file', $attached_file)){
        throw new Exception("Can not update img meta!");
    }
    update_attached_file($imgId, $attached_file);
    return $url;
}