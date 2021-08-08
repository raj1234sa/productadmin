<?php

function sendSampleEmail() {
    $emailKey = 'SAMPLE_EMAIL';

    $notification = new EmailNotificaion();

    $variables = $notification->getVariables($emailKey);
    $variables['{site_name}'] = 'Raj';

    $emailConfig = $notification->getEmailConfiguration($emailKey, array_keys($variables), array_values($variables));
    $emailConfig['to'] = 'prathamkadiya2002@gmail.com';
    $notification->sendEmailNotification($emailConfig);
}


?>