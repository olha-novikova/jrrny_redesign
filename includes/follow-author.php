<?php 
function follow(){
	global $wpdb;
	header('Content-Type: application/json');
	$ret = ['status' => 'fail'];
	try{
		global $current_user;
		wp_get_current_user();
		if(!is_user_logged_in()){
			throw new Exception("Please login!");
		}
		if(!isset($_POST['user-id']) || $_POST['user-id'] == ''){
			throw new Exception("User not set!");
		}
		$userId = intval($_POST['user-id']);
		if($userId == $current_user->ID){
			throw new Exception("Can follow himself!");
		}
		if(is_follow($userId)){
			throw new Exception("Already been following!");
		}

		//Insert to like table
		$sql = "INSERT INTO `". $wpdb->prefix . "jrrny_follow` ";
		$sql .= "(`user_id`, `follow_user_id`) ";
		$sql .= "VALUES ('" . $current_user->ID . "', '". $userId ."');";
		$res = $wpdb->get_results($sql);
		
		$ret = [
			'status' => 'ok',
            'following' => '1',                    
            'text' => 'following'
		];

		sendFollowMail($userId, $current_user->ID);
	}catch(Exception $ex){
		$ret['msg'] = $ex->getMessage();
	}
	echo json_encode($ret);
	die();
}

add_action( 'wp_ajax_nopriv_follow', 'follow' );
add_action( 'wp_ajax_follow', 'follow' );

function unfollow(){
	global $wpdb;
	header('Content-Type: application/json');
	$ret = ['status' => 'fail'];
	try{
		global $current_user;
		wp_get_current_user();
		if(!is_user_logged_in()){
			throw new Exception("Please login!");
		}
		if(!isset($_POST['user-id']) || $_POST['user-id'] == ''){
			throw new Exception("User not set!");
		}
		$userId = intval($_POST['user-id']);
		if($userId == $current_user->ID){
			throw new Exception("Can unfollow himself!");
		}
		if(!is_follow($userId)){
			throw new Exception("Already removed following!");
		}

		//Delete following user
		$sql = "DELETE FROM `". $wpdb->prefix . "jrrny_follow` ";
		$sql .= "WHERE user_id = '" . $current_user->ID . "' AND follow_user_id = '". $userId ."' LIMIT 1";
		$res = $wpdb->get_results($sql);
		
		$ret = [
			'status' => 'ok',
            'following' => '0',                    
            'text' => 'follow'
		];
		//sendUnfollowMail($userId, $current_user->ID);
	}catch(Exception $ex){
		$ret['msg'] = $ex->getMessage();
	}
	echo json_encode($ret);
	die();
}

add_action( 'wp_ajax_nopriv_unfollow', 'unfollow' );
add_action( 'wp_ajax_unfollow', 'unfollow' );

function sendFollowMail( $follow_user_id, $user_id){
	$userNotification = get_user_meta($follow_user_id, '_notification', true );
	if($userNotification === "0"){
		return true;
	}
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('follow', null, $user_id, $follow_user_id);
        /*
	$user = get_user_by( 'id', $user_id  );
	$follow_user = get_user_by( 'id', $follow_user_id  );
	$email = $follow_user->user_email;

	// Email
	$content = "<div>";
	$content .= "<p>";
	$content .= "Hey, <a href='" . get_author_posts_url($user->ID, $user->user_login) . "'>".$user->user_login."</a> is now following you on jrrny!";
	$content .= "</p>";
	$content .= "<p>";
	$content .= "You can see their profile <a href='" . get_author_posts_url($user->ID, $user->user_login) . "'>here</a>";
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

function sendUnfollowMail( $follow_user_id, $user_id){
	$userNotification = get_user_meta($follow_user_id, '_notification', true );
	if($userNotification === "0"){
		return true;
	}
        
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('unfollow', null, $user_id, $follow_user_id);
	/*$user = get_user_by( 'id', $user_id  );
	$follow_user = get_user_by( 'id', $follow_user_id  );
	$email = $follow_user->user_email;


	$upload_dir = wp_upload_dir(); 
	// Email
	$content = "<div>";
	$content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
	$content .= "<h3>Hey, </h3>";
	$content .= "<p>";
	$content .= "User <a href='" . get_author_posts_url($user->ID, $user->user_login) . "'>".$user->user_login."</a><br>";
	$content .= "End follow you.";
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

//Return bool is follow
function is_follow($userId){
	global $current_user, $wpdb;
	wp_get_current_user();
	if(!is_user_logged_in()){
		return false;
	}

	$login_user_id = $current_user->ID;
	$follow_table = $wpdb->prefix . 'jrrny_follow';

    $sql = "SELECT * from ";
    $sql .= $follow_table ." ";
    $sql .= "WHERE user_id='";
    $sql .= intval($login_user_id);
    $sql .= "'";
    $sql .= "AND follow_user_id='";
    $sql .= intval($userId);
    $sql .= "';";

    $result = $wpdb->get_results( $sql );

	if(count($result) > 0) {
		return true;
	}
	return false;
}

function getFollowedUsersIds($userId, $limit){

    global $wpdb;
    $ret = array();

    $follow_table = $wpdb->prefix . 'jrrny_follow';
    $sql = "SELECT follow_user_id from ";
    $sql .= $follow_table  ." ";
    $sql .= "WHERE user_id='";
    $sql .= intval($userId);
    $sql .="'";
    if ( $limit != '' ) $sql .= " LIMIT ".$limit;
    $sql .=";";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $result = $wpdb->get_results( $sql );

    foreach ($result as $key => $value) {
        $ret[] = intval($value->follow_user_id);
    }
    return $ret;
}