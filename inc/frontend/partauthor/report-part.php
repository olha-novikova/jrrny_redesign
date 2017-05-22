<!-- Views chart -->
<?php 
global $current_user;
wp_get_current_user();
if(isset($_GET['author_name'])) {
    $curauth = get_userdatabylogin($author_name);
} else {
    $curauth = get_userdata(intval($author));
}
?>
<?php if(get_current_user_id() == $curauth->ID || current_user_can('administrator')) : ?>
<?php
$viewsPerDay = [];
for($i = 30; $i >= 0; $i--) {
	$timestamp = strtotime('-'. $i .' days');
	$day = date("d M", $timestamp);
    $viewsPerDay[$day] = getViewsPerDay($timestamp, $curauth->ID); 
}
?>
	<div class="jrrny-chart-navigate row">
		<h3 class="col-md-11">Here's a look at how your jrrnys are being viewed!</h3>
	    <button id="jrrnyViewChartShowBtn" class="btn btn-default btn-xs col-md-1">
		    <i class="fa fa-minus"></i> hide
	    </button>
	</div>
    <div id="morisChart">
	    <div id="myViewChart" style="height: 250px;"></div>
	    <br>
    </div>
    <script type="text/javascript">
    new Morris.Bar({
	  element: 'myViewChart',
	  data: [
	  <?php foreach ($viewsPerDay as $key => $value) : ?>
	  	{ y: '<?= $key?>', x: <?= $value ?> },
	  <?php endforeach ?>
	  ],
	  xkey: 'y',
	  ykeys: ['x'],
	  labels: ['Views'],
	  barColors: ['#313131']
	});

    jQuery('#jrrnyViewChartShowBtn').click(function (e){
    	if(jQuery('#morisChart').is(":hidden")){
    		jQuery('#morisChart').show();
    		jQuery(this).html('<i class="fa fa-minus"></i> hide');
    	}else {
    		jQuery('#morisChart').hide();
    		jQuery(this).html('<i class="fa fa-plus"></i> show');
    	}
	});
    </script>
<?php endif; ?>