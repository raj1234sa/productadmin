<?php

class BusStopsData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['stop_id'] = array('Field', 'stop_id', 'stop_id', 'int');
		$PropMap['city_id'] = array('Field', 'city_id', 'city_id', 'int');
		$PropMap['stop_title'] = array('Field', 'stop_title', 'stop_title', 'string');
		$PropMap['stop_internal_name'] = array('Field', 'stop_internal_name', 'stop_internal_name', 'string');
		$PropMap['stop_image'] = array('Field', 'stop_image', 'stop_image', 'string');
		$PropMap['status'] = array('Field', 'status', 'status', 'string');

		return $PropMap;
	}
}
?>