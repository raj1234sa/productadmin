<?php

class AdminPagesData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['page_id'] = array('Field', 'page_id', 'page_id', 'int');
		$PropMap['page_name'] = array('Field', 'page_name', 'page_name', 'string');

		return $PropMap;
	}
}
?>