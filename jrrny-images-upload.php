<?php
//function
function generateFileName()
{
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789_";
	$name = "";
	for($i=0; $i<12; $i++)
	$name.= $chars[rand(0,strlen($chars))];
	return $name;
}

////////
header('Content-Type: application/json');
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
require_once( $parse_uri[0] . 'wp-admin/includes/image.php' );
	if(is_user_logged_in()) {
		global $current_user;
		wp_get_current_user();
		$ret = array();
		
		if(isset($_FILES["file"]) 
			&& $_FILES["file"]["size"] > 0 
		) {
			$file = $_FILES["file"];
			$wp_upload_dir = wp_upload_dir();
			$target_dir = $wp_upload_dir['basedir']."/journeys/".trim($current_user->user_login)."/";
			if(!file_exists($target_dir)){
			 	mkdir($target_dir, 0755, true);
				if(!file_exists($target_dir)) {
					$ret = array('status' => 'fail', 'msg' => "Can not create dir " . $checkdir . "!");
				}
			}

			$filename_arr = explode('.', $file["name"]);
			$file_type = end($filename_arr);
			$file_name = md5(microtime()) . '.' . $file_type;
			$target_file = $target_dir . $file_name;
			$file_tmp_name = $file["tmp_name"];			
			$check = getimagesize($file_tmp_name);
			if($check !== false){
				if(strtolower($file_type) == "jpg" 
					|| strtolower($file_type) == "png" 
					|| strtolower($file_type) == "jpeg" 
					|| strtolower($file_type) == "gif"
				){
					if(move_uploaded_file($file_tmp_name, $target_file)){
						$target_url = $wp_upload_dir['baseurl']."/journeys/".trim($current_user->user_login)."/".$file_name;
						$post_mime_type = image_type_to_mime_type(exif_imagetype($target_file));
						$attachment = array(
							'post_mime_type' => $post_mime_type,
							'post_title'     =>  date('j F Y h:i') . ' ' . $current_user->user_login,
							'post_content'   => '',
							'post_status'    => 'inherit',
							'post_type' => 'attachment',
							'guid' => $target_url
						);
						$attach_id = wp_insert_attachment( $attachment, $target_file );
						$ret['status'] = 'ok';
						$ret['img_id'] = $attach_id;
						$ret['url'] = $target_url;
						//Generate matadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $target_file );
						wp_update_attachment_metadata( $attach_id, $attach_data );
					}else {
						$ret = array('status' => 'fail', 'msg' => "Can not copy file!");
					}
				}else {
					$ret = array('status' => 'fail', 'msg' => "Wrong file type: ". $file_type);
				}
			}else {
				$ret = array('status' => 'fail', 'msg' => "Wrong file!");
			}
		}else {
			$ret = array('status' => 'fail', 'msg' => "Wrong file!");
		};
	}else {
		$ret = array('status' => 'fail', 'msg' => "You must login!");
	}
	echo json_encode($ret); 
	die();