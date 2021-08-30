<form method="POST" id="form_add_passenger">
    <div class="row">
        <div class="col-12">
            <?php echo drawFormButtons('save,save_back,back', DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING) ?>
        </div>
    </div>
    <?php
        $validation = array('required' => COMMON_VALIDATE_REQUIRED);
        $email = array('email' => COMMON_INVALID_EMAIL);
        $phone = array('phone' => COMMON_INVALID_PHONE);
        $zipcode = array('digits' => VALIDATE_ONLY_DIGITS);
        $labelColClass = 3;
    ?>

    <div class="dummy_accordian" data-id="frm_passenger_reg" data-title="<?php echo PASSENGER_PERSONAL_DETAILS ?>">
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo formElement(COMMON_FIRSTNAME, 'text', 'firstname', $formData['firstname'], '', array('validation' => $validation, 'error' => $err['firstname'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo formElement(COMMON_LASTNAME, 'text', 'lastname', $formData['lastname']) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?php echo formElement(COMMON_EMAIL_ADDRESS, 'text', 'email', $formData['email'], '', array('validation' => array_merge($validation, $email), 'error' => $err['email'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group row no-gutters pa-input">
                    <div class="col-12 col-md-3">
                        <label class="m-0 col-form-label" for="set"><?= COMMON_PASSWORD ?></label>
                    </div>
                    <div class="col-12 col-md-7">
                        <?php
                        if (!empty($pid)) {
                            $list = array(
                                'no' => 'Leave Unchanged (<a href="#" data-toggle="modal" data-target="#show_password">'.PASSENGER_SHOW_PASSWORD.'</a>)',
                                'new' => 'Set to'
                            );
                            echo formRadio('new_password', 'no', array('list' => $list));
                        }
                        ?>
                        <div class="password_div">
                            <?=
                                formPassword('password', '', array('validation' => $validation, 'error' => $err['password']))
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?= formElement(COMMON_PHONE_NUMBER, 'text', 'phone', $formData['phone'], '', array('validation' => array_merge($validation, $phone), 'error' => $err['phone'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= formElement(COMMON_STATUS, 'switchbutton', 'status', $formData['status'], '') ?>
            </div>
        </div>
    </div>

    <div class="dummy_accordian" data-id="frm_passenger_reg_address" data-title="<?= COMMON_ADDRESS_DETAILS ?>">
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?= formElement(ADDRESS_LINE, 'text', 'address_line', $formData['address_line'], '', array('validation' => $validation, 'error' => $err['address_line'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= formElement(ADDRESS_LINE2, 'text', 'address_line2', $formData['address_line2']) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?= formElement(AREA_NAME, 'text', 'area_name', $formData['area_name'], '', array('validation' => $validation, 'error' => $err['area_name'])) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= formElement(ZIPCODE, 'text', 'zipcode', $formData['zipcode'], '', array('validation' => array_merge($validation, $zipcode), 'error' => $err['zipcode'])) ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6" id="bus_stop_div" data-selected="<?= $formData['bus_stop_id'] ?>">
            </div>
            <div class="col-12 col-md-6">
                <?=
                    formHidden('city', $formData['city']).
                    formElement(COMMON_CITY, 'label', '', $formData['city_name'])
                ?>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-md-6">
                <?=
                    formHidden('state', $formData['state']).
                    formElement(COMMON_STATE, 'label', '', $formData['state_name'])
                ?>
            </div>
            <div class="col-12 col-md-6">
                <?=
                    formHidden('country', $formData['country']).
                    formElement(COMMON_COUNTRY, 'label', '', $formData['country_name'])
                ?>
            </div>
        </div>
    </div>
</form>
<?php
if($SITE_VAR_PASSENGER_SHOW_PASSWORD && !empty($pid)) {
    $labelColClass = 3;
?>
<div class="modal fade" id="show_password">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= PASSENGER_LOGIN_DETAILS ?></h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <?=
                    formElement(COMMON_EMAIL_ADDRESS, 'label', '', $formData['email'], '', array('element_class'=>'email_modal')).
                    formElement(COMMON_PASSWORD, 'label', '', $enc->decrypt($formData['password']), '', array('element_class'=>'password_modal'));
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= COMMON_CLOSE ?></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>
    var FILE_ADMIN_PASSENGER_EDIT = "<?php echo FILE_ADMIN_PASSENGER_EDIT ?>";
</script>
<?php
$globalJs .= <<<JS
    $(function() {
        $("#form_add_passenger").paValidate();
    });
JS;
?>