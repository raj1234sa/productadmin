<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'SiteLanguageMaster.php');

$siteLanguageMaster = new SiteLanguageMaster();

$listingData = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

if($action == 'change_status') {
    $statusCode = postValue('status_code');
    $status = postValue('status');
    $id = postValue('id');
    if(strpos($statusCode, 'status') !== FALSE) {
        $siteLanguageData = new SiteLanguageData();
        $siteLanguageData->status = ($status == '1') ? '1' : '0';
        $siteLanguageData->language_id = $id;
        $siteLanguageMaster->editSiteLanguage($siteLanguageData);
        echo 'success';
    }
    exit;
}

if($isAjaxRequest && ($listingData || $export)) {
    extract(extractSearchFields(), EXTR_PREFIX_ALL, 'SC');

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
        $exportStructure = array();
        $exportStructure[] = array('language_id'=>array('name'=>'language_id', 'title'=>COMMON_LANGUAGE_ID));
        $exportStructure[] = array('language_name'=>array('name'=>'language_name', 'title'=>COMMON_LANGUAGE_NAME));
        $exportStructure[] = array('status'=>array('name'=>'status', 'title'=>COMMON_STATUS));

        $sheetTitle = $pageTitle;
        $headerDate = "All";
        $spreadsheet = exportFileGenerate($exportStructure, $siteLanguages);
        echo json_encode(exportReport($spreadsheet));
        exit;
    }

    $allRecs = array();
    $totalRecordCount = 0;
    if(!empty($siteLanguages)) {
        $totalRecordCount = $siteLanguages->FoundRows();
        $sr = $SC_start + 1;
        foreach ($siteLanguages as $lang) {
            $rec = array();
            $flagName = (!empty($lang['language_flag'])) ? $lang['language_flag'] : '';
            if(!empty($flagName)) {
                $httpPath = DIR_HTTP_IMAGES_FLAGS.$flagName;
                $srcPath = DIR_WS_IMAGES_FLAGS.$flagName;
            }
            $rec['DT_RowId'] = "language:".$lang['language_id'];
            $rec['language_id'] = $sr++;
            $rec['language_name'] = $lang['language_name'];
            if(!empty($httpPath)) {
                $rec['language_name'] .= drawImge($httpPath, $srcPath, array('width'=>30, 'class'=>'ml-2'));
            }
            $rec['status'] = formSwitchbutton('status'.$lang['language_id'], $lang['status'], array('element_class'=>'ajax change_status'));
            // $rec[] = formSwitchbutton('status', $state['status'], array('class'=>'ajax change_status'));
            // $actionButtons = array();
            // $actionButtons[COMMON_EDIT] = array(
            //     'link' => DIR_HTTP_ADMIN.ADMIN_FILE_CITY_EDIT.'?city_id='.$state['city_id'],
            //     'icon' => 'far fa-edit',
            // );
            // $actionButtons[COMMON_DELETE] = array(
            //     'link' => DIR_HTTP_ADMIN.ADMIN_FILE_CITIES.'?action=delete&city_id='.$state['city_id'],
            //     'icon' => 'far fa-trash-alt',
            //     'class' => 'label-danger ajax delete',
            //     'compact_class' => 'btn-danger ajax delete',
            // );
            // $rec[] = drawActionMenu($actionButtons);
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

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'title' => $pageTitle,
        // 'link' => DIR_HTTP_ADMIN.FILE_ADMIN_USER_LISTING
    ),
);

$actionButtons = array();
// $actionButtons[COMMON_ADD_LANGUAGE] = array(
//     'class' => 'btn btn-success',
//     'link' => DIR_HTTP_ADMIN.FILE_ADMIN_LANGUAGE_EDIT,
//     'icon' => 'fa fa-plus',
// );

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>