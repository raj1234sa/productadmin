<?php

class SiteLanguageData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');
		$PropMap['language_name'] = array('Field', 'language_name', 'language_name', 'string');
		$PropMap['language_flag'] = array('Field', 'language_flag', 'language_flag', 'string');
		$PropMap['status'] = array('Field', 'status', 'status', 'string');

		return $PropMap;
	}
}
?>