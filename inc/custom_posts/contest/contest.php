<?php

add_action('admin_menu' , 'attended_user_pages'); 
 
function attended_user_pages()
{
    add_submenu_page('edit.php?post_type=contest', 'Attended Users', 'Attended Users', 'edit_posts', basename(__FILE__), 'attended_users_page');
}

add_action('admin_menu', 'pin_signup_menu');
function pin_signup_menu() {

	//create new top-level menu
        add_submenu_page('edit.php?post_type=contest', 'Pin contest to singup', 'Pin contest to singup', 'edit_posts', 'pin_signup_option_page', 'pin_signup_option_page');

	//call register settings function
	add_action( 'admin_init', 'pin_signup_option' );
}
function pin_signup_option() {
	//register our settings
	register_setting( 'pin_signup_option', 'pin_signup_contest' );
}

function pin_signup_option_page() {    
	$jrrnyContestPostsSql = get_custom_posts('contest');
?>
<div class="wrap">
<h2>Pin contest to signup form</h2>

<form method="post" action="options.php">
    <?php 
    settings_fields( 'pin_signup_option' ); ?>
    <?php do_settings_sections( 'pin_signup_option' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Contest</th>
            <td>
                <select name="pin_signup_contest" >
                    <option value="">none</option>
                <?php foreach ($jrrnyContestPostsSql as $key => $post): ?>
                        <option value="<?= $post->ID?>" <?= (esc_attr( get_option('pin_signup_contest')) == $post->ID)? 'selected="selected"': '' ?>><?= $post->post_title ?></option>
                <?php endforeach ?>               
                </select>
            </td>
        </tr>       
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 

function attended_users_page()
{ /*?>
<form method="POST">
    <select name="contest_id"> 
        <?php 

        $args = array(
            'post_type' => 'contest'
        );
        $posts = get_posts($args); 
        foreach( $posts as $post ) : setup_postdata($post); ?> <?php echo $post->ID; ?>
            <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option> 
        <?php endforeach; ?> 
    </select> 
    <input type="submit" name="submit" value="view" />    
   
</form>
    
<?php   */ 
    //if(isset($_POST['contest_id'])){
        require_once TS_SERVER_PATH . "/inc/admin/model/contests_users_m.php";

        $option = 'per_page';
        $args = [
            'label' => __('Contests', 'plc-plugin'),
            'default' => 20,
            'option' => 'contests_per_page'
        ];

        add_screen_option($option, $args);
        
        $contest_id = $_POST['contest_id'];
        $contests_users_obj = new Contests_users_m();

        include TS_SERVER_PATH . "/inc/admin/views/contests_users.php";
    //}
}
function contest_custom_type()
{
    register_post_type('contest', array(
        'labels' =>
        array(
            'name' => __('Contests'),
            'singular_name' => __('Contest')
        ),
        'show_ui' => true,
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'contest'),
        'supports' => array('thumbnail', 'title', 'editor')
            )
    );
}

add_action('init', 'contest_custom_type');

function add_user_to_contest($contest_id, $user_id, $referral_url = '', $referral_user_id = 0)
{
    $contest = get_post($contest_id);
    //$user = get_userdata($user_id);
    
    $check_contest = get_user_contests_data($user_id, $contest_id, TRUE);
    
    if(!$check_contest){
        
        $url = get_permalink($contest). '?ref='. wp_generate_password( 4, false ) . 'xyz' . $user_id;
        
        $data = array(
            'user_id' => $user_id,
            'contest_id' => $contest_id,
            'url' => $url,
            'referral_user_id' => $referral_user_id,
            'referral_url' => $referral_url,
            'date' => date("Y-m-d H:i:s")
        );
        insert_user_to_contest($data);
        
        if($referral_user_id > 0){
            send_autoreply($contest_id);
        }
    }
} 
function get_sharre_modal($contest_id, $user_id)
{
    
    $contest = get_user_contests_data($user_id, $contest_id, TRUE);
    
    $fb_title = get_post_meta($contest_id, 'fb_title', true);
    $fb_description = get_post_meta($contest_id, 'fb_description', true);
    $fb_image = get_post_meta($contest_id, 'fb_image', true);
    $tw_description = get_post_meta($contest_id, 'tw_description', true);
    
    
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="contest_sharre_modal">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
              $msg .= '<div class="modal-body">';
                  $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                  $msg .= '<div class="contest_sharre_modal_wrapper">';
                    $msg .= '<h1>Thanks for joining!</h1>';
                    $msg .= '<h2>Now invite your friends to be entered in our daily incentives program where you can win giftcards, t-shirts, travel vouchers, and more!<br/></h2>';
                    $msg .= '<p>HOW WOULD YOU LIKE TO <span class="color">INVITE YOUR FRIENDS</span>?</p>';
                    $msg .= '<ul class="contest-share">';                    
                    $msg .= '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $contest->url . '" target="_blank"><span class="flaticon flaticon-facebook-logo-button"></span> Facebook</a></li>';
                    $msg .= '<li><a href="https://twitter.com/home?status=' . $tw_description . ' ' . $contest->url . '" target="_blank"><span class="flaticon flaticon-twitter-logo-button"></span> Twitter</a></li>';
                    $msg .= '<li><a class="send-contest-modal-mail" data-content="' . $contest_id . '" data-user="' . $user_id . '"><span class="flaticon flaticon-user-avatar-main-picture"></span> EMAIL</a></li>';
                    $msg .= '</ul>';
                    $msg .= '<h3>Or copy and send the link below and send your own email<br/>';
                    $msg .= '<span>this is your own personal link that tracks who you invite!</span></h3>';  
                    $msg .= '<div class="footer-wrapper">';                  
                        $msg .= '<span class="share-link">' . $contest->url . '</span>';
                  $msg .= '</div>';
                  $msg .= '</div>';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';
    
    /*
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="contest_sharre_modal">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
              $msg .= '<div class="modal-body">';
                  $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                  $msg .= '<div class="contest_sharre_modal_wrapper">';
                    $msg .= '<h1>SHARE THIS CONTEST</h1>';
                    $msg .= '<h2>Share with your friend.<br/>'
                            . 'The more firends that join, the <span class="color">better your chances</span>!</h2>';
                    $msg .= '<p>HOW WOULD YOU LIKE TO <span class="color">SHARE</span>?</p>';
                    $msg .= '<ul class="contest-share">';                    
                    $msg .= '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $contest->url . '" target="_blank"><span class="flaticon flaticon-facebook-logo-button"></span> Facebook</a></li>';
                    $msg .= '<li><a href="https://twitter.com/home?status=' . $tw_description . ' ' . $contest->url . '" target="_blank"><span class="flaticon flaticon-twitter-logo-button"></span> Twitter</a></li>';
                    $msg .= '<li><a class="send-contest-modal-mail" data-content="' . $contest_id . '" data-user="' . $user_id . '"><span class="flaticon flaticon-user-avatar-main-picture"></span> EMAIL</a></li>';
                    $msg .= '</ul>';
                    $msg .= '<h3>Or copy and send the link below and send your own email<br/>';
                    $msg .= '<span>this is your own personal link that tracks who you invite!</span></h3>';  
                    $msg .= '<div class="footer-wrapper">';                  
                        $msg .= '<span class="share-link">' . $contest->url . '</span>';
                  $msg .= '</div>';
                  $msg .= '</div>';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';*/
    
    return $msg;
    
}
function contest_modal_mail() 
{    
    $msg = '<div class="modal fade plc-modal" tabindex="-1" role="dialog" id="contest_mail_modal">';
       $msg .= '<div class="modal-dialog">';
          $msg .= '<div class="modal-content">';
              $msg .= '<div class="modal-body">';
                  $msg .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                  $msg .= '<div class="contest_sharre_modal_wrapper">';
                    $msg .= '<form id="contestSendEmails_form">';
                        $msg .= '<h1>EMAIL INVITE TO FIRENDS</h1>';
                        $msg .= '<p class="p-no-margin"><strong>'
                                . 'Enter firends email separated by comma</strong></p>';

                        $msg .= '<div class="form-group"><input id="contestEmails" class="form-control" name="emails" type="text" placeholder="steve@example.com, jessica@example.com, etc."></div>';

                        $msg .= '<p>Jrrny will email your firends using you personalize invitation link we track the firends that sign up, notify you when they do, and increase your odds of winning!</p>';  
                        $msg .= '<p><button id="contestSendEmails" class="btn btn-blue">Send Invite <i class="fa processing-icon hide"></i></button></p>';                        
                        $msg .= '<p><button type="button" class="btn btn-link" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">< Back</span></button></p>';
                    $msg .= '</form>';
                $msg .= '</div>';
              $msg .= '</div>';
          $msg .= '</div>';
      $msg .= '</div>';
    $msg .= '</div>';
    
    return $msg;
    
}
function insert_user_to_contest($data)
{
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'jrrny_contests_users';
    
    $wpdb->insert( $table_name, $data );
    
    $result = $wpdb->insert_id;
            
    return $result;
}
function get_user_contests_data($user_id, $contest_id, $single = FALSE)
{    
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'jrrny_contests_users';
    
    $sql = "SELECT * ";
    $sql .= "FROM ";
    $sql .= '`' . $table_name  . '` ';
    $sql .= "WHERE ";
    $sql .= "user_id = '" . $user_id . "' ";
    $sql .= "AND ";
    $sql .= "contest_id = '" . $contest_id . "' ";
    
    if($single){
        $sql .= "LIMIT 1";
        $result = $wpdb->get_row( $sql );        
    }
    else{
        $result = $wpdb->get_results( $sql );        
    }
    
    return $result;
}
function count_user_contest($user_id)
{  
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'jrrny_contests_users';
    
    $sql = "SELECT COUNT(*) ";
    $sql .= "FROM ";
    $sql .= '`' . $table_name  . '` ';
    $sql .= "WHERE ";
    $sql .= "user_id = '" . $user_id . "' ";
    $sql .= "GROUP BY ";
    $sql .= "user_id ";
    $sql .= "LIMIT 1";
    
    $result = $wpdb->get_var( $sql );
    
    return $result;
    
}
function count_user_contest_referral($contest_id, $user_id)
{  
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'jrrny_contests_users';
    
    $sql = "SELECT COUNT(*) ";
    $sql .= "FROM ";
    $sql .= '`' . $table_name  . '` ';
    $sql .= "WHERE ";
    $sql .= "referral_user_id = '" . $user_id . "' ";
    $sql .= "AND ";
    $sql .= "contest_id = '" . $contest_id . "' ";
    $sql .= "GROUP BY ";
    $sql .= "referral_user_id ";
    $sql .= "LIMIT 1";
    
    $result = $wpdb->get_var( $sql );
    
    return $result;
    
}
function send_autoreply($contest_id = 0)
{   
    if(isset($_POST['contest'])){
        $contest_id = $_POST['contest'];    
    }    
    $current_user = wp_get_current_user();        
    $contest = get_user_contests_data($current_user->ID, $contest_id, TRUE);
    
    $email_content_autoreply = get_post_meta($contest_id, 'email_content_autoreply', true);

         
    $referral_user_info = get_userdata($contest->referral_user_id);
    $referral_user_contest = get_user_contests_data($referral_user_info->ID, $contest_id, TRUE);

    $autoreply = str_replace("{username}", $current_user->display_name, $email_content_autoreply);        
    $autoreply = str_replace("{share_url}", $referral_user_contest->url, $autoreply);

    $from = "Jrrny.com <contact@jrrny.com>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
    $headers .= "From: $from\r\n";
    $headers .= "BCC: john@jrrny.com\r\n";
    $headers .= "BCC: jeremy@jrrny.com\n";

    $mail_content = apply_filters('the_content', $autoreply);

    $mail = wp_mail($referral_user_info->user_email, "Your friend has signed up!", $mail_content, $headers, array());
       
    
}

function send_share_emails()
{
    header('Content-Type: application/json');
    
    $current_user = wp_get_current_user();
    $contest_id = $_POST['contest'];   
    $contestSendEmails = $_POST['contestSendEmails'];    
    
    $contest = get_user_contests_data($current_user->ID, $contest_id, TRUE);
    
    $email_content = get_post_meta($contest_id , 'email_content', true);
        
    $email_msg = str_replace("{username}", $current_user->display_name, $email_content);
    $email_msg = str_replace("{share_url}", $contest->url, $email_msg);
    
    $mail_content = apply_filters('the_content', $email_msg);
      
    $response['status'] = 'ok';
    $response['msg'] = 'Your invitation was sent';
    
    if($contestSendEmails){
        $emails = explode(',', $contestSendEmails);
        $email_notSent = array();
        foreach($emails as $email){
            $email = trim($email);
            if(is_email($email) && $email != $current_user->user_email){
                $from = "Jrrny.com <contact@jrrny.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
                $headers .= "From: $from\r\n";
                $headers .= "BCC: john@jrrny.com\r\n";
                $headers .= "BCC: jeremy@jrrny.com\n";

                
                $mail = wp_mail($email, "Your friend sent you invitation", $mail_content, $headers, array());
                
                if(!$mail){   
                    $email_notSent[] = $email;
                }
            }
        }
        if($email_notSent){            
            $response['status'] = 'fail';
            $msg = "We can't sent invitation to ";
            foreach($email_notSent as $value){
                $msg .= $value . ' ';
            }
            $response['msg'] = $msg; 
        }
    }
    echo json_encode($response);
    die();
    
}
add_action('wp_ajax_send_share_emails', 'send_share_emails');
add_action('wp_ajax_nopriv_send_share_emails', 'send_share_emails');

function add_to_contest()
{
    header('Content-Type: application/json');
    
    $current_user = wp_get_current_user();
    $contest_id = $_POST['contest'];   
    $referral_url = $_POST['referral_url'];   
    $referral_user_id = $_POST['referral_user_id'];      
    
    add_user_to_contest($contest_id, $current_user->ID, $referral_url, $referral_user_id);  
      
    $response['status'] = 'ok';
    
    echo json_encode($response);
    die();
    
}
add_action('wp_ajax_add_to_contest', 'add_to_contest');
add_action('wp_ajax_nopriv_add_to_contest', 'add_to_contest');


function get_wizzard()
{
    header('Content-Type: application/json');
    
    $wizzard_step = $_POST['step'];        

    $content = $wizzard_step();
    $response = array(
        'content' => $content
    );
    echo json_encode($response);
    die();   
}
add_action('wp_ajax_get_wizzard', 'get_wizzard');
add_action('wp_ajax_nopriv_get_wizzard', 'get_wizzard');


function wizzard_step_2()
{
    $current_user = wp_get_current_user(); 
     
    $next = isset($_POST['next']) ? $_POST['next'] : '';        
    $contest_id = $_POST['contest'];        
    $contest = get_user_contests_data($current_user->ID, $contest_id, TRUE);
    
    $tw_description = get_post_meta($contest_id, 'tw_description', true);
    
    $msg = '<div class="wizzard-step-2">';
        $msg .= '<p>HOW WOULD YOU LIKE TO <span class="color">SHARE</span>?</p>';
        $msg .= '<ul class="contest-share">';                    
            $msg .= '<li><a class="next-step" href="https://www.facebook.com/sharer/sharer.php?u=' . $contest->url . '" target="_blank"><span class="flaticon flaticon-facebook-logo-button"></span> Facebook</a></li>';
            $msg .= '<li><a class="next-step" href="https://twitter.com/home?status=' . $tw_description . ' ' . $contest->url . '" target="_blank"><span class="flaticon flaticon-twitter-logo-button"></span> Twitter</a></li>';
            $msg .= '<li><a class="send-contest-modal-mail" data-content="' . $contest_id . '" data-user="' . $current_user->ID . '"><span class="flaticon flaticon-user-avatar-main-picture"></span> EMAIL</a></li>';
        $msg .= '</ul>';
        $msg .= '<h3>Or copy and send the link below and send your own email<br/>';
        $msg .= '<span>this is your own personal link that tracks who you invite!</span></h3>';  
        $msg .= '<div class="footer-wrapper">';                  
            $msg .= '<span class="share-link">' . $contest->url . '</span>';
        $msg .= '</div>';
        if($next){
            $msg .= '<p class="text-right"><button data-step="5" class="btn btn-blue next-step">Next&nbsp;<i class="fa processing-icon hide"></i></button></p>';
        }
    $msg .= '</div>';
    
    return $msg;
}
function wizzard_step_5()
{
    $current_user = wp_get_current_user(); 
         
    $msg = '<div class="wizzard-step-5">';
        $msg .= '<p>Where to now?:</p>';
        $msg .= '<p>';
        $msg .= '<a href="' . site_url() . '">HOME</a> | ';
        $msg .= '<a href="' . site_url('collection') . '">COLLECTIONS</a> | ';
        $msg .= '<a href="' . site_url('trending') . '">TRENDING</a> | ';
        $msg .= '<a href="' . site_url('author/' . $current_user->user_login) . '">MY JRRNYS</a>';
        $msg .= '</p>';       
    $msg .= '</div>';
    
    return $msg;
}

function get_user_current_contests($user_id)
{    
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'jrrny_contests_users';
    
    $sql = "SELECT cu.* ";
    $sql .= "FROM ";
    $sql .= '`' . $table_name  . '` AS cu ';
    $sql .= 'LEFT JOIN ' . $wpdb->postmeta . ' AS pm  ';
    $sql .= 'ON cu.contest_id=pm.post_id ';
    $sql .= "WHERE ";
    $sql .= "cu.user_id = '" . $user_id . "' ";
    $sql .= "AND ";
    $sql .= "pm.meta_key = 'contest_end_date' ";
    $sql .= "AND ";
    $sql .= "pm.meta_value >= '" . date('Ymd') . "' ";
    $sql .= "GROUP BY cu.contest_id";
    
    $result = $wpdb->get_results( $sql );        
        
    return $result;
}

function get_contest_input_uploader_tool($user_id)
{ 
   $contests = get_user_current_contests($user_id);
   $count_contests = count($contests);
   
   $input_option = '<input type="hidden" name="contest_id" value="0" />';
   
   if($contests){
       $select = '<select name="contest_id" class="form-control">';
            foreach($contests as $contest){
                $title = get_the_title($contest->contest_id);
                
                $input_option = '<input type="hidden" name="contest_id" value="' . $contest->contest_id . '" />';
                $select .= '<option value="' . $contest->contest_id . '">' . $title . '</option>';
            }
       $select .= '</select>';
   }
   
   $input = '<div class="form-group contest-input">';
        if($count_contests > 1){
           $input .= '<label class="col-xs-12">';
                $input .= "That's fantastic! You're taking part in multiple contests! <br/> Remember though that you can create only one jrrny for one contest at a time. <br/> Thus, please, select the contest for the jrrny you've just created.";
           $input .= '</label>';
           $input .= '<div class="col-xs-12">';
                $input .= $select;
           $input .= '</div>';
        }
        else{
           $input .=  $input_option;
        }
   $input .= '</div>';
   
   echo $input;
}

add_action('wp_ajax_get_uploader', 'get_uploader');
add_action('wp_ajax_nopriv_get_uploader', 'get_uploader');

function get_uploader()
{
    header('Content-Type: application/json');
    
    ob_start();
    include TS_SERVER_PATH . "/inc/frontend/upload/uploader.php";
    $content = ob_get_clean();
    
    $response = array(
        'content' => $content
    );
    
    echo json_encode($response);
    die();
    
}
