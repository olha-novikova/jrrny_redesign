<div class="clearfix"></div>
<div class="container padding-top-30 padding-bottom-30">
    <h3>Lost your password?</h3>
    <p>No problem. We'll send you a link to create a new password.<br/>
        Should be there in a minute! <small>(Check your spam folder if it's not)</small></p>
	<form id = "lostpassword_form" method="post" action="" class="wp-user-form " data-redirect="<?php echo site_url().'#login-form' ?>">
		<div class="input-group">
                <span id="email-addon" class="input-group-addon"><i class="fa fa-envelope"></i></span>
				<input type="email" aria-describedby="email-addon" class="form-control required" name="user_login" id="user_login" placeholder="Email">
		</div>
	    <div class="input-group">
	        <button id="lostpassword_btn" class="btn btn-blue" name="user-submit"><?php _e('Reset my password'); ?>
	        	<i class="fa processing-icon hide"></i>
	        </button>
	        <input type="hidden" name="user-cookie" value="1" />
	        <input type="hidden" name="action" value="lostpass" />
	    </div>
	    <p id="form-error"></p>
	</form>
</div>
<!-- Modal after sen mail-->
<div class="modal fade" id="jrrny_lostpass_modal" tabindex="-1" role="dialog" aria-labelledby="jrrnyLostpassModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #000 !important;">
			<span aria-hidden="true">&times;</span>
		</button>        
		<h4 class="modal-title" id="myModalLabel">Success</h4>
      </div>
      <div class="modal-body">
        <p>
        	Thanks, We're sending you a link to create a new password. Should be there any minute!" (Check your spam folder if it's not)
        </p>
        <p>
        	When it arrives, click <a href="<?php echo site_url('login') ?>">here</a> to log back.
        </p>
      </div>
    </div>
  </div>
</div>