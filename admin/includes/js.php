<?php
$adminJsArr['global'] = array(
    DIR_HTTP_THIRDPARTY_JS.'jquery-2.2.4.min.js',
    DIR_HTTP_THIRDPARTY_JS.'popper.min.js',
    DIR_HTTP_THIRDPARTY_JS.'bootstrap.min.js',
    DIR_HTTP_THIRDPARTY_JS.'owl.carousel.min.js',
    DIR_HTTP_THIRDPARTY_JS.'metisMenu.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.slimscroll.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.slicknav.min.js',
    DIR_HTTP_THIRDPARTY_JS.'plugins.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.cookie.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.dataTables.js',
    DIR_HTTP_THIRDPARTY_JS.'dataTables.buttons.min.js',
    DIR_HTTP_THIRDPARTY_JS.'buttons.print.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.validate.min.js',
    DIR_HTTP_THIRDPARTY_JS.'image-zoom.min.js',
    DIR_HTTP_THIRDPARTY_JS.'bootbox.min.js',
    DIR_HTTP_THIRDPARTY_JS.'ckeditor.js',
    DIR_HTTP_THIRDPARTY_JS.'bootstrap-select.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.smartWizard.min.js',
    DIR_HTTP_THIRDPARTY_JS.'scripts.js',
    DIR_HTTP_ADMIN_JS.'admin.js',
    DIR_HTTP_ADMIN_JS.'validation.js',
);

$adminJsArr['passenger_action.php'] = array(
    DIR_HTTP_ADMIN_JS.'passenger_action.js',
);

$adminJsArr['passenger_listing.php'] = array(
    DIR_HTTP_ADMIN_JS.'passenger_listing.js',
);

$adminJsArr['email_configuration_action.php'] = array(
    DIR_HTTP_ADMIN_JS.'email_configuration.js',
);

$adminJsArr['menu_link_action.php'] = array(
    DIR_HTTP_ADMIN_JS.'menu_link_action.js',
);

$adminJsArr['passenger_import.php'] = array(
    DIR_HTTP_ADMIN_JS.'passenger_import.js',
);

$adminJsArrMain = $adminJsArr['global'];
if(isset($adminJsArr[FILE_FILENAME_WITH_EXT])) {
    $adminJsArrMain = array_merge($adminJsArrMain, $adminJsArr[FILE_FILENAME_WITH_EXT]);
}

$globalJsVars = array();
$globalJsVars['COMMON_SAVE_BACK'] = COMMON_SAVE_BACK;
$globalJsVars['COMMON_SAVE'] = COMMON_SAVE;
$globalJsVars['DIR_HTTP_IMAGES_COMMON'] = DIR_HTTP_IMAGES_COMMON;
$globalJsVars['COMMON_UPLOAD_ERROR'] = COMMON_UPLOAD_ERROR;
$globalJsVars['COMMON_REQUIRED_RED_STAR'] = COMMON_REQUIRED_RED_STAR;
$globalJsVars['SC_NEXT'] = SC_NEXT;
$globalJsVars['SC_PREVIOUS'] = SC_PREVIOUS;
$globalJsVars['SC_FIRST'] = SC_FIRST;
$globalJsVars['SC_LAST'] = SC_LAST;
$globalJsVars['COMMON_FILE_DELETE_WARNING'] = COMMON_FILE_DELETE_WARNING;
$globalJsVars['COMMON_DELETE_SUCCESS'] = COMMON_DELETE_SUCCESS;
$globalJsVars['COMMON_DELETE_WARNING'] = COMMON_DELETE_WARNING;
$globalJsVars['SC_NO_RECORDS_TABLE'] = SC_NO_RECORDS_TABLE;
$globalJsVars['SC_NO_RECORDS_FOUND'] = SC_NO_RECORDS_FOUND;
$globalJsVars['DIR_WS_IMAGES'] = DIR_WS_IMAGES;
$globalJsVars['DIR_HTTP_IMAGES'] = DIR_HTTP_IMAGES;
$globalJsVars['SITE_URL'] = SITE_URL;
$globalJsVars['FILE_FILENAME_WITHOUT_EXT'] = FILE_FILENAME_WITHOUT_EXT;
$globalJsVars['FILE_FILENAME_WITH_EXT'] = FILE_FILENAME_WITH_EXT;
$globalJsVars['COMMON_IMPORT'] = COMMON_IMPORT;
$globalJsVars['COMMON_NEXT'] = COMMON_NEXT;
$globalJsVars['COMMON_PREVIOUS'] = COMMON_PREVIOUS;

if(!$isAjaxRequest) {
    echo "<script>";
    foreach ($globalJsVars as $key => $value) {
        echo "var $key = '".addslashes($value)."';";
    }
    echo "</script>";
}

function addJs() {
    global $adminJsArrMain;
    foreach ($adminJsArrMain as $value) {
        echo "<script src='".$value."?".time()."'></script>";
    }
}
?>