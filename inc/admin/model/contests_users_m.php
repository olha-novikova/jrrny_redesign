<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Contests_users_m extends WP_List_Table
{

    public function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => __('Contest User', 'plc-plugin'),
            'plural' => __('Contest Users', 'plc-plugin'),
            'ajax' => false
        ));
    }

    public static function get_contest_users($per_page = 5, $page_number = 1, $search = null)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'jrrny_contests_users';
        $table_posts = $wpdb->prefix . 'posts';
        $table_postmeta = $wpdb->prefix . 'postmeta';
        $table_users = $wpdb->prefix . 'users AS u';
        $table_rusers = $wpdb->prefix . 'users AS ru';
        $table_usermeta = $wpdb->prefix . 'usermeta AS um';
                
        $sql = "SELECT ";
        $sql .= "cs.user_id, cs.referral_user_id, cs.contest_id, cs.referral_url, ";
        $sql .= "p.post_title, ru.user_login AS referral_user_login, u.user_login,  u.user_email, u.display_name, ";
        $sql .= "(";
            $sql .= "SELECT COUNT(*) ";
            $sql .= "FROM ";
            $sql .= '`' . $table_name  . '` ';
            $sql .= "WHERE ";
            $sql .= "referral_user_id = cs.user_id ";
            $sql .= "AND ";
            $sql .= "contest_id = cs.contest_id ";
            $sql .= "GROUP BY ";
            $sql .= "referral_user_id ";
            $sql .= "LIMIT 1";
        $sql .= ") as referral_count, ";
        $sql .= "(";
            $sql .= "SELECT COUNT(*) ";
            $sql .= "FROM ";
            $sql .= '`' . $table_posts  . '` AS p ';
            $sql .= "WHERE ";
            $sql .= "post_author = cs.user_id  ";
            $sql .= "AND  cs.contest_id = (";
                    $sql .= "SELECT meta_value ";
                    $sql .= "FROM ";
                    $sql .= '`' . $table_postmeta  . '` ';
                    $sql .= "WHERE post_id = p.ID ";
                    $sql .= "AND meta_key = '_attend_to_contest' ";
                    $sql .= "LIMIT 1";
                $sql .= ") ";
            $sql .= "AND (post_date BETWEEN DATE_FORMAT(cs.date,'%Y%m%d') AND ";
                $sql .= "(";
                    $sql .= "SELECT meta_value ";
                    $sql .= "FROM ";
                    $sql .= '`' . $table_postmeta  . '` ';
                    $sql .= "WHERE post_id = cs.contest_id ";
                    $sql .= "AND meta_key = 'contest_end_date' ";
                    $sql .= "LIMIT 1";
                $sql .= ") ";
            $sql .= ") ";
            $sql .= "GROUP BY ";
            $sql .= "referral_user_id ";
            $sql .= "LIMIT 1";
        $sql .= ") as created_jrrny ";
        $sql .= "FROM " . $table_name." AS cs ";
        $sql .= "LEFT JOIN " . $table_users . " ON cs.user_id=u.ID ";
        $sql .= "LEFT JOIN " . $table_rusers . " ON cs.referral_user_id=ru.ID ";
        $sql .= "LEFT JOIN " . $table_posts . " AS p ON cs.contest_id=p.ID ";
        
        
        if( $search != NULL ){
            $search = trim($search);
            $sql .= " WHERE p.post_title LIKE '%" . $search . "%'";
            $sql .= " OR u.user_email LIKE '%" . $search . "%'";
            $sql .= " OR u.display_name LIKE '%" . $search . "%'";
            $sql .= " OR ru.user_login LIKE '%" . $search . "%'";
            $sql .= " OR cs.referral_url LIKE '%" . $search . "%'";
        }
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        if( !$search ){
            $sql .= " LIMIT $per_page";
            $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }
        
        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function record_count($search = null)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'jrrny_contests_users';
        $table_posts = $wpdb->prefix . 'posts AS p';
        $table_users = $wpdb->prefix . 'users AS u';
        $table_rusers = $wpdb->prefix . 'users AS ru';

        if( $search != NULL ){
            $sql = "SELECT ";
            $sql .= "COUNT(*) ";
            $sql .= "FROM " . $table_name." AS cs ";
            $sql .= "LEFT JOIN " . $table_users . " ON cs.user_id=u.ID ";
            $sql .= "LEFT JOIN " . $table_rusers . " ON cs.referral_user_id=ru.ID ";
            $sql .= "LEFT JOIN " . $table_posts . " ON cs.contest_id=p.ID ";
        
            $search = trim($search);
            $sql .= " WHERE p.post_title LIKE '%" . $search . "%'";
            $sql .= " OR u.user_email LIKE '%" . $search . "%'";
            $sql .= " OR u.display_name LIKE '%" . $search . "%'";
            $sql .= " OR ru.user_login LIKE '%" . $search . "%'";
            $sql .= " OR cs.referral_url LIKE '%" . $search . "%'";
        }
        else{
            $sql = "SELECT COUNT(*) FROM " . $table_name;
        }

        return $wpdb->get_var($sql);
    }

    public function no_items()
    {
        _e('No users avaliable', 'plc-plugin');
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'post_title':
            case 'displayname':
            case 'user_email':
            case 'referral_user_login':
            case 'referral_url':
            case 'referral_count':
            case 'created_jrrny':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_post_title($item)
    {
        
        $html = '<strong>' . $item['post_title'] . '</strong>';
        $actions = array(
            'edit' => sprintf('<a href="/wp-admin/post.php?post=%s&action=edit&wp_http_referer=/wp-admin/edit.php?post_type=contest&page=core.php">Edit</a>', $item['contest_id']),
        );

        return sprintf('%1$s %2$s', $html, $this->row_actions($actions));


    }

    function column_displayname($item)
    {
        $avatar = get_avatar( $item['user_id'], 32 );
        
        $html = $avatar . '<strong>' . $item['user_login'] . '</strong>';
        
        $actions = array(
            'edit' => sprintf('<a href="/wp-admin/user-edit.php?user_id=%s&wp_http_referer=/wp-admin/edit.php?post_type=contest&page=core.php">Edit</a>', $item['user_id']),
        );

        return sprintf('%1$s %2$s', $html, $this->row_actions($actions));

    }
    function column_referral_user_login($item)
    {
        
        if($item['referral_user_id'] > 0) {
            $avatar = get_avatar( $item['referral_user_id'], 32 );
        
            $html = $avatar . '<strong>' . $item['referral_user_login'] . '</strong>';

            $actions = array(
                'edit' => sprintf('<a href="/wp-admin/user-edit.php?user_id=%s&wp_http_referer=/wp-admin/edit.php?post_type=contest&page=core.php">Edit</a>', $item['referral_user_id']),
            );

            return sprintf('%1$s %2$s', $html, $this->row_actions($actions));
            }

    }
    
      
    function column_referral_count($item)
    {
        return $item['referral_count'] ? $item['referral_count'] : 0;
    }
    
    function column_created_jrrny($item)
    {
        return $item['created_jrrny'] ? $item['created_jrrny'] : 0;
    }


    function get_columns()
    {
        $columns = array(
            'post_title' => __('Contest'),
            'displayname' => __('Username'),
            'user_email' => __('E-mail'),
            'referral_user_login' => __('Referral by'),
            'referral_url' => __('Referral URL'),
            'referral_count' => __('Count'),
            'created_jrrny' => __('Created Jrrnys for this Contest')
            
        );

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'post_title' => array('post_title', true),
            'displayname' => array('display_name', false),
            'user_email' => array('user_email', false),
            'referral_user_login' => array('referral_user_login', false),
            'referral_url' => array('referral_url', false),
            'referral_count' => array('referral_count', false),
            'created_jrrny' => array('created_jrrny', false)
        );

        return $sortable_columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }


    public function prepare_items($search = null)
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        
        if( $search != NULL ){
            $per_page = $this->get_items_per_page('signup_users_per_page', 1000);            
        }
        else{            
            $per_page = $this->get_items_per_page('signup_users_per_page', 20);
        }
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count($search);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page
        ]);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->get_contest_users($per_page, $current_page, $search);
    }
    public function search_box( $text, $input_id ) {
           /* if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
               return;
            */
            $input_id = $input_id . '-search-input';

            if ( ! empty( $_REQUEST['orderby'] ) )
                echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
            if ( ! empty( $_REQUEST['order'] ) )
                echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
            if ( ! empty( $_REQUEST['post_mime_type'] ) )
                echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
            if ( ! empty( $_REQUEST['detached'] ) )
                echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
    ?>
    <p class="search-box">
        <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
        <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
        <?php submit_button( $text, 'button', '', false, array('id' => 'search-submit') ); ?>
    </p>
    <?php
        }

}
