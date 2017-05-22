<div class="single-post-likes">
	<div class="meta-item-likes">
        <?php if(is_user_logged_in()): ?>
            <a class="post-like-attributes  meta-item-like <?php (is_user_logged_in()) ? is_liked() : '' ?>" 
                data-on-post="<?php echo get_the_id();?>" 
                data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID'));?>"
            > 
            <span>Like</span>
            <?php $likes = get_post_meta( get_the_id(), "likes_count", true ); ?>
        	<span class="likes-quant" 
                        <?= (intval($likes)>0) ? 'style="display: inline;"' : '' ?>
            >
            	<?php echo $likes; ?></span> 
            </a>
        <?php endif; ?>
	</div>
</div>