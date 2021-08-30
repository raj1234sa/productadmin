<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Create admin section, page, menu od constants
*/
function defineAccessData() {
    require_once(DIR_WS_MODEL.'AdminMenuMaster.php');
    require_once(DIR_WS_MODEL.'AdminSectionMaster.php');
    require_once(DIR_WS_MODEL.'AdminPagesMaster.php');

    $AdminPagesMaster = new AdminPagesMaster();
    if(FILE_FILENAME_WITHOUT_EXT != 'index') {
        $AdminPagesMaster->setWhere("AND page_name = :page_name", FILE_FILENAME_WITH_EXT, 'string');
        $pageData = $AdminPagesMaster->getAdminPage();
        if(empty($pageData)) { define('ADMIN_PAGE_ID', 0);}
        $pageData = $pageData[0];
        define('ADMIN_PAGE_ID', $pageData['page_id']);
    } else {
        define('ADMIN_PAGE_ID', 0);
    }

    global $pageTitle;
    if(FILE_FILENAME_WITHOUT_EXT == 'welcome') {
        $pageTitle = 'Dashboard';
    }
    $allowedPages = array();
    $AdminMenuMaster = new AdminMenuMaster();
    if(defined('ADMIN_PAGE_ID') && ADMIN_PAGE_ID > 0) {
        $AdminMenuMaster->setWhere('AND FIND_IN_SET("'.ADMIN_PAGE_ID.'", page_ids) > 0', '', 'string');
        $AdminMenuMaster->setJoin('LEFT JOIN admin_section ON admin_section.section_id = admin_section_menu.section_id');
        $AdminMenuMaster->setJoin('LEFT JOIN admin_section_description ON admin_section_description.section_id = admin_section.section_id');
        $adminMenuData = $AdminMenuMaster->getAdminMenu('yes');
        if(empty($adminMenuData)) { define('ADMIN_MENU_ID', 0); define('ADMIN_SECTION_ID', 0); }
        elseif(!empty($adminMenuData)) {
            $adminMenuData = $adminMenuData[0];
            $allowedPages = explode(',', $adminMenuData['page_ids']);
            $pageTitle = $adminMenuData['menu_name'];
            define('ADMIN_MENU_ID', $adminMenuData['menu_id']);
            define('ADMIN_SECTION_ID', $adminMenuData['section_id']);
        }
    } else {
        define('ADMIN_MENU_ID', 0);
        define('ADMIN_SECTION_ID', 0);
    }
    define('ADMIN_ALLOWED_PAGE_ID', $allowedPages);
}

/**
 * Create admin constants
*/
function createAdminConstants() {
    if(defined('ADMIN_MENU_ID')) {
        require_once(DIR_WS_MODEL.'AdminConstantsMaster.php');
        $adminConstantsMaster = new AdminConstantsMaster();

        $adminConstantsMaster->setSelect('admin_constants.constant_name, admin_constants_description.constant_value');
        $adminConstantsMaster->setWhere('AND (section_menu_id = :section_menu_id', ADMIN_MENU_ID, 'int');
        $adminConstantsMaster->setWhere('OR section_menu_id = :section_menu_id_1)', 0, 'int');
        $constantsData = $adminConstantsMaster->getAdminConstants('yes');

        if(!empty($constantsData)) {
            foreach ($constantsData as $value) {
                define($value['constant_name'], $value['constant_value']);
            }
        }
    }
}

/**
 * Get datatable filter data
 * @return array
*/
function extractSearchFields() {
    $postData = requestValue();
    $searchData = $postData['data'];
    parse_str($searchData, $resultSearch);
    $resultSearch['start'] = $postData['start'];
    $resultSearch['length'] = $postData['length'];
    $resultSearch['column'] = $resultSearch['dir'] = '';
    if(isset($postData['order'])) {
        $resultSearch['column'] = $postData['columns'][$postData['order'][0]['column']]['data'];
        $resultSearch['dir'] = $postData['order'][0]['dir'];
    }
    $resultSearch['searchval'] = $postData['search']['value'];
    parse_str($postData['searchval'], $urlParams);
    $resultSearch = array_merge($resultSearch, $urlParams);
    return $resultSearch;
}

/**
 * Get response for download
 * @param Spreadsheet $spreadsheet
 * @param string $fileName
 * @return array
*/
function exportReport($spreadsheet, $fileName = '') {
    ob_start();
    if(empty($fileName)) {
        global $pageTitle;
        $fileName = str_replace(" ", "_", $pageTitle);
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $fileName);
    }
    $fileName = empty($fileName) ? 'download.xlsx' : $fileName;
    IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
    $pdfData = ob_get_contents();
    ob_end_clean();
    return array(
        'op' => 'ok',
        'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pdfData),
        'fileName' => $fileName,
    );
}

/**
 * Get spreadsheet data based in data passed
 * @param array $exportDataStructure
 * @param array $exportData
 * @param array $extra
 * @return Spreadsheet
*/
function exportFileGenerate($exportDataStructure, $exportData, $extra = array()) {
    if (!empty($exportData)) {
        $rowIndex = 0;
        $colIndex = 0;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        global $sheetTitle;
        $sheetTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $sheetTitle);
        $sheet->setTitle((!empty($extra['sheetTitle']) ? $extra['sheetTitle'] : $sheetTitle));

        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => "ffffff"
                ]
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'rgb' => '4659d4',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];

        $styleUrlArray = [
            'font' => [
                'underline' => true,
                'color' => [
                    'rgb' => "1a58c7"
                ]
            ],
        ];
        $headerCells = array();
        foreach ($exportDataStructure as $key => $value) {
            foreach ($value as $value1) {
                $header[] = $value1['title'];
                $sheet->setCellValue(chr(65 + $key) . '1', $value1['title']);
                $sheet->getStyle(chr(65 + $key) . '1')->applyFromArray($styleArray);
                $sheet->getColumnDimension(chr(65 + $key))->setAutoSize(true);
            }
            $headerCells[] = chr(65 + $key) . '1';
        }
        $rowIndex++;

        $sheet->insertNewRowBefore(1, 2);
        $rowIndex += 2;
        $sheet->mergeCells($headerCells[0] . ":" . $headerCells[count($headerCells) - 1]);
        global $headerDate;
        $headerDate = !empty($extra['headerDate']) ? $extra['headerDate'] : $headerDate;
        $sheet->setCellValue("A1", "Report $headerDate");
        $sheet->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(30);
        $cellvalue = array();
        foreach ($exportData as $rowcount => $value) {
            $value = objectToArray($value);
            $rowcount += $rowIndex;
            foreach ($exportDataStructure as $colcount => $value1) {
                $cellHeight = 15;
                $value1 = array_values($value1)[0];
                if(isset($value1['call_func']) && !empty($value1['call_func'])) {
                    $funcParam = array();
                    foreach ($value1['func_param'] as $param) {
                        if(in_array($param, array_keys($value))) {
                            $funcParam[] = $value[$param];
                        } else {
                            $funcParam[] = $param;
                        }
                    }
                    $value[$value1['name']] = call_user_func_array($value1['call_func'], $funcParam);
                }
                if (is_array($value[$value1['name']])) {
                    $cellHeight = count($value[$value1['name']]) * 15;
                    $value[$value1['name']] = implode("\n", $value[$value1['name']]);
                }

                if (isset($value1['datatype'])) {
                    switch ($value1['datatype']) {
                        case 'email':
                            $sheet->getCell(chr(65 + $colcount) . ($rowcount + 1))->getHyperlink()->setUrl("mailto:" . $value[$value1['name']]);
                            $sheet->getStyle(chr(65 + $colcount) . ($rowcount + 1))->applyFromArray($styleUrlArray);
                            break;

                        case 'date':
                            $sheet->getStyle(chr(65 + $colcount) . ($rowcount + 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                            $value[$value1['name']] = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($value[$value1['name']]);
                            break;

                        case 'currency':
                            $sheet->getStyle(chr(65 + $colcount) . ($rowcount + 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                            break;

                        default:
                            break;
                    }
                }
                if (isset($value1['total'])) {
                    if ($value1['total'] == TRUE) {
                        $cellvalue[chr(65 + $colcount)][] = chr(65 + $colcount) . ($rowcount + 1);
                        $cellvalue[chr(65 + $colcount)]['lastcell'] = chr(65 + $colcount) . ($rowcount + 1 + 2);
                    }
                }
                $sheet->setCellValue(chr(65 + $colcount) . ($rowcount + 1), $value[$value1['name']]);

                $sheet->getStyle(chr(65 + $colcount) . ($rowcount + 1))->getAlignment()->setWrapText(true);
                $sheet->getRowDimension($rowcount + 1)->setRowHeight($cellHeight);
                $colIndextemp = $colcount + 1;
            }
            $colIndex += $colIndextemp;
            $rowIndextemp = $rowcount;
        }
        $rowIndex += $rowIndextemp;

        foreach ($cellvalue as $key => $value) {
            $sheet->setCellValue($value['lastcell'], "=SUM({$cellvalue[$key][0]}:" . array_pop($cellvalue[$key]) . ")");
        }
        foreach ($headerCells as $key => $value) {
            $sheet->getStyle(chr(65 + $key) . $rowIndex)->applyFromArray($styleArray);
        }
        return $spreadsheet;
    }
}

/**
 * Draw image
 * @param string $imgHttpPath
 * @param string $imgSrcPath
 * @param array $extraParam
 * @return string
*/
function drawImge($imgHttpPath, $imgSrcPath, $extraParam = array()) {
    $html = '';
    $attr = getAttributes($extraParam);
    
    if(file_exists($imgSrcPath))
        $html = '<img src="'.$imgHttpPath.'" '.$attr.'>';
    return $html;
}

/**
 * Draw image
 * @param array $extraParam
 * @return string
*/
function drawNoimge($extraParam = array()) {
    $html = '';
    $attr = getAttributes($extraParam);
    $imgSrcPath = DIR_WS_IMAGES_COMMON.'no_preview.jpg';
    $imgHttpPath = DIR_HTTP_IMAGES_COMMON.'no_preview.jpg';
    
    if(file_exists($imgSrcPath))
        $html = '<img src="'.$imgHttpPath.'" '.$attr.'>';
    return $html;
}

/**
 * Get available menu actions for validation
 * @return array
*/
function getAvailableActions() {
    $returnArr = array();

    $objUtilMaster = new UtilMaster();

    $objUtilMaster->setFrom('admin_menu_action');
    $objUtilMaster->setWhere('AND section_menu_id = :section_menu_id', ADMIN_MENU_ID, 'int');
    $menuActions = $objUtilMaster->exec_query();

    foreach ($menuActions as $value) {
        $returnArr[constant($value['constant_name'])] = '';
    }
    return $returnArr;
}

/**
 * Sync form data with table data
 * @param array $formData
 * @return array
*/
function syncPostData($formData) {
    if(empty($_POST)) { return $formData; }
    $postData = $_POST;
    foreach ($postData as $key => $value) {
        if(isset($postData[$key])) {
            $formData[$key] = $value;
        }
    }
    return $formData;
}

?>