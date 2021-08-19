<?php

function sendSampleEmail() {
    $emailKey = 'SAMPLE_EMAIL';

    $notification = new EmailNotificaion();

    $variables = $notification->getVariables($emailKey);
    $variables['{site_name}'] = 'Raj';

    $emailConfig = $notification->getEmailConfiguration($emailKey, array_keys($variables), array_values($variables));
    
    if($emailConfig !== FALSE) {
        $emailConfig['to'] = 'prathamkadiya2002@gmail.com';
        $notification->sendEmailNotification($emailConfig);
    }
}

function sendPassengerRegisteredAdmin($passenger_id) {
    $emailKey = 'PASSENGER_ADDED_ADMIN';
    $notification = new EmailNotificaion();

    require_once(DIR_WS_MODEL.'PassengersMaster.php');
    
    $objPassengersMaster = new PassengersMaster();

    $passengerDetails = $objPassengersMaster->getPassenger($passenger_id);
    if(!empty($passengerDetails)) {
        $passengerDetails = $passengerDetails[0];
    }

    $variables = $notification->getVariables($emailKey, $passenger_id);

    $emailConfig = $notification->getEmailConfiguration($emailKey, array_keys($variables), array_values($variables));
    if($emailConfig !== FALSE) {
        $emailConfig['to'] = $passengerDetails['email'];
        $notification->sendEmailNotification($emailConfig);
    }
}


?>