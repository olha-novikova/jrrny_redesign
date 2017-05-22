<?php
add_action('admin_menu', 'subscribers_pages');

function subscribers_pages()
{
    add_submenu_page('edit.php?post_type=newsletter', 'Subscribers', 'Subscribers', 'manage_options', 'subscribers', 'subscribers_page');
    add_submenu_page('edit.php?post_type=newsletter', 'Add Subscriber', 'Add Subscriber', 'manage_options', 'new_subscribers', 'new_subscribers_page');
}

function subscribers_page()
{
    require_once TS_SERVER_PATH . "/inc/admin/model/subscribers_m.php";

    $option = 'per_page';
    $args = [
        'label' => __('Subscribers', 'plc-plugin'),
        'default' => 20,
        'option' => 'subscribers_per_page'
    ];

    add_screen_option($option, $args);

    $subscribers_obj = new Subscribers_m();

    include TS_SERVER_PATH . "/inc/admin/views/subscribers.php";
}

function new_subscribers_page()
{
    include TS_SERVER_PATH . "/inc/admin/views/new_subscriber.php";
}

function newsletter_custom_type()
{

    $args = array(
        'labels' => array(
            'name' => 'Newsletter',
            'singular_name' => 'Newsletter',
            'menu_name' => 'Newsletter'
        ),
        'public' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'supports' => array('thumbnail', 'title', 'editor')
    );
    register_post_type('Newsletter', $args);
}

add_action('init', 'newsletter_custom_type');

function add_user_to_newsletter($user_id = NULL)
{
    $users = $user_id ? $user_id : $_POST['users'];

    if ($users === 'plcall') {
        $blogusers = get_users('blog_id=1');
        // Array of WP_User objects.
        foreach ($blogusers as $user) {
            $url = wp_generate_password(4, false) . 'xyz' . $user->ID;

            $data = array(
                'user_id' => $user->ID,
                'unsubscribe' => $url
            );
            insert_user_to_newsletter($data);
        }
    }
    elseif ($users) {
        $url = wp_generate_password(4, false) . 'xyz' . $users;

        $data = array(
            'user_id' => $users,
            'unsubscribe' => $url
        );
        insert_user_to_newsletter($data);
    }
}

add_action('wp_ajax_add_user_to_newsletter', 'add_user_to_newsletter');
add_action('wp_ajax_nopriv_add_user_to_newsletter', 'add_user_to_newsletter');

function insert_user_to_newsletter($data)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_subscribers';

    $wpdb->insert($table_name, $data);

    $result = $wpdb->insert_id;

    return $result;
}

function plc_send_newsletter_meta_boxes()
{
    global $post;

    // Get the page template post meta
    $page_template = get_post_meta($post->ID, '_wp_page_template', true);

    // If the current page uses our specific
    // template, then output our custom metabox

    add_meta_box(
            'plc_send_newsletter', // Metabox HTML ID attribute
            'Send Newsletter', // Metabox title
            'plc_send_newsletter_metabox_callback', // callback name
            array('newsletter'), // post type
            'side', // context (advanced, normal, or side)
            'core' // priority (high, core, default or low)
    );
    add_meta_box(
            'plc_autosend_newsletter', // Metabox HTML ID attribute
            'Autosend Newsletter', // Metabox title
            'plc_autosend_newsletter_metabox_callback', // callback name
            array('newsletter'), // post type
            'side', // context (advanced, normal, or side)
            'core' // priority (high, core, default or low)
    );
}

add_action('add_meta_boxes', 'plc_send_newsletter_meta_boxes');

function plc_send_newsletter_metabox_callback($post)
{
    wp_nonce_field('my_plc_send_newsletter_nonce', 'plc_send_newsletter_nonce');

    if ($post->post_status === 'publish') {
        ?>
        <p style="text-align: center;">Before send newsletter remember to save changes</p>
        <p style="text-align: center;"><a href="#" class="send-newsletter button button-primary button-large">SEND&nbsp;<i class="fa processing-icon hide"></i></a></p>
    <?php
    }
    else {
        ?>
        <p style="text-align: center;">You can't send newsletter<br/>unless you publish it</p>
        <?php
    }
    ?>
    <hr/>
    <p>Use in email content</p>
    <ul>
        <li><strong>{unsubscribe}</strong> - it will be replaced by unique user unsubscribe link</li>
    </ul>
    <?php
}

function plc_autosend_newsletter_metabox_callback($post)
{
    wp_nonce_field('my_plc_autosend_newsletter_nonce', 'plc_autosend_newsletter_nonce');

    $where = 'post_id = ' . $post->ID;
    $cron = plc_get_cronjob($where, true);
    ?>
    <p>Set autosend option</p>
    <div class="form-group">
        <label for="datetimepicker">Send Date</label>
        <input id="datetimepicker" name="datetime" type="text" value="<?php echo $cron ? $cron->date : ''; ?>"/>        
    </div>
    <div class="form-group">
        <label for="recurrence">Recurrence</label>
        <select id="recurrence" name="recurrence">
            <option value="once" <?php echo isset($cron) && $cron->recurrence === 'once' ? 'selected="selected"' : ''; ?>>once</option>
            <option value="weekly" <?php echo isset($cron) && $cron->recurrence === 'weekly' ? 'selected="selected"' : ''; ?>>weekly</option>
            <option value="monthly" <?php echo isset($cron) && $cron->recurrence === 'monthly' ? 'selected="selected"' : ''; ?>>monthly</option>
        </select>        
    </div>
    <div class="form-group">
        <?php if (isset($cron) && $cron) { ?>
            <input id="cron-id" name="cron-id" type="hidden" value="<?php echo $cron ? $cron->id : ''; ?>"/>  
            <p style="text-align: center;"><a href="#" class="add-to-cron button button-primary button-large">UPDATE AUTOSEND LIST&nbsp;<i class="fa processing-icon hide"></i></a></p>
            <p style="text-align: center;"><a href="#" class="remove-from-cron button button-secondary button-large">REMOVE FROM AUTOSEND LIST&nbsp;<i class="fa processing-icon hide"></i></a></p>

            <hr/>
            <p>Before removing option from autosend list please save changes</p>
        <?php }
        else { ?>        
            <p style="text-align: center;"><a href="#" class="add-to-cron button button-primary button-large">ADD TO AUTOSEND LIST&nbsp;<i class="fa processing-icon hide"></i></a></p>

            <hr/>
            <p>Before add option to autosend list please save changes</p>
    <?php } ?>
    </div>    
    <?php
}

function plc_remove_subscriber($code)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_subscribers';
    $wpdb->delete($table_name, array('unsubscribe' => $code));
}

function ajax_plc_remove_subscriber()
{
    header('Content-Type: application/json');

    $code = $_POST['code'];
    plc_remove_subscriber($code);

    $response['msg'] = 'Successfull delete';

    echo json_encode($response);
    die();
}

add_action('wp_ajax_ajax_plc_remove_subscriber', 'ajax_plc_remove_subscriber');
add_action('wp_ajax_nopriv_ajax_plc_remove_subscriber', 'ajax_plc_remove_subscriber');

function get_subscribers()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_subscribers';
    $table_users = $wpdb->prefix . 'users AS u';

    $sql = "SELECT ";
    $sql .= "cs.user_id, cs.unsubscribe, u.user_login,  u.user_email, u.display_name ";
    $sql .= "FROM " . $table_name . " AS cs ";
    $sql .= "LEFT JOIN " . $table_users . " ON cs.user_id=u.ID ";

    $result = $wpdb->get_results($sql);

    return $result;
}

function plc_user_to_newsletter()
{
    $users = get_subscribers();
    $exclude = array();

    foreach ($users as $user) {
        $exclude[] = $user->user_id;
    }

    $args = array(
        'show_option_all' => null, // string
        'show_option_none' => null, // string
        'hide_if_only_one_author' => null, // string
        'orderby' => 'display_name',
        'order' => 'ASC',
        'include' => null, // string
        'exclude' => implode(',', $exclude), // string
        'multi' => false,
        'show' => 'display_name',
        'echo' => true,
        'selected' => false,
        'include_selected' => false,
        'name' => 'user', // string
        'id' => 'userToNewsletter', // integer
        'class' => null, // string 
        'blog_id' => $GLOBALS['blog_id'],
        'who' => null // string
    );
    wp_dropdown_users($args);
}

function ajax_plc_send_newsletter($id = NULL)
{
    header('Content-Type: application/json');

    // get post
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : $id;
    $post = get_post($post_id);

    $response['status'] = 'warning';
    $response['msg'] = 'Newsletter not exist';

    if ($post) {
        // get subscribers
        $subscribers = get_subscribers();

        $newsletter_title = $post->post_title;
        $newsletter_content = $post->post_content;

        $response['status'] = 'ok';
        $response['msg'] = 'We sent newsletter to ';

        if ($subscribers) {
            $i = 0;
            foreach ($subscribers as $subscriber) {
                $from = "Jrrny.com <contact@jrrny.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
                $headers .= "From: $from\r\n";
                // $headers .= "BCC: john@jrrny.com\r\n";
                // $headers .= "BCC: jeremy@jrrny.com\n";

                $unsubscribe = '<a href="' . home_url() . '/newsletter'
                        . '?a=unsubscribe&c=' . $subscriber->unsubscribe . '">unsubscribe</a>';

                $email_msg = str_replace("{unsubscribe}", $unsubscribe, $newsletter_content);
                //$mail_content = apply_filters('the_content', $email_msg);
                $mail_content = $email_msg;

                $mail = wp_mail($subscriber->user_email, $newsletter_title, $mail_content, $headers, array());
                $i++;
            }
            $response['msg'] .= $i . ' subscribers';
        }
        else {
            $response['status'] = 'warning';
            $response['msg'] = 'There are none subscribers to our newsletter';
        }
    }

    echo json_encode($response);
    die();
}

function plc_send_newsletter($id = NULL)
{
    $post = get_post($id);

    if ($post) {
        // get subscribers
        $subscribers = get_subscribers();

        $newsletter_title = $post->post_title;
        $newsletter_content = $post->post_content;

        if ($subscribers) {
            $i = 0;
            foreach ($subscribers as $subscriber) {
                $from = "Jrrny.com <contact@jrrny.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
                $headers .= "From: $from\r\n";
                // $headers .= "BCC: john@jrrny.com\r\n";
                // $headers .= "BCC: jeremy@jrrny.com\n";

                $unsubscribe = '<a href="' . home_url() . '/newsletter'
                        . '?a=unsubscribe&c=' . $subscriber->unsubscribe . '">unsubscribe</a>';

                $email_msg = str_replace("{unsubscribe}", $unsubscribe, $newsletter_content);
                $mail_content = $email_msg;

                $mail = wp_mail($subscriber->user_email, $newsletter_title, $mail_content, $headers, array());
                $i++;
            }
        }
    }
}

add_action('wp_ajax_plc_send_newsletter', 'ajax_plc_send_newsletter');
add_action('wp_ajax_nopriv_plc_send_newsletter', 'ajax_plc_send_newsletter');

