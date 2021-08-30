<?php
/**
 * Draw action buttons
 * @param array $actionButtons
 * @return string
*/
function drawActionButtons($actionButtons) {
    $availableActionLinks = getAvailableActions();
    $html = '<div class="text-right action-buttons-div">';
    foreach ($actionButtons as $key => $item) {
        if(!array_key_exists($key, $availableActionLinks)) { continue; }
        $html .= "<a href=" . $item['link'] . " class='btn {$item['class']}'>
                    <i class='{$item['icon']} pa-icon'></i>
                    $key
                </a>";
    }
    $html .= "</div>";
    return $html;
}

/**
 * Draw form buttons
 * @param string $submitButtons
 * @param string $backUrl
 * @return string
 */
function drawFormButtons($submitButtons, $backUrl = null) {
    $submitButtons = explode(',', $submitButtons);
    $html = '<div class="float-right form_button_div">';
    foreach ($submitButtons as $value) {
        switch ($value) {
            case 'save':
                $html .= '<button type="submit" name="submit_btn" value="'.COMMON_SAVE.'" class="btn btn-sm btn-info formsubmit mr-1" id="formSubmit">
                            <i class="pa-icon ti-save"></i>
                            '.COMMON_SAVE.'
                        </button>';
                break;
            case 'back':
                if (isset($backUrl) && !empty($backUrl))
                    $html .= '<a class="btn btn-sm btn-secondary mr-1" href="' . $backUrl . '">
                            <i class="pa-icon fa fa-arrow-left"></i>
                            Back
                        </a>';
                break;
            case 'save_back':
                $html .= '<button type="submit" name="submit_btn" value="'.COMMON_SAVE_BACK.'" class="btn btn-sm btn-info formsubmit mr-1" id="formSubmitBack">
                            <i class="pa-icon ti-save"></i>
                            '.COMMON_SAVE_BACK.'
                        </button>';
                break;
        }
    }
    $html .= '<button class="btn btn-sm btn-secondary" type="reset" id="formReset">
                <i class="pa-icon fa fa-history"></i>
                '.COMMON_RESET.'
            </button>';
    $html .= '</div>';
    return $html;
}

/**
 * Draw action menu button in listing
 * @param array $actionLinks
 * @return string
 */
function drawActionMenu($actionLinks) {
    $availableActionLinks = getAvailableActions();
    $count = 0;
    $html = '<div class="table_action_buttons">';
    if(count($actionLinks) > 3) {
        $html .= '<div class="dropdown">
                    <button data-toggle="dropdown" class="btn btn-sm btn-secondary dropdown-toggle" aria-expanded="false">
                        '.COMMON_ACTION.'
                    </button>
                    <div class="dropdown-menu dropdown-menu-right action-menu">';
        foreach ($actionLinks as $key => $value) {
            if(!array_key_exists($key, $availableActionLinks)) { continue; }
            $class = $extra = '';
            if (isset($value['class'])) {
                $class = $value['class'];
            }
            if(isset($value['extra'])) {
                $extra = $value['extra'];
            }
            $html .= "<a href='{$value['link']}' class='dropdown-item $class' $extra><i class='{$value['icon']} pa-icon'></i>$key</a>";
            $count++;
        }
        $html .= '</div></div>';
    } else {
        foreach ($actionLinks as $key => $value) {
            if(!array_key_exists($key, $availableActionLinks)) { continue; }
            $class = $extra = '';
            if (isset($value['class'])) {
                $class = $value['class'];
            }
            if(isset($value['extra'])) {
                $extra = $value['extra'];
            }
            $html .= "<a href='{$value['link']}' data-toggle='tooltip' data-placement='top' title='".$key."' class='btn btn-sm $class' $extra>
            <i class='{$value['icon']}'></i></a>";
            $count++;
        }
    }
    $html .= '</div>';
    if($count == 0) {
        $html = '---';
    }
    return $html;
}

/**
 * Draw different types of input fields
 * @param string $label
 * @param string $type (text, password, number, switchbutton, file, label, select, radio, ckeditor, textarea)
 * @param string $name
 * @param string $value
 * @param string $class (mini, small, medium, large, none)
 * @param array $extra
 * @return string
*/
function formElement($label, $type='text', $name, $value = '', $class = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $frmGrpClasses = 'form-group row no-gutters';
    $frmGrpClasses .= $extra['form_group_class'];
    if($type != 'switchbutton') {
        $frmGrpClasses .= ' pa-input';
    }

    $colClasses = getExtraClasses($class);

    $labelColClass = $colClasses['label'];
    $elementColClass = $colClasses['input'];

    if($type == 'select') {
        $frmGrpClasses = str_replace('form-group', '', $frmGrpClasses);
    }

    $return .= '<div class="'.$frmGrpClasses.'">';
    if(!empty($label)) {
        $return .= '<div class="col-12 col-md-'.$labelColClass.'"><label class="m-0 col-form-label" for="'.$id.'">'.$label.'</label></div>';
    } else {
        $elementColClass = 12;
    }
    $attrStr = getAttributes($extra);
    $args = func_get_args();
    array_shift($args);
    $extraClasses = '';
    switch ($type) {
        case 'text':
        case 'password':
        case 'number':
        case 'switchbutton':
        case 'file':
        case 'label':
        case 'select':
        case 'radio':
            array_shift($args);
            unset($args[2]);
            if($type == 'switchbutton') {
                $extraClasses .= ' form-switch';
            }
            if($type == 'number') {
                $elementColClass = 1;
            }
            $return .= '<div class="col-12 col-md-'.$elementColClass.' '.$extraClasses.'">';
            $return .= call_user_func_array('form'.ucfirst($type), $args);
            $return .= '</div>';
            break;
        case 'ckeditor':
            array_shift($args);
            unset($args[2]);
            $return .= '<div class="col-12 col-md-'.$elementColClass.' '.$extraClasses.'">';
            $return .= call_user_func_array('form'.ucfirst($type), $args);
            $return .= '</div>';
            break;
        case 'textarea':
            $extraClass = '';
            if($extra['autosize'] == true) {
                $extraClass .= "autosize-transition";
            }
            $rows = "rows='5'";
            if(!empty($extra['rows'])) {
                $rows = "rows='".$extra['rows']."'";
            }
            $cols = "cols='50'";
            if(!empty($extra['cols'])) {
                $cols = "cols='".$extra['cols']."'";
            }
            $return .= '<div class="col-sm-5">';
            $return .= '<textarea id="'.$id.'" name="'.$name.'" class="form-control '.$extraClass.'" '.$rows.' '.$cols.' '.$attrStr.'>'.$value.'</textarea>';
            $return .= '</div>';
            break;
        case 'datepicker':
            $return .= '<div class="col-sm-5">';
            $return .= '<div class="input-group col-xs-10 col-sm-5">';
            $return .= '<input class="form-control date-picker '.$elementClasses.'" id="'.$id.'" name="'.$name.'" type="text" data-date-format="'.SITE_DATE_FORMAT.'" value="'.$value.'" '.$attrStr.' />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar pa-icon"></i>
                        </span>';
            $return .= '</div>';
            $return .= '</div>';
            break;
    }
    $return .= '</div>';
    return $return;
}

/**
 * Draw ckeditor input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formCkeditor($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = '';
    $elementClasses = $extra['element_class'];
    $frmGrpClasses = $extra['form_group_class'];
    $validation = $extra['validation'];
    if(!empty($validation)) {
        if($validation) {
            foreach ($validation as $key => $value1) {
                $extra['data-validation-'.$key] = $value1;
            }
        }
    }
    if(isset($extra['error']) && !empty($extra['error'])) {
        $extra['data-error'] = $extra['error'];
    }
    $attrStr = getAttributes($extra);
    
    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= '<textarea name="'.$name.'" id="'.$id.'" class="form-ckeditor '.$elementClasses.'" '.$attrStr.'>';
    $return .= $value;
    $return .= '</textarea>';
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

/**
 * Draw text input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formText($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = str_replace(array('[',']'), '_', $name);
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = '';
    $elementClasses = $extra['element_class'];
    $frmGrpClasses = $extra['form_group_class'];
    $validation = $extra['validation'];
    if(!empty($validation)) {
        if($validation) {
            foreach ($validation as $key => $value1) {
                $extra['data-validation-'.$key] = $value1;
            }
        }
    }
    if(isset($extra['error']) && !empty($extra['error'])) {
        $extra['data-error'] = $extra['error'];
    }
    $attrStr = getAttributes($extra);

    // $extraParams = '';
    // if($type == 'number') {
    //     $extraParams .= 'data-type="number" class="only-number"';
    //     $type = 'text';
    // }
    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= "<input type='text' id='".$id."' name='".$name."' class='form-control ".$elementClasses."' value='".$value."' ".$attrStr." />";
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

/**
 * Draw number input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formNumber($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = str_replace(array('[',']'), '_', $name);
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = '';
    $elementClasses = $extra['element_class'];
    $frmGrpClasses = $extra['form_group_class'];
    $validation = $extra['validation'];
    if(!empty($validation)) {
        if($validation) {
            foreach ($validation as $key => $value1) {
                $extra['data-validation-'.$key] = $value1;
            }
        }
    }
    if(isset($extra['error']) && !empty($extra['error'])) {
        $extra['data-error'] = $extra['error'];
    }
    $attrStr = getAttributes($extra);

    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= "<input type='number' id='".$id."' name='".$name."' class='form-control ".$elementClasses."' value='".$value."' ".$attrStr." />";
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

/**
 * Draw password input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formPassword($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = '';
    $elementClasses = $extra['element_class'];
    $frmGrpClasses = $extra['form_group_class'];
    $validation = $extra['validation'];
    if(!empty($validation)) {
        if($validation) {
            foreach ($validation as $key => $value1) {
                $extra['data-validation-'.$key] = $value1;
            }
        }
    }
    $attrStr = getAttributes($extra);

    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= "<input type='password' id='".$id."' name='".$name."' class='form-control ".$elementClasses."' value='".$value."' ".$attrStr." />";
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

/**
 * Draw file input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formFile($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $attrStr = getAttributes($extra);

    $return .= '<div class="upload_file_div">';
    $return .= '<input type="file" class="form-hide" id="'.$id.'" '.$attrStr.' data-filename="'.$value.'">';
    $return .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
    $return .= '<div><button type="button" class="btn btn-success upload_file" data-trigger="#'.$id.'">
                    <i class="fa fa-upload pa-icon"></i>
                    '.COMMON_UPLOAD_FILE.'
                </button></div>';
    if(isset($extra['html']) && $extra['html']) {
        $return .= $extra['html'];
    }
    $return .= '</div>';
    return $return;
}

/**
 * Draw switchbutton input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formSwitchbutton($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $elementClasses = $extra['element_class'];
    $attrStr = getAttributes($extra);

    $extraParams = '';
    if($value) {
        $extraParams .= ' checked';
    }
    $return .= '<div class="switch">
                    <input id="'.$id.'" class="cmn-toggle cmn-toggle-round '.$elementClasses.'" '.$extraParams.' type="checkbox" name="'.$name.'" value="1" '.$attrStr.'>
                    <label for="'.$id.'"></label>
                </div>';
    return $return;
}

/**
 * Draw label input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formLabel($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = $extra['element_class'];
    $attrStr = getAttributes($extra);

    $return .= '<label for="'.$id.'" class="m-0 '.$elementClasses.'" '.$attrStr.'>'.$value.'</label>';
    return $return;
}

/**
 * Draw select input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formSelect($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $list = $extra['list'];
    $elementClasses = $extra['element_class'];
    $frmGrpClasses = $extra['form_group_class'];
    $validation = $extra['validation'];
    if(!empty($validation)) {
        if($validation) {
            foreach ($validation as $key => $value1) {
                $extra['data-validation-'.$key] = $value1;
            }
        }
    }
    $attrStr = getAttributes($extra);
    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= '<select class="'.$elementClasses.'" '.$extra['attributes'].' id="'.$id.'" name="'.$name.'" '.$attrStr.'">';
    $return .= drawOptions($list, $extra['value_field'], $extra['text_field'], $value, $extra['list_before'], $extra);
    $return .= '<select>';
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

/**
 * Draw radio input
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formRadio($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $list = $extra['list'];
    $attrStr = getAttributes($extra);
    $elementClasses = $extra['element_class'];

    $count = 0;
    foreach ($list as $key => $field) {
        $valueField = !isset($extra['value_field']) ? $key : $field[$extra['value_field']];
        $textField = !isset($extra['text_field']) ? $field : $field[$extra['text_field']];
        if($value == '' && $count == 0) {
            $value = $valueField;
        }
        $select = ($value == $valueField) ? "checked" : '';
        $return .= '<div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="'.$valueField.'" id="'.$name.'_'.$key.'" name="'.$name.'" class="custom-control-input" '.$select.'>
                        <label class="custom-control-label" for="'.$name.'_'.$key.'">'.$textField.'</label>
                    </div>';
        $count++;
    }
    return $return;
}

/**
 * Draw options for select input
 * @param array $list
 * @param string $valueField
 * @param string $textField
 * @param string $selected
 * @param string $before
 * @return string
*/
function drawOptions($list, $valueField, $textField, $selected, $before = null, $extra = array()) {
    $html = '';
    if(isset($before) || !empty($before)) {
        $html .= $before;
    }
    foreach ($list as $key=>$value) {
        $valueField = !isset($extra['value_field']) ? $key : $value[$extra['value_field']];
        $textField = !isset($extra['text_field']) ? $value : $value[$extra['text_field']];
        $select = ($selected == $valueField) ? "selected='selected'" : '';
        $attr = $extra['selectpicker-content'] ? "data-content='".$textField."'" : '';
        $icon = $extra['selectpicker-icon'] ? "data-icon='".$textField['icon']."'" : '';
        $text = $textField;
        if($extra['selectpicker-icon']) {
            $text = $textField['text'];
        }
        $html .= "<option $icon $attr value='" . $valueField . "' $select>" . $text . "</option>";
    }
    return $html;
}

/**
 * Draw hidden input field
 * @param string $name
 * @param string $value
 * @param array $extra
 * @return string
*/
function formHidden($name, $value = '', $extra_param = array()) {
    $type = 'hidden';
    $id = !empty($id) ? $extra_param['id'] : $name;
    if($name) {
        return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'">';
    }
}

/**
 * Draw buttons
 * @param string $type
 * @param string $title
 * @param string $classes
 * @param array $extra
 * @return string
*/
function formButton($type, $title, $classes, $extraParams = array()) {
    $attrStr = getAttributes($extraParams);
    $return = '<button type="'.$type.'" class="btn '.$classes.'" '.$attrStr.'>'.$title.'</button>';
    return $return;
}

/**
 * Get form element attributes string
 * @param array $extra
 * @return string
*/
function getAttributes($extra) {
    unset($extra['list']);
    unset($extra['list_before']);
    unset($extra['value_field']);
    unset($extra['text_field']);
    unset($extra['element_class']);
    unset($extra['validation']);
    unset($extra['error']);
    unset($extra['selected']);
    unset($extra['form_group_class']);
    unset($extra['html']);
    unset($extra['preview']);
    if($extra['searchdropdown']) {
        $extra['data-live-search'] = 'true';
    }
    unset($extra['searchdropdown']);
    $return = '';
    foreach ($extra as $key => $value) {
        $return .= " $key='$value' ";
    }
    return $return;
}

/**
 * Get label and element col classes
 * @param string $className
 * @return array
*/
function getExtraClasses($className) {
    global $labelColClass;
    if($labelColClass == '') {
        $labelColClass = 2;
    }
    switch ($className) {
        case 'mini':
            return array('label'=>$labelColClass, 'input'=>1);
            break;
        case 'small':
            return array('label'=>$labelColClass, 'input'=>3);
            break;
        case 'medium':
            return array('label'=>$labelColClass, 'input'=>5);
            break;
        case 'large':
            return array('label'=>$labelColClass, 'input'=>9);
            break;
        default:
            return array('label'=>$labelColClass, 'input'=>7);
            break;
    }
}