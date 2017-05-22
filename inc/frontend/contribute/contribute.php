<?php
function plc_get_contribute_action_btn($user_id, $user_review = false, $user_invitation = false){
    if(empty($user_invitation)){
        $action = '<button data-user="' . $user_id . '" class="button button-primary contribute-mail-invitation">E-mail Invitation&nbsp;<i class="fa processing-icon hide"></i></button>';
        $action .= '<button data-user="' . $user_id . '" class="button button-primary contribute-invitation">Invitation&nbsp;<i class="fa processing-icon hide"></i></button>';
    }
    else{        
        if(empty($user_review)){
            $action = '<strong>INVITATION SENT</strong>';
        }
        else{    
            $action = '<button data-user="' . $user_id . '" class="button button-primary contribute-mark">Mark as reviewed&nbsp;<i class="fa processing-icon hide"></i></button>';
        }    
    }
    
    return $action;
}
function plc_contribute_user_columns($column_headers)
    {
        $column_headers['contribute'] = 'Contribute Action';

        return $column_headers;
    }

    add_action('manage_users_columns', 'plc_contribute_user_columns');

    function plc_contribute_column_content($value, $column_name, $user_id)
    {
        if ('contribute' == $column_name) {            
            $user_invitation = get_user_meta($user_id, '_user_invitation');
            $user_reviewed = get_user_meta($user_id, '_user_reviewed');
            
            if(empty($user_reviewed)){
                $user_review = get_user_meta($user_id, '_user_review');
                $action = plc_get_contribute_action_btn($user_id, $user_review, $user_invitation);
            }
            else{
                $action = '<strong>IS REVIEWED</strong>';
            }   
            
            return $action;
        }

        return $value;
    }

    add_action('manage_users_custom_column', 'plc_contribute_column_content', 10, 3);


function plc_review_profile()
{
    header('Content-Type: application/json');
    
    $current_user = wp_get_current_user();  
    
    $user_review = get_user_meta($current_user->ID, '_user_review', true);
    
    $response['status'] = 'error';
    $response['msg'] = $current_user->display_name .  ' Your profile is reviewed right now.';
    
    if(!$user_review){   
        update_user_meta($current_user->ID, '_user_review', true);
        $response['status'] = 'ok';
        
        $from = "Jrrny.com <contact@jrrny.com>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
        $headers .= "From: $from\r\n";
        $headers .= "BCC: john@jrrny.com\r\n";
        $headers .= "BCC: jeremy@jrrny.com\n";

        $mail_content = '<p>User ID: ' . $current_user->ID . '<br>';
        $mail_content .= 'User Email: ' . $current_user->user_email . '<br>';
        $mail_content .= 'Username: ' . $current_user->display_name . '</p>';

        $mail = wp_mail('contact@jrrny.com', $current_user->display_name .  ' want to review profile.', $mail_content, $headers, array());
    }
    
    echo json_encode($response);
    die();
    
}
add_action('wp_ajax_review_profile', 'plc_review_profile');
add_action('wp_ajax_nopriv_review_profile', 'plc_review_profile');

function plc_contribute_invitation()
{    
    header('Content-Type: application/json');
    
    $user_id = $_POST['user'];
    $mail = isset($_POST['mail']) ? $_POST['mail'] : false;
    
    update_user_meta($user_id, '_user_invitation', true);
    $user_invitation = get_user_meta($user_id, '_user_invitation');
    $user_review = get_user_meta($user_id, '_user_review');
    
    $response['status'] = 'ok';
    $response['action'] = plc_get_contribute_action_btn($user_id, $user_review, $user_invitation);
    
    if($mail){
        /*$user_info = get_userdata($user_id);
        
        $from = "Jrrny.com <contact@jrrny.com>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
        $headers .= "From: $from\r\n";
        //$headers .= "BCC: john@jrrny.com\r\n";
        //$headers .= "BCC: jeremy@jrrny.com\n";

        
        $mail_content .= '<img src="' . $upload_dir['baseurl'] . '/logo_mail.png" />';
        $mail_content .= '<h3>Hello ' . $user_info->display_name . '</h3>';
        $mail_content .= '<p>You\'re invited to contibute in our comunity.<br>';
        $mail_content .= 'Please check it out <a href="' . home_url() . '/contribute">contributor program</a>.</p>';

        $mail = wp_mail($user_info->user_email, 'Invitation to the contributor program on Jrrny', $mail_content, $headers, array());*/
        
        $plcNotifications = PlcNotifications::get_instance();
        $mail = $plcNotifications->plc_send_notification('contribute_invitation', null, $user_id);
    }
    
    echo json_encode($response);
    die();
}

add_action('wp_ajax_contribute_invitation', 'plc_contribute_invitation');
add_action('wp_ajax_nopriv_contribute_invitation', 'plc_contribute_invitation');

function plc_contribute_mark()
{
    header('Content-Type: application/json');
    
    $user_id = $_POST['user'];
    
    update_user_meta($user_id, '_user_reviewed', true);
    
    $response['status'] = 'ok';
    $response['action'] = '<strong>IS REVIEWED</strong>';    
    
    echo json_encode($response);
    die();
}

add_action('wp_ajax_contribute_mark', 'plc_contribute_mark');
add_action('wp_ajax_nopriv_contribute_mark', 'plc_contribute_mark');