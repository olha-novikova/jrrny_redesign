<div class="upload-widget">
    <div class="title">
        <i class="flaticon flaticon-checkbox-pen-outline"></i> Create your jrrny
    </div>
    <div class="text">
        Share with the community your travel experiences<br/> 
        Local or on the other side of the world. Let us hear your jrrny.
    </div>  
    <form id="top_upload" metgod="GET" action="<?php echo home_url();?>/upload">
         <div class="input-group">
            <span class="input-group-addon"><i class="flaticon flaticon-map-pin-marked"></i></span>
            <input type="text" name="place" id="place-jrrny" class="form-control" placeholder="Where you went - 'New York'">
        </div>
        <div class="form-group">
            <label>FOR</label>
        </div>
         <div class="input-group">
            <span class="input-group-addon"><i class="flaticon flaticon-directions-signs-outlines"></i></span>
            <input type="text" name="activity" id="activity-jrrny" class="form-control" placeholder="What did you do? 'Cocktails'">
        </div>
        <div class="form-group text-right">
            <button type="submit" id="header_btn" data-form="top_upload" class="btn btn-blue <?php echo !is_user_logged_in() ? 'login_modal' : '' ?>">Next</button>
        </div>
    </form>
</div>