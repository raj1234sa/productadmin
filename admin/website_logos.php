<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'WebsiteLogosMaster.php');

$objWebsiteLogosMaster = new WebsiteLogosMaster();

$submit = postValue('submit_btn');
if(!empty($submit)) {
    $logoArr = postValue('logo');

    $responseSuccess = array();
    foreach ($logoArr as $key => $value) {
        $wLogosData = getWebsiteLogos();
        if(!empty($value)) {
            $copySuccess = copy(DIR_WS_TEMP_IMAGES.$value, DIR_WS_WEBSITE_LOGOS.$value);
            $responseSuccess[$key] = true;
    
            $objWebsiteLogosData = new WebsiteLogosData();
            $objWebsiteLogosData->$key = $value;
            if($copySuccess) {
                if(!empty($wLogosData)) {
                    $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                    unlink(DIR_WS_TEMP_IMAGES.$wLogosData[$key]);
                    $responseSuccess[$key] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
                } else {
                    $responseSuccess[$key] = ($objWebsiteLogosMaster->addWebsiteLogos($objWebsiteLogosData)) ? true : false;
                }
            }
        } elseif(!empty($wLogosData)) {
            $objWebsiteLogosData = new WebsiteLogosData();

            if(empty($value)) {
                $objWebsiteLogosData->$key = '';
                $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                $responseSuccess[$key.'_delete_db'] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
            }
        }
    }
    if(!empty($responseSuccess)) {
        if(!in_array(false, $responseSuccess)) {
            setFlashMessage(COMMON_UPDATE_SUCCESS, 'success');
        } else {
            setFlashMessage(COMMON_UPDATE_ERROR, 'danger');
        }
    }
    showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_WEBSITE_LOGOS);
}
$headingLabel = $pageTitle;

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'title' => $pageTitle
    ),
);

$wLogosData = getWebsiteLogos();

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>