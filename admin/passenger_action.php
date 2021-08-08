<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'PassengersMaster.php');
require_once(DIR_WS_MODEL.'BusStopsMaster.php');

$objPassengersMaster = new PassengersMaster();
$objBusStopsMaster = new BusStopsMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$pid = requestValue('passenger_id');

if($action == 'get_bus_stops') {
    $busStopsData = $objBusStopsMaster->getBusStops();
    if(!empty($busStopsData)) {
        $busStopsData = objectToArray($busStopsData);
        echo form_element('Bus Stop', 'select', 'bus_stop', '', '', array('list'=>$busStopsData, 'value_field'=>'stop_id', 'text_field'=>'stop_title'));
    } else {
        echo form_element('Bus Stop', 'select', 'bus_stop', '', '', array('list_before'=>'<select>Select Bus Stop</select>'));
    }
    exit;
}

// Add/Edit Passenger data
if(!empty($submit)) {
    $firstname = postValue('firstname');
    $lastname = postValue('lastname');
    $email = postValue('email');
    $password = postValue('password');
    $phone = postValue('phone');
    $status = postValue('status');

    // Validation start
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
    // Validation end

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

// Get passenger data
if(!empty($pid)) {
    $passengerData = $objPassengersMaster->getPassenger($pid);
    if(!empty($passengerData)) {
        $passengerData = $passengerData[0];
    } else {
        set_flash_message(COMMON_RECORD_NOT_EXISTS, 'fail');
        show_page_header(DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING);
    }
}

$objUtilMaster = new UtilMaster();
$getCountries = $objUtilMaster->exec_query('SELECT * FROM countries');
if($getCountries->RecordCount() <= 1) {
    $getCountries = $getCountries[0];
    $passengerData['country'] = $getCountries['country_id'];
    $passengerData['country_name'] = $getCountries['country_name'];
}

$getStates = $objUtilMaster->exec_query('SELECT * FROM states');
if($getStates->RecordCount() <= 1) {
    $getStates = $getStates[0];
    $passengerData['state'] = $getStates['state_id'];
    $passengerData['state_name'] = $getStates['state_name'];
}

$getCities = $objUtilMaster->exec_query('SELECT * FROM cities');
if($getCities->RecordCount() <= 1) {
    $getCities = $getCities[0];
    $passengerData['city'] = $getCities['city_id'];
    $passengerData['city_name'] = $getCities['city_name'];
}

$pageTitle = !empty($pid) ? EDIT_PASSENGER : ADD_PASSENGER;
$headingLabel = $pageTitle;
if(!empty($pid)) {
    $headingLabel .= "<small>".$passengerData['firstname'].' '.$passengerData['lastname']."</small>";
}

// Set breadcrub array
$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'link' => DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING,
        'title' => COMMON_PASSENGERS,
    ),
    array(
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>