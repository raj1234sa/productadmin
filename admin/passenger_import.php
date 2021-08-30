<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL.'MenuLinksMaster.php');

$menuLinksMaster = new MenuLinksMaster();

$listingData = postValue('listing_data');
$export = requestValue('export');
$action = requestValue('action');

$headingLabel = IMPORT_PASSENGER;

$breadcrumbArr = array(
    $breadcrumbHome,
    array(
        'title' => IMPORT_PASSENGER,
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