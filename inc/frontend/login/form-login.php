<div class="container">
	<h1>LOG IN</h1>
	<form method="post" action="" id="login_form" class="form-login-signup form-horizontal">
			<?php if(isset($_POST["activity-jrrny-tmp"]) && isset($_POST["place-jrrny-tmp"])):?>
				<h4> Please login to continue! </h4>
				<input type="hidden" name="place" value="<?php echo $_POST["place-jrrny-tmp"];?>" />
				<input type="hidden" name="activity" value="<?php echo $_POST["activity-jrrny-tmp"]; ?>" />
			<?php endif; ?>
			<?php if(isset($_GET['redirect'])) : ?>
				<input type="hidden" id="redirect" name="redirect" value="<?php echo $_GET["redirect"];?>" />
			<?php endif; ?>
		<div class="form-group">
			<label for="email" class="control-label col-xs-2">E-mail</label>
			<div class="col-xs-10">
				<input type="email" placeholder="Email" id="email" name="email" class="form-control required" aria-describedby="email-addon" />
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="control-label col-xs-2">Password</label>
			<div class="col-xs-10">
				<input type="password" placeholder="Password" id="password" name="password" class="form-control required" aria-describedby="password-addon" />
			</div>
		</div>
		<div class="login-info">
			<p class="register-now">Don't have an account yet? <a id="signup_lnk">Start being awesome here!</a></p>
			<?php do_action( 'wordpress_social_login' ); ?>
		</div>
		<div class="form-group">
			<div class="col-xs-6 lost-pass"><a href="<?php echo wp_lostpassword_url() ?>" title="Lost Password">Lost Password</a></div>
			<div class="col-xs-6">
				<a id="login_btn" class="btn btn-success">
					Login&nbsp;<i class="fa processing-icon hide"></i>
				</a>
			</div>
		</div>
	</form>
</div>