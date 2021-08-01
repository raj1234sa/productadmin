<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'WebsiteLogosMaster.php');

$objWebsiteLogosMaster = new WebsiteLogosMaster();

$submit = postValue('submit_btn');
if(!empty($submit)) {
    $logo_arr = postValue('logo');

    $response_success = array();
    foreach ($logo_arr as $key => $value) {
        $wLogosData = getWebsiteLogos();
        if(!empty($value)) {
            $copySuccess = copy(DIR_WS_TEMP_IMAGES.$value, DIR_WS_WEBSITE_LOGOS.$value);
            $response_success[$key] = true;
    
            $objWebsiteLogosData = new WebsiteLogosData();
            $objWebsiteLogosData->$key = $value;
            if($copySuccess) {
                if(!empty($wLogosData)) {
                    $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                    unlink(DIR_WS_TEMP_IMAGES.$wLogosData[$key]);
                    $response_success[$key] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
                } else {
                    $response_success[$key] = ($objWebsiteLogosMaster->addWebsiteLogos($objWebsiteLogosData)) ? true : false;
                }
            }
        } elseif(!empty($wLogosData)) {
            $objWebsiteLogosData = new WebsiteLogosData();

            if(empty($value)) {
                $objWebsiteLogosData->$key = '';
                $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                $response_success[$key.'_delete_db'] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
            }
        }
    }
    if(!empty($response_success)) {
        if(!in_array(false, $response_success)) {
            set_flash_message(COMMON_UPDATE_SUCCESS, 'success');
        } else {
            set_flash_message(COMMON_UPDATE_ERROR, 'danger');
        }
    }
    show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_WEBSITE_LOGOS);
}
$heading_label = $page_title;

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'title' => $page_title
    ),
);

$wLogosData = getWebsiteLogos();

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>