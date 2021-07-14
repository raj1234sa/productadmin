<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'AdminMaster.php');

$adminMaster = new AdminMaster();
// $adminData = new AdminData();
// $adminData->admin_password = '21232f297a57a5a743894a0e4a801fc3';
// $adminData->admin_id = '3';
// $adminMaster->editAdmin($adminData);
$adminData = $adminMaster->getAdmin();

// echo '<pre>'; print_r($adminData); echo '</pre>';

// exit;

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'title' => COMMON_CUSTOMERS,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>