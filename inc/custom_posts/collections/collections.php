<?php

class PlcCollections
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
        register_post_type('featured_destination', array(
            'labels' => array(
                'name' => __('Collections'),
                'singular_name' => __('Collection')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('thumbnail', 'title', 'editor'),
            'rewrite' => array('slug' => 'collection'),
                )
        );
    }

    public function tags_support_custom_post_type()
    {
        register_taxonomy_for_object_type('post_tag', 'featured_destination');
        register_taxonomy_for_object_type('category', 'featured_destination');
    }

    private function run_plugin()
    {
        
    }

}

$plcCollections = PlcCollections::get_instance();
