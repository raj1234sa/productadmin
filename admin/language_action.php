<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'SiteLanguageMaster.php');

$siteLanguageMaster = new SiteLanguageMaster();

$listing_data = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

if($action == 'change_status') {
    $status_code = postValue('status_code');
    $status = postValue('status');
    $id = postValue('id');
    if(strpos($status_code, 'status') !== FALSE) {
        $siteLanguageData = new SiteLanguageData();
        $siteLanguageData->status = ($status == '1') ? '1' : '0';
        $siteLanguageData->language_id = $id;
        $siteLanguageMaster->editSiteLanguage($siteLanguageData);
        echo 'success';
    }
    exit;
}

if($is_ajax_request && ($listing_data || $export)) {
    extract(extract_search_fields(), EXTR_PREFIX_ALL, 'SC');

    $siteLanguageMaster->setLimit($SC_start, $SC_length);
    if(!empty($SC_column)) {
        $siteLanguageMaster->setOrderBy($SC_column.' '.$SC_dir);
    }

    if(!empty($SC_keyword)) {
        $siteLanguageMaster->setWhere("AND language_name LIKE :language_name", "%$SC_keyword%", 'string');
    }
    $siteLanguages = $siteLanguageMaster->getSiteLanguage();

    if($export) {
        $siteLanguages = objectToArray($siteLanguages);
        $export_structure = array();
        $export_structure[] = array('language_id'=>array('name'=>'language_id', 'title'=>COMMON_LANGUAGE_ID));
        $export_structure[] = array('language_name'=>array('name'=>'language_name', 'title'=>COMMON_LANGUAGE_NAME));
        $export_structure[] = array('status'=>array('name'=>'status', 'title'=>COMMON_STATUS));

        $sheetTitle = $page_title;
        $headerDate = "All";
        $spreadsheet = export_file_generate($export_structure, $siteLanguages);
        echo json_encode(export_report($spreadsheet, 'export_languages.xlsx'));
        exit;
    }

    $allRecs = array();
    $totalRecordCount = 0;
    if(!empty($siteLanguages)) {
        $totalRecordCount = $siteLanguages->FoundRows();
        $sr = $SC_start + 1;
        foreach ($siteLanguages as $lang) {
            $rec = array();
            $flag_name = (!empty($lang['language_flag'])) ? $lang['language_flag'] : '';
            if(!empty($flag_name)) {
                $http_path = DIR_HTTP_IMAGES_FLAGS.$flag_name;
                $src_path = DIR_WS_IMAGES_FLAGS.$flag_name;
            }
            $rec['DT_RowId'] = "language:".$lang['language_id'];
            $rec['language_id'] = $sr++;
            $rec['language_name'] = $lang['language_name'];
            if(!empty($http_path)) {
                $rec['language_name'] .= draw_imge($http_path, $src_path, array('width'=>30, 'class'=>'ml-2'));
            }
            $rec['status'] = form_switchbutton('status'.$lang['language_id'], $lang['status'], array('element_classes'=>'ajax change_status'));
            // $rec[] = form_switchbutton('status', $state['status'], array('class'=>'ajax change_status'));
            // $action_buttons = array();
            // $action_buttons[COMMON_EDIT] = array(
            //     'link' => DIR_HTTP_ADMIN.ADMIN_FILE_CITY_EDIT.'?city_id='.$state['city_id'],
            //     'icon' => 'far fa-edit',
            // );
            // $action_buttons[COMMON_DELETE] = array(
            //     'link' => DIR_HTTP_ADMIN.ADMIN_FILE_CITIES.'?action=delete&city_id='.$state['city_id'],
            //     'icon' => 'far fa-trash-alt',
            //     'class' => 'label-danger ajax delete',
            //     'compact_class' => 'btn-danger ajax delete',
            // );
            // $rec[] = draw_action_menu($action_buttons);
            $allRecs[] = $rec;
        }
    }
    $result = array(
        'data' => $allRecs,
        'recordsTotal' => $totalRecordCount,
        'recordsFiltered' => $totalRecordCount,
        'draw' => requestValue('draw'),
    );
    echo json_encode($result);
    exit;
}

$breadcrumb_arr = array(
    $breadcrumb_home,
    array(
        'title' => $page_title,
        // 'link' => DIR_HTTP_ADMIN.FILE_ADMIN_USER_LISTING
    ),
);

$action_buttons = array();
// $action_buttons[COMMON_ADD_LANGUAGE] = array(
//     'class' => 'btn btn-success',
//     'link' => DIR_HTTP_ADMIN.FILE_ADMIN_LANGUAGE_EDIT,
//     'icon' => 'fa fa-plus',
// );

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>