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
    $_SESSION['flash_message'] = array($message, $mode);
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

function show_page_header($url) {
    header("Location: ".$url);
    exit;
}

function generateFileName($filename) {
    $filename = str_replace(' ', '_', $filename);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    $return_filename = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $filename).time().'.'.$ext;
    return $return_filename;
}



function uploadFiles($destination, $element) {
    if(!empty($element)) {
        if(!empty($element)) {
            $target_dir = $destination;
            $target_file = $target_dir . basename($element["name"]);
            if (move_uploaded_file($element["tmp_name"], $target_file)) {
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

?>