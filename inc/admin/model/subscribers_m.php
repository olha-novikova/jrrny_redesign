<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscribers_m extends WP_List_Table
{
    
    public function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => __('Subscriber', 'plc-plugin'),
            'plural' => __('Subscribers', 'plc-plugin'),
            'ajax' => false
        ));
    }

    public static function get_subscribers($per_page = 5, $page_number = 1, $search = null)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'jrrny_subscribers';
        $table_users = $wpdb->prefix . 'users AS u';
                
        $sql = "SELECT ";
        $sql .= "cs.user_id, cs.unsubscribe, u.user_login,  u.user_email, u.display_name ";      
        $sql .= "FROM " . $table_name." AS cs ";
        $sql .= "LEFT JOIN " . $table_users . " ON cs.user_id=u.ID ";
        
        
        if( $search != NULL ){
            $search = trim($search);
            $sql .= " WHERE u.user_email LIKE '%" . $search . "%'";
            $sql .= " OR u.display_name LIKE '%" . $search . "%'";
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

        $table_name = $wpdb->prefix . 'jrrny_subscribers';
        $table_users = $wpdb->prefix . 'users AS u';

        if( $search != NULL ){
            $sql = "SELECT ";
            $sql .= "COUNT(*) ";
            $sql .= "FROM " . $table_name." AS cs ";
            $sql .= "LEFT JOIN " . $table_users . " ON cs.user_id=u.ID ";
        
            $search = trim($search);
            $sql .= " WHERE u.user_email LIKE '%" . $search . "%'";
            $sql .= " OR u.display_name LIKE '%" . $search . "%'";
        }
        else{
            $sql = "SELECT COUNT(*) FROM " . $table_name;
        }

        return $wpdb->get_var($sql);
    }

    public function no_items()
    {
        _e('No subscribers avaliable', 'plc-plugin');
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'displayname':
            case 'user_email':
            case 'actions':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
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
    function column_action($item)
    {
        return '<button id="remove' . $item['user_id'] . '" role="button" data-id="' . $item['user_id'] . '" data-code="' . $item['unsubscribe'] . '" class="remove-user-from-newsletter button button-primary button-large"><span class="fa fa-trash"></span></button>';
    }


    function get_columns()
    {
        $columns = array(
            'displayname' => __('Username'),
            'user_email' => __('E-mail'),
            'action' => __('Action')
            
        );

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'displayname' => array('display_name', false),
            'user_email' => array('user_email', false)
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
        $this->items = $this->get_subscribers($per_page, $current_page, $search);
        
        
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
