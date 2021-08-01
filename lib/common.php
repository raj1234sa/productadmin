<?php
error_reporting(0);

$docRoot = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']);
$docRoot = (explode('/',$docRoot))[0];
$_SERVER['DOCUMENT_ROOT'] = str_replace('/','\\',$_SERVER['DOCUMENT_ROOT']);
$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'\\'.$docRoot;

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
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $is_ajax_request = true;
}

require_once(DIR_WS_MODEL_CLASSES.'RMasterModel.php');
include_once DIR_WS_MODEL_CLASSES.'RDataModel.php';
require_once(DIR_WS_MODEL.'UtilMaster.php');
require_once(DIR_WS_VENDOR."autoload.php");
require_once(DIR_WS_LIB.'functions.php');
require_once(DIR_WS_LIB.'siteconstants.php');
if(IS_ADMIN) {
    $page_title = $global_js = '';
    require_once(DIR_WS_ADMIN_INCLUDES.'functions.php');
    defineAccessData();
    require_once(DIR_WS_ADMIN_INCLUDES.'filenames.php');
    createAdminConstants();
    require_once(DIR_WS_ADMIN_INCLUDES.'sessions.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'html_component.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'html_render.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'css.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'js.php');
    require_once(DIR_WS_ADMIN_INCLUDES.'table_helper.php');
    
    $breadcrumb_home = array(
        'title' => COMMON_DASHBOARD,
        'link' => (FILE_FILENAME_WITHOUT_EXT != 'welcome') ? DIR_HTTP_ADMIN.FILE_ADMIN_WELCOME : '',
    );
} else {
    $twc = array();
    createFrontConstants();
    require_once(DIR_WS_LIB.'twig.php');
    // echo '<pre>'; print_r($twc); echo '</pre>'; exit;
    define('FILE_MAIN_INTERFACE', 'mainpage.tpl');
}
// var_dump(IS_ADMIN);
// echo '<pre>'; print_r($_SERVER); echo '</pre>'; exit;
?>