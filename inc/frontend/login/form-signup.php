<?php
$parse_uri = explode( 'index.php', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
?>
<div class="container">
	<h1 class="register">START YOUR JRRNY!</h1>
	<form method="post" id="signup_form" class="form-login-signup form-horizontal">
		<div class="form-group">
			<label class="hint col-xs-2" for="first-name">First name</label>
			<div class="col-xs-10">
				<input type="text" id="first-name" name="first-name" class="form-control" aria-describedby="name-addon" pattern=".{2,}"/>
			</div>
		</div>
		<div class="form-group">
			<label class="hint col-xs-2" for="last-name">Last name</label>
			<div class="col-xs-10">
				<input type="text" id="last-name" name="last-name" class="form-control" aria-describedby="name-addon" pattern=".{2,}" />
			</div>
		</div>
		<div class="form-group">
			<label class="hint col-xs-2" for="email">Email</label>
			<div class=" col-xs-10">
				<input type="email" title="your@email.com" id="email" name="email" class="form-control required" aria-describedby="email-addon" />
			</div>

		</div>

		<div class="form-group">
			<label class="hint col-xs-2" for="country">Country</label>
			<div class="col-xs-10">
				<select title="" name="country" id="country">
					<?php get_template_part("inc/frontend/world-countries", "optionlist"); ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="hint col-xs-2" for="city">City</label>
			<div class="col-xs-10">
				<input type="text" id="city" name="city" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label class="hint col-xs-2" for="username">Username</label>
			<div class="col-xs-10">
				<input type="text" title="John11" id="username" name="username" class="form-control required" aria-describedby="name-addon" />
			</div>
		</div>

		<div class="form-group">
			<label class="hint col-xs-2" for="password">Password</label>
			<div class="col-xs-10">
				<input type="password" title="Enter your password" id="password" name="password" class="form-control required" aria-describedby="password-addon" />
			</div>
		</div>

		<div class="form-group">
                    <div class="col-sm-push-2 col-sm-10">
                        <div class="checkbox">
                            <label class="acc_req">
                              <input type="checkbox" name="acc_req" id="acc_req" value="0"> I agree to Jrrny's Terms and Conditions
                            </label>
                          </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 text-right"><a id="signup_btn" class="btn btn-success">Sign Up&nbsp;<i class="fa processing-icon hide"></i></a></div>
                </div>
		<div class="form-group subscribe-center">
			<?php do_action( 'wordpress_social_login' ); ?>
  		</div>
		<div class="form-group last-line">
			<div class="col-xs-6 ">
				<p class="login-line">
					Already have account? 
					<a href="<?php echo home_url();?>#login-form">Login</a>
				</p>
			</div>
			<div class="col-xs-6 text-right">
				<p class="login-line">
					<a href="<?php echo home_url();?>/terms.pdf">Terms and conditions of service</a> 
				</p>
			</div>
		</div>
	</form>
</div>
</div></div> </div></div></div></div></div></div>   
<?php 
get_footer();
die();?>