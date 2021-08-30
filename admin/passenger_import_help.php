<?php

require_once('../lib/common.php');
require_once(DIR_WS_MODEL . 'BusStopsMaster.php');

$objBusStopsMaster = new BusStopsMaster();

$importHelp = $importSection = array();
$importSection = array(
    IMPORT_SHEET_DETAILS,
    COMMON_BUS_STOP,
);
$importHelp[0][] = array(
    'title' => COMMON_FIRSTNAME,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_LASTNAME,
    'validation' => '',
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_EMAIL_ADDRESS,
    'validation' => IMPORT_VALIDATION_EMAIL_PATTERN,
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_PASSWORD,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_PHONE_NUMBER,
    'validation' => '10 Digits inly',
    'type' => 'Numeric',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_STATUS,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'Possible valus(0, 1)',
    'default' => '1',
);
$importHelp[0][] = array(
    'title' => ADDRESS_LINE,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => ADDRESS_LINE2,
    'validation' => '',
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => AREA_NAME,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'String',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => ZIPCODE,
    'validation' => '6 Digits only',
    'type' => 'Numeric',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_BUS_STOP,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'Numeric',
    'default' => '',
    'description' => 'Enter bus stop id by referring help of Bus Stop'
);
$importHelp[0][] = array(
    'title' => COMMON_CITY,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'Numeric',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_STATE,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'Numeric',
    'default' => '',
);
$importHelp[0][] = array(
    'title' => COMMON_COUNTRY,
    'validation' => IMPORT_VALIDATION_REQUIRED,
    'type' => 'Numeric',
    'default' => '',
);

$busStopsDetails = $objBusStopsMaster->getBusStops();

?>
<div class="nav nav-tabs" id="nav-tab" role="tablist">
<?php
foreach ($importSection as $sectionId => $sectionHeading) {
    $id = str_replace(" ", "_", $sectionHeading);
    ?>
    <a class="nav-item nav-link <?= $sectionId == 0 ? 'active' : '' ?>" id="<?= $id ?>-tab" data-toggle="tab" href="#<?= $id ?>" role="tab" aria-controls="<?= $id ?>" aria-selected="true"><?= $sectionHeading ?></a>
    <?php
}

?>
</div>
<div class="tab-content mt-3" id="nav-tabContent">
<?php
foreach ($importSection as $sectionId => $sectionHeading) {
    $id = str_replace(" ", "_", $sectionHeading);
    ?>
    <div class="tab-pane fade <?= $sectionId == 0 ? 'show active' : '' ?>" id="<?= $id ?>" role="tabpanel" aria-labelledby="<?= $id ?>-tab">
        <?php
        if($sectionId == 0) {
            ?>
            <div class="table-responsive datatable-dark">
                <table class="table table-bordered dataTable" id="sheet_details">
                    <thead>
                        <tr>
                            <th><?= COMMON_FIELD_NAME ?></th>
                            <th><?= COMMON_DESCRIPTION ?></th>
                            <th class="text-center"><?= COMMON_VALIDATION ?></th>
                            <th class="text-center"><?= COMMON_TYPE ?></th>
                            <th class="text-center"><?= COMMON_DEFAULT_VALUE ?></th>
                        </tr>
                    </thead>
                <?php
                foreach ($importHelp[$sectionId] as $help) {
                    $help['validation'] = empty($help['validation']) ? "---" : $help['validation'];
                    $help['default'] = ($help['default'] == '') ? "---" : $help['default'];
                    $help['description'] = ($help['description'] == '') ? "---" : $help['description'];
                    echo '<tr>'.
                    '<td>'.$help['title'].'</td>'.
                    '<td>'.$help['description'].'</td>'.
                    '<td class="text-center">'.$help['validation'].'</td>'.
                    '<td class="text-center">'.$help['type'].'</td>'.
                    '<td class="text-center">'.$help['default'].'</td>'.
                    '</tr>';
                }
                ?>
                </table>
            </div>
            <?php
        } elseif($sectionId == 1) {
            ?>
            <div class="table-responsive datatable-dark">
                <table class="table table-bordered dataTable" id="bus_stop_tbl">
                    <thead>
                        <tr>
                            <th class="text-center"><?= COMMON_BUS_STOP ?></th>
                            <th class="text-center"><?= COMMON_ID ?></th>
                        </tr>
                    </thead>
                <?php
                foreach ($busStopsDetails as $stop) {
                    echo '<tr>'.
                    '<td class="text-center">'.$stop['stop_title'].'</td>'.
                    '<td class="text-center">'.$stop['stop_id'].'</td>'.
                    '</tr>';
                }
                ?>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
</div>