<?php
function draw_action_buttons($action_buttons) {
    $html = '<div class="text-right action-buttons-div">';
    foreach ($action_buttons as $key => $item) {
        $html .= "<a href=" . $item['link'] . " class='btn btn-app {$item['class']}'>
                    <i class='{$item['icon']}'></i>
                    $key
                </a>";
    }
    $html .= "</div>";
    return $html;
}

function draw_form_buttons($submit_buttons, $extra = array()) {
    $submit_buttons = explode(',', $submit_buttons);
    $html = '<div class="btn-group pull-right">';
    foreach ($submit_buttons as $key => $value) {
        switch ($value) {
            case 'save':
                $html .= '<button type="submit" name="submit_btn" value="'.COMMON_SAVE.'" class="btn btn-sm btn-info formsubmit" id="formSubmit">
                            <i class="ace-icon far fa-save"></i>
                            '.COMMON_SAVE.'
                        </button>';
                break;
            case 'back':
                if (isset($extra['backUrl']) && !empty($extra['backUrl']))
                    $html .= '<a class="btn btn-sm" href="' . $extra['backUrl'] . '">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            Back
                        </a>';
                break;
            case 'save_back':
                $html .= '<button type="submit" value="'.COMMON_SAVE.'" name="submit_btn" class="btn btn-sm btn-info formsubmit" id="formSubmitBack">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            '.COMMON_SAVE_AND_BACK.'
                        </button>';
                break;
        }
    }
    $html .= '<button class="btn btn-sm" id="formReset">
                <i class="ace-icon fa fa-history"></i>
                '.COMMON_RESET.'
            </button>';
    $html .= '</div>';
    return $html;
}

function draw_action_menu($action_links) {
    $html = '';
    if(count($action_links) > 3) {
        $html .= '<div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle" aria-expanded="false">
                        Action
                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-info dropdown-menu-right action-menu">';
        foreach ($action_links as $key => $value) {
            $class = '';
            if (isset($value['class'])) {
                $class = $value['class'];
            }
            $html .= "<li>
                        <a href='{$value['link']}' class='$class'><i class='{$value['icon']}'></i>$key</a>
                    </li>";
        }
        $html .= '</ul></div>';
    } else {
        foreach ($action_links as $key => $value) {
            $class = '';
            if (isset($value['compact_class'])) {
                $class = $value['compact_class'];
            }
            $html .= "<a href='{$value['link']}' class='btn btn-white btn-bold $class'>
            <i class='{$value['icon']}'></i></a>";
        }
    }
    return $html;
}

function form_element($label, $type='text', $name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    // $element_classes = $frm_grp_classes = '';

    // $validation = $extra['validation'];
    // $validationText = '';

    $frm_grp_classes = $validationText = '';
    if(!empty($validation)) {
        foreach ($validation as $data => $msg) {
            $validationText .= " data-validate-$data='$msg'";
        }
        $frm_grp_classes .= 'validation-div';
    }
    if(!empty($extra['error'])) {
        $validationText .= " data-error.'".$extra['error']."'";
    }

    $return .= '<div class="form-group '.$frm_grp_classes.'" '.$validationText.'>';
    if(!empty($label)) {
        $return .= '<label for="'.$id.'">'.$label.'</label>';
    }
    $attr_str = get_attributes($extra);
    switch ($type) {
        case 'text':
        case 'password':
        case 'number':
            // $extra_params = '';
            // if($type == 'number') {
            //     $extra_params .= 'data-type="number" class="only-number"';
            //     $type = 'text';
            // }
            // $return .= '<input type="'.$type.'" id="'.$id.'" '.$extra_params.' name="'.$name.'" class="form-control '.$element_classes.'" value="'.$value.'" '.$attr_str.' />';
            $return .= form_text(func_get_arg(1), func_get_arg(2), func_get_arg(3), $extra);
            break;
        case 'switchbutton':
            // $return .= '<div class="col-xs-3">
            //                 <label class="switch-radio">
            //                     <input id="'.$id.'" name="'.$name.'" '.$extra_params.' class="ace ace-switch ace-switch-6" type="checkbox" '.$attr_str.' />
            //                     <span class="lbl"></span>
            //                 </label>
            //             </div>';
            // $return .= '</div>';
            $return .= form_switchbutton(func_get_arg(2), func_get_arg(3), $extra);
            break;
        case 'select':
            $list = $extra['list'];
            $return .= '<div class="col-sm-5">
                            <select class="chosen-select" '.$extra['attributes'].' id="'.$id.'" name="'.$name.' '.$attr_str.'">';
            $return .= $extra['list_before'];
            foreach ($list as $option) {
                $selected = $value == $option[$extra['value_field']] ? 'selected' : '';
                $return .= "<option $selected value='".$option[$extra['value_field']]."'>".$option[$extra['text_field']]."</option>";
            }
            $return .= '    </select>
                        </div>';
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
            $return .= '<textarea id="'.$id.'" name="'.$name.'" class="form-control '.$extra_class.'" '.$rows.' '.$cols.' '.$attr_str.'>'.$value.'</textarea>';
            $return .= '</div>';
            break;
        case 'file':
            $return .= '<input type="file" class="form-hide" id="'.$id.'" name="'.$name.'">';
            $return .= '<div class="col-sm-5 upload_file_div">';
            $return .= '<button type="button" class="btn btn-xs btn-success upload_file" data-trigger="#'.$id.'">
                            <i class="ace-icon fas fa-upload bigger-110"></i>
                            Upload File
                        </button>';
            $return .= '</div>';
            break;
        case 'datepicker':
            $return .= '<div class="col-sm-5">';
            $return .= '<div class="input-group col-xs-10 col-sm-5">';
            $return .= '<input class="form-control date-picker '.$element_classes.'" id="'.$id.'" name="'.$name.'" type="text" data-date-format="'.SITE_DATE_FORMAT.'" value="'.$value.'" '.$attr_str.' />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>';
            $return .= '</div>';
            $return .= '</div>';
            break;
    }
    $return .= '</div>';
    return $return;
}

// function form_switchbutton($name, $value = '', $extra = array()) {
//     $return = '';
    
//     $id = $name;
//     if(!empty($extra['id'])) {
//         $id = $extra['id'];
//     }

//     $element_classes = $extra['class'];
//     $extra_params = '';
//     if(!empty($value)) {
//         $extra_params .= ' checked';
//     }
//     $extra_params .= $extra['params'];
//     $return .= '<label class="switch-radio">
//                     <input id="'.$id.'" name="'.$name.'" '.$extra_params.' class="ace ace-switch ace-switch-6 '.$element_classes.'" type="checkbox" />
//                     <span class="lbl"></span>
//                 </label>';
//     return $return;
// }

function form_text($type='text', $name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }

    $element_classes = $frm_grp_classes = '';

    $validation = $extra['validation'];
    $validationText = '';

    if(!empty($validation)) {
        foreach ($validation as $data => $msg) {
            $validationText .= " data-validate-$data='$msg'";
        }
        $frm_grp_classes .= 'validation-div';
    }
    if(!empty($extra['error'])) {
        $validationText .= " data-error.'".$extra['error']."'";
    }
    $element_classes = $extra['element_classes'];
    $attr_str = get_attributes($extra);

    $extra_params = '';
    if($type == 'number') {
        $extra_params .= 'data-type="number" class="only-number"';
        $type = 'text';
    }
    $return .= '<input type="'.$type.'" id="'.$id.'" '.$extra_params.' name="'.$name.'" class="form-control '.$element_classes.'" value="'.$value.'" '.$attr_str.' />';
    return $return;
}

function form_switchbutton($name, $value = '', $extra = array()) {
    $return = '';
    
    $id = $name;
    if(!empty($extra['id'])) {
        $id = $extra['id'];
    }
    $element_classes = $extra['element_classes'];
    $attr_str = get_attributes($extra);

    $extra_params = '';
    if($value) {
        $extra_params .= ' checked';
    }
    $return .= '<div class="switch">
                    <input id="'.$id.'" class="cmn-toggle cmn-toggle-round '.$element_classes.'" '.$extra_params.' type="checkbox" name="'.$name.'" '.$attr_str.'>
                    <label for="'.$id.'"></label>
                </div>';
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

function form_hidden($name, $value = '', $extra_param = array()) {
    $type = 'hidden';
    $id = !empty($id) ? $extra_param['id'] : $name;
    if($name) {
        return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'">';
    }
}

function form_button($type, $title, $classes, $extra_params = array()) {
    $attr_str = get_attributes($extra_params);
    $return = '<button type="'.$type.'" class="btn '.$classes.'" '.$attr_str.'>'.$title.'</button>';
    return $return;
}

function get_attributes($extra) {
    $return = '';
    foreach ($extra as $key => $value) {
        $return .= " $key=$value ";
    }
    return $return;
}