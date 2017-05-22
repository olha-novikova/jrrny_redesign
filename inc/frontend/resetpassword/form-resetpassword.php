<div id="password-reset-form" class="container">
    <?php if ( $attributes['show_title'] ) : ?>

    <?php endif; ?>
 
 <?php if($_GET['login'] == 'invalidkey') : ?>
 	<h1>Invalid key!</h1>
 <?php elseif ($_GET['login'] == 'expiredkey') : ?>
 		<h1>Expired key!</h1>
 <?php elseif ($_GET['password'] == 'changed') : ?>
  	<h1>Password changed!</h1>
  	<p>
  		<a href="<?php echo site_url('login')?>">LOGIN</a>
  	</p>
 <?php else : ?>
	 <?php
	 $formActionUrl = site_url( 'wp-login.php?action=resetpass' );
	 ?>
	 <div class="clear"></div>

	 <form name="resetpassform" id="resetpass_form" action="<?php echo $formActionUrl ?>" method="post" autocomplete="off">
	        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $_REQUEST['login'] ); ?>" autocomplete="off" />
	        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $_REQUEST['key'] ); ?>" />
	        	 
	        <div class="input-group">
	        	<span id="email-addon" class="input-group-addon"><i class="fa fa-envelope"></i></span>
	            <input type="password" placeholder="<?php _e( 'New password', 'personalize-login' ) ?>" name="pass1" id="pass1" class="form-control required" size="20" value="" autocomplete="off" />
	        </div>
	        <div class="input-group">
	        	<span id="email-addon" class="input-group-addon"><i class="fa fa-envelope"></i></span>
	            <input type="password" placeholder="<?php _e( 'Repeat new password', 'personalize-login' ) ?>" name="pass2" id="pass2" class="form-control required" size="20" value="" autocomplete="off" />
	        </div>
	         
	        <p class="description"><Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers.</p>
	         
	        <div class="input-group">
	            <input type="submit" name="submit" id="resetpass-btn"
	                   class="button" value="<?php _e( 'Reset Password', 'personalize-login' ); ?>" />
	        </div>
			<?php if(isset($_GET['errors'])) : ?>
				<div>
					<i class="err-message user-message">
						<?php if($_GET['errors'] == 'password_reset_mismatch') : ?>
							Password reset mismatch!
						<?php endif; ?>
						<?php if($_GET['errors'] == 'password_reset_empty') : ?>
							Password reset empty!
						<?php endif; ?>
						<?php if($_GET['errors'] == 'password_reset_wrong') : ?>
							Your password must contain only letters and numbers and be at least 6 characters in length!
						<?php endif; ?>
					</i>
				</div>
			<?php endif ?>
	    </form>
	<?php endif ?>
</div>