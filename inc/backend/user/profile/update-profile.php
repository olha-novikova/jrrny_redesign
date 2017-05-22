<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
if(is_user_logged_in()):
	global $current_user;
	wp_get_current_user();
	$salt = "M6WzCZm7*e+qfz4|2k8QxcTLNk0j1K|ngZK%vzb%2m~Q8jY5k+a*ek_e%^Fr";
	if(isset($_POST["_user_token"]) && $_POST["_user_token"] !== ""):
	 	if(crypt($current_user->user_login, $salt) == $_POST["_user_token"]):
	 		if(isset($_POST["email"]) && $_POST["email"] !== ""):
	 			if(isset($_POST["country"]) && $_POST["country"] !== ""):
	 				if(isset($_POST["password"]) && $_POST["password"] !== ""):
	 					$errors = 0;
	 					$password = $_POST["password"];
	 					$email = $_POST["email"];
	 					$country = $_POST["country"];
	 					$new_location = array("country" => $country);
	 					if(isset($_POST["city"]) && $_POST["city"] !== ""):
	 						$city = $_POST["city"];
	 						$new_location["city"] = $city;
	 					endif;
	 					$user_id = $current_user->ID;
	 					if(isset($_POST["bio"]) && $_POST["bio"] !== ""):
	 						$bio = $_POST["bio"];
	 					else:
	 						$bio = "";
	 					endif;
	 					$args = array(
	 						'ID' => $user_id,
	 						'user_email' => $email,
	 						'user_pass' => $password,
	 						'description' => $bio
	 					);
	 					$update_user = wp_update_user($args);
	 					if(is_wp_error($update_user)):
	 						$errors = $errors + 1;
	 					endif;
	 					if(!($old_location = get_user_meta($current_user->ID, "_user_location", true))) {
	 						add_user_meta( $current_user->ID, "_user_location", $new_location );
	 					}
	 					else {
	 						update_user_meta( $current_user->ID, "_user_location", $new_location, $old_location );
	 					}

						if(isset($_FILES["profile-pic"]) && $_FILES["profile-pic"]["size"] > 0):
							$file = $_FILES["profile-pic"];
							$file_name = $file["name"];
							$file_type = $file["type"];
							$file_tmp_name = $file["tmp_name"];
							$filesize = $file["size"];
							$checkdir = $_SERVER["DOCUMENT_ROOT"]."/jrrny_dev/wp-content/uploads/user_avatars/".trim($current_user->user_login)."/";
							$target_dir = file_exists($checkdir) ? $checkdir : mkdir($checkdir, 0705, true);
							if(file_exists($checkdir)) {
								$file_name = preg_replace("/[^a-zA-Z0-9.]+/", "", preg_replace('/[\s+]/', '', $file_name));
								// Escape file existment
								$file_name = preg_replace("/[\s+.]/", "", microtime())."-".$file_name;
								$target_file = $target_dir . basename($file_name);
								// Removing all files in user directory
								$files_to_remove = glob($checkdir.'/*', GLOB_BRACE);
								foreach ($files_to_remove as $file_to_remove) {
									if(is_file($file_to_remove)) {
										unlink($file_to_remove);
									}
								}
								if(move_uploaded_file($file_tmp_name, $target_file)) {
									if(!($avatar = get_user_meta($current_user->ID, '_user_avatar', true))):
										add_user_meta( $current_user->ID, '_user_avatar', $file_name );
									else:
										update_user_meta( $current_user->ID, '_user_avatar', $file_name, $avatar );
									endif;
								}
								else {
									$errors = $errors + 1;
								}
							}
						endif;
						if(!$errors):
							echo json_encode(array("user_updated" => "success"));
						else:
							echo json_encode(array("errors" => true));
						endif;
					endif;
				endif;
			endif;
	 	endif;
	endif;
endif;