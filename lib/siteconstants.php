<?php

$siteSettingsMaster = new SiteSettingsMaster();

$siteSettingsData = $siteSettingsMaster->getSiteSetting();
$siteSettingsData = objectToArray($siteSettingsData);
foreach ($siteSettingsData as $setting) {
    $selectedValue = (!empty($setting['set_value'])) ? $setting['set_value'] : $setting['default_value'];
    define($setting['setting_constant'], $selectedValue);
}

$websiteLogoData = getWebsiteLogos();

if(!empty($websiteLogoData)) {
    $siteLogo = $websiteLogoData['site_logo'];
    $siteFavicon = $websiteLogoData['site_favicon'];

    if(!empty($siteLogo) && file_exists(DIR_WS_WEBSITE_LOGOS.$siteLogo) && is_file(DIR_WS_WEBSITE_LOGOS.$siteLogo))
        define('SITE_LOGO', DIR_HTTP_WEBSITE_LOGOS.$siteLogo);
    else
        define('SITE_LOGO', '');

    if(!empty($siteFavicon) && file_exists(DIR_WS_WEBSITE_LOGOS.$siteFavicon) && is_file(DIR_WS_WEBSITE_LOGOS.$siteFavicon)) {
        define('SITE_FAVICON', DIR_HTTP_WEBSITE_LOGOS.$siteFavicon);
        define('SITE_FAVICON_EXT', pathinfo($siteFavicon, PATHINFO_EXTENSION));
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