<?php

class PlcSponsoredPost
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
        register_post_type('sponsored_post', array(
            'labels' => array(
                'name' => __('Sponsored post'),
                'singular_name' => __('Sponsored post')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('thumbnail', 'title', 'editor')
                )
        );
    }

    public function tags_support_custom_post_type()
    {
        register_taxonomy_for_object_type('post_tag', 'sponsored_post');
        register_taxonomy_for_object_type('category', 'sponsored_post');
    }

    private function run_plugin()
    {
        
    }

}

$plcSponsoredPost = PlcSponsoredPost::get_instance();
