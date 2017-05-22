<div class="login-form-wrapper">
    <h3 class="title">Please login to continue!</h3>
    <form method="post" id="login_form">
        <div class="form-group">
            <label class="sr-only" for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <button id="login_btn" class="btn btn-lg btn-blue btn-block">Log in&nbsp;<i class="fa processing-icon hide"></i></button>
                </div>   
                <div class="col-xs-12 col-sm-6">
                    <a href="<?php echo home_url();?>/register" class="btn btn-lg btn-link btn-block login_modal">or <strong>Join</strong>&nbsp;<i class="fa processing-icon hide"></i></a>
                </div>                           
            </div>
        </div>
        <div class="form-group social-logins">
            <?php $current_url = home_url(add_query_arg(array(),$wp->request));?>
            <a data-provider="Facebook" title="Connect with Facebook" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=<?php echo urlencode($current_url);?>"><i class="fa fa-facebook"></i>&nbsp;&nbsp;Join with Facebook</a>
            <a data-provider="Twitter" title="Connect with Twitter" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to=<?php echo urlencode($current_url);?>"><i class="fa fa-twitter"></i>&nbsp;&nbsp;Join with Twitter</a>
        </div>
    </form>        
</div>