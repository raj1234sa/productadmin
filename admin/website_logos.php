<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'WebsiteLogosMaster.php');

$objWebsiteLogosMaster = new WebsiteLogosMaster();

$submit = postValue('submit_btn');
if(!empty($submit)) {
    $site_logo = postValue('site_logo');
    $site_favicon = postValue('site_favicon');

    $response_success = array();
    if(!empty($site_logo)) {
        $wLogosData = getWebsiteLogos();
        $copySuccess = copy(DIR_WS_TEMP_IMAGES.$site_logo, DIR_WS_WEBSITE_LOGOS.$site_logo);
        $response_success['site_logo'] = true;

        $objWebsiteLogosData = new WebsiteLogosData();
        $objWebsiteLogosData->site_logo = $site_logo;
        if($copySuccess) {
            if(!empty($wLogosData)) {
                $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                unlink(DIR_WS_TEMP_IMAGES.$wLogosData['site_logo']);
                $response_success['site_logo'] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
            } else {
                $response_success['site_logo'] = ($objWebsiteLogosMaster->addWebsiteLogos($objWebsiteLogosData)) ? true : false;
            }
        }
    }
    if(!empty($site_favicon)) {
        $wLogosData = getWebsiteLogos();
        $copySuccess = copy(DIR_WS_TEMP_IMAGES.$site_favicon, DIR_WS_WEBSITE_LOGOS.$site_favicon);
        $response_success['site_favicon'] = true;

        $objWebsiteLogosData = new WebsiteLogosData();
        $objWebsiteLogosData->site_favicon = $site_favicon;
        if($copySuccess) {
            if(!empty($wLogosData)) {
                $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                unlink(DIR_WS_TEMP_IMAGES.$wLogosData['site_favicon']);
                $response_success['site_favicon'] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
            } else {
                $response_success['site_favicon'] = ($objWebsiteLogosMaster->addWebsiteLogos($objWebsiteLogosData)) ? true : false;
            }
        }
    }
    
    if(empty($site_logo) || empty($site_favicon)) {
        $wLogosData = getWebsiteLogos();
        if(!empty($wLogosData)) {
            $objWebsiteLogosData = new WebsiteLogosData();

            if(empty($site_logo) && file_exists(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_logo']) && is_file(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_logo'])) {
                $deleteSuccess1 = unlink(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_logo']);
                $response_success['site_logo_delete'] = $deleteSuccess1;
                if($deleteSuccess1) {
                    $objWebsiteLogosData->site_logo = '';
                }
            }
            if(empty($site_favicon) && file_exists(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_favicon']) && is_file(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_favicon'])) {
                $deleteSuccess2 = unlink(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_favicon']);
                $response_success['site_favicon_delete'] = $deleteSuccess2;
                if($deleteSuccess2) {
                    $objWebsiteLogosData->site_favicon = '';
                }
            }

            if($deleteSuccess1 && $deleteSuccess2) {
                $objWebsiteLogosData->logo_id = $wLogosData['logo_id'];
                $response_success['site_favicon_delete_db'] = ($objWebsiteLogosMaster->editWebsiteLogos($objWebsiteLogosData)) ? true : false;
            }
        }
    }
    if(!empty($response_success)) {
        if(!in_array(false, $response_success)) {
            set_flash_message(COMMON_UPDATE_SUCCESS, 'success');
        } else {
            set_flash_message(COMMON_UPDATE_ERROR, 'danger');
        }
        unlink(DIR_WS_TEMP_IMAGES.$site_logo);
        unlink(DIR_WS_TEMP_IMAGES.$site_favicon);
    }
    show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_WEBSITE_LOGOS);
}

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'title' => $page_title
    ),
);

$wLogosData = getWebsiteLogos();

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>