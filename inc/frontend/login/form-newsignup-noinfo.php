<?php $disabled = is_user_logged_in() ? 'disabled' : ''; ?>

<div id="sign_wrapper" class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="singup-form-wrapper no-bg">
                <h3 class="title">Join jrrny to enter</h3>
                <form method="post" id="signup_form">                    
                    <input id="contest" name="contest" value="<?php echo get_option('pin_signup_contest'); ?>" type="hidden"/>
                    <div class="form-group">
                        <label class="sr-only" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group social-logins">
                        <?php $current_url = home_url(add_query_arg());?>                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 no-right-padding">
                                <a class="fb-login <?php echo $disabled;?>" data-provider="Facebook" title="Connect with Facebook" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=<?php echo urlencode($current_url);?>"><span class="flaticon flaticon-facebook-logo-button"></span>&nbsp;&nbsp;Join with Facebook</a>
                            </div>   
                            <div class="col-xs-12 col-sm-6">
                                <a class="tw-login <?php echo $disabled;?>" data-provider="Twitter" title="Connect with Twitter" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to=<?php echo urlencode($current_url);?>"><span class="flaticon flaticon-twitter-logo-button"></span>&nbsp;&nbsp;Join with Twitter</a>
                            </div>                           
                        </div>
                    </div>
                    <div class="form-group buttons">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <button id="signup_btn" class="btn btn-blue btn-block <?php echo $disabled;?>">Sign up&nbsp;<i class="fa processing-icon hide"></i></button>
                            </div>         
                            <div class="col-xs-12">
                                <p>ALREADY A MEMBER? <button id="signin_btn" class="btn btn-link login_modal <?php echo $disabled;?>"><strong>LOG IN</strong>&nbsp;<i class="fa processing-icon hide"></i></button></p>
                            </div>                           
                        </div>
                    </div>
                </form>        
            </div>      
        </div>
    </div>                                
</div>
