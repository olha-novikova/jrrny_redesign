<?php 
// Salt user IDs
function encode_by_salt($type, $value, $salt = NULL) {
	$uid_salt = "zNyMd~x4UhU_ScWDl~F3ZT_whCAwj3TAUg|=6i|RfVo7sW^aUj921r7_CChp";
	$crypted = crypt($value, ((isset($salt) && $salt !== NULL) ? $salt : $uid_salt));
	switch ($type) {
		case 'user_id':
                        return $crypted;
                break;
	}
}

// Unsalt user IDs
function check_by_salt($value, $crypted) {
	return crypt($value, $crypted) == $crypted;
}

function login_user() {
	header('Content-Type: application/json');
	$response = ['status'=>'fail'];
        $log_db = '';
	if(isset($_POST["user"]) && isset($_POST["user"]["email"]) && $_POST["user"]["email"] !== ""){
		if (filter_var($_POST["user"]["email"], FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower($_POST["user"]["email"]);
                    $password = $_POST["user"]["password"];
                    if(email_exists( $email )){
                        $user_check = get_user_by('email',$email);
                        $user_pass = $user_check->data->user_pass;
                        if(wp_check_password( $password, $user_check->data->user_pass, $user_check->data->ID) !== false){
                            $creds = array();
                            $creds['user_login'] = $user_check->data->user_login;
                            $creds['user_password'] = $password;
                            $creds['remember'] = true;
                            if(get_class(wp_signon( $creds )) == "WP_User"){
                                
                                $login = $user_check->data->user_login;
                                $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : home_url() . "/author/" . $login;
                                $response = array(
                                        "status" => 'ok', 
                                        'login' => $login,
                                        'redirect' => $redirect
                                );
                                                                
                                $contest = isset($_POST["contest"]) ? $_POST["contest"] : '';
                                $referral_user_id = isset($_POST["referral_user_id"]) ? $_POST["referral_user_id"] : 0;
                                $referral_url = isset($_POST["referral_url"]) ? $_POST["referral_url"] : '';
                                if($contest){
                                    add_user_to_contest($contest, $user_check->data->ID, $referral_url, $referral_user_id);
                                    $response['modal'] = get_sharre_modal($contest, $user_check->data->ID);
                                }
                            }else {
                                    $response['msg'] = $log_db = "Wrong user!";
                            }
                        }else{
                                $response['msg'] = $log_db = "The password for this account is incorrect!";
                        }
                    }else{
                            $response['msg'] = $log_db = "Hmmmm.  We don't recognize that email!";
                    }                    
		}else {
			$response['msg'] = 'Wrong data!';
		}
	}else {
		$response['msg'] = 'Empty data!';
	}	
        
        if(function_exists('lal_actually_log') && $log_db){
            global $wpdb;
            $wpdb->insert("{$wpdb->prefix}login_attempt_log",
                    array(
                            "username" 	=> $email,
                            "password" 	=> $log_db,
                            "time" 	=> current_time('mysql'),
                            "agent" 	=> $_SERVER['HTTP_USER_AGENT'],
                            "ip" 	=> $_SERVER['REMOTE_ADDR']
                    )
            );            
        }
	echo json_encode($response);
	die();
}
add_action('wp_ajax_login_user', 'login_user');
add_action('wp_ajax_nopriv_login_user', 'login_user');

function login_out_user() {
	if(isset($_POST["event"]) && $_POST["event"] == "_header_top_login_out_user"):
		if(!is_user_logged_in()):
			echo json_encode(array("loggedin" => "no"));
		else:
			echo json_encode(array("loggedin" => "yes"));
			wp_logout();
		endif;
	endif;
	die();
}
add_action('wp_ajax_login_out_user', 'login_out_user');
add_action('wp_ajax_nopriv_login_out_user', 'login_out_user');

function signup_user() {
	global $wpdb;
	header('Content-Type: application/json');
	$response = ['status'=>'fail', 'type' => NULL];
	try {
		if(!isset($_POST["user"]) || $_POST["user"] == "") {
			throw new \Exception("Wrong data!");
		}
		$new_user = array();
		parse_str($_POST["user"], $new_user);
		if(
			!isset($new_user["email"]) || 
			$new_user["email"] == "" ||
			!isset($new_user["password"]) ||
			$new_user["password"] == ""		
		) {
			$response['type'] = 'username';
			throw new \Exception("Empty user data!");
		}			
			$email = strtolower($new_user["email"]);
			$username = $new_user["username"];
			$password = trim($new_user["password"]);
			$track_signup = isset($_POST["track_signup"]) ? $_POST["track_signup"] : '';
                        
			if(strlen($password) < 6){
				$response['type'] = 'password';
				throw new Exception("6 characters mininum!");
			}
			if(!preg_match('/^[a-zA-Z0-9]+$/', $password)){
				$response['type'] = 'password';
				throw new Exception("Only letter and numbers!");
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			    $response['type'] = 'email';
				throw new Exception("Email is invalid!"); 
			}
			if(email_exists($email) !== false) {
				$response['type'] = 'email';
				throw new \Exception("This email already exist in our site!");
			}
			if (username_exists($username) !== false) {
				$response['type'] = 'username';
				throw new \Exception("This username already exist in our site!");
			}
                        
			$userdata = array(
				"user_login" => $username,
				"display_name" => $username,
				"user_nicename" => $username,
				"user_email" => $email,
				"user_pass" => $password,
				"role" => "author"
			);
			$insert_new_user = wp_insert_user($userdata);
			if(is_wp_error($insert_new_user)) {
				throw new \Exception($insert_new_user->get_error_message());
			}
			update_user_meta($insert_new_user, '_notification', true);
                        if($track_signup){
                            update_user_meta($insert_new_user, '_track_signup', $track_signup);                            
                        }
                        add_user_to_newsletter($insert_new_user);
			$creds = array();
			$creds['user_login'] = $username;
			$creds['user_password'] = $password;
			$creds['remember'] = true;
			if(get_class(wp_signon( $creds )) == "WP_User"){
				$upload_dir = wp_upload_dir(); 
                                /*
				// Email
				$content = "<div>";
				$content .= '<img src="' . $upload_dir['baseurl'] . '/logo_mail.png" />';
				$content .= "<h3>Hey, Thanks for signing up!</h3>";
				$content .= "<p>We're a community for travelers and travel pros to share adventures and urban discoveries.<br/>";
				$content .= "Click <a href='".home_url()."/upload'>here</a> to add your own! Or <a href='".home_url()."/trending'>view</a> what's trending.</p>";
				if(isset($new_user["subscribe"]) && $new_user["subscribe"] == "on"){
					$content .= "<p>You're now on the list to receive the weeks best jrrnys and a great deal now and then.</p>";
				}
                                $content_link = false;
                                $content .= '<p>';
                                if(get_option('link_1') && get_option('text_1')){                                    
                                    $content_link = true;
                                    $content .= get_option('text_1');
                                    $content .= ' <a href="' . get_the_permalink(get_option('link_1')) . '">' . (get_option('link_text_1') ? get_option('link_text_1') : get_the_title(get_option('link_1'))) . '</a>';                                   
                                }
                                if(get_option('link_2') && get_option('text_2')){
                                    if($content_link){
                                        $content .= ',';
                                    }
                                    $content .= get_option('text_2');
                                    $content .= ' <a href="' . get_the_permalink(get_option('link_2')) . '">' . (get_option('link_text_2') ? get_option('link_text_2') : get_the_title(get_option('link_2'))) . '</a>';
                                                                      
                                }
                                $content .= '</p>';
				$content .= "</div>";
                                
                                $subject = 'Thanks for signing up ' . $username;
				$from = "Jrrny.com <contact@jrrny.com>";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
				$headers .= "From: $from\r\n";
				$headers .= "BCC: john@jrrny.com\r\n";
				$headers .= "BCC: jeremy@jrrny.com\n";
                        
                //                include get_template_directory() . '/mail_welcome.php';
				$mail = wp_mail($email, $subject, $content, $headers, array());
                                */
                                $plcNotifications = PlcNotifications::get_instance();
                                $mail = $plcNotifications->plc_send_notification('welcome', null, $insert_new_user);
				if(!$mail){
                                   $response['type'] = 'email';
                                   throw new \Exception("Can not send mail!");
				}
                                $urlLocation = '';
                                $referer = isset($_POST["referer"]) ? $_POST["referer"] : '';
                                if ( $referer == home_url() . "/contribute" ) {
                                    $urlLocation = $referer;
                                }
				$response = [
                                    'status' => 'ok', 
                                    'user_id' => $insert_new_user,
                                    'username' => $username,
                                    'location' => $urlLocation
				]; 
                                
                                $contest = isset($_POST["contest"]) ? $_POST["contest"] : '';
                                $referral_user_id = isset($_POST["referral_user_id"]) ? $_POST["referral_user_id"] : 0;
                                $referral_url = isset($_POST["referral_url"]) ? $_POST["referral_url"] : '';
                                if($contest){
                                    add_user_to_contest($contest, $insert_new_user, $referral_url, $referral_user_id);
                                    $response['modal'] = get_sharre_modal($contest, $insert_new_user);
                                }
			}
	}catch (\Exception $ex){
		$response['msg'] = $ex->getMessage();
	}
	
	echo json_encode($response);
	die();
}

add_action('wp_ajax_signup_user', 'signup_user');
add_action('wp_ajax_nopriv_signup_user', 'signup_user');

function get_modal() 
{
    header('Content-Type: application/json');
    
    $modal = $_POST['modal'];    
    $msg = $modal();
    
    $response['status'] = 'ok';
    $response['msg'] = $msg;
    echo json_encode($response);
    die();
}
add_action('wp_ajax_get_modal', 'get_modal');
add_action('wp_ajax_nopriv_get_modal', 'get_modal');

function login_form_new_user_modal(){
    $current_url = home_url(add_query_arg(array(),$wp->request));
    if(isset($_POST['current_url']) && $_POST['current_url'] != ''){
        $current_url = $_POST['current_url'];
    }
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="login_form_new_user_wrapper">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
          $msg .= '<div class="modal-header">';
                $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
          $msg .= '</div>';
              $msg .= '<div class="modal-body">';
                  $msg .= '<div class="login-form-wrapper">';
                    $msg .= '';
                    $msg .= '<h2 class="title">First time Visitor?</h2>';
                    $msg .= '<p>Join the Jrrny Comunity for free to continue enjoying all of the great travel experiences!</p>';
                    $msg .= '<form method="post" id="signup_form">';
                        $msg .= '<div class="form-group">';
                            $msg .= '<label class="sr-only" for="username">Username</label>';
                            $msg .= '<input type="text" class="form-control" id="username" name="username" placeholder="Username">';
                        $msg .= '</div>';
                        $msg .= '<div class="form-group">';
                            $msg .= '<label class="sr-only" for="email">Email address</label>';
                            $msg .= '<input type="email" class="form-control" id="email" name="email" placeholder="Email">';
                        $msg .= '</div>';
                        $msg .= '<div class="form-group">';
                            $msg .= '<label class="sr-only" for="password">Password</label>';
                            $msg .= '<input type="password" class="form-control" id="password" name="password" placeholder="Password">';
                        $msg .= '</div>';
                        $msg .= '<div class="clearfix"></div>';
                        $msg .= '<div class="form-group">';
                            $msg .= '<div class="row">';
                                $msg .= '<div class="col-xs-12 col-sm-6">';
                                    $msg .= '<button id="signup_btn" class="btn btn-lg btn-blue btn-block">Join&nbsp;<i class="fa processing-icon hide"></i></button>';
                                $msg .= '</div>';
                                $msg .= '<div class="col-xs-12 col-sm-6">';
                                    $msg .= '<button id="signin_btn" class="btn btn-lg btn-link login_modal close_modal">or <strong>Log in</strong>&nbsp;<i class="fa processing-icon hide"></i></button>';
                                $msg .= '</div>'; 
                            $msg .= '</div>';
                        $msg .= '</div>';
                        $msg .= '
                    <div class="form-group info">
                        <p class="help-block">By joining jrrny, you agree to our <a href="' . home_url() . '/terms.pdf"><strong>TERMS OF SERVICE</strong></a> and <strong><a href="' . home_url() . '/privacy.pdf">PRIVACY POLICY</strong></a></p>
                    </div>';
                        $msg .= '<div class="form-group social-logins">';
                            $msg .= '<div class="row">';
                                $msg .= '<div class="col-xs-12 col-sm-6 no-right-padding">';
                                    $msg .= '<a class="fb-login" data-provider="Facebook" title="Connect with Facebook" rel="nofollow" href="' . home_url() . '/admin4214?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=' . urlencode($current_url) . '"><span class="flaticon flaticon-facebook-logo-button"></span>&nbsp;&nbsp;Join with Facebook</a>';
                                $msg .= '</div>';   
                                $msg .= '<div class="col-xs-12 col-sm-6">';
                                    $msg .= '<a class="tw-login" data-provider="Twitter" title="Connect with Twitter" rel="nofollow" href="' . home_url() . '/admin4214?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to=' . urlencode($current_url) . '"><span class="flaticon flaticon-twitter-logo-button"></span>&nbsp;&nbsp;Join with Twitter</a>';
                                $msg .= '</div>';                     
                            $msg .= '</div>';
                        $msg .= '</div>';
                    $msg .= '</form>';
                  $msg .= '</div>';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';
    
    return $msg;
}

function login_form_modal(){
    $current_url = home_url(add_query_arg(array(),$wp->request));
    if(isset($_POST['current_url']) && $_POST['current_url'] != ''){
        $current_url = $_POST['current_url'];
    }
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="login_form_wrapper">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
              $msg .= '<div class="modal-body">';
                  $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                  $msg .= '<div class="login-form-wrapper">';
                    $msg .= '<h2 class="title">Login to continue!</h2>';
                    $msg .= '<form method="post" id="login_form">';
                        $msg .= '<div class="form-group">';
                            $msg .= '<div class="col-xs-12 col-sm-12">';
                                $msg .= '<label class="sr-only" for="email">Email address</label>';
                                $msg .= '<input type="email" class="form-control" id="email" name="email" placeholder="Email">';
                            $msg .= '</div>';
                        $msg .= '</div>';
                        $msg .= '<div class="form-group">';
                            $msg .= '<div class="col-xs-12 col-sm-12">';
                                $msg .= '<label class="sr-only" for="password">Password</label>';
                                $msg .= '<input type="password" class="form-control" id="password" name="password" placeholder="Password">';
                            $msg .= '</div>';
                        $msg .= '</div>';
                        $msg .= '<div class="clearfix"></div>';
                        $msg .= '<div class="form-group padding-top-30 padding-bottom-30">';
                            $msg .= '<div class="col-xs-12 col-sm-6">';
                                $msg .= '<button id="login_btn" class="btn btn-lg btn-blue btn-block">Log in&nbsp;<i class="fa processing-icon hide"></i></button>';
                            $msg .= '</div>';
                            $msg .= '<div class="col-xs-12 col-sm-6">';
                                $msg .= '<div class="form-group social-logins">';
                                    $msg .= 'or log in with&nbsp;&nbsp;';
                                    $msg .= '<a class="fb-login" data-provider="Facebook" title="Connect with Facebook" rel="nofollow" href="' . home_url() . '/admin4214?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=' . urlencode($current_url) . '"><span class="flaticon flaticon-facebook-logo-button"></span></a>';
                                    $msg .= '<a class="tw-login" data-provider="Twitter" title="Connect with Twitter" rel="nofollow" href="' . home_url() . '/admin4214?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to=' . urlencode($current_url) . '"><span class="flaticon flaticon-twitter-logo-button"></span></a>';
                                $msg .= '</div>';
                            $msg .= '</div>';
                            $msg .= '<div class="clearfix"></div>';
                            $msg .= '</div>';
                            $msg .= '<div class="col-xs-12 col-sm-6">';
                                $msg .= '<a href="' . wp_lostpassword_url() . '" class="btn btn-block btn-link forgot_password" title="Forgot Password">Forgot password?</a>';
                            $msg .= '</div>';
                            $msg .= '<div class="col-xs-12 col-sm-6">';
                                $msg .= 'or <a href="' . home_url() . '/register" class="btn btn-lg btn-link signup_modal"><strong>JOIN</strong>&nbsp;<i class="fa processing-icon hide"></i></a>';
                            $msg .= '</div>';
                            $msg .= '<div class="clearfix"></div>';
                        $msg .= '</form>';
                  $msg .= '</div>';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';
    
    return $msg;
}

function signup_modal(){
    $current_user = wp_get_current_user();
    
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="successfully_signup">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
              $msg .= '<div class="modal-body">';
                  $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                  $msg .= '<h2>Thank you for joining!</h2>';
                  $msg .= '<p>Next Steps:</p>';
                  $msg .= '<div class="profile-link-wrapper">';
                      $msg .= '<ul>';
                         $msg .= '<li><a href="' . home_url('upload') . '"><i class="flaticon flaticon-directions-signs-outlines"></i> Create and Share a jrrny</a></li>';
                         $msg .= '<li><a href="' . home_url('author/' . $current_user->user_login) . '"><i class="flaticon flaticon-folded-map"></i> Set up profile / my jrrnys page</a></li>';
                         $msg .= '<li><a href="' . home_url('trending') . '"><i class="flaticon flaticon-rocket-icon"></i> View Trending jrrnys</a></li>';
                      $msg .= '</ul>';
                  $msg .= '</div>';
              $msg .= '</div>';
              $msg .= '<div class="modal-footer hidden">';
                  $msg .= '<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>';
                  //$msg .= '<img src="//trc.taboola.com/jrrny-sc/log/3/action?name=SignUp&item-url=' . home_url() . '" width="0" height="0" />';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';
    
    return $msg;
}

function lostpass(){
	header('Content-Type: application/json');
	global $wpdb, $wp_hasher;
    $user_login = $_POST['user_login'];
    if ( empty( $user_login) ) {
        echo json_encode(array(
        	'status' => 'fail',
        	'msg' => 'Empty user'
        	));
		die();
    } else if ( strpos( $user_login, '@' ) ) {
        $user_data = get_user_by( 'email', trim( $user_login ) );
    } else {
        $login = trim($user_login);
        $user_data = get_user_by('login', $login);
    }

    if ( !$user_data ) {
    	echo json_encode(array(
        	'status' => 'fail',
        	'msg' => 'User not found!'
        	));
		die();
    }
    // redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    do_action('retrieve_password', $user_login);

    $allow = apply_filters('allow_password_reset', true, $user_data->ID);
    if ( ! $allow ){
    	echo json_encode(array(
        	'status' => 'fail',
        	'msg' => 'User not allow!'
        	));
		die();
    }else if ( is_wp_error($allow) ){
    	echo json_encode(array(
        	'status' => 'fail',
        	'msg' => 'User not allow!'
        	));
		die();
    }
    $key = wp_generate_password( 20, false );
    do_action( 'retrieve_password_key', $user_login, $key );

    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = $wp_hasher->HashPassword( $key );    
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => time().":".$hashed ), array( 'user_login' => $user_login ) );
    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
    $message .= network_home_url( '/' ) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

    if ( is_multisite() )
        $blogname = $GLOBALS['current_site']->site_name;
    else
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $title = sprintf( __('[%s] Password Reset'), $blogname );

    $title = apply_filters('retrieve_password_title', $title);
    $message = apply_filters('retrieve_password_message', $message, $key);

    if ( $message && !wp_mail($user_email, $title, $message) )
        wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );

    echo json_encode(array('status' => 'ok'));
	die();
}
add_action( 'wp_ajax_lostpass', 'lostpass' );
add_action( 'wp_ajax_nopriv_lostpass', 'lostpass' );