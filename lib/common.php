<?php
error_reporting(0);

$is_cli = (!isset($_SERVER['HTTP_USER_AGENT']));
if($is_cli) {
    $_SERVER['DOCUMENT_ROOT'] = 'C:\xampp\htdocs\productadmin';
} else {
    $docRoot = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']);
    $docRoot = (explode('/',$docRoot))[0];
    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'/'.$docRoot;
}

$script_name = $_SERVER['SCRIPT_NAME'];
$filename = pathinfo($script_name, PATHINFO_BASENAME);
$filenameWithoutExt = pathinfo($script_name, PATHINFO_FILENAME);

define('FILE_FILENAME_WITH_EXT', $filename);
define('FILE_FILENAME_WITHOUT_EXT', $filenameWithoutExt);

require_once('path.php');
require_once(DIR_WS_LOCALCONFIG.'localconfig.php');

if(!$is_cli && strpos($_SERVER['REQUEST_URI'], '/admin/') !== FALSE) {
    define('IS_ADMIN', true);
} else {
    define('IS_ADMIN', false);
}

$is_ajax_request = false;
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $is_ajax_request = true;
}

require_once(DIR_WS_MODEL_CLASSES.'RMasterModel.php');
require_once(DIR_WS_VENDOR."autoload.php");
require_once(DIR_WS_LIB.'functions.php');
if(IS_ADMIN) {
    $page_title = '';
    require_once(DIR_WS_ADMIN_INCLUDES.'functions.php');
    defineAccessData();
    require_once(DIR_WS_ADMIN_INCLUDES.'filenames.php');
    createAdminConstants();
    createMenuActionConstants();
    require_once(DIR_WS_ADMIN_INCLUDES.'sessions.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'html_component.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'html_render.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'css.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'js.php');
    
    $breadcrumb_home = array(
        'title' => COMMON_DASHBOARD,
        'link' => (FILE_FILENAME_WITHOUT_EXT != 'welcome') ? DIR_HTTP_ADMIN.FILE_ADMIN_WELCOME : '',
    );
}
// var_dump(IS_ADMIN);
// echo '<pre>'; print_r($_SERVER); echo '</pre>'; exit;
?>