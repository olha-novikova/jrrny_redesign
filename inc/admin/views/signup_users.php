<div class="wrap">
    <h1><?php _e('Special Signup Users'); ?></h1>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post" id="form-reports">
                        <?php
                        $signup_users_obj->prepare_items();
                        $signup_users_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>