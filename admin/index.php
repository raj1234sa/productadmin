<?php
require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'AdminMaster.php');

$adminMaster = new AdminMaster();

$err = array();

$submit = postValue('admin_login');
if($submit == 'yes') {
    $adminUsername = postValue('admin_username');
    $adminPassword = md5(postValue('admin_password'));
    $backurl = postValue('backurl');

    $adminMaster->setWhere("AND admin_username = :admin_username", $adminUsername, 'string');
    $adminMaster->setWhere("AND admin_password = :admin_password", $adminPassword, 'string');
    $adminData = $adminMaster->getAdmin();

    if(!empty($adminData)) {
        setSession('_ses_admin_login_id', $adminData[0]['admin_id']);
        if(!empty($backurl)) {
            // $backurl = str_replace(HOSTNAME_URL.'/', HOSTNAME_URL, $backurl);
            showPageHeader($backurl);
        } else {
            showPageHeader(FILE_ADMIN_WELCOME);
        }
    } else {
        $err['username'] = COMMON_LOGIN_ERROR;
    }
}

require_once(DIR_WS_ADMIN_CONTENTS.FILE_MAIN_INTERFACE);
?>