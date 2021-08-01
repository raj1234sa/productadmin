<form method="POST" id="form_add_passenger">
    <div class="row">
        <div class="col-12">
            <?php echo draw_form_buttons('save,save_back,back', array('backUrl'=>DIR_HTTP_ADMIN.FILE_ADMIN_PASSENGER_LISTING)) ?>
        </div>
    </div>
    <?php
        $validation = array('required'=>COMMON_VALIDATE_REQUIRED);
        $email = array('email'=>COMMON_INVALID_EMAIL);
        $phone = array('phone'=>COMMON_INVALID_PHONE);
    ?>
    <div class="according accordion-s3 gradiant-bg mt-3">
        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#frm_passenger_reg" aria-expanded="true"><?php echo PASSENGER_PERSONAL_DETAILS ?></a>
            </div>
            <div id="frm_passenger_reg" class="collapse show">
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_FIRSTNAME, 'text', 'firstname', $passenger_data['firstname'], array('validation'=>$validation, 'error'=>$err['firstname'])) ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_LASTNAME, 'text', 'lastname', $passenger_data['lastname'], array('validation'=>$validation, 'error'=>$err['lastname'])) ?>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_EMAIL_ADDRESS, 'text', 'email', $passenger_data['email'], array('validation'=>array_merge($validation,$email), 'error'=>$err['email'])) ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_PASSWORD, 'password', 'password', $passenger_data['password'], array('validation'=>$validation, 'error'=>$err['password'])) ?>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_PHONE_NUMBER, 'text', 'phone', $passenger_data['phone'], array('validation'=>array_merge($validation,$phone), 'error'=>$err['phone'])) ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?php echo form_element(COMMON_STATUS, 'switchbutton', 'status', $passenger_data['status']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
$global_js .= <<<JS
$("#form_add_passenger").validate();
JS;
?>