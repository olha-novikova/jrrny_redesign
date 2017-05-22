<?php

$action = isset($_GET['a']) ? $_GET['a'] : '';
$code = isset($_GET['c']) ? $_GET['c'] : '';

if($action === 'unsubscribe'){
    plc_remove_subscriber($code);
}

$redirectUrl = home_url();
wp_redirect($redirectUrl, '301');
exit; 
