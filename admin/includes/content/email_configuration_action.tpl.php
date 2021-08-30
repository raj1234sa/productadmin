<form method="POST" id="form_add_passenger">
    <div class="row">
        <div class="col-12">
            <?php echo drawFormButtons('save,save_back,back', DIR_HTTP_ADMIN.FILE_ADMIN_EMAIL_CONFIG_LISTING) ?>
        </div>
    </div>
    <?php
        $validation = array('required'=>COMMON_VALIDATE_REQUIRED);
        $email = array('email'=>COMMON_INVALID_EMAIL);
        $phone = array('phone'=>COMMON_INVALID_PHONE);
    ?>
    <div class="formrows mt-3">
        <?php
            $labelColClass = 2;

            echo formElement(EMAIL_TEMPLATE_KEY, 'label', '', $emailConfigData['constant_name']);

            echo formElement(EMAIL_TEMPLATE_SUBJECT, 'text', 'template_subject', $emailConfigData['template_subject'], 'medium', array('validation'=>$validation, 'error'=>$err['template_subject']));

            echo formElement(EMAIL_TEMPLATE_CONTENT, 'ckeditor', 'template_content', $emailConfigData['template_content'], 'none', array('error'=>$err['template_content']));

            echo formElement(COMMON_STATUS, 'switchbutton', 'status', $emailConfigData['status'], '');
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