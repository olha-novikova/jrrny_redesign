<?php
if (isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
} else {
    $curauth = get_userdata(intval($author));
}
$userLocation = get_user_meta($curauth->ID, '_user_location', true);
$userNotification = get_user_meta($curauth->ID, '_notification', true);
if ($userNotification === "") { //default
    $userNotification = true;
}
?>
<!-- Modal -->
<div class="modal fade" id="jrrny-auhor-edit" tabindex="-1" role="dialog" aria-labelledby="jrrnyAuhorEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-head"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <form id="jrrny-edit-avatar-form" action="<?= admin_url() . 'admin-ajax.php' ?>" method="post"
                              enctype="multipart/form-data" role="form">
                            <input type="hidden" name="user-id" value="<?= $curauth->ID ?>">
                            <input type="hidden" name="user-login" value="<?= $curauth->user_login ?>">
                            <input type="hidden" name="action" value="edit_avatar">
                            <?php echo get_avatar($curauth->ID, 256, '', $curauth->user_login); ?>
                            <div class="fileUpload btn btn-danger">
                                <span>choose image</span>
                                <input type="file" class="upload" name="avatar" id="avatar-input" autocomplete="off"/>
                            </div>
                            <div class="">
                                <label for=notification"">notification?</label>
                                <input type="checkbox" class="notification" name="notification" id="notification-input"
                                       autocomplete="off" <?= ($userNotification) ? 'checked' : '' ?>/>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <form id="jrrny-edit-profile-form" action="<?= admin_url() . 'admin-ajax.php' ?>" method="post"
                              enctype="multipart/form-data" role="form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="hidden" name="user-id" value="<?= $curauth->ID ?>">
                                    <input type="hidden" name="action" value="edit_profile">

                                    <div class="form-group">
                                        <label for="email">UPDATE EMAIL</label>
                                        <input id="email" type="email" name="email" class="form-control"
                                               placeholder="Email" value="<?= $curauth->user_email ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="first-name">UPDATE FIRST NAME</label>
                                        <input id="first-name" type="text" name="first-name" class="form-control"
                                               placeholder="Name" value="<?= $curauth->first_name ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="last-name">UPDATE LAST NAME</label>
                                        <input id="last-name" type="text" name="last-name" class="form-control"
                                               placeholder="Surname" value="<?= $curauth->last_name ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">UPDATE LOCATION</label>
                                        <select name="country" id="country" autocomplete="off" class="form-control">
                                            <?php get_template_part("inc/frontend/world-countries", "optionlist"); ?>
                                        </select>
                                        <input id="city" type="text" name="city" class="form-control" placeholder="City"
                                               value="<?= isset($userLocation["city"]) ? $userLocation["city"] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">SET NEW PASSWORD</label>
                                        <input id="password" type="password" name="password" class="form-control"
                                               placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="url">URL Link</label>
                                        <input id="url" type="text" name="url" class="form-control"
                                               placeholder="URL Link" value="<?php echo $curauth->user_url; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook">Facebook Link</label>
                                        <input id="facebook" type="text" name="facebook" class="form-control"
                                               placeholder="Facebook Link" value="<?= get_user_meta($curauth->ID, '_facebook', true); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="twitter">Twitter Link</label>
                                        <input id="twitter" type="text" name="twitter" class="form-control"
                                               placeholder="Twitter Link" value="<?= get_user_meta($curauth->ID, '_twitter', true); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="google">Google Link</label>
                                        <input id="google" type="text" name="google" class="form-control"
                                               placeholder="Google Link" value="<?= get_user_meta($curauth->ID, '_google', true); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="tumblr">Tumblr Link</label>
                                        <input id="tumblr" type="text" name="tumblr" class="form-control"
                                               placeholder="Tumblr Link" value="<?= get_user_meta($curauth->ID, '_tumblr', true); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="linkedin">LinkedIn Link</label>
                                        <input id="linkedin" type="text" name="linkedin" class="form-control"
                                               placeholder="LinkedIn Link" value="<?= get_user_meta($curauth->ID, '_linkedin', true); ?>">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="quota">WHY DO YOU ENJOY JRRNY? (100 Characters)</label>
                            <input type="text" id="quota" name="quota" class="form-control" maxlength="100"
                                   value="<?= get_user_meta($curauth->ID, 'quota', true); ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="full_description">Description</label>
                            <textarea id="full_description" name="full_description" 
                                      class="form-control"><?= get_user_meta($curauth->ID, 'full_description', true); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="specials_offers">Specials & Offers</label>
                            <textarea id="specials_offers" name="specials_offers" 
                                      class="form-control"><?= get_user_meta($curauth->ID, 'specials_offers', true); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="profile-updated" class="hidden">Your profile has been updated!</div>
                 <button type="submit" id="jrrny-edit-profile-btn" class="btn btn-primary edit-profile">
                    UPDATE&nbsp;<i class="fa processing-icon hide"></i></button>
                <button type="button" id="jrrny-edit-profile-btn-cancel" class="btn btn-default" data-dismiss="modal">
                    CANCEL
                </button>
                <button type="submit" id="jrrny-edit-profile-btn-2" class="btn btn-default edit-profile">
                    DONE&nbsp;<i class="fa processing-icon hide"></i>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>