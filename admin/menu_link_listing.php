<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'MenuLinksMaster.php');

$menuLinksMaster = new MenuLinksMaster();

$listingData = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

if ($action == 'change_status') {
    $statusCode = postValue('status_code');
    $status = postValue('status');
    $id = postValue('id');
    if ($statusCode == 'status') {
        $objMenuLinksData = new MenuLinksData();
        $objMenuLinksData->status = ($status == '1') ? '1' : '0';
        $objMenuLinksData->menu_link_id = $id;
        $menuLinksMaster->editMenuLink($objMenuLinksData);
        echo 'success';
    }
    exit;
}

if ($action == 'delete') {
    $id = getValue('menu_id');
    if (!empty($id)) {
        $menuLinksMaster->deleteMenuLink($id);
        echo 'success';
    } else {
        echo COMMON_DELETE_ERROR;
    }
    exit;
}

if ($isAjaxRequest && ($listingData || $export)) {
    extract(extractSearchFields(), EXTR_PREFIX_ALL, 'SC');

    if ($SC_column == 'display') {
        $SC_column = 'display, icon_class';
    }
    $menuLinksMaster->setLimit($SC_start, $SC_length);
    $menuLinksMaster->setOrderBy($SC_column . ' ' . $SC_dir);

    $menuLinksMaster->setSelect('*');
    if ($export) {
        $menuLinksMaster->setSelect("IF(menu_link.status = '1', '".COMMON_ENABLED."', '".COMMON_DISABLED."') as status");
        $menuLinksMaster->setSelect("IF(menu_link.link_location = 'h', '".COMMON_HEADER."', '".COMMON_FOOTER."') as link_location");
        $menuLinksMaster->setSelect("CASE
                                        WHEN menu_link.display = 'b' THEN '".COMMON_BOTH."'
                                        WHEN menu_link.display = 't' THEN '".MENU_ONLY_TEXT."'
                                        ELSE '".MENU_ONLY_ICON."'
                                    END as display");
    }

    if($SC_keyword) {
        $menuLinksMaster->setWhere("AND (menu_title LIKE :menu_title", "%$SC_keyword%", 'string');
        $menuLinksMaster->setWhere("OR page_link LIKE :page_link", "%$SC_keyword%", 'string');
        $menuLinksMaster->setWhere("OR icon_class LIKE :icon_class)", "%$SC_keyword%", 'string');
    }

    $menuLinksMaster->setFoundRows();
    $menuLinkDetails = $menuLinksMaster->getMenuLinks();

    if ($export) {
        $menuLinkDetails = objectToArray($menuLinkDetails);
        $exportStructure = array();
        $exportStructure[] = array('menu_link_id' => array('name' => 'menu_link_id', 'title' => COMMON_ID));
        $exportStructure[] = array('menu_title' => array('name' => 'menu_title', 'title' => COMMON_TITLE));
        $exportStructure[] = array('page_link' => array('name' => 'page_link', 'title' => MENU_PAGE_LINK));
        $exportStructure[] = array('link_location' => array('name' => 'link_location', 'title' => LINK_LOCATION));
        $exportStructure[] = array('display' => array('name' => 'display', 'title' => COMMON_DISPLAY));
        $exportStructure[] = array('icon_class' => array('name' => 'icon_class', 'title' => COMMON_ICON_CLASS));
        $exportStructure[] = array('sort_order' => array('name' => 'sort_order', 'title' => COMMON_SORT_ORDER));
        $exportStructure[] = array('status' => array('name' => 'status', 'title' => COMMON_STATUS));

        $sheetTitle = $pageTitle;
        $headerDate = "All";
        $spreadsheet = exportFileGenerate($exportStructure, $menuLinkDetails);
        echo json_encode(exportReport($spreadsheet));
        exit;
    }

    $menuLinksArr = array();
    $totalRec = 0;
    if (!empty($menuLinkDetails)) {
        $totalRec = $menuLinkDetails->FoundRows();
        $sr = $SC_start + 1;
        $locationArr = array(
            'h' => COMMON_HEADER,
            'f' => COMMON_FOOTER,
        );
        foreach ($menuLinkDetails as $menuLink) {
            $rec = array();
            $rec['DT_RowId'] = "menulink:" . $menuLink['menu_link_id'];
            $rec['sr'] = $sr++;
            $rec['menu_title'] = $menuLink['menu_title'];
            $rec['link_location'] = $locationArr[$menuLink['link_location']];
            $rec['page_link'] = $menuLink['page_link'];
            $rec['display'] = getMenuIconData($menuLink);
            $rec['status'] = formSwitchbutton('status', $menuLink['status'], array('element_class' => 'ajax change_status', 'id' => 'status_' . $menuLink['menu_link_id']));
            $actionButtons = array();
            $actionButtons[COMMON_EDIT] = array(
                'link' => DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_EDIT . '?action=edit&menu_id=' . $menuLink['menu_link_id'],
                'icon' => 'fa fa-edit',
                'class' => 'btn-outline-secondary',
            );
            $actionButtons[COMMON_DELETE] = array(
                'link' => DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_LISTING . '?action=delete&menu_id=' . $menuLink['menu_link_id'],
                'icon' => 'fa fa-trash',
                'class' => 'bg-danger text-white ajax delete',
            );
            $rec['action'] = drawActionMenu($actionButtons);
            $menuLinksArr[] = $rec;
        }
    }
    $result = array(
        'data' => $menuLinksArr,
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

$actionButtons = array();
$actionButtons[COMMON_ADD] = array(
    'link' => DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_EDIT,
    'class' => 'btn-success',
    'icon' => 'fa fa-plus'
);

require_once(DIR_WS_ADMIN_INCLUDES.FILE_MAIN_INTERFACE);

?>