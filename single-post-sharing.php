
                                            <div id="ts-post-comments-share-wrap" class="clearfix <?php echo esc_attr(ts_post_sharing_class());?>">
                                                <?php get_template_part('inc/frontend/partpost/like-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/delete-part'); ?>
                                                <?php get_template_part('inc/frontend/partpost/edit-part'); ?>                                                
                                                <?php if(!plc_is_category(13)) { get_template_part('inc/frontend/partpost/following_span-part'); } ?>
                                                <?php
                                                $ts_sharing_options = ts_sharing_options_on_posts();
                                                if($ts_sharing_options->show) :
                                                ?>
                                                <?php
                                                endif;
                                                ?>
                                            </div>
