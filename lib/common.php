<?php
error_reporting(0);

$docRoot = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']);
$docRoot = (explode('/',$docRoot))[0];
$_SERVER['DOCUMENT_ROOT'] = str_replace('/','\\',$_SERVER['DOCUMENT_ROOT']);
$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'\\'.$docRoot;

$scriptName = $_SERVER['SCRIPT_NAME'];
$filename = pathinfo($scriptName, PATHINFO_BASENAME);
$filenameWithoutExt = pathinfo($scriptName, PATHINFO_FILENAME);

define('FILE_FILENAME_WITH_EXT', $filename);
define('FILE_FILENAME_WITHOUT_EXT', $filenameWithoutExt);

require_once('path.php');
require_once(DIR_WS_LOCALCONFIG.'localconfig.php');

if(strpos($_SERVER['REQUEST_URI'], '/admin/') !== FALSE) {
    define('IS_ADMIN', true);
} else {
    define('IS_ADMIN', false);
}

$isAjaxRequest = false;
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $isAjaxRequest = true;
}

require_once(DIR_WS_MODEL_CLASSES.'RMasterModel.php');
include_once DIR_WS_MODEL_CLASSES.'RDataModel.php';
require_once(DIR_WS_MODEL.'UtilMaster.php');
require_once(DIR_WS_MODEL.'SiteSettingsMaster.php');
require_once(DIR_WS_VENDOR."autoload.php");
require_once(DIR_WS_LIB.'site_variables.php');
require_once(DIR_WS_LIB.'email_notification.php');
require_once(DIR_WS_LIB.'mail_config.php');
require_once(DIR_WS_LIB.'encryption.php');
require_once(DIR_WS_LIB.'functions.php');
require_once(DIR_WS_LIB.'siteconstants.php');
if(IS_ADMIN) {
    $pageTitle = $globalJs = '';
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
    
    $breadcrumbHome = array(
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