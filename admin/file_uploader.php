<?php

require_once('../lib/common.php');

$params = requestValue('params');
$params = json_decode($params, true);

$action = requestValue('action');
$response = array();

if($action == 'add') {
    $filename = fileValue('files');

    $imgFilename = generateFileName($filename['name']);
    $filename['name'] = $imgFilename;
    $srcPath = $params['srcPath'];
    $httpPath = $params['httpPath'];
    $allowDelete = $params['delete'];
    if(!is_dir($srcPath)) {
        mkdir($srcPath);
    }
    $uploaded = uploadFiles($srcPath, $filename);
    $extension = pathinfo($imgFilename, PATHINFO_EXTENSION);
    $deleteStr = "<i class='ti-trash delete'></i>";
    $fileType = '';
    if(in_array($extension, array('jpg','png','jpeg','gif'))) { $fileType = 'image'; }
    if($uploaded) {
        $response = array(
            'preview_html' => drawImge($httpPath.$imgFilename, $srcPath.$imgFilename, array('width'=>'100','class'=>'image_zoom')).((!isset($allowDelete) || $allowDelete != false) ? $deleteStr : ''),
            'file_preview_html' => $imgFilename.((!isset($allowDelete) || $allowDelete != false) ? $deleteStr : ''),
            'filename' => $imgFilename,
            'file_type' => $fileType,
        );
    } else {
        $response = array(
            'status' => 'fail',
        );
    }
    echo json_encode($response);
    exit;
}

if($action == 'delete') {
    $delFilename = $params['filename'];
    $srcPath = $params['srcPath'];
    $extension = pathinfo($delFilename, PATHINFO_EXTENSION);
    $fileType = '';
    if(in_array($extension, array('jpg','png','jpeg','gif'))) { $fileType = 'image'; }

    $result = unlink($srcPath.$delFilename);
    
    if($result) {
        $response = array(
            'preview_html' => drawNoimge(array('width'=>'100')),
            'file_type' => $fileType,
        );
    } else {
        $response = array(
            'status' => 'fail',
        );
    }
    echo json_encode($response);
    exit;
}

?>