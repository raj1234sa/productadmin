<form method="POST" id="form_add_menulink">
    <div class="row">
        <div class="col-12">
            <?php echo drawFormButtons('save,save_back,back', DIR_HTTP_ADMIN . FILE_ADMIN_MENU_LINKS_LISTING) ?>
        </div>
    </div>
    <?php
        $validation = array('required' => COMMON_VALIDATE_REQUIRED);
        $email = array('email' => COMMON_INVALID_EMAIL);
        $phone = array('phone' => COMMON_INVALID_PHONE);
        $zipcode = array('digits' => VALIDATE_ONLY_DIGITS);
    ?>

    <div class="formrows mt-3">
        <?php
            echo formElement(COMMON_TITLE, 'text', 'menu_title', $formData['menu_title'], 'medium', array('validation'=>$validation));

            $locationArr = array(
                'h' => COMMON_HEADER,
                'f' => COMMON_FOOTER,
            );
            echo formElement(LINK_LOCATION, 'radio', 'link_location', $formData['link_location'], '', array('list'=>$locationArr));

            echo formElement(MENU_PAGE_LINK, 'text', 'page_link', $formData['page_link'], 'small', array('validation'=>$validation));

            $displayArr = array(
                'b' => COMMON_BOTH,
                't' => MENU_ONLY_TEXT,
                'i' => MENU_ONLY_ICON,
            );
            echo formElement(COMMON_DISPLAY, 'radio', 'display', $formData['display'], '', array('list'=>$displayArr));
        ?>
        <div class="offset-2 form-group icon_div">
        <?php
            $iconArr = array(
                'fa fa-amazon' => array(
                    'icon'=>"fa fa-amazon",
                    'text'=>"fa fa-amazon",
                )
            );
            echo formElement('', 'select', 'icon_class', $formData['icon_class'], '', array('list'=>$iconArr, 'selectpicker-icon'=>true, 'element_class'=>'selectpicker', 'validation'=>$validation, 'searchdropdown'=>true, 'list_before'=>"<option value=''>Select Icon</option>"));
        ?>
        </div>
        <?php
            echo formElement(COMMON_SORT_ORDER, 'number', 'sort_order', $formData['sort_order']);

            echo formElement(COMMON_STATUS, 'switchbutton', 'status', $formData['status']);
        ?>
    </div>
</form>
<?php
$globalJs .= <<<JS
    $("#form_add_menulink").paValidate();
JS;
?>