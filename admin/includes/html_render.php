<?php
function draw_action_buttons($actionButtons) {
    $availableActionLinks = get_available_actions();
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
 * @param string $submitButtons
 * @param array $extra
 * @return string
 */
function draw_form_buttons($submitButtons, $extra = array()) {
    $submitButtons = explode(',', $submitButtons);
    $html = '<div class="float-right">';
    foreach ($submitButtons as $value) {
        switch ($value) {
            case 'save':
                $html .= '<button type="submit" name="submit_btn" value="'.COMMON_SAVE.'" class="btn btn-sm btn-info formsubmit mr-1" id="formSubmit">
                            <i class="pa-icon ti-save"></i>
                            '.COMMON_SAVE.'
                        </button>';
                break;
            case 'back':
                if (isset($extra['backUrl']) && !empty($extra['backUrl']))
                    $html .= '<a class="btn btn-sm btn-secondary mr-1" href="' . $extra['backUrl'] . '">
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

function draw_action_menu($actionLinks) {
    $availableActionLinks = get_available_actions();
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
            $class = '';
            if (isset($value['class'])) {
                $class = $value['class'];
            }
            $html .= "<a href='{$value['link']}' class='dropdown-item $class'><i class='{$value['icon']} pa-icon'></i>$key</a>";
            $count++;
        }
        $html .= '</div></div>';
    } else {
        foreach ($actionLinks as $key => $value) {
            if(!array_key_exists($key, $availableActionLinks)) { continue; }
            $class = '';
            if (isset($value['class'])) {
                $class = $value['class'];
            }
            $html .= "<a href='{$value['link']}' data-toggle='tooltip' data-placement='top' title='".$key."' class='btn btn-sm $class'>
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

function form_element($label, $type='text', $name, $value = '', $class = '', $extra = array()) {
    global $label_col_class;
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
    if($label_col_class != '') {
        $labelColClass = $label_col_class;
    }

    if($type == 'select') {
        $frmGrpClasses = str_replace('form-group', '', $frmGrpClasses);
    }

    $return .= '<div class="'.$frmGrpClasses.'">';
    if(!empty($label)) {
        $return .= '<div class="col-12 col-md-'.$labelColClass.'"><label class="m-0 col-form-label" for="'.$id.'">'.$label.'</label></div>';
    } else {
        $elementColClass = 12;
    }
    $attrStr = get_attributes($extra);
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
            $return .= '<div class="col-12 col-md-'.$elementColClass.' '.$extraClasses.'">';
            $return .= call_user_func_array('form_'.$type, $args);
            $return .= '</div>';
            break;
        case 'ckeditor':
            array_shift($args);
            unset($args[2]);
            $return .= '<div class="col-12 col-md-'.$elementColClass.' '.$extraClasses.'">';
            $return .= call_user_func_array('form_'.$type, $args);
            $return .= '</div>';
            break;
        case 'textarea':
            $extra_class = '';
            if($extra['autosize'] == true) {
                $extra_class .= "autosize-transition";
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
            $return .= '<textarea id="'.$id.'" name="'.$name.'" class="form-control '.$extra_class.'" '.$rows.' '.$cols.' '.$attrStr.'>'.$value.'</textarea>';
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

function form_ckeditor($name, $value = '', $extra = array()) {
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
    $attrStr = get_attributes($extra);
    
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

function form_text($name, $value = '', $extra = array()) {
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
    $attrStr = get_attributes($extra);

    // $extra_params = '';
    // if($type == 'number') {
    //     $extra_params .= 'data-type="number" class="only-number"';
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

function form_password($name, $value = '', $extra = array()) {
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
    $attrStr = get_attributes($extra);

    if(!empty($validation['required'])) {
        $return .= "<div class='validation-group $frmGrpClasses'>";
    }
    $return .= "<input type='password' id='".$id."' name='".$name."' class='form-control ".$elementClasses."' value='".$value."' ".$attrStr." />";
    if(!empty($validation['required'])) {
        $return .= "</div>";
    }
    return $return;
}

function form_file($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $attrStr = get_attributes($extra);

    $return .= '<div class="upload_file_div">';
    $return .= '<input type="file" class="form-hide" id="'.$id.'" '.$attrStr.' data-filename="'.$value.'">';
    $return .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
    $return .= '<div><button type="button" class="btn btn-success upload_file" data-trigger="#'.$id.'">
                    <i class="fa fa-upload pa-icon"></i>
                    '.COMMON_UPLOAD_FILE.'
                </button></div>';
    if(!empty($value) && file_exists($extra['data-src-path'].$value) && is_file($extra['data-src-path'].$value)) {
        $return .= '<span id="filepreview_'.$id.'" class="ml-2">';
        if($extra['allow_delete'] != false || !isset($extra['allow_delete']))
            $return .= '<i class="ti-trash delete"></i>';
        $return .= '<img src="'.$extra['data-http-path'].$value.'" width="100" class="image_zoom">
                    </span>';
    } else {
        $return .= '<span id="filepreview_'.$id.'" class="ml-2">
                        <img src="'.DIR_HTTP_IMAGES_COMMON.'no_preview.jpg" width="100">
                    </span>';
    }
    $return .= '</div>';
    return $return;
}

function form_switchbutton($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $elementClasses = $extra['element_class'];
    $attrStr = get_attributes($extra);

    $extra_params = '';
    if($value) {
        $extra_params .= ' checked';
    }
    $return .= '<div class="switch">
                    <input id="'.$id.'" class="cmn-toggle cmn-toggle-round '.$elementClasses.'" '.$extra_params.' type="checkbox" name="'.$name.'" value="1" '.$attrStr.'>
                    <label for="'.$id.'"></label>
                </div>';
    return $return;
}

function form_label($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $elementClasses = $extra['element_class'];
    $attrStr = get_attributes($extra);

    $return .= '<label for="'.$id.'" class="m-0 '.$elementClasses.'" '.$attrStr.'>'.$value.'</label>';
    return $return;
}

function form_select($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $list = $extra['list'];
    $attrStr = get_attributes($extra);
    $elementClasses = $extra['element_class'];

    $return .= '<select class="selectpicker '.$elementClasses.'" '.$extra['attributes'].' id="'.$id.'" name="'.$name.'" '.$attrStr.'">';
    $return .= draw_options($list, $extra['value_field'], $extra['text_field'], $value, $extra['list_before']);
    $return .= '<select>';
    return $return;
}

function form_radio($name, $value = '', $extra = array()) {
    $return = '';

    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $list = $extra['list'];
    $attrStr = get_attributes($extra);
    $elementClasses = $extra['element_class'];

    foreach ($list as $key => $field) {
        $value_field = !isset($extra['value_field']) ? $key : $field[$extra['value_field']];
        $text_field = !isset($extra['text_field']) ? $field : $field[$extra['text_field']];
        $select = ($value == $value_field) ? "checked" : '';
        $return .= '<div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="'.$value_field.'" id="'.$name.'_'.$key.'" name="'.$name.'" class="custom-control-input" '.$select.'>
                        <label class="custom-control-label" for="'.$name.'_'.$key.'">'.$text_field.'</label>
                    </div>';
    }
    return $return;
}

function draw_options($list, $value_field, $text_field, $selected, $before = null) {
    $html = '';
    if(isset($before) || !empty($before)) {
        $html .= $before;
    }
    foreach ($list as $value) {
        $select = ($selected == $value[$value_field]) ? "selected='selected'" : '';
        $html .= "<option value='" . $value[$value_field] . "' $select>" . $value[$text_field] . "</option>";
    }
    return $html;
}

function drawRadio($list, $value_field, $text_field, $selected) {
    $html = '';
    foreach ($list as $value) {
        $select = ($selected == $value[$value_field]) ? "checked" : '';
        $html .= '<div class="custom-control custom-radio">
                    <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" '.$select.'>
                    <label class="custom-control-label" for="customRadio1">'.$text_field.'</label>
                </div>';
    }
    return $html;
}

function form_hidden($name, $value = '', $extra_param = array()) {
    $type = 'hidden';
    $id = !empty($id) ? $extra_param['id'] : $name;
    if($name) {
        return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'">';
    }
}

function form_button($type, $title, $classes, $extra_params = array()) {
    $attrStr = get_attributes($extra_params);
    $return = '<button type="'.$type.'" class="btn '.$classes.'" '.$attrStr.'>'.$title.'</button>';
    return $return;
}

function get_attributes($extra) {
    unset($extra['list']);
    unset($extra['list_before']);
    unset($extra['value_field']);
    unset($extra['text_field']);
    unset($extra['element_class']);
    unset($extra['validation']);
    unset($extra['error']);
    unset($extra['selected']);
    unset($extra['form_group_class']);
    $return = '';
    foreach ($extra as $key => $value) {
        $return .= " $key='$value' ";
    }
    return $return;
}

function getExtraClasses($classname) {
    global $labelColClass;
    if($labelColClass == '') {
        $labelColClass = 3;
    }
    switch ($classname) {
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