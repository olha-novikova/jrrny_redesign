<div class="upload-widget small-widget">
    <div class="title">
        <i class="flaticon flaticon-checkbox-pen-outline"></i> Create your jrrny
    </div>
    <form id="top_upload" metgod="GET" action="<?php echo home_url();?>/upload">
         <div class="input-group">
            <span class="input-group-addon"><i class="flaticon flaticon-map-pin-marked"></i></span>
            <input type="text" name="place" id="place-jrrny-2" class="form-control place-jrrny" placeholder="Where you went - 'New York'">
        </div>
        <div class="form-group">
            <label>FOR</label>
        </div>
         <div class="input-group">
            <span class="input-group-addon"><i class="flaticon flaticon-directions-signs-outlines"></i></span>
            <input type="text" name="activity" class="form-control activity-jrrny" placeholder="What did you do? 'Cocktails'">
        </div>
        <div class="form-group text-right">
            <button type="submit" id="header_btn" data-form="top_upload" class="btn btn-blue <?php echo !is_user_logged_in() ? 'login_modal' : '' ?>">Next&nbsp;<i class="fa processing-icon hide"></i></button>
        </div>
    </form>
</div>