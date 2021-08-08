<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'EmailConfigurationMaster.php');

$objEmailConfigurationMaster = new EmailConfigurationMaster();

$listing_data = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

if($action == 'change_status') {
    $status_code = postValue('status_code');
    $status = postValue('status');
    $id = postValue('id');
    if($status_code == 'status') {
        $objEmailConfigurationData = new EmailConfigurationData();
        $objEmailConfigurationData->status = ($status == '1') ? '1' : '0';
        $objEmailConfigurationData->email_template_id = $id;
        $objEmailConfigurationMaster->editEmailConfiguration($objEmailConfigurationData);
        echo 'success';
    }
    exit;
}

if($isAjaxRequest && ($listing_data || $export)) {
    extract(extract_search_fields(), EXTR_PREFIX_ALL, 'SC');

    if($SC_column == 'template_constant') { $SC_column = 'constant_name'; }
    $objEmailConfigurationMaster->setLimit($SC_start, $SC_length);
    $objEmailConfigurationMaster->setOrderBy($SC_column.' '.$SC_dir);

    if($export) {
        $objEmailConfigurationMaster->setSelect("IF(email_configuration.status = '1', 'Enabled', 'Disabled') as status");
    }

    if($SC_keyword != '') {
        $objEmailConfigurationMaster->setWhere("AND (firstname LIKE :firstname", "%$SC_keyword%", 'string');
        $objEmailConfigurationMaster->setWhere("OR email LIKE :email", "%$SC_keyword%", 'string');
        $objEmailConfigurationMaster->setWhere("OR lastname LIKE :lastname)", "%$SC_keyword%", 'string');
    }

    $objEmailConfigurationMaster->setFoundRows();
    $emailConfigDetails = $objEmailConfigurationMaster->getEmailConfiguration(null, 'yes');

    if($export) {
        $emailConfigDetails = objectToArray($emailConfigDetails);
        $export_structure = array();
        $export_structure[] = array('email_template_id'=>array('name'=>'email_template_id', 'title'=>COMMON_ID));
        $export_structure[] = array('state_name'=>array('name'=>'state_name', 'title'=>COMMON_STATE_NAME));
        $export_structure[] = array('country_name'=>array('name'=>'country_name', 'title'=>COMMON_COUNTRY_NAME));
        $export_structure[] = array('status'=>array('name'=>'status', 'title'=>COMMON_STATUS));

        $sheetTitle = "State Report";
        $headerDate = "All";
        $spreadsheet = export_file_generate($export_structure, $emailConfigDetails);
        echo json_encode(export_report($spreadsheet, 'export_state.xlsx'));
        exit;
    }

    $emailTemplates = array();
    $totalRec = 0;
    if(!empty($emailConfigDetails)) {
        $totalRec = $emailConfigDetails->FoundRows();
        $sr = $SC_start + 1;
        foreach ($emailConfigDetails as $email_config) {
            $rec = array();
            $rec['DT_RowId'] = "emailtempid:".$email_config['email_template_id'];
            $rec['sr'] = $sr++;
            $rec['template_constant'] = getEmailSubjectDetails($email_config);
            $rec['status'] = form_switchbutton('status', $email_config['status'], array('element_classes'=>'ajax change_status', 'id'=>'status_'.$email_config['email_template_id']));
            $action_buttons = array();
            $action_buttons[COMMON_EDIT] = array(
                'link' => DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_EDIT.'?action=edit&template_id='.$email_config['email_template_id'],
                'icon' => 'fa fa-edit',
                'class' => 'btn-outline-secondary',
            );
            $rec['action'] = draw_action_menu($action_buttons);
            $emailTemplates[] = $rec;
        }
    }
    $result = array(
        'data' => $emailTemplates,
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
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>