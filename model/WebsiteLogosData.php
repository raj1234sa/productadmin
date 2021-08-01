<?php

class WebsiteLogosData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['logo_id'] = array('Field', 'logo_id', 'logo_id', 'int');
		$PropMap['site_logo'] = array('Field', 'site_logo', 'site_logo', 'string');
		$PropMap['site_favicon'] = array('Field', 'site_favicon', 'site_favicon', 'string');

		return $PropMap;
	}
}
?>