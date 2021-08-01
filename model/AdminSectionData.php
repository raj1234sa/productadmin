<?php

class AdminSectionData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['section_id'] = array('Field', 'section_id', 'section_id', 'int');
		$PropMap['icon_class'] = array('Field', 'icon_class', 'icon_class', 'string');
		$PropMap['page_ids'] = array('Field', 'page_ids', 'page_ids', 'string');
		$PropMap['section_heading'] = array('Field', 'section_heading', 'section_heading', 'string');
		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');

		return $PropMap;
	}
}
?>