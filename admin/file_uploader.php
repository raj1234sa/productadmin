<?php

require_once('../lib/common.php');

$params = requestValue('params');
$params = json_decode($params, true);

$action = requestValue('action');
$response = array();

if($action == 'add') {
    $filename = fileValue('files');

    $img_filename = generateFileName($filename['name']);
    $filename['name'] = $img_filename;
    $uploaded = uploadFiles(DIR_WS_TEMP_IMAGES, $filename);
    if($uploaded) {
        $response = array(
            'preview_html' => draw_imge(DIR_HTTP_TEMP_IMAGES.$img_filename, DIR_WS_TEMP_IMAGES.$img_filename, array('width'=>'100','class'=>'image_zoom')),
            'filename' => $img_filename,
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
    $del_filename = $params['filename'];
    $src_path = $params['srcPath'];

    $result = unlink($src_path.$del_filename);
    
    if($result) {
        $response = array(
            'preview_html' => draw_noimge(array('width'=>'100')),
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