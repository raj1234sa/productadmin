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
    DIR_HTTP_THIRDPARTY_JS.'scripts.js',
    DIR_HTTP_THIRDPARTY_JS.'jquery.dataTables.js',
    DIR_HTTP_ADMIN_JS.'admin.js',
);

function addJs($admin_js_arr) {
    foreach ($admin_js_arr['global'] as $value) {
        echo "<script src='".$value."?".time()."'></script>";
    }
    echo "<script>";
    foreach (get_defined_constants(true)['user'] as $key => $value) {
        // echo "var ".$key." = '".$value."';";
    }
    echo "</script>";
}
?>