<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php if(isset($errors) && $errors){ 
                foreach($errors as $error){ ?>
                    <div class="alert alert-danger"><?php echo $error;?></div>
                <?php } ?>
            <?php } ?>
            <form method="POST" class="form-contribute">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="username" value="<?php echo $username;?>" class="form-control input-lg"/>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $email;?>" class="form-control input-lg"/>
                </div>
                <hr/>
                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" name="twitter" value="<?php echo $twitter;?>" class="form-control input-lg"/>
                </div>
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="<?php echo $instagram;?>" class="form-control input-lg"/>
                </div>
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="<?php echo $facebook;?>" class="form-control input-lg"/>
                </div>
                <hr/>
                <div class="form-group">
                    <label>Website URL</label>
                    <input type="text" name="url" value="<?php echo $url;?>" class="form-control input-lg"/>
                </div>
                <div class="form-group">
                    <label>What is your area of expertise</label>
                    <textarea name="expertise" class="form-control"><?php echo $expertise;?></textarea>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input id="place-jrrny" type="text" name="location" value="<?php echo $location;?>" class="form-control input-lg"/>
                </div>
                <div class="form-group">
                    <input type="hidden" name="action" value="review"/>
                    <button role="button" class="btn btn-blue btn-lg" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>