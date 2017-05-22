<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if (post_password_required()){ ?>
	<p class="nopassword"><?php _e('This post is password protected. Enter the password to view comments.', 'ThemeStockyard');?></p> 
	<?php
	return;
} ?>

<?php
global $smof_data, $ts_comments_top_padding;
global $current_user;
wp_get_current_user();
if (is_user_logged_in()){
    echo '<p id="userName" style="display:none;">' . $current_user->user_login . ' ! Please post your comment</p>';
}
if(isset($smof_data['use_disqus']) && $smof_data['use_disqus'] && trim($smof_data['disqus_shortname'])) :
?>
            <div id="comments"></div>
            <div id="disqus_thread" class="comm-wrapper"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = '<?php echo esc_js($smof_data['disqus_shortname']);?>'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function() {
                    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <!--<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>-->
<?php
else :
?>
            <div id="comments" class="comm-wrapper">
                <?php 
                if(have_comments()) :
                ?>
                <div id="comments-content">
                    <div class="page-title clearfix">
                        <h6 class="uppercase small"><?php comments_number(__('No Comment', 'ThemeStockyard'), __('One Comment', 'ThemeStockyard'), __('% Comments', 'ThemeStockyard') );?></h6>
                        <?php echo do_shortcode('[divider height="10"]');?>
                    </div>

                    <ol class="commentlist <?php echo (ts_option_vs_default('show_comments_avatars', 1)) ? '' : 'no-comment-avatars';?>">
                        <?php wp_list_comments(array('callback' => 'ts_custom_comment_list')); ?>
                    </ol>

                    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                    <div id="comment-nav" class="comment-nav" role="navigation">
                        <div class="comm-prev"><?php previous_comments_link( __( '<i class="icon-chevron-left"></i> Older Comments', 'ThemeStockyard' ) ); ?></div>
                        <div class="comm-next"><?php next_comments_link( __( 'Newer Comments <i class="icon-chevron-right"></i>', 'ThemeStockyard' ) ); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php
                    if (!comments_open() && get_comments_number()) : ?>
                    <p class="nocomments"><?php _e('Comments are closed.', 'ThemeStockyard'); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div><!-- end div.comm-wrapper -->


<?php 
    $comment_form = array( 
        'fields' => apply_filters('comment_form_default_fields', array(

            'author' => '<fieldset class="comment-form-author ts-one-third">' .
                        '<input id="author" name="author" type="text" placeholder="'.__('Name *', 'ThemeStockyard') .'" value="' .
                        esc_attr($commenter['comment_author']) . '" size="30" tabindex="1" />' .
                        '</fieldset>',

            'email'  => '<fieldset class="comment-form-email ts-one-third">' .
                        '<input id="email" name="email" type="text" placeholder="'.__('Email *', 'ThemeStockyard') .'" value="' . 
                        esc_attr($commenter['comment_author_email']) . '" size="30" tabindex="2" />' .
                        '</fieldset>',

            'url'    => '<fieldset class="comment-form-url ts-one-third ts-column-last">' .
                        '<input id="url" name="url" type="text" placeholder="'.__('Website', 'ThemeStockyard') .'" value="' . 
                        esc_attr($commenter['comment_author_url']) . '" size="30" tabindex="3" />' .
                        '</fieldset>' )),

            'comment_field' => '<div class="clear"></div><fieldset class="comment_form_message">'.'<textarea id="comment" name="comment"></textarea><p id="commentUserName" style="font-weight:bold; text-transform: capitalize; color:#000; font-size:14px;"></p>'.'</fieldset>',

            'comment_notes_before' => '',
            'logged_in_as' => '',
            'title_reply' => '<span class="smaller uppercase">' . __('Have you done this? What can you add to this jrrny?', 'ThemeStockyard') . '</span>',
            'title_reply_to' => '<span class="smaller uppercase">' . __('Have you done this? What can you add to this jrrny?', 'ThemeStockyard') . '</span> <strong>%s</strong>',
            'cancel_reply_link' => '('.__('Cancel Reply', 'ThemeStockyard').')',
            'label_submit'=> __('Post Comment', 'ThemeStockyard'),
            'id_submit' => 'button',

    );

    comment_form($comment_form, $post->ID);

endif;
?>