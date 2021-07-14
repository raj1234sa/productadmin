<?php
require_once('../lib/common.php');
require_once('../localconfig/localconfig.php');
require_once('../model/AdminConstantsMaster.php');
require_once('../model/AdminMenuMaster.php');
require_once('../model/AdminSectionMaster.php');
require_once('../model/AdminPagesMaster.php');
require_once('../admin/includes/functions.php');
manage_admin_constants(array());

function manage_admin_constants($constant_arr=array()) {
    $constant_arr[] = array(
        'name' => 'COMMON_USERNAME',
        'section' => 'common',
        'value' =>  array(
            1 => 'Username',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_PASSWORD',
        'section' => 'common',
        'value' =>  array(
            1 => 'Password',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_SIGNIN',
        'section' => 'common',
        'value' =>  array(
            1 => 'SIGN IN',
        ),
    );
    $constant_arr[] = array(
        'name' => 'LOGIN_FORGOT_PASSWORD',
        'section' => 'common',
        'value' =>  array(
            1 => 'Forgot Password?',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_SUBMIT',
        'section' => 'common',
        'value' =>  array(
            1 => 'Submit',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_DASHBOARD',
        'section' => 'common',
        'value' =>  array(
            1 => 'Dashboard',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_CUSTOMERS',
        'section' => 'Customers',
        'value' =>  array(
            1 => 'Customers',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_SEARCH',
        'section' => 'common',
        'value' =>  array(
            1 => 'Search',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_LANGUAGE_ID',
        'section' => 'common',
        'value' =>  array(
            1 => 'Language Id',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_LANGUAGE_NAME',
        'section' => 'common',
        'value' =>  array(
            1 => 'Language Name',
        ),
    );
    $constant_arr[] = array(
        'name' => 'COMMON_STATUS',
        'section' => 'common',
        'value' =>  array(
            1 => 'Status',
        ),
    );

    $adminConstantsMaster = new AdminConstantsMaster();
    foreach ($constant_arr as $value) {
        $adminConstantsMaster->setWhere('AND constant_name = :constant_name', $value['name'], 'string');
        $adminConstants = $adminConstantsMaster->getAdminConstants();
        if(!empty($adminConstants)) {
            $adminConstants = $adminConstants[0];
            $adminConstantsData = new AdminConstantsData();
            $adminConstantsData->constant_id = $adminConstants['constant_id'];
            $adminConstantsData->section_menu_id = getMenuId($value['section']);
            $adminConstantsMaster->editAdminConstants($adminConstantsData);
            foreach ($value['value'] as $langid => $constvalue) {
                $adminConstantsData = new AdminConstantsData();
                $adminConstantsData->constant_id = $adminConstants['constant_id'];
                $adminConstantsData->language_id = $langid;
                $adminConstantsData->constant_value = $constvalue;
                $adminConstantsMaster->editAdminConstantsDesc($adminConstantsData);
            }
        } else {
            $adminConstantsData = new AdminConstantsData();
            $adminConstantsData->constant_id = NULL;
            $adminConstantsData->constant_name = $value['name'];
            $adminConstantsData->section_menu_id = getMenuId($value['section']);
            $constant_id = $adminConstantsMaster->addAdminConstants($adminConstantsData);
            foreach ($value['value'] as $langid => $constvalue) {
                $adminConstantsData = new AdminConstantsData();
                $adminConstantsData->constant_id = $constant_id;
                $adminConstantsData->constant_value = $constvalue;
                $adminConstantsData->language_id = $langid;
                $adminConstantsMaster->addAdminConstantsDesc($adminConstantsData);
            }
        }
    }
}

function getMenuId($menu_name) {
    if($menu_name == 'common') {
        return '0';
    } else {
        $adminMenuMaster = new AdminMenuMaster();
        $adminMenuMaster->setWhere("AND menu_name = :menu_name", $menu_name, 'string');
        $menuData = $adminMenuMaster->getAdminMenu('yes');
        if(!empty($menuData)) {
            return $menuData[0]['menu_id'];
        }
    }
    return 0;
}

function getSectionId($section_name) {
    if($section_name == 'common') {
        return '0';
    } else {
        $adminSectionMaster = new AdminSectionMaster();
        $adminSectionMaster->setWhere("AND section_heading = :section_heading", $section_name, 'string');
        $sectionData = $adminSectionMaster->getAdminSection('yes');
        if(!empty($sectionData)) {
            return $sectionData[0]['section_id'];
        }
    }
    return 0;
}

function getPageId($page_name) {
    if(!empty($page_name)) {
        $adminPagesMaster = new AdminPagesMaster();
        $adminPagesMaster->setWhere("AND page_name = :page_name", $page_name, 'string');
        $pagesData = $adminPagesMaster->getAdminPage();
        if(!empty($pagesData)) {
            return $pagesData[0]['page_id'];
        }
    }
    return 0;
}

function custom_query($query) {
    $rMaster = new RMasterModel();
    return $rMaster->exec_query($query);
}

?>