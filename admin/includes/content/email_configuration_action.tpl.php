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

            echo form_element(EMAIL_TEMPLATE_SUBJECT, 'text', 'template_subject', $email_config_data['template_subject'], 'medium', array('validation'=>$validation, 'error'=>$err['template_subject']));

            echo form_element(EMAIL_TEMPLATE_CONTENT, 'ckeditor', 'template_content', $email_config_data['template_content'], 'none', array('error'=>$err['template_content']));

            echo form_element(COMMON_STATUS, 'switchbutton', 'status', $email_config_data['status'], '');
        ?>
    </div>
</form>
<div class="dummy_accordian" data-id="site_variables" data-show-icon="false" data-title="Site Variables" data-theme="gradient_purple">
    <table class="table table-bordered">
        <tbody>
            <?php foreach($emailVariablesData as $value) { ?>
                <tr>
                    <td><?php echo constant($value['constant_name']) ?></td>
                    <td>
                        <?php echo $value['variable_name'] ?>
                        <button class="btn btn-sm fa fa-copy copy_variable" data-toggle="tooltip" data-placement="top" title="<?php echo COPY_VARIABLE_NAME ?>" data-copy="<?php echo $value['variable_name'] ?>"></button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
$globalJs .= <<<JS
$("#form_add_passenger").validate();
JS;
?>