<?php

require_once('../lib/common.php');
$objSiteSettingsMaster = new SiteSettingsMaster();

$submit = postValue('submit_btn');

$tabs = array(
    0 => COMMON_GENERAL
);

$settingTabsArray = array();

$settingData = $objSiteSettingsMaster->getSiteSetting(null, null, 'yes');
foreach ($settingData as $setting) {
    if(array_key_exists($setting['section_name'], $tabs)) {
        $settingTabsArray[$tabs[$setting['section_name']]][] = objectToArray($setting);
    }
}

if(!empty($submit)) {
    $stConfig = postValue('stconfig');
    if(!empty($stConfig)) {
        foreach ($stConfig as $settingId => $settingValue) {
            $objSiteSettingsData = new SiteSettingsData();
            $objSiteSettingsData->setting_id = $settingId;
            $objSiteSettingsData->set_value = $settingValue;
            $objSiteSettingsMaster->editSiteSetting($objSiteSettingsData);
        }
        setFlashMessage(COMMON_UPDATE_SUCCESS, 'success');
        showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_SITE_SETTINGS_LISTING);
    }
}

$headingLabel = $pageTitle;

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>