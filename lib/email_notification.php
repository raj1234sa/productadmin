<?php
use PHPMailer\PHPMailer\PHPMailer;

class EmailNotificaion {
    private $mailObj;
    public function __construct() {
        $this->mailObj = new PHPMailer(true);
    }

    public function getVariables($emailKey) {
        $objUtilMaster = new UtilMaster();

        $objUtilMaster->setFrom('email_variables');
        $objUtilMaster->setJoin("LEFT JOIN email_configuration ON email_configuration.email_template_id = email_variables.email_template_id AND constant_name = :constant_name", $emailKey, 'int');
        $variablesData = $objUtilMaster->exec_query();

        $variablesArr = array();
        
        if(!empty($variablesData)) {
            foreach ($variablesData as $variable) {
                $variablesArr[$variable['variable_name']] = '';
            }
        }
        return $variablesArr;
    }

    public function getEmailConfiguration($emailKey, $variableKey, $variableValue) {
        require_once(DIR_WS_MODEL.'EmailConfigurationMaster.php');
        $objEmailConfigurationMaster = new EmailConfigurationMaster();

        $emailConfig = array();

        $objEmailConfigurationMaster->setWhere("AND constant_name = :constant_name", $emailKey, 'string');
        $emailData = $objEmailConfigurationMaster->getEmailConfiguration(null, 'yes');
        if(empty($emailData)) {
            return false;
        }
        $emailData = $emailData[0];
        $variables = $this->getVariables($emailKey);

        foreach ($variableKey as $key) {
            if(!array_key_exists($key, $variables)) {
                $index = array_search($key, $variableKey);
                unset($variableKey[$index]);
                unset($variableValue[$index]);
            }
        }
        $emailConfig['subject'] = str_replace($variableKey, $variableValue, $emailData['template_subject']);
        $emailConfig['content'] = str_replace($variableKey, $variableValue, $emailData['template_content']);

        $objUtilMaster = new UtilMaster();

        $objUtilMaster->setFrom('email_settings');
        $objUtilMaster->setWhere("AND `default` = :default1", '1', 'string');
        $emailSettings = $objUtilMaster->exec_query();
        if(empty($emailSettings)) { return null; }
        
        $emailSettings = $emailSettings[0];
        $authType = 'mail';
        if($emailSettings['authentication_type'] == '1') {
            $authType = 'smtp';
        } elseif($emailSettings['authentication_type'] == '2') {
            $authType = 'api';
        }
        $emailConfig['auth'] = $authType;
        $emailConfig['username'] = $emailSettings['username'];
        $emailConfig['password'] = $emailSettings['password'];
        $emailConfig['host'] = $emailSettings['host'];
        $emailConfig['from'] = CONFIG_SITE_EMAIL;
        $emailConfig['from_name'] = CONFIG_SITE_NAME;

        return $emailConfig;
    }

    public function sendEmailNotification($emailConfig) {
        try {
            if(empty($emailConfig['from'])) {
                return null;
            }
            $this->mailObj->setFrom($emailConfig['from'], $emailConfig['from_name'], 0);
            //Server settings
            switch ($emailConfig['auth']) {
                case 'mail':
                    $this->mailObj->SMTPAuth = false;
                    $this->mailObj->SMTPSecure = false;
                    $this->mailObj->isMail();
                    break;
                case 'smtp':
                    $this->mailObj->SMTPDebug = 0;
                    $this->mailObj->isSMTP();
                    $this->mailObj->Host       = $emailConfig['host'];
                    $this->mailObj->SMTPAuth   = true;
                    $this->mailObj->Username   = $emailConfig['username'];
                    $this->mailObj->Password   = $emailConfig['password'];
                    $this->mailObj->SMTPSecure = 'tls';
                    $this->mailObj->Port = 587;
                    break;
            }
            //Recipients
            $toArr = explode(',', $emailConfig['to']);
            $toArr = array_filter($toArr);
            foreach ($toArr as $value) {
                if(!empty($value)) {
                    $this->mailObj->addAddress($value);
                }
            }
            // $this->mailObj->addAddress('kailashmistry59@gmail.com');               //Name is optional
            // $this->mailObj->addReplyTo('info@example.com', 'Information');
            // $this->mailObj->addCC('cc@example.com');
            // $this->mailObj->addBCC('bcc@example.com');

            //Attachments
            // $this->mailObj->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $this->mailObj->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $this->mailObj->isHTML(true);                                  //Set email format to HTML
            $this->mailObj->Subject = $emailConfig['subject'];
            $this->mailObj->Body = html_entity_decode($emailConfig['content']);

            $this->mailObj->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailObj->ErrorInfo}";
            exit;
        }
    }
}

?>