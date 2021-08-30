<?php
$frontCssArr['default'] = array(
    DIR_HTTP_THIRDPARTY_CSS.'bootstrap.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'icofont.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'boxicons.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'owl.carousel.min.css',
    DIR_HTTP_THIRDPARTY_CSS.'remixicon.css',
    DIR_HTTP_THIRDPARTY_CSS.'venobox.css',
    DIR_HTTP_THIRDPARTY_CSS.'aos.css',
    DIR_HTTP_TEMPLATES_CONTENT_CSS.'common.css',
);

function addCss() {
    global $frontCssArr;
    foreach ($frontCssArr['default'] as $value) {
        if(pathinfo($value, PATHINFO_EXTENSION) == 'js') {
            echo "<script src='".$value."?".time()."'></script>";
        } else {
            echo "<link rel='stylesheet' href='".$value."?".time()."'>";
        }
    }
}
?>