<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

function createAdminConstants() {
    require_once(DIR_WS_MODEL.'AdminConstantsMaster.php');
    $adminConstantsMaster = new AdminConstantsMaster();

    $adminConstantsMaster->setSelect('admin_constants.constant_name, admin_constants_description.constant_value');
    $adminConstantsMaster->setWhere('AND (section_menu_id = :section_menu_id', ADMIN_SECTION_ID, 'int');
    $adminConstantsMaster->setWhere('OR section_menu_id = :section_menu_id_1)', 0, 'int');
    $constantsData = $adminConstantsMaster->getAdminConstants('yes');

    if(!empty($constantsData)) {
        foreach ($constantsData as $value) {
            define($value['constant_name'], $value['constant_value']);
        }
    }
}

function defineAccessData() {
    require_once(DIR_WS_MODEL.'AdminMenuMaster.php');
    require_once(DIR_WS_MODEL.'AdminSectionMaster.php');
    require_once(DIR_WS_MODEL.'AdminPagesMaster.php');

    $AdminPagesMaster = new AdminPagesMaster();
    if(FILE_FILENAME_WITHOUT_EXT != 'index') {
        $AdminPagesMaster->setWhere("AND page_name = :page_name", FILE_FILENAME_WITH_EXT, 'string');
        $pageData = $AdminPagesMaster->getAdminPage();
        if(empty($pageData)) { echo 'Page not available'; exit; }
        $pageData = $pageData[0];
        define('ADMIN_PAGE_ID', $pageData['page_id']);
    } else {
        define('ADMIN_PAGE_ID', 0);
    }

    $AdminSectionMaster = new AdminSectionMaster();
    global $page_title;
    if(defined('ADMIN_PAGE_ID') && ADMIN_PAGE_ID > 0) {
        $AdminSectionMaster->setWhere('AND FIND_IN_SET(page_ids, "'.ADMIN_PAGE_ID.'") > 0', '', '');
        $adminSectionData = $AdminSectionMaster->getAdminSection('yes');
        if(empty($adminSectionData)) { define('ADMIN_SECTION_ID', 0); }
        else {
            $adminSectionData = $adminSectionData[0];
            $page_title = $adminSectionData['section_heading'];
            define('ADMIN_SECTION_ID', $adminSectionData['section_id']);
        }
    } else {
        define('ADMIN_SECTION_ID', 0);
    }

    $AdminMenuMaster = new AdminMenuMaster();
    if(defined('ADMIN_SECTION_ID') && ADMIN_SECTION_ID > 0) {
        $AdminMenuMaster->setWhere('AND section_id = :section_id', ADMIN_SECTION_ID, 'int');
        $adminMenuData = $AdminMenuMaster->getAdminMenu('yes');
        if(empty($adminMenuData) && $page_title == 'Dashboard') { define('ADMIN_MENU_ID', 0); }
        elseif(!empty($adminMenuData)) {
            $adminMenuData = $adminMenuData[0];
            $page_title = $adminMenuData['menu_name'];
            define('ADMIN_MENU_ID', $adminMenuData['menu_id']);
        }
    } else {
        define('ADMIN_MENU_ID', 0);
    }
}

function extract_search_fields($prefix = '') {
    $search_data = requestValue('data');
    parse_str($search_data, $result_search);
    $result_search['start'] = requestValue('start');
    $result_search['length'] = requestValue('length');
    $result_search['column'] = requestValue('columns')[requestValue('order')[0]['column']]['data'];
    $result_search['dir'] = requestValue('order')[0]['dir'];
    return $result_search;
}

function export_report($spreadsheet, $fileName = 'download.xlsx') {
    ob_start();
    IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
    $pdfData = ob_get_contents();
    ob_end_clean();
    return array(
        'op' => 'ok',
        'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pdfData),
        'fileName' => $fileName,
    );
}

function export_file_generate($export_data_structure, $export_data, $extra = array()) {
    if (!empty($export_data)) {
        $rowIndex = 0;
        $colIndex = 0;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        global $sheetTitle;
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
        foreach ($export_data_structure as $key => $value) {
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
        foreach ($export_data as $rowcount => $value) {
            $value = objectToArray($value);
            $rowcount += $rowIndex;
            foreach ($export_data_structure as $colcount => $value1) {
                $cellHeight = 15;
                $value1 = array_values($value1)[0];
                if(isset($value1['call_func']) && !empty($value1['call_func'])) {
                    $func_param = array();
                    foreach ($value1['func_param'] as $param) {
                        if(in_array($param, array_keys($value))) {
                            $func_param[] = $value[$param];
                        } else {
                            $func_param[] = $param;
                        }
                    }
                    $value[$value1['name']] = call_user_func_array($value1['call_func'], $func_param);
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

function draw_imge($img_http_path, $img_src_path, $extra_param = array()) {
    $html = '';
    $attr = get_attributes($extra_param);
    
    if(file_exists($img_src_path))
        $html = '<img src="'.$img_http_path.'" '.$attr.'>';
    return $html;
}

function createMenuActionConstants() {
    require_once(DIR_WS_MODEL.'AdminConstantsMaster.php');
    $adminConstantsMaster = new AdminConstantsMaster();

    $adminConstantsMaster->setSelect('admin_menu_action.constant_name, admin_menu_action.title');
    $adminConstantsMaster->setWhere('AND (section_menu_id = :section_menu_id', ADMIN_MENU_ID, 'int');
    $adminConstantsMaster->setWhere('OR section_menu_id = :section_menu_id_1)', 0, 'int');
    $adminConstantsMaster->setFrom('admin_menu_action');
    $constantsData = $adminConstantsMaster->exec_query();

    if(!empty($constantsData)) {
        foreach ($constantsData as $value) {
            define($value['constant_name'], $value['title']);
        }
    }
}

?>