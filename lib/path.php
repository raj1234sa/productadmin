<?php
define('SITE_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'].'\\');
define('MAIN_URL', 'http://localhost/productadmin/');
define('HOSTNAME_URL', 'http://localhost/');

define('DIR_WS_ADMIN', SITE_DOCUMENT_ROOT.'admin\\');
define('DIR_HTTP_ADMIN', MAIN_URL.'admin/');

define('DIR_WS_LIB', SITE_DOCUMENT_ROOT.'lib\\');

define('DIR_WS_LOCALCONFIG', SITE_DOCUMENT_ROOT.'localconfig\\');

define('DIR_WS_MODEL', SITE_DOCUMENT_ROOT.'model\\');
define('DIR_WS_MODEL_CLASSES', DIR_WS_MODEL.'classes\\');

define('DIR_WS_ADMIN_INCLUDES', DIR_WS_ADMIN.'includes\\');
define('DIR_HTTP_ADMIN_INCLUDES', DIR_HTTP_ADMIN.'includes/');

define('DIR_WS_ADMIN_CONTENTS', DIR_WS_ADMIN_INCLUDES.'content\\');
define('DIR_HTTP_ADMIN_CONTENTS', DIR_HTTP_ADMIN_INCLUDES.'content/');

define('DIR_HTTP_THIRDPARTY', MAIN_URL.'thirdparty/');
define('DIR_HTTP_THIRDPARTY_CSS', DIR_HTTP_THIRDPARTY.'css/');
define('DIR_HTTP_THIRDPARTY_JS', DIR_HTTP_THIRDPARTY.'js/');

define('DIR_HTTP_ADMIN_JS', DIR_HTTP_ADMIN_INCLUDES.'js/');
define('DIR_HTTP_ADMIN_CSS', DIR_HTTP_ADMIN_INCLUDES.'css/');

define('DIR_WS_IMAGES', SITE_DOCUMENT_ROOT.'images\\');
define('DIR_HTTP_IMAGES', MAIN_URL.'images/');

define('DIR_WS_IMAGES_FLAGS', DIR_WS_IMAGES.'flags\\');
define('DIR_HTTP_IMAGES_FLAGS', DIR_HTTP_IMAGES.'flags/');

define('DIR_WS_VENDOR', SITE_DOCUMENT_ROOT.'vendor/');

define('DIR_WS_WEBSITE_LOGOS', DIR_WS_IMAGES.'websitelogos\\');
define('DIR_HTTP_WEBSITE_LOGOS', DIR_HTTP_IMAGES.'websitelogos/');

define('DIR_WS_TEMP_IMAGES', DIR_WS_IMAGES.'tempimages\\');
define('DIR_HTTP_TEMP_IMAGES', DIR_HTTP_IMAGES.'tempimages/');

define('DIR_WS_IMAGES_COMMON', DIR_WS_IMAGES.'common\\');
define('DIR_HTTP_IMAGES_COMMON', DIR_HTTP_IMAGES.'common/');


?>