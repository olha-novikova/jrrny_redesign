<?php
	if(isset($_POST["jrrny_id"]) && $_POST["jrrny_id"] > 0 && isset($_POST["formData"]) && $_POST["formData"] !== ""):
		$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		require_once( $parse_uri[0] . 'wp-load.php' );
		global $wpdb;
		$jrrny_id = (int)$_POST["jrrny_id"];
		$attachments = get_attached_media('image', $jrrny_id);
		$attachment_ids = array();
		foreach ($attachments as $attachment):
			array_push($attachment_ids, $attachment->ID);
		endforeach;
		$formData = array();
		parse_str($_POST["formData"], $formData);
		if(isset($formData["hotel-name"]) && $formData["hotel-name"] !== ""):
			$hotel_name = $formData["hotel-name"];
			if(isset($formData["hotel-link"]) && $formData["hotel-link"] !== ""):
				$hotel_link = $formData["hotel-link"];
			endif;
			if(isset($formData["jrrny-story"]) && $formData["jrrny-story"] !== ""):
				$story = strip_tags($formData["jrrny-story"]);
			endif;
			if($_FILES && sizeof($_FILES) > 0):
				$wp_upload_dir = wp_upload_dir();
				$checkdir = $wp_upload_dir['basedir']."/journeys/".trim($current_user->user_login)."/";
				if(file_exists($checkdir)):
					$target_dir = $checkdir;
				else:
				 	mkdir($checkdir, 0755, true);
					if(file_exists($checkdir)):
				 		$target_dir = $checkdir;
					endif;
				endif;
				if(isset($_FILES["jrrny-image"]) && sizeof($_FILES["jrrny-image"]) > 0):
					//require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$jrrny_images = $_FILES["jrrny-image"];
					$jrrny_images_ord = array();
					foreach ($jrrny_images as $key => $values):
						for($i=0; $i<sizeof($values);$i++):
							$jrrny_images_ord[$i][$key] = $values[$i];
						endfor;
					endforeach;
					for($i=0;$i<sizeof($jrrny_images_ord);$i++):
						$file_name = preg_replace("/[^a-zA-Z0-9.]+/", "", preg_replace('/[\s+]/', '', $jrrny_images_ord[$i]["name"]));
						// Escape file existment
						$file_name = preg_replace("/[\s+.]/", "", microtime())."-".$file_name;
						$target_file = $target_dir . basename($file_name);
						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
						$file_tmp_name = $jrrny_images_ord[$i]["tmp_name"];
						$file_type = $jrrny_images_ord[$i]["type"];
						$check = getimagesize($file_tmp_name);
						if($check !== false):
							if(strtolower($imageFileType) == "jpg" || strtolower($imageFileType) == "png" || strtolower($imageFileType) == "jpeg" || strtolower($imageFileType) == "gif"):
								if(move_uploaded_file($file_tmp_name, $target_file)):
										$new_attachment = array(
										  	'ID' => (int)$attachment_ids[$i+1],
										  	'guid' => $wp_upload_dir['baseurl'].'/journeys/'.trim($current_user->user_login).'/'.basename($file_name),
										  	'post_mime_type' => $file_type,
										  	'post_title'     => preg_replace( '/\.[^.]+$/', '', $target_file),
										  	'post_content'   => '',
										  	'post_status'    => 'inherit'
										);
										$attach_id = wp_insert_attachment( $new_attachment, $target_file, $jrrny_id );
								endif;
							endif;
						endif;
					endfor;
					//var_dump($attachment_ids);
				endif;
			endif;

		endif;
	endif;
	die();