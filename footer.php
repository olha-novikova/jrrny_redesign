<?php
global $smof_data, $ts_options;
?>
            <?php
            echo ts_get_ticker('above-footer');
            ?>
            <div id="footer-copyright-wrap">
                <?php
                echo ts_get_bottom_ad();
                
                echo ts_bottom_ad_widgets_sep(false);
                
                ts_footer_widgets();
                ?>
                
                <?php
                if(ts_option_vs_default('show_copyright', 1) == 1) :
                ?>
                <div id="copyright-nav-wrap">
                    <div id="copyright-nav" class="container">
                        <div class="row">
                            <div class="nav mimic-smaller uppercase span6">
                                <?php
                                wp_nav_menu(array('container' => false, 'theme_location' => 'footer_nav', 'echo' => 1, 'depth' => 1));
                                ?>
                            </div>
                            <div class="copyright span6">
                                <p><?php echo do_shortcode(ts_option_vs_default('copyright_text'));?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endif;
                ?>
                
                <?php
                if(ts_option_vs_default('show_back_to_top', 0) == 1) :
                ?>
                <div id="ts-back-to-top-wrap">
                    <a href="#wrap" id="ts-back-to-top" class="smoothscroll-up"><i class="fa fa-arrow-up"></i></a>
                </div>
                <?php
                endif;
                ?>
                
            </div>
        </div>
    </div>
    
    <?php if(show_beta_popup() && !is_singular( array( 'sponsored_post', 'featured_destination') ) ): ?>
    <div class="modal fade plc-modal" tabindex="-1" role="dialog" id="beta-now">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <h2>Hey there! Thanks for visiting us!</h2>
                    <p>Jrrny's content is built by contributors just like you. Why not make your first post today?</p>
                    <p><a href="<?= home_url().'/register'?>">Start Here</a></p>

                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php endif ;?>
            
    <script>
        var tour = '<div class="tour_container"><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_0"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>how does jrrny work?</h2> <p>We’re building the world\'s "largest travel magazine" built by the people. This means that people just like you are encouraged to share a travel experience so you might connect with others!</p><div class="row modal-btn"> <div class="col-xs-12 col-sm-6"> <button type="button" class="btn btn-blue btn-block nextTour" data-dismiss="modal" data-terget="tour_1"><strong>take a tour</strong></button> </div><div class="col-xs-12 col-sm-6"> <a href="<?php echo home_url();?>/register" class="btn btn-red btn-block"><strong>nah, im good</strong><span>(sign me up)</span></a> </div></div></div><div class="modal-footer hidden"> <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_1"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>SHARE YOUR JRRNY</h2> <p>Jrrny allows anyone to post about a travel experience using a simple, drag and drop posting tool. It can be your favorite trip, or a place where you have some special insight.</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/modal_share_your_jrrny.png" width="600" height="340" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_2">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_2"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>MY JRRNYS</h2> <p>Your My Jrrnys page allows you to keep track (or edit) the jrrnys you post as well as the ones you like or follow. You can also tell other travelers a little about your expertise!</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/modal_my_jrrny.png" width="681" height="311" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_3">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_3"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>MORE INFO</h2> <p>All jrrnys have a link associated with them. If you\'re a blogger, you\'re welcome to share a link back to your blog, hotel, or other resource that travelers would find helpful.</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/modal_more_info.png" width="482" height="378" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_4">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_4"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>FEATURED</h2> <p>Each week, the Jrrnys with the most views are featured on Jrrny and automatically put in order of popularity on Trending. (If you\'re a popular for 2 weeks you receive a Jrrny t-shirt!). Be sure to share your jrrnys to your friends on facebook, twitter, or email. </p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/modal_featured.png" width="500" height="239" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_5">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_5"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>JOIN NOW!</h2> <p>What was your favorite trip? Start your jrrny and share it with the rest of Jrrny community!</p><div class="row modal-btn"> <div class="col-xs-12 col-sm-push-3 col-sm-6"> <a href="/register" class="btn btn-blue btn-block"><strong>sign up now</strong></a> </div></div></div></div></div></div></div>';
    </script>
<?php 
$ts_disqus_shortname = ts_option_vs_default('disqus_shortname', '');
if((ts_option_vs_default('use_disqus', 1) == 1) && trim($ts_disqus_shortname)) :
?>
<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
var disqus_shortname = '<?php echo esc_js($ts_disqus_shortname);?>'; // required: replace example with your forum shortname
(function($) {
    $(window).load(function() {
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = '//<?php echo esc_js($ts_disqus_shortname);?>.disqus.com/count.js';
            (document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    });
})(jQuery);
</script>
<?php
endif;
if(ts_enable_style_selector()) :
    get_template_part('style_selector');
endif;
wp_footer();
?>
</body>
</html>