<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'PassengersMaster.php');

$objPassengersMaster = new PassengersMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$pid = requestValue('passenger_id');

if(!empty($submit)) {
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
        $objPassengersData = new PassengersData();
        $objPassengersData->firstname = $firstname;
        $objPassengersData->lastname = $lastname;
        $objPassengersData->email = $email;
        $objPassengersData->password = $password;
        $objPassengersData->phone = $phone;
        $objPassengersData->status = !empty($status) ? '1' : '0';

        if($action == 'edit') {
            $objPassengersData->passenger_id = $pid;
            if($objPassengersMaster->editPassenger($objPassengersData)) {
                set_flash_message(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                set_flash_message(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $pid = $objPassengersMaster->addPassenger($objPassengersData);
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

if(!empty($pid)) {
    $passenger_data = $objPassengersMaster->getPassenger($pid);
    if(!empty($passenger_data)) {
        $passenger_data = $passenger_data[0];
    } else {
        set_flash_message(COMMON_RECORD_NOT_EXISTS, 'fail');
        show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING);
    }
}

$page_title = !empty($pid) ? EDIT_PASSENGER : ADD_PASSENGER;
$heading_label = $page_title;
if(!empty($pid)) {
    $heading_label .= "<small>".$passenger_data['firstname'].' '.$passenger_data['lastname']."</small>";
}

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'link' => DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING,
        'title' => COMMON_PASSENGERS,
    ),
    array(
        'title' => $page_title,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>