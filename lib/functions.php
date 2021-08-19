<?php


function objectToArray($data) {
    if (is_array($data) || is_object($data)) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = objectToArray($value);
        }
        return $result;
    }
    return $data;
}

function is_dev_mode() {
    return (defined('DEV_MODE') && constant('DEV_MODE') == true);
}

function type_cast($value, $type) {
    switch ($type) {
        case 'int':
        case 'integer':
            return (int) $value;
            break;
        case 'float':
            return (float) $value;
            break;
        case 'double':
            return (double) $value;
            break;
        case 'bool':
        case 'boolean':
            return (bool) $value;
            break;
        case 'string':
        default:
            return (string) $value;
            break;
    }
}

function is_assoc(array $array) {
    return (bool) count(array_filter(array_keys($array), 'is_string'));
}

function rxArrayToAssoc($rdxAry, $keyAry) {
    if(!is_array($rdxAry) || !is_array($keyAry) || count($rdxAry) === 0 || count($keyAry) === 0 || count($rdxAry) !== count($keyAry)) {
        return array();
    }
    $aryOut = array();
    for ($i=0; $i < count($rdxAry); $i++) { 
        $aryOut[$keyAry[$i]] = $rdxAry[$i];
    }
    return $aryOut;
}

function is_blank($value, $type = null) {
    if(isset($type)) {
        switch ($type) {
            case 'string':
                return (is_null($value) || trim($value) == '');
                break;
            case 'int':
                return (is_null($value));
                break;
            case 'boolean':
                return (is_null($value) || $value != true || $value != false);
                break;
            case 'array':
                return (is_null($value) || empty($value));
                break;
            default:
                return (is_null($value) || !isset($value));
                break;
        }
    }
}

function set_flash_message($message, $mode) {
    setcookie('flash_message', json_encode(array($message, $mode)));
}
function getValue($name, $default='') {
    if(isset($_GET[$name])) {
        return $_GET[$name];
    } else {
        return $default;
    }
}

function postValue($name, $default='') {
    if(isset($_POST[$name])) {
        return $_POST[$name];
    } else {
        return $default;
    }
}

function requestValue($name, $default='') {
    if(isset($_REQUEST[$name])) {
        return $_REQUEST[$name];
    } else {
        return $default;
    }
}

function fileValue($name, $default='') {
    if(isset($_FILES[$name])) {
        return $_FILES[$name];
    } else {
        return $default;
    }
    // return (isset($_FILES[$name]) && !empty($_FILES[$name])) ? $_FILES[$name]['name'] : $default;
}

function checkEmpty($value, $msg) {
    if(empty($value) || !isset($value) || $value == '') {
        return $msg;
    }
}

function checkEmailPattern($value, $msg) {
    if(!preg_match("/(?:[a-z0-9!#$%&'*+\=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\=?^_`{|}~-]+)*|'(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*')@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/", $value)) {
        return $msg;
    }
}

function checkPhoneNumber($value, $msg) {
    if(!preg_match("/^[0-9]{10}$/", $value)) {
        return $msg;
    }
}

function checkNumeric($value, $msg) {
    if(!preg_match("/^[0-9]*$/", $value)) {
        return $msg;
    }
}

function checkValidation($err) {
    $validation = true;
    foreach ($err as $value) {
        if(!empty($value)) {
            $validation = false;
            break;
        }
    }
    return $validation;
}

function show_page_header($url) {
    header("Location: ".$url);
    exit;
}

function generateFileName($filename) {
    $filename = str_replace(' ', '_', $filename);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    $returnFilename = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $filename).'_'.time().'.'.$ext;
    return $returnFilename;
}

function uploadFiles($destination, $element) {
    if(!empty($element)) {
        if(!empty($element)) {
            $targetDir = $destination;
            $targetFile = $targetDir . basename($element["name"]);
            if (move_uploaded_file($element["tmp_name"], $targetFile)) {
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

function getWebsiteLogos() {
    require_once(DIR_WS_MODEL.'WebsiteLogosMaster.php');

    $objWebsiteLogosMaster = new WebsiteLogosMaster();
    $wLogosData = $objWebsiteLogosMaster->getWebsiteLogos();
    if(!empty($wLogosData)) {
        $wLogosData = $wLogosData[0];
    }
    return $wLogosData;
}

function createFrontConstants() {
    global $twc;
    foreach (get_defined_constants(true)['user'] as $key => $value) {
        $twc[$key] = $value;
    }
}

?>