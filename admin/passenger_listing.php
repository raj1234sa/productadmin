<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL . 'PassengersMaster.php');

$objPassengersMaster = new PassengersMaster();

$listingData = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

if ($action == 'change_status') {
    $statusCode = postValue('status_code');
    $status = postValue('status');
    $id = postValue('id');
    if ($statusCode == 'status') {
        $objPassengersData = new PassengersData();
        $objPassengersData->status = ($status == '1') ? '1' : '0';
        $objPassengersData->passenger_id = $id;
        $objPassengersMaster->editPassenger($objPassengersData);
        echo 'success';
    }
    exit;
}

if ($action == 'delete') {
    $id = getValue('passenger_id');
    if (!empty($id)) {
        $objPassengersMaster->deletePassenger($id);
        echo 'success';
    } else {
        echo COMMON_DELETE_ERROR;
    }
    exit;
}

if($action == 'show_password') {
    $pid = requestValue('pid');
    $respArr = array();
    if(!empty($pid)) {
        $passengersDataPassword = $objPassengersMaster->getPassenger($pid);
        if(!empty($passengersDataPassword)) {
            $passengersDataPassword = $passengersDataPassword[0];
            $respArr['email'] = $passengersDataPassword['email'];
            $enc = new Encryption();
            $respArr['password'] = $enc->decrypt($passengersDataPassword['password']);
        }
    }
    echo json_encode($respArr);
    exit;
}

if ($isAjaxRequest && ($listingData || $export)) {
    extract(extractSearchFields(), EXTR_PREFIX_ALL, 'SC');

    $fieldArr = array(
        "passengers_master.*",
        "countries.country_name",
        "states.state_name",
        "cities.city_name",
        "bus_stops_master.stop_title",
        "bus_stops_master.stop_internal_name"
    );
    $objPassengersMaster->setSelect($fieldArr);

    if ($SC_column == 'user_info') {
        $SC_column = 'firstname, lastname';
    }
    if ($SC_column == 'address_info') {
        $SC_column = 'address_line';
    }
    $objPassengersMaster->setLimit($SC_start, $SC_length);
    $objPassengersMaster->setOrderBy($SC_column . ' ' . $SC_dir);

    if ($export) {
        $objPassengersMaster->setSelect("IF(passengers_master.status = '1', '".COMMON_ENABLED."', '".COMMON_DISABLED."') as status");
    }

    if ($SC_keyword != '') {
        $objPassengersMaster->setWhere("AND (firstname LIKE :firstname", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR email LIKE :email", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR phone LIKE :phone", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR passengers_master.address_line LIKE :address_line", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR passengers_master.address_line2 LIKE :address_line2", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR passengers_master.area_name LIKE :area_name", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR stop_title LIKE :stop_title", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR stop_internal_name LIKE :stop_internal_name", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR city_name LIKE :city_name", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR country_name LIKE :country_name", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR state_name LIKE :state_name", "%$SC_keyword%", 'string');
        $objPassengersMaster->setWhere("OR lastname LIKE :lastname)", "%$SC_keyword%", 'string');
    }

    $objPassengersMaster->setJoin("LEFT JOIN countries ON countries.country_id = passengers_master.country");
    $objPassengersMaster->setJoin("LEFT JOIN states ON states.state_id = passengers_master.state");
    $objPassengersMaster->setJoin("LEFT JOIN cities ON cities.city_id = passengers_master.city");
    $objPassengersMaster->setJoin("LEFT JOIN bus_stops_master ON bus_stops_master.stop_id = passengers_master.bus_stop_id");

    $objPassengersMaster->setFoundRows();
    $passengersDetails = $objPassengersMaster->getPassenger();

    if ($export) {
        $passengersDetails = objectToArray($passengersDetails);
        $exportStructure = array();
        $exportStructure[] = array('passenger_id' => array('name' => 'passenger_id', 'title' => COMMON_ID));
        $exportStructure[] = array('state_name' => array('name' => 'state_name', 'title' => COMMON_STATE_NAME));
        $exportStructure[] = array('country_name' => array('name' => 'country_name', 'title' => COMMON_COUNTRY_NAME));
        $exportStructure[] = array('status' => array('name' => 'status', 'title' => COMMON_STATUS));

        $sheetTitle = "State Report";
        $headerDate = "All";
        $spreadsheet = exportFileGenerate($exportStructure, $passengersDetails);
        echo json_encode(exportReport($spreadsheet, 'export_state.xlsx'));
        exit;
    }

    $passengers = array();
    $totalRec = 0;
    if (!empty($passengersDetails)) {
        $totalRec = $passengersDetails->FoundRows();
        $sr = $SC_start + 1;
        foreach ($passengersDetails as $passenger) {
            $rec = array();
            $rec['DT_RowId'] = "passenger:" . $passenger['passenger_id'];
            $rec['sr'] = $sr++;
            $rec['user_info'] = getPassengerDetails($passenger);
            $rec['address_info'] = getAddressDetails($passenger);
            $rec['status'] = formSwitchbutton('status', $passenger['status'], array('element_class' => 'ajax change_status', 'id' => 'status_' . $passenger['passenger_id']));
            $actionButtons = array();
            $actionButtons[COMMON_EDIT] = array(
                'link' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_EDIT . '?action=edit&passenger_id=' . $passenger['passenger_id'],
                'icon' => 'fa fa-edit',
                'class' => 'btn-outline-secondary',
            );
            if($SITE_VAR_PASSENGER_SHOW_PASSWORD) {
                $actionButtons[PASSENGER_SHOW_PASSWORD] = array(
                    'link' => 'javascript:void(0)',
                    'icon' => 'ti-key',
                    'class' => 'btn-outline-secondary show_password_btn',
                    'extra' => "data-pass_id='{$passenger['passenger_id']}'",
                );
            }
            $actionButtons[COMMON_DELETE] = array(
                'link' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING . '?action=delete&passenger_id=' . $passenger['passenger_id'],
                'icon' => 'fa fa-trash',
                'class' => 'bg-danger text-white ajax delete',
            );
            $rec['action'] = drawActionMenu($actionButtons);
            $passengers[] = $rec;
        }
    }
    $result = array(
        'data' => $passengers,
        'recordsTotal' => $totalRec,
        'recordsFiltered' => $totalRec,
        'draw' => requestValue('draw'),
    );
    echo json_encode($result);
    exit;
}
$headingLabel = $pageTitle;

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'title' => COMMON_PASSENGERS,
    ),
);

$actionButtons = array();
$actionButtons[ADD_PASSENGER] = array(
    'link' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_EDIT,
    'class' => 'btn-success',
    'icon' => 'fa fa-plus'
);
$actionButtons[IMPORT_PASSENGER] = array(
    'link' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_IMPORT,
    'class' => 'btn-secondary',
    'icon' => 'fa fa-upload'
);

require_once(DIR_WS_ADMIN_INCLUDES . FILE_MAIN_INTERFACE);
