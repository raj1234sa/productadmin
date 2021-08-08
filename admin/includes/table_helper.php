<?php

function getPassengerDetails($userDetails, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $userDetails['firstname'].' '.$userDetails['lastname'];
    $return .= $separator."<a href='mailto:".$userDetails['email']."'>".$userDetails['email']."</a>";
    $return .= $separator."<strong>".COMMON_PHONE_NUMBER." : </strong>".$userDetails['phone'];

    return $return;
}

function getEmailSubjectDetails($emailDetails, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $emailDetails['constant_name'];
    $return .= $separator."<strong>".COMMON_SUBJECT." : </strong>".$emailDetails['template_subject'];

    return $return;
}

?>