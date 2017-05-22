<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Signup_users_m extends WP_List_Table
{

    public function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => __('Special Signup User', 'plc-plugin'),
            'plural' => __('Special Signup Users', 'plc-plugin'),
            'ajax' => false
        ));
    }

    public static function get_signup_users($per_page = 5, $page_number = 1)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'usermeta AS um';
        $table_users = $wpdb->prefix . 'users AS u';
                
        $sql = "SELECT um.meta_value, u.user_login,  u.user_email, u.display_name FROM " . $table_name;
        $sql .= " JOIN " . $table_users . " ON um.user_id=u.ID";
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " WHERE meta_key = '_track_signup'";
        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function record_count()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'usermeta';

        $sql = "SELECT COUNT(*) FROM " . $table_name;
        $sql .= " WHERE meta_key = '_track_signup'";

        return $wpdb->get_var($sql);
    }

    public function no_items()
    {
        _e('No users avaliable', 'plc-plugin');
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'meta_value':
            case 'displayname':
            case 'user_email':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_meta_value($item)
    {
        
        $html = '<strong>' . $item['meta_value'] . '</strong>';
        

        return $html;

    }

    function column_displayname($item)
    {
        $avatar = get_avatar( $item['user_id'], 32 );
        
        $html = $avatar . '<strong>' . $item['user_login'] . '</strong>';
        
        $actions = array(
            'edit' => sprintf('<a href="/wp-admin/user-edit.php?user_id=%s&wp_http_referer=/wp-admin/users.php?page=%s">Edit</a>', $item['user_id'], $_REQUEST['page']),
        );

        return sprintf('%1$s %2$s', $html, $this->row_actions($actions));

    }


    function get_columns()
    {
        $columns = array(
            'meta_value' => __('Signup from'),
            'displayname' => __('Username'),
            'user_email' => __('E-mail')
        );

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'meta_value' => array('meta_value', true),
            'displayname' => array('displayname', false),
            'user_email' => array('email', false)
        );

        return $sortable_columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }


    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $per_page = $this->get_items_per_page('signup_users_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page
        ]);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->get_signup_users($per_page, $current_page);
    }

}
