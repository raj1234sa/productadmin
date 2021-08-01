<?php
require_once('../lib/common.php');

$action = getValue('action');
if($action == 'logout') {
    if(isset($_SESSION['admin_session'])) {
        unset($_SESSION['admin_session']);
    }
    show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_LOGIN);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


$breadcrumb_arr = array(
    $breadcrumb_home
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);
?>