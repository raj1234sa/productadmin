<form method="POST" id="form_add_passenger">
    <div class="row">
        <div class="col-12">
            <?php echo draw_form_buttons('save,save_back,back', array('backUrl'=>DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING)) ?>
        </div>
    </div>
    <?php
        $validation = array('required'=>COMMON_VALIDATE_REQUIRED);
        $email = array('email'=>COMMON_INVALID_EMAIL);
        $phone = array('phone'=>COMMON_INVALID_PHONE);
    ?>
    <div class="formrows mt-3">
        <?php
            $label_col_class = 2;

            echo form_element(EMAIL_TEMPLATE_KEY, 'label', '', $email_config_data['constant_name']);

            echo form_element(EMAIL_TEMPLATE_SUBJECT, 'text', 'template_subject', $email_config_data['template_subject'], '', array('validation'=>$validation, 'error'=>$err['template_subject']));

            echo form_element(EMAIL_TEMPLATE_CONTENT, 'ckeditor', 'template_content', $email_config_data['template_content'], '', array('error'=>$err['template_content']));

            echo form_element(COMMON_STATUS, 'switchbutton', 'status', $email_config_data['status'], '');
        ?>
    </div>
</form>
<?php
$globalJs .= <<<JS
$("#form_add_passenger").validate();
JS;
?>