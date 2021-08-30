<?php
require_once('../lib/common.php');

$action = getValue('action');
if($action == 'logout') {
    if(isset($_SESSION['admin_session'])) {
        unset($_SESSION['admin_session']);
    }
    showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_LOGIN);
}

$headingLabel = $pageTitle;

$breadcrumbArr = array(
    $breadcrumbHome
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);
?>