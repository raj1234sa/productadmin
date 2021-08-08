<?php

class EmailConfigurationData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['email_template_id'] = array('Field', 'email_template_id', 'email_template_id', 'int');
		$PropMap['constant_name'] = array('Field', 'constant_name', 'constant_name', 'string');
		$PropMap['template_subject'] = array('Field', 'template_subject', 'template_subject', 'string');
		$PropMap['status'] = array('Field', 'status', 'status', 'string');
		$PropMap['template_content'] = array('Field', 'template_content', 'template_content', 'string');
		$PropMap['language_id'] = array('Field', 'language_id', 'language_id', 'int');

		return $PropMap;
	}
}
?>