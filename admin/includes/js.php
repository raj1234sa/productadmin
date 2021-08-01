<?php
$admin_js_arr['global'] = array(
    DIR_HTTP_THIRDPARTY_JS.'jquery-2.2.4.min.js',
    DIR_HTTP_THIRDPARTY_JS.'popper.min.js',
    DIR_HTTP_THIRDPARTY_JS.'bootstrap.min.js',
    DIR_HTTP_THIRDPARTY_JS.'owl.carousel.min.js',
    DIR_HTTP_THIRDPARTY_JS.'metisMenu.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.slimscroll.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.slicknav.min.js',
    DIR_HTTP_THIRDPARTY_JS.'plugins.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.cookie.min.js',
    DIR_HTTP_THIRDPARTY_JS.'scripts.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.dataTables.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.validate.min.js',
    DIR_HTTP_THIRDPARTY_JS.'image-zoom.min.js',
    DIR_HTTP_THIRDPARTY_JS.'bootbox.min.js',
    DIR_HTTP_THIRDPARTY_JS.'ckeditor.js',
    DIR_HTTP_ADMIN_JS.'admin.js',
    DIR_HTTP_ADMIN_JS.'validation.js',
);

$global_js_vars = array();

$global_js_vars['COMMON_SAVE_BACK'] = COMMON_SAVE_BACK;
$global_js_vars['COMMON_SAVE'] = COMMON_SAVE;
$global_js_vars['DIR_HTTP_IMAGES_COMMON'] = DIR_HTTP_IMAGES_COMMON;
$global_js_vars['COMMON_UPLOAD_ERROR'] = COMMON_UPLOAD_ERROR;
$global_js_vars['COMMON_REQUIRED_RED_STAR'] = COMMON_REQUIRED_RED_STAR;
$global_js_vars['SC_NEXT'] = SC_NEXT;
$global_js_vars['SC_PREVIOUS'] = SC_PREVIOUS;
$global_js_vars['SC_FIRST'] = SC_FIRST;
$global_js_vars['SC_LAST'] = SC_LAST;
$global_js_vars['COMMON_FILE_DELETE_WARNING'] = COMMON_FILE_DELETE_WARNING;
$global_js_vars['COMMON_DELETE_SUCCESS'] = COMMON_DELETE_SUCCESS;
$global_js_vars['COMMON_DELETE_WARNING'] = COMMON_DELETE_WARNING;
$global_js_vars['SC_NO_RECORDS_TABLE'] = SC_NO_RECORDS_TABLE;
$global_js_vars['SC_NO_RECORDS_FOUND'] = SC_NO_RECORDS_FOUND;

if(!$is_ajax_request) {
    echo "<script>";
    foreach ($global_js_vars as $key => $value) {
        echo "var $key = '$value';";
    }
    echo "</script>";
}

function addJs($admin_js_arr) {
    foreach ($admin_js_arr['global'] as $value) {
        echo "<script src='".$value."?".time()."'></script>";
    }
}
?>