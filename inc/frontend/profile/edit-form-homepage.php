<?php
global $current_user;

$curauth = get_userdata(intval($current_user->ID));

$userLocation = get_user_meta($curauth->ID, "_user_location", true);
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
                                <span>Add Image</span>
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
                                            <?php get_template_part("inc/frontend/world-countries-optionlist", "cu"); ?>
                                        </select>
                                        <input id="city" type="text" name="city" class="form-control" placeholder="City"
                                               value="<?= isset($userLocation["city"]) ? $userLocation["city"] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">EDIT PASSWORD</label>
                                        <input id="password" type="password" name="password" class="form-control"
                                               placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="url">WEB SITE</label>
                                        <input id="url" type="text" name="url" class="form-control"
                                               placeholder="URL Link" value="<?php echo $curauth->user_url; ?>">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="description">TELL US ABOUT YOURSELF (120 Characters)</label>
                            <textarea id="description" name="description" class="form-control"
                                      maxlength="120"><?= get_user_meta($curauth->ID, 'description', true); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="quota">WHY DO YOU ENJOY JRRNY? (100 Characters)</label>
                            <input type="text" id="quota" name="quota" class="form-control" maxlength="100"
                                   value="<?= get_user_meta($curauth->ID, 'quota', true); ?>"/>
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
