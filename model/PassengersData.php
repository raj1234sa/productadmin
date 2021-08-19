<?php

class PassengersData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['passenger_id'] = array('Field', 'passenger_id', 'passenger_id', 'int');
		$PropMap['firstname'] = array('Field', 'firstname', 'firstname', 'string');
		$PropMap['lastname'] = array('Field', 'lastname', 'lastname', 'string');
		$PropMap['email'] = array('Field', 'email', 'email', 'string');
		$PropMap['phone'] = array('Field', 'phone', 'phone', 'string');
		$PropMap['password'] = array('Field', 'password', 'password', 'string');
		$PropMap['address_line'] = array('Field', 'address_line', 'address_line', 'string');
		$PropMap['address_line2'] = array('Field', 'address_line2', 'address_line2', 'string');
		$PropMap['country'] = array('Field', 'country', 'country', 'int');
		$PropMap['state'] = array('Field', 'state', 'state', 'int');
		$PropMap['city'] = array('Field', 'city', 'city', 'int');
		$PropMap['bus_stop_id'] = array('Field', 'bus_stop_id', 'bus_stop_id', 'int');
		$PropMap['area_name'] = array('Field', 'area_name', 'area_name', 'string');
		$PropMap['zipcode'] = array('Field', 'zipcode', 'zipcode', 'string');
		$PropMap['status'] = array('Field', 'status', 'status', 'string');

		return $PropMap;
	}
}
?>