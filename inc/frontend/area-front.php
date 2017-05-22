<?php //if(!is_page_template('upload.php')):?>
<div id="content-header">
	<!-- <form id='create_jrrny-elementary' method="post" action="<?php echo (!is_user_logged_in() ? home_url().'#login-form' : home_url().'/upload') ?>" class="col-md-12">
		<div class="col-md-5">
			<div class="form-group">
				<input type="text" placeholder="City | Country" name="place-jrrny-tmp" id="place-jrrny-tmp" class="form-control no-margin" />
				<label for="place-jrrny-tmp">
					Where have you been?
				</label>
			</div>
		</div> -->
		
			<!-- <p class="top-tagline">
                We're a community for travelers and travel pros to share experiences and urban discoveries.<br>  Click <a href="<?php echo home_url();?>/upload">here</a> to add your own! Or <a href="<?php echo home_url()?>/trending">viewwhat's trending.</a> 
            </p> -->

	        <div class="text-right right-side side search-area"><?php echo ts_top_search() ?></div>
	        
		
		<!-- <div class="col-md-5">
			<div class="form-group">
				<input type="text" placeholder="Activity" name="activity-jrrny-tmp" id="activity-jrrny-tmp" class="form-control no-margin" />
				<label for="activity-jrrny-tmp">
					What did you do?
				</label>
			</div>
		</div>
		<div class="col-md-2 text-left">
			<div class="form-group">
				<button id="process-jrrny-tmp">next <i class="fa fa-chevron-right"></i></button>
			</div>
		</div> -->
	<!-- </form> -->
</div>
<?php //endif; ?>
