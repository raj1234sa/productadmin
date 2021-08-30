<div id="passenger_import_wiz" class="smartwizard">
    <ul class="nav">
        <li>
            <a class="nav-link" href="#step-1"><?= IMPORT_DOWNLOAD_STEP_HEADER ?></a>
        </li>
        <li>
            <a class="nav-link" href="#step-2"><?= IMPORT_HELP_STEP_HEADER ?></a>
        </li>
        <li>
            <a class="nav-link" href="#step-3"><?= IMPORT_FILE_STEP_HEADER ?></a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="step-1" class="tab-pane text-center" role="tabpanel">
            <a href="<?= FILE_ADMIN_SAMPLE_DOWNLOAD.'?action=passenger' ?>" class="btn btn-success"><i class="fa fa-download pa-icon"></i><?= COMMON_DOWNLOAD_FILE ?></a>
        </div>
        <div id="step-2" class="tab-pane" role="tabpanel">
            <?php require_once(FILE_ADMIN_PASSENGER_IMPORT_HELP); ?>
        </div>
        <div id="step-3" class="tab-pane" role="tabpanel">
            <div class="d-flex justify-content-center">
                <?php
                $allowedExt = IMPORT_DOWNLOAD_EXT;
                echo formFile('file_upload', '', array('data-ajax' => DIR_HTTP_ADMIN . FILE_ADMIN_AJAX_UPLOADER, 'data-src-path' => DIR_WS_TEMP_IMAGES, 'data-http-path' => DIR_HTTP_TEMP_IMAGES, 'form_group_class' => 'no-gutters', 'id' => 'file_upload', 'data-allowed-ext' => $allowedExt));
                ?>
            </div>
            <div class="text-center text-danger d-none file_valid_msg"><?= COMMON_VALIDATE_REQUIRED ?></div>
        </div>
    </div>
</div>