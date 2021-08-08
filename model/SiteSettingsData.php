<?php

class SiteSettingsData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['setting_id'] = array('Field', 'setting_id', 'setting_id', 'int');
		$PropMap['setting_constant'] = array('Field', 'setting_constant', 'setting_constant', 'string');
		$PropMap['visible'] = array('Field', 'visible', 'visible', 'string');
		$PropMap['section_name'] = array('Field', 'section_name', 'section_name', 'int');
		$PropMap['input_type'] = array('Field', 'input_type', 'input_type', 'string');
		$PropMap['possible_values'] = array('Field', 'possible_values', 'possible_values', 'string');
		$PropMap['default_value'] = array('Field', 'default_value', 'default_value', 'string');
		$PropMap['set_value'] = array('Field', 'set_value', 'set_value', 'string');
		$PropMap['title'] = array('Field', 'title', 'title', 'string');
		$PropMap['help'] = array('Field', 'help', 'help', 'string');
		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');

		return $PropMap;
	}
}
?>