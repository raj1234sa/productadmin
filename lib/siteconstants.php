<?php

$websiteLogoData = getWebsiteLogos();

if(!empty($websiteLogoData)) {
    $site_logo = $websiteLogoData['site_logo'];
    $site_favicon = $websiteLogoData['site_favicon'];

    if(!empty($site_logo) && file_exists(DIR_WS_WEBSITE_LOGOS.$site_logo) && is_file(DIR_WS_WEBSITE_LOGOS.$site_logo))
        define('SITE_LOGO', DIR_HTTP_WEBSITE_LOGOS.$site_logo);
    else
        define('SITE_LOGO', '');

    if(!empty($site_favicon) && file_exists(DIR_WS_WEBSITE_LOGOS.$site_favicon) && is_file(DIR_WS_WEBSITE_LOGOS.$site_favicon)) {
        define('SITE_FAVICON', DIR_HTTP_WEBSITE_LOGOS.$site_favicon);
        define('SITE_FAVICON_EXT', pathinfo($site_favicon, PATHINFO_EXTENSION));
    }
    else {
        define('SITE_FAVICON', '');
        define('SITE_FAVICON_EXT', '');
    }
} else {
    define('SITE_LOGO', '');
    define('SITE_FAVICON', '');
    define('SITE_FAVICON_EXT', '');
}

?>