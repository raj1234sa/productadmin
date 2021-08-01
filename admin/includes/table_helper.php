<?php

function getPassengerDetails($user_details, $format = 'html') {
    $return = '';
    $separator = ($format == 'html') ? "<br>" : "\n";

    $return .= $user_details['firstname'].' '.$user_details['lastname'];
    $return .= $separator."<a href='mailto:".$user_details['email']."'>".$user_details['email']."</a>";
    $return .= $separator."<strong>".COMMON_PHONE_NUMBER." : </strong>".$user_details['phone'];

    return $return;
}

?>