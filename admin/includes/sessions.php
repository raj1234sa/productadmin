<?php
session_start();
if(!isset($_SESSION['admin_session'])) {
    $_SESSION['admin_session'] = serialize(array());
}

function addSession($name, $value) {
    $adminSession = unserialize($_SESSION['admin_session']);
    $adminSession[$name] = $value;
    $_SESSION['admin_session'] = serialize($adminSession);
}

function getSession($name) {
    $adminSession = unserialize($_SESSION['admin_session']);
    return $adminSession[$name];
}

function clearSession($name) {
    $adminSession = unserialize($_SESSION['admin_session']);
    if(isset($adminSession[$name])) {
        unset($adminSession[$name]);
        $_SESSION['admin_session'] = serialize($adminSession);
    }
}

$adminId = getSession('_ses_admin_login_id');
if(!empty($adminId)) {
    require_once(DIR_WS_MODEL.'AdminMaster.php');
    $adminMaster = new AdminMaster();

    $adminMaster->setWhere("AND admin_id = :admin_id", $adminId, 'int');
    $adminData = $adminMaster->getAdmin();
    if(!empty($adminData)) {
        $adminData = $adminData[0];
        define('SES_ADMIN_USERID', $adminData['admin_id']);
        define('SES_ADMIN_USERNAME', $adminData['admin_username']);

        $superadmin = ($adminData['is_superadmin'] == '1') ? true : false;
        define('IS_SUPER_ADMIN', $superadmin);
    }
} elseif(FILE_FILENAME_WITHOUT_EXT != '' && FILE_FILENAME_WITHOUT_EXT != 'index') {
    $backurl = urlencode(HOSTNAME_URL.$_SERVER['PHP_SELF']);
    show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_LOGIN.'?backurl='.$backurl);
}

?>