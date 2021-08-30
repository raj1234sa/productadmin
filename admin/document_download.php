<?php

require_once('../lib/common.php');

$action = requestValue('action');

$filename = '';
switch ($action) {
    case 'passenger':
        if(IMPORT_DOWNLOAD_EXT == 'xlsx') {
            $filename = 'passenger_import.xlsx';
        } else {
            $filename = 'passenger_import.csv';
        }
        break;
}
if(!empty($filename)) {
    $filename = DIR_WS_IMAGES_DOCUMENTS.$filename;
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    ob_clean();
    flush();
    readfile($filename);
}
exit();
