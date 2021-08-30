<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL . 'PassengersMaster.php');
require_once(DIR_WS_MODEL . 'BusStopsMaster.php');

$objPassengersMaster = new PassengersMaster();
$objBusStopsMaster = new BusStopsMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$pid = requestValue('passenger_id');
$enc = new Encryption();

if ($action == 'get_bus_stops') {
    $selected = requestValue('selected');
    $busStopsData = $objBusStopsMaster->getBusStops();
    $labelColClass = 3;
    $validation = array('required' => COMMON_VALIDATE_REQUIRED);
    if (!empty($busStopsData)) {
        $busStopsData = objectToArray($busStopsData);
        echo formElement(COMMON_BUS_STOP, 'select', 'bus_stop', '', $selected, array('list'=>$busStopsData, 'value_field'=>'stop_id', 'text_field'=>'stop_title', 'element_class'=>'selectpicker', 'validation'=>$validation));
    } else {
        echo formElement(COMMON_BUS_STOP, 'select', 'bus_stop', '', '', array('list_before' => '<option>' . SELECT_BUS_STOP . '</option>', 'element_class'=>'selectpicker', 'validation'=>$validation));
    }
    exit;
}

// Add/Edit Passenger data
if (!empty($submit)) {
    $firstname = postValue('firstname');
    $lastname = postValue('lastname');
    $email = postValue('email');
    $password = postValue('password');
    $phone = postValue('phone');
    $addressLine = postValue('address_line');
    $addressLine2 = postValue('address_line2');
    $areaName = postValue('area_name');
    $zipcode = postValue('zipcode');
    $busStop = postValue('bus_stop');
    $city = postValue('city');
    $state = postValue('state');
    $country = postValue('country');
    $status = postValue('status');
    $newPassword = postValue('new_password');

    // Validation start
    $err = array();
    $err['firstname'] = checkEmpty($firstname, COMMON_VALIDATE_REQUIRED);
    $err['lastname'] = checkEmpty($lastname, COMMON_VALIDATE_REQUIRED);
    $err['email'] = checkEmpty($email, COMMON_VALIDATE_REQUIRED);
    if (($newPassword == 'new' && !empty($pid)) || empty($pid)) {
        $err['password'] = checkEmpty($password, COMMON_VALIDATE_REQUIRED);
    }
    $err['address_line'] = checkEmpty($addressLine, COMMON_VALIDATE_REQUIRED);
    $err['area_name'] = checkEmpty($areaName, COMMON_VALIDATE_REQUIRED);
    $err['zipcode'] = checkEmpty($zipcode, COMMON_VALIDATE_REQUIRED);
    $err['city'] = checkEmpty($city, COMMON_VALIDATE_REQUIRED);
    $err['state'] = checkEmpty($state, COMMON_VALIDATE_REQUIRED);
    $err['country'] = checkEmpty($country, COMMON_VALIDATE_REQUIRED);

    if (empty($err['email'])) {
        $err['email'] = checkEmailPattern($email, COMMON_INVALID_EMAIL);
    }
    if (empty($err['phone'])) {
        $err['phone'] = checkPhoneNumber($phone, COMMON_INVALID_PHONE);
    }
    if (empty($err['zipcode'])) {
        $err['zipcode'] = checkNumeric($zipcode, VALIDATE_ONLY_DIGITS);
    }

    if ($action == 'edit') {
        $objPassengersMaster->setWhere("AND passenger_id != :passenger_id", $pid, 'int');
    }
    $objPassengersMaster->setWhere("AND email = :email", $email, 'string');
    $passengerDataValidation = $objPassengersMaster->getPassenger();
    if (!empty($passengerDataValidation)) {
        if (empty($err['email'])) {
            $err['email'] = checkNumeric($email, EMAIL_ALREADY_EXISTS);
        }
    }
    $validation = checkValidation($err);
    // Validation end

    if ($validation) {
        $objPassengersData = new PassengersData();
        $objPassengersData->firstname = $firstname;
        $objPassengersData->lastname = $lastname;
        $objPassengersData->email = $email;
        if (($newPassword == 'new' && !empty($pid)) || empty($pid)) {
            $objPassengersData->password = $enc->encrypt($password);
        }
        $objPassengersData->phone = $phone;
        $objPassengersData->address_line = $addressLine;
        $objPassengersData->address_line2 = $addressLine2;
        $objPassengersData->area_name = $areaName;
        $objPassengersData->zipcode = $zipcode;
        $objPassengersData->bus_stop_id = $busStop;
        $objPassengersData->city = $city;
        $objPassengersData->state = $state;
        $objPassengersData->country = $country;
        $objPassengersData->status = !empty($status) ? '1' : '0';

        if ($action == 'edit') {
            $objPassengersData->passenger_id = $pid;
            if ($objPassengersMaster->editPassenger($objPassengersData)) {
                setFlashMessage(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                setFlashMessage(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $pid = $objPassengersMaster->addPassenger($objPassengersData);
            if ($pid > 0) {
                setFlashMessage(COMMON_INSERT_SUCCESS, 'success');
                sendPassengerRegisteredAdmin($pid);
            } else {
                setFlashMessage(COMMON_INSERT_ERROR, 'danger');
            }
        }
        if (!empty($pid)) {
            if ($submit == COMMON_SAVE) {
                showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_EDIT . '?action=edit&passenger_id=' . $pid);
            } elseif ($submit == COMMON_SAVE_BACK) {
                showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING);
            }
        }
    }
}

// Get passenger data
if (!empty($pid)) {
    $formData = $objPassengersMaster->getPassenger($pid);
    if (!empty($formData)) {
        $formData = $formData[0];
    } else {
        setFlashMessage(COMMON_RECORD_NOT_EXISTS, 'fail');
        showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING);
    }
}

$objUtilMaster = new UtilMaster();
$getCountries = $objUtilMaster->exec_query('SELECT * FROM countries');
if ($getCountries->RecordCount() <= 1) {
    $getCountries = $getCountries[0];
    $formData['country'] = $getCountries['country_id'];
    $formData['country_name'] = $getCountries['country_name'];
}

$getStates = $objUtilMaster->exec_query('SELECT * FROM states');
if ($getStates->RecordCount() <= 1) {
    $getStates = $getStates[0];
    $formData['state'] = $getStates['state_id'];
    $formData['state_name'] = $getStates['state_name'];
}

$getCities = $objUtilMaster->exec_query('SELECT * FROM cities');
if ($getCities->RecordCount() <= 1) {
    $getCities = $getCities[0];
    $formData['city'] = $getCities['city_id'];
    $formData['city_name'] = $getCities['city_name'];
}
$formData = syncPostData($formData);

$pageTitle = !empty($pid) ? EDIT_PASSENGER : ADD_PASSENGER;
$headingLabel = $pageTitle;
if (!empty($pid)) {
    $headingLabel .= "<small>" . $formData['firstname'] . ' ' . $formData['lastname'] . "</small>";
}

// Set breadcrub array
$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'link' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING,
        'title' => COMMON_PASSENGERS,
    ),
    array(
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES . FILE_MAIN_INTERFACE);
