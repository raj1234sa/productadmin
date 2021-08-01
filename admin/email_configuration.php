<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'PassengersMaster.php');

$objPassengersMaster = new PassengersMaster();

$listing_data = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

// if($action == 'change_status') {
//     $status_code = postValue('status_code');
//     $status = postValue('status');
//     $id = postValue('id');
//     if($status_code == 'status') {
//         $objPassengersData = new PassengersData();
//         $objPassengersData->status = ($status == '1') ? '1' : '0';
//         $objPassengersData->passenger_id = $id;
//         $objPassengersMaster->editPassenger($objPassengersData);
//         echo 'success';
//     }
//     exit;
// }

// if($action == 'delete') {
//     $id = getValue('passenger_id');
//     if(!empty($id)) {
//         $objPassengersMaster->deletePassenger($id);
//         echo 'success';
//     } else {
//         echo COMMON_DELETE_ERROR;
//     }
//     exit;
// }

// if($is_ajax_request && ($listing_data || $export)) {
//     extract(extract_search_fields(), EXTR_PREFIX_ALL, 'SC');

//     if($SC_column == 'user_info') { $SC_column = 'firstname'; }
//     $objPassengersMaster->setLimit($SC_start, $SC_length);
//     $objPassengersMaster->setOrderBy($SC_column.' '.$SC_dir);

//     if($export) {
//         $objPassengersMaster->setSelect("IF(passengers_master.status = '1', 'Enabled', 'Disabled') as status");
//     }

//     if($SC_keyword != '') {
//         $objPassengersMaster->setWhere("AND (firstname LIKE :firstname", "%$SC_keyword%", 'string');
//         $objPassengersMaster->setWhere("OR email LIKE :email", "%$SC_keyword%", 'string');
//         $objPassengersMaster->setWhere("OR lastname LIKE :lastname)", "%$SC_keyword%", 'string');
//     }

//     $objPassengersMaster->setFoundRows();
//     $passengersDetails = $objPassengersMaster->getPassenger();

//     if($export) {
//         $passengersDetails = objectToArray($passengersDetails);
//         $export_structure = array();
//         $export_structure[] = array('passenger_id'=>array('name'=>'passenger_id', 'title'=>COMMON_ID));
//         $export_structure[] = array('state_name'=>array('name'=>'state_name', 'title'=>COMMON_STATE_NAME));
//         $export_structure[] = array('country_name'=>array('name'=>'country_name', 'title'=>COMMON_COUNTRY_NAME));
//         $export_structure[] = array('status'=>array('name'=>'status', 'title'=>COMMON_STATUS));

//         $sheetTitle = "State Report";
//         $headerDate = "All";
//         $spreadsheet = export_file_generate($export_structure, $passengersDetails);
//         echo json_encode(export_report($spreadsheet, 'export_state.xlsx'));
//         exit;
//     }

//     $passengers = array();
//     $totalRec = 0;
//     if(!empty($passengersDetails)) {
//         $totalRec = $passengersDetails->FoundRows();
//         $sr = $search['start'] + 1;
//         foreach ($passengersDetails as $passenger) {
//             $rec = array();
//             $rec['DT_RowId'] = "passenger:".$passenger['passenger_id'];
//             $rec['sr'] = $sr++;
//             $rec['user_info'] = getPassengerDetails($passenger);
//             $rec['status'] = form_switchbutton('status', $passenger['status'], array('element_classes'=>'ajax change_status', 'id'=>'status_'.$passenger['passenger_id']));
//             $action_buttons = array();
//             $action_buttons[COMMON_EDIT] = array(
//                 'link' => DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_EDIT.'?action=edit&passenger_id='.$passenger['passenger_id'],
//                 'icon' => 'fa fa-edit',
//                 'class' => 'btn-outline-secondary',
//             );
//             $action_buttons[COMMON_DELETE] = array(
//                 'link' => DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING.'?action=delete&passenger_id='.$passenger['passenger_id'],
//                 'icon' => 'fa fa-trash',
//                 'class' => 'bg-danger text-white ajax delete',
//             );
//             $rec['action'] = draw_action_menu($action_buttons);
//             $passengers[] = $rec;
//         }
//     }
//     $result = array(
//         'data' => $passengers,
//         'recordsTotal' => $totalRec,
//         'recordsFiltered' => $totalRec,
//         'draw' => requestValue('draw'),
//     );
//     echo json_encode($result);
//     exit;
// }
$heading_label = $page_title;

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'title' => $page_title,
    ),
);

$action_buttons = array();
$action_buttons[ADD_EMAIL_TEMPLATE] = array(
    'link' => DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_EDIT,
    'class' => 'btn-success',
    'icon' => 'fa fa-plus'
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>