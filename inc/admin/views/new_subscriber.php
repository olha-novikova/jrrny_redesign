<div class="wrap">
    <h1><?php _e('New Subscriber'); ?></h1>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post" id="form-reports">   
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <?php plc_user_to_newsletter(); ?>
                        <a href="#" class="add-user-to-newsletter button button-primary button-large">Add user to subscribers&nbsp;<i class="fa processing-icon hide"></i></a>
                    
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <ul id="list-added"></ul>
</div>