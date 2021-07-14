<?php
session_start();
if(!isset($_SESSION['admin_session'])) {
    $_SESSION['admin_session'] = serialize(array());
}

function addSession($name, $value) {
    $admin_session = unserialize($_SESSION['admin_session']);
    $admin_session[$name] = $value;
    $_SESSION['admin_session'] = serialize($admin_session);
}

function getSession($name) {
    $admin_session = unserialize($_SESSION['admin_session']);
    return $admin_session[$name];
}

function clearSession($name) {
    $admin_session = unserialize($_SESSION['admin_session']);
    if(isset($admin_session[$name])) {
        unset($admin_session[$name]);
        $_SESSION['admin_session'] = serialize($admin_session);
    }
}

$admin_id = getSession('_ses_admin_login_id');
if(!empty($admin_id)) {
    require_once(DIR_WS_MODEL.'AdminMaster.php');
    $adminMaster = new AdminMaster();

    $adminMaster->setWhere("AND admin_id = :admin_id", $admin_id, 'int');
    $adminData = $adminMaster->getAdmin();
    if(!empty($adminData)) {
        $adminData = $adminData[0];
        define('SES_ADMIN_USERID', $adminData['admin_id']);
        define('SES_ADMIN_USERNAME', $adminData['admin_username']);

        $superadmin = $adminData['is_superadmin'] == '1' ? true : false;
        define('IS_SUPER_ADMIN', $superadmin);
    }
} elseif(FILE_FILENAME_WITHOUT_EXT != '' && FILE_FILENAME_WITHOUT_EXT != 'index') {
    $backurl = urlencode(HOSTNAME_URL.$_SERVER['PHP_SELF']);
    show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_LOGIN.'?backurl='.$backurl);
}

?>