<?php

class PlcStampready
{

    private static $instance = null;
    private static $data = array();
    private static $validation = array();

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_init', array($this, 'register_option'));
        add_action('admin_menu', array($this, 'admin_menu'));

        $this->set_api();
        $this->run_plugin();
    }

    public function register_option()
    {
        register_setting('stampready', 'stampready_export');
    }

    public function admin_menu()
    {
        add_management_page('Stampready', 'Stampready', 'manage_options', 'stampready', array($this, 'stampready_option_page'));
    }

    public function stampready_option_page()
    {
        $this->_proccess_post();
        $validation = $this->get_validation();
        $stampready_export = get_option('stampready_export');
        
        ob_start();
        include TS_SERVER_PATH . "/inc/plugins/stampready/view/index.php";
        echo ob_get_clean();
    }

    private function _proccess_post()
    {
        $submit = isset($_POST['submit']) ? $_POST['submit'] : '';

        if ($submit === 'export') {
            $users = get_users(array('fields' => array('user_email', 'display_name')));
            foreach ($users as $user) {
                $args = array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(),
                    'body' => array(
                        'api' => $this->data['api'],
                        'list' => $this->data['list'],
                        'opt' => $this->data['opt'],
                        'email' => $user->user_email,
                        'name' => $user->display_name
                    ),
                    'cookies' => array()
                );
                $this->_curl('https://www.stampready.net/api2/api.php', $args);
            }
            update_option( 'stampready_export', TRUE );
        }
    }

    private function set_api()
    {
        $this->data['api'] = '6eCoLXGBl3y1tZ4N0aTw';
        $this->data['list'] = 'deslyj3xk8YFaTfZHtMbpvNn';
        $this->data['opt'] = FALSE;
    }

    private function set_validation($type, $msg)
    {
        $this->validation[] = '<div class="notice notice-' . $type . ' is-dismissible"><p>' . $msg . '</p></div>';
    }

    private function get_validation()
    {
        return $this->validation;
    }

    private function _curl($url, $args)
    {
        wp_remote_post($url, $args);
    }

    private function run_plugin()
    {
        
    }

}

$plcStampready = PlcStampready::get_instance();
