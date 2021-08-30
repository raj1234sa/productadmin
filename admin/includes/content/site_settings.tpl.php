<form method="POST">
    <div class="row">
        <div class="col-12">
            <?php echo drawFormButtons('save', DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING) ?>
        </div>
    </div>
    <div class="d-md-flex mt-3">
        <div class="nav flex-column nav-pills mr-4 mb-3 mb-sm-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <?php
            $counter = 0;
            foreach ($tabs as $s => $label) {
            ?>
                <a class="nav-link <?php echo ($counter == 0) ? 'active show' : '' ?>" id="v-pills-<?php echo $s ?>-tab" data-toggle="pill" href="#v-pills-<?php echo $s ?>" role="tab" aria-controls="v-pills-<?php echo $s ?>" aria-selected="true"><?php echo $label ?></a>
            <?php
                $counter++;
            }
            ?>
        </div>
        <div class="tab-content w-100" id="v-pills-tabContent">
            <?php
            $counter = 0;
            foreach ($tabs as $s => $label) {
            ?>
                <div class="tab-pane fade <?php echo ($counter == 0) ? 'active show' : '' ?>" id="v-pills-<?php echo $s ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $s ?>-tab">
                    <div class="table-responsive datatable-dark">
                        <table class="table table-bordered dataTable settings_dataTable" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Setting Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($settingTabsArray[$label] as $setting) {
                                ?>
                                    <tr>
                                        <td>
                                            <?= $setting['title'] ?><br>
                                            <small class="help"><?= $setting['help'] ?></small>
                                        </td>
                                        <td width="50%">
                                            <?php
                                            $inputType = $setting['input_type'];
                                            if ($inputType == 'dropdown') {
                                                $inputType = 'select';
                                            }
                                            $selectedValue = (!empty($setting['set_value'])) ? $setting['set_value'] : $setting['default_value'];

                                            $extraArr = array();
                                            if ($inputType == 'text') {
                                                $extraArr['element_class'] = 'w-75';
                                            } else {
                                                // $extraArr['element_class'] = 'selectpicker';
                                                $keys = explode(',', $setting['key_values']);
                                                $displayValues = explode(',', $setting['display_values']);
                                                $list = array();
                                                foreach ($keys as $k => $value) {
                                                    $list[$keys[$k]] = $displayValues[$k];
                                                }
                                                $extraArr['list'] = $list;
                                                $extraArr['element_class'] = 'w-50';
                                            }

                                            $options = array(
                                                'stconfig[' . $setting['setting_id'] . ']',
                                                $selectedValue,
                                                $extraArr,
                                            );
                                            echo call_user_func_array('form' . ucfirst($inputType), $options);
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
                $counter++;
            }
            ?>
        </div>
    </div>
</form>
<?php
$globalJs .= <<<JS
    $("#dataTable").PATable({
        "serverSide": false,
        "search": true,
        "sort": false,
    });
JS;
?>