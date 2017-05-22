<?php
//Added like to post
//Return quality
function addLike($jrrny_id, $user_id) {
	global $wpdb;
	//Add count of voute
	$likesCount = intval(get_post_meta( $jrrny_id, "likes_count", true ));
	++$likesCount;
	$like = update_post_meta($jrrny_id, "likes_count", $likesCount);
	if(!$like){
		throw new Exception('Can not save meta!');
	}
	//Insert to like table
	$sql = "INSERT INTO `". $wpdb->prefix . "jrrny_likes` ";
	$sql .= "(`user_id`, `post_id`) ";
	$sql .= "VALUES ('" . intval($user_id) . "', '". intval($jrrny_id) ."');";

	$wpdb->get_results( $sql );

	sendLikeMail($jrrny_id, $user_id);

	return $likesCount;
}

//Remove like from post
//Return quality
function unLike($jrrny_id, $user_id) {
	global $wpdb;
	//Remove count of voute
	$likesCount = intval(get_post_meta( $jrrny_id, "likes_count", true ));
	--$likesCount;
	$like = update_post_meta($jrrny_id, "likes_count", $likesCount);
	if(!$like){
		throw new Exception('Can not save meta!');
	}

	//Remove from like table
	$sql = "DELETE FROM `". $wpdb->prefix . "jrrny_likes` ";
	$sql .= "WHERE user_id='" . intval($user_id) ."' ";
	$sql .= "AND post_id='" . intval($jrrny_id) . "' ";
	$sql .= ";";
	$wpdb->get_results( $sql );

	//sendUnlikeMail($jrrny_id, $user_id);

	return $likesCount;
}

function sendUnlikeMail($jrrny_id, $user_id){
	$post = get_post($jrrny_id);
	$authorId = $post->post_author;
	$userNotification = get_user_meta( $authorId, '_notification', true );
	if($userNotification === "0"){
		return true;
	}
        
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('unlike', $jrrny_id, $user_id);
/*
	$author = get_user_by( 'id', $authorId  );
	$email = $author->user_email;
	$user = get_user_by( 'id', $user_id  );

	$upload_dir = wp_upload_dir(); 

	// Email
	$content = "<div>";
	$content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
	$content .= "<h3>Hey, </h3>";
	$content .= "<p>";
	$content .= "User: ". $user->user_login."<br>";
	$content .= "Unike your jrrny <a href='".get_permalink($post->ID)."'>".$post->post_title."</a>";
	$content .= "</p>";
	$content .= "</div>";
	$from = "Jrrny.com <contact@jrrny.com>";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
	$headers .= "From: $from\r\n";
                        
	$mail = wp_mail($email, "Message", $content, $headers, array());
             */                   
	if(!$mail){
        throw new \Exception("Can not send mail!");
	}
	return true;
}

function sendLikeMail($jrrny_id, $user_id){
	$post = get_post($jrrny_id);
	$authorId = $post->post_author;
	$userNotification = get_user_meta( $authorId, '_notification', true );
	if($userNotification === "0"){
		return true;
	}
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('like', $jrrny_id, $user_id);
	/*$author = get_user_by( 'id', $authorId  );
	$email = $author->user_email;
	$user = get_user_by( 'id', $user_id  );


	$upload_dir = wp_upload_dir();

	ob_start();
	require 'mail/like-jrrny-mail.php';
	$content = ob_get_clean();


	$from = "Jrrny.com <contact@jrrny.com>";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
	$headers .= "From: $from\r\n";
                        
	$mail = wp_mail($email, "Someone liked your jrrny post!", $content, $headers, array());
            */                    
	if(!$mail){
        throw new \Exception("Can not send mail!");
	}
	return true;
}

function like_the_jrrny() {
	global $current_user, $wpdb;
	wp_get_current_user();
	header('Content-Type: application/json');
	$ret = array();
	try {
		if(isset($_POST["event"]) 
			&& $_POST["event"] == "like" 
			&& isset($_POST["jrrny"]) 
			&& $_POST["jrrny"] !== "" 
			&& (int)$_POST["jrrny"] > 0 
			&& isset($_POST["author"]) 
			&& $_POST["author"] !== "" 
		){
			if( !is_user_logged_in()) {
				throw new Exception("You must be logged in!");
			}
			$author = $_POST["author"];
			$jrrny_id = intval($_POST["jrrny"]);
			$r_author = intval(get_post($jrrny_id)->post_author);
			$user_id = intval($current_user->ID);
			if(!check_by_salt($r_author, $author)){
				throw new Exception("Do not have permission!");
			}
			if(ifLike($jrrny_id)){
				$likesCount = unLike($jrrny_id, $user_id);
				$ret = array("liked" => "unliked", "quantity" => $likesCount);
			}else {
				$likesCount = addLike($jrrny_id, $user_id);
				$ret = array("liked" => "liked", "quantity" => $likesCount);
			}
		}else {
			throw new Exception("Wrong post data!");
		}
	} catch (Exception $e) {
		$ret["liked"] = false;
		$ret['msg'] = $e->getMessage();
	}
	echo json_encode($ret);
	die();
}

add_action("wp_ajax_like_the_jrrny", "like_the_jrrny");
add_action("wp_ajax_nopriv_like_the_jrrny", "like_the_jrrny");


// Like Jrrny return bool
function ifLike($post_id = null) {
	global $current_user, $wpdb, $post;
	wp_get_current_user();
	$user_id = $current_user->ID;
	if($post_id == null){
		$post_id = get_the_id();
	}
	$likes_table = $wpdb->prefix . 'jrrny_likes';
    $sql = "SELECT post_id from ";
    $sql .= $likes_table ." ";
    $sql .= "WHERE user_id='";
    $sql .= intval($user_id);
    $sql .= "'";
    $sql .= "AND post_id='";
    $sql .= intval($post_id);
    $sql .= "';";


    $result = $wpdb->get_results( $sql );
	if(count($result) > 0) {
		return true;
	}
	return false;
}
// Like Jrrny
function is_liked($post_id = null) {
	if(ifLike($post_id)) {
		echo "liked";
	}else {
		echo "";
	}
}