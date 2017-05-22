<div class="wrap">
    <h1><?php _e('Stampready'); ?></h1>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                    if (isset($validation) && $validation !== '') {
                        echo $validation;
                    }
                    if(!$stampready_export){
                    ?>
                    <form method="post">   
                        <button role="submit" name="submit" value="export" class="button button-primary button-large">Export users to Stampready</button>
                    </form>
                    <?php } else {?>
                    You already exported users to Stampready
                    <?php } ?>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <ul id="list-added"></ul>
</div>