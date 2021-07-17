<?php
$admin_css_arr['global'] = array(
    DIR_HTTP_THIRDPARTY_CSS.'bootstrap.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'font-awesome.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'themify-icons.css',
    DIR_HTTP_THIRDPARTY_CSS.'metisMenu.css',
    DIR_HTTP_THIRDPARTY_CSS.'owl.carousel.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'slicknav.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'typography.css',
    DIR_HTTP_THIRDPARTY_CSS.'default-css.css',
    DIR_HTTP_THIRDPARTY_CSS.'styles.css',
    DIR_HTTP_THIRDPARTY_CSS.'responsive.css',
    DIR_HTTP_THIRDPARTY_CSS.'jquery.dataTables.css',
    DIR_HTTP_THIRDPARTY_CSS.'image-zoom.css',
    DIR_HTTP_THIRDPARTY_JS.'modernizr-2.8.3.min.js',
    DIR_HTTP_ADMIN_CSS.'admin.css',
);

function addCss($admin_css_arr) {
    foreach ($admin_css_arr['global'] as $value) {
        if(pathinfo($value, PATHINFO_EXTENSION) == 'js') {
            echo "<script src='".$value."?".time()."'></script>";
        } else {
            echo "<link rel='stylesheet' href='".$value."?".time()."'>";
        }
    }
}
?>