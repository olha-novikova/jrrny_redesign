<?php

class PlcNotifications
{

    private static $instance = null;
    private static $data;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->data = array();
        $this->data['from'] = "Jrrny.com <contact@jrrny.com>";

        add_action('init', array($this, 'notifications_custom_type'));
        add_action('add_meta_boxes', array($this, 'plc_notifications_meta_boxes'));
        add_action('save_post', array($this, 'plc_save_custom_post_meta'));

        $this->run_plugin();
    }

    public function plc_notifications_meta_boxes()
    {
        add_meta_box(
                'plc_notifications_template', // Metabox HTML ID attribute
                'Template notification for', // Metabox title
                array($this, 'plc_notifications_metabox_callback'), // callback name
                array('notifications'), // post type
                'side', // context (advanced, normal, or side)
                'core' // priority (high, core, default or low)
        );
        add_meta_box(
                'plc_notifications_html_template', // Metabox HTML ID attribute
                'Head (html code inside <head> tag)', // Metabox title
                array($this, 'plc_notifications_html_metabox_callback'), // callback name
                array('notifications'), // post type
                'normal', // context (advanced, normal, or side)
                'high' // priority (high, core, default or low)
        );
    }

    public function plc_notifications_html_metabox_callback($post)
    {
        $values = get_post_custom($post->ID);
        $plc_notification_head_template = isset($values['_notification_head_template']) ? $values['_notification_head_template'][0] : '';

        wp_nonce_field('my_plc_nonce', 'plc_nonce');
        ?>
        <p>Do not include title tag, it is generated automatically from notification title</p>
        <textarea name="notification_head_template" class="form-control" rows="20"><?php echo $plc_notification_head_template; ?></textarea>
        <?php
    }

    public function plc_notifications_metabox_callback($post)
    {
        $values = get_post_custom($post->ID);
        $plc_notification_template = isset($values['_notification_template']) ? $values['_notification_template'][0] : '';

        wp_nonce_field('my_plc_nonce', 'plc_nonce');

        $templates = array(
            '' => 'None',
            'welcome' => 'User - Welcome',
            'follow' => 'User - Follow',
            'unfollow' => 'User - Unfollow',
            'no_jrrny_posted' => 'User - No jrrny posted after sign up',
            'like' => 'Jrrny - Like',
            'unlike' => 'Jrrny - Unlike',
            'contribute_invitation' => 'Contribute - Invitation',
            'add_bucket' => 'Bucket list - Added by User'
        );
        ?>
        <select name="notification_template" class="form-control">
            <?php
            foreach ($templates as $key => $value) {
                $selected = ($key === $plc_notification_template ? ' selected="selected"' : '');
                ?>

                <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>    
            <?php } ?> 
        </select>
        <p><strong>Use in content</strong></p>
        <hr/>
        <ul>
            <li>{author_name}</li>
            <li>{author_profile_page}</li>
            <li>{user_name}</li>
            <li>{user_profile_page}</li>
            <li>{follow_user_name}</li>
            <li>{follow_user_profile_page}</li>
            <li>{post_title}</li>
            <li>{post_url}</li>
        </ul>
        <?php
    }

    public function plc_save_custom_post_meta($post_id)
    {
        // Bail if we're doing an auto save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        // if our nonce isn't there, or we can't verify it, bail
        if (!isset($_POST['plc_nonce']) || !wp_verify_nonce($_POST['plc_nonce'], 'my_plc_nonce'))
            return;

        // if our current user can't edit this post, bail
        if (!current_user_can('edit_post'))
            return;

        // now we can actually save the data
        $allowed = array(
            'a' => array(// on allow a tags
                'href' => array() // and those anchords can only have href attribute
            )
        );
        update_post_meta($post_id, '_notification_head_template', $_POST['notification_head_template']);
        update_post_meta($post_id, '_notification_template', $_POST['notification_template']);
    }

    public function notifications_custom_type()
    {
        $args = array(
            'labels' => array(
                'name' => 'Notifications',
                'singular_name' => 'Notification',
                'menu_name' => 'Notifications'
            ),
            'public' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'menu_icon'   => 'dashicons-megaphone',
            'supports' => array('title', 'editor')
        );
        register_post_type('notifications', $args);
    }

    public function plc_get_notification_postID($type = 'welcome')
    {
        global $wpdb;

        $table_postmeta = $wpdb->prefix . 'postmeta ';

        $sql = "SELECT ";
        $sql .= "post_id ";
        $sql .= "FROM " . $table_postmeta;
        $sql .= "WHERE meta_key = '_notification_template' ";
        $sql .= "AND meta_value = '" . $type . "' ";
        $sql .= "LIMIT 1 ";

        return $wpdb->get_var($sql);
    }

    public function plc_send_notification($type, $postId = null, $userId = null, $followUserId = null)
    {
        $this->data['type'] = $type;
        
        $notificationId = $this->plc_get_notification_postID($type);
        $this->plc_set_headers();
        $this->plc_set_main_data($postId, $userId, $followUserId);

        if ($notificationId) {
            $this->plc_set_notification_data($notificationId);
        }
        else {
            switch ($type):
                case 'welcome':
                    $this->plc_set_welcome_notification();
                    break;
                case 'follow':
                    $this->plc_set_follow_notification();
                    break;
                case 'unfollow':
                    $this->plc_set_unfollow_notification();
                    break;
                case 'no_jrrny_posted':
                    $this->plc_set_no_jrrny_posted_notification();
                    break;
                case 'like':
                    $this->plc_set_like_notification();
                    break;
                case 'unlike':
                    $this->plc_set_unlike_notification();
                    break;
                case 'contribute_invitation':
                    $this->plc_set_contribute_invitation_notification();
                    break;
                case 'add_bucket':
                    $this->plc_set_add_to_bucket_notification();
                    break;
                default :
                    break;
            endswitch;
        }

        return $this->plc_send();
    }

    private function plc_set_headers()
    {
        $from = $this->data['from'];

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=utf-8" . "\r\n";
        $headers .= "From: $from\r\n";

        switch ($this->data['type']):
            case 'welcome':
                $headers .= "BCC: john@jrrny.com\r\n";
                $headers .= "BCC: jeremy@jrrny.com\n";
                break;
            default :
                break;
        endswitch;

        $this->data['headers'] = $headers;
    }

    private function plc_set_main_data($postId = null, $userId = null, $followUserId = null)
    {
        if ($postId) {
            $post = get_post($postId);

            $this->data['post_title'] = $post->post_title;
            $this->data['post_url'] = get_permalink($post->ID);
            $this->data['post_author'] = $post->post_author;

            $author = get_user_by('id', $this->data['post_author']);
            $this->data['author_url'] = get_author_posts_url($author->ID, $author->user_login);
            $this->data['author_name'] = ($author->user_firstname && $author->user_lastname ? $author->user_firstname . " " . $author->user_lastname : $author->user_login);
        }
        if ($userId) {
            $user = get_user_by('id', $userId);
            
            $this->data['user_url'] = get_author_posts_url($user->ID, $user->user_login);
            $this->data['user_name'] = ($user->user_firstname && $user->user_lastname ? $user->user_firstname . " " . $user->user_lastname : $user->user_login);
        }
        if ($followUserId) {
            $follow_user = get_user_by('id', $followUserId);
            
            $this->data['follow_user_url'] = get_author_posts_url($follow_user->ID, $follow_user->user_login);
            $this->data['follow_user_name'] = ($follow_user->user_firstname && $follow_user->user_lastname ? $follow_user->user_firstname . " " . $follow_user->user_lastname : $follow_user->user_login);
        }
        switch ($this->data['type']):
            case 'welcome':
            case 'contribute_invitation':
            case 'no_jrrny_posted':
                $this->data['email'] = $user->user_email;
                break;
            case 'follow':
            case 'unfollow':
                $this->data['email'] = $follow_user->user_email;
                break;
            case 'like':
            case 'unlike':
            case 'add_bucket':                
                $this->data['email'] = $author->user_email;
                break;
            default :
                break;
        endswitch;
    }

    private function plc_set_notification_data($notificationId)
    {
        $notification = get_post($notificationId);

        $notification_title = $notification->post_title;
        $notification_content = $notification->post_content;

        $notification_head = get_post_meta($notificationId, '_notification_head_template', true);

        $replace = array(
            '{author_name}',
            '{author_profile_page}',
            '{user_name}',
            '{user_profile_page}',
            '{follow_user_name}',
            '{follow_user_profile_page}',
            '{post_title}',
            '{post_url}'
        );
        $by = array(
            isset($this->data['author_name']) ? $this->data['author_name'] : '',
            isset($this->data['author_url']) ? $this->data['author_url'] : '',
            isset($this->data['user_name']) ? $this->data['user_name'] : '',
            isset($this->data['user_url']) ? $this->data['user_url'] : '',
            isset($this->data['follow_user_name']) ? $this->data['follow_user_name'] : '',
            isset($this->data['follow_user_url']) ? $this->data['follow_user_url'] : '',
            isset($this->data['post_title']) ? $this->data['post_title'] : '',
            isset($this->data['post_url']) ? $this->data['post_url'] : ''
        );

        $notification_claer_content = str_replace($replace, $by, $notification_content);

        $this->data['title'] = $notification_title;

        $content = '<html>';
        $content .= '<head>';
        $content .= '<title>' . $notification_title . '</title>';
        $content .= $notification_head;
        $content .= '</head>';
        $content .= '<body>';
        $content .= $notification_claer_content;
        $content .= '</body>';
        $content .= '</html>';

        $this->data['content'] = $content;
    }

    private function plc_set_welcome_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Thanks for signing up ' . $this->data['user_name'];

        $content_link = false;

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, Thanks for signing up!</h3>';
        $content .= '<p>We\'re a community for travelers and travel pros to share adventures and urban discoveries. <br/>'
                . ' Click <a href="' . home_url() . '"/upload">here</a> to add your own! '
                . 'Or <a href="' . home_url() . '"/trending">view</a> what\'s trending.'
                . '</p>';
        $content .= '<p>';

        if (get_option('link_1') && get_option('text_1')) {
            $content_link = true;
            $content .= get_option('text_1');
            $content .= ' <a href="' . get_the_permalink(get_option('link_1')) . '">' . (get_option('link_text_1') ? get_option('link_text_1') : get_the_title(get_option('link_1'))) . '</a>';
        }
        if (get_option('link_2') && get_option('text_2')) {
            if ($content_link) {
                $content .= ',';
            }
            $content .= get_option('text_2');
            $content .= ' <a href="' . get_the_permalink(get_option('link_2')) . '">' . (get_option('link_text_2') ? get_option('link_text_2') : get_the_title(get_option('link_2'))) . '</a>';
        }
        $content .= '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['follow_user_url'] . '">' . $this->data['follow_user_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_follow_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Someone followed is now following you on jrrny!';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['follow_user_name'] . '</h3>';
        $content .= '<p><strong>' . $this->data['user_name'] . '</strong> is now following you on jrrny!</p>';
        $content .= '<p>'
                . 'Check out their jrrnys <a href="' . $this->data['user_url'] . '">here</a><br/>'
                . '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['follow_user_url'] . '">' . $this->data['follow_user_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    public function plc_set_unfollow_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Someone unfollowed is stop following you on jrrny!';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['follow_user_name'] . '</h3>';
        $content .= '<p>We\'re sorry but <strong>' . $this->data['user_name'] . '</strong> is stop following you on jrrny!</p>';
        $content .= '<p>'
                . 'Check out their jrrnys <a href="' . $this->data['user_url'] . '">here</a><br/>'
                . '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['follow_user_url'] . '">' . $this->data['follow_user_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_no_jrrny_posted_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Message';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['user_url'] . '</h3>';
        $content .= '<p>You haven\'t posted a jrrny yet! Log in and tell the world about your last great trip.</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['user_url'] . '">' . $this->data['user_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_like_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Someone liked your jrrny post!';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['author_name'] . '</h3>';
        $content .= '<p>Your stuff is kind of a big deal! '
                . '<strong>' . $this->data['user_name'] . '</strong> liked your post '
                . '<a href="' . $this->data['post_url'] . '">' . $this->data['post_title'] . '</a>'
                . '</p>';
        $content .= '<p>'
                . 'Check out their jrrnys <a href="' . $this->data['user_url'] . '">here</a><br/>'
                . 'Or make a comment if their travels spark a question or interest.'
                . '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['author_url'] . '">' . $this->data['author_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_unlike_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Someone unliked your jrrny post!';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['author_name'] . '</h3>';
        $content .= '<p>We\'re sorry but '
                . '<strong>' . $this->data['user_name'] . '</strong> unliked your post '
                . '<a href="' . $this->data['post_url'] . '">' . $this->data['post_title'] . '</a>'
                . '</p>';
        $content .= '<p>'
                . 'Check out their jrrnys <a href="' . $this->data['user_url'] . '">here</a><br/>'
                . 'Or make a comment if their travels spark a question or interest.'
                . '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['author_url'] . '">' . $this->data['author_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_contribute_invitation_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Invitation to the contributor program on Jrrny';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['user_name'] . '</h3>';
        $content .= '<p>You\'re invited to contibute in our comunity.<br/>'
                . 'Please check it out <a href="' . home_url() . '/contribute">contributor program</a>.'
                . '</p>';

        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['user_url'] . '">' . $this->data['user_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_set_add_to_bucket_notification()
    {
        $upload_dir = wp_upload_dir();

        $this->data['title'] = 'Someone added your post to his bucket list!';

        $content = '<div>';
        $content .= '<img src="' . $upload_dir['baseurl'] . '"/logo_mail.png />';
        $content .= '<h3>Hey, ' . $this->data['author_name'] . '</h3>';
        $content .= '<p>Your stuff is kind of a big deal! '
                . '<strong>' . $this->data['user_name'] . '</strong> added your post '
                . '(<a href="' . $this->data['post_url'] . '">' . $this->data['post_title'] . '</a>) '
                . 'to his bucket list'
                . '</p>';
        $content .= '<p>'
                . 'Check out their jrrnys <a href="' . $this->data['user_url'] . '">here</a>'
                . '</p>';
        $content .= '<p>'
                . 'Enjoy the jrrny!<br/>'
                . '<a href="http://www.jrrny.com">www.jrrny.com</a><br/>'
                . 'Check out what\'s trending <a href="' . site_url('/trending') . '">' . site_url('/trending') . '</a><br/>'
                . 'Update your profile <a href="' . $this->data['author_url'] . '">' . $this->data['author_url'] . '</a><br/>'
                . 'Add a new jrrny <a href="' . site_url('/upload') . '">' . site_url('/upload') . '</a>'
                . '</p>';
        $content .= '</div>';

        $this->data['content'] = $content;
    }

    private function plc_send()
    {
        return wp_mail($this->data['email'], $this->data['title'], $this->data['content'], $this->data['headers'], array());
    }

    private function run_plugin()
    {
        
    }

}

PlcNotifications::get_instance();