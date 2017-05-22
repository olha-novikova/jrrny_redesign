<?php

class PlcCities
{

    private static $instance = null;
    private static $data = array();

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'create_custom_post_type'));
        add_action('init', array($this, 'tags_support_custom_post_type'));
        
        $this->run_plugin();
    }

    public function create_custom_post_type()
    {
        register_post_type('cities', array(
            'labels' => array(
                'name' => __('Cities'),
                'singular_name' => __('City')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('thumbnail', 'title', 'editor'),
            'rewrite' => array('slug' => 'cities'),
                )
        );
    }
    
    public function tags_support_custom_post_type()
    {
        register_taxonomy_for_object_type('post_tag', 'cities');
        register_taxonomy_for_object_type('category', 'cities');
    }

    private function run_plugin()
    {
        
    }

}

$plcCities = PlcCities::get_instance();
