<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL . 'MenuLinksMaster.php');

$objMenuLinksMaster = new MenuLinksMaster();

$submit = postValue('submit_btn');
$action = requestValue('action');
$menuId = requestValue('menu_id');

// Add/Edit Menu link data
if (!empty($submit)) {
    $menu_Ttle = postValue('menu_title');
    $linkLocation = postValue('link_location');
    $pageLink = postValue('page_link');
    $display = postValue('display');
    $iconClass = postValue('icon_class');
    $sortOrder = postValue('sort_order');
    $status = postValue('status');

    // Validation start
    $err = array();
    $err['menu_title'] = checkEmpty($menuTitle, COMMON_VALIDATE_REQUIRED);
    $err['page_link'] = checkEmpty($pageLink, COMMON_VALIDATE_REQUIRED);

    if (in_array($display, array('b', 'i'))) {
        $err['icon_class'] = checkEmpty($iconClass, COMMON_VALIDATE_REQUIRED);
    }

    $validation = checkValidation($err);
    // Validation end

    if ($validation) {
        $enc = new Encryption();
        $objMenuLinksData = new MenuLinksData();
        $objMenuLinksData->menu_title = $menuTitle;
        $objMenuLinksData->page_link = $pageLink;
        $objMenuLinksData->link_location = $linkLocation;
        $objMenuLinksData->display = $display;
        $objMenuLinksData->icon_class = $iconClass;
        $objMenuLinksData->sort_order = $sortOrder;
        $objMenuLinksData->status = !empty($status) ? '1' : '0';

        if ($action == 'edit') {
            $objMenuLinksData->menu_link_id = $menuId;
            if ($objMenuLinksMaster->editMenuLink($objMenuLinksData)) {
                setFlashMessage(COMMON_UPDATE_SUCCESS, 'success');
            } else {
                setFlashMessage(COMMON_UPDATE_ERROR, 'danger');
            }
        } else {
            $menuId = $objMenuLinksMaster->addMenuLink($objMenuLinksData);
            if ($menuId > 0) {
                setFlashMessage(COMMON_INSERT_SUCCESS, 'success');
            } else {
                setFlashMessage(COMMON_INSERT_ERROR, 'danger');
            }
        }
        if (!empty($menuId)) {
            if ($submit == COMMON_SAVE) {
                showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_EDIT . '?action=edit&menu_id=' . $menuId);
            } elseif ($submit == COMMON_SAVE_BACK) {
                showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_LISTING);
            }
        }
    }
}

// Get passenger data
if (!empty($menuId)) {
    $menuLinkData = $objMenuLinksMaster->getMenuLinks($menuId);
    if (!empty($menuLinkData)) {
        $formData = $menuLinkData[0];
    } else {
        setFlashMessage(COMMON_RECORD_NOT_EXISTS, 'fail');
        showPageHeader(DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING);
    }
}

$pageTitle = !empty($menuId) ? EDIT_MENU_LINK : ADD_MENU_LINK;
$headingLabel = $pageTitle;
if (!empty($menuId)) {
    $headingLabel .= "<small>" . $formData['menu_title'] . "</small>";
}

// Set breadcrub array
$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'link' => DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_LISTING,
        'title' => HEADER_FOOTER_LINKS,
    ),
    array(
        'title' => $pageTitle,
    ),
);

require_once(DIR_WS_ADMIN_INCLUDES . FILE_MAIN_INTERFACE);
