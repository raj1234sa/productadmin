<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'EmailConfigurationMaster.php');

$objEmailConfigurationMaster = new EmailConfigurationMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$temp_id = requestValue('template_id');

if(!empty($submit)) {
    var_dump(str_replace('&', '&amp;', $_REQUEST['template_content'])); exit;
    $firstname = postValue('firstname');
    $lastname = postValue('lastname');
    $email = postValue('email');
    $password = postValue('password');
    $phone = postValue('phone');
    $status = postValue('status');

    $err = array();
    $err['firstname'] = checkEmpty($firstname, COMMON_VALIDATE_REQUIRED);
    $err['lastname'] = checkEmpty($lastname, COMMON_VALIDATE_REQUIRED);
    $err['email'] = checkEmpty($email, COMMON_VALIDATE_REQUIRED);
    $err['password'] = checkEmpty($password, COMMON_VALIDATE_REQUIRED);
    $err['phone'] = checkEmpty($phone, COMMON_VALIDATE_REQUIRED);

    if(empty($err['email'])) {
        $err['email'] = checkEmailPattern($email, COMMON_INVALID_EMAIL);
    }
    if(empty($err['phone'])) {
        $err['phone'] = checkPhoneNumber($phone, COMMON_INVALID_PHONE);
    }
    $validation = checkValidation($err);

    if($validation) {
        $objEmailConfigurationData = new EmailConfigurationData();
        $objEmailConfigurationData->firstname = $firstname;
        $objEmailConfigurationData->lastname = $lastname;
        $objEmailConfigurationData->email = $email;
        $objEmailConfigurationData->password = $password;
        $objEmailConfigurationData->phone = $phone;
        $objEmailConfigurationData->status = !empty($status) ? '1' : '0';

        if($action == 'edit') {
            $objEmailConfigurationData->passenger_id = $pid;
            if($objEmailConfigurationMaster->editEmailConfiguration($objEmailConfigurationData)) {
                set_flash_message(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                set_flash_message(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $pid = $objEmailConfigurationMaster->addEmailConfiguration($objEmailConfigurationData);
            if($pid > 0) {
                set_flash_message(COMMON_INSERT_SUCCESS, 'success');
            } else {
                set_flash_message(COMMON_INSERT_ERROR, 'danger');
            }
        }
        if(!empty($pid)) {
            if($submit == COMMON_SAVE) {
                show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_EDIT.'?action=edit&passenger_id='.$pid);
            } elseif($submit == COMMON_SAVE_BACK) {
                show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING);
            }
        }
    }
}

if(!empty($temp_id)) {
    $email_config_data = $objEmailConfigurationMaster->getEmailConfiguration($temp_id);
    if(!empty($email_config_data)) {
        $email_config_data = $email_config_data[0];
    } else {
        set_flash_message(COMMON_RECORD_NOT_EXISTS, 'fail');
        show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING);
    }
}
$heading_label = $page_title;
$page_title = !empty($temp_id) ? EDIT_EMAIL_CONFIGURATION : ADD_EMAIL_CONFIGURATION;
$heading_label = $page_title;
if(!empty($temp_id)) {
    $heading_label .= "<small>".$email_config_data['template_subject']."</small>";
}

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'link' => DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING,
        'title' => COMMON_EMAIL_CONFIGURATION,
    ),
    array(
        'title' => $page_title,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>