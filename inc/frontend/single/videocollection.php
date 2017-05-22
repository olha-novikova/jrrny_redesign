
<?php if( have_rows('own_video_section') ):?>
    <div class="clearfix produced_by_jrrny not_logged">
        <div class="header-section">
            <div class="container">
                <p class="module-header">FEATURED VIDEOS</p>
            </div>
        </div>
        <div class="loop-wrap loop-3-column-wrap ">
            <div class="hfeed entries blog-entries loop loop-3-column no-sidebar clearfix">
                <?php
                while ( have_rows('own_video_section') ) : the_row();

                    $link   = get_sub_field('video_link');
                    $title  = get_sub_field('video_title');
                    $id = plc_youtube_id_from_url($link);?>

                    <div id="home-video-<?php echo $id; ?>" class="hentry entry span4">
                        <div class="post-content">
                            <div class="ts-meta-wrap media-meta-wrap">
                                <div class="featured-media-wrap ts-featured-media-standard" style="opacity: 1;">
                                    <div class="featured-video ">
                                        <iframe id="ytplayer-<?php echo $id; ?>" type="text/html" width="640" height="360"  src="http://www.youtube.com/embed/<?php echo $id; ?>?autoplay=0" frameborder="0" ></iframe>
                                    </div>
                                </div>
                                <?php if ( $title ){?>
                                    <div class="title-date clearfix">
                                        <div class="title-info">
                                            <h4 class="title-h entry-title ">
                                                <a  data-src="http://www.youtube.com/embed/<?php echo $id;?>?rel=0&wmode=transparent&fs=0" data-title="<?php echo $title; ?>" class = "video-modal-link" data-toggle="modal" data-target="#VideoModal" href="#"><?php echo $title; ?></a>
                                            </h4>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>

                <?php endwhile;?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="VideoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0"></iframe>
                </div>

            </div>
        </div>
    </div>
    <script>

       $('a.video-modal-link').on('click', function(e) {
            var src = $(this).attr('data-src');
            var title = $(this).attr('data-title');

            var height = $(this).attr('data-height') || 325;
            var width = $(this).attr('data-width') || 565;

            $("#VideoModal .modal-title").html(title);

            $("#VideoModal iframe").attr(
                {'src':src,
                    'height': height,
                    'width': width});
        });

    </script>
<?php endif; ?>