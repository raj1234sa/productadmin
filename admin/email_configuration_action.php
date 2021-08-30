<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'EmailConfigurationMaster.php');

$objEmailConfigurationMaster = new EmailConfigurationMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$tempId = requestValue('template_id');

if(!empty($submit)) {
    $templateSubject = postValue('template_subject');
    $templateContent = htmlentities(postValue('template_content'));
    $status = postValue('status');

    $err = array();
    $err['template_subject'] = checkEmpty($templateSubject, COMMON_VALIDATE_REQUIRED);
    $err['template_content'] = checkEmpty($templateContent, COMMON_VALIDATE_REQUIRED);

    $validation = checkValidation($err);

    if($validation) {
        $objEmailConfigurationData = new EmailConfigurationData();
        $objEmailConfigurationData->status = !empty($status) ? '1' : '0';

        if($action == 'edit') {
            $objEmailConfigurationData->email_template_id = $tempId;
            $ediSsuccess = $objEmailConfigurationMaster->editEmailConfiguration($objEmailConfigurationData);
            $objEmailConfigurationData->email_template_id = $tempId;
            $objEmailConfigurationData->template_subject = $templateSubject;
            $objEmailConfigurationData->template_content = $templateContent;
            $objEmailConfigurationData->language_id = '1';
            $editSuccess1 = $objEmailConfigurationMaster->editEmailConfigurationDesc($objEmailConfigurationData);
            if($editSuccess && $editSuccess1) {
                setFlashMessage(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                setFlashMessage(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $tempId = $objEmailConfigurationMaster->addEmailConfiguration($objEmailConfigurationData);
            $objEmailConfigurationData->email_template_id = $tempId;
            $objEmailConfigurationData->template_subject = $templateSubject;
            $objEmailConfigurationData->template_content = $templateContent;
            $objEmailConfigurationData->language_id = '1';
            $added = $objEmailConfigurationMaster->addEmailConfigurationDesc($objEmailConfigurationData);
            if($tempId > 0 && $added > 0) {
                setFlashMessage(COMMON_INSERT_SUCCESS, 'success');
            } else {
                setFlashMessage(COMMON_INSERT_ERROR, 'danger');
            }
        }
        if(!empty($tempId)) {
            if($submit == COMMON_SAVE) {
                showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_EDIT.'?action=edit&template_id='.$tempId);
            } elseif($submit == COMMON_SAVE_BACK) {
                showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING);
            }
        }
    }
}

if(!empty($tempId)) {
    $emailConfigData = $objEmailConfigurationMaster->getEmailConfiguration($tempId, 'yes');
    if(!empty($emailConfigData)) {
        $emailConfigData = $emailConfigData[0];
    } else {
        setFlashMessage(COMMON_RECORD_NOT_EXISTS, 'fail');
        showPageHeader(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING);
    }
}
$headingLabel = $pageTitle;
$pageTitle = !empty($tempId) ? EDIT_EMAIL_CONFIGURATION : ADD_EMAIL_CONFIGURATION;
$headingLabel = $pageTitle;
if(!empty($tempId)) {
    $headingLabel .= "<small>".$emailConfigData['template_subject']."</small>";
}

$objUtilMaster = new UtilMaster();
$objUtilMaster->setFrom('email_variables');
$objUtilMaster->setWhere("AND email_template_id IN @email_template_id", array(0, $emailConfigData['email_template_id']), 'int');
$emailVariablesData = $objUtilMaster->exec_query();

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'link' => DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING,
        'title' => COMMON_EMAIL_CONFIGURATION,
    ),
    array(
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>