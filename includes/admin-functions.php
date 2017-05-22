<?php
/* AD control */

add_action('admin_menu', 'ad_control_admin_menu');
function ad_control_admin_menu() {
    //create new top-level menu
    add_submenu_page('edit.php', 'AD control', 'AD control', 'edit_posts', 'ad_control_page', 'ad_control_page');

    //call register settings function
    add_action( 'admin_init', 'ad_control_option' );
}
function ad_control_option() {
    //register our settings
    register_setting( 'ad_control_option', 'ad_single_page' );
}

function ad_control_page() {    
?>
<div class="wrap">
<h2>AD for all jrrnys (if one of them don't have one)</h2>

<form method="post" action="options.php">
    <?php 
    settings_fields( 'ad_control_option' ); ?>
    <?php do_settings_sections( 'ad_control_option' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">AD content</th>
            <td>
                <textarea name="ad_single_page" class="form-control"><?php echo get_option('ad_single_page');?></textarea>
            </td>
        </tr>       
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 
/* END AD control */
