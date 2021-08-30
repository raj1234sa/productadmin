<?php
$frontJsArr['default'] = array(
    DIR_HTTP_THIRDPARTY_JS.'jquery-2.2.4.min.js',
    DIR_HTTP_THIRDPARTY_JS.'popper.min.js',
    DIR_HTTP_THIRDPARTY_JS.'bootstrap.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.easing.min.js',
    DIR_HTTP_THIRDPARTY_JS.'owl.carousel.min.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.waypoints.min.js',
    DIR_HTTP_THIRDPARTY_JS.'counterup.min.js',
    DIR_HTTP_THIRDPARTY_JS.'isotope.pkgd.min.js',
    DIR_HTTP_THIRDPARTY_JS.'venobox.min.js',
    DIR_HTTP_THIRDPARTY_JS.'aos.js',
    DIR_HTTP_TEMPLATES_CONTENT_JS.'common.js',
);

$frontJsArrMain = $frontJsArr['default'];
if(isset($frontJsArr[FILE_FILENAME_WITH_EXT])) {
    $frontJsArrMain = array_merge($frontJsArrMain, $frontJsArr[FILE_FILENAME_WITH_EXT]);
}

$globalJsVars = array();
$globalJsVars['DIR_WS_IMAGES'] = DIR_WS_IMAGES;
$globalJsVars['DIR_HTTP_IMAGES'] = DIR_HTTP_IMAGES;
$globalJsVars['SITE_URL'] = SITE_URL;

if(!$isAjaxRequest) {
    echo "<script>";
    foreach ($globalJsVars as $key => $value) {
        echo "var $key = '".addslashes($value)."';";
    }
    echo "</script>";
}

function addJs() {
    global $frontJsArrMain;
    foreach ($frontJsArrMain as $value) {
        echo "<script src='".$value."?".time()."'></script>";
    }
}
?>