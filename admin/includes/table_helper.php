<?php

function getPassengerDetails($userDetails, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $userDetails['firstname'].' '.$userDetails['lastname'];
    $return .= $separator."<a href='mailto:".$userDetails['email']."'>".$userDetails['email']."</a>";
    $return .= $separator."<strong>".COMMON_PHONE_NUMBER." : </strong><a href='tel:".$userDetails['phone']."'>".$userDetails['phone']."</a>";

    return $return;
}

function getAddressDetails($addressDetails, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $addressDetails['address_line'];
    if(!empty($addressDetails['address_line2'])) {
        $return .= $separator.$addressDetails['address_line2'];
    }
    $return .= $separator.$addressDetails['city_name'].', '.$addressDetails['state_name'].', '.$addressDetails['country_name'];
    $return .= $separator."<strong>".COMMON_BUS_STOP." : </strong>".$addressDetails['stop_title']." [".$addressDetails['stop_internal_name']."]";

    return $return;
}

function getEmailSubjectDetails($emailDetails, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $emailDetails['constant_name'];
    $return .= $separator."<strong>".COMMON_SUBJECT." : </strong>".$emailDetails['template_subject'];

    return $return;
}

function getMenuIconData($menuLink, $format = 'html') {
    $separator = ($format == 'html') ? "<br>" : "\n";
    $return = '';

    $displayArr = array(
        'b' => COMMON_BOTH,
        't' => MENU_ONLY_TEXT,
        'i' => MENU_ONLY_ICON,
    );

    $return = $displayArr[$menuLink['display']];

    if($menuLink['icon_class'] && in_array($menuLink['display'], array('b', 'i'))) {
        $return .= " ( <i class='".$menuLink['icon_class']."'></i> )";
    }

    return $return;
}