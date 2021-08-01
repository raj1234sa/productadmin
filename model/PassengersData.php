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
		$PropMap['status'] = array('Field', 'status', 'status', 'string');

		return $PropMap;
	}
}
?>