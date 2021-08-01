<?php

class AdminConstantsData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['constant_id'] = array('Field', 'constant_id', 'constant_id', 'int');
		$PropMap['constant_name'] = array('Field', 'constant_name', 'constant_name', 'string');
		$PropMap['section_menu_id'] = array('Field', 'section_menu_id', 'section_menu_id', 'int');
		$PropMap['constant_value'] = array('Field', 'constant_value', 'constant_value', 'string');
		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');

		return $PropMap;
	}
}
?>