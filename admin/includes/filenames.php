<?php

define('FILE_ADMIN_LOGIN', 'index.php');
define('FILE_ADMIN_WELCOME', 'welcome.php');

if(FILE_FILENAME_WITHOUT_EXT == 'index') {
    define('FILE_MAIN_INTERFACE', 'index.tpl.php');
} else {
    define('FILE_MAIN_INTERFACE', 'mainpage.tpl.php');
}

define('FILE_ADMIN_WEBSITE_LOGOS', 'website_logos.php');
define('FILE_ADMIN_AJAX_UPLOADER', 'file_uploader.php');

define('FILE_ADMIN_PASSENGER_LISTING', 'passenger_listing.php');
define('FILE_ADMIN_PASSENGER_EDIT', 'passenger_action.php');

define('FILE_ADMIN_LANGUAGE_LISTING', 'language_listing.php');
define('FILE_ADMIN_LANGUAGE_EDIT', 'language_action.php');

define('FILE_ADMIN_EMAIL_CONFIG_LISTING', 'email_configuration.php');
define('FILE_ADMIN_EMAIL_CONFIG_EDIT', 'email_configuration_action.php');

define('FILE_ADMIN_SITE_SETTINGS_LISTING', 'site_settings.php');

?>