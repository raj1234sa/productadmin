<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'EmailConfigurationMaster.php');

$objEmailConfigurationMaster = new EmailConfigurationMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$temp_id = requestValue('template_id');

if(!empty($submit)) {
    $template_subject = postValue('template_subject');
    $template_content = htmlentities(postValue('template_content'));
    $status = postValue('status');

    $err = array();
    $err['template_subject'] = checkEmpty($template_subject, COMMON_VALIDATE_REQUIRED);
    $err['template_content'] = checkEmpty($template_content, COMMON_VALIDATE_REQUIRED);

    $validation = checkValidation($err);

    if($validation) {
        $objEmailConfigurationData = new EmailConfigurationData();
        $objEmailConfigurationData->status = !empty($status) ? '1' : '0';

        if($action == 'edit') {
            $objEmailConfigurationData->email_template_id = $temp_id;
            $edit_success = $objEmailConfigurationMaster->editEmailConfiguration($objEmailConfigurationData);
            $objEmailConfigurationData->email_template_id = $temp_id;
            $objEmailConfigurationData->template_subject = $template_subject;
            $objEmailConfigurationData->template_content = $template_content;
            $objEmailConfigurationData->language_id = '1';
            $edit_success1 = $objEmailConfigurationMaster->editEmailConfigurationDesc($objEmailConfigurationData);
            if($edit_success && $edit_success1) {
                set_flash_message(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                set_flash_message(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $temp_id = $objEmailConfigurationMaster->addEmailConfiguration($objEmailConfigurationData);
            $objEmailConfigurationData->email_template_id = $temp_id;
            $objEmailConfigurationData->template_subject = $template_subject;
            $objEmailConfigurationData->template_content = $template_content;
            $objEmailConfigurationData->language_id = '1';
            $added = $objEmailConfigurationMaster->addEmailConfigurationDesc($objEmailConfigurationData);
            if($temp_id > 0 && $added > 0) {
                set_flash_message(COMMON_INSERT_SUCCESS, 'success');
            } else {
                set_flash_message(COMMON_INSERT_ERROR, 'danger');
            }
        }
        if(!empty($temp_id)) {
            if($submit == COMMON_SAVE) {
                show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_EDIT.'?action=edit&template_id='.$temp_id);
            } elseif($submit == COMMON_SAVE_BACK) {
                show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING);
            }
        }
    }
}

if(!empty($temp_id)) {
    $email_config_data = $objEmailConfigurationMaster->getEmailConfiguration($temp_id, 'yes');
    if(!empty($email_config_data)) {
        $email_config_data = $email_config_data[0];
    } else {
        set_flash_message(COMMON_RECORD_NOT_EXISTS, 'fail');
        show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING);
    }
}
$headingLabel = $pageTitle;
$pageTitle = !empty($temp_id) ? EDIT_EMAIL_CONFIGURATION : ADD_EMAIL_CONFIGURATION;
$headingLabel = $pageTitle;
if(!empty($temp_id)) {
    $headingLabel .= "<small>".$email_config_data['template_subject']."</small>";
}

$objUtilMaster = new UtilMaster();
$objUtilMaster->setFrom('email_variables');
$objUtilMaster->setWhere("AND email_template_id IN @email_template_id", array(0, $email_config_data['email_template_id']), 'int');
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