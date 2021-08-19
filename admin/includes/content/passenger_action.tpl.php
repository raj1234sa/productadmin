<form method="POST" id="form_add_passenger">
    <div class="row">
        <div class="col-12">
            <?php echo draw_form_buttons('save,save_back,back', array('backUrl' => DIR_HTTP_ADMIN . FILE_ADMIN_PASSENGER_LISTING)) ?>
        </div>
    </div>
    <?php
    $validation = array('required' => COMMON_VALIDATE_REQUIRED);
    $email = array('email' => COMMON_INVALID_EMAIL);
    $phone = array('phone' => COMMON_INVALID_PHONE);
    $zipcode = array('digits' => VALIDATE_ONLY_DIGITS);
    ?>

    <div class="dummy_accordian" data-id="frm_passenger_reg" data-title="<?php echo PASSENGER_PERSONAL_DETAILS ?>">
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo form_element(COMMON_FIRSTNAME, 'text', 'firstname', $passengerData['firstname'], '', array('validation' => $validation, 'error' => $err['firstname'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_element(COMMON_LASTNAME, 'text', 'lastname', $passengerData['lastname'], '', array('validation' => $validation, 'error' => $err['lastname'])) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo form_element(COMMON_EMAIL_ADDRESS, 'text', 'email', $passengerData['email'], '', array('validation' => array_merge($validation, $email), 'error' => $err['email'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group row no-gutters pa-input">
                    <div class="col-12 col-md-3">
                        <label class="m-0 col-form-label" for="set"><?php echo COMMON_PASSWORD ?></label>
                    </div>
                    <div class="col-12 col-md-7">
                        <?php
                        if (!empty($pid)) {
                            echo form_radio('new_password', 'no', array('list' => array('no' => 'Leave Unchanged', 'new' => 'Set to')));
                        }
                        ?>
                        <div class="password_div">
                            <?php
                            echo form_password('password', '', array('validation' => $validation, 'error' => $err['password']));
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($pid)) {
                    // echo form_element(COMMON_PASSWORD, 'radio', 'set', '', '', array('list' => array('no' => 'Leave Unchanged', 'new' => 'Set to')));
                }
                // echo form_element(COMMON_PASSWORD, 'password', 'password', $passengerData['password'], '', array('validation' => $validation, 'error' => $err['password']));
                ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo form_element(COMMON_PHONE_NUMBER, 'text', 'phone', $passengerData['phone'], '', array('validation' => array_merge($validation, $phone), 'error' => $err['phone'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_element(COMMON_STATUS, 'switchbutton', 'status', $passengerData['status'], '') ?>
            </div>
        </div>
    </div>

    <div class="dummy_accordian" data-id="frm_passenger_reg_address" data-title="<?php echo COMMON_ADDRESS_DETAILS ?>">
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo form_element(ADDRESS_LINE, 'text', 'address_line', $passengerData['address_line'], '', array('validation' => $validation, 'error' => $err['address_line'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_element(ADDRESS_LINE2, 'text', 'address_line2', $passengerData['address_line2']) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo form_element(AREA_NAME, 'text', 'area_name', $passengerData['area_name'], '', array('validation' => $validation, 'error' => $err['area_name'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_element(ZIPCODE, 'text', 'zipcode', $passengerData['zipcode'], '', array('validation' => array_merge($validation, $zipcode), 'error' => $err['zipcode'])) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6" id="bus_stop_div" data-selected="<?php echo $passengerData['bus_stop_id'] ?>">
            </div>
            <div class="col-12 col-md-6">
                <?php
                echo form_hidden('city', $passengerData['city']);
                echo form_element(COMMON_CITY, 'label', '', $passengerData['city_name']);
                ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php
                echo form_hidden('state', $passengerData['state']);
                echo form_element(COMMON_STATE, 'label', '', $passengerData['state_name']);
                ?>
            </div>
            <div class="col-12 col-md-6">
                <?php
                echo form_hidden('country', $passengerData['country']);
                echo form_element(COMMON_COUNTRY, 'label', '', $passengerData['country_name']);
                ?>
            </div>
        </div>
    </div>
</form>
<script>
    var FILE_ADMIN_PASSENGER_EDIT = "<?php echo FILE_ADMIN_PASSENGER_EDIT ?>";
</script>
<?php
$globalJs .= <<<JS
    $("#form_add_passenger").validate();
JS;
?>