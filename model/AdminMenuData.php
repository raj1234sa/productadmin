<?php
include_once DIR_WS_MODEL_CLASSES.'RDataModel.php';

class AdminMenuData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['menu_id'] = array('Field', 'menu_id', 'menu_id', 'int');
		$PropMap['section_id'] = array('Field', 'section_id', 'section_id', 'int');
		$PropMap['action_page'] = array('Field', 'action_page', 'action_page', 'int');
		$PropMap['menu_name'] = array('Field', 'menu_name', 'menu_name', 'string');
		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');

		return $PropMap;
	}
}
?>