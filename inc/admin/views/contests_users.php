<div class="wrap">
    <h1><?php _e('Attended Users'); ?></h1>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post" id="form-reports">   
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <?php
                        $contests_users_obj->search_box('Search Table', 's');
                        if( isset($_POST['s']) ){
                                $contests_users_obj->prepare_items($_POST['s']);
                        } else {
                                $contests_users_obj->prepare_items();
                        }                     
                        $contests_users_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>