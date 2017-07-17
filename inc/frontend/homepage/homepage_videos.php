<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olga
 * Date: 02.03.17
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */

/**
 * Add a page to the dashboard menu.
 */

define('API_KEY', 'AIzaSyACZBcvZblc1zzc4GzRlbj3RW5SuKDnLxA');

add_action('admin_menu', function(){
    add_menu_page( 'Homepage Video', 'Homepage Video', 'manage_options', 'site-options', 'add_video_setting', 'dashicons-video-alt3', 59 );
} );


function add_video_setting(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <?php

        if( get_current_screen()->parent_base !== 'options-general' )
            settings_errors('название_опции');
        ?>

        <form action="options.php" method="POST">
            <?php
            settings_fields( 'home_video_options' );
            do_settings_sections( 'home_video_settings_page' );
            submit_button();
            ?>
        </form>
    </div>
<?php

}

add_action('admin_init', 'home_video_settings');

function home_video_settings(){
    register_setting( 'home_video_options', 'home_video_link_1', 'sanitize_callback_for_link' );  //<- option = option name in DB
    register_setting( 'home_video_options', 'home_video_title_1', 'sanitize_callback_for_title' );  //<- option = option name in DB

    register_setting( 'home_video_options', 'home_video_link_2', 'sanitize_callback_for_link' );
    register_setting( 'home_video_options', 'home_video_title_2', 'sanitize_callback_for_title' );

    register_setting( 'home_video_options', 'home_video_link_3', 'sanitize_callback_for_link' );
    register_setting( 'home_video_options', 'home_video_title_3', 'sanitize_callback_for_title' );

    register_setting( 'home_video_options', 'home_video_show_on_page', 'sanitize_callback_for_title' );

    add_settings_section( 'home_video_slot_1', 'Video Slot 1', '', 'home_video_settings_page' ); // <-section
    add_settings_section( 'home_video_slot_2', 'Video Slot 2', '', 'home_video_settings_page' );
    add_settings_section( 'home_video_slot_3', 'Video Slot 3', '', 'home_video_settings_page' );
    add_settings_section( 'home_video_show_on_page_section', 'Show/Hide on home page', '', 'home_video_settings_page' );

    add_settings_field('home_video_link_1', 'YouTube Link', 'fill_video_link', 'home_video_settings_page', 'home_video_slot_1', array('id'=>'home_video_link_1'));
    add_settings_field('home_video_link_2', 'YouTube Link', 'fill_video_link', 'home_video_settings_page', 'home_video_slot_2', array('id'=>'home_video_link_2'));
    add_settings_field('home_video_link_3', 'YouTube Link', 'fill_video_link', 'home_video_settings_page', 'home_video_slot_3', array('id'=>'home_video_link_3') );

    add_settings_field('home_video_title_1', 'Video Title', 'fill_video_title', 'home_video_settings_page', 'home_video_slot_1', array('id'=>'home_video_title_1') );
    add_settings_field('home_video_title_2', 'Video Title', 'fill_video_title', 'home_video_settings_page', 'home_video_slot_2', array('id'=>'home_video_title_2') );
    add_settings_field('home_video_title_3', 'Video Title', 'fill_video_title', 'home_video_settings_page', 'home_video_slot_3', array('id'=>'home_video_title_3') );

    add_settings_field('home_video_show_on_page', 'Show this section on home page', 'fill_show_option', 'home_video_settings_page', 'home_video_show_on_page_section', array('id'=>'home_video_show_on_page') );

}

function _get_img_for_youtube_link($url, $decode = FALSE)
{
    $data = wp_remote_get($url);
    if (is_array($data) && $data['response']['code'] !== 404) {
        if ($decode) {
            $return = json_decode($data['body'], true);
        }
        else {
            $return = $data['body'];
        }
        return $return;
    }
    return false;
}
function _youtube_id_from_url($link)
{
    $url = urldecode(rawurldecode($link));

    $result = preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

    if ($result) {
        $yt_id = trim($matches[1]);
        return $yt_id;
    }

    return FALSE;
}

function fill_show_option($val){
    $id = $val['id'];
    $val = get_option($id);
    ?>
    <input type="checkbox" name="<?php echo $id; ?>" id ="<?php echo $id; ?>" value="1" <?php  if ($val == '1') echo "checked=\"checked\""; ?> />
    <?php
}

function fill_video_link($val){
    $id = $val['id'];
    $val = get_option($id);
    if ($val){
        $link = 'https://www.youtube.com/watch?v=' . $val;
        $thumb = get_option('video_'.$val);
    }
    ?>
    <input type="url" name="<?php echo $id; ?>" id ="<?php echo $id; ?>" value="<?php if (isset( $link )) echo $link; ?>" />
    <?php
    if (isset( $thumb )){
        ?>
        <img src="<?php if (isset( $thumb )) echo $thumb; ?>" alt="Video " width="200" style="float: right;"/>
    <?php
    }
}


function fill_video_title($val){
    $id = $val['id'];
    $val = get_option($id);

    ?>
    <input type="text" name="<?php echo $id; ?>" id ="<?php echo $id; ?>" value="<?php echo $val; ?>" />
<?php
}

function _get_yt_thumbnail($thumbs)
{
    if (isset($thumbs['maxres']['url'])) {
        return $thumbs['maxres']['url'];
    }
    elseif (isset($thumbs['standard']['url'])) {
        return $thumbs['standard']['url'];
    }
    elseif (isset($thumbs['high']['url'])) {
        return $thumbs['high']['url'];
    }
    elseif (isset($thumbs['medium']['url'])) {
        return $thumbs['medium']['url'];
    }
    elseif (isset($thumbs['default']['url'])) {
        return $thumbs['default']['url'];
    }
    else {
        return '';
    }
}


function sanitize_callback_for_link( $option ){
    $id =  _youtube_id_from_url($option);

    if ( $id ) {
        $link = 'https://www.youtube.com/watch?v=' . $id;

        $url = urldecode("https://www.googleapis.com/youtube/v3/videos?id=" . $id . "&key=" . API_KEY . "&fields=items(id,snippet(title,thumbnails))&part=snippet");
        $data = _get_img_for_youtube_link($url, true);

        if ($data) {
            foreach ($data['items'] as $vid) {
                $thumbnail = _get_yt_thumbnail($vid['snippet']['thumbnails']);
                if ($thumbnail) update_option( 'video_'.$id, $thumbnail );
            }
        }

        return $id;
    }
}

function sanitize_callback_for_title( $option ){

    strip_tags( $option );

    return $option;
}

function sanitize_callback_for_checkbox( $option ){

    return $option;
}

function show_video_content(  ){

$show_home_video = get_option('home_video_show_on_page');

if ( $show_home_video == 1){
    $video_id1 = get_option('home_video_link_1');
    $video_id2 = get_option('home_video_link_2');
    $video_id3 = get_option('home_video_link_3');

    $video_img1 = get_option('video_'.$video_id1);
    $video_img2 = get_option('video_'.$video_id2);
    $video_img3 = get_option('video_'.$video_id3);

    $video_text_1 = get_option('home_video_title_1');
    $video_text_2 = get_option('home_video_title_2');
    $video_text_3 = get_option('home_video_title_3');?>

    <div class="clearfix produced_by_jrrny not_logged">
        <div class="header-section">
            <div class="container">
                <p class="module-header">FEATURED VIDEOS</p>
            </div>
        </div>
        <div class="loop-wrap loop-3-column-wrap ">
            <div class="hfeed entries blog-entries loop loop-3-column no-sidebar clearfix">
                <?php if ( $video_id1 ){?>
                    <div id="home-video-1" class="hentry entry span4">
                        <div class="post-content">
                            <div class="ts-meta-wrap media-meta-wrap">
                                <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                    <div class="featured-video ">
                                        <img src="<?php echo esc_url($video_img1); ?>" alt="" width="480">
                                        <a class="featured-video-link modal-link" data-title="<?php echo $video_text_1; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id1;?>?rel=0&wmode=transparent&fs=0"  data-target="#videoModal"></a>
                                    </div>
                                </div>
                                <?php if ( $video_text_1 ){?>
                                    <div class="title-date clearfix">
                                        <div class="title-info">
                                            <h4 class="title-h entry-title ">
                                                <a class="modal-link" data-title="<?php echo $video_text_1; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id1;?>?rel=0&wmode=transparent&fs=0"  data-target="#videoModal"><?php echo $video_text_1; ?></a>
                                            </h4>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <?php if ( $video_id2 ){?>
                    <div id="home-video-2" class="hentry entry span4">
                        <div class="post-content">
                            <div class="ts-meta-wrap media-meta-wrap">
                                <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                    <div class="featured-video ">
                                        <img src="<?php echo esc_url($video_img2); ?>" alt="" width="480">

                                        <a class="featured-video-link modal-link" data-title="<?php echo $video_text_2; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id2;?>?rel=0&wmode=transparent&fs=0"  data-target="#videoModal"></a>
                                    </div>
                                </div>
                                <?php if ( $video_text_2 ){?>
                                    <div class="title-date clearfix">
                                        <div class="title-info">
                                            <h4 class="title-h entry-title ">
                                                <a class="modal-link" data-title="<?php echo $video_text_2; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id2;?>?rel=0&wmode=transparent&fs=0"  data-target="#videoModal"><?php echo $video_text_2; ?></a>
                                            </h4>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <?php if ( $video_id3 ){?>
                    <div id="home-video-3" class="hentry entry span4">
                        <div class="post-content">
                            <div class="ts-meta-wrap media-meta-wrap">
                                <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                    <div class="featured-video ">
                                        <img src="<?php echo esc_url($video_img3); ?>" alt="" width="480">
                                        <a class="featured-video-link modal-link" data-title="<?php echo $video_text_3; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id3;?>?rel=0&wmode=transparent&fs=0" data-target="#videoModal"></a>
                                    </div>
                                </div>
                                <?php if ( $video_text_3 ){?>
                                    <div class="title-date clearfix">
                                        <div class="title-info">
                                            <h4 class="title-h entry-title ">
                                                <a class="modal-link" data-title="<?php echo $video_text_3; ?>" data-toggle="modal" data-src="http://www.youtube.com/embed/<?php echo $video_id3;?>?rel=0&wmode=transparent&fs=0"  data-target="#videoModal"><?php echo $video_text_3; ?></a>
                                            </h4>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="videoWrapper">
                        <iframe frameborder="0"></iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>
<style>
    .videoWrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        padding-top: 25px;
        height: 0;
    }
    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }


</style>
    <script>

            $('a.modal-link').on('click', function(e) {
                var src = $(this).attr('data-src');
                var title = $(this).attr('data-title');

                var width = (window.innerWidth - 50)|| 565;

                $("#videoModal .modal-title").html(title);

                $("#videoModal iframe").attr(
                    {'src':src /*,
                    'width': width+'px'*/}
                );
            });

    </script>
<?php }
}

add_shortcode('show_video', 'show_video_content');