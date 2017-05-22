<div class="wrap">
    <h1><?php _e('Subscribers'); ?></h1>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post" id="form-reports">   
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <?php
                        $subscribers_obj->search_box('Search Table', 's');
                        if( isset($_POST['s']) ){
                                $subscribers_obj->prepare_items($_POST['s']);
                        } else {
                                $subscribers_obj->prepare_items();
                        }                     
                        $subscribers_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>