<?php

class MenuLinksData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['menu_link_id'] = array('Field', 'menu_link_id', 'menu_link_id', 'int');
		$PropMap['menu_title'] = array('Field', 'menu_title', 'menu_title', 'string');
		$PropMap['page_link'] = array('Field', 'page_link', 'page_link', 'string');
		$PropMap['link_location'] = array('Field', 'link_location', 'link_location', 'string');
		$PropMap['display'] = array('Field', 'display', 'display', 'string');
		$PropMap['icon_class'] = array('Field', 'icon_class', 'icon_class', 'string');
		$PropMap['sort_order'] = array('Field', 'sort_order', 'sort_order', 'int');
		$PropMap['status'] = array('Field', 'status', 'status', 'string');

		return $PropMap;
	}
}
?>