<?php

define('FILE_ADMIN_LOGIN', 'index.php');
define('FILE_ADMIN_WELCOME', 'welcome.php');

if(FILE_FILENAME_WITHOUT_EXT == 'index') {
    define('FILE_MAIN_INTERFACE', 'index.tpl.php');
} else {
    define('FILE_MAIN_INTERFACE', 'mainpage.tpl.php');
}

define('FILE_ADMIN_USER_LISTING', 'user_listing.php');
define('FILE_ADMIN_USER_EDIT', 'user_action.php');

define('FILE_ADMIN_LANGUAGE_LISTING', 'language_listing.php');
define('FILE_ADMIN_LANGUAGE_EDIT', 'language_action.php');

define('FILE_ADMIN_WEBSITE_LOGOS', 'website_logos.php');

define('FILE_ADMIN_AJAX_UPLOADER', 'file_uploader.php');

?>